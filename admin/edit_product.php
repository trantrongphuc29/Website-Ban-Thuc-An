<?php
require_once '../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)$_GET['id'];
$table = $_GET['table'];
$message = '';
$error = '';

$conn = getDBConnection();

// Lấy thông tin sản phẩm
$product = $conn->query("SELECT * FROM `$table` WHERE product_id = $id")->fetch_assoc();

if (!$product) {
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $promotion = !empty($_POST['promotion']) ? (float)$_POST['promotion'] : null;
    $imageUrl = $product['image_url'];
    
    // Xử lý ảnh mới nếu có
    if (!empty($_POST['cropped_image'])) {
        $imageData = $_POST['cropped_image'];
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $data = base64_decode($imageData);
        
        $fileName = uniqid() . '.jpg';
        $uploadPath = '../uploads/' . $fileName;
        
        if (file_put_contents($uploadPath, $data)) {
            // Xóa ảnh cũ
            if (file_exists('../' . $product['image_url'])) {
                unlink('../' . $product['image_url']);
            }
            $imageUrl = 'uploads/' . $fileName;
        }
    }
    
    $sql = "UPDATE `$table` SET name = ?, description = ?, price = ?, promotion = ?, image_url = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddsi", $name, $description, $price, $promotion, $imageUrl, $id);
    
    if ($stmt->execute()) {
        header("Location: products.php?table=$table&success=updated");
        exit;
    } else {
        $error = 'Có lỗi xảy ra: ' . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/style.css">
    <style>
        body { background: #f5f5f5; }
        .admin-container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .admin-header { background: white; padding: 1rem 2rem; margin-bottom: 2rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .admin-nav { display: flex; gap: 1rem; }
        .admin-nav a { padding: 0.5rem 1rem; border-radius: 8px; background: #f0f0f0; transition: all 0.2s; }
        .admin-nav a:hover, .admin-nav a.active { background: #e11b22; color: white; }
        .content-card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-width: 800px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .image-preview { margin-top: 1rem; max-width: 200px; border-radius: 8px; }
        .success-msg { background: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .error-msg { background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .btn-group { display: flex; gap: 1rem; }
        .btn-cancel { background: #6b7280; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Sửa sản phẩm</h1>
            <div class="admin-nav">
                <a href="index.php">Dashboard</a>
                <a href="products.php" class="active">Sản phẩm</a>
                <a href="orders.php">Đơn hàng</a>
                <a href="users.php">Người dùng</a>
                <a href="?logout=1">Đăng xuất</a>
            </div>
        </div>

        <div class="content-card">
            <?php if ($message): ?>
                <div class="success-msg"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Danh mục</label>
                    <input type="text" value="<?php echo ucfirst($table); ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Tên sản phẩm *</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
                </div>

                <div class="form-group">
                    <label>Mô tả</label>
                    <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Giá bán *</label>
                    <input type="number" name="price" required value="<?php echo $product['price']; ?>" min="0" step="1000">
                </div>

                <div class="form-group">
                    <label>Giá khuyến mãi</label>
                    <input type="number" name="promotion" value="<?php echo $product['promotion']; ?>" min="0" step="1000">
                </div>

                <div class="form-group">
                    <label>Hình ảnh hiện tại</label>
                    <img src="<?php echo BASE_URL . '/' . $product['image_url']; ?>" class="image-preview" alt="Current">
                </div>

                <div class="form-group">
                    <label>Thay đổi hình ảnh (Tùy chọn - Tỷ lệ 4:3)</label>
                    <input type="file" name="image" accept="image/*" id="imageInput" onchange="previewImage(this)">
                    <div id="cropContainer" style="display:none; margin-top:1rem;">
                        <img id="imageToCrop" style="max-width:100%;">
                        <button type="button" onclick="cropImage()" class="btn-primary" style="margin-top:0.5rem;">Cắt ảnh</button>
                    </div>
                    <img id="imagePreview" class="image-preview" style="display:none;" alt="Preview">
                    <input type="hidden" name="cropped_image" id="croppedImageData">
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-primary" id="submitBtn">Cập nhật sản phẩm</button>
                    <a href="products.php?table=<?php echo $table; ?>" class="btn-cancel">Hủy</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script>
    let cropper;
    let imageSelected = false;
    function previewImage(input) {
        const cropContainer = document.getElementById('cropContainer');
        const imageToCrop = document.getElementById('imageToCrop');
        const submitBtn = document.getElementById('submitBtn');
        
        if (input.files && input.files[0]) {
            imageSelected = true;
            submitBtn.disabled = true;
            const reader = new FileReader();
            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                cropContainer.style.display = 'block';
                
                if (cropper) cropper.destroy();
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 4 / 3,
                    viewMode: 1,
                    autoCropArea: 1
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function cropImage() {
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 600
        });
        
        const preview = document.getElementById('imagePreview');
        const croppedData = document.getElementById('croppedImageData');
        const cropContainer = document.getElementById('cropContainer');
        const submitBtn = document.getElementById('submitBtn');
        
        preview.src = canvas.toDataURL('image/jpeg');
        preview.style.display = 'block';
        croppedData.value = canvas.toDataURL('image/jpeg');
        cropContainer.style.display = 'none';
        submitBtn.disabled = false;
        cropper.destroy();
    }
    </script>
</body>
</html>
<?php
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    header('Location: login.php');
    exit;
}
?>
