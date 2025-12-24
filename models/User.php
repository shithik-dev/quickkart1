<?php
require_once __DIR__ . '/../config/database.php';

/**
 * User model handling CRUD and authentication helpers.
 */
class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Create a new user.
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (full_name, email, password, phone, address, created_at)
                VALUES (:full_name, :email, :password, :phone, :address, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':phone' => $data['phone'],
            ':address' => $data['address']
        ]);
    }

    /**
     * Find user by email.
     */
    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Find user by id.
     */
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}

