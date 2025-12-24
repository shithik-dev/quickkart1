<?php
require_once __DIR__ . '/../models/User.php';

/**
 * Authentication controller for signup, login, and logout.
 */
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Handle user registration.
     */
    public function signup() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            // Validation
            if (!$full_name || !$email || !$password || !$confirm_password) {
                $errors[] = "All required fields must be filled.";
            }
            if ($password !== $confirm_password) {
                $errors[] = "Passwords do not match.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email address.";
            }
            if ($this->userModel->findByEmail($email)) {
                $errors[] = "Email already registered.";
            }

            if (empty($errors)) {
                $created = $this->userModel->create([
                    'full_name' => htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8'),
                    'email' => $email,
                    'password' => $password,
                    'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
                    'address' => htmlspecialchars($address, ENT_QUOTES, 'UTF-8'),
                ]);
                if ($created) {
                    header("Location: login.php?success=1");
                    exit;
                } else {
                    $errors[] = "Registration failed. Please try again.";
                }
            }
        }
        return $errors;
    }

    /**
     * Handle user login.
     */
    public function login() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if (!$email || !$password) {
                $errors[] = "Email and password are required.";
            } else {
                // Dedicated admin credential (bypass DB)
                if ($email === 'adminquickkart@gmail.com' && $password === 'admin@123') {
                    $_SESSION['user_id'] = 0;
                    $_SESSION['user_name'] = 'Admin';
                    $_SESSION['is_admin'] = true;
                    header("Location: admin/dashboard.php");
                    exit;
                }

                // Standard user auth via DB
                $user = $this->userModel->findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['is_admin'] = false;
                    header("Location: index.php");
                    exit;
                } else {
                    $errors[] = "Invalid credentials.";
                }
            }
        }
        return $errors;
    }

    /**
     * Destroy session for logout.
     */
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }
}

