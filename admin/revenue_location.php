<?php
// Lọc theo thời gian
if ($type === 'day') {
    if (isset($_GET['date']) && !empty($_GET['date'])) {
        $date = $_GET['date'];
        $dateCondition = "DATE(created_at) = '$date'";
    } else {
        $dateCondition = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    }
} elseif ($type === 'month') {
    if (isset($_GET['month']) && !empty($_GET['month'])) {
        $month = $_GET['month'];
        $dateCondition = "DATE_FORMAT(created_at, '%Y-%m') = '$month'";
    } else {
        $dateCondition = "created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)";
    }
} else {
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $q = $_GET['q'];
        list($year, $quarter) = explode('-Q', $q);
        $dateCondition = "YEAR(created_at) = $year AND QUARTER(created_at) = $quarter";
    } else {
        $dateCondition = "created_at >= DATE_SUB(NOW(), INTERVAL 2 YEAR)";
    }
}

// Lấy doanh thu theo khu vực (từ order_id)
$sql = "SELECT 
        CASE 
            WHEN order_id LIKE 'HCM-%' THEN 'TP. Hồ Chí Minh'
            WHEN order_id LIKE 'HN-%' THEN 'Hà Nội'
            WHEN order_id LIKE 'DN-%' THEN 'Đà Nẵng'
            WHEN order_id LIKE 'CT-%' THEN 'Cần Thơ'
            WHEN order_id LIKE 'HP-%' THEN 'Hải Phòng'
            ELSE 'Khác'
        END as location,
        COUNT(*) as total_orders,
        SUM(total_price) as total_revenue
        FROM orders
        WHERE status = 'completed' AND $dateCondition
        GROUP BY location
        ORDER BY total_revenue DESC";

$result = $conn->query($sql);
$locations = $result->fetch_all(MYSQLI_ASSOC);

$totalRevenue = array_sum(array_column($locations, 'total_revenue'));
$totalOrders = array_sum(array_column($locations, 'total_orders'));

$labels = array_column($locations, 'location');
$revenues = array_column($locations, 'total_revenue');
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
        <h3>Tổng doanh thu <?php echo $periodLabel; ?></h3>
        <div class="value"><?php echo number_format($totalRevenue); ?>đ</div>
    </div>
    <div class="stat-card">
        <h3>Tổng đơn hàng <?php echo $periodLabel; ?></h3>
        <div class="value"><?php echo number_format($totalOrders); ?></div>
    </div>
    <div class="stat-card">
        <h3>Số cửa hàng</h3>
        <div class="value"><?php echo count($locations); ?></div>
    </div>
</div>

<div class="content-card">
    <div class="filter-tabs">
        <a href="?view=location&type=day" class="filter-tab <?php echo $type === 'day' ? 'active' : ''; ?>">Theo ngày</a>
        <a href="?view=location&type=month" class="filter-tab <?php echo $type === 'month' ? 'active' : ''; ?>">Theo tháng</a>
        <a href="?view=location&type=quarter" class="filter-tab <?php echo $type === 'quarter' ? 'active' : ''; ?>">Theo quý</a>
        
        <?php if ($type === 'day'): ?>
            <select onchange="window.location.href='?view=location&type=day&date='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
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
            <select onchange="window.location.href='?view=location&type=month&month='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
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
            <select onchange="window.location.href='?view=location&type=quarter&q='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
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
    <h2>Doanh thu theo cửa hàng</h2>
    <div class="chart-container">
        <canvas id="locationChart"></canvas>
    </div>
</div>

<div class="content-card">
    <h2>Chi tiết theo cửa hàng</h2>
    <table>
        <thead>
            <tr>
                <th>Cửa hàng</th>
                <th>Số đơn hàng</th>
                <th>Doanh thu</th>
                <th>Trung bình/đơn</th>
                <th>Tỷ lệ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locations as $loc): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($loc['location']); ?></strong></td>
                <td><?php echo number_format($loc['total_orders']); ?></td>
                <td><?php echo number_format($loc['total_revenue']); ?>đ</td>
                <td><?php echo number_format($loc['total_revenue'] / $loc['total_orders']); ?>đ</td>
                <td><?php echo number_format(($loc['total_revenue'] / $totalRevenue) * 100, 1); ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
const ctx2 = document.getElementById('locationChart').getContext('2d');
new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($revenues); ?>,
            backgroundColor: ['#e11b22', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});
</script>
