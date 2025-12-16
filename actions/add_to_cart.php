<?php
require_once '../config/config.php';

// Kiểm tra đăng nhập
if (empty($_SESSION['user'])) {
    echo json_encode(['status' => 'login_required']);
    exit;
}

// Kiểm tra dữ liệu POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['product_id']) || empty($_POST['product_table'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$productId = (int)$_POST['product_id'];
$productTable = $_POST['product_table'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$userId = $_SESSION['user']['user_id'];

// Kết nối database
$conn = getDBConnection();

// Kiểm tra sản phẩm có tồn tại không
$allowedTables = ['khuyenmai', 'bestseller', 'garan', 'burger', 'miy', 'combo', 'nuocuong'];
if (!in_array($productTable, $allowedTables)) {
    echo json_encode(['status' => 'error', 'message' => 'Bảng sản phẩm không hợp lệ: ' . $productTable . '. Cho phép: ' . implode(', ', $allowedTables)]);
    exit;
}

$stmt = $conn->prepare("SELECT product_id, name, price FROM $productTable WHERE product_id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại']);
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

// Kiểm tra sản phẩm đã có trong giỏ hàng chưa
$stmt = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_table = ? AND product_id = ?");
$stmt->bind_param("isi", $userId, $productTable, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Cập nhật số lượng
    $cartItem = $result->fetch_assoc();
    $newQuantity = $cartItem['quantity'] + $quantity;
    
    $stmt->close();
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $stmt->bind_param("ii", $newQuantity, $cartItem['cart_id']);
    $stmt->execute();
} else {
    // Thêm mới vào giỏ hàng
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_table, product_id, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $userId, $productTable, $productId, $quantity);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo json_encode(['status' => 'success', 'message' => 'Đã thêm vào giỏ hàng']);
?>