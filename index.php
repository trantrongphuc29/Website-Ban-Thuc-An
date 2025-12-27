<?php
require_once 'config/config.php';
$pageTitle = 'Web Bán Thức Ăn - Set Menu';

// Lấy 3 combo mới nhất
$conn = getDBConnection();
$combos = $conn->query("SELECT * FROM combo ORDER BY product_id DESC LIMIT 3")->fetch_all(MYSQLI_ASSOC);
$conn->close();

include INCLUDES_PATH . '/header.php';
?>

<!-- BANNER / TITLE -->
<section class="hero hero-home">
    <div class="hero-content hero-home-content">
        <div class="hero-text">
            <span class="hero-badge"><i class="fas fa-fire"></i> Ưu đãi hôm nay</span>
            <h1>Thưởng thức món ngon<br>Giao tận nơi siêu nhanh</h1>
            <p>Gà rán giòn rụm, Burger thơm ngon, Mì ý đậm đà cùng hàng trăm combo hấp dẫn. Đặt ngay, giao trong 30 phút!</p>
            <div class="hero-actions">
                <a href="category/khuyenmai.php" class="btn-primary hero-btn"><i class="fas fa-shopping-cart"></i> Đặt món ngay</a>
                <a href="category/combo.php" class="btn-outline hero-btn-outline"><i class="fas fa-gift"></i> Xem combo</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <strong><i class="fas fa-shopping-bag"></i> 1000+</strong>
                    <span>Đơn hàng/ngày</span>
                </div>
                <div class="stat-item">
                    <strong><i class="fas fa-clock"></i> 30 phút</strong>
                    <span>Giao hàng nhanh</span>
                </div>
                <div class="stat-item">
                    <strong> 4.9 <i class="fas fa-star"></i></strong>
                    <span>Đánh giá</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .hero-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    }

    .hero-badge i {
        color: #ffd700;
        margin-right: 0.5rem;
    }

    .hero-btn i,
    .hero-btn-outline i {
        margin-right: 0.5rem;
    }

    .hero-home {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=1920') center/cover;
        min-height: 600px;
        display: flex;
        align-items: center;
    }

    .hero-home .hero-content {
        justify-content: flex-start;
        align-items: center;
    }

    .hero-home .hero-text {
        text-align: left;
        max-width: 800px;
    }

    .hero-home h1 {
        color: white;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
    }

    .hero-home p {
        color: rgba(255, 255, 255, 0.95);
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    }

    .hero-actions {
        justify-content: flex-start;
    }

    .hero-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
        justify-content: flex-start;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.15);
        padding: 1rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .stat-item strong {
        font-size: 24px;
        color: #ffd700;
        margin-bottom: 0.25rem;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }

    .stat-item span {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.9);
    }

    .category-icon {
        font-size: 48px;
        margin-bottom: 1rem;
    }

    .category-icon .fa-drumstick-bite {
        color: #f59e0b;
    }

    .category-icon .fa-hamburger {
        color: #ef4444;
    }

    .category-icon .fa-pizza-slice {
        color: #ec4899;
    }

    .category-icon .fa-box {
        color: #8b5cf6;
    }

    .home-section {
        margin: 3rem 0;
        padding: 2rem 0;
    }

    .hero-home {
        margin-bottom: 0;
    }
</style>

<main class="main">
    <!-- SECTION: DANH MỤC NỔI BẬT -->
    <section class="home-section">
        <div class="home-section-header">
            <h2>Danh mục nổi bật.</h2>
            <a href="category/khuyenmai.php" class="link-more">Xem tất cả &rarr;</a>
        </div>
        <div class="category-grid">
            <a href="category/ga.php" class="category-tile">
                <div class="category-icon"><i class="fas fa-drumstick-bite"></i></div>
                <h3>Gà rán</h3>
                <p>Giòn rụm, nóng hổi, combo tiết kiệm.</p>
            </a>
            <a href="category/burger.php" class="category-tile">
                <div class="category-icon"><i class="fas fa-hamburger"></i></div>
                <h3>Burger</h3>
                <p>Bò, gà, phô mai cho bữa ăn nhanh.</p>
            </a>
            <a href="category/miy.php" class="category-tile">
                <div class="category-icon"><i class="fas fa-pizza-slice"></i></div>
                <h3>Mì Ý</h3>
                <p>Sốt bò bằm, kem, phô mai béo ngậy.</p>
            </a>
            <a href="category/combo.php" class="category-tile">
                <div class="category-icon"><i class="fas fa-box"></i></div>
                <h3>Combo</h3>
                <p>Set 1–4 người, tiết kiệm chi phí.</p>
            </a>
        </div>
    </section>

    <!-- SECTION: COMBO GỢI Ý -->
    <section class="home-section">
        <div class="home-section-header">
            <h2>Combo gợi ý cho bạn</h2>
            <a href="category/combo.php" class="link-more">Xem thêm combo &rarr;</a>
        </div>
        <div class="product-grid">
            <?php foreach ($combos as $combo): ?>
                <article class="product-card">
                    <div class="product-image">
                        <img src="<?php echo BASE_URL . '/' . $combo['image_url']; ?>" alt="<?php echo htmlspecialchars($combo['name']); ?>">
                        <?php if ($combo['promotion']): ?>
                            <span class="badge">-<?php echo round((($combo['price'] - $combo['promotion']) / $combo['price']) * 100); ?>%</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-body">
                        <h2 class="product-title"><?php echo htmlspecialchars($combo['name']); ?></h2>
                        <p class="product-desc"><?php echo htmlspecialchars($combo['description']); ?></p>
                        <div class="product-price">
                            <?php if ($combo['promotion']): ?>
                                <span class="price-old"><?php echo number_format($combo['price']); ?>đ</span>
                                <div class="price-row">
                                    <span class="price-current"><?php echo number_format($combo['promotion']); ?>đ</span>
                                    <button class="btn-add" data-id="<?php echo $combo['product_id']; ?>" data-table="combo">+</button>
                                </div>
                            <?php else: ?>
                                <div class="price-row">
                                    <span class="price-current"><?php echo number_format($combo['price']); ?>đ</span>
                                    <button class="btn-add" data-id="<?php echo $combo['product_id']; ?>" data-table="combo">+</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SECTION: LÝ DO CHỌN FOODSHOP -->
    <section class="home-section benefits-section">
        <div class="home-section-header">
            <h2>Tại sao khách hàng tin tưởng chúng tôi?</h2>
            <h2 style="margin-top:0.5rem; ">Cam kết mang đến trải nghiệm tốt nhất cho bạn</h2>
        </div>
        <div class="benefit-grid">
            <div class="benefit-item">
                <div class="benefit-icon"><i class="fas fa-shipping-fast"></i></div>
                <h3>Giao hàng siêu nhanh</h3>
                <p>Đối tác giao hàng chuyên nghiệp, giao trong 30 phút hoặc hoàn tiền.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon"><i class="fas fa-fire-alt"></i></div>
                <h3>Đảm bảo nóng hổi</h3>
                <p>Hệ thống đóng gói giữ nhiệt hiệu quả, đồ ăn luôn thơm ngon.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon"><i class="fas fa-tags"></i></div>
                <h3>Ưu đãi hấp dẫn</h3>
                <p>Khuyến mãi mỗi ngày, giảm giá đến 50% cho combo hot.</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon"><i class="fas fa-star"></i></div>
                <h3>Chất lượng đảm bảo</h3>
                <p>Nguyên liệu tươi ngon, quy trình chế biến chuẩn vệ sinh.</p>
            </div>
        </div>
    </section>

    <style>
        .benefits-section {
            background: linear-gradient(135deg, #8eb3ebff 0%, #c3cfe2 100%);
            padding: 4rem 2rem;
            margin: 3rem 0;
            border-radius: 24px;
        }

        .benefit-icon {
            font-size: 48px;
            margin-bottom: 1rem;
        }

        .benefit-icon .fa-shipping-fast {
            color: #3b82f6;
        }

        .benefit-icon .fa-fire-alt {
            color: #ef4444;
        }

        .benefit-icon .fa-tags {
            color: #10b981;
        }

        .benefit-icon .fa-star {
            color: #fbbf24;
        }

        .benefit-item {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .benefit-item:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .benefit-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
    </style>
</main>

<?php
include INCLUDES_PATH . '/footer.php';
?>