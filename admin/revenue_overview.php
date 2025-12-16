<?php
// Thống kê theo ngày
if ($type === 'day') {
    if (isset($_GET['date']) && !empty($_GET['date'])) {
        $date = $_GET['date'];
        $sql = "SELECT DATE(created_at) as period, SUM(total_price) as revenue, COUNT(*) as orders 
                FROM orders 
                WHERE status = 'completed' AND DATE(created_at) = '$date'
                GROUP BY DATE(created_at)";
        $chartTitle = "Doanh thu ngày " . date('d/m/Y', strtotime($date));
    } else {
        $sql = "SELECT DATE(created_at) as period, SUM(total_price) as revenue, COUNT(*) as orders 
                FROM orders 
                WHERE status = 'completed' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY DATE(created_at) 
                ORDER BY period ASC";
        $chartTitle = "Doanh thu 7 ngày gần nhất";
    }
}
// Thống kê theo tháng
elseif ($type === 'month') {
    if (isset($_GET['month']) && !empty($_GET['month'])) {
        $month = $_GET['month'];
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as period, SUM(total_price) as revenue, COUNT(*) as orders 
                FROM orders 
                WHERE status = 'completed' AND DATE_FORMAT(created_at, '%Y-%m') = '$month'
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')";
        $chartTitle = "Doanh thu tháng " . date('m/Y', strtotime($month));
    } else {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as period, SUM(total_price) as revenue, COUNT(*) as orders 
                FROM orders 
                WHERE status = 'completed' AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                ORDER BY period ASC";
        $chartTitle = "Doanh thu 12 tháng gần nhất";
    }
}
// Thống kê theo quý
else {
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $q = $_GET['q'];
        list($year, $quarter) = explode('-Q', $q);
        $sql = "SELECT CONCAT(YEAR(created_at), '-Q', QUARTER(created_at)) as period, 
                SUM(total_price) as revenue, COUNT(*) as orders 
                FROM orders 
                WHERE status = 'completed' AND YEAR(created_at) = $year AND QUARTER(created_at) = $quarter
                GROUP BY YEAR(created_at), QUARTER(created_at)";
        $chartTitle = "Doanh thu quý $quarter/$year";
    } else {
        $sql = "SELECT CONCAT(YEAR(created_at), '-Q', QUARTER(created_at)) as period, 
                SUM(total_price) as revenue, COUNT(*) as orders 
                FROM orders 
                WHERE status = 'completed' AND created_at >= DATE_SUB(NOW(), INTERVAL 2 YEAR)
                GROUP BY YEAR(created_at), QUARTER(created_at) 
                ORDER BY YEAR(created_at), QUARTER(created_at) ASC";
        $chartTitle = "Doanh thu theo quý";
    }
}

$result = $conn->query($sql);
$data = $result->fetch_all(MYSQLI_ASSOC);

$labels = [];
$revenues = [];
$orderCounts = [];
$totalRevenue = 0;
$totalOrders = 0;

foreach ($data as $row) {
    $labels[] = $row['period'];
    $revenues[] = (float)$row['revenue'];
    $orderCounts[] = (int)$row['orders'];
    
    if ($selectedPeriod) {
        if ($row['period'] === $selectedPeriod) {
            $totalRevenue = (float)$row['revenue'];
            $totalOrders = (int)$row['orders'];
        }
    } else {
        $totalRevenue += (float)$row['revenue'];
        $totalOrders += (int)$row['orders'];
    }
}

$avgRevenue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
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
    <div class="stat-card" style="cursor:pointer;" onclick="window.location.href='?view=overview&type=<?php echo $type; ?>'">
        <h3>Tổng doanh thu <?php echo $periodLabel; ?></h3>
        <div class="value"><?php echo number_format($totalRevenue); ?>đ</div>
    </div>
    <div class="stat-card" style="cursor:pointer;" onclick="window.location.href='?view=overview&type=<?php echo $type; ?>'">
        <h3>Tổng đơn hàng <?php echo $periodLabel; ?></h3>
        <div class="value"><?php echo number_format($totalOrders); ?></div>
    </div>
    <div class="stat-card">
        <h3>Trung bình/đơn</h3>
        <div class="value"><?php echo number_format($avgRevenue); ?>đ</div>
    </div>
</div>

<div class="content-card">
    <div class="filter-tabs">
        <a href="?view=overview&type=day" class="filter-tab <?php echo $type === 'day' ? 'active' : ''; ?>">Theo ngày</a>
        <a href="?view=overview&type=month" class="filter-tab <?php echo $type === 'month' ? 'active' : ''; ?>">Theo tháng</a>
        <a href="?view=overview&type=quarter" class="filter-tab <?php echo $type === 'quarter' ? 'active' : ''; ?>">Theo quý</a>
        
        <?php if ($type === 'day'): ?>
            <select onchange="window.location.href='?view=overview&type=day&date='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
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
            <select onchange="window.location.href='?view=overview&type=month&month='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
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
            <select onchange="window.location.href='?view=overview&type=quarter&q='+this.value" style="margin-left:auto;padding:0.5rem;border-radius:8px;border:1px solid #ddd;">
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
    
    <h2><?php echo $chartTitle; ?></h2>
    <div class="chart-container">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<div class="content-card">
    <h2>Chi tiết doanh thu</h2>
    <table>
        <thead>
            <tr>
                <th>Thời gian</th>
                <th>Số đơn hàng</th>
                <th>Doanh thu</th>
                <th>Trung bình/đơn</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
            <tr>
                <td><?php echo $row['period']; ?></td>
                <td><?php echo number_format($row['orders']); ?></td>
                <td><?php echo number_format($row['revenue']); ?>đ</td>
                <td><?php echo number_format($row['revenue'] / $row['orders']); ?>đ</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Doanh thu (đ)',
            data: <?php echo json_encode($revenues); ?>,
            backgroundColor: 'rgba(225, 27, 34, 0.8)',
            yAxisID: 'y'
        }, {
            label: 'Số đơn hàng',
            data: <?php echo json_encode($orderCounts); ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { position: 'left', title: { display: true, text: 'Doanh thu (đ)' }},
            y1: { position: 'right', title: { display: true, text: 'Số đơn hàng' }, grid: { drawOnChartArea: false }}
        }
    }
});
</script>
