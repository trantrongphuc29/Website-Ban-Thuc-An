<?php
require_once '../config/config.php';

if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$orderId = $_POST['order_id'];
$userId = $_SESSION['user']['user_id'];

$conn = getDBConnection();

// Kiểm tra đơn hàng
$stmt = $conn->prepare("SELECT status FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("si", $orderId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng']);
    exit;
}

$order = $result->fetch_assoc();
$stmt->close();

// Chỉ cho phép hủy khi đang pending
if ($order['status'] !== 'pending') {
    echo json_encode(['status' => 'error', 'message' => 'Không thể hủy đơn hàng này']);
    exit;
}

// Cập nhật trạng thái
$stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("si", $orderId, $userId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Đã hủy đơn hàng']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra']);
}

$stmt->close();
$conn->close();
?>
