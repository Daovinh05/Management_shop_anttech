-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 01, 2026 lúc 04:01 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `phone_store_v2`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_the`
--

CREATE TABLE `bien_the` (
  `ma_bien_the` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `ten_bien_the` varchar(55) DEFAULT NULL,
  `img_bien_the` text NOT NULL,
  `mau_sac` varchar(50) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `dung_luong` varchar(50) DEFAULT NULL,
  `gia` decimal(15,2) DEFAULT NULL,
  `so_luong_kho` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_the`
--

INSERT INTO `bien_the` (`ma_bien_the`, `ma_san_pham`, `ten_bien_the`, `mau_sac`, `ram`, `dung_luong`, `gia`, `so_luong_kho`) VALUES
('BT001', 'SP001', '8GB/256GB/Titan', 'Titan Tự Nhiên', '8GB', '256GB', 29990000.00, 50),
('BT002', 'SP001', '8GB/512GB/Đen', 'Đen', '8GB', '512GB', 35990000.00, 20),
('BT003', 'SP002', '12GB/256GB/Xám', 'Xám Titan', '12GB', '256GB', 26990000.00, 30),
('BT004', 'SP002', '12GB/512GB/Vàng', 'Vàng', '12GB', '512GB', 29990000.00, 15),
('BT005', 'SP003', '12GB/256GB/Xanh', 'Xanh Lá', '12GB', '256GB', 19990000.00, 100),
('BT006', 'SP004', '8GB/64GB/Wifi', 'Xám', '8GB', '64GB', 13500000.00, 40);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_ctdh` varchar(20) NOT NULL,
  `ma_don_hang` varchar(20) NOT NULL,
  `ma_bien_the` varchar(20) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `gia_luc_mua` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ma_ctdh`, `ma_don_hang`, `ma_bien_the`, `so_luong`, `gia_luc_mua`) VALUES
('CTDH001', 'DH001', 'BT001', 1, 29990000.00),
('CTDH002', 'DH002', 'BT003', 1, 26990000.00),
('CTDH003', 'DH003', 'BT002', 2, 35990000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_gio_hang`
--

CREATE TABLE `chi_tiet_gio_hang` (
  `ma_gio_hang` varchar(20) NOT NULL,
  `ma_bien_the` varchar(20) NOT NULL,
  `so_luong` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_gio_hang`
--

INSERT INTO `chi_tiet_gio_hang` (`ma_gio_hang`, `ma_bien_the`, `so_luong`) VALUES
('GH001', 'BT005', 2),
('GH001', 'BT006', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `ma_danh_gia` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `so_sao` int(11) DEFAULT NULL CHECK (`so_sao` >= 1 and `so_sao` <= 5),
  `noi_dung` text DEFAULT NULL,
  `ngay_danh_gia` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_gia`
--

INSERT INTO `danh_gia` (`ma_danh_gia`, `ma_user`, `ma_san_pham`, `so_sao`, `noi_dung`, `ngay_danh_gia`) VALUES
('DG001', 'US001', 'SP001', 5, 'Máy đẹp, mượt, titan tự nhiên nhìn sang.', '2026-01-30 15:05:58'),
('DG002', 'US002', 'SP002', 4, 'Chụp ảnh đẹp nhưng máy hơi nóng khi chơi game.', '2026-01-30 15:05:58'),
('DG003', 'US001', 'SP003', 5, 'Giá rẻ so với cấu hình, sạc siêu nhanh.', '2026-01-30 15:05:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` varchar(20) NOT NULL,
  `ten_danh_muc` varchar(255) NOT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `ngay_tao`) VALUES
('DM01', 'Điện thoại', '2026-02-01 14:49:22'),
('DM02', 'Máy tính bảng', '2026-02-01 14:49:22'),
('DM03', 'Phụ kiện', '2026-02-01 14:49:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_chi_giao_hang`
--

CREATE TABLE `dia_chi_giao_hang` (
  `ma_dia_chi` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `mac_dinh` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dia_chi_giao_hang`
--

INSERT INTO `dia_chi_giao_hang` (`ma_dia_chi`, `ma_user`, `ho_ten`, `so_dien_thoai`, `dia_chi`, `mac_dinh`) VALUES
('DC001', 'US001', 'Đào Phúc Dân', '0862757951', '123 Đường Láng, Đống Đa, Hà Nội', 1),
('DC002', 'US001', 'Dân (Cơ quan)', '0862757951', 'Tòa nhà FPT, Cầu Giấy, Hà Nội', 0),
('DC003', 'US002', 'Đào Văn Vinh', '0987654321', '456 Lê Lợi, Quận 1, TP.HCM', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_don_hang` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ma_dia_chi` varchar(20) DEFAULT NULL,
  `ma_khuyen_mai` varchar(20) DEFAULT NULL,
  `tong_tien_hang` decimal(15,2) DEFAULT NULL,
  `thanh_toan` decimal(15,2) NOT NULL,
  `trang_thai_don_hang` enum('cho_duyet','dang_giao','hoan_thanh','da_huy') DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`ma_don_hang`, `ma_user`, `ma_dia_chi`, `ma_khuyen_mai`, `tong_tien_hang`, `trang_thai_don_hang`, `ngay_tao`) VALUES
('DH001', 'US001', 'DC001', 'KM01', 29490000.00, 'hoan_thanh', '2024-06-15 10:30:00'),
('DH002', 'US002', 'DC003', NULL, 26990000.00, 'dang_giao', '2024-06-20 14:00:00'),
('DH003', 'US001', 'DC002', NULL, 71980000.00, 'da_huy', '2024-06-10 09:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gio_hang`
--

CREATE TABLE `gio_hang` (
  `ma_gio_hang` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `trang_thai` enum('active','ordered') DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `gio_hang`
--

INSERT INTO `gio_hang` (`ma_gio_hang`, `ma_user`, `trang_thai`, `ngay_tao`) VALUES
('GH001', 'US001', 'active', '2026-01-30 15:05:58'),
('GH002', 'US002', 'active', '2026-01-30 15:05:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `ma_khuyen_mai` varchar(20) NOT NULL,
  `ten_khuyen_mai` varchar(255) DEFAULT NULL,
  `tien_khuyen_mai` decimal(15,2) DEFAULT NULL,
  `ngay_bat_dau` datetime DEFAULT NULL,
  `ngay_ket_thuc` datetime DEFAULT NULL,
  `trang_thai_khuyen_mai` enum('con','het') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`ma_khuyen_mai`, `ten_khuyen_mai`, `tien_khuyen_mai`, `ngay_bat_dau`, `ngay_ket_thuc`, `trang_thai_khuyen_mai`) VALUES
('KM01', 'Chào Hè 2024', 500000.00, '2024-05-01 00:00:00', '2026-02-13 00:00:00', 'con'),
('KM02', 'Black Friday', 1000000.00, '2024-11-20 00:00:00', '2024-11-30 00:00:00', 'het'),
('KM03', 'Khách hàng mới', 200000.00, '2024-01-01 00:00:00', '2025-01-01 00:00:00', 'con');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nha_cung_cap`
--

CREATE TABLE `nha_cung_cap` (
  `ma_nha_cung_cap` varchar(20) NOT NULL,
  `ten_nha_cung_cap` varchar(255) NOT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `dien_thoai` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nha_cung_cap`
--

INSERT INTO `nha_cung_cap` (`ma_nha_cung_cap`, `ten_nha_cung_cap`, `dia_chi`, `dien_thoai`) VALUES
('NCC01', 'FPT Trading', 'Duy Tân, Hà Nội', '02473008888'),
('NCC02', 'DigiWorld', 'Hoàng Hoa Thám, TP.HCM', '02839290059'),
('NCC03', 'Viettel Store', 'Giảng Võ, Hà Nội', '18008123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `ma_san_pham` varchar(20) NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `ma_danh_muc` varchar(20) DEFAULT NULL,
  `ma_thuong_hieu` varchar(20) DEFAULT NULL,
  `ma_nha_cung_cap` varchar(20) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`ma_san_pham`, `ten_san_pham`, `img_hinh_anh`, `ma_danh_muc`, `ma_thuong_hieu`, `ma_nha_cung_cap`, `ngay_tao`) VALUES
('SP001', 'iPhone 15 Pro Max', 'iphone15pm.jpg', 'DM01', 'TH01', 'NCC01', '2026-01-30 15:05:58'),
('SP002', 'Samsung Galaxy S24 Ultra', 's24ultra.jpg', 'DM01', 'TH02', 'NCC02', '2026-01-30 15:05:58'),
('SP003', 'Xiaomi 14', 'xiaomi14.jpg', 'DM01', 'TH03', 'NCC02', '2026-01-30 15:05:58'),
('SP004', 'iPad Air 5 M1', 'ipadair5.jpg', 'DM02', 'TH01', 'NCC01', '2026-01-30 15:05:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `ma_giao_dich` varchar(20) NOT NULL,
  `ma_don_hang` varchar(20) NOT NULL,
  `phuong_thuc` varchar(50) DEFAULT NULL,
  `so_tien_thanh_toan` decimal(15,2) DEFAULT NULL,
  `trang_thai_thanh_toan` enum('da_thanh_toan','chua_thanh_toan') DEFAULT NULL,
  `ngay_thanh_toan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thanh_toan`
--

INSERT INTO `thanh_toan` (`ma_giao_dich`, `ma_don_hang`, `phuong_thuc`, `so_tien_thanh_toan`, `trang_thai_thanh_toan`, `ngay_thanh_toan`) VALUES
('TT001', 'DH001', 'MOMO', 29490000.00, 'da_thanh_toan', '2024-06-15 10:35:00'),
('TT002', 'DH002', 'COD', 26990000.00, 'chua_thanh_toan', NULL),
('TT003', 'DH003', 'BANKING', 71980000.00, '', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `ma_thuong_hieu` varchar(20) NOT NULL,
  `ten_thuong_hieu` varchar(255) NOT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuong_hieu`
--

INSERT INTO `thuong_hieu` (`ma_thuong_hieu`, `ten_thuong_hieu`, `ngay_tao`) VALUES
('TH01', 'Apple', '2026-02-01 14:49:22'),
('TH02', 'Samsung', '2026-02-01 14:49:22'),
('TH03', 'Xiaomi', '2026-02-01 14:49:22'),
('TH04', 'Oppo', '2026-02-01 14:49:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `ma_user` varchar(20) NOT NULL,
  `ten_user` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `phan_quyen` enum('admin','khach_hang') DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`ma_user`, `ten_user`, `password`, `full_name`, `email`, `phan_quyen`, `so_dien_thoai`, `ngay_tao`) VALUES
('ADMIN01', 'admin', '123', 'Quản Trị Viên', 'admin@phonestore.com', 'admin', '0909000000', '2026-01-30 15:05:58'),
('US001', 'dan', '123', 'Đào Phúc Dân', 'phucdan@gmail.com', 'khach_hang', '0862757951', '2026-01-30 15:05:58'),
('US002', 'vinh', '123', 'Đào Văn Vinh', 'vanvinh@gmail.com', 'khach_hang', '0987654321', '2026-01-30 15:05:58'),
('US003', 'thanh', '123', 'Hoàng Văn Thành', 'hoangthanh@gmail.com', 'khach_hang', '0123456789', '2026-02-01 21:50:15');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bien_the`
--
ALTER TABLE `bien_the`
  ADD PRIMARY KEY (`ma_bien_the`),
  ADD KEY `fk_bt_sp` (`ma_san_pham`);

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_ctdh`),
  ADD KEY `fk_ctdh_dh` (`ma_don_hang`),
  ADD KEY `fk_ctdh_bt` (`ma_bien_the`);

--
-- Chỉ mục cho bảng `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD PRIMARY KEY (`ma_gio_hang`,`ma_bien_the`),
  ADD KEY `fk_ghct_bt` (`ma_bien_the`);

--
-- Chỉ mục cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`ma_danh_gia`),
  ADD KEY `fk_dg_user` (`ma_user`),
  ADD KEY `fk_dg_sp` (`ma_san_pham`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

--
-- Chỉ mục cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD PRIMARY KEY (`ma_dia_chi`),
  ADD KEY `fk_dc_user` (`ma_user`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `fk_dh_user` (`ma_user`),
  ADD KEY `fk_dh_diachi` (`ma_dia_chi`),
  ADD KEY `fk_dh_km` (`ma_khuyen_mai`);

--
-- Chỉ mục cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`ma_gio_hang`),
  ADD KEY `fk_gh_user` (`ma_user`);

--
-- Chỉ mục cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`ma_khuyen_mai`);

--
-- Chỉ mục cho bảng `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  ADD PRIMARY KEY (`ma_nha_cung_cap`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`ma_san_pham`),
  ADD KEY `fk_sp_dm` (`ma_danh_muc`),
  ADD KEY `fk_sp_th` (`ma_thuong_hieu`),
  ADD KEY `fk_sp_ncc` (`ma_nha_cung_cap`);

--
-- Chỉ mục cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`ma_giao_dich`),
  ADD KEY `fk_tt_dh` (`ma_don_hang`);

--
-- Chỉ mục cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  ADD PRIMARY KEY (`ma_thuong_hieu`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ma_user`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bien_the`
--
ALTER TABLE `bien_the`
  ADD CONSTRAINT `fk_bt_sp` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_ctdh_bt` FOREIGN KEY (`ma_bien_the`) REFERENCES `bien_the` (`ma_bien_the`),
  ADD CONSTRAINT `fk_ctdh_dh` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD CONSTRAINT `fk_ghct_bt` FOREIGN KEY (`ma_bien_the`) REFERENCES `bien_the` (`ma_bien_the`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ghct_gh` FOREIGN KEY (`ma_gio_hang`) REFERENCES `gio_hang` (`ma_gio_hang`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `fk_dg_sp` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dg_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD CONSTRAINT `fk_dc_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_dh_diachi` FOREIGN KEY (`ma_dia_chi`) REFERENCES `dia_chi_giao_hang` (`ma_dia_chi`),
  ADD CONSTRAINT `fk_dh_km` FOREIGN KEY (`ma_khuyen_mai`) REFERENCES `khuyen_mai` (`ma_khuyen_mai`),
  ADD CONSTRAINT `fk_dh_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`);

--
-- Các ràng buộc cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `fk_gh_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `fk_sp_dm` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_sp_ncc` FOREIGN KEY (`ma_nha_cung_cap`) REFERENCES `nha_cung_cap` (`ma_nha_cung_cap`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_sp_th` FOREIGN KEY (`ma_thuong_hieu`) REFERENCES `thuong_hieu` (`ma_thuong_hieu`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `fk_tt_dh` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
