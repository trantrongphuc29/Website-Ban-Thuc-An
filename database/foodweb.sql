-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 08, 2025 at 06:36 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', '2025-12-08 08:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `bestseller`
--

DROP TABLE IF EXISTS `bestseller`;
CREATE TABLE IF NOT EXISTS `bestseller` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `promotion` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bestseller`
--

INSERT INTO `bestseller` (`product_id`, `name`, `description`, `price`, `image_url`, `created_at`, `promotion`) VALUES
(1, 'Best Seller Gà Rán', 'Gà rán được ưa chuộng nhất', 65.00, 'images/fried_chicken_best.jpg', '2025-12-02 08:54:04', 70.00),
(2, 'Gà Rán Giòn Nhất', 'Gà rán giòn rụm, bán chạy nhất', 65000.00, 'images/bestseller1.jpg', '2025-12-02 09:35:46', 80000.00),
(3, 'Burger Bò Phô Mai', 'Burger bò với phô mai tan chảy', 70000.00, 'images/bestseller2.jpg', '2025-12-02 09:35:46', 85000.00),
(4, 'Combo Gà + Khoai', 'Combo gà rán và khoai chiên', 95000.00, 'images/bestseller3.jpg', '2025-12-02 09:35:46', 120000.00),
(5, 'Mì Ý Sốt Kem', 'Mì Ý sốt kem bán chạy', 75000.00, 'images/bestseller4.jpg', '2025-12-02 09:35:46', 90000.00),
(6, 'Trà Sữa Thơm Ngon', 'Trà sữa bán chạy mọi thời điểm', 30000.00, 'images/bestseller5.jpg', '2025-12-02 09:35:46', 35000.00),
(7, 'Snack Khoai Tây', 'Snack khoai tây giòn rụm', 20000.00, 'images/bestseller6.jpg', '2025-12-02 09:35:46', 25000.00),
(8, 'Burger Gà Cay', 'Burger gà cay hấp dẫn', 68000.00, 'images/bestseller7.jpg', '2025-12-02 09:35:46', 80000.00),
(9, 'Combo Gia Đình', 'Combo cho 4 người', 150000.00, 'images/bestseller8.jpg', '2025-12-02 09:35:46', 180000.00),
(10, 'Pizza Mini Bán Chạy', 'Pizza mini thơm ngon', 55000.00, 'images/bestseller9.jpg', '2025-12-02 09:35:46', 65000.00),
(11, 'Nước Ngọt Thịnh Hành', 'Coca Cola, Pepsi...', 15000.00, 'images/bestseller10.jpg', '2025-12-02 09:35:46', 20000.00),
(16, 'vua them', 'zzzzzzzzzzzzz', 12000.00, 'uploads/6936918b39e42.jpg', '2025-12-08 08:51:23', NULL),
(17, 'Trần Trọng Phúc', '', 110000.00, 'uploads/693691c8178f0.jpg', '2025-12-08 08:52:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `burger`
--

DROP TABLE IF EXISTS `burger`;
CREATE TABLE IF NOT EXISTS `burger` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `promotion` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `burger`
--

INSERT INTO `burger` (`product_id`, `name`, `description`, `price`, `image_url`, `created_at`, `promotion`) VALUES
(1, 'Burger Bò', 'Burger bò thơm ngon với phô mai và rau tươi', 50.00, 'images/burger_beef.jpg', '2025-12-02 08:54:04', 70000.00),
(2, 'Burger Bò Phô Mai', 'Burger bò kèm phô mai tan chảy', 70000.00, 'images/burger1.jpg', '2025-12-02 09:35:46', 85000.00),
(3, 'Burger Gà Cay', 'Burger gà cay hấp dẫn', 65000.00, 'images/burger2.jpg', '2025-12-02 09:35:46', 80000.00),
(4, 'Burger Heo Nướng', 'Burger heo nướng mềm', 68000.00, 'images/burger3.jpg', '2025-12-02 09:35:46', 82000.00),
(5, 'Burger Cá', 'Burger cá chiên giòn', 60000.00, 'images/burger4.jpg', '2025-12-02 09:35:46', 75000.00),
(6, 'Burger Veggie', 'Burger rau củ cho người ăn chay', 55000.00, 'images/burger5.jpg', '2025-12-02 09:35:46', 70000.00),
(7, 'Burger Double', 'Burger 2 tầng thơm ngon', 85000.00, 'images/burger6.jpg', '2025-12-02 09:35:46', 100000.00),
(8, 'Burger Mini', 'Burger mini tiện lợi', 40000.00, 'images/burger7.jpg', '2025-12-02 09:35:46', 50000.00),
(9, 'Burger Combo', 'Burger + Khoai + Nước', 95000.00, 'images/burger8.jpg', '2025-12-02 09:35:46', 120000.00),
(10, 'Burger BBQ', 'Burger sốt BBQ', 70000.00, 'images/burger9.jpg', '2025-12-02 09:35:46', 85000.00),
(11, 'Burger Hành Tây Chiên', 'Burger kèm hành tây chiên giòn', 68000.00, 'images/burger10.jpg', '2025-12-02 09:35:46', 82000.00),
(12, 'Trần Trọng Phúc', '', 110000.00, 'uploads/6936921899b08.jpg', '2025-12-08 08:53:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_table` varchar(50) NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_table`, `product_id`, `quantity`, `added_at`) VALUES
(14, 4, 'khuyenmai', 5, 1, '2025-12-08 07:28:37'),
(12, 4, 'khuyenmai', 2, 1, '2025-12-08 07:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `combo`
--

DROP TABLE IF EXISTS `combo`;
CREATE TABLE IF NOT EXISTS `combo` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `promotion` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `combo`
--

INSERT INTO `combo` (`product_id`, `name`, `description`, `price`, `image_url`, `created_at`, `promotion`) VALUES
(1, 'Combo Burger + Gà', 'Burger và gà rán combo', 100.00, 'images/combo_burger_chicken.jpg', '2025-12-02 08:54:04', NULL),
(2, 'Combo Burger + Gà', 'Burger và gà rán combo', 100000.00, 'images/combo1.jpg', '2025-12-02 09:35:46', 130000.00),
(3, 'Combo Gia Đình', 'Combo cho 4 người', 150000.00, 'images/combo2.jpg', '2025-12-02 09:35:46', 180000.00),
(4, 'Combo Trưa Tiết Kiệm', 'Combo trưa ưu đãi', 90000.00, 'images/combo3.jpg', '2025-12-02 09:35:46', 120000.00),
(5, 'Combo Mì Ý + Nước', 'Mì Ý + nước giải khát', 95000.00, 'images/combo4.jpg', '2025-12-02 09:35:46', 120000.00),
(6, 'Combo Snack + Nước', 'Snack + nước giải khát', 50000.00, 'images/combo5.jpg', '2025-12-02 09:35:46', 65000.00),
(7, 'Combo Gà Rán 3 Miếng', '3 miếng gà rán + khoai', 120000.00, 'images/combo6.jpg', '2025-12-02 09:35:46', 150000.00),
(8, 'Combo Burger Mini', 'Burger mini + nước', 80000.00, 'images/combo7.jpg', '2025-12-02 09:35:46', 100000.00),
(9, 'Combo Pizza + Nước', 'Pizza mini + nước', 95000.00, 'images/combo8.jpg', '2025-12-02 09:35:46', 120000.00),
(10, 'Combo Thập Cẩm', 'Kết hợp nhiều món', 140000.00, 'images/combo9.jpg', '2025-12-02 09:35:46', 170000.00),
(11, 'Combo Ăn Nhẹ', 'Món nhẹ, ăn vặt', 70000.00, 'images/combo10.jpg', '2025-12-02 09:35:46', 90000.00);

-- --------------------------------------------------------

--
-- Table structure for table `garan`
--

DROP TABLE IF EXISTS `garan`;
CREATE TABLE IF NOT EXISTS `garan` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `promotion` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `garan`
--

INSERT INTO `garan` (`product_id`, `name`, `description`, `price`, `image_url`, `created_at`, `promotion`) VALUES
(1, 'Gà Rán', 'Gà rán giòn rụm, nóng hổi', 60.00, 'images/fried_chicken.jpg', '2025-12-02 08:54:04', NULL),
(2, 'Gà Rán Cánh', 'Cánh gà rán giòn rụm', 30000.00, 'images/garan1.jpg', '2025-12-02 09:35:46', 40000.00),
(3, 'Gà Rán Đùi', 'Đùi gà rán vàng giòn', 35000.00, 'images/garan2.jpg', '2025-12-02 09:35:46', 45000.00),
(4, 'Gà Rán Combo 2 Miếng', 'Combo 2 miếng gà rán', 60000.00, 'images/garan3.jpg', '2025-12-02 09:35:46', 75000.00),
(5, 'Gà Rán Cay', 'Gà rán cay đặc biệt', 40000.00, 'images/garan4.jpg', '2025-12-02 09:35:46', 50000.00),
(6, 'Gà Rán Phô Mai', 'Gà rán phô mai thơm ngon', 45000.00, 'images/garan5.jpg', '2025-12-02 09:35:46', 55000.00),
(7, 'Gà Rán Thập Cẩm', 'Gà rán kết hợp nhiều vị', 50000.00, 'images/garan6.jpg', '2025-12-02 09:35:46', 65000.00),
(8, 'Gà Rán Mini', 'Miếng gà rán nhỏ, tiện ăn', 25000.00, 'images/garan7.jpg', '2025-12-02 09:35:46', 35000.00),
(9, 'Gà Rán Chảo', 'Gà rán trong chảo nóng hổi', 70000.00, 'images/garan8.jpg', '2025-12-02 09:35:46', 85000.00),
(10, 'Gà Rán Giòn Nhất', 'Gà rán bán chạy', 65000.00, 'images/garan9.jpg', '2025-12-02 09:35:46', 80000.00),
(11, 'Gà Rán BBQ', 'Gà rán sốt BBQ', 60000.00, 'images/garan10.jpg', '2025-12-02 09:35:46', 75000.00);

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

DROP TABLE IF EXISTS `khuyenmai`;
CREATE TABLE IF NOT EXISTS `khuyenmai` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `promotion` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`product_id`, `name`, `description`, `price`, `image_url`, `created_at`, `promotion`) VALUES
(2, 'Combo Trưa Tiết Kiệm', 'Áp dụng khung giờ 11h – 14h mỗi ngày.', 69000.00, 'images/khuyenmai1.jpg', '2025-12-02 09:34:16', 199000.00),
(3, 'Combo Gia Đình Cuối Tuần', 'Giảm thêm 20% cho hóa đơn từ 300.000đ.', 120000.00, 'images/khuyenmai2.jpg', '2025-12-02 09:34:16', 150000.00),
(4, 'Burger Khuyến Mãi', 'Burger giá ưu đãi chỉ hôm nay.', 45000.00, 'images/khuyenmai3.jpg', '2025-12-02 09:34:16', 60000.00),
(5, 'Gà Rán Size Lớn', 'Gà rán giòn rụm, combo 2 miếng', 80000.00, 'images/khuyenmai4.jpg', '2025-12-02 09:34:16', 100000.00),
(6, 'Mì Ý Sốt Bò', 'Mì Ý sốt bò giảm giá đặc biệt.', 70000.00, 'images/khuyenmai5.jpg', '2025-12-02 09:34:16', 90000.00),
(7, 'Nước Ngọt Combo', '2 chai nước ngọt tặng 1', 25000.00, 'images/khuyenmai6.jpg', '2025-12-02 09:34:16', 35000.00),
(8, 'Snack Giảm Giá', 'Snack khoai tây giòn rụm', 20000.00, 'images/khuyenmai7.jpg', '2025-12-02 09:34:16', 30000.00),
(9, 'Combo Burger + Gà', 'Burger và gà rán combo giá ưu đãi', 100000.00, 'images/khuyenmai8.jpg', '2025-12-02 09:34:16', 130000.00),
(10, 'Trà Sữa Khuyến Mãi', 'Trà sữa giảm 15% cho hóa đơn trên 100.000đ', 30000.00, 'images/khuyenmai9.jpg', '2025-12-02 09:34:16', 35000.00),
(11, 'Pizza Mini Sale', 'Pizza mini thơm ngon, giảm 20%', 55000.00, 'images/khuyenmai10.jpg', '2025-12-02 09:34:16', 70000.00);

-- --------------------------------------------------------

--
-- Table structure for table `miy`
--

DROP TABLE IF EXISTS `miy`;
CREATE TABLE IF NOT EXISTS `miy` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `promotion` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `miy`
--

INSERT INTO `miy` (`product_id`, `name`, `description`, `price`, `image_url`, `created_at`, `promotion`) VALUES
(1, 'Mì Ý sốt bò', 'Mì Ý với sốt bò bằm', 70.00, 'images/spaghetti_beef.jpg', '2025-12-02 08:54:04', NULL),
(2, 'Mì Ý Sốt Bò', 'Mì Ý sốt bò bằm', 70000.00, 'images/miy1.jpg', '2025-12-02 09:35:46', 90000.00),
(3, 'Mì Ý Sốt Kem', 'Mì Ý sốt kem ngon', 75000.00, 'images/miy2.jpg', '2025-12-02 09:35:46', 95000.00),
(4, 'Mì Ý Hải Sản', 'Mì Ý kết hợp hải sản', 80000.00, 'images/miy3.jpg', '2025-12-02 09:35:46', 100000.00),
(5, 'Mì Ý Rau Củ', 'Mì Ý với rau củ tươi', 65000.00, 'images/miy4.jpg', '2025-12-02 09:35:46', 85000.00),
(6, 'Mì Ý Phô Mai', 'Mì Ý phủ phô mai béo ngậy', 72000.00, 'images/miy5.jpg', '2025-12-02 09:35:46', 90000.00),
(7, 'Mì Ý Combo', 'Mì Ý + nước uống', 90000.00, 'images/miy6.jpg', '2025-12-02 09:35:46', 110000.00),
(8, 'Mì Ý Mini', 'Mì Ý size nhỏ', 50000.00, 'images/miy7.jpg', '2025-12-02 09:35:46', 65000.00),
(9, 'Mì Ý Bò Cay', 'Mì Ý sốt bò cay', 78000.00, 'images/miy8.jpg', '2025-12-02 09:35:46', 95000.00),
(10, 'Mì Ý Gà', 'Mì Ý sốt gà', 70000.00, 'images/miy9.jpg', '2025-12-02 09:35:46', 85000.00),
(11, 'Mì Ý Thập Cẩm', 'Mì Ý kết hợp nhiều nguyên liệu', 82000.00, 'images/miy10.jpg', '2025-12-02 09:35:46', 100000.00);

-- --------------------------------------------------------

--
-- Table structure for table `nuocuong`
--

DROP TABLE IF EXISTS `nuocuong`;
CREATE TABLE IF NOT EXISTS `nuocuong` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `promotion` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nuocuong`
--

INSERT INTO `nuocuong` (`product_id`, `name`, `description`, `price`, `image_url`, `created_at`, `promotion`) VALUES
(1, 'Coca Cola', 'Nước uống giải khát', 15.00, 'images/coke.jpg', '2025-12-02 08:54:04', NULL),
(2, 'Coca Cola', 'Nước uống giải khát', 15000.00, 'images/nuocuong1.jpg', '2025-12-02 09:35:46', 20000.00),
(3, 'Pepsi', 'Nước uống giải khát', 15000.00, 'images/nuocuong2.jpg', '2025-12-02 09:35:46', 20000.00),
(4, 'Trà Xanh', 'Trà xanh mát lạnh', 12000.00, 'images/nuocuong3.jpg', '2025-12-02 09:35:46', 15000.00),
(5, 'Trà Sữa Trân Châu', 'Trà sữa trân châu thơm ngon', 25000.00, 'images/nuocuong4.jpg', '2025-12-02 09:35:46', 30000.00),
(6, 'Nước Ép Cam', 'Nước ép cam tươi', 20000.00, 'images/nuocuong5.jpg', '2025-12-02 09:35:46', 25000.00),
(7, 'Nước Ép Dứa', 'Nước ép dứa thơm mát', 20000.00, 'images/nuocuong6.jpg', '2025-12-02 09:35:46', 25000.00),
(8, 'Nước Khoáng', 'Nước khoáng đóng chai', 10000.00, 'images/nuocuong7.jpg', '2025-12-02 09:35:46', 12000.00),
(9, 'Trà Đào', 'Trà đào thơm mát', 22000.00, 'images/nuocuong8.jpg', '2025-12-02 09:35:46', 27000.00),
(10, 'Nước Soda', 'Soda giải khát', 18000.00, 'images/nuocuong9.jpg', '2025-12-02 09:35:46', 22000.00),
(11, 'Nước Chanh Tươi', 'Chanh tươi mát lạnh', 20000.00, 'images/nuocuong10.jpg', '2025-12-02 09:35:46', 25000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

DROP TABLE IF EXISTS `orderitems`;
CREATE TABLE IF NOT EXISTS `orderitems` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL,
  `product_table` varchar(50) NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`order_item_id`, `order_id`, `product_table`, `product_id`, `quantity`, `price`) VALUES
(44, 'HN-20251209-002', 'khuyenmai', 2, 1, 69000.00),
(45, 'HN-20251209-002', 'combo', 4, 1, 90000.00),
(21, 'HCM-20251208-001', 'bestseller', 16, 1, 12000.00),
(22, 'HCM-20251208-001', 'bestseller', 17, 1, 110000.00),
(43, 'HN-20251209-002', 'garan', 3, 1, 35000.00),
(42, 'HN-20251209-002', 'miy', 4, 1, 80000.00),
(41, 'HN-20251209-002', 'nuocuong', 5, 1, 25000.00),
(26, 'HCM-20251208-009', 'bestseller', 17, 1, 110000.00),
(27, 'HCM-20251208-010', 'khuyenmai', 3, 1, 120000.00),
(28, 'HCM-20251208-010', 'khuyenmai', 2, 1, 69000.00),
(29, 'HCM-20251208-010', 'bestseller', 17, 1, 110000.00),
(30, 'HCM-20251208-010', 'bestseller', 16, 1, 12000.00),
(31, 'HCM-20251208-011', 'bestseller', 17, 1, 110000.00),
(32, 'HCM-20251208-011', 'khuyenmai', 2, 1, 69000.00),
(33, 'HCM-20251208-012', 'bestseller', 17, 1, 110000.00),
(34, 'HCM-20251208-012', 'bestseller', 16, 1, 12000.00),
(35, 'HCM-20251208-012', 'khuyenmai', 2, 1, 69000.00),
(36, 'HCM-20251208-013', 'bestseller', 16, 1, 12000.00),
(37, 'HN-20251208-001', 'bestseller', 17, 1, 110000.00),
(38, 'HN-20251208-001', 'bestseller', 16, 1, 12000.00),
(39, 'HN-20251208-001', 'khuyenmai', 2, 1, 69000.00),
(40, 'HN-20251209-001', 'khuyenmai', 3, 1, 120000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','preparing','delivering','completed','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'COD',
  `notes` text,
  `shipping_address` text,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `status`, `payment_method`, `notes`, `shipping_address`, `customer_name`, `customer_phone`, `created_at`, `updated_at`) VALUES
('HCM-20251208-005', 1, 80000.00, 'completed', 'BANK', 'abc', '123 Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn A', '0912345678', '2025-12-08 14:54:19', '2025-12-08 15:31:28'),
('HCM-20251208-006', 1, 122000.00, 'completed', 'COD', '', '123 Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn A', '0912345678', '2025-12-08 15:15:22', '2025-12-08 15:31:27'),
('HCM-20251208-007', 1, 189000.00, 'completed', 'COD', '', '123 Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn A', '0912345678', '2025-12-08 15:21:17', '2025-12-08 15:31:25'),
('HCM-20251208-008', 1, 189000.00, 'completed', 'COD', '', '123 Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn A', '0912345678', '2025-12-08 15:24:52', '2025-12-08 15:31:24'),
('HN-20251208-001', 2, 191000.00, 'completed', 'COD', '', '33, Phường Cổng Vị, Ba Đình, Hà Nội', 'phuc', '0911112223', '2025-12-08 18:07:09', '2025-12-08 18:09:41'),
('HCM-20251208-011', 1, 179000.00, 'completed', 'COD', '', '12, Phường 02, Quận 3, TP. Hồ Chí Minh', '12', '0818281212', '2025-12-08 16:02:09', '2025-12-08 16:03:05'),
('HCM-20251208-012', 1, 191000.00, 'completed', 'COD', '', '123 Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn A', '0912345678', '2025-12-08 16:07:19', '2025-12-08 16:10:45'),
('HCM-20251208-013', 1, 12000.00, 'cancelled', 'COD', '', '12, Phường 02, Quận 3, TP. Hồ Chí Minh', '12', '0818281212', '2025-12-08 18:00:55', '2025-12-08 18:03:34'),
('HCM-20251208-001', 1, 122000.00, 'cancelled', 'COD', '', '123 Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn A', '0912345678', '2025-12-08 15:50:02', '2025-12-08 15:52:21'),
('HCM-20251208-009', 1, 110000.00, 'cancelled', 'COD', '', '45 Trần Hưng Đạo, Q.5, TP.HCM', 'Nguyễn Văn A', '0912345678', '2025-12-08 15:57:59', '2025-12-08 15:58:12'),
('HCM-20251208-010', 1, 311000.00, 'cancelled', 'COD', 'zzz', '123 Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn A', '0912345678', '2025-12-08 15:58:45', '2025-12-08 15:58:53'),
('HN-20251209-001', 2, 120000.00, 'completed', 'COD', '', '33, Phường Cổng Vị, Ba Đình, Hà Nội', 'phuc', '0911112223', '2025-12-08 18:15:26', '2025-12-08 18:15:38'),
('HN-20251209-002', 2, 299000.00, 'completed', 'COD', '', '33, Phường Cổng Vị, Ba Đình, Hà Nội', 'phuc', '0911112223', '2025-12-08 18:33:43', '2025-12-08 18:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `fullname`, `phone`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin@example.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'Admin', '0123456789', 'active', '2025-12-02 16:54:11', '2025-12-02 16:54:11'),
(2, 'trantrongphuc.243201@gmail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', '123', '123', 'active', '2025-12-02 17:04:16', '2025-12-02 17:04:16'),
(3, 'adsmin@example.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'ad', '123', 'active', '2025-12-02 17:08:54', '2025-12-02 17:08:54'),
(4, 'user@gmail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', '123', '09294593701', 'active', '2025-12-08 14:23:55', '2025-12-08 14:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

DROP TABLE IF EXISTS `user_addresses`;
CREATE TABLE IF NOT EXISTS `user_addresses` (
  `address_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `address_text` varchar(255) NOT NULL,
  `receiver_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`address_id`, `user_id`, `address_text`, `receiver_name`, `phone`, `is_default`, `created_at`) VALUES
(1, 1, '123 Lê Lợi, Q.1, TP.HCM', 'Nguyễn Văn A', '0912345678', 1, '2025-12-02 17:52:17'),
(2, 1, '45 Trần Hưng Đạo, Q.5, TP.HCM', 'Nguyễn Văn A', '0912345678', 0, '2025-12-02 17:52:17'),
(3, 1, '12, Phường 02, Quận 3, TP. Hồ Chí Minh', '12', '0818281212', 0, '2025-12-08 16:16:25'),
(4, 2, '33, Phường Cổng Vị, Ba Đình, Hà Nội', 'phuc', '0911112223', 1, '2025-12-09 01:07:05');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
