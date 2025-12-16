<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    public function getByCategory($table, $limit = null) {
        $sql = "SELECT * FROM `$table`";
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getById($table, $id) {
        $sql = "SELECT * FROM `$table` WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
