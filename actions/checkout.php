<?php
require_once '../config/config.php';
require_once __DIR__ . '/../includes/order_helper.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$userId = $_SESSION['user']['user_id'];
$shippingAddress = trim($_POST['address'] ?? '');
$customerName = trim($_POST['name'] ?? '');
$customerPhone = trim($_POST['phone'] ?? '');
$paymentMethod = trim($_POST['payment'] ?? 'COD');
$notes = trim($_POST['note'] ?? '');

if (!$shippingAddress || !$customerName || !$customerPhone) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin']);
    exit;
}

$conn = getDBConnection();

// Lấy giỏ hàng
$sql = "SELECT c.*, p.price FROM cart c JOIN (
    SELECT product_id, price, 'bestseller' as tbl FROM bestseller
    UNION SELECT product_id, price, 'burger' FROM burger
    UNION SELECT product_id, price, 'combo' FROM combo
    UNION SELECT product_id, price, 'garan' FROM garan
    UNION SELECT product_id, price, 'khuyenmai' FROM khuyenmai
    UNION SELECT product_id, price, 'miy' FROM miy
    UNION SELECT product_id, price, 'nuocuong' FROM nuocuong
) p ON c.product_id = p.product_id AND c.product_table = p.tbl
WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cartItems)) {
    echo json_encode(['status' => 'error', 'message' => 'Giỏ hàng trống']);
    exit;
}

// Tính tổng tiền
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Tạo mã đơn hàng
$orderId = generateOrderCode($conn, $shippingAddress);

// Tạo đơn hàng
$stmt = $conn->prepare("INSERT INTO orders (order_id, user_id, customer_name, customer_phone, shipping_address, payment_method, notes, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisssssd", $orderId, $userId, $customerName, $customerPhone, $shippingAddress, $paymentMethod, $notes, $totalPrice);
$stmt->execute();
$stmt->close();

// Unlock tables
$conn->query("UNLOCK TABLES");

// Thêm chi tiết đơn hàng
$stmt = $conn->prepare("INSERT INTO orderitems (order_id, product_table, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
foreach ($cartItems as $item) {
    $stmt->bind_param("ssiid", $orderId, $item['product_table'], $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}
$stmt->close();

// Xóa giỏ hàng
$conn->query("DELETE FROM cart WHERE user_id = $userId");

$conn->close();

echo json_encode(['status' => 'success', 'message' => 'Đặt hàng thành công!', 'order_id' => $orderId]);
?>
