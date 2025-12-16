<?php
// Header dùng chung cho tất cả các trang
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/../config/config.php';
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include xử lý auth
require_once ACTIONS_PATH . '/auth.php';

// Kiểm tra trạng thái tài khoản nếu đã đăng nhập
if (!empty($_SESSION['user'])) {
    $conn = getDBConnection();
    if ($conn) {
        $stmt = $conn->prepare("SELECT status FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $_SESSION['user']['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($user['status'] === 'blocked') {
                // Kick user ra khỏi hệ thống
                unset($_SESSION['user']);
                echo '<script>alert("Tài khoản của bạn đã bị khóa. Bạn sẽ bị đăng xuất."); window.location.href = "' . BASE_URL . '/index.php";</script>';
                exit;
            }
        }
        $stmt->close();
        $conn->close();
    }
}

// Thiết lập title mặc định nếu chưa truyền vào
if (!isset($pageTitle)) {
    $pageTitle = 'FoodShop - Web bán thức ăn';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="header-inner">
            <div class="logo">
                <a href="../index.php">Food<span>Shop</span></a>
            </div>
            <nav class="nav">
                <a href="../index.php">Trang chủ</a>
                <a href="../category/khuyenmai.php">Menu</a>
                <a href="../LabThucHanh/">Lab Thực Hành</a>
            </nav>
            <div class="header-actions">
                <form action="../pages/search.php" method="GET">
                    <input type="text" name="q" placeholder="Tìm món ăn..." class="search-input" required>
                </form>
                <?php if (!empty($_SESSION['user'])): ?>
                    <div class="user-menu">
                        <a href="../pages/account.php" class="btn-user" title="Tài khoản của tôi">
                            👤
                        </a>
                        <div class="user-dropdown">
                            <div class="user-info">
                                <strong><?php echo htmlspecialchars($_SESSION['user']['fullname']); ?></strong>
                                <span><?php echo htmlspecialchars($_SESSION['user']['email']); ?></span>
                            </div>
                            <a href="../pages/account.php">Tài khoản của tôi</a>
                            <a href="../pages/account.php#orders-tab">Đơn hàng</a>
                            <a href="../pages/account.php#address-tab">Địa chỉ giao hàng</a>
                            <a href="?logout=1" class="logout-link">Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="#login-modal" class="btn-outline">Đăng nhập</a>
                <?php endif; ?>
                <a href="../pages/cart.php" class="btn-primary">Giỏ hàng (0)</a>
            </div>
        </div>
    </header>
