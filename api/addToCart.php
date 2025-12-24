<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Cart.php';
$cartModel = new Cart();
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$userId = intval($input['user_id'] ?? 0);
$productId = intval($input['product_id'] ?? 0);
$qty = max(1, intval($input['quantity'] ?? 1));
if ($userId && $productId) {
    $cartModel->addToCart($userId, $productId, $qty);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid payload']);
}

