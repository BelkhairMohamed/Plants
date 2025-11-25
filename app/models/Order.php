<?php
/**
 * Order Model
 */

class Order {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function create($userId, $items, $shippingAddress = null) {
        $this->db->beginTransaction();
        
        try {
            // Calculate total
            $total = 0;
            $productModel = new Product();
            
            foreach ($items as $item) {
                $product = $productModel->findById($item['product_id']);
                if (!$product || $product['stock'] < $item['quantity']) {
                    throw new Exception("Insufficient stock for product ID: " . $item['product_id']);
                }
                $total += $product['price'] * $item['quantity'];
            }
            
            // Create order
            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, total_amount, shipping_address, status) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $total, $shippingAddress, ORDER_STATUS_PENDING]);
            $orderId = $this->db->lastInsertId();
            
            // Create order items and update stock
            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, unit_price) 
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($items as $item) {
                $product = $productModel->findById($item['product_id']);
                $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $product['price']]);
                
                // Update stock
                $productModel->updateStock($item['product_id'], -$item['quantity']);
            }
            
            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function findByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM orders 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getOrderItems($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name, p.image_url
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
    
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}




