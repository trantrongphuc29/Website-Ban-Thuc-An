<?php
require_once '../config/config.php';
$pageTitle = 'Tìm kiếm sản phẩm';

$keyword = $_GET['q'] ?? '';
$products = [];

if (!empty($keyword)) {
    $conn = getDBConnection();
    $searchTerm = '%' . $keyword . '%';

    // Tìm kiếm trong tất cả các bảng
    $tables = ['bestseller', 'burger', 'combo', 'garan', 'khuyenmai', 'miy', 'nuocuong'];

    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT *, '$table' as category FROM `$table` WHERE name LIKE ? OR description LIKE ?");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
    }

    $conn->close();
}

include INCLUDES_PATH . '/header.php';
?>

<main class="main">
    <div class="search-page">
        <h1>Kết quả tìm kiếm: "<?php echo htmlspecialchars($keyword); ?>"</h1>
        <p>Tìm thấy <?php echo count($products); ?> sản phẩm</p>

        <?php if (!empty($products)): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <article class="product-card">
                        <div class="product-image">
                            <img src="<?php echo BASE_URL . '/' . $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php if ($product['promotion']): ?>
                                <span class="badge">-<?php echo round((($product['price'] - $product['promotion']) / $product['price']) * 100); ?>%</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-body">
                            <h2 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="product-desc"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="product-price">
                                <?php if ($product['promotion']): ?>
                                    <span class="price-old"><?php echo number_format($product['price']); ?>đ</span>
                                    <div class="price-row">
                                        <span class="price-current"><?php echo number_format($product['promotion']); ?>đ</span>
                                        <button class="btn-add" data-id="<?php echo $product['product_id']; ?>" data-table="<?php echo $product['category']; ?>">+</button>
                                    </div>
                                <?php else: ?>
                                    <div class="price-row">
                                        <span class="price-current"><?php echo number_format($product['price']); ?>đ</span>
                                        <button class="btn-add" data-id="<?php echo $product['product_id']; ?>" data-table="<?php echo $product['category']; ?>">+</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:3rem;">
                <i class="fas fa-search" style="font-size:64px;color:#ccc;margin-bottom:1rem;"></i>
                <p style="font-size:18px;color:#666;">Không tìm thấy sản phẩm nào phù hợp</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
    .search-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .search-page h1 {
        font-size: 28px;
        margin-bottom: 0.5rem;
        color: #111827;
        font-weight: 700;
    }

    .search-page>p {
        color: #6b7280;
        margin-bottom: 2rem;
        font-size: 15px;
    }

    .search-page .product-grid {
        margin-top: 2rem;
    }
</style>

<?php include INCLUDES_PATH . '/footer.php'; ?>