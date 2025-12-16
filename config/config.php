<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Cấu hình đường dẫn
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '');

// Đường dẫn thư mục
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('PAGES_PATH', BASE_PATH . '/pages');
define('ACTIONS_PATH', BASE_PATH . '/actions');
define('CATEGORY_PATH', BASE_PATH . '/category');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('MODELS_PATH', BASE_PATH . '/models');
define('ADMIN_PATH', BASE_PATH . '/admin');

// URL
define('ASSETS_URL', BASE_URL . '/assets');
define('PAGES_URL', BASE_URL . '/pages');
define('ACTIONS_URL', BASE_URL . '/actions');
define('CATEGORY_URL', BASE_URL . '/category');
define('ADMIN_URL', BASE_URL . '/admin');

// Include database config
require_once __DIR__ . '/database.php';

// Khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
