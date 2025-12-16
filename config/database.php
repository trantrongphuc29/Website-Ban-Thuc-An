<?php
// Cấu hình kết nối database
define('DB_HOST', 'sql309.infinityfree.com');
define('DB_PORT', '3306');
define('DB_NAME', 'if0_40293397_foodweb');
define('DB_USER', 'if0_40293397');
define('DB_PASS', 'x3sFqZvDMLzG7C');

// Thiết lập timezone TP.HCM
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Kết nối database
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        
        // Đồng bộ timezone với MySQL
        $conn->query("SET time_zone = '+07:00'");
        
        return $conn;
    } catch (Exception $e) {
        die("Lỗi: " . $e->getMessage());
    }
}
?>
