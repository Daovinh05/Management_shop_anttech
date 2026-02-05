-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 05, 2026 lúc 09:26 AM
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
-- Cơ sở dữ liệu: `banhang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_the`
--

CREATE TABLE `bien_the` (
  `ma_bien_the` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `ten_bien_the` varchar(55) DEFAULT NULL,
  `img_bien_the` varchar(255) DEFAULT NULL,
  `mau_sac` varchar(50) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `dung_luong` varchar(50) DEFAULT NULL,
  `gia` decimal(15,2) DEFAULT NULL,
  `so_luong_kho` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_the`
--

INSERT INTO `bien_the` (`ma_bien_the`, `ma_san_pham`, `ten_bien_the`, `img_bien_the`, `mau_sac`, `ram`, `dung_luong`, `gia`, `so_luong_kho`) VALUES
('BT008', 'SP04', '16GB/256GB/Xám', 'macbook_air_m2_1_1_1_8.webp', 'Xám tro', '16GB', '256GB', 24900000.00, 8),
('BT009', 'SP04', '16GB/256GB/Vàng', 'vn0d33_1_1770016506.webp', 'Vàng sa mạc', '16GB', '256GB', 251990000.00, 7),
('BT01', 'SP01', '16GB RAM 512GB ROM', 'tuf_den.jpg', 'Đen', '16GB', '512GB', 25000000.00, 10),
('BT010', 'SP04', '16GB/256GB/Bạc', 'macbook_air_m2_2_1_1_7.webp', 'Bạc Titan', '16GB', '256GB', 2490000.00, 8),
('BT011', 'SP04', '16GB/256GB/Xám', 'macbook_air_m2_4_1_1_6.webp', 'Xám ', '16GB', '256GB', 25490000.00, 8),
('BT012', 'SP019', '8GB/256GB/Xanh', 'oppo_reno_15f_1.webp', 'Xanh Dương', '8GB', '256GB', 11670000.00, 11),
('BT013', 'SP019', '8GB/256GB/Xanh', 'oppo_reno15f_5g.webp', 'Xanh nhạt', '8GB', '256GB', 11567000.00, 9),
('BT014', 'SP019', '8GB/256GB/Hồng', 'oppo_reno_15f_2.webp', 'Hồng cánh sen', '8GB', '256GB', 11490000.00, 9),
('BT015', 'SP018', '6GB/256GB/Đen', 'redmi_note_15_series_2_3.webp', 'Đen', '6GB', '256GB', 4930000.00, 49),
('BT016', 'SP018', '6GB/128GB/Tím', 'redmi_note_15_series_1_3_1770018150.webp', 'Tím', '6GB', '128GB', 4990000.00, 9),
('BT017', 'SP018', '6GB/128GB/Xanh', 'redmi_note_15_series_3.webp', 'Xanh da trời', '6GB', '128GB', 4990000.00, 9),
('BT018', 'SP017', 'VGA 6GB RTX3050 - CORE 5-210H - 8GB/512GB/Đen', 'text_ng_n_7__4_146_1770018135.webp', 'Đen', '8GB', '512GB', 20900000.00, 11),
('BT019', 'SP017', 'VGA 4GB RTX2050 - R7-7435HS - 16GB/512GB/Đen', 'text_ng_n_7__4_146_2.webp', 'Đen', '16GB', '512GB', 21990000.00, 4),
('BT02', 'SP020', 'Bản quốc tế', 'mac_book_1769963800.jpg', 'Đen', '16GB', '512GB', 18000000.00, 14),
('BT020', 'SP017', 'VGA 8GB RTX5050 - i5-13450HX - 32GB/1TB/Đen', 'text_ng_n_7__4_146_3.webp', 'Đen', '32GB', '1TB', 24900000.00, 3),
('BT021', 'SP017', 'VGA 8GB RTX5070 -  i7-14650HX - 16GB/1TB/Đen', 'text_ng_n_7__4_146_4.webp', 'Đen', '16GB', '1TB', 41990000.00, 1),
('BT022', 'SP08', 'VGA 6GB RTX3050 -  R7-7735HS - 16GB/512GB/Xám', 'text_ng_n_6__4_24_1770019902.webp', 'Xám', '16GB', '512GB', 20990000.00, 3),
('BT023', 'SP08', 'VGA 6GB RTX4050 - R7-7735HS - 16GB/512GB/Xám', 'text_ng_n_6__4_24.webp', 'Xám', '16GB', '512GB', 24990000.00, 3),
('BT024', 'SP08', 'VGA 8GB RTX5050 - i7-13650HX - 16GB/512GB/Xám', 'text_ng_n_6__4_24_1.webp', 'Xám', '16GB', '512GB', 33990000.00, 2),
('BT025', 'SP09', 'Intel UHD Graphics - I5-12450H/16GB/512GB/Đen', 'text_ng_n_13__8_27.webp', 'Đen', '16GB', '512GB', 19990000.00, 3),
('BT026', 'SP09', 'VGA 4GB RTX2050 - I5-12450H/16GB/512GB/Đen', 'text_ng_n_13__8_27_1.webp', 'Đen', '16GB', '512GB', 21990000.00, 5),
('BT027', 'SP09', 'VGA 6GB RTX3050 - i7-12650H/16GB/512GB/Đen', 'text_ng_n_13__8_27_2.webp', 'Đen', '16GB', '512GB', 24090000.00, 6),
('BT028', 'SP010', ' VGA 2GB MX570A - CORE 7-150U/16GB/512GB/Đen', 'text_ng_n_3__7_221_1770019887.webp', 'Đen', '16GB', '512GB', 16490000.00, 5),
('BT029', 'SP010', 'I5-1334U/24GB/512GB/Đen', 'text_ng_n_3__7_221_1.webp', 'Đen', '24GB', '512GB', 17990000.00, 5),
('BT03', 'SP03', 'Sạc nhanh', 'frame_2_54__1770184775.webp', 'Trắng', '', '20000mAh', 1200000.00, 30),
('BT030', 'SP010', 'Snapdragon X PLUS X1P - 16GB/1TB/Đen', 'text_ng_n_3__7_221_1770019929.webp', 'Đen', '16GB', '1TB', 19990000.00, 5),
('BT031', 'SP011', '44mm/Đen', 'dh_2__5.webp', 'Đen', '', '', 8500000.00, 0),
('BT032', 'SP011', '48mm/Đen', 'dh_2__5_1.webp', 'Đen', '', '', 7990000.00, 5),
('BT033', 'SP011', '44mm/Vàng', 't_rex_3_pro_vang.webp', 'Vàng', '', '', 8490000.00, 10),
('BT034', 'SP011', '48mm/Vàng', 't_rex_3_pro_vang_1.webp', 'Vàng', '', '', 7990000.00, 0),
('BT035', 'SP012', 'Polyme/ Đen', 'text_ng_n_32__7_31_3.webp', 'Đen', '', '', 690000.00, 10),
('BT039', 'SP013', 'AirPods Pro 3 2025', 'airpods_pro_3_sep25_pdp_image_position_2__vn_vi_1.webp', 'AirPods Pro 3 2025', '', '', 6590000.00, 12),
('BT04', 'SP024', '8GB/256GB/Cam', 'iphone_17_pro_max_3_1770184820.webp', 'Cam vũ trụ', '8GB', '256GB', 29990000.00, 14),
('BT051', 'SP024', '8GB/512GB/Đen', 'iphone_17_pro_max_1.webp', 'Đen', '8GB', '512GB', 35990000.00, 11),
('BT052', 'SP021', '12GB/256GB/Xám', 'dien_thoai_samsung_galaxy_s25_ultra.webp', 'Xám tro', '12GB', '256GB', 26990000.00, 30),
('BT053', 'SP022', '12GB/512GB/Vàng', 'iphone_17_256gb_5.webp', 'Vàng', '12GB', '512GB', 29990000.00, 14),
('BT054', 'SP022', '8GB/64GB/Xanh', 'iphone_air_3_2.webp', 'Xanh Sky', '8GB', '128GB', 13500000.00, 40),
('BT055', 'SP022', '12GB/256GB/Đen', 'iphone_air_2.webp', 'Đen', '12GB', '256GB', 19990000.00, 100),
('BT056', 'SP03', '6GB/256GB/Đen', 'dien_thoai_samsung_galaxy_s25_ultra_3__6.webp', 'Đen', '6GB', '256GB', 27900000.00, 25),
('BT057', 'SP07', 'VanVinhHP', 'Screenshot_2026_02_04_131237.png', 'Đa sắc', '', '', 26062005.00, 1),
('BT058', 'SP023', 'Tai nghe Bluetooth Apple Airpods', '4_197_1.webp', 'Trắng', '', '', 4350000.00, 3),
('BT059', 'SP06', '8GB/ 256GB/ VNA', 'iphone_17_pro_max_2.webp', 'Bạc ', '8GB', '256GB', 29990000.00, 4),
('BT06', 'SP022', '4/256GB/Trắng', 'iphone_17_256gb_4_1770184930.webp', 'Trắng nt', '4GB', '256GB', 24990000.00, 11),
('BT060', 'SP02', 'Trắng', 'tai_nghe_chup_tai_sony_wh_1000xm5_trang_chinh_hang.jpg', 'Trắng', '', '', 5990000.00, 5),
('BT061', 'SP05', 'Trắng', 'se_2_2023_2.webp', 'Trắng', '', '', 9100000.00, 6),
('BT062', 'SP05', 'Đen', 'apple_watch_se_2023_40mm_1_1_1.png', 'Đen', '', '', 9090000.00, 9),
('BT063', 'SP06', '8GB/256GB/Cam', 'iphone_17_pro_max_3_1770186473.webp', 'Cam vũ trụ', '8GB', '256GB', 32900000.00, 6),
('BT064', 'SP024', '8GB/512GB/Titan', 'iphone_17_pro_max_2_1.webp', 'Titan', '8GB', '512GB', 35900000.00, 4),
('BT07', 'SP16', '64GB RAM 512GB ROM', 'zenbook.jpg', 'Trắng', '64', '512', 30000000.00, 10),
('BT36', 'SP012', 'Hợp kim nhôm/ Đen', 'text_ng_n_32__7_31_3_1.webp', 'Đen', '', '', 690000.00, 11),
('BT37', 'SP012', 'Hợp kim nhôm/Hồng', 'text_ng_n_28__8_31_4.webp', 'Hồng', '', '', 649000.00, 9),
('BT38', 'SP012', 'Polyme/Hồng', 'text_ng_n_28__8_31_4_1.webp', 'Hồng', '', '', 649000.00, 8),
('BT40', 'SP013', 'AirPods Pro 2 2023', 'airpods_pro_2_sep24_pdp_image_position_2__vn_vi_1770184870.webp', 'AirPods Pro 2 2023', '', '', 4950000.00, 14),
('BT41', 'SP013', 'AirPods Pro 2022/ Lighting', '4_197.webp', 'AirPods Pro 2022', '', '', 5690000.00, 15),
('BT42', 'SP014', 'Free chip 2', 'freeclip_2.webp', 'Đen', '', '', 3790000.00, 12),
('BT43', 'SP014', 'Free chip 2', 'huawei_freeclip_2_12_.webp', 'Xanh dương', '', '', 3790000.00, 8),
('BT44', 'SP014', 'Free chip 2', '_.webp', 'Trắng', '', '', 3790000.00, 7),
('BT45', 'SP015', 'Trắng', 'frame_2_54_.webp', 'Trắng', '', '', 1250000.00, 25),
('BT46', 'SP015', 'Đen', 'frame_2_53_.webp', 'Đen', '', '', 1250000.00, 30),
('BT47', 'SP016', '12GB/ 256GB/ Đen', 'samsung_galaxy_z_fold_7_1_1770022664.webp', 'Đen', '12GB', '256GB', 39500000.00, 4),
('BT48', 'SP016', '12GB/ 256GB/ Xám bóng', 'samsung_galaxy_z_fold_7_2_1770022677.webp', 'Xám bóng', '12GB', '512GB', 45990000.00, 2),
('BT49', 'SP021', '12GB/ 256GB/ Đen', 'dien_thoai_samsung_galaxy_s25_ultra_3__6_1770186090.webp', 'Đen', '12GB', '256GB', 39500000.00, 5);

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
('CT01', 'DH01', 'BT04', 1, 29990000.00),
('CT02', 'DH02', 'BT06', 3, 24990000.00),
('CT03', 'DH03', 'BT02', 2, 18000000.00);

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
('GH01', 'BT01', 1),
('GH02', 'BT04', 4),
('GH03', 'BT03', 1),
('GH1770127157', 'BT022', 1),
('GH1770127157', 'BT023', 1),
('GH1770127157', 'BT024', 1);

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
  `phan_hoi` text NOT NULL,
  `ngay_danh_gia` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_gia`
--

INSERT INTO `danh_gia` (`ma_danh_gia`, `ma_user`, `ma_san_pham`, `so_sao`, `noi_dung`, `phan_hoi`, `ngay_danh_gia`) VALUES
('DG01', 'U06', 'SP01', 5, 'Sản phẩm rất tốt', '1233', '2026-01-30 21:31:40'),
('DG02', 'U07', 'SP02', 4, 'Âm thanh hay', '', '2026-01-30 21:31:40'),
('DG03', 'U08', 'SP03', 5, 'Sạc nhanh, pin trâu', '', '2026-01-30 21:31:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` varchar(20) NOT NULL,
  `ten_danh_muc` varchar(255) NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `ngay_tao`) VALUES
('DM01', 'Điện thoại', '2026-01-30 03:27:04'),
('DM02', 'Máy tính bảng', '2026-01-30 03:27:04'),
('DM03', 'Phụ kiện 123', '2026-01-30 03:27:04'),
('DM06', 'Macbook', '2026-01-30 07:00:14'),
('DM07', 'Laptop Gaming', '2026-01-30 07:31:40'),
('DM08', 'Tai nghe', '2026-01-30 07:31:40'),
('DM09', 'Sạc dự phòng', '2026-01-30 07:31:40'),
('DM9', 'Iphone', '2026-01-31 10:38:51');

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
('DC01', 'U06', 'Nguyễn Văn Long', '0901111111', 'Hà Nội', 1),
('DC02', 'U07', 'Trần Văn Minh', '0902222222', 'TP HCM', 1),
('DC03', 'U08', 'Lê Văn Hùng', '0903333333', 'Đà Nẵng', 1);

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
  `thanh_toan` decimal(15,2) DEFAULT 0.00,
  `trang_thai_don_hang` enum('cho_duyet','dang_giao','hoan_thanh','da_huy') DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`ma_don_hang`, `ma_user`, `ma_dia_chi`, `ma_khuyen_mai`, `tong_tien_hang`, `thanh_toan`, `trang_thai_don_hang`, `ngay_tao`) VALUES
('DH01', 'U07', 'DC02', 'KM06', 29990000.00, 29490000.00, 'cho_duyet', '2026-02-05 00:19:20'),
('DH02', 'U07', 'DC02', 'KM06', 74970000.00, 74470000.00, 'cho_duyet', '2026-02-05 00:19:32'),
('DH03', 'U07', 'DC02', 'KM06', 36000000.00, 0.00, 'cho_duyet', '2026-02-05 00:19:43');

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
('GH01', 'U06', 'active', '2026-01-30 21:31:40'),
('GH02', 'U07', 'ordered', '2026-01-30 21:31:40'),
('GH03', 'U08', 'ordered', '2026-01-30 21:31:40'),
('GH1770127157', 'U02', 'active', '2026-02-03 20:59:17');

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
('KM01', 'Chào Hè 2024', 500000.00, '2026-01-17 00:00:00', '2024-08-31 00:00:00', 'het'),
('KM02', 'Black Friday', 1000000.00, '2024-11-20 00:00:00', '2024-11-30 00:00:00', 'het'),
('KM03', 'Khách hàng mới', 200000.00, '2024-01-01 00:00:00', '2026-01-22 23:00:00', 'het'),
('KM04', 'Giảm giá Tết', 300000.00, '2026-01-25 00:00:00', '2026-01-31 00:00:00', 'con'),
('KM05', 'Sale cuối tuần', 200000.00, '2026-01-30 00:00:00', '2026-01-31 00:00:00', 'con'),
('KM06', 'Flash Sale', 500000.00, '2026-01-30 00:00:00', '2026-06-30 00:00:00', 'con'),
('KM07', 'tết', 123.00, '1970-01-01 08:00:00', '2026-06-30 09:09:00', 'con');

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
('NCC01', 'FPT Trading', 'Hà Nội', '0909000009'),
('NCC02', 'Thế Giới Số', 'TP HCM', '0909000002'),
('NCC03', 'CellphoneS', 'Đà Nẵng', '0909000003'),
('NCC04', 'FPT Trading', 'Hà nội', '0987654345'),
('NCC05', 'DigiWorld', 'Hà nội', '0913818387'),
('NCC06', 'Clickbuy', 'HCM', '0192823874');

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

INSERT INTO `san_pham` (`ma_san_pham`, `ten_san_pham`, `ma_danh_muc`, `ma_thuong_hieu`, `ma_nha_cung_cap`, `ngay_tao`) VALUES
('SP01', 'Asus TUF Gaming', 'DM07', 'TH06', 'NCC01', '2026-01-30 21:31:40'),
('SP010', 'Laptop Dell Inspiron 14', 'DM02', 'TH07', 'NCC02', '2026-02-02 15:00:40'),
('SP011', 'Amazfit T-Rex 3 Pro', 'DM03', 'TH10', 'NCC01', '2026-02-02 15:07:02'),
('SP012', 'Vòng đeo tay thông minh Huawei Band 10', 'DM03', 'TH09', 'NCC01', '2026-02-02 15:16:27'),
('SP013', 'Aripod', 'DM9', 'TH01', 'NCC06', '2026-02-02 21:06:01'),
('SP014', 'Tai nghe không dây HUAWEI FreeClip 2', 'DM03', 'TH09', 'NCC05', '2026-02-02 15:32:59'),
('SP015', 'Sạc Anker Zolo 3C1A 140W', 'DM03', 'TH11', 'NCC06', '2026-02-02 15:38:35'),
('SP016', 'Samsung Galaxy Z Fold7', 'DM01', 'TH02', 'NCC05', '2026-02-02 15:54:36'),
('SP017', 'Laptop ASUS TUF Gaming F16', 'DM02', 'TH08', 'NCC03', '2026-02-02 14:39:45'),
('SP018', 'Xiaomi Redmi Note 15 ', 'DM01', 'TH03', 'NCC03', '2026-02-02 14:32:38'),
('SP019', 'Oppo Reno 15F', 'DM01', 'TH04', 'NCC01', '2026-02-02 14:28:59'),
('SP02', 'Tai nghe Sony WH-1000XM5', 'DM08', 'TH07', 'NCC02', '2026-01-30 21:31:40'),
('SP020', 'Macbook Air M2', 'DM02', 'TH01', 'NCC02', '2026-02-02 14:09:27'),
('SP021', 'Samsung s25 Ultra', 'DM01', 'TH02', 'NCC02', '2026-02-02 13:53:56'),
('SP022', 'Iphone Air', 'DM01', 'TH01', 'NCC05', '2026-02-02 13:52:58'),
('SP023', 'Tai nghe Bluetooth Apple AirPods', 'DM03', 'TH01', 'NCC03', '2026-02-02 15:23:26'),
('SP024', 'IPhone 17 promax', 'DM01', 'TH01', 'NCC04', '2026-02-02 13:25:50'),
('SP03', 'Sạc Anker 20000mAh', 'DM09', 'TH08', 'NCC03', '2026-01-30 21:31:40'),
('SP04', 'Macbook m2', 'DM9', 'TH01', 'NCC01', '2026-02-01 01:15:43'),
('SP05', 'Apple Watch SE 2023 GPS 40mm', 'DM9', 'TH01', 'NCC01', '2026-02-01 01:17:41'),
('SP06', 'iPhone 17 Pro Max 256GB VN/A', 'DM01', 'TH01', 'NCC03', '2026-02-01 01:21:33'),
('SP07', 'Ao ma', 'DM07', 'TH01', 'NCC02', '2026-02-01 19:19:15'),
('SP08', 'Laptop Lenovo LOQ', 'DM02', 'TH06', 'NCC03', '2026-02-02 14:48:38'),
('SP09', 'Laptop Acer Gaming Aspire 7', 'DM02', 'TH05', 'NCC04', '2026-02-02 14:54:30'),
('SP16', 'Laptop ASUS Zenbook', 'DM02', 'TH06', 'NCC01', '2026-02-04 13:33:56');

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
('GD01', 'DH01', 'COD', 29490000.00, 'chua_thanh_toan', '2026-02-05 00:19:20'),
('GD02', 'DH02', 'COD', 74470000.00, 'chua_thanh_toan', '2026-02-05 00:19:32'),
('GD03', 'DH03', 'VNPAY', 36000000.00, 'da_thanh_toan', '2026-02-05 00:20:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `ma_thuong_hieu` varchar(20) NOT NULL,
  `ten_thuong_hieu` varchar(255) NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuong_hieu`
--

INSERT INTO `thuong_hieu` (`ma_thuong_hieu`, `ten_thuong_hieu`, `ngay_tao`) VALUES
('TH01', 'Appleee', '2026-01-30 03:29:39'),
('TH02', 'Samsung', '2026-01-30 03:29:39'),
('TH03', 'Xiaomi', '2026-01-30 03:29:39'),
('TH04', 'Oppo', '2026-01-30 03:29:39'),
('TH05', 'Dellll', '2026-01-30 07:15:48'),
('TH06', 'Asus', '2026-01-30 07:31:40'),
('TH07', 'Sony', '2026-01-30 07:31:40'),
('TH08', 'Anker', '2026-01-30 07:31:40'),
('TH09', 'Huawei', '2026-02-01 23:16:45'),
('TH10', 'Amazfit', '2026-02-01 23:17:03'),
('TH11', 'Anker', '2026-02-02 01:38:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `ma_user` varchar(20) NOT NULL,
  `ten_user` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `phan_quyen` enum('admin','khach_hang') NOT NULL DEFAULT 'khach_hang',
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`ma_user`, `ten_user`, `password`, `full_name`, `email`, `phan_quyen`, `so_dien_thoai`, `ngay_tao`) VALUES
('U01', 'vinh', '123', 'Đào Văn Vinh', 'daovinhgm2005@gmail.com', 'admin', '0389783619', '2026-01-30 13:32:56'),
('U02', 'dan', '123', 'Đào Phúc Dân', 'dan@gmail.com', 'khach_hang', '12312312', '2026-01-30 13:32:56'),
('U03', 'thanh', '123', 'Hoàng Văn Thành', 'thanh@gmail.com', 'admin', '0389783619', '2026-01-30 13:34:04'),
('U04', 'qa', '123', 'Đỗ Quanh Anh', 'qa@gmail.com', 'khach_hang', '12312312', '2026-01-30 13:34:04'),
('U05', 'chuong', '123', 'Phạm Văn Chương', 'chuong@gmail.com', 'khach_hang', '0389783611', '2026-01-30 15:32:13'),
('U06', 'long', '123', 'Nguyễn Văn Long', 'long@gmail.com', 'khach_hang', '0901111111', '2026-01-30 21:31:40'),
('U07', 'minh', '1234', 'Trần Văn Minh', 'minh@gmail.com', 'khach_hang', '0902222222', '2026-01-30 21:31:40'),
('U08', 'hung', '123', 'Lê Văn Hùng', 'hung@gmail.com', 'admin', '0903333333', '2026-01-30 21:31:40'),
('U09', 'dan', '1234', 'Đào Phúc Dân', 'dunghiep6b@gmail.com', 'admin', '0862757951', '2026-02-03 08:20:14');

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
  ADD KEY `fk_ctdh_bt` (`ma_bien_the`),
  ADD KEY `fk_ctdh_dh` (`ma_don_hang`);

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
  ADD KEY `fk_dg_sp` (`ma_san_pham`),
  ADD KEY `fk_dg_user` (`ma_user`);

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
  ADD KEY `fk_dh_diachi` (`ma_dia_chi`),
  ADD KEY `fk_dh_km` (`ma_khuyen_mai`),
  ADD KEY `fk_dh_user` (`ma_user`);

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
