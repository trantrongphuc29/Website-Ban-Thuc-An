<?php
require_once '../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Cập nhật trạng thái
if (isset($_GET['action'], $_GET['order_id'])) {
    $orderId = $_GET['order_id'];
    $action = $_GET['action'];
    
    // Kiểm tra trạng thái hiện tại
    $checkStmt = $conn->prepare("SELECT status FROM orders WHERE order_id = ?");
    $checkStmt->bind_param("s", $orderId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $currentStatus = $result->fetch_assoc()['status'];
    $checkStmt->close();
    
    // Không cho cập nhật nếu đã hủy
    if ($currentStatus === 'cancelled') {
        header('Location: orders.php?error=cancelled');
        exit;
    }
    
    $statusMap = [
        'approve' => 'preparing',
        'ship' => 'delivering',
        'complete' => 'completed',
        'cancel' => 'cancelled'
    ];
    
    if (isset($statusMap[$action])) {
        $status = $statusMap[$action];
        $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE order_id = ? AND status != 'cancelled'");
        $stmt->bind_param("ss", $status, $orderId);
        $stmt->execute();
        $stmt->close();
    }
    
    header('Location: orders.php');
    exit;
}

// Lấy đơn hàng theo trạng thái
$filterStatus = $_GET['status'] ?? 'all';
$sql = "SELECT o.*, u.fullname, u.email, u.phone FROM orders o JOIN users u ON o.user_id = u.user_id";
if ($filterStatus !== 'all') {
    $sql .= " WHERE o.status = '$filterStatus'";
}
$sql .= " ORDER BY o.created_at DESC";
$orders = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

// Đếm số lượng theo trạng thái
$counts = [
    'all' => $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'],
    'pending' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='pending'")->fetch_assoc()['c'],
    'preparing' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='preparing'")->fetch_assoc()['c'],
    'delivering' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='delivering'")->fetch_assoc()['c'],
    'completed' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='completed'")->fetch_assoc()['c'],
    'cancelled' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='cancelled'")->fetch_assoc()['c']
];

$pageTitle = 'Quản lý đơn hàng';
include 'includes/header.php';
?>

        <div class="content-card">
            <div style="display:flex;gap:0.5rem;margin-bottom:1.5rem;flex-wrap:wrap;">
                <a href="?status=all" class="filter-tab <?php echo $filterStatus === 'all' ? 'active' : ''; ?>">Tất cả (<?php echo $counts['all']; ?>)</a>
                <a href="?status=pending" class="filter-tab <?php echo $filterStatus === 'pending' ? 'active' : ''; ?>">Chờ duyệt (<?php echo $counts['pending']; ?>)</a>
                <a href="?status=preparing" class="filter-tab <?php echo $filterStatus === 'preparing' ? 'active' : ''; ?>">Chuẩn bị (<?php echo $counts['preparing']; ?>)</a>
                <a href="?status=delivering" class="filter-tab <?php echo $filterStatus === 'delivering' ? 'active' : ''; ?>">Giao hàng (<?php echo $counts['delivering']; ?>)</a>
                <a href="?status=completed" class="filter-tab <?php echo $filterStatus === 'completed' ? 'active' : ''; ?>">Hoàn thành (<?php echo $counts['completed']; ?>)</a>
                <a href="?status=cancelled" class="filter-tab <?php echo $filterStatus === 'cancelled' ? 'active' : ''; ?>">Đã hủy (<?php echo $counts['cancelled']; ?>)</a>
            </div>
            <table>
                <tbody>
                    <?php foreach ($orders as $order): 
                        // Lấy chi tiết đơn hàng
                        $itemsSql = "SELECT oi.*, p.name, p.image_url FROM orderitems oi 
                                     LEFT JOIN (SELECT product_id, name, image_url, 'bestseller' as tbl FROM bestseller
                                                UNION SELECT product_id, name, image_url, 'burger' FROM burger
                                                UNION SELECT product_id, name, image_url, 'combo' FROM combo
                                                UNION SELECT product_id, name, image_url, 'garan' FROM garan
                                                UNION SELECT product_id, name, image_url, 'khuyenmai' FROM khuyenmai
                                                UNION SELECT product_id, name, image_url, 'miy' FROM miy
                                                UNION SELECT product_id, name, image_url, 'nuocuong' FROM nuocuong) p 
                                     ON oi.product_id = p.product_id AND oi.product_table = p.tbl
                                     WHERE oi.order_id = ?";
                        $itemsStmt = $conn->prepare($itemsSql);
                        $itemsStmt->bind_param("s", $order['order_id']);
                        $itemsStmt->execute();
                        $items = $itemsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                        $itemsStmt->close();
                    ?>
                    <tr style="border-bottom:2px solid #f0f0f0;">
                        <td colspan="7" style="padding:1.5rem;background:#fafafa;">
                            <div style="display:flex;gap:2rem;">
                                <div style="flex:1;">
                                    <div style="font-weight:700;font-size:16px;margin-bottom:0.5rem;">Đơn hàng: <?php echo $order['order_id']; ?></div>
                                    <div style="margin-bottom:0.5rem;"><strong><?php echo htmlspecialchars($order['customer_name']); ?></strong> - <?php echo htmlspecialchars($order['customer_phone']); ?></div>
                                    <div style="color:#666;font-size:13px;margin-bottom:0.5rem;">📍 <?php echo htmlspecialchars($order['shipping_address']); ?></div>
                                    <div style="color:#666;font-size:13px;">📅 <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
                                    <div style="margin-top:0.75rem;">
                                        <span class="badge <?php echo $order['status']; ?>"><?php echo $order['status']; ?></span>
                                    </div>
                                </div>
                                <div style="flex:2;">
                                    <div style="font-weight:600;margin-bottom:0.5rem;">Chi tiết đơn hàng:</div>
                                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                                        <?php foreach ($items as $item): ?>
                                        <div style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem;background:white;border-radius:6px;">
                                            <img src="<?php echo BASE_URL . '/' . ($item['image_url'] ?? 'images/default.jpg'); ?>" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                            <div style="flex:1;">
                                                <div style="font-size:14px;font-weight:500;"><?php echo htmlspecialchars($item['name'] ?? 'Sản phẩm đã xóa'); ?></div>
                                                <div style="font-size:12px;color:#666;">SL: <?php echo $item['quantity']; ?> x <?php echo number_format($item['price']); ?>đ</div>
                                            </div>
                                            <div style="font-weight:600;color:#e11b22;"><?php echo number_format($item['price'] * $item['quantity']); ?>đ</div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div style="text-align:right;margin-top:0.75rem;padding-top:0.75rem;border-top:2px solid #f0f0f0;">
                                        <span style="font-size:16px;font-weight:700;color:#e11b22;">Tổng: <?php echo number_format($order['total_price']); ?>đ</span>
                                    </div>
                                </div>
                                <div style="display:flex;flex-direction:column;gap:0.5rem;align-items:flex-start;min-width:120px;">
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <a href="?action=approve&order_id=<?php echo $order['order_id']; ?>" class="btn-sm" style="background:#10b981;color:white;text-align:center;width:100%;">✓ Phê duyệt</a>
                                        <a href="?action=cancel&order_id=<?php echo $order['order_id']; ?>" class="btn-sm" style="background:#ef4444;color:white;text-align:center;width:100%;" onclick="return confirm('Hủy đơn này?')">✕ Hủy</a>
                                    <?php elseif ($order['status'] === 'preparing'): ?>
                                        <a href="?action=ship&order_id=<?php echo $order['order_id']; ?>" class="btn-sm" style="background:#3b82f6;color:white;text-align:center;width:100%;">🚚 Giao hàng</a>
                                    <?php elseif ($order['status'] === 'delivering'): ?>
                                        <a href="?action=complete&order_id=<?php echo $order['order_id']; ?>" class="btn-sm" style="background:#8b5cf6;color:white;text-align:center;width:100%;">✓ Hoàn thành</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<?php
$conn->close();

if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    header('Location: login.php');
    exit;
}
include 'includes/footer.php';
?>
