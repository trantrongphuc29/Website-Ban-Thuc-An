<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab5_4_3</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <form style="background-color: #F0FFFF;" action="lab5_4_3.php" method="post" enctype="multipart/form-data">
        <label for="username">Tên đăng nhập (*):</label>
        <input type="text" name="username" id="username"> <br><br>
        <label for="password">Mật khẩu (*):</label>
        <input type="password" name="password" id="password"> <br><br>
        <label for="confirm_password">Nhập lại mật khẩu (*):</label>
        <input type="password" name="confirm_password" id="confirm_password"> <br><br>
        <label for="gender">Giới tính (*):</label>
        <input type="radio" id="male" name="gender" value="Nam">Nam
        <input type="radio" id="female" name="gender" value="Nữ">Nữ <br><br>
        <label for="sothich">Sở thích:</label> <br>
        <textarea id="sothich" name="sothich"></textarea> <br><br>
        <label for="image">Hình ảnh (tùy chọn):</label>
        <input type="file" id="image" name="image" accept="image/*"> <br><br>
        <label for="tinh">Tỉnh (*):</label>
        <select id="province" name="province" required="">
            <option value="">Chọn tỉnh</option>
            <option value="Hồ Chí Minh">Hồ Chí Minh</option>
            <option value="Khánh Hòa">Khánh Hòa</option>
            <option value="Đồng Tháp">Đồng Tháp</option>
        </select> <br><br>
        <input style="background-color: blue; color: #F0FFFF;"  type="submit" value="Đăng ký" class="btn btn-primary">
        <input style="background-color: red; color: #F0FFFF;" type="reset" value="Reset" class="btn btn-danger">
    </form>
    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Khởi tạo biến lỗi
    $errors = [];

    // Lấy thông tin người dùng nhập vào
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;  // Kiểm tra nếu người dùng chọn giới tính
    $sothich = htmlspecialchars($_POST['sothich']);
    $province = htmlspecialchars($_POST['province']);

    // Kiểm tra các trường bắt buộc
    if (empty($username)) {
        $errors[] = "Hãy nhập tên đăng nhập!";
    }

    if (empty($password)) {
        $errors[] = "Hãy nhập mật khẩu!";
    }

    if (empty($confirm_password)) {
        $errors[] = "Hãy nhập mật khẩu lại!";
    }

    if (empty($gender)) {
        $errors[] = "Hãy chọn giới tính!";
    }

    if (empty($province)) {
        $errors[] = "Vui lòng chọn tỉnh.";
    }

    // Kiểm tra mật khẩu và xác nhận mật khẩu có khớp không
    if ($password != $confirm_password) {
        $errors[] = "Mật khẩu và mật khẩu xác nhận không khớp.";
    }

    // Xử lý hình ảnh (nếu có)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_type = $_FILES['image']['type'];

        // Kiểm tra định dạng hình ảnh (chỉ cho phép các file .jpg, .png)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($image_type, $allowed_types)) {
            $errors[] = "Chỉ hỗ trợ các định dạng hình ảnh JPG, PNG, GIF.";
        }

        // Kiểm tra kích thước hình ảnh (dưới 2MB)
        if ($image_size > 2 * 1024 * 1024) {
            $errors[] = "Kích thước hình ảnh không được vượt quá 2MB.";
        }

        // Di chuyển ảnh vào thư mục
        $image_path = $image_name;
        move_uploaded_file($image_tmp_name, $image_path);
    } else {
        $image_path = 'Không có hình ảnh nào được chọn.';
    }

    // Nếu có lỗi, hiển thị thông báo lỗi
    if (count($errors) > 0) {
        echo "<h3>Vui lòng kiểm tra lại thông tin:</h3>";
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    } else {
        // Nếu không có lỗi, hiển thị thông tin
        echo "<h2>Thông tin bạn đã nhập:</h2>";
        echo "<p><strong>Tên đăng nhập:</strong> $username</p>";
        echo "<p><strong>Mật khẩu:</strong> $password</p>";
        echo "<p><strong>Giới tính:</strong> $gender</p>";
        echo "<p><strong>Sở thích:</strong> $sothich</p>";
        echo "<p><strong>Tỉnh:</strong> $province</p>";

        // Hiển thị hình ảnh
        if ($image_path != 'Không có hình ảnh nào được chọn.') {
            echo "<p><strong>Hình ảnh đã chọn:</strong><br><img src='$image_path' alt='Image' style='max-width: 200px;'></p>";
        } else {
            echo "<p><strong>Không có hình ảnh nào được chọn.</strong></p>";
        }
    }
} else {
    echo "<p>Form chưa được gửi.</p>";
}
?>

</body>

</html>