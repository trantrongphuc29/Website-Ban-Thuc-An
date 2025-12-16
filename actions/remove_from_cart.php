<?php
require_once '../config/config.php';

if (empty($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Không có quyền truy cập']);
    exit;
}

$cartId = (int)$_POST['cart_id'];
$userId = $_SESSION['user']['user_id'];

$conn = getDBConnection();

$stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
$stmt->bind_param("ii", $cartId, $userId);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['status' => 'success']);
?>