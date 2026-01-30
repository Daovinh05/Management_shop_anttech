-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2026 at 11:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phone_store_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `bien_the`
--

CREATE TABLE `bien_the` (
  `ma_bien_the` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `ten_bien_the` varchar(55) DEFAULT NULL,
  `mau_sac` varchar(50) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `dung_luong` varchar(50) DEFAULT NULL,
  `gia` decimal(15,2) DEFAULT NULL,
  `so_luong_kho` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_ctdh` varchar(20) NOT NULL,
  `ma_don_hang` varchar(20) NOT NULL,
  `ma_bien_the` varchar(20) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `gia_luc_mua` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_gio_hang`
--

CREATE TABLE `chi_tiet_gio_hang` (
  `ma_gio_hang` varchar(20) NOT NULL,
  `ma_bien_the` varchar(20) NOT NULL,
  `so_luong` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `ma_danh_gia` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `so_sao` int(11) DEFAULT NULL CHECK (`so_sao` >= 1 and `so_sao` <= 5),
  `noi_dung` text DEFAULT NULL,
  `ngay_danh_gia` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` varchar(20) NOT NULL,
  `ten_danh_muc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dia_chi_giao_hang`
--

CREATE TABLE `dia_chi_giao_hang` (
  `ma_dia_chi` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `mac_dinh` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_don_hang` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ma_dia_chi` varchar(20) DEFAULT NULL,
  `ma_khuyen_mai` varchar(20) DEFAULT NULL,
  `tong_tien_hang` decimal(15,2) DEFAULT NULL,
  `trang_thai_don_hang` enum('cho_duyet','dang_giao','hoan_thanh','da_huy') DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gio_hang`
--

CREATE TABLE `gio_hang` (
  `ma_gio_hang` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `trang_thai` enum('active','ordered') DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `ma_khuyen_mai` varchar(20) NOT NULL,
  `ten_khuyen_mai` varchar(255) DEFAULT NULL,
  `tien_khuyen_mai` decimal(15,2) DEFAULT NULL,
  `ngay_bat_dau` datetime DEFAULT NULL,
  `ngay_ket_thuc` datetime DEFAULT NULL,
  `trang_thai_khuyen_mai` enum('con','het') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nha_cung_cap`
--

CREATE TABLE `nha_cung_cap` (
  `ma_nha_cung_cap` varchar(20) NOT NULL,
  `ten_nha_cung_cap` varchar(255) NOT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `dien_thoai` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `ma_san_pham` varchar(20) NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `img_hinh_anh` text DEFAULT NULL,
  `ma_danh_muc` varchar(20) DEFAULT NULL,
  `ma_thuong_hieu` varchar(20) DEFAULT NULL,
  `ma_nha_cung_cap` varchar(20) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `ma_giao_dich` varchar(20) NOT NULL,
  `ma_don_hang` varchar(20) NOT NULL,
  `phuong_thuc` varchar(50) DEFAULT NULL,
  `so_tien_thanh_toan` decimal(15,2) DEFAULT NULL,
  `trang_thai_thanh_toan` enum('da_thanh_toan','chua_thanh_toan') DEFAULT NULL,
  `ngay_thanh_toan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `ma_thuong_hieu` varchar(20) NOT NULL,
  `ten_thuong_hieu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ma_user` varchar(20) NOT NULL,
  `ten_user` varchar(55) NOT NULL,
  `password_hash` varchar(55) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `phan_quyen` enum('admin','customer') DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bien_the`
--
ALTER TABLE `bien_the`
  ADD PRIMARY KEY (`ma_bien_the`),
  ADD KEY `fk_bt_sp` (`ma_san_pham`);

--
-- Indexes for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_ctdh`),
  ADD KEY `fk_ctdh_dh` (`ma_don_hang`),
  ADD KEY `fk_ctdh_bt` (`ma_bien_the`);

--
-- Indexes for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD PRIMARY KEY (`ma_gio_hang`,`ma_bien_the`),
  ADD KEY `fk_ghct_bt` (`ma_bien_the`);

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`ma_danh_gia`),
  ADD KEY `fk_dg_user` (`ma_user`),
  ADD KEY `fk_dg_sp` (`ma_san_pham`);

--
-- Indexes for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

--
-- Indexes for table `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD PRIMARY KEY (`ma_dia_chi`),
  ADD KEY `fk_dc_user` (`ma_user`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `fk_dh_user` (`ma_user`),
  ADD KEY `fk_dh_diachi` (`ma_dia_chi`),
  ADD KEY `fk_dh_km` (`ma_khuyen_mai`);

--
-- Indexes for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`ma_gio_hang`),
  ADD KEY `fk_gh_user` (`ma_user`);

--
-- Indexes for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`ma_khuyen_mai`);

--
-- Indexes for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  ADD PRIMARY KEY (`ma_nha_cung_cap`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`ma_san_pham`),
  ADD KEY `fk_sp_dm` (`ma_danh_muc`),
  ADD KEY `fk_sp_th` (`ma_thuong_hieu`),
  ADD KEY `fk_sp_ncc` (`ma_nha_cung_cap`);

--
-- Indexes for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`ma_giao_dich`),
  ADD KEY `fk_tt_dh` (`ma_don_hang`);

--
-- Indexes for table `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  ADD PRIMARY KEY (`ma_thuong_hieu`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ma_user`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bien_the`
--
ALTER TABLE `bien_the`
  ADD CONSTRAINT `fk_bt_sp` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE;

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_bt` FOREIGN KEY (`ma_bien_the`) REFERENCES `bien_the` (`ma_bien_the`),
  ADD CONSTRAINT `fk_ctdh_dh` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE;

--
-- Constraints for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD CONSTRAINT `fk_ghct_bt` FOREIGN KEY (`ma_bien_the`) REFERENCES `bien_the` (`ma_bien_the`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ghct_gh` FOREIGN KEY (`ma_gio_hang`) REFERENCES `gio_hang` (`ma_gio_hang`) ON DELETE CASCADE;

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `fk_dg_sp` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dg_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE CASCADE;

--
-- Constraints for table `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD CONSTRAINT `fk_dc_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE CASCADE;

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_dh_diachi` FOREIGN KEY (`ma_dia_chi`) REFERENCES `dia_chi_giao_hang` (`ma_dia_chi`),
  ADD CONSTRAINT `fk_dh_km` FOREIGN KEY (`ma_khuyen_mai`) REFERENCES `khuyen_mai` (`ma_khuyen_mai`),
  ADD CONSTRAINT `fk_dh_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`);

--
-- Constraints for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `fk_gh_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE CASCADE;

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `fk_sp_dm` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_sp_ncc` FOREIGN KEY (`ma_nha_cung_cap`) REFERENCES `nha_cung_cap` (`ma_nha_cung_cap`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_sp_th` FOREIGN KEY (`ma_thuong_hieu`) REFERENCES `thuong_hieu` (`ma_thuong_hieu`) ON DELETE SET NULL;

--
-- Constraints for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `fk_tt_dh` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
