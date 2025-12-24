<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Product.php';
$productModel = new Product();
$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
$search = $_GET['q'] ?? null;
echo json_encode($productModel->getProducts($categoryId, $search));

