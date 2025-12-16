<?php
require_once '../config/config.php';

// Kiểm tra đăng nhập
if (empty($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

$pageTitle = 'Tài khoản - FoodShop';
include '../includes/header.php';

$user = $_SESSION['user'];

// Kết nối database để lấy địa chỉ
$conn = getDBConnection();

// Lấy địa chỉ của user
$userId = $user['user_id'];
$addressSql = "SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC";
$stmt = $conn->prepare($addressSql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$addresses = $stmt->get_result();
$stmt->close();

// Lấy đơn hàng của user
$filterStatus = $_GET['status'] ?? 'all';
$orderSql = "SELECT * FROM orders WHERE user_id = ?";
if ($filterStatus !== 'all') {
    $orderSql .= " AND status = '$filterStatus'";
}
$orderSql .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($orderSql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Đếm số lượng theo trạng thái
$counts = [
    'all' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id = $userId")->fetch_assoc()['c'],
    'pending' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id = $userId AND status='pending'")->fetch_assoc()['c'],
    'preparing' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id = $userId AND status='preparing'")->fetch_assoc()['c'],
    'delivering' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id = $userId AND status='delivering'")->fetch_assoc()['c'],
    'completed' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id = $userId AND status='completed'")->fetch_assoc()['c'],
    'cancelled' => $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id = $userId AND status='cancelled'")->fetch_assoc()['c']
];
?>

<link rel="stylesheet" href="../assets/style.css">

<main class="main">
    <div class="account-container">
        <h1>Tài khoản của tôi</h1>
        
        <div class="account-tabs">
            <button class="tab-btn active" onclick="showTab('info')">Thông tin tài khoản</button>
            <button class="tab-btn" onclick="showTab('address')">Địa chỉ giao hàng</button>
            <button class="tab-btn" onclick="showTab('orders')">Đơn hàng</button>
        </div>

        <!-- Thông tin tài khoản -->
        <div id="info-tab" class="tab-content active">
            <div class="info-card">
                <h2>Thông tin cá nhân</h2>
                <div class="info-row">
                    <label>Họ tên:</label>
                    <span><?php echo htmlspecialchars($user['fullname']); ?></span>
                </div>
                <div class="info-row">
                    <label>Email:</label>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-row">
                    <label>Số điện thoại:</label>
                    <span><?php echo htmlspecialchars($user['phone'] ?? 'Chưa cập nhật'); ?></span>
                </div>
                <div class="info-row">
                    <label>Ngày tham gia:</label>
                    <span><?php echo isset($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : 'Chưa cập nhật'; ?></span>
                </div>
                <br>
                <button class="btn-primary" onclick="showEditProfileModal()">Chỉnh sửa thông tin</button>
            </div>
        </div>

        <!-- Địa chỉ giao hàng -->
        <div id="address-tab" class="tab-content">
            <div class="info-card">
                <h2>Địa chỉ giao hàng</h2>
                <div class="address-list">
                    <?php if ($addresses->num_rows > 0): ?>
                        <?php while($address = $addresses->fetch_assoc()): ?>
                            <div class="address-item">
                                <div class="address-info">
                                    <strong>
                                        <?php echo htmlspecialchars($address['receiver_name']); ?>
                                        <?php if ($address['is_default']): ?>
                                            <span class="default-badge">Mặc định</span>
                                        <?php endif; ?>
                                    </strong>
                                    <p><?php echo htmlspecialchars($address['address_text']); ?></p>
                                    <p>SĐT: <?php echo htmlspecialchars($address['phone']); ?></p>
                                </div>
                                <div class="address-actions">
                                    <?php if (!$address['is_default']): ?>
                                        <button class="btn-outline" onclick="setDefaultAddress(<?php echo $address['address_id']; ?>)">Đặt mặc định</button>
                                    <?php endif; ?>
                                    <button class="btn-outline btn-delete" onclick="deleteAddress(<?php echo $address['address_id']; ?>)">Xóa</button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Chưa có địa chỉ giao hàng nào.</p>
                    <?php endif; ?>
                </div>
                <button class="btn-primary" onclick="showAddAddressModal()">Thêm địa chỉ mới</button>
            </div>
        </div>

        <!-- Đơn hàng -->
        <div id="orders-tab" class="tab-content">
            <div class="info-card">
                <h2>Đơn hàng của tôi</h2>
                <div class="order-filters">
                    <a href="?status=all#orders" class="filter-btn <?php echo $filterStatus === 'all' ? 'active' : ''; ?>">Tất cả (<?php echo $counts['all']; ?>)</a>
                    <a href="?status=pending#orders" class="filter-btn <?php echo $filterStatus === 'pending' ? 'active' : ''; ?>">Chờ duyệt (<?php echo $counts['pending']; ?>)</a>
                    <a href="?status=preparing#orders" class="filter-btn <?php echo $filterStatus === 'preparing' ? 'active' : ''; ?>">Chuẩn bị (<?php echo $counts['preparing']; ?>)</a>
                    <a href="?status=delivering#orders" class="filter-btn <?php echo $filterStatus === 'delivering' ? 'active' : ''; ?>">Đang giao (<?php echo $counts['delivering']; ?>)</a>
                    <a href="?status=completed#orders" class="filter-btn <?php echo $filterStatus === 'completed' ? 'active' : ''; ?>">Hoàn thành (<?php echo $counts['completed']; ?>)</a>
                    <a href="?status=cancelled#orders" class="filter-btn <?php echo $filterStatus === 'cancelled' ? 'active' : ''; ?>">Đã hủy (<?php echo $counts['cancelled']; ?>)</a>
                </div>
                
                <div class="order-list">
                    <?php if (empty($orders)): ?>
                        <p style="text-align:center;color:#666;padding:2rem;">Chưa có đơn hàng nào</p>
                    <?php else: ?>
                        <?php foreach ($orders as $order): 
                            $statusMap = [
                                'pending' => ['text' => 'Chờ duyệt', 'class' => 'pending'],
                                'preparing' => ['text' => 'Đang chuẩn bị', 'class' => 'preparing'],
                                'delivering' => ['text' => 'Đang giao hàng', 'class' => 'delivering'],
                                'completed' => ['text' => 'Đã giao thành công', 'class' => 'completed'],
                                'cancelled' => ['text' => 'Đã hủy', 'class' => 'cancelled']
                            ];
                            $status = $statusMap[$order['status']] ?? ['text' => $order['status'], 'class' => 'pending'];
                        ?>
                        <div class="order-item">
                            <div class="order-header">
                                <span class="order-status <?php echo $status['class']; ?>"><?php echo $status['text']; ?></span>
                            </div>
                            <div class="order-info">
                                <p><strong><?php echo htmlspecialchars($order['customer_name']); ?></strong></p>
                                <p><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                                <p><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                                <p>Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                                <p class="order-total">Tổng: <?php echo number_format($order['total_price']); ?>đ</p>
                            </div>
                            <div class="order-actions">
                                <?php if ($order['status'] === 'pending'): ?>
                                    <button class="btn-outline btn-cancel" onclick="cancelOrder('<?php echo $order['order_id']; ?>')">Hủy đơn</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Đăng xuất -->
        <div class="logout-section">
            <a href="?logout=1" class="btn-logout">Đăng xuất</a>
        </div>
    </div>
</main>

<!-- Modal thêm địa chỉ -->
<div id="addAddressModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddAddressModal()">&times;</span>
        <h3>Thêm địa chỉ giao hàng</h3>
        <form id="addAddressForm">
            <div class="form-group">
                <label>Tên người nhận:</label>
                <input type="text" id="newName" name="receiver_name" placeholder="Tên người nhận *" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="tel" id="newPhone" name="phone" placeholder="Số điện thoại * (VD: 0912345678)" class="form-input" pattern="(84|0[3|5|7|8|9])+([0-9]{8})" title="Nhập số điện thoại 10 số, bắt đầu bằng 03, 05, 07, 08, 09" required>
            </div>
            <div class="form-group">
                <label>Tỉnh/Thành phố:</label>
                <select id="province" class="form-input" required>
                    <option value="">Chọn Tỉnh/Thành phố</option>
                </select>
            </div>
            <div class="form-group">
                <label>Quận/Huyện:</label>
                <select id="district" class="form-input" required disabled>
                    <option value="">Chọn Quận/Huyện</option>
                </select>
            </div>
            <div class="form-group">
                <label>Phường/Xã:</label>
                <select id="ward" class="form-input" required disabled>
                    <option value="">Chọn Phường/Xã</option>
                </select>
            </div>
            <div class="form-group">
                <label>Số nhà, tên đường:</label>
                <input type="text" id="street" name="street" placeholder="Số nhà, tên đường *" class="form-input" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_default"> Đặt làm địa chỉ mặc định
                </label>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-outline" onclick="closeAddAddressModal()">Hủy</button>
                <button type="button" class="btn-primary" onclick="saveNewAddress()">Thêm địa chỉ</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal chỉnh sửa thông tin -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditProfileModal()">&times;</span>
        <h3>Chỉnh sửa thông tin cá nhân</h3>
        <form id="editProfileForm">
            <div class="form-group">
                <label>Họ tên:</label>
                <input type="text" id="editFullname" name="fullname" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="tel" id="editPhone" name="phone" class="form-input" pattern="(84|0[3|5|7|8|9])+([0-9]{8})" title="Nhập số điện thoại 10 số, bắt đầu bằng 03, 05, 07, 08, 09" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="editEmail" class="form-input" disabled style="background: #f5f5f5; color: #999;">
                <small style="color: #666; font-size: 12px;">Email không thể thay đổi</small>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-outline" onclick="closeEditProfileModal()">Hủy</button>
                <button type="button" class="btn-primary" onclick="saveProfile()">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');
    if (event && event.target) {
        event.target.classList.add('active');
    }
    
    // Lưu tab hiện tại
    localStorage.setItem('activeTab', tabName);
}

// Khôi phục tab khi load trang
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    let activeTab = 'info';
    
    // Nếu có status trong URL hoặc hash #orders thì mở tab orders
    if (urlParams.has('status') || window.location.hash === '#orders') {
        activeTab = 'orders';
    } else {
        // Không thì lấy từ localStorage
        activeTab = localStorage.getItem('activeTab') || 'info';
    }
    
    // Hiển thị tab
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(activeTab + '-tab').classList.add('active');
    document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.getAttribute('onclick') === `showTab('${activeTab}')`) {
            btn.classList.add('active');
        }
    });
});

function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc muốn hủy đơn hàng này?')) return;
    
    fetch('../actions/cancel_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `order_id=${orderId}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// Chức năng chỉnh sửa thông tin
function showEditProfileModal() {
    // Lấy thông tin hiện tại từ giao diện
    const infoRows = document.querySelectorAll('.info-row');
    const currentName = infoRows[0].querySelector('span').textContent;
    const currentPhone = infoRows[2].querySelector('span').textContent;
    const currentEmail = infoRows[1].querySelector('span').textContent;
    
    document.getElementById('editFullname').value = currentName;
    document.getElementById('editPhone').value = currentPhone === 'Chưa cập nhật' ? '' : currentPhone;
    document.getElementById('editEmail').value = currentEmail;
    document.getElementById('editProfileModal').style.display = 'block';
}

function closeEditProfileModal() {
    document.getElementById('editProfileModal').style.display = 'none';
}

function saveProfile() {
    const fullname = document.getElementById('editFullname').value.trim();
    const phone = document.getElementById('editPhone').value.trim();
    
    if (!fullname || !phone) {
        alert('Vui lòng nhập đầy đủ thông tin');
        return;
    }
    
    const phoneRegex = /(84|0[3|5|7|8|9])+([0-9]{8})\b/;
    if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
        alert('Số điện thoại không hợp lệ');
        return;
    }
    
    fetch('../actions/update_profile.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `fullname=${encodeURIComponent(fullname)}&phone=${encodeURIComponent(phone)}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            // Cập nhật giao diện
            const infoRows = document.querySelectorAll('.info-row');
            infoRows[0].querySelector('span').textContent = fullname; // Họ tên
            infoRows[2].querySelector('span').textContent = phone;    // Số điện thoại
            closeEditProfileModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// Chức năng địa chỉ
function showAddAddressModal() {
    document.getElementById('addAddressModal').style.display = 'block';
}

function closeAddAddressModal() {
    document.getElementById('addAddressModal').style.display = 'none';
    // Reset form
    document.getElementById('newName').value = '';
    document.getElementById('newPhone').value = '';
    document.getElementById('province').value = '';
    document.getElementById('district').innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    document.getElementById('district').disabled = true;
    document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
    document.getElementById('ward').disabled = true;
    document.getElementById('street').value = '';
    document.querySelector('input[name="is_default"]').checked = false;
}

function deleteAddress(addressId) {
    if (!confirm('Bạn có chắc muốn xóa địa chỉ này?')) return;
    
    fetch('../actions/address.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=delete&address_id=${addressId}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

function setDefaultAddress(addressId) {
    fetch('../actions/address.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=set_default&address_id=${addressId}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// Dữ liệu tỉnh/quận/phường Việt Nam
const vietnamData = {
    "Hà Nội": {
        "Ba Đình": ["Phường Cổng Vị", "Phường Điện Biên", "Phường Đội Cấn", "Phường Giảng Võ", "Phường Kim Mã"],
        "Hoàn Kiếm": ["Phường Chương Dương Độ", "Phường Cửu Nam", "Phường Đồng Xuân", "Phường Hàng Bạc", "Phường Hàng Bông"],
        "Cầu Giấy": ["Phường Dịch Vọng", "Phường Dịch Vọng Hậu", "Phường Mai Dịch", "Phường Nghĩa Đô", "Phường Nghĩa Tân"],
        "Hai Bà Trưng": ["Phường Bạch Đằng", "Phường Bùi Thị Xuân", "Phường Đồng Nhân", "Phường Lê Đại Hành", "Phường Minh Khai"]
    },
    "TP. Hồ Chí Minh": {
        "Quận 1": ["Phường Bến Nghé", "Phường Bến Thành", "Phường Cầu Kho", "Phường Cầu Ông Lãnh", "Phường Cô Giang"],
        "Quận 3": ["Phường 01", "Phường 02", "Phường 03", "Phường 04", "Phường 05"],
        "Quận 5": ["Phường 01", "Phường 02", "Phường 03", "Phường 04", "Phường 05"],
        "Bình Thạnh": ["Phường 01", "Phường 02", "Phường 03", "Phường 05", "Phường 06"]
    },
    "Đà Nẵng": {
        "Hải Châu": ["Phường Bình Hiên", "Phường Bình Thuận", "Phường Hòa Cường Bắc", "Phường Hòa Cường Nam"],
        "Thanh Khê": ["Phường An Khê", "Phường Chính Ghiên", "Phường Tân Chính", "Phường Thanh Khê Đông"]
    }
};

// Load provinces
const provinceSelect = document.getElementById('province');
Object.keys(vietnamData).forEach(province => {
    const option = document.createElement('option');
    option.value = province;
    option.textContent = province;
    provinceSelect.appendChild(option);
});

// Province change
provinceSelect.addEventListener('change', function() {
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    wardSelect.disabled = true;
    
    if (this.value) {
        districtSelect.disabled = false;
        Object.keys(vietnamData[this.value]).forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });
    } else {
        districtSelect.disabled = true;
    }
});

// District change
document.getElementById('district').addEventListener('change', function() {
    const wardSelect = document.getElementById('ward');
    const province = provinceSelect.value;
    
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    
    if (this.value && province) {
        wardSelect.disabled = false;
        vietnamData[province][this.value].forEach(ward => {
            const option = document.createElement('option');
            option.value = ward;
            option.textContent = ward;
            wardSelect.appendChild(option);
        });
    } else {
        wardSelect.disabled = true;
    }
});

function saveNewAddress() {
    const name = document.getElementById('newName').value;
    const phone = document.getElementById('newPhone').value;
    const street = document.getElementById('street').value;
    const ward = document.getElementById('ward').value;
    const district = document.getElementById('district').value;
    const province = document.getElementById('province').value;
    const isDefault = document.querySelector('input[name="is_default"]').checked;
    
    if (!name || !phone || !street || !ward || !district || !province) {
        alert('Vui lòng nhập đầy đủ thông tin');
        return;
    }
    
    const phoneRegex = /(84|0[3|5|7|8|9])+([0-9]{8})\b/;
    if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
        alert('Số điện thoại không hợp lệ');
        return;
    }
    
    const address = `${street}, ${ward}, ${district}, ${province}`;
    
    fetch('../actions/address.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=add&receiver_name=${encodeURIComponent(name)}&phone=${encodeURIComponent(phone)}&address=${encodeURIComponent(address)}&is_default=${isDefault ? 1 : 0}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            // Thêm địa chỉ mới vào danh sách
            const addressList = document.querySelector('.address-list');
            const newAddressHtml = `
                <div class="address-item">
                    <div class="address-info">
                        <strong>
                            ${data.address.receiver_name}
                            ${data.address.is_default ? '<span class="default-badge">Mặc định</span>' : ''}
                        </strong>
                        <p>${data.address.address_text}</p>
                        <p>SĐT: ${data.address.phone}</p>
                    </div>
                    <div class="address-actions">
                        ${!data.address.is_default ? `<button class="btn-outline" onclick="setDefaultAddress(${data.address.address_id})">\u0110\u1eb7t m\u1eb7c \u0111\u1ecbnh</button>` : ''}
                        <button class="btn-outline btn-delete" onclick="deleteAddress(${data.address.address_id})">Xóa</button>
                    </div>
                </div>
            `;
            
            if (addressList.innerHTML.includes('Chưa có địa chỉ giao hàng nào')) {
                addressList.innerHTML = newAddressHtml;
            } else {
                addressList.insertAdjacentHTML('beforeend', newAddressHtml);
            }
            
            closeAddAddressModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

// Đóng modal khi click bên ngoài
window.onclick = function(event) {
    const addressModal = document.getElementById('addAddressModal');
    const profileModal = document.getElementById('editProfileModal');
    
    if (event.target === addressModal) {
        closeAddAddressModal();
    }
    if (event.target === profileModal) {
        closeEditProfileModal();
    }
}
</script>

<style>
.account-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.account-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.tab-btn {
    padding: 0.75rem 1rem;
    border: none;
    background: none;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}

.tab-btn.active {
    border-bottom-color: #e11b22;
    color: #e11b22;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.info-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-row label {
    font-weight: 600;
    color: #666;
}

.address-item, .order-item {
    padding: 1.5rem;
    border: 1px solid #eee;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.order-actions {
    margin-top: 1rem;
    display: flex;
    gap: 0.5rem;
}

.btn-cancel {
    background: #ef4444;
    color: white;
    border: none;
}

.btn-cancel:hover {
    background: #dc2626;
}

.order-filters {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 20px;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
    color: #333;
    transition: all 0.2s;
}

.filter-btn:hover {
    background: #f5f5f5;
}

.filter-btn.active {
    background: #e11b22;
    color: #fff;
    border-color: #e11b22;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.order-status {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.order-status.completed {
    background: #dcfce7;
    color: #166534;
}

.order-status.delivering {
    background: #e0e7ff;
    color: #4338ca;
}

.order-status.pending {
    background: #fef3c7;
    color: #92400e;
}

.order-status.preparing {
    background: #dbeafe;
    color: #1e40af;
}

.order-status.cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.order-total {
    font-weight: 600;
    color: #e11b22;
}

.logout-section {
    margin-top: 2rem;
    text-align: center;
}

.btn-logout {
    padding: 0.75rem 2rem;
    background: #dc2626;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.2s;
}

.btn-logout:hover {
    background: #b91c1c;
}

.default-badge {
    background: #e11b22;
    color: #fff;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    margin-left: 0.5rem;
}

.address-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.btn-delete {
    background: #ef4444;
    color: white;
    border: none;
}

.btn-delete:hover {
    background: #dc2626;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 2rem;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    position: relative;
}

.close {
    position: absolute;
    right: 1rem;
    top: 1rem;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    color: #aaa;
}

.close:hover {
    color: #000;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
}

.form-group input[type="checkbox"] {
    width: auto;
    margin-right: 0.5rem;
}

select.form-input {
    cursor: pointer;
    background: white;
}

select.form-input:disabled {
    background: #f5f5f5;
    cursor: not-allowed;
    color: #999;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}
</style>

<?php 
$conn->close();
include '../includes/footer.php'; 
?>