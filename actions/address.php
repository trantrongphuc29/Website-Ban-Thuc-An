<?php
require_once '../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$conn = getDBConnection();
$userId = $_SESSION['user']['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $receiverName = trim($_POST['receiver_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $isDefault = !empty($_POST['is_default']);
        
        if (empty($receiverName) || empty($phone) || empty($address)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin']);
            exit;
        }
        
        // Kiểm tra xét có địa chỉ nào chưa, nếu chưa thì tự động làm mặc định
        $count = $conn->query("SELECT COUNT(*) as total FROM user_addresses WHERE user_id = $userId")->fetch_assoc()['total'];
        if ($count == 0) {
            $isDefault = 1;
        }
        
        // Nếu đặt làm mặc định, bỏ mặc định của các địa chỉ khác
        if ($isDefault) {
            $conn->query("UPDATE user_addresses SET is_default = 0 WHERE user_id = $userId");
        }
        
        $stmt = $conn->prepare("INSERT INTO user_addresses (user_id, receiver_name, phone, address_text, is_default) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $userId, $receiverName, $phone, $address, $isDefault);
        
        if ($stmt->execute()) {
            $addressId = $conn->insert_id;
            echo json_encode([
                'status' => 'success', 
                'message' => 'Thêm địa chỉ thành công',
                'address' => [
                    'address_id' => $addressId,
                    'receiver_name' => $receiverName,
                    'phone' => $phone,
                    'address_text' => $address,
                    'is_default' => $isDefault
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra']);
        }
        $stmt->close();
        
    } elseif ($action === 'delete') {
        $addressId = (int)$_POST['address_id'];
        
        $stmt = $conn->prepare("DELETE FROM user_addresses WHERE address_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $addressId, $userId);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Xóa địa chỉ thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra']);
        }
        $stmt->close();
        
    } elseif ($action === 'set_default') {
        $addressId = (int)$_POST['address_id'];
        
        // Bỏ mặc định tất cả
        $conn->query("UPDATE user_addresses SET is_default = 0 WHERE user_id = $userId");
        
        // Đặt mặc định cho địa chỉ được chọn
        $stmt = $conn->prepare("UPDATE user_addresses SET is_default = 1 WHERE address_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $addressId, $userId);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Đặt làm địa chỉ mặc định thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra']);
        }
        $stmt->close();
    }
}

$conn->close();
?>