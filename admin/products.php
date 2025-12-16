<?php
require_once '../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Lấy danh sách bảng sản phẩm
$tables = ['bestseller', 'burger', 'combo', 'garan', 'khuyenmai', 'miy', 'nuocuong'];
$selectedTable = $_GET['table'] ?? 'bestseller';

// Xóa sản phẩm
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $table = $_GET['table'];

    // Lấy thông tin ảnh trước khi xóa
    $product = $conn->query("SELECT image_url FROM `$table` WHERE product_id = $id")->fetch_assoc();

    // Xóa sản phẩm
    $conn->query("DELETE FROM `$table` WHERE product_id = $id");

    // Xóa file ảnh
    if ($product && file_exists('../' . $product['image_url'])) {
        unlink('../' . $product['image_url']);
    }

    header("Location: products.php?table=$table");
    exit;
}

// Lấy sản phẩm
$products = $conn->query("SELECT * FROM `$selectedTable` ORDER BY product_id DESC")->fetch_all(MYSQLI_ASSOC);

$conn->close();
$pageTitle = 'Quản lý sản phẩm';
include 'includes/header.php';
?>
<style>
    .success-msg { background: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
    .toolbar { display: flex; justify-content: space-between; margin-bottom: 1rem; }
    .category-tabs { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .category-tabs a { padding: 0.5rem 1rem; border-radius: 8px; background: #f0f0f0; font-size: 14px; text-decoration: none; color: #333; }
    .category-tabs a.active { background: #e11b22; color: white; }
    .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    .btn-edit { background: #3b82f6; color: white; }
    .btn-delete { background: #ef4444; color: white; }
    .btn-primary { padding: 0.5rem 1rem; background: #10b981; color: white; border-radius: 8px; text-decoration: none; }
</style>

        <div class="content-card">
            <?php if (isset($_GET['success'])): ?>
                <div class="success-msg">
                    <?php echo $_GET['success'] === 'added' ? 'Thêm sản phẩm thành công!' : 'Cập nhật sản phẩm thành công!'; ?>
                </div>
            <?php endif; ?>
            <div class="toolbar">
                <div class="category-tabs">
                    <?php foreach ($tables as $table): ?>
                        <a href="?table=<?php echo $table; ?>" class="<?php echo $selectedTable === $table ? 'active' : ''; ?>">
                            <?php echo ucfirst($table); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <a href="add_product.php" class="btn-primary">+ Thêm sản phẩm</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá KM</th>
                        <th>Giá gốc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><img src="<?php echo BASE_URL . '/' . $product['image_url']; ?>" class="product-img" alt=""></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo number_format($product['price']); ?>đ</td>
                            <td><?php echo $product['promotion'] ? number_format($product['promotion']) . 'đ' : '-'; ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $product['product_id']; ?>&table=<?php echo $selectedTable; ?>" class="btn-sm btn-edit">Sửa</a>
                                <a href="?delete=<?php echo $product['product_id']; ?>&table=<?php echo $selectedTable; ?>" class="btn-sm btn-delete" onclick="return confirm('Xác nhận xóa sản phẩm này?')">Xóa</a>
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
