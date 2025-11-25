<?php
/**
 * Marketplace Controller
 */

class MarketplaceController extends Controller {
    private $productModel;
    private $orderModel;
    private $userPlantModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->userPlantModel = new UserPlant();
    }
    
    public function index() {
        $filters = [
            'category' => $_GET['category'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $products = $this->productModel->getAll($filters, $limit, $offset);
        
        $this->view('marketplace/index', [
            'products' => $products,
            'filters' => $filters,
            'currentPage' => $page
        ]);
    }
    
    public function detail() {
        $id = intval($_GET['id'] ?? 0);
        
        if (!$id) {
            $this->redirect('/?controller=marketplace&action=index');
            return;
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            http_response_code(404);
            require_once VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        $this->view('marketplace/detail', ['product' => $product]);
    }
    
    public function addToCart() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = intval($_POST['product_id'] ?? 0);
            $quantity = max(1, intval($_POST['quantity'] ?? 1));
            
            if ($productId) {
                $product = $this->productModel->findById($productId);
                if ($product && $product['stock'] >= $quantity) {
                    // Check if already in cart
                    $found = false;
                    foreach ($_SESSION['cart'] as &$item) {
                        if ($item['product_id'] == $productId) {
                            $item['quantity'] += $quantity;
                            $found = true;
                            break;
                        }
                    }
                    
                    if (!$found) {
                        $_SESSION['cart'][] = [
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'image_url' => $product['image_url']
                        ];
                    }
                }
            }
        }
        
        $this->redirect('/?controller=marketplace&action=cart');
    }
    
    public function cart() {
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        $this->view('marketplace/cart', [
            'cart' => $cart,
            'total' => $total
        ]);
    }
    
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $index = intval($_POST['index'] ?? -1);
            
            if ($index >= 0 && isset($_SESSION['cart'][$index])) {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
            }
        }
        
        $this->redirect('/?controller=marketplace&action=cart');
    }
    
    public function checkout() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = $_SESSION['cart'] ?? [];
            $shippingAddress = $_POST['shipping_address'] ?? '';
            
            if (empty($cart)) {
                $this->redirect('/?controller=marketplace&action=cart');
                return;
            }
            
            try {
                // Convert cart to order items format
                $items = [];
                foreach ($cart as $item) {
                    $items[] = [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity']
                    ];
                }
                
                // Create order
                $orderId = $this->orderModel->create($_SESSION['user_id'], $items, $shippingAddress);
                
                // Add plants/seeds to user collection if applicable
                foreach ($cart as $item) {
                    $product = $this->productModel->findById($item['product_id']);
                    if ($product && ($product['category'] === 'plant' || $product['category'] === 'seed') && $product['related_plant_catalog_id']) {
                        // Check if user already has this plant
                        if (!$this->userPlantModel->userOwnsPlant($_SESSION['user_id'], $product['related_plant_catalog_id'])) {
                            $this->userPlantModel->create([
                                'user_id' => $_SESSION['user_id'],
                                'plant_catalog_id' => $product['related_plant_catalog_id'],
                                'is_from_marketplace' => true,
                                'acquisition_type' => $product['category'] === 'seed' ? 'seed' : 'plant',
                                'purchase_date' => date('Y-m-d')
                            ]);
                        }
                    }
                }
                
                // Clear cart
                $_SESSION['cart'] = [];
                
                $this->redirect('/?controller=marketplace&action=orderSuccess&id=' . $orderId);
            } catch (Exception $e) {
                $error = 'Order failed: ' . $e->getMessage();
                $this->view('marketplace/cart', ['cart' => $cart, 'error' => $error]);
            }
        } else {
            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)) {
                $this->redirect('/?controller=marketplace&action=cart');
                return;
            }
            
            $this->view('marketplace/checkout', ['cart' => $cart]);
        }
    }
    
    public function orderSuccess() {
        $this->requireAuth();
        
        $orderId = intval($_GET['id'] ?? 0);
        $order = $this->orderModel->findById($orderId);
        
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/?controller=marketplace&action=index');
            return;
        }
        
        $items = $this->orderModel->getOrderItems($orderId);
        
        $this->view('marketplace/order_success', [
            'order' => $order,
            'items' => $items
        ]);
    }
    
    public function orders() {
        $this->requireAuth();
        
        $orders = $this->orderModel->findByUserId($_SESSION['user_id']);
        
        $this->view('marketplace/orders', ['orders' => $orders]);
    }
}



