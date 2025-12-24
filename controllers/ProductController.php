<?php
require_once __DIR__ . '/../models/Product.php';

/**
 * Controller for product and category interactions.
 */
class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    /**
     * Fetch categories for navigation.
     */
    public function categories() {
        return $this->productModel->getCategories();
    }

    /**
     * Fetch products with optional filters.
     */
    public function products($categoryId = null, $search = null) {
        return $this->productModel->getProducts($categoryId, $search);
    }

    /**
     * Fetch single product detail.
     */
    public function product($id) {
        return $this->productModel->getProduct($id);
    }

    /**
     * Admin create category.
     */
    public function createCategory($name, $image) {
        return $this->productModel->createCategory($name, $image);
    }

    /**
     * Admin update category.
     */
    public function updateCategory($id, $name, $image) {
        return $this->productModel->updateCategory($id, $name, $image);
    }

    /**
     * Admin delete category.
     */
    public function deleteCategory($id) {
        return $this->productModel->deleteCategory($id);
    }

    /**
     * Admin create product.
     */
    public function createProduct($data) {
        return $this->productModel->createProduct($data);
    }

    /**
     * Admin update product.
     */
    public function updateProduct($id, $data) {
        return $this->productModel->updateProduct($id, $data);
    }

    /**
     * Admin delete product.
     */
    public function deleteProduct($id) {
        return $this->productModel->deleteProduct($id);
    }
}

