<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/Product.php';
$productModel = new Product();
echo json_encode($productModel->getCategories());

