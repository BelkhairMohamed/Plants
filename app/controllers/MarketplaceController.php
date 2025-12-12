<?php
/**
 * Marketplace Controller
 */

class MarketplaceController extends Controller {
    private $productModel;
    private $orderModel;
    private $userPlantModel;
    private $cartModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->userPlantModel = new UserPlant();
        $this->cartModel = new Cart();
    }
    
    /**
     * Get cart from database (if logged in) or session (if not logged in)
     */
    private function getCart() {
        if (isset($_SESSION['user_id'])) {
            // User is logged in - get from database
            return $this->cartModel->toSessionFormat($_SESSION['user_id']);
        } else {
            // User not logged in - get from session
            return $_SESSION['cart'] ?? [];
        }
    }
    
    /**
     * Save cart to database (if logged in) or session (if not logged in)
     */
    private function saveCartItem($productId, $quantity) {
        if (isset($_SESSION['user_id'])) {
            // User is logged in - save to database
            return $this->cartModel->addItem($_SESSION['user_id'], $productId, $quantity);
        } else {
            // User not logged in - save to session
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $found = false;
            foreach ($_SESSION['cart'] as $key => &$item) {
                if (isset($item['product_id']) && $item['product_id'] == $productId) {
                    $item['quantity'] = (isset($item['quantity']) ? intval($item['quantity']) : 0) + $quantity;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $product = $this->productModel->findById($productId);
                if ($product) {
                    $_SESSION['cart'][] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image_url' => $product['image_url']
                    ];
                }
            }
            return true;
        }
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
        
        // Get product images
        $images = $this->productModel->getImages($id);
        
        // If no images in product_images table, use image_url as fallback
        if (empty($images) && !empty($product['image_url'])) {
            $images = [$product['image_url']];
        }
        
        $product['images'] = $images;
        
        $this->view('marketplace/detail', ['product' => $product]);
    }
    
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = intval($_POST['product_id'] ?? 0);
            $quantity = max(1, intval($_POST['quantity'] ?? 1));
            
            if ($productId) {
                $product = $this->productModel->findById($productId);
                if ($product && $product['stock'] >= $quantity) {
                    $this->saveCartItem($productId, $quantity);
                    
                    // If user is logged in, also update session cart for immediate display
                    if (isset($_SESSION['user_id'])) {
                        $_SESSION['cart'] = $this->cartModel->toSessionFormat($_SESSION['user_id']);
                    }
                }
            }
        }
        
        $this->redirect('/?controller=marketplace&action=cart');
    }
    
    public function cart() {
        $cart = $this->getCart();
        $total = 0;
        
        // Get product stock info for validation
        $products = [];
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            if (isset($item['product_id'])) {
                $product = $this->productModel->findById($item['product_id']);
                if ($product) {
                    $products[$item['product_id']] = $product;
                }
            }
        }
        
        $error = $_GET['error'] ?? null;
        $errorMessage = '';
        if ($error === 'stock_insufficient') {
            $errorMessage = 'La quantité demandée dépasse le stock disponible.';
        }
        
        $this->view('marketplace/cart', [
            'cart' => $cart,
            'total' => $total,
            'products' => $products,
            'error' => $errorMessage
        ]);
    }
    
    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = intval($_POST['product_id'] ?? 0);
            $quantity = max(0, intval($_POST['quantity'] ?? 0));
            
            if ($productId) {
                $product = $this->productModel->findById($productId);
                
                if ($product) {
                    // Check stock availability
                    if ($quantity > $product['stock']) {
                        $this->redirect('/?controller=marketplace&action=cart&error=stock_insufficient');
                        return;
                    }
                    
                    if (isset($_SESSION['user_id'])) {
                        // User is logged in - update in database
                        if ($quantity > 0) {
                            $this->cartModel->updateQuantity($_SESSION['user_id'], $productId, $quantity);
                        } else {
                            $this->cartModel->removeItem($_SESSION['user_id'], $productId);
                        }
                        // Update session cart for immediate display
                        $_SESSION['cart'] = $this->cartModel->toSessionFormat($_SESSION['user_id']);
                    } else {
                        // User not logged in - update in session
                        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                            $_SESSION['cart'] = [];
                        }
                        
                        $found = false;
                        foreach ($_SESSION['cart'] as $key => &$item) {
                            if (isset($item['product_id']) && $item['product_id'] == $productId) {
                                if ($quantity > 0) {
                                    $item['quantity'] = $quantity;
                                } else {
                                    unset($_SESSION['cart'][$key]);
                                    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
                                }
                                $found = true;
                                break;
                            }
                        }
                        
                        if (!$found && $quantity > 0) {
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
        }
        
        $this->redirect('/?controller=marketplace&action=cart');
    }
    
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $index = intval($_POST['index'] ?? -1);
            
            if (isset($_SESSION['user_id'])) {
                // User is logged in - remove from database
                $this->cartModel->removeItemByIndex($_SESSION['user_id'], $index);
                // Update session cart for immediate display
                $_SESSION['cart'] = $this->cartModel->toSessionFormat($_SESSION['user_id']);
            } else {
                // User not logged in - remove from session
                if ($index >= 0 && isset($_SESSION['cart'][$index])) {
                    unset($_SESSION['cart'][$index]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
                }
            }
        }
        
        $this->redirect('/?controller=marketplace&action=cart');
    }
    
    public function checkout() {
        $this->requireAuth();
        
        $cart = $this->getCart();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                
                // Clear cart from database
                $this->cartModel->clearCart($_SESSION['user_id']);
                $_SESSION['cart'] = []; // Also clear session cart
                
                $this->redirect('/?controller=marketplace&action=orderSuccess&id=' . $orderId);
            } catch (Exception $e) {
                $error = 'Order failed: ' . $e->getMessage();
                $this->view('marketplace/cart', ['cart' => $cart, 'error' => $error]);
            }
        } else {
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
    
    /**
     * Get cart count via AJAX
     */
    public function getCartCount() {
        $cartCount = 0;
        
        if (isset($_SESSION['user_id'])) {
            // User is logged in - get from database
            try {
                $cartCount = $this->cartModel->getCartCount($_SESSION['user_id']);
                // Update session for consistency
                if ($cartCount > 0) {
                    $_SESSION['cart'] = $this->cartModel->toSessionFormat($_SESSION['user_id']);
                } else {
                    $_SESSION['cart'] = [];
                }
            } catch (Exception $e) {
                // Fallback to session
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        if (isset($item['quantity']) && is_numeric($item['quantity'])) {
                            $cartCount += intval($item['quantity']);
                        }
                    }
                }
            }
        } else {
            // User not logged in - get from session
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    if (isset($item['quantity']) && is_numeric($item['quantity'])) {
                        $cartCount += intval($item['quantity']);
                    }
                }
            }
        }
        
        $this->json(['count' => $cartCount]);
    }
}



