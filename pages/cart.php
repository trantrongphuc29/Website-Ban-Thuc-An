<?php
require_once '../config/config.php';

// Kiểm tra đăng nhập
if (empty($_SESSION['user'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$pageTitle = 'Giỏ hàng - FoodShop';
include INCLUDES_PATH . '/header.php';

// Kết nối database
$conn = getDBConnection();

$userId = $_SESSION['user']['user_id'];

// Lấy sản phẩm trong giỏ hàng
$sql = "SELECT c.cart_id, c.product_table, c.product_id, c.quantity, c.added_at FROM cart c WHERE c.user_id = ? ORDER BY c.added_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result();

$totalAmount = 0;
$cartProducts = [];

while ($item = $cartItems->fetch_assoc()) {
    // Lấy thông tin sản phẩm từ bảng tương ứng
    $productTable = $item['product_table'];
    $productId = $item['product_id'];
    
    $productSql = "SELECT name, price, image_url FROM $productTable WHERE product_id = ?";
    $productStmt = $conn->prepare($productSql);
    $productStmt->bind_param("i", $productId);
    $productStmt->execute();
    $productResult = $productStmt->get_result();
    
    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();
        $item['name'] = $product['name'];
        $item['price'] = $product['price'];
        $item['image_url'] = $product['image_url'];
        $item['subtotal'] = $product['price'] * $item['quantity'];
        $totalAmount += $item['subtotal'];
        $cartProducts[] = $item;
    }
    $productStmt->close();
}

$stmt->close();
$conn->close();
?>

<main class="main">
    <div class="cart-container">
        <h1>Giỏ hàng của bạn</h1>
        
        <?php if (empty($cartProducts)): ?>
            <div class="empty-cart">
                <p>Giỏ hàng của bạn đang trống</p> <br>
                <a href="<?php echo CATEGORY_URL; ?>/khuyenmai.php" class="btn-primary">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cartProducts as $item): ?>
                    <div class="cart-item" data-cart-id="<?php echo $item['cart_id']; ?>">
                        <div class="item-image">
                            <img src="<?php echo BASE_URL . '/' . htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </div>
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</p>
                        </div>
                        <div class="item-quantity">
                            <button class="qty-btn" onclick="updateQuantity(<?php echo $item['cart_id']; ?>, -1)">-</button>
                            <span class="qty-value"><?php echo $item['quantity']; ?></span>
                            <button class="qty-btn" onclick="updateQuantity(<?php echo $item['cart_id']; ?>, 1)">+</button>
                        </div>
                        <div class="item-subtotal">
                            <?php echo number_format($item['subtotal'], 0, ',', '.'); ?>đ
                        </div>
                        <button class="remove-btn" onclick="removeItem(<?php echo $item['cart_id']; ?>)">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
                <div class="total-amount">
                    <strong>Tổng cộng: <?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</strong>
                </div>
                <div class="cart-actions">
                    <a href="<?php echo CATEGORY_URL; ?>/khuyenmai.php" class="btn-outline">Tiếp tục mua sắm</a>
                    <a href="checkout.php" class="btn-primary">Tiến hành thanh toán</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function updateQuantity(cartId, change) {
    fetch('../actions/update_cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `cart_id=${cartId}&change=${change}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function removeItem(cartId) {
    fetch('../actions/remove_from_cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `cart_id=${cartId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}


</script>

<style>
.cart-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.empty-cart {
    text-align: center;
    padding: 3rem;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #eee;
    gap: 1rem;
}

.item-image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.item-info {
    flex: 1;
}

.item-info h3 {
    margin: 0 0 0.5rem 0;
}

.item-price {
    color: #666;
    margin: 0;
}

.item-quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.qty-btn {
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    background: white;
    cursor: pointer;
    border-radius: 4px;
}

.qty-value {
    min-width: 30px;
    text-align: center;
}

.item-subtotal {
    font-weight: bold;
    min-width: 100px;
    text-align: right;
}

.remove-btn {
    width: 30px;
    height: 30px;
    border: none;
    background: #ff4444;
    color: white;
    cursor: pointer;
    border-radius: 4px;
    font-size: 1.2rem;
}

.cart-summary {
    margin-top: 2rem;
    padding: 1rem;
    background: #f9f9f9;
    border-radius: 4px;
}

.total-amount {
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

.cart-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}


</style>

<?php include INCLUDES_PATH . '/footer.php'; ?>