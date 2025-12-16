# FoodShop - Web Bán Thức Ăn Online

> **Hệ thống đặt món ăn nhanh với PHP + MySQL, hỗ trợ đầy đủ tính năng từ đặt hàng đến quản trị**

## Tính năng chính

### **Khách hàng**
- **Đăng ký/Đăng nhập** 
- **Giỏ hàng** 
- **Quản lý địa chỉ** 
- **Chỉnh sửa thông tin** 
- **Theo dõi đơn hàng** 
- **Thanh toán** 
- **Tìm kiếm**

### **Admin**
- **Dashboard**
- **Quản lý sản phẩm** (thêm/sửa/xóa)
- **Quản lý đơn hàng** cập nhật trạng thái
- **Quản lý người dùng** chức năng khóa tài khoản
- **Báo cáo doanh thu** theo sản phẩm và địa điểm

### **Bảo mật**
- **Khóa tài khoản**: Admin có thể khóa user, tự động kick ra khỏi hệ thống
- **Session management**: Kiểm tra trạng thái đăng nhập liên tục
- **SQL Injection prevention**: Sử dụng prepared statements
- **Input validation**: Kiểm tra dữ liệu đầu vào

## Cấu trúc dự án

```
WebBanThucAn/
├── assets/                 # Tài nguyên tĩnh
│   └── style.css           # CSS chính với Font Awesome icons
├── includes/               # Components dùng chung
│   ├── header.php          # Header với navigation và kiểm tra tài khoản bị khóa
│   ├── footer.php          # Footer responsive
│   └── modal.php           # Modal đăng nhập/đăng ký + logic giỏ hàng
├── pages/                  # Trang người dùng
│   ├── account.php         # Quản lý tài khoản, địa chỉ, đơn hàng
│   ├── cart.php            # Giỏ hàng với cập nhật số lượng
│   ├── checkout.php        # Thanh toán với form địa chỉ đầy đủ
│   └── search.php          # Tìm kiếm sản phẩm
├── category/               # Danh mục sản phẩm
│   ├── bestseller.php      # Sản phẩm bán chạy
│   ├── burger.php          # Danh mục Burger
│   ├── combo.php           # Danh mục Combo
│   ├── ga.php              # Danh mục Gà rán
│   ├── khuyenmai.php       # Khuyến mãi (trang menu chính)
│   ├── miy.php             # Danh mục Mì Ý
│   └── nuocuong.php        # Danh mục Nước uống
├── actions/                # Xử lý backend (API endpoints)
│   ├── add_to_cart.php     # Thêm sản phẩm vào giỏ hàng
│   ├── address.php         # CRUD địa chỉ giao hàng
│   ├── auth.php            # Xử lý đăng nhập/đăng ký
│   ├── checkout.php        # Xử lý đặt hàng
│   ├── update_profile.php  # Cập nhật thông tin cá nhân
│   ├── cancel_order.php    # Hủy đơn hàng
│   ├── get_cart_count.php  # Lấy số lượng giỏ hàng
│   ├── update_cart.php     # Cập nhật số lượng trong giỏ
│   ├── remove_from_cart.php # Xóa sản phẩm khỏi giỏ
│   └── add_address.php     # Thêm địa chỉ mới
├── config/                 # Cấu hình hệ thống
│   ├── config.php          # Constants và đường dẫn
│   └── database.php        # Kết nối MySQL với prepared statements
├── database/               # Database và scripts
│   ├── foodweb.sql         # Database hoàn chỉnh với sample data
│   └── update_user_status.sql # Script cập nhật cột status cho users
├── admin/                  # Quản trị hệ thống
│   ├── includes/
│   │   └── header.php      # Header admin với navigation
│   ├── index.php           # Dashboard với thống kê
│   ├── login.php           # Đăng nhập admin (admin/admin123)
│   ├── products.php        # Quản lý sản phẩm (CRUD)
│   ├── orders.php          # Quản lý đơn hàng và cập nhật trạng thái
│   ├── users.php           # Quản lý user + khóa/mở khóa tài khoản
│   └── revenue.php         # Báo cáo doanh thu theo thời gian
├── LabThucHanh/            # Bài tập thực hành PHP
│   ├── lab01/              # Cơ bản PHP
│   ├── lab02/              # Variables & Control Structures
│   ├── lab03/              # Functions
│   ├── lab04/              # Arrays & Forms
│   ├── lab05/              # Advanced PHP & OOP
│   ├── lab06/              # Database Operations
│   ├── Lab07/              # Project
│   └── index.php           # Trang chủ lab với danh sách bài tập
├── images/                 # Hình ảnh sản phẩm
│   ├── bestseller/         # Ảnh sản phẩm bán chạy
│   ├── burger/             # Ảnh burger
│   ├── combo/              # Ảnh combo
│   ├── ga/                 # Ảnh gà rán
│   ├── miy/                # Ảnh mì ý
│   └── nuocuong/           # Ảnh nước uống
├── index.php               # Trang chủ với hero section và categories
└── README.md               # Tài liệu dự án
```

### **Mô tả chức năng các thư mục:**

- **assets/**: Chứa CSS, JS, fonts và các tài nguyên tĩnh
- **includes/**: Components dùng chung như header, footer, modal
- **pages/**: Các trang chính của người dùng (tài khoản, giỏ hàng, thanh toán)
- **category/**: Trang hiển thị sản phẩm theo danh mục
- **actions/**: API endpoints xử lý các thao tác (thêm giỏ hàng, đặt hàng, etc.)
- **config/**: Cấu hình database và constants
- **database/**: File SQL và scripts database
- **admin/**: Panel quản trị với đầy đủ chức năng CRUD
- **LabThucHanh/**: Bài tập thực hành PHP từ cơ bản đến nâng cao
- **images/**: Thư mục chứa hình ảnh sản phẩm theo danh mục

## Cài đặt và chạy

### **Chuẩn bị môi trường**
```bash
# WAMP
C:\wamp64\www\WebBanThucAn

# XAMPP  
C:\xampp\htdocs\WebBanThucAn

# Hoặc bất kỳ web server nào hỗ trợ PHP 7.4+
```

### **Setup Database**
```sql
-- Tạo database
CREATE DATABASE foodweb;

-- Import file SQL
-- Vào phpMyAdmin → Import → Chọn database/foodweb.sql
```

### **Cấu hình kết nối**
```php
// config/database.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodweb');

// Thiết lập timezone TP.HCM
date_default_timezone_set('Asia/Ho_Chi_Minh');
```

### **Khởi động**
```bash
# Bật Apache + MySQL
# Truy cập: http://localhost/WebBanThucAn/
```

## Công nghệ sử dụng

- **Backend**: PHP 8+ với MySQLi
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Database**: MySQL 8+
- **Icons**: Font Awesome 6.5.1
- **Architecture**: MVC pattern đơn giản
- **Security**: Prepared Statements, Session Management