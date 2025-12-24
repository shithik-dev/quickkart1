<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';
$orderModel = new Order();
$cartModel = new Cart();
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$userId = intval($input['user_id'] ?? 0);
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'User required']);
    exit;
}
$cartItems = $cartModel->getUserCart($userId);
if (empty($cartItems)) {
    echo json_encode(['success' => false, 'message' => 'Cart empty']);
    exit;
}
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
$orderId = $orderModel->createOrder($userId, $cartItems, $total);
if ($orderId) {
    $cartModel->clearCart($userId);
    echo json_encode(['success' => true, 'order_id' => $orderId]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to place order']);
}

