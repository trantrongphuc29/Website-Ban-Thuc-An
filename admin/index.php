<?php
require_once '../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Thống kê
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalOrders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$totalRevenue = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE status = 'completed'")->fetch_assoc()['total'] ?? 0;

// Đơn hàng mới
$recentOrders = $conn->query("SELECT o.*, u.fullname, u.email FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        /* Reset CSS conflicts */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f5f5f5; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        a { text-decoration: none; }
        
        .admin-container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .admin-header { background: white; padding: 1rem 2rem; margin-bottom: 2rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .admin-header h1 { margin: 0; font-size: 24px; color: #111; }
        .admin-nav { display: flex; gap: 1rem; }
        .admin-nav a { padding: 0.5rem 1rem; border-radius: 8px; background: #f0f0f0; transition: all 0.2s; text-decoration: none; color: #333; }
        .admin-nav a:hover, .admin-nav a.active { background: #e11b22; color: white !important; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .stat-card h3 { font-size: 14px; color: #666; margin-bottom: 0.5rem; }
        .stat-card .value { font-size: 32px; font-weight: bold; color: #e11b22; }
        .content-card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .content-card h2 { margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        table th { text-align: left; padding: 0.75rem; background: #f9f9f9; font-weight: 600; }
        table td { padding: 0.75rem; border-bottom: 1px solid #f0f0f0; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block; }
        .badge.pending { background: #fef3c7; color: #92400e; }
        .badge.preparing { background: #dbeafe; color: #1e40af; }
        .badge.delivering { background: #e0e7ff; color: #4338ca; }
        .badge.completed { background: #dcfce7; color: #166534; }
        .badge.cancelled { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <div class="admin-nav">
                <a href="index.php" class="active">Dashboard</a>
                <a href="products.php">Sản phẩm</a>
                <a href="orders.php">Đơn hàng</a>
                <a href="users.php">Người dùng</a>
                <a href="revenue.php">Doanh thu</a>
                <a href="?logout=1">Đăng xuất</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Tổng người dùng</h3>
                <div class="value"><?php echo number_format($totalUsers); ?></div>
            </div>
            <div class="stat-card">
                <h3>Tổng đơn hàng</h3>
                <div class="value"><?php echo number_format($totalOrders); ?></div>
            </div>
            <div class="stat-card">
                <h3>Doanh thu</h3>
                <div class="value"><?php echo number_format($totalRevenue); ?>đ</div>
            </div>
        </div>

        <div class="content-card">
            <h2>Đơn hàng mới</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                        <td><?php echo number_format($order['total_price']); ?>đ</td>
                        <td><span class="badge <?php echo $order['status']; ?>"><?php echo $order['status']; ?></span></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    header('Location: login.php');
    exit;
}
?>
