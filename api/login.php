<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/User.php';
$userModel = new User();

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email and password required']);
    exit;
}
$user = $userModel->findByEmail($email);
if ($user && password_verify($password, $user['password'])) {
    echo json_encode(['success' => true, 'user' => ['id' => $user['id'], 'name' => $user['full_name'], 'email' => $user['email']]]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}

