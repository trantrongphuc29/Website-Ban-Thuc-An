<?php
require_once '../config/config.php';

if (empty($_SESSION['user']) || empty($_GET['order_id'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$orderId = (int)$_GET['order_id'];
$pageTitle = 'Đặt hàng thành công - FoodShop';
include INCLUDES_PATH . '/header.php';
?>

<style>
.success-container { max-width: 600px; margin: 4rem auto; padding: 2rem; text-align: center; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
.success-icon { font-size: 80px; margin-bottom: 1rem; }
.success-title { font-size: 28px; font-weight: 700; color: #16a34a; margin-bottom: 0.5rem; }
.order-id { font-size: 20px; color: #666; margin-bottom: 2rem; }
.order-id strong { color: #e11b22; }
.success-message { color: #666; margin-bottom: 2rem; line-height: 1.6; }
.action-buttons { display: flex; gap: 1rem; justify-content: center; }
</style>

<main class="main">
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1 class="success-title">Đặt hàng thành công!</h1>
        <div class="success-message">
            Cảm ơn bạn đã đặt hàng tại FoodShop!<br>
            Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.<br>
            Đơn hàng sẽ được giao trong vòng 30-45 phút.
        </div>
        <div class="action-buttons">
            <a href="<?php echo BASE_URL; ?>/index.php" class="btn-outline">Về trang chủ</a>
            <a href="<?php echo PAGES_URL; ?>/account.php#orders-tab" class="btn-primary">Xem đơn hàng</a>
        </div>
    </div>
</main>

<?php include INCLUDES_PATH . '/footer.php'; ?>
