<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Product model for product and category retrieval.
 */
class Product {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Fetch all categories.
     */
    public function getCategories() {
        $stmt = $this->conn->query("SELECT * FROM categories ORDER BY category_name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Fetch products with optional category or search filter.
     */
    public function getProducts($categoryId = null, $search = null) {
        $sql = "SELECT p.*, c.category_name FROM products p
                LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];
        if ($categoryId) {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        if ($search) {
            $sql .= " AND p.product_name LIKE :search";
            $params[':search'] = "%{$search}%";
        }
        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single product.
     */
    public function getProduct($id) {
        $stmt = $this->conn->prepare("SELECT p.*, c.category_name FROM products p
                                      LEFT JOIN categories c ON p.category_id = c.id
                                      WHERE p.id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Update stock after order placement.
     */
    public function decreaseStock($productId, $quantity) {
        $stmt = $this->conn->prepare("UPDATE products SET stock = stock - :qty WHERE id = :id");
        return $stmt->execute([':qty' => $quantity, ':id' => $productId]);
    }

    /**
     * Admin create product.
     */
    public function createProduct($data) {
        $sql = "INSERT INTO products (product_name, category_id, price, mrp, image, description, stock)
                VALUES (:product_name, :category_id, :price, :mrp, :image, :description, :stock)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':product_name' => $data['product_name'],
            ':category_id' => $data['category_id'],
            ':price' => $data['price'],
            ':mrp' => $data['mrp'],
            ':image' => $data['image'],
            ':description' => $data['description'],
            ':stock' => $data['stock'],
        ]);
    }

    /**
     * Admin update product.
     */
    public function updateProduct($id, $data) {
        $sql = "UPDATE products SET product_name = :product_name, category_id = :category_id,
                price = :price, mrp = :mrp, image = :image, description = :description,
                stock = :stock WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':product_name' => $data['product_name'],
            ':category_id' => $data['category_id'],
            ':price' => $data['price'],
            ':mrp' => $data['mrp'],
            ':image' => $data['image'],
            ':description' => $data['description'],
            ':stock' => $data['stock'],
            ':id' => $id,
        ]);
    }

    /**
     * Delete product.
     */
    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Category CRUD for admin.
     */
    public function createCategory($name, $image) {
        $stmt = $this->conn->prepare("INSERT INTO categories (category_name, image) VALUES (:name, :image)");
        return $stmt->execute([':name' => $name, ':image' => $image]);
    }

    public function updateCategory($id, $name, $image) {
        $stmt = $this->conn->prepare("UPDATE categories SET category_name = :name, image = :image WHERE id = :id");
        return $stmt->execute([':name' => $name, ':image' => $image, ':id' => $id]);
    }

    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

