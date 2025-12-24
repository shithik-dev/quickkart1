<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';

/**
 * Controller for admin operations.
 */
class AdminController {
    private $userModel;
    private $productModel;
    private $orderModel;

    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Simple admin check; extend with roles as needed.
     */
    private function requireAdmin() {
        if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            header("Location: ../login.php");
            exit;
        }
    }

    /**
     * Handle admin login using existing users table.
     */
    public function login() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if (!$email || !$password) {
                $errors[] = "Email and password are required.";
            } else {
                // Only allow the fixed admin credential
                if ($email === 'adminquickkart@gmail.com' && $password === 'admin@123') {
                    $_SESSION['user_id'] = 0;
                    $_SESSION['user_name'] = 'Admin';
                    $_SESSION['is_admin'] = true;
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $errors[] = "Invalid admin credentials.";
                }
            }
        }
        return $errors;
    }

    /**
     * Dashboard stats.
     */
    public function dashboardData() {
        $this->requireAdmin();
        return [
            'products' => count($this->productModel->getProducts()),
            'categories' => count($this->productModel->getCategories()),
            'orders' => count($this->orderModel->getAllOrders()),
        ];
    }

    public function products() {
        $this->requireAdmin();
        return $this->productModel->getProducts();
    }

    public function categories() {
        $this->requireAdmin();
        return $this->productModel->getCategories();
    }

    public function orders() {
        $this->requireAdmin();
        return $this->orderModel->getAllOrders();
    }

    public function updateOrderStatus() {
        $this->requireAdmin();
        $orderId = intval($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? 'Pending';
        if ($orderId) {
            $this->orderModel->updateStatus($orderId, $status);
        }
        header("Location: orders.php");
        exit;
    }

    public function clearAllOrders() {
        $this->requireAdmin();
        // Delete all order items first, then orders
        $conn = $this->orderModel->getConnection();
        try {
            $conn->exec("DELETE FROM order_items");
            $conn->exec("DELETE FROM orders");
        } catch (Exception $e) {
            error_log("Error clearing orders: " . $e->getMessage());
        }
        header("Location: orders.php");
        exit;
    }
}

