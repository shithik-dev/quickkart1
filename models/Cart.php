<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Cart model handling cart operations.
 */
class Cart {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Add item to cart or update quantity if exists.
     */
    public function addToCart($userId, $productId, $quantity = 1) {
        $stmt = $this->conn->prepare("SELECT id, quantity FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
        $existing = $stmt->fetch();
        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $update = $this->conn->prepare("UPDATE cart SET quantity = :qty WHERE id = :id");
            return $update->execute([':qty' => $newQty, ':id' => $existing['id']]);
        }
        $insert = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
        return $insert->execute([':user_id' => $userId, ':product_id' => $productId, ':quantity' => $quantity]);
    }

    /**
     * Update cart item quantity.
     */
    public function updateQuantity($cartId, $quantity, $userId) {
        $stmt = $this->conn->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([':quantity' => $quantity, ':id' => $cartId, ':user_id' => $userId]);
    }

    /**
     * Remove item from cart.
     */
    public function removeItem($cartId, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([':id' => $cartId, ':user_id' => $userId]);
    }

    /**
     * Get user cart with product details.
     */
    public function getUserCart($userId) {
        $sql = "SELECT c.id as cart_id, c.quantity, p.*
                FROM cart c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Clear cart after order placement.
     */
    public function clearCart($userId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
        return $stmt->execute([':user_id' => $userId]);
    }
}

