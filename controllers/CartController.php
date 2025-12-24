<?php
require_once __DIR__ . '/../models/Cart.php';

/**
 * Controller for cart operations.
 */
class CartController {
    private $cartModel;

    public function __construct() {
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
     * Add product to cart.
     */
    public function add() {
        $this->requireAuth();
        $productId = intval($_POST['product_id'] ?? 0);
        $quantity = max(1, intval($_POST['quantity'] ?? 1));
        if ($productId) {
            $this->cartModel->addToCart($_SESSION['user_id'], $productId, $quantity);
        }
        header("Location: cart.php");
        exit;
    }

    /**
     * Update cart quantities.
     */
    public function update() {
        $this->requireAuth();
        if (!empty($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $cartId => $qty) {
                $qty = max(1, intval($qty));
                $this->cartModel->updateQuantity($cartId, $qty, $_SESSION['user_id']);
            }
        }
        header("Location: cart.php");
        exit;
    }

    /**
     * Remove cart item.
     */
    public function remove() {
        $this->requireAuth();
        $cartId = intval($_GET['id'] ?? 0);
        if ($cartId) {
            $this->cartModel->removeItem($cartId, $_SESSION['user_id']);
        }
        header("Location: cart.php");
        exit;
    }

    /**
     * Get cart data for view.
     */
    public function view() {
        $this->requireAuth();
        return $this->cartModel->getUserCart($_SESSION['user_id']);
    }
}

