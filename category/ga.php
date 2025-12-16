<?php
require_once '../config/config.php';
// Trang danh sách món Gà rán
$pageTitle = 'Gà rán - FoodShop';
include INCLUDES_PATH . '/header.php';

// Kết nối database
$conn = getDBConnection();

// Lấy sản phẩm từ bảng garan
$sql = "SELECT * FROM garan ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

    <!-- BANNER / TITLE -->
    <section class="hero">
        <div class="hero-content">
            <h1>Gà rán</h1>
            <p>Các phần gà rán giòn tan, ăn kèm khoai tây và nước ngọt.</p>
        </div>
    </section>

    <!-- CATEGORY BAR -->
    <section class="category-bar">
        <div class="category-inner">
            <a class="category-btn" href="<?php echo CATEGORY_URL; ?>/khuyenmai.php">Khuyến mãi</a>
            <a class="category-btn" href="<?php echo CATEGORY_URL; ?>/bestseller.php">Best Seller</a>
            <a class="category-btn active" href="<?php echo CATEGORY_URL; ?>/ga.php">Gà rán</a>
            <a class="category-btn" href="<?php echo CATEGORY_URL; ?>/burger.php">Burger</a>
            <a class="category-btn" href="<?php echo CATEGORY_URL; ?>/miy.php">Mì Ý</a>
            <a class="category-btn" href="<?php echo CATEGORY_URL; ?>/combo.php">Combo</a>
            <a class="category-btn" href="<?php echo CATEGORY_URL; ?>/nuocuong.php">Nước uống</a>
        </div>
    </section>

    <main class="main">
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $hasPromotion = !empty($row['promotion']) && $row['promotion'] > $row['price'];

                    echo '<article class="product-card">';
                    echo '    <div class="product-image">';
                    $imgPath = BASE_URL . '/' . htmlspecialchars($row['image_url']);
                echo '        <img src="'. $imgPath .'" alt="'. htmlspecialchars($row['name']) .'" onerror="this.onerror=null; this.src=\''. BASE_URL .'/images/placeholder.jpg\'">';
                    
                    if ($hasPromotion) {
                        $discount = round((($row['promotion'] - $row['price']) / $row['promotion']) * 100);
                        echo '    <span class="badge">-'.$discount.'%</span>';
                    }

                    echo '    </div>';
                    echo '    <div class="product-body">';
                    echo '        <h2 class="product-title">'. htmlspecialchars($row['name']) .'</h2>';
                    echo '        <p class="product-desc">'. htmlspecialchars($row['description']) .'</p>';
                    echo '        <div class="product-price">';
                    
                    if ($hasPromotion) {
                        echo '            <span class="price-old">'. number_format($row['promotion'],0,',','.') .'đ</span>';
                    }
                    
                    echo '            <div class="price-row">';
                    echo '                <span class="price-current">'. number_format($row['price'],0,',','.') .'đ</span>';
                    echo '                <button class="btn-add" data-id="'. $row['product_id'] .'" data-table="garan">+</button>';
                    echo '            </div>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</article>';
                }
            } else {
                echo '<p>Hiện chưa có sản phẩm trong danh mục này.</p>';
            }

            $conn->close();
            ?>
        </div>
    </main>

<?php
include INCLUDES_PATH . '/footer.php';
?>
