<!-- AUTH MODALS (Đăng nhập / Đăng ký) -->
<div id="login-modal" class="modal<?php echo !empty($openLoginModal) ? ' show' : ''; ?>">
    <a href="#" class="modal-backdrop" aria-label="Đóng đăng nhập"></a>
    <div class="modal-content auth-card">
        <h1 class="auth-title">Đăng nhập</h1>
        <p class="auth-subtitle">Đăng nhập để đặt món nhanh hơn, lưu địa chỉ và xem lịch sử đơn hàng.</p>

        <?php if (!empty($loginError)): ?>
            <p class="auth-error"><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php elseif (!empty($authSuccess) && isset($_POST['auth_type']) && $_POST['auth_type'] === 'login'): ?>
            <p class="auth-success"><?php echo htmlspecialchars($authSuccess, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form action="" method="post" class="auth-form">
            <input type="hidden" name="auth_type" value="login">
            <div class="form-group">
                <label for="login-email">Email</label>
                <input type="email" id="login-email" name="email" placeholder="email@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="login-password">Mật khẩu</label>
                <input type="password" id="login-password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <div class="form-extra">
                <label class="checkbox">
                    <input type="checkbox" name="remember">
                    <span>Ghi nhớ đăng nhập</span>
                </label>
                <a href="#" class="link-small">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-primary auth-submit">Đăng nhập</button>
        </form>

        <p class="auth-switch">
            Chưa có tài khoản?
            <a href="#register-modal">Đăng ký ngay</a>
        </p>
        <a href="#" class="modal-close link-small">Đóng</a>
    </div>
</div>

<div id="register-modal" class="modal<?php echo !empty($openRegisterModal) ? ' show' : ''; ?>">
    <a href="#" class="modal-backdrop" aria-label="Đóng đăng ký"></a>
    <div class="modal-content auth-card">
        <h1 class="auth-title">Đăng ký</h1>
        <p class="auth-subtitle">Tạo tài khoản FoodShop để nhận nhiều ưu đãi và đặt món nhanh chóng.</p>

        <?php if (!empty($registerError)): ?>
            <p class="auth-error"><?php echo htmlspecialchars($registerError, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php elseif (!empty($authSuccess) && isset($_POST['auth_type']) && $_POST['auth_type'] === 'register'): ?>
            <p class="auth-success"><?php echo htmlspecialchars($authSuccess, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form action="" method="post" class="auth-form">
            <input type="hidden" name="auth_type" value="register">
            <div class="form-group">
                <label for="reg-name">Họ và tên</label>
                <input type="text" id="reg-name" name="name" placeholder="Nguyễn Văn A" value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="reg-phone">Số điện thoại</label>
                <input type="tel" id="reg-phone" name="phone" placeholder="09xx xxx xxx" value="<?php echo htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="reg-email">Email</label>
                <input type="email" id="reg-email" name="email" placeholder="nhapemail@gmail.com" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="reg-password">Mật khẩu</label>
                <input type="password" id="reg-password" name="password" placeholder="Tối thiểu 6 ký tự" required>
            </div>

            <div class="form-group">
                <label for="reg-password-confirm">Nhập lại mật khẩu</label>
                <input type="password" id="reg-password-confirm" name="password_confirm" placeholder="Nhập lại mật khẩu" required>
            </div>

            <div class="form-extra">
                <label class="checkbox">
                    <input type="checkbox" name="agree" required>
                    <span>Tôi đồng ý với Điều khoản sử dụng và Chính sách bảo mật</span>
                </label>
            </div>

            <button type="submit" class="btn-primary auth-submit">Đăng ký</button>
        </form>

        <p class="auth-switch">
            Đã có tài khoản?
            <a href="#login-modal">Đăng nhập</a>
        </p>
        <a href="#" class="modal-close link-small">Đóng</a>
    </div>
</div>

<script>
// Xử lý modal
document.addEventListener('DOMContentLoaded', function() {
    // Mở modal
    document.querySelectorAll('a[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            const target = this.getAttribute('href');
            if (target.includes('modal')) {
                e.preventDefault();
                const modal = document.querySelector(target);
                if (modal) {
                    modal.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }
            }
        });
    });

    // Đóng modal
    document.querySelectorAll('.modal-backdrop, .modal-close').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    });

    // Đóng modal khi nhấn ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.show').forEach(function(modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            });
        }
    });

    // Xử lý thêm vào giỏ hàng bằng event delegation
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btn-add')) {
            e.preventDefault();
            
            // Kiểm tra đăng nhập trước
            const isLoggedIn = document.querySelector('.btn-user') !== null;
            
            if (!isLoggedIn) {
                // Mở modal đăng nhập nếu chưa đăng nhập
                const loginModal = document.querySelector('#login-modal');
                if (loginModal) {
                    loginModal.classList.add('show');
                    document.body.style.overflow = 'hidden';
                }
                return;
            }
            
            const button = e.target;
            const productId = button.getAttribute('data-id');
            const productTable = button.getAttribute('data-table');
            
            console.log('Product ID:', productId);
            console.log('Product Table:', productTable);
            console.log('Button element:', button);
            
            if (!productId || !productTable) {
                alert('Thông tin sản phẩm không hợp lệ. ID: ' + productId + ', Table: ' + productTable);
                return;
            }
            
            // Vô hiệu hóa nút tạm thời
            const originalText = button.textContent;
            button.disabled = true;
            
            // Xác định đường dẫn file
            const addToCartUrl = '../actions/add_to_cart.php';
            
            fetch(addToCartUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&product_table=${productTable}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Hiệu ứng bay vào giỏ hàng
                    flyToCart(button);
                    updateCartCount();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
            })
            .finally(() => {
                // Khôi phục trạng thái nút
                button.disabled = false;
            });
        }
    });

     // Tự động cập nhật số lượng giỏ hàng khi trang load
    updateCartCount(false);
});

// Hàm cập nhật số lượng giỏ hàng
function updateCartCount(showAnimation = true) {
    const getCartCountUrl = '../actions/get_cart_count.php';
    
    fetch(getCartCountUrl)
    .then(response => response.json())
    .then(data => {
        const cartButton = document.querySelector('a.btn-primary');
        if (cartButton && data.count !== undefined) {
            cartButton.textContent = `Giỏ hàng (${data.count})`;
            // Hiệu ứng bounce chỉ khi thêm sản phẩm
            if (showAnimation) {
                cartButton.classList.add('cart-bounce');
                setTimeout(() => cartButton.classList.remove('cart-bounce'), 600);
            }
        }
    })
    .catch(error => console.error('Error updating cart count:', error));
}

// Hiệu ứng bay vào giỏ hàng
function flyToCart(button) {
    const productCard = button.closest('.product-card');
    const productImage = productCard.querySelector('.product-image img');
    const cartButton = document.querySelector('a.btn-primary');
    
    if (!productImage || !cartButton) return;
    
    // Tạo bản sao của hình ảnh
    const flyingImage = productImage.cloneNode(true);
    flyingImage.classList.add('flying-to-cart');
    
    // Lấy vị trí ban đầu và đích
    const imageRect = productImage.getBoundingClientRect();
    const cartRect = cartButton.getBoundingClientRect();
    
    // Đặt vị trí ban đầu
    flyingImage.style.position = 'fixed';
    flyingImage.style.left = imageRect.left + 'px';
    flyingImage.style.top = imageRect.top + 'px';
    flyingImage.style.width = imageRect.width + 'px';
    flyingImage.style.height = imageRect.height + 'px';
    flyingImage.style.zIndex = '9999';
    flyingImage.style.pointerEvents = 'none';
    flyingImage.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    
    document.body.appendChild(flyingImage);
    
    // Bắt đầu animation
    setTimeout(() => {
        flyingImage.style.left = cartRect.left + cartRect.width/2 - 25 + 'px';
        flyingImage.style.top = cartRect.top + cartRect.height/2 - 25 + 'px';
        flyingImage.style.width = '50px';
        flyingImage.style.height = '50px';
        flyingImage.style.opacity = '0.8';
    }, 50);
    
    // Xóa sau khi hoàn thành
    setTimeout(() => {
        if (flyingImage.parentNode) {
            flyingImage.parentNode.removeChild(flyingImage);
        }
    }, 900);
}
</script>

<style>
.flying-to-cart {
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.cart-bounce {
    animation: cartBounce 0.6s ease-in-out;
}

@keyframes cartBounce {
    0%, 100% { transform: scale(1); }
    25% { transform: scale(1.1); }
    50% { transform: scale(1.05); }
    75% { transform: scale(1.08); }
}
</style>