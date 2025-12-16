<?php
// Helper function để tạo mã đơn hàng
function generateOrderCode($conn, $shippingAddress) {
    // Xác định khu vực từ địa chỉ
    $region = 'HCM'; // Mặc định
    
    if (stripos($shippingAddress, 'Hà Nội') !== false || stripos($shippingAddress, 'Ha Noi') !== false) {
        $region = 'HN';
    } elseif (stripos($shippingAddress, 'Đà Nẵng') !== false || stripos($shippingAddress, 'Da Nang') !== false) {
        $region = 'DN';
    } elseif (stripos($shippingAddress, 'Cần Thơ') !== false || stripos($shippingAddress, 'Can Tho') !== false) {
        $region = 'CT';
    } elseif (stripos($shippingAddress, 'Hải Phòng') !== false || stripos($shippingAddress, 'Hai Phong') !== false) {
        $region = 'HP';
    }
    
    // Lấy ngày hiện tại (YYYYMMDD)
    $date = date('Ymd');
    
    // Lock table để tránh race condition
    $conn->query("LOCK TABLES orders WRITE");
    
    // Lấy số đơn hàng lớn nhất trong ngày
    $sql = "SELECT MAX(CAST(SUBSTRING_INDEX(order_id, '-', -1) AS UNSIGNED)) as max_num FROM orders WHERE order_id LIKE ?";
    $stmt = $conn->prepare($sql);
    $pattern = $region . '-' . $date . '-%';
    $stmt->bind_param("s", $pattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = ($row['max_num'] ? intval($row['max_num']) : 0) + 1;
    $stmt->close();
    
    // Format: HCM-20251208-001
    $orderCode = sprintf("%s-%s-%03d", $region, $date, $count);
    
    return $orderCode;
}
?>
