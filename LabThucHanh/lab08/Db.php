<?php

class Db {
    protected $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=sql309.infinityfree.com;port=3306;dbname=if0_40293397_bookstore", "if0_40293397", "x3sFqZvDMLzG7C");
            $this->pdo->query("SET NAMES 'utf8'");
        } catch (Exception $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
            exit;
        }
    }

    // Phương thức thực thi câu lệnh SELECT
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Phương thức thực thi câu lệnh INSERT, UPDATE, DELETE
    public function execute($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
}
