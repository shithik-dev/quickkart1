<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Order model to manage order creation and retrieval.
 */
class Order {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Get the database connection.
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Create order with items in a transaction.
     */
    public function createOrder($userId, $cartItems, $totalAmount) {
        try {
            error_log("=== Order Creation Started ===");
            error_log("User ID: $userId, Total: $totalAmount, Items: " . count($cartItems));
            
            if (!$this->conn) {
                throw new Exception("Database connection is null");
            }
            
            // Don't use transaction - do it step by step
            // Insert order
            $sql = "INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'Pending', NOW())";
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . implode(", ", $this->conn->errorInfo()));
            }
            
            error_log("Executing: INSERT order with user_id=$userId, total=$totalAmount");
            $result = $stmt->execute([$userId, $totalAmount]);
            
            if (!$result) {
                throw new Exception("Execute failed: " . implode(", ", $stmt->errorInfo()));
            }
            
            $orderId = $this->conn->lastInsertId();
            error_log("Order created with ID: $orderId");
            
            if (!$orderId) {
                throw new Exception("No order ID returned");
            }
            
            // Insert order items
            foreach ($cartItems as $item) {
                error_log("Inserting order item - Product: {$item['id']}, Qty: {$item['quantity']}, Price: {$item['price']}");
                
                $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                
                if (!$stmt) {
                    throw new Exception("Prepare item failed: " . implode(", ", $this->conn->errorInfo()));
                }
                
                $result = $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
                
                if (!$result) {
                    throw new Exception("Insert item failed: " . implode(", ", $stmt->errorInfo()));
                }
                
                error_log("Order item inserted for product {$item['id']}");
                
                // Update stock
                $sql = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
                $stmt = $this->conn->prepare($sql);
                
                if (!$stmt) {
                    throw new Exception("Prepare stock failed");
                }
                
                $result = $stmt->execute([$item['quantity'], $item['id'], $item['quantity']]);
                
                if (!$result) {
                    throw new Exception("Stock update failed: " . implode(", ", $stmt->errorInfo()));
                }
                
                error_log("Stock updated for product {$item['id']}");
            }
            
            error_log("=== Order Creation Successful - Order ID: $orderId ===");
            return $orderId;
            
        } catch (Exception $e) {
            error_log("=== Order Creation Failed ===");
            error_log("Error: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Retrieve user orders with items.
     */
    public function getOrdersByUser($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute([':user_id' => $userId]);
        $orders = $stmt->fetchAll();
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }
        return $orders;
    }

    /**
     * Fetch items for an order.
     */
    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare("SELECT oi.*, p.product_name, p.image FROM order_items oi
                                      JOIN products p ON oi.product_id = p.id
                                      WHERE oi.order_id = :order_id");
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    /**
     * Admin get all orders.
     */
    public function getAllOrders() {
        $stmt = $this->conn->query("SELECT o.*, u.full_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
        $orders = $stmt->fetchAll();
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }
        return $orders;
    }

    /**
     * Update order status.
     */
    public function updateStatus($orderId, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $orderId]);
    }
}

