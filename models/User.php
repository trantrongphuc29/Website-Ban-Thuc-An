<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    public function register($email, $password, $fullname, $phone) {
        $hashedPassword = hash('sha256', $password);
        $sql = "INSERT INTO users (email, password, fullname, phone) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $hashedPassword, $fullname, $phone);
        return $stmt->execute();
    }
    
    public function login($email, $password) {
        $hashedPassword = hash('sha256', $password);
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashedPassword);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getById($userId) {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
