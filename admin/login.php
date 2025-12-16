<?php
require_once '../config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username && $password) {
        $conn = getDBConnection();
        $passwordHash = hash('sha256', $password);
        
        $stmt = $conn->prepare("SELECT admin_id, username FROM admins WHERE username = ? AND password_hash = ?");
        $stmt->bind_param("ss", $username, $passwordHash);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
        }
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Vui lòng nhập đầy đủ thông tin';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .admin-login {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .login-card h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }
        .error-msg {
            background: #fee;
            color: #c33;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 14px;
        }
        .auth-form .form-group {
            margin-bottom: 1rem;
        }
        .auth-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        .auth-form input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            background: #e11b22;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: #c41e3a;
        }
    </style>
</head>
<body>
    <div class="admin-login">
        <div class="login-card">
            <h1>Admin Login</h1>
            <p style="text-align: center; color: #666; margin-bottom: 1.5rem; font-size: 14px;">
                Tên đăng nhập: <strong>admin</strong><br>
                Mật khẩu: <strong>admin123</strong>
            </p>
            <?php if ($error): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary auth-submit">Đăng nhập</button>
            </form>
        </div>
    </div>
</body>
</html>
