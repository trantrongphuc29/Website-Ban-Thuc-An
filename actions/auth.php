<?php
// Xử lý đăng nhập / đăng ký
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Khởi tạo biến dùng cho thông báo trong modal
$loginError = $registerError = '';
$authSuccess = '';
$openLoginModal = false;
$openRegisterModal = false;

require_once __DIR__ . '/../config/config.php';

// Kết nối database
$conn = getDBConnection();

// Xử lý đăng xuất
if (!empty($_GET['logout'])) {
    unset($_SESSION['user']);
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

// Xử lý submit form đăng nhập / đăng ký (từ modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auth_type'])) {
    $authType = $_POST['auth_type'];

    if ($authType === 'login') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $loginError = 'Vui lòng nhập đầy đủ email và mật khẩu.';
        } else {
            $stmt = $conn->prepare("SELECT user_id, email, fullname, phone, status FROM users WHERE email = ? AND password = ?");
            $passwordHash = hash('sha256', $password);
            $stmt->bind_param("ss", $email, $passwordHash);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($user['status'] === 'blocked') {
                    $loginError = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.';
                } else {
                    unset($user['status']); // Không lưu status vào session
                    $_SESSION['user'] = $user;
                    $authSuccess = 'Đăng nhập thành công.';
                }
            } else {
                $loginError = 'Email hoặc mật khẩu không đúng.';
            }
            $stmt->close();
        }

        if ($loginError !== '') {
            $openLoginModal = true;
        }
    } elseif ($authType === 'register') {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $agree = !empty($_POST['agree']);

        if ($name === '' || $phone === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            $registerError = 'Vui lòng nhập đầy đủ tất cả các trường.';
        } elseif ($password !== $passwordConfirm) {
            $registerError = 'Mật khẩu nhập lại không khớp.';
        } elseif (!$agree) {
            $registerError = 'Bạn cần đồng ý với Điều khoản sử dụng và Chính sách bảo mật.';
        } else {
            // Kiểm tra email đã tồn tại chưa
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $registerError = 'Email này đã được đăng ký. Vui lòng dùng email khác hoặc đăng nhập.';
                $stmt->close();
            } else {
                $stmt->close();
                // Thêm user mới vào database
                $passwordHash = hash('sha256', $password);
                $stmt = $conn->prepare("INSERT INTO users (email, password, fullname, phone) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $email, $passwordHash, $name, $phone);
                
                if ($stmt->execute()) {
                    $userId = $conn->insert_id;
                    $_SESSION['user'] = [
                        'user_id' => $userId,
                        'email' => $email,
                        'fullname' => $name,
                        'phone' => $phone
                    ];
                    $authSuccess = 'Đăng ký tài khoản thành công.';
                } else {
                    $registerError = 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.';
                }
                $stmt->close();
            }
        }

        if ($registerError !== '') {
            $openRegisterModal = true;
        }
    }
}

// Đóng kết nối database
if (isset($conn)) {
    $conn->close();
}
?>