<?php
require_once '../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();
$view = $_GET['view'] ?? 'overview';
$type = $_GET['type'] ?? 'day';
$selectedPeriod = $_GET['period'] ?? null;


$pageTitle = 'Thống kê doanh thu';
include 'includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .revenue-layout { display: flex; gap: 2rem; margin-top: 0; }
    .revenue-sidebar { width: 250px; background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: fit-content; position: sticky; top: 20px; }
    .revenue-sidebar h3 { font-size: 16px; margin: 0 0 1rem 0; }
    .sidebar-menu { list-style: none; padding: 0; margin: 0; }
    .sidebar-menu li { margin-bottom: 0.5rem; }
    .sidebar-menu a { display: block; padding: 0.75rem; border-radius: 8px; color: #333; text-decoration: none; transition: all 0.2s; }
    .sidebar-menu a:hover, .sidebar-menu a.active { background: #e11b22; color: white !important; }
    .revenue-content { flex: 1; min-width: 0; }
    .revenue-content .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
    .revenue-content .stat-card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .revenue-content .stat-card h3 { font-size: 14px; color: #666; margin: 0 0 0.5rem 0; }
    .revenue-content .stat-card .value { font-size: 32px; font-weight: bold; color: #e11b22; margin: 0; }
    .revenue-content .chart-container { position: relative; height: 400px; width: 100%; }
    .revenue-content .content-card + .content-card { margin-top: 2rem; }
    .revenue-content .filter-tabs + h2 { margin-top: 1.5rem; }
    .revenue-content table tbody tr:hover { background: #f9f9f9; }
</style>

<div class="revenue-layout">
    <div class="revenue-sidebar">
        <h3>Doanh thu</h3>
        <ul class="sidebar-menu">
            <li><a href="?view=overview&type=day" class="<?php echo $view === 'overview' ? 'active' : ''; ?>">Tổng quan</a></li>
            <li><a href="?view=product" class="<?php echo $view === 'product' ? 'active' : ''; ?>">Theo món ăn</a></li>
            <li><a href="?view=location" class="<?php echo $view === 'location' ? 'active' : ''; ?>">Theo cửa hàng</a></li>
        </ul>
    </div>
    <div class="revenue-content">
        <?php 
        if ($view === 'product') {
            include 'revenue_product.php';
        } elseif ($view === 'location') {
            include 'revenue_location.php';
        } else {
            include 'revenue_overview.php';
        }
        ?>
    </div>
</div>

<?php $conn->close(); ?>
<?php
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    header('Location: login.php');
    exit;
}
include 'includes/footer.php';
?>
