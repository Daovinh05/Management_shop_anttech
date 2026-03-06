-- TechZone - Spring Boot Version
-- Database: phone_store_v2
-- Compatible with Spring Boot 3.x + JPA/Hibernate

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `phone_store_v2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `phone_store_v2`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `ma_user` varchar(20) NOT NULL,
  `ten_user` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mat_khau` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `phan_quyen` varchar(20) DEFAULT 'khach_hang',
  `ngay_tao` timestamp NULL DEFAULT current_timestamp(),
  `trang_thai` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`ma_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `danh_muc`
-- --------------------------------------------------------

CREATE TABLE `danh_muc` (
  `ma_danh_muc` varchar(20) NOT NULL,
  `ten_danh_muc` varchar(255) NOT NULL,
  `ngay_tao` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ma_danh_muc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `thuong_hieu`
-- --------------------------------------------------------

CREATE TABLE `thuong_hieu` (
  `ma_thuong_hieu` varchar(20) NOT NULL,
  `ten_thuong_hieu` varchar(255) NOT NULL,
  `ngay_tao` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ma_thuong_hieu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `nha_cung_cap`
-- --------------------------------------------------------

CREATE TABLE `nha_cung_cap` (
  `ma_nha_cung_cap` varchar(20) NOT NULL,
  `ten_nha_cung_cap` varchar(255) NOT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ngay_tao` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ma_nha_cung_cap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `san_pham`
-- --------------------------------------------------------

CREATE TABLE `san_pham` (
  `ma_san_pham` varchar(20) NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `ma_danh_muc` varchar(20) DEFAULT NULL,
  `ma_thuong_hieu` varchar(20) DEFAULT NULL,
  `ma_nha_cung_cap` varchar(20) DEFAULT NULL,
  `ngay_tao` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ma_san_pham`),
  KEY `fk_danh_muc` (`ma_danh_muc`),
  KEY `fk_thuong_hieu` (`ma_thuong_hieu`),
  KEY `fk_nha_cung_cap` (`ma_nha_cung_cap`),
  CONSTRAINT `fk_danh_muc` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`),
  CONSTRAINT `fk_thuong_hieu` FOREIGN KEY (`ma_thuong_hieu`) REFERENCES `thuong_hieu` (`ma_thuong_hieu`),
  CONSTRAINT `fk_nha_cung_cap` FOREIGN KEY (`ma_nha_cung_cap`) REFERENCES `nha_cung_cap` (`ma_nha_cung_cap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `bien_the`
-- --------------------------------------------------------

CREATE TABLE `bien_the` (
  `ma_bien_the` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `ten_bien_the` varchar(55) DEFAULT NULL,
  `img_bien_the` text DEFAULT NULL,
  `mau_sac` varchar(50) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `dung_luong` varchar(50) DEFAULT NULL,
  `gia` decimal(15,2) DEFAULT NULL,
  `so_luong_kho` int(11) DEFAULT 0,
  PRIMARY KEY (`ma_bien_the`),
  KEY `fk_san_pham_bt` (`ma_san_pham`),
  CONSTRAINT `fk_san_pham_bt` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `gio_hang`
-- --------------------------------------------------------

CREATE TABLE `gio_hang` (
  `ma_gio_hang` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  PRIMARY KEY (`ma_gio_hang`),
  KEY `fk_user_gh` (`ma_user`),
  CONSTRAINT `fk_user_gh` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `chi_tiet_gio_hang`
-- --------------------------------------------------------

CREATE TABLE `chi_tiet_gio_hang` (
  `ma_gio_hang` varchar(20) NOT NULL,
  `ma_bien_the` varchar(20) NOT NULL,
  `so_luong` int(11) DEFAULT 1,
  PRIMARY KEY (`ma_gio_hang`, `ma_bien_the`),
  KEY `fk_bien_the_gh` (`ma_bien_the`),
  CONSTRAINT `fk_gio_hang` FOREIGN KEY (`ma_gio_hang`) REFERENCES `gio_hang` (`ma_gio_hang`),
  CONSTRAINT `fk_bien_the_gh` FOREIGN KEY (`ma_bien_the`) REFERENCES `bien_the` (`ma_bien_the`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `dia_chi_giao_hang`
-- --------------------------------------------------------

CREATE TABLE `dia_chi_giao_hang` (
  `ma_dia_chi` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `la_mac_dinh` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`ma_dia_chi`),
  KEY `fk_user_dc` (`ma_user`),
  CONSTRAINT `fk_user_dc` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `khuyen_mai`
-- --------------------------------------------------------

CREATE TABLE `khuyen_mai` (
  `ma_khuyen_mai` varchar(20) NOT NULL,
  `ten_khuyen_mai` varchar(255) NOT NULL,
  `mo_ta` varchar(500) DEFAULT NULL,
  `gia_tri` decimal(15,2) DEFAULT NULL,
  `loai_khuyen_mai` varchar(20) DEFAULT NULL,
  `ngay_bat_dau` timestamp NULL DEFAULT NULL,
  `ngay_ket_thuc` timestamp NULL DEFAULT NULL,
  `trang_thai` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`ma_khuyen_mai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `don_hang`
-- --------------------------------------------------------

CREATE TABLE `don_hang` (
  `ma_don_hang` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ma_dia_chi` varchar(20) DEFAULT NULL,
  `ma_khuyen_mai` varchar(20) DEFAULT NULL,
  `ngay_dat` timestamp NULL DEFAULT current_timestamp(),
  `tong_tien` decimal(15,2) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT 'Moi',
  `ghi_chu` varchar(500) DEFAULT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phuong_thuc_thanh_toan` varchar(20) DEFAULT 'cod',
  PRIMARY KEY (`ma_don_hang`),
  KEY `fk_user_dh` (`ma_user`),
  KEY `fk_dia_chi_dh` (`ma_dia_chi`),
  KEY `fk_khuyen_mai_dh` (`ma_khuyen_mai`),
  CONSTRAINT `fk_user_dh` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`),
  CONSTRAINT `fk_dia_chi_dh` FOREIGN KEY (`ma_dia_chi`) REFERENCES `dia_chi_giao_hang` (`ma_dia_chi`),
  CONSTRAINT `fk_khuyen_mai_dh` FOREIGN KEY (`ma_khuyen_mai`) REFERENCES `khuyen_mai` (`ma_khuyen_mai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `chi_tiet_don_hang`
-- --------------------------------------------------------

CREATE TABLE `chi_tiet_don_hang` (
  `ma_ctdh` varchar(20) NOT NULL,
  `ma_don_hang` varchar(20) NOT NULL,
  `ma_bien_the` varchar(20) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `gia_luc_mua` decimal(15,2) NOT NULL,
  PRIMARY KEY (`ma_ctdh`),
  KEY `fk_don_hang_ct` (`ma_don_hang`),
  KEY `fk_bien_the_ct` (`ma_bien_the`),
  CONSTRAINT `fk_don_hang_ct` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`),
  CONSTRAINT `fk_bien_the_ct` FOREIGN KEY (`ma_bien_the`) REFERENCES `bien_the` (`ma_bien_the`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `danh_gia`
-- --------------------------------------------------------

CREATE TABLE `danh_gia` (
  `ma_danh_gia` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `so_sao` int(11) DEFAULT NULL CHECK (`so_sao` >= 1 and `so_sao` <= 5),
  `noi_dung` text DEFAULT NULL,
  `phan_hoi` text DEFAULT NULL,
  `ngay_danh_gia` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ma_danh_gia`),
  KEY `fk_user_dg` (`ma_user`),
  KEY `fk_san_pham_dg` (`ma_san_pham`),
  CONSTRAINT `fk_user_dg` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`),
  CONSTRAINT `fk_san_pham_dg` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table `users`
-- --------------------------------------------------------

INSERT INTO `users` (`ma_user`, `ten_user`, `email`, `mat_khau`, `so_dien_thoai`, `dia_chi`, `phan_quyen`, `trang_thai`) VALUES
('U01', 'vinh', 'vinh@techzone.com', '123', '0901234567', 'Ha Noi', 'admin', 1),
('U02', 'hung', 'hung@techzone.com', '123', '0901234568', 'Ha Noi', 'admin', 1),
('U06', 'long', 'long@gmail.com', '123', '0901111111', 'Ha Noi', 'khach_hang', 1),
('U07', 'minh', 'minh@gmail.com', '1234', '0902222222', 'TP HCM', 'khach_hang', 1);

-- --------------------------------------------------------
-- Dumping data for table `danh_muc`
-- --------------------------------------------------------

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`) VALUES
('DM01', 'Điện thoại'),
('DM02', 'Máy tính bảng'),
('DM03', 'Phụ kiện'),
('DM06', 'Macbook'),
('DM07', 'Laptop Gaming'),
('DM08', 'Tai nghe'),
('DM09', 'Sạc dự phòng'),
('DM10', 'Đồng hồ thông minh');

-- --------------------------------------------------------
-- Dumping data for table `thuong_hieu`
-- --------------------------------------------------------

INSERT INTO `thuong_hieu` (`ma_thuong_hieu`, `ten_thuong_hieu`) VALUES
('TH01', 'Apple'),
('TH02', 'Samsung'),
('TH03', 'Xiaomi'),
('TH04', 'ASUS'),
('TH05', 'Dell'),
('TH06', 'HP'),
('TH07', 'Lenovo'),
('TH08', 'Huawei');

-- --------------------------------------------------------
-- Dumping data for table `nha_cung_cap`
-- --------------------------------------------------------

INSERT INTO `nha_cung_cap` (`ma_nha_cung_cap`, `ten_nha_cung_cap`, `dia_chi`, `so_dien_thoai`, `email`) VALUES
('NCC01', 'Công ty TNHH Apple VN', 'Hà Nội', '0243123456', 'contact@apple.vn'),
('NCC02', 'Samsung Vietnam', 'TP HCM', '0283123456', 'contact@samsung.vn'),
('NCC03', 'Xiaomi Vietnam', 'Đà Nẵng', '02363123456', 'contact@xiaomi.vn');

-- --------------------------------------------------------
-- Dumping data for table `san_pham`
-- --------------------------------------------------------

INSERT INTO `san_pham` (`ma_san_pham`, `ten_san_pham`, `ma_danh_muc`, `ma_thuong_hieu`, `ma_nha_cung_cap`) VALUES
('SP01', 'Laptop ASUS TUF Gaming', 'DM07', 'TH04', 'NCC01'),
('SP02', 'Tai nghe Sony WH-1000XM5', 'DM08', 'TH08', 'NCC01'),
('SP03', 'Sạc dự phòng 20000mAh', 'DM09', 'TH03', 'NCC03'),
('SP04', 'MacBook Air M2', 'DM06', 'TH01', 'NCC01'),
('SP05', 'Apple Watch SE 2023', 'DM03', 'TH01', 'NCC01'),
('SP06', 'iPhone 17 Pro Max', 'DM01', 'TH01', 'NCC01'),
('SP07', 'Laptop HP Gaming', 'DM07', 'TH06', 'NCC01'),
('SP08', 'Laptop ASUS ROG', 'DM07', 'TH04', 'NCC01');

-- --------------------------------------------------------
-- Dumping data for table `bien_the`
-- --------------------------------------------------------

INSERT INTO `bien_the` (`ma_bien_the`, `ma_san_pham`, `ten_bien_the`, `mau_sac`, `ram`, `dung_luong`, `gia`, `so_luong_kho`) VALUES
('BT01', 'SP01', '16GB RAM 512GB ROM', 'Đen', '16GB', '512GB', 25000000.00, 10),
('BT02', 'SP020', 'Bản quốc tế', 'Đen', '16GB', '512GB', 18000000.00, 18),
('BT03', 'SP03', 'Sạc nhanh', 'Trắng', '', '20000mAh', 1200000.00, 30),
('BT04', 'SP04', '16GB/256GB/Cam', 'Cam vũ trụ', '16GB', '256GB', 29990000.00, 12),
('BT05', 'SP05', '44mm/Đen', 'Đen', '', '', 9100000.00, 6);

-- --------------------------------------------------------
-- Dumping data for table `khuyen_mai`
-- --------------------------------------------------------

INSERT INTO `khuyen_mai` (`ma_khuyen_mai`, `ten_khuyen_mai`, `mo_ta`, `gia_tri`, `loai_khuyen_mai`, `ngay_bat_dau`, `ngay_ket_thuc`, `trang_thai`) VALUES
('KM01', 'Giảm giá 10%', 'Giảm 10% cho đơn hàng trên 10 triệu', 10.00, 'phan_tram', '2026-01-01 00:00:00', '2026-12-31 23:59:59', 1),
('KM02', 'Giảm 500K', 'Giảm 500K cho đơn hàng từ 5 triệu', 500000.00, 'so_tien', '2026-01-01 00:00:00', '2026-06-30 23:59:59', 1);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
