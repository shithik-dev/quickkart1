<?php
/**
 * Database connection helper using PDO.
 */
class Database {
    private $host = "localhost";
    private $db_name = "quickkart";
    private $username = "root";
    private $password = "";
    private $conn;

    /**
     * Create and return a PDO instance.
     */
    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $exception) {
            // Do not expose sensitive details in production; log instead.
            die("Database connection error.");
        }
        return $this->conn;
    }
}

