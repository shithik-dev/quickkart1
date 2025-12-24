<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';

/**
 * Controller handling checkout and orders.
 */
class OrderController {
    private $orderModel;
    private $cartModel;

    public function __construct() {
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Ensure user is authenticated.
     */
    private function requireAuth() {
        if (empty($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }
    }

    /**
     * Process checkout and return order ID or false.
     */
    public function checkout() {
        error_log("=== Checkout Started ===");
        $this->requireAuth();
        
        // Get cart items
        $cartItems = $this->cartModel->getUserCart($_SESSION['user_id']);
        error_log("Cart items count: " . count($cartItems));
        
        if (empty($cartItems)) {
            error_log("Cart is empty");
            return false;
        }
        
        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        error_log("Total calculated: $total");
        
        // Create order
        $orderId = $this->orderModel->createOrder($_SESSION['user_id'], $cartItems, $total);
        error_log("Order creation returned: $orderId");
        
        if ($orderId) {
            // Clear cart after successful order
            $this->cartModel->clearCart($_SESSION['user_id']);
            error_log("Cart cleared for user " . $_SESSION['user_id']);
            return $orderId;
        }
        
        error_log("Order creation failed");
        return false;
    }

    /**
     * Get orders for current user.
     */
    public function myOrders() {
        $this->requireAuth();
        return $this->orderModel->getOrdersByUser($_SESSION['user_id']);
    }
}

