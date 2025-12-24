<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Cart.php';
$cartModel = new Cart();
$userId = intval($_GET['user_id'] ?? 0);
if ($userId) {
    echo json_encode($cartModel->getUserCart($userId));
} else {
    echo json_encode([]);
}

