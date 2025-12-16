<?php
// Lọc theo thời gian
if ($type === 'day') {
    if (isset($_GET['date']) && !empty($_GET['date'])) {
        $date = $_GET['date'];
        $dateCondition = "DATE(o.created_at) = '$date'";
    } else {
        $dateCondition = "o.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    }
} elseif ($type === 'month') {
    if (isset($_GET['month']) && !empty($_GET['month'])) {
        $month = $_GET['month'];
        $dateCondition = "DATE_FORMAT(o.created_at, '%Y-%m') = '$month'";
    } else {
        $dateCondition = "o.created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)";
    }
} else {
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $q = $_GET['q'];
        list($year, $quarter) = explode('-Q', $q);
        $dateCondition = "YEAR(o.created_at) = $year AND QUARTER(o.created_at) = $quarter";
    } else {
        $dateCondition = "o.created_at >= DATE_SUB(NOW(), INTERVAL 2 YEAR)";
    }
}

// Lấy doanh thu theo món ăn
$sql = "SELECT oi.product_table, oi.product_id, p.name, p.image_url, 
        SUM(oi.quantity) as total_quantity, 
        SUM(oi.price * oi.quantity) as total_revenue,
        COUNT(DISTINCT oi.order_id) as order_count
        FROM orderitems oi
        JOIN orders o ON oi.order_id = o.order_id
        LEFT JOIN (
            SELECT product_id, name, image_url, 'bestseller' as tbl FROM bestseller
            UNION SELECT product_id, name, image_url, 'burger' FROM burger
            UNION SELECT product_id, name, image_url, 'combo' FROM combo
            UNION SELECT product_id, name, image_url, 'garan' FROM garan
            UNION SELECT product_id, name, image_url, 'khuyenmai' FROM khuyenmai
            UNION SELECT product_id, name, image_url, 'miy' FROM miy
            UNION SELECT product_id, name, image_url, 'nuocuong' FROM nuocuong
        ) p ON oi.product_id = p.product_id AND oi.product_table = p.tbl
        WHERE o.status = 'completed' AND $dateCondition AND p.name IS NOT NULL
        GROUP BY oi.product_table, oi.product_id
        ORDER BY total_revenue DESC";

$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);

$topProduct = $products[0] ?? null;
$lowProduct = end($products);
$totalRevenue = array_sum(array_column($products, 'total_revenue'));
?>

<?php
$periodLabel = '';
if (isset($_GET['date']) && !empty($_GET['date'])) {
    $periodLabel = '(' . date('d/m/Y', strtotime($_GET['date'])) . ')';
} elseif (isset($_GET['month']) && !empty($_GET['month'])) {
    $periodLabel = '(Tháng ' . date('m/Y', strtotime($_GET['month'])) . ')';
} elseif (isset($_GET['q']) && !empty($_GET['q'])) {
    list($y, $q) = explode('-Q', $_GET['q']);
    $periodLabel = "(Quý $q/$y)";
} else {
    $periodLabel = $type === 'day' ? '(7 ngày)' : ($type === 'month' ? '(12 tháng)' : '(Theo quý)');
}
?>
<div class="stats-grid">
    <div class="stat-card">
        <h3>Tổng doanh thu món ăn <?php echo $periodLabel; ?></h3>
        <div class="value"><?php echo number_format($totalRevenue); ?>đ</div>
    </div>
    <div class="stat-card">
        <h3>Món bán chạy nhất <?php echo $periodLabel; ?></h3>
        <div class="value" style="font-size:18px;"><?php echo $topProduct ? htmlspecialchars($topProduct['name']) : '-'; ?></div>
        <small><?php echo $topProduct ? number_format($topProduct['total_revenue']) . 'đ' : ''; ?></small>
    </div>
    <div class="stat-card">
        <h3>Tổng số món</h3>
        <div class="value"><?php echo count($products); ?></div>
    </div>
</div>

<div class="content-card">
    <div class="filter-tabs">
        <a href="?view=product&type=day" class="filter-tab <?php echo $type === 'day' ? 'active' : ''; ?>">Theo ngày</a>
        <a href="?view=product&type=month" class="filter-tab <?php echo $type === 'month' ? 'active' : ''; ?>">Theo tháng</a>
        <a href="?view=product&type=quarter" class="filter-tab <?php echo $type === 'quarter' ? 'active' : ''; ?>">Theo quý</a>
        
        <?php if ($type === 'day'): ?>
            <select onchange="window.location.href='?view=product&type=day&date='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
                <option value="">Chọn ngày</option>
                <?php 
                for ($i = 0; $i < 30; $i++) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $selected = (isset($_GET['date']) && $_GET['date'] === $date) ? 'selected' : '';
                    echo "<option value='$date' $selected>" . date('d/m/Y', strtotime($date)) . "</option>";
                }
                ?>
            </select>
        <?php elseif ($type === 'month'): ?>
            <select onchange="window.location.href='?view=product&type=month&month='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
                <option value="">Chọn tháng</option>
                <?php 
                for ($i = 0; $i < 12; $i++) {
                    $month = date('Y-m', strtotime("-$i months"));
                    $selected = (isset($_GET['month']) && $_GET['month'] === $month) ? 'selected' : '';
                    echo "<option value='$month' $selected>Tháng " . date('m/Y', strtotime($month)) . "</option>";
                }
                ?>
            </select>
        <?php elseif ($type === 'quarter'): ?>
            <select onchange="window.location.href='?view=product&type=quarter&q='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
                <option value="">Chọn quý</option>
                <?php 
                for ($i = 0; $i < 8; $i++) {
                    $year = date('Y', strtotime("-" . floor($i/4) . " years"));
                    $quarter = 4 - ($i % 4);
                    $qValue = "$year-Q$quarter";
                    $selected = (isset($_GET['q']) && $_GET['q'] === $qValue) ? 'selected' : '';
                    echo "<option value='$qValue' $selected>Quý $quarter/$year</option>";
                }
                ?>
            </select>
        <?php endif; ?>
    </div>
    <h2>Doanh thu theo món ăn</h2>
    <table>
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên món</th>
                <th>Số lượng bán</th>
                <th>Số đơn</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><img src="<?php echo BASE_URL . '/' . $product['image_url']; ?>" style="width:50px;height:50px;object-fit:cover;border-radius:4px;"></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo number_format($product['total_quantity']); ?></td>
                <td><?php echo number_format($product['order_count']); ?></td>
                <td><strong><?php echo number_format($product['total_revenue']); ?>đ</strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
