<?php
require_once '../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$userId = $_SESSION['user']['user_id'];
$fullname = trim($_POST['fullname'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (empty($fullname) || empty($phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin']);
    exit;
}

// Validate số điện thoại
$phoneRegex = '/^(84|0[3|5|7|8|9])([0-9]{8})$/';
if (!preg_match($phoneRegex, $phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Số điện thoại không hợp lệ']);
    exit;
}

$conn = getDBConnection();

$stmt = $conn->prepare("UPDATE users SET fullname = ?, phone = ? WHERE user_id = ?");
$stmt->bind_param("ssi", $fullname, $phone, $userId);

if ($stmt->execute()) {
    // Cập nhật session
    $_SESSION['user']['fullname'] = $fullname;
    $_SESSION['user']['phone'] = $phone;
    
    echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin thành công']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>