<?php
require_once '../config/config.php';

if (empty($_SESSION['user'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$userId = $_SESSION['user']['user_id'];
$conn = getDBConnection();

// Lấy giỏ hàng
$sql = "SELECT c.cart_id, c.product_table, c.product_id, c.quantity, c.added_at FROM cart c WHERE c.user_id = ? ORDER BY c.added_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result();

$totalAmount = 0;
$cartProducts = [];

while ($item = $cartItems->fetch_assoc()) {
    $productTable = $item['product_table'];
    $productId = $item['product_id'];
    
    $productSql = "SELECT name, price, image_url FROM $productTable WHERE product_id = ?";
    $productStmt = $conn->prepare($productSql);
    $productStmt->bind_param("i", $productId);
    $productStmt->execute();
    $productResult = $productStmt->get_result();
    
    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();
        $item['name'] = $product['name'];
        $item['price'] = $product['price'];
        $item['image_url'] = $product['image_url'];
        $item['subtotal'] = $product['price'] * $item['quantity'];
        $totalAmount += $item['subtotal'];
        $cartProducts[] = $item;
    }
    $productStmt->close();
}

$stmt->close();

if (empty($cartProducts)) {
    header('Location: cart.php');
    exit;
}

// Lấy địa chỉ
$addresses = $conn->query("SELECT * FROM user_addresses WHERE user_id = $userId ORDER BY is_default DESC, created_at DESC")->fetch_all(MYSQLI_ASSOC);

$conn->close();

$shippingFee = 20000;
$finalTotal = $totalAmount + $shippingFee;

$pageTitle = 'Thanh toán - FoodShop';
include INCLUDES_PATH . '/header.php';
?>

<style>
.checkout-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; display: grid; grid-template-columns: 1fr 400px; gap: 2rem; }
.checkout-main { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.checkout-sidebar { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: fit-content; position: sticky; top: 80px; }
.section-title { font-size: 18px; font-weight: 700; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #f0f0f0; }
.product-item { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #f0f0f0; }
.product-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
.product-info { flex: 1; }
.product-name { font-weight: 600; font-size: 14px; }
.product-qty { color: #666; font-size: 13px; }
.product-price { font-weight: 600; color: #e11b22; }
.address-card { padding: 1rem; border: 2px solid #f0f0f0; border-radius: 8px; margin-bottom: 0.75rem; cursor: pointer; transition: all 0.2s; display: block; }
.address-card:hover { border-color: #e11b22; background: #fffbfb; }
.address-card.selected { border-color: #e11b22; background: #fff5f5; }
.address-card input[type="radio"] { margin-right: 0.75rem; vertical-align: middle; }
.address-card strong { font-size: 15px; color: #111; }
.address-info { color: #666; font-size: 14px; margin-top: 0.5rem; line-height: 1.5; }
.add-address-btn { width: 100%; padding: 0.75rem; border: 2px dashed #ddd; background: white; border-radius: 8px; cursor: pointer; color: #666; }
.add-address-btn:hover { border-color: #e11b22; color: #e11b22; }
.payment-method { display: flex; gap: 1rem; margin-top: 1rem; }
.payment-option { flex: 1; padding: 1rem; border: 2px solid #f0f0f0; border-radius: 8px; cursor: pointer; text-align: center; }
.payment-option:hover, .payment-option.selected { border-color: #e11b22; background: #fff5f5; }
.summary-row { display: flex; justify-content: space-between; padding: 0.75rem 0; }
.summary-row.total { font-size: 18px; font-weight: 700; color: #e11b22; border-top: 2px solid #f0f0f0; margin-top: 0.5rem; padding-top: 1rem; }
.form-input { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 0.75rem; font-size: 14px; box-sizing: border-box; }
textarea.form-input { min-height: 80px; resize: vertical; }
select.form-input { cursor: pointer; background: white; }
select.form-input:disabled { background: #f5f5f5; cursor: not-allowed; color: #999; }
.new-address-form { display: none; padding: 1.5rem; background: #f9f9f9; border: 1px solid #e5e5e5; border-radius: 8px; margin-top: 1rem; }
@media (max-width: 768px) { .checkout-container { grid-template-columns: 1fr; } }
</style>

<main class="main">
    <div class="checkout-container">
        <div class="checkout-main">
            <h2 class="section-title">Địa chỉ giao hàng</h2>
            <div id="addressList">
                <?php foreach ($addresses as $addr): ?>
                <label class="address-card <?php echo $addr['is_default'] ? 'selected' : ''; ?>">
                    <input type="radio" name="address" value="<?php echo $addr['address_id']; ?>" 
                        data-name="<?php echo htmlspecialchars($addr['receiver_name']); ?>"
                        data-phone="<?php echo htmlspecialchars($addr['phone']); ?>"
                        data-address="<?php echo htmlspecialchars($addr['address_text']); ?>"
                        <?php echo $addr['is_default'] ? 'checked' : ''; ?>>
                    <strong><?php echo htmlspecialchars($addr['receiver_name']); ?></strong>
                    <?php if ($addr['is_default']): ?>
                        <span style="display:inline-block;background:#e11b22;color:white;font-size:11px;padding:2px 8px;border-radius:4px;margin-left:8px;">Mặc định</span>
                    <?php endif; ?>
                    <div class="address-info">
                        <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($addr['phone']); ?></div>
                        <div><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($addr['address_text']); ?></div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
            <button class="add-address-btn" onclick="toggleNewAddress()">+ Thêm địa chỉ mới</button>
            
            <div class="new-address-form" id="newAddressForm">
                <input type="text" id="newName" placeholder="Tên người nhận *" class="form-input" required>
                <input type="tel" id="newPhone" placeholder="Số điện thoại * (VD: 0912345678)" class="form-input" pattern="(84|0[3|5|7|8|9])+([0-9]{8})" title="Nhập số điện thoại 10 số, bắt đầu bằng 03, 05, 07, 08, 09" required>
                <select id="province" class="form-input" required>
                    <option value="">Chọn Tỉnh/Thành phố</option>
                </select>
                <select id="district" class="form-input" required disabled>
                    <option value="">Chọn Quận/Huyện</option>
                </select>
                <select id="ward" class="form-input" required disabled>
                    <option value="">Chọn Phường/Xã</option>
                </select>
                <input type="text" id="street" placeholder="Số nhà, tên đường *" class="form-input" required>
                <div style="display:flex;gap:0.5rem;margin-top:0.5rem;">
                    <button type="button" class="btn-primary" onclick="saveNewAddress()" style="flex:1;">Xác nhận</button>
                    <button type="button" class="btn-outline" onclick="toggleNewAddress()" style="flex:1;">Hủy</button>
                </div>
            </div>

            <h2 class="section-title" style="margin-top:2rem;">Phương thức thanh toán</h2>
            <div class="payment-method">
                <label class="payment-option selected">
                    <input type="radio" name="payment" value="COD" checked style="display:none;">
                    <div style="font-weight:600;"><i class="fas fa-money-bill-wave"></i> COD</div>
                    <div style="font-size:12px;color:#666;">Thanh toán khi nhận hàng</div>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment" value="BANK" style="display:none;">
                    <div style="font-weight:600;"><i class="fas fa-university"></i> Chuyển khoản</div>
                    <div style="font-size:12px;color:#666;">Chuyển khoản ngân hàng</div>
                </label>
            </div>

            <h2 class="section-title" style="margin-top:2rem;">Ghi chú đơn hàng</h2>
            <textarea id="orderNote" placeholder="Ghi chú cho người bán (tùy chọn)" class="form-input"></textarea>
        </div>

        <div class="checkout-sidebar">
            <h3 class="section-title">Đơn hàng (<?php echo count($cartProducts); ?> sản phẩm)</h3>
            <?php foreach ($cartProducts as $item): ?>
            <div class="product-item">
                <img src="<?php echo BASE_URL . '/' . $item['image_url']; ?>" alt="">
                <div class="product-info">
                    <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    <div class="product-qty">SL: <?php echo $item['quantity']; ?></div>
                </div>
                <div class="product-price"><?php echo number_format($item['subtotal']); ?>đ</div>
            </div>
            <?php endforeach; ?>

            <div style="margin-top:1rem;">
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span><?php echo number_format($totalAmount); ?>đ</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span><?php echo number_format($shippingFee); ?>đ</span>
                </div>
                <div class="summary-row total">
                    <span>Tổng cộng:</span>
                    <span><?php echo number_format($finalTotal); ?>đ</span>
                </div>
            </div>

            <div style="display:flex;gap:0.5rem;margin-top:1rem;">
                <a href="cart.php" class="btn-outline" style="flex:1;padding:1rem;text-align:center;text-decoration:none;">
                    Quay về giỏ hàng
                </a>
                <button class="btn-primary" style="flex:1;padding:1rem;" onclick="placeOrder()">
                    Xác nhận đặt hàng
                </button>
            </div>
        </div>
    </div>
</main>

<script>
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

document.querySelectorAll('.payment-option').forEach(opt => {
    opt.addEventListener('click', function() {
        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input').checked = true;
    });
});

document.querySelectorAll('.address-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.address-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
    });
});

function toggleNewAddress() {
    const form = document.getElementById('newAddressForm');
    if (form.style.display === 'block') {
        form.style.display = 'none';
        // Reset form
        document.getElementById('newName').value = '';
        document.getElementById('newPhone').value = '';
        document.getElementById('province').value = '';
        document.getElementById('district').innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        document.getElementById('district').disabled = true;
        document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
        document.getElementById('ward').disabled = true;
        document.getElementById('street').value = '';
    } else {
        form.style.display = 'block';
    }
}

function saveNewAddress() {
    const name = document.getElementById('newName').value;
    const phone = document.getElementById('newPhone').value;
    const street = document.getElementById('street').value;
    const ward = document.getElementById('ward').value;
    const district = document.getElementById('district').value;
    const province = document.getElementById('province').value;
    
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
    
    fetch('../actions/add_address.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `name=${encodeURIComponent(name)}&phone=${encodeURIComponent(phone)}&address=${encodeURIComponent(address)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Thêm địa chỉ mới vào danh sách
            const addressList = document.getElementById('addressList');
            const newCard = document.createElement('label');
            newCard.className = 'address-card selected';
            newCard.innerHTML = `
                <input type="radio" name="address" value="${data.address.address_id}" 
                    data-name="${data.address.receiver_name}"
                    data-phone="${data.address.phone}"
                    data-address="${data.address.address_text}" checked>
                <strong>${data.address.receiver_name}</strong>
                ${data.address.is_default ? '<span style="display:inline-block;background:#e11b22;color:white;font-size:11px;padding:2px 8px;border-radius:4px;margin-left:8px;">Mặc định</span>' : ''}
                <div class="address-info">
                    <div><i class="fas fa-phone"></i> ${data.address.phone}</div>
                    <div><i class="fas fa-map-marker-alt"></i> ${data.address.address_text}</div>
                </div>
            `;
            
            // Bỏ selected của các card khác
            document.querySelectorAll('.address-card').forEach(c => c.classList.remove('selected'));
            
            addressList.appendChild(newCard);
            
            // Gắn sự kiện click
            newCard.addEventListener('click', function() {
                document.querySelectorAll('.address-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
            });
            
            toggleNewAddress();
            alert(data.message);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

function placeOrder() {
    let name, phone, address;
    const newForm = document.getElementById('newAddressForm');
    
    if (newForm.style.display === 'block') {
        name = document.getElementById('newName').value;
        phone = document.getElementById('newPhone').value;
        const street = document.getElementById('street').value;
        const ward = document.getElementById('ward').value;
        const district = document.getElementById('district').value;
        const province = document.getElementById('province').value;
        
        if (!street || !ward || !district || !province) {
            alert('Vui lòng chọn đầy đủ địa chỉ');
            return;
        }
        
        address = `${street}, ${ward}, ${district}, ${province}`;
    } else {
        const selected = document.querySelector('input[name="address"]:checked');
        if (!selected) {
            alert('Vui lòng chọn địa chỉ giao hàng');
            return;
        }
        name = selected.dataset.name;
        phone = selected.dataset.phone;
        address = selected.dataset.address;
    }
    
    if (!name || !phone || !address) {
        alert('Vui lòng nhập đầy đủ thông tin giao hàng');
        return;
    }
    
    // Kiểm tra số điện thoại Việt Nam
    const phoneRegex = /(84|0[3|5|7|8|9])+([0-9]{8})\b/;
    if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
        alert('Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam (10 số, bắt đầu bằng 03, 05, 07, 08, 09)');
        return;
    }
    
    const payment = document.querySelector('input[name="payment"]:checked').value;
    const note = document.getElementById('orderNote').value;
    
    fetch('../actions/checkout.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `name=${encodeURIComponent(name)}&phone=${encodeURIComponent(phone)}&address=${encodeURIComponent(address)}&payment=${payment}&note=${encodeURIComponent(note)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = 'order_success.php?order_id=' + data.order_id;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}
</script>

<?php include INCLUDES_PATH . '/footer.php'; ?>
