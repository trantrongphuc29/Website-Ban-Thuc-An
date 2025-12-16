<?php
require_once '../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'Quản lý người dùng';
$conn = getDBConnection();

// Khóa/mở tài khoản
if (isset($_GET['action'], $_GET['user_id'])) {
    $userId = (int)$_GET['user_id'];
    $status = $_GET['action'] === 'block' ? 'blocked' : 'active';
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $status, $userId);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php');
    exit;
}

// Lấy người dùng
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

$conn->close();
include 'includes/header.php';
?>
<style>
    .btn-block { background: #ef4444; color: white; }
    .btn-unblock { background: #10b981; color: white; }
</style>

        <div class="content-card">
            <h2>Danh sách người dùng</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><span class="badge <?php echo $user['status'] ?? 'active'; ?>"><?php echo ($user['status'] ?? 'active') === 'active' ? 'Hoạt động' : 'Bị khóa'; ?></span></td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if (($user['status'] ?? 'active') === 'active'): ?>
                                <a href="?action=block&user_id=<?php echo $user['user_id']; ?>" class="btn-sm btn-block" onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?')">Khóa</a>
                            <?php else: ?>
                                <a href="?action=unblock&user_id=<?php echo $user['user_id']; ?>" class="btn-sm btn-unblock" onclick="return confirm('Bạn có chắc muốn mở khóa tài khoản này?')">Mở khóa</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<?php
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    header('Location: login.php');
    exit;
}
include 'includes/footer.php';
?>
