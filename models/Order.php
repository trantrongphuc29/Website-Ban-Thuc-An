<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    public function create($userId, $totalPrice) {
        $sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("id", $userId, $totalPrice);
        $stmt->execute();
        return $this->conn->insert_id;
    }
    
    public function addItem($orderId, $productTable, $productId, $quantity, $price) {
        $sql = "INSERT INTO orderitems (order_id, product_table, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isiid", $orderId, $productTable, $productId, $quantity, $price);
        return $stmt->execute();
    }
    
    public function getByUser($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getById($orderId, $userId) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
        $stmt->bind_param("si", $orderId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function updateStatus($orderId, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE order_id = ?");
        $stmt->bind_param("ss", $status, $orderId);
        return $stmt->execute();
    }
}
?>
