<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle ?? 'Admin - FoodShop'; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f5f5f5; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        a { text-decoration: none; }
        
        .admin-container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .admin-header { background: white; padding: 1rem 2rem; margin-bottom: 2rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .admin-header h1 { margin: 0; font-size: 24px; color: #111; }
        .admin-nav { display: flex; gap: 1rem; }
        .admin-nav a { padding: 0.5rem 1rem; border-radius: 8px; background: #f0f0f0; transition: all 0.2s; text-decoration: none; color: #333; }
        .admin-nav a:hover, .admin-nav a.active { background: #e11b22; color: white !important; }
        
        .content-card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .content-card h2 { margin-bottom: 1rem; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        table th { text-align: left; padding: 0.75rem; background: #f9f9f9; font-weight: 600; }
        table td { padding: 0.75rem; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block; }
        .badge.pending { background: #fef3c7; color: #92400e; }
        .badge.preparing { background: #dbeafe; color: #1e40af; }
        .badge.delivering { background: #e0e7ff; color: #4338ca; }
        .badge.completed { background: #dcfce7; color: #166534; }
        .badge.cancelled { background: #fee2e2; color: #991b1b; }
        .badge.active { background: #dcfce7; color: #166534; }
        .badge.blocked { background: #fee2e2; color: #991b1b; }
        
        .btn-sm { padding: 0.4rem 0.8rem; border-radius: 6px; text-decoration: none !important; font-size: 13px; display: inline-block; margin-right: 0.25rem; border: none; cursor: pointer; white-space: nowrap; }
        .filter-tab { padding: 0.5rem 1rem; border-radius: 8px; background: #f0f0f0; transition: all 0.2s; text-decoration: none !important; color: #333 !important; display: inline-block; white-space: nowrap; }
        .filter-tab:hover, .filter-tab.active { background: #e11b22 !important; color: white !important; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><?php echo $pageTitle ?? 'Admin Panel'; ?></h1>
            <div class="admin-nav">
                <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Dashboard</a>
                <a href="products.php" <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'class="active"' : ''; ?>>Sản phẩm</a>
                <a href="orders.php" <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'class="active"' : ''; ?>>Đơn hàng</a>
                <a href="users.php" <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'class="active"' : ''; ?>>Người dùng</a>
                <a href="revenue.php" <?php echo basename($_SERVER['PHP_SELF']) == 'revenue.php' ? 'class="active"' : ''; ?>>Doanh thu</a>
                <a href="?logout=1">Đăng xuất</a>
            </div>
        </div>
