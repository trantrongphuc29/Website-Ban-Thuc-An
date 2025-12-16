<?php
require_once '../config/config.php';

if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$userId = $_SESSION['user']['user_id'];
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

if (!$name || !$phone || !$address) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin']);
    exit;
}

$conn = getDBConnection();

// Kiểm tra xem có địa chỉ nào chưa
$count = $conn->query("SELECT COUNT(*) as total FROM user_addresses WHERE user_id = $userId")->fetch_assoc()['total'];
$isDefault = $count == 0 ? 1 : 0;

$stmt = $conn->prepare("INSERT INTO user_addresses (user_id, address_text, receiver_name, phone, is_default) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isssi", $userId, $address, $name, $phone, $isDefault);

if ($stmt->execute()) {
    $addressId = $conn->insert_id;
    echo json_encode([
        'status' => 'success',
        'message' => 'Thêm địa chỉ thành công',
        'address' => [
            'address_id' => $addressId,
            'receiver_name' => $name,
            'phone' => $phone,
            'address_text' => $address,
            'is_default' => $isDefault
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra']);
}

$stmt->close();
$conn->close();
?>
