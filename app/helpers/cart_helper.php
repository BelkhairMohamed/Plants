<?php
/**
 * Cart Helper Functions
 */

/**
 * Get cart count (total quantity of items)
 * Works for both logged-in users (from DB) and guests (from session)
 */
function getCartCount() {
    if (!isset($_SESSION)) {
        return 0;
    }
    
    // If user is logged in, try to get from database first
    if (isset($_SESSION['user_id']) && class_exists('Cart')) {
        try {
            $cartModel = new Cart();
            $count = $cartModel->getCartCount($_SESSION['user_id']);
            // Also update session for consistency
            if ($count > 0) {
                $_SESSION['cart'] = $cartModel->toSessionFormat($_SESSION['user_id']);
            }
            return $count;
        } catch (Exception $e) {
            // Fallback to session if DB fails
        }
    }
    
    // Fallback: calculate from session
    $cartCount = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (isset($item['quantity']) && is_numeric($item['quantity'])) {
                $cartCount += intval($item['quantity']);
            }
        }
    }
    
    return $cartCount;
}

