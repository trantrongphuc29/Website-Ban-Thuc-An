<?php
require_once __DIR__ . '/../config/database.php';

class Cart {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    public function add($userId, $productTable, $productId, $quantity = 1) {
        $sql = "INSERT INTO cart (user_id, product_table, product_id, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isii", $userId, $productTable, $productId, $quantity);
        return $stmt->execute();
    }
    
    public function getByUser($userId) {
        $sql = "SELECT c.*, p.name, p.price, p.image_url 
                FROM cart c 
                JOIN {product_table} p ON c.product_id = p.product_id 
                WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function update($cartId, $quantity) {
        $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $cartId);
        return $stmt->execute();
    }
    
    public function remove($cartId) {
        $sql = "DELETE FROM cart WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $cartId);
        return $stmt->execute();
    }
}
?>
