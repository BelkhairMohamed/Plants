<?php
/**
 * Cart Model - Persistent Shopping Cart
 */

class Cart {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    /**
     * Get all cart items for a user
     */
    public function getCartItems($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    ci.id,
                    ci.product_id,
                    ci.quantity,
                    p.name,
                    p.price,
                    p.image_url,
                    p.stock
                FROM cart_items ci
                INNER JOIN products p ON ci.product_id = p.id
                WHERE ci.user_id = ?
                ORDER BY ci.created_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Table might not exist yet, return empty array
            return [];
        }
    }
    
    /**
     * Add or update item in cart
     */
    public function addItem($userId, $productId, $quantity = 1) {
        try {
            // Check if item already exists
            $stmt = $this->db->prepare("
                SELECT id, quantity FROM cart_items 
                WHERE user_id = ? AND product_id = ?
            ");
            $stmt->execute([$userId, $productId]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                // Update quantity
                $newQuantity = $existing['quantity'] + $quantity;
                $stmt = $this->db->prepare("
                    UPDATE cart_items 
                    SET quantity = ?, updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                return $stmt->execute([$newQuantity, $existing['id']]);
            } else {
                // Insert new item
                $stmt = $this->db->prepare("
                    INSERT INTO cart_items (user_id, product_id, quantity)
                    VALUES (?, ?, ?)
                ");
                return $stmt->execute([$userId, $productId, $quantity]);
            }
        } catch (PDOException $e) {
            // Table might not exist yet, return false
            return false;
        }
    }
    
    /**
     * Update item quantity
     */
    public function updateQuantity($userId, $productId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($userId, $productId);
        }
        
        $stmt = $this->db->prepare("
            UPDATE cart_items 
            SET quantity = ?, updated_at = CURRENT_TIMESTAMP
            WHERE user_id = ? AND product_id = ?
        ");
        return $stmt->execute([$quantity, $userId, $productId]);
    }
    
    /**
     * Remove item from cart
     */
    public function removeItem($userId, $productId) {
        $stmt = $this->db->prepare("
            DELETE FROM cart_items 
            WHERE user_id = ? AND product_id = ?
        ");
        return $stmt->execute([$userId, $productId]);
    }
    
    /**
     * Remove item by index (for compatibility with session-based cart)
     */
    public function removeItemByIndex($userId, $index) {
        $items = $this->getCartItems($userId);
        if (isset($items[$index])) {
            return $this->removeItem($userId, $items[$index]['product_id']);
        }
        return false;
    }
    
    /**
     * Clear entire cart for a user
     */
    public function clearCart($userId) {
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
    
    /**
     * Get cart count (total quantity of items)
     */
    public function getCartCount($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT SUM(quantity) as total 
                FROM cart_items 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($result['total'] ?? 0);
        } catch (PDOException $e) {
            // Table might not exist yet, return 0
            return 0;
        }
    }
    
    /**
     * Convert cart items to session format (for compatibility)
     */
    public function toSessionFormat($userId) {
        try {
            $items = $this->getCartItems($userId);
            $sessionCart = [];
            
            foreach ($items as $item) {
                $sessionCart[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'image_url' => $item['image_url']
                ];
            }
            
            return $sessionCart;
        } catch (Exception $e) {
            // Return empty array if error
            return [];
        }
    }
    
    /**
     * Sync session cart to database (when user logs in)
     */
    public function syncFromSession($userId, $sessionCart) {
        if (empty($sessionCart) || !is_array($sessionCart)) {
            return;
        }
        
        foreach ($sessionCart as $item) {
            if (isset($item['product_id']) && isset($item['quantity'])) {
                $this->addItem($userId, $item['product_id'], $item['quantity']);
            }
        }
    }
}

