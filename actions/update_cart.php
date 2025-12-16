<?php
require_once '../config/config.php';

if (empty($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Không có quyền truy cập']);
    exit;
}

$cartId = (int)$_POST['cart_id'];
$change = (int)$_POST['change'];
$userId = $_SESSION['user']['user_id'];

$conn = getDBConnection();

// Lấy số lượng hiện tại
$stmt = $conn->prepare("SELECT quantity FROM cart WHERE cart_id = ? AND user_id = ?");
$stmt->bind_param("ii", $cartId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm']);
    exit;
}

$currentQty = $result->fetch_assoc()['quantity'];
$newQty = $currentQty + $change;

if ($newQty <= 0) {
    // Xóa sản phẩm nếu số lượng <= 0
    $stmt->close();
    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartId, $userId);
} else {
    // Cập nhật số lượng
    $stmt->close();
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $newQty, $cartId, $userId);
}

$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['status' => 'success']);
?>