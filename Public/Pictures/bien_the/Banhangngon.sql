-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th4 22, 2026 lúc 07:11 AM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `Banhang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_the`
--

CREATE TABLE `bien_the` (
  `ma_bien_the` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `ten_bien_the` varchar(55) DEFAULT NULL,
  `img_bien_the` text DEFAULT NULL,
  `mau_sac` varchar(255) DEFAULT NULL,
  `ram` varchar(255) DEFAULT NULL,
  `dung_luong` varchar(255) DEFAULT NULL,
  `gia` decimal(15,2) DEFAULT NULL,
  `so_luong_kho` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_the`
--

INSERT INTO `bien_the` (`ma_bien_the`, `ma_san_pham`, `ten_bien_the`, `img_bien_the`, `mau_sac`, `ram`, `dung_luong`, `gia`, `so_luong_kho`) VALUES
('BT008', 'SP04', '16GB/256GB/Xám', 'macbook_air_m2_1_1_1_8.webp', 'Xám tro', '16GB', '256GB', '24900000.00', 9),
('BT009', 'SP04', '16GB/256GB/Vàng', 'vn0d33_1_1770016506.webp', 'Vàng sa mạc', '16GB', '256GB', '251990000.00', 7),
('BT01', 'SP01', '16GB RAM 512GB ROM', 'tuf_den.jpg', 'Đen', '16GB', '512GB', '25000000.00', 10),
('BT010', 'SP04', '16GB/256GB/Bạc', 'macbook_air_m2_2_1_1_7.webp', 'Bạc Titan', '16GB', '256GB', '2490000.00', 8),
('BT011', 'SP04', '16GB/256GB/Xám', 'macbook_air_m2_4_1_1_6.webp', 'Xám ', '16GB', '256GB', '25490000.00', 8),
('BT012', 'SP019', '8GB/256GB/Xanh', 'oppo_reno_15f_1.webp', 'Xanh Dương', '8GB', '256GB', '11670000.00', 11),
('BT013', 'SP019', '8GB/256GB/Xanh', 'oppo_reno15f_5g.webp', 'Xanh nhạt', '8GB', '256GB', '11567000.00', 9),
('BT014', 'SP019', '8GB/256GB/Hồng', 'oppo_reno_15f_2.webp', 'Hồng cánh sen', '8GB', '256GB', '11490000.00', 9),
('BT015', 'SP018', '6GB/256GB/Đen', 'redmi_note_15_series_2_3.webp', 'Đen', '6GB', '256GB', '4930000.00', 48),
('BT016', 'SP018', '6GB/128GB/Tím', 'redmi_note_15_series_1_3_1770018150.webp', 'Tím', '6GB', '128GB', '4990000.00', 9),
('BT017', 'SP018', '6GB/128GB/Xanh', 'redmi_note_15_series_3.webp', 'Xanh da trời', '6GB', '128GB', '4990000.00', 9),
('BT018', 'SP017', 'VGA 6GB RTX3050 - CORE 5-210H - 8GB/512GB/Đen', 'text_ng_n_7__4_146_1770018135.webp', 'Đen', '8GB', '512GB', '20900000.00', 10),
('BT019', 'SP017', 'VGA 4GB RTX2050 - R7-7435HS - 16GB/512GB/Đen', 'text_ng_n_7__4_146_2.webp', 'Đen', '16GB', '512GB', '21990000.00', 4),
('BT02', 'SP020', 'Bản quốc tế', 'mac_book_1769963800.jpg', 'Đen', '16GB', '512GB', '18000000.00', 17),
('BT020', 'SP017', 'VGA 8GB RTX5050 - i5-13450HX - 32GB/1TB/Đen', 'text_ng_n_7__4_146_3.webp', 'Đen', '32GB', '1TB', '24900000.00', 3),
('BT021', 'SP017', 'VGA 8GB RTX5070 -  i7-14650HX - 16GB/1TB/Đen', 'text_ng_n_7__4_146_4.webp', 'Đen', '16GB', '1TB', '41990000.00', 1),
('BT022', 'SP08', 'VGA 6GB RTX3050 -  R7-7735HS - 16GB/512GB/Xám', 'text_ng_n_6__4_24_1770019902.webp', 'Xám', '16GB', '512GB', '20990000.00', 4),
('BT023', 'SP08', 'VGA 6GB RTX4050 - R7-7735HS - 16GB/512GB/Xám', 'text_ng_n_6__4_24.webp', 'Xám', '16GB', '512GB', '24990000.00', 3),
('BT024', 'SP08', 'VGA 8GB RTX5050 - i7-13650HX - 16GB/512GB/Xám', 'text_ng_n_6__4_24_1.webp', 'Xám', '16GB', '512GB', '33990000.00', 2),
('BT025', 'SP09', 'Intel UHD Graphics - I5-12450H/16GB/512GB/Đen', 'text_ng_n_13__8_27.webp', 'Đen', '16GB', '512GB', '19990000.00', 1),
('BT026', 'SP09', 'VGA 4GB RTX2050 - I5-12450H/16GB/512GB/Đen', 'text_ng_n_13__8_27_1.webp', 'Đen', '16GB', '512GB', '21990000.00', 5),
('BT027', 'SP09', 'VGA 6GB RTX3050 - i7-12650H/16GB/512GB/Đen', 'text_ng_n_13__8_27_2.webp', 'Đen', '16GB', '512GB', '24090000.00', 6),
('BT028', 'SP010', ' VGA 2GB MX570A - CORE 7-150U/16GB/512GB/Đen', 'text_ng_n_3__7_221_1770019887.webp', 'Đen', '16GB', '512GB', '16490000.00', 5),
('BT029', 'SP010', 'I5-1334U/24GB/512GB/Đen', 'text_ng_n_3__7_221_1.webp', 'Đen', '24GB', '512GB', '17990000.00', 5),
('BT03', 'SP03', 'Sạc nhanh', 'frame_2_54__1770184775.webp', 'Trắng', '', '20000mAh', '1200000.00', 30),
('BT030', 'SP010', 'Snapdragon X PLUS X1P - 16GB/1TB/Đen', 'text_ng_n_3__7_221_1770019929.webp', 'Đen', '16GB', '1TB', '19990000.00', 5),
('BT031', 'SP011', '44mm/Đen', 'dh_2__5.webp', 'Đen', '', '', '8500000.00', 0),
('BT032', 'SP011', '48mm/Đen', 'dh_2__5_1.webp', 'Đen', '', '', '7990000.00', 5),
('BT033', 'SP011', '44mm/Vàng', 't_rex_3_pro_vang.webp', 'Vàng', '', '', '8490000.00', 10),
('BT034', 'SP011', '48mm/Vàng', 't_rex_3_pro_vang_1.webp', 'Vàng', '', '', '7990000.00', 0),
('BT035', 'SP012', 'Polyme/ Đen', 'text_ng_n_32__7_31_3.webp', 'Đen', '', '', '690000.00', 10),
('BT039', 'SP013', 'AirPods Pro 3 2025', 'airpods_pro_3_sep25_pdp_image_position_2__vn_vi_1.webp', 'AirPods Pro 3 2025', '', '', '6590000.00', 12),
('BT04', 'SP024', '8GB/256GB/Cam', 'iphone_17_pro_max_3_1770184820.webp', 'Cam vũ trụ', '8GB', '256GB', '29990000.00', 11),
('BT051', 'SP024', '8GB/512GB/Đen', 'iphone_17_pro_max_1.webp', 'Đen', '8GB', '512GB', '35990000.00', 19),
('BT052', 'SP021', '12GB/256GB/Xám', 'dien_thoai_samsung_galaxy_s25_ultra.webp', 'Xám tro', '12GB', '256GB', '26990000.00', 28),
('BT053', 'SP022', '12GB/512GB/Vàng', 'iphone_17_256gb_5.webp', 'Vàng', '12GB', '512GB', '29990000.00', 7),
('BT054', 'SP022', '8GB/64GB/Xanh', 'iphone_air_3_2.webp', 'Xanh Sky', '8GB', '128GB', '13500000.00', 38),
('BT055', 'SP022', '12GB/256GB/Đen', 'iphone_air_2.webp', 'Đen', '12GB', '256GB', '19990000.00', 94),
('BT056', 'SP03', '6GB/256GB/Đen', 'dien_thoai_samsung_galaxy_s25_ultra_3__6.webp', 'Đen', '6GB', '256GB', '27900000.00', 25),
('BT057', 'SP07', 'VanVinhHP', 'Screenshot_2026_02_04_131237.png', 'Đa sắc', '', '', '26062005.00', 1),
('BT058', 'SP023', 'Tai nghe Bluetooth Apple Airpods', '4_197_1.webp', 'Trắng', '', '', '4350000.00', 1),
('BT059', 'SP06', '8GB/ 256GB/ VNA', 'iphone_17_pro_max_2.webp', 'Bạc ', '8GB', '256GB', '29990000.00', 4),
('BT06', 'SP022', '4/256GB/Trắng', 'iphone_17_256gb_4_1770184930.webp', 'Trắng nt', '4GB', '256GB', '24990000.00', 14),
('BT060', 'SP02', 'Trắng', 'tai_nghe_chup_tai_sony_wh_1000xm5_trang_chinh_hang.jpg', 'Trắng', '', '', '5990000.00', 5),
('BT061', 'SP05', 'Trắng', 'se_2_2023_2.webp', 'Trắng', '', '', '9100000.00', 6),
('BT062', 'SP05', 'Đen', 'apple_watch_se_2023_40mm_1_1_1.png', 'Đen', '', '', '9090000.00', 9),
('BT063', 'SP06', '8GB/256GB/Cam', 'iphone_17_pro_max_3_1770186473.webp', 'Cam vũ trụ', '8GB', '256GB', '32900000.00', 6),
('BT064', 'SP024', '8GB/512GB/Titan', 'iphone_17_pro_max_2_1.webp', 'Titan', '8GB', '512GB', '35900000.00', 6),
('BT065', 'SP024', '12GB/512GB/Titan', 'iphone_17_pro_max_2_1.webp', 'Titan', '12GB', '512GB', '37900000.00', 20),
('BT07', 'SP16', '64GB RAM 512GB ROM', 'zenbook.jpg', 'Trắng', '64', '512', '30000000.00', 5),
('BT100', 'SP100', '8GB/256GB/Đen', 'redmi_note_black.webp', 'Đen', '8GB', '256GB', '5200000.00', 12),
('BT101', 'SP101', '8GB/512GB/Xanh', 'redmi_note_blue.webp', 'Xanh', '8GB', '512GB', '6200000.00', 10),
('BT102', 'SP102', '12GB/256GB/Đen', 'oppo_black.webp', 'Đen', '12GB', '256GB', '12900000.00', 6),
('BT103', 'SP103', '12GB/512GB/Xanh', 'oppo_blue.webp', 'Xanh', '12GB', '512GB', '13900000.00', 5),
('BT104', 'SP104', '16GB/512GB/Đen', 's25_black.webp', 'Đen', '16GB', '512GB', '29900000.00', 7),
('BT105', 'SP021', '16GB/1TB/Xám', 's25_gray.webp', 'Xám', '16GB', '1TB', '32900000.00', 4),
('BT106', 'SP022', '8GB/256GB/Hồng', 'iphone_pink.webp', 'Hồng', '8GB', '256GB', '18900000.00', 11),
('BT107', 'SP022', '12GB/512GB/Xanh', 'iphone_blue.webp', 'Xanh', '12GB', '512GB', '23900000.00', 6),
('BT108', 'SP024', '12GB/1TB/Đen', 'iphone_pro_black.webp', 'Đen', '12GB', '1TB', '39900000.00', 3),
('BT109', 'SP109', '12GB/512GB/Vàng', 'iphone_pro_gold.webp', 'Vàng', '12GB', '512GB', '36900000.00', 5),
('BT110', 'SP017', '16GB/1TB/Đen', 'tuf_black.webp', 'Đen', '16GB', '1TB', '27900000.00', 4),
('BT111', 'SP111', '32GB/1TB/Xám', 'tuf_gray.webp', 'Xám', '32GB', '1TB', '32900000.00', 2),
('BT112', 'SP112', '16GB/1TB/Đen', 'lenovo_black.webp', 'Đen', '16GB', '1TB', '25900000.00', 5),
('BT113', 'SP113', '32GB/1TB/Xám', 'lenovo_gray.webp', 'Xám', '32GB', '1TB', '30900000.00', 3),
('BT114', 'SP114', '16GB/1TB/Đen', 'acer_black.webp', 'Đen', '16GB', '1TB', '22900000.00', 6),
('BT115', 'SP115', '32GB/1TB/Xám', 'acer_gray.webp', 'Xám', '32GB', '1TB', '26900000.00', 2),
('BT116', 'SP116', '16GB/512GB/Bạc', 'mac_silver.webp', 'Bạc', '16GB', '512GB', '21900000.00', 7),
('BT117', 'SP117', '24GB/1TB/Xám', 'mac_gray.webp', 'Xám', '24GB', '1TB', '28900000.00', 3),
('BT118', 'SP118', '32GB/1TB/Đen', 'asus_tuf_black.webp', 'Đen', '32GB', '1TB', '30900000.00', 5),
('BT119', 'SP119', '16GB/512GB/Xám', 'asus_tuf_gray.webp', 'Xám', '16GB', '512GB', '26900000.00', 6),
('BT120', 'SP120', 'Đen', 'sony_black.webp', 'Đen', '', '', '6990000.00', 9),
('BT121', 'SP121', 'Xanh', 'sony_blue.webp', 'Xanh', '', '', '6990000.00', 8),
('BT122', 'SP122', 'AirPods Gen 2', 'airpods_gen2.webp', 'Trắng', '', '', '2990000.00', 10),
('BT123', 'SP123', 'AirPods Gen 3', 'airpods_gen3.webp', 'Trắng', '', '', '3990000.00', 7),
('BT124', 'SP124', 'FreeClip 2 Đen', 'freeclip_black.webp', 'Đen', '', '', '3790000.00', 6),
('BT125', 'SP125', 'FreeClip 2 Trắng', 'freeclip_white.webp', 'Trắng', '', '', '3790000.00', 5),
('BT126', 'SP126', '140W Đen', 'anker_black.webp', 'Đen', '', '', '1290000.00', 20),
('BT127', 'SP127', '140W Trắng', 'anker_white.webp', 'Trắng', '', '', '1290000.00', 18),
('BT128', 'SP128', '30000mAh/Đen', 'anker_power_black.webp', 'Đen', '', '30000mAh', '1500000.00', 15),
('BT129', 'SP129', '30000mAh/Trắng', 'anker_power_white.webp', 'Trắng', '', '30000mAh', '1500000.00', 14),
('BT130', 'SP130', '50mm/Đen', 'amazfit_black.webp', 'Đen', '', '', '9500000.00', 6),
('BT131', 'SP131', '50mm/Xanh', 'amazfit_blue.webp', 'Xanh', '', '', '9500000.00', 5),
('BT132', 'SP132', 'Nhựa/Đen', 'band_black.webp', 'Đen', '', '', '690000.00', 20),
('BT133', 'SP133', 'Nhựa/Hồng', 'band_pink.webp', 'Hồng', '', '', '690000.00', 18),
('BT134', 'SP134', '12GB/512GB/Đen', 'fold_black.webp', 'Đen', '12GB', '512GB', '45900000.00', 3),
('BT135', 'SP135', '12GB/1TB/Xám', 'fold_gray.webp', 'Xám', '12GB', '1TB', '49900000.00', 2),
('BT136', 'SP136', 'Đen', 'sony_xm3_black.webp', 'Đen', '', '', '4990000.00', 6),
('BT137', 'SP137', 'Bạc', 'sony_xm3_silver.webp', 'Bạc', '', '', '4990000.00', 5),
('BT138', 'SP138', '16GB/512GB/Xám', 'macneo_gray.webp', 'Xám', '16GB', '512GB', '19900000.00', 7),
('BT139', 'SP139', '32GB/1TB/Đen', 'macneo_black.webp', 'Đen', '32GB', '1TB', '25900000.00', 4),
('BT140', 'SP140', 'Bản thường', 'default.webp', 'Đen', '', '', '1000000.00', 10),
('BT141', 'SP141', 'Bản cao cấp', 'default_pro.webp', 'Đen', '', '', '2000000.00', 5),
('BT142', 'SP142', '8GB/128GB/Đen', 'iphone16_black.jpg', 'Đen', '8GB', '128GB', '20000000.00', 10),
('BT143', 'SP149', '12GB/256GB/Đen', 'iphone16pro_black.jpg', 'Đen', '12GB', '256GB', '30000000.00', 8),
('BT144', 'SP147', '8GB/256GB/Xanh', 'samsung_a75_blue.jpg', 'Xanh', '8GB', '256GB', '9000000.00', 12),
('BT145', 'SP146', '12GB/256GB/Đen', 'xiaomi14_black.jpg', 'Đen', '12GB', '256GB', '22000000.00', 6),
('BT146', 'SP145', '12GB/256GB/Xám', 'oppo_findx8_gray.jpg', 'Xám', '12GB', '256GB', '21000000.00', 7),
('BT147', 'SP144', '12GB/256GB/Xanh', 'vivo_x200_blue.jpg', 'Xanh', '12GB', '256GB', '19000000.00', 9),
('BT148', 'SP144', '8GB/256GB/Đen', 'realme_gt6_black.jpg', 'Đen', '8GB', '256GB', '11000000.00', 15),
('BT149', 'SP143', '12GB/256GB/Đen', 'pixel9_black.jpg', 'Đen', '12GB', '256GB', '23000000.00', 5),
('BT36', 'SP012', 'Hợp kim nhôm/ Đen', 'text_ng_n_32__7_31_3_1.webp', 'Đen', '', '', '690000.00', 11),
('BT37', 'SP012', 'Hợp kim nhôm/Hồng', 'text_ng_n_28__8_31_4.webp', 'Hồng', '', '', '649000.00', 9),
('BT38', 'SP012', 'Polyme/Hồng', 'text_ng_n_28__8_31_4_1.webp', 'Hồng', '', '', '649000.00', 8),
('BT40', 'SP013', 'AirPods Pro 2 2023', 'airpods_pro_2_sep24_pdp_image_position_2__vn_vi_1770184870.webp', 'AirPods Pro 2 2023', '', '', '4950000.00', 14),
('BT41', 'SP013', 'AirPods Pro 2022/ Lighting', '4_197.webp', 'AirPods Pro 2022', '', '', '5690000.00', 15),
('BT42', 'SP014', 'Free chip 2', 'freeclip_2.webp', 'Đen', '', '', '3790000.00', 12),
('BT43', 'SP014', 'Free chip 2', 'huawei_freeclip_2_12_.webp', 'Xanh dương', '', '', '3790000.00', 8),
('BT44', 'SP014', 'Free chip 2', '_.webp', 'Trắng', '', '', '3790000.00', 7),
('BT45', 'SP015', 'Trắng', 'frame_2_54_.webp', 'Trắng', '', '', '1250000.00', 24),
('BT46', 'SP015', 'Đen', 'frame_2_53_.webp', 'Đen', '', '', '1250000.00', 30),
('BT47', 'SP016', '12GB/ 256GB/ Đen', 'samsung_galaxy_z_fold_7_1_1770022664.webp', 'Đen', '12GB', '256GB', '39500000.00', 3),
('BT48', 'SP016', '12GB/ 256GB/ Xám bóng', 'samsung_galaxy_z_fold_7_2_1770022677.webp', 'Xám bóng', '12GB', '512GB', '45990000.00', 2),
('BT49', 'SP021', '12GB/ 256GB/ Đen', 'dien_thoai_samsung_galaxy_s25_ultra_3__6_1770186090.webp', 'Đen', '12GB', '256GB', '39500000.00', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_conversations`
--

CREATE TABLE `chat_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_code` varchar(64) NOT NULL,
  `ma_user` varchar(20) DEFAULT NULL,
  `guest_token` varchar(64) DEFAULT NULL,
  `status` enum('active','closed') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `conversation_code`, `ma_user`, `guest_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'CV6a0d95ea4f1f816a932c', 'U02', 'GST4618034f7e8cfea9b2b5f713', 'active', '2026-04-18 13:42:19', '2026-04-18 14:39:44'),
(2, 'CV926c4da45995cc0cba3a', 'U01', 'GSTcea140fe6b9c52987c88cf89', 'active', '2026-04-19 16:20:46', '2026-04-19 16:20:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `ma_user` varchar(20) DEFAULT NULL,
  `sender` enum('user','bot') NOT NULL,
  `message` text NOT NULL,
  `intent` varchar(64) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `ma_user`, `sender`, `message`, `intent`, `created_at`) VALUES
(1, 1, 'U02', 'user', 'sản phẩm trên 15 triệu', 'product_lookup', '2026-04-18 13:42:19'),
(2, 1, 'U02', 'bot', 'Dạ, TechZone đã tìm thấy sản phẩm phù hợp cho Quý khách:\n- ???? Iphone Air | ???? 19.990.000đ | Danh mục: Điện thoại | Thương hiệu: Appleee | ✅ Kho: 112\n- ???? IPhone 17 promax | ???? 29.990.000đ | Danh mục: Điện thoại | Thương hiệu: Appleee | ✅ Kho: 53\n- ???? Samsung s25 Ultra | ???? 26.990.000đ | Danh mục: Điện thoại | Thương hiệu: Samsung | ✅ Kho: 30\n- ???? Sạc Anker 20000mAh | ???? 27.900.000đ | Danh mục: Sạc dự phòng | Thương hiệu: Anker | ✅ Kho: 25\n- ???? Macbook m2 | ???? 24.900.000đ | Danh mục: Iphone | Thương hiệu: Appleee | ✅ Kho: 23\nQuý khách có muốn em hỗ trợ thêm sản phẩm này vào giỏ hàng không ạ?', 'product_lookup', '2026-04-18 13:42:19'),
(3, 1, 'U02', 'user', 'sản phẩm tôi đã mua', 'customer_my_orders', '2026-04-18 13:42:47'),
(4, 1, 'U02', 'bot', 'Dạ, TechZone đã lấy danh sách đơn hàng của tài khoản hiện tại:\n- ???? Mã tài khoản: U02\n- ???? Trạng thái đang xem: Tất cả trạng thái\n- ✅ Số đơn khớp: 19\n- ???? Đang hiển thị: 19 đơn gần nhất\n- DH27 | Đang giao | 18.000.000đ | 2026-04-17 23:53:19\n- DH26 | Chờ duyệt | 26.990.000đ | 2026-04-02 21:44:15\n- DH25 | Đã hủy | 20.900.000đ | 2026-04-02 21:32:08\n- DH24 | Đã hủy | 29.990.000đ | 2026-04-02 21:28:35\n- DH23 | Đã hủy | 4.930.000đ | 2026-04-02 21:27:57\n- DH22 | Chờ duyệt | 18.000.000đ | 2026-04-02 21:16:05\n- DH21 | Chờ duyệt | 74.980.000đ | 2026-04-02 20:53:07\n- DH20 | Chờ duyệt | 0đ | 2026-04-02 20:47:25\n- DH19 | Chờ duyệt | 0đ | 2026-04-02 20:46:11\n- DH18 | Chờ duyệt | 29.990.000đ | 2026-04-02 20:43:50\n- DH17 | Chờ duyệt | 0đ | 2026-04-02 20:43:27\n- DH16 | Chờ duyệt | 30.000.000đ | 2026-04-02 17:43:26\n- DH15 | Chờ duyệt | 26.990.000đ | 2026-04-02 15:50:21\n- DH14 | Chờ duyệt | 20.900.000đ | 2026-04-02 11:13:27\n- DH13 | Chờ duyệt | 4.930.000đ | 2026-04-02 11:12:30\n- DH12 | Chờ duyệt | 11.670.000đ | 2026-04-02 11:12:14\n- DH11 | Chờ duyệt | 29.990.000đ | 2026-04-02 11:12:00\n- DH10 | Chờ duyệt | 29.990.000đ | 2026-04-02 11:11:38\n- DH09 | Chờ duyệt | 0đ | 2026-04-02 10:31:18\nQuý khách muốn em mở chi tiết đơn nào (ví dụ: DH27) để xem sản phẩm và thanh toán không ạ?', 'customer_my_orders', '2026-04-18 13:42:47'),
(5, 1, 'U02', 'user', 'sản phẩm trên 20 triệu', 'product_lookup', '2026-04-18 13:48:30'),
(6, 1, 'U02', 'bot', 'Dạ, TechZone đã tìm thấy sản phẩm phù hợp cho Quý khách:\n- IPhone 17 promax | 29.990.000đ | Danh mục: Điện thoại | Thương hiệu: Appleee | Kho: 53\n- Samsung s25 Ultra | 26.990.000đ | Danh mục: Điện thoại | Thương hiệu: Samsung | Kho: 30\n- Sạc Anker 20000mAh | 27.900.000đ | Danh mục: Sạc dự phòng | Thương hiệu: Anker | Kho: 25\n- Macbook m2 | 24.900.000đ | Danh mục: Iphone | Thương hiệu: Appleee | Kho: 23\n- Iphone Air | 24.990.000đ | Danh mục: Điện thoại | Thương hiệu: Appleee | Kho: 18\nQuý khách có muốn em hỗ trợ thêm sản phẩm này vào giỏ hàng không ạ?', 'product_lookup', '2026-04-18 13:48:30'),
(7, 1, 'U02', 'user', 'những đơn Tôi đã hủy', 'customer_my_orders', '2026-04-18 14:39:05'),
(8, 1, 'U02', 'bot', 'Dạ, TechZone đã lấy danh sách đơn hàng của tài khoản hiện tại:\n- Mã tài khoản: U02\n- Trạng thái đang xem: Đã hủy\n- Số đơn khớp: 3\n- Đang hiển thị: 3 đơn gần nhất\n- DH25 | Đã hủy | 20.900.000đ | 2026-04-02 21:32:08\n- DH24 | Đã hủy | 29.990.000đ | 2026-04-02 21:28:35\n- DH23 | Đã hủy | 4.930.000đ | 2026-04-02 21:27:57\nQuý khách muốn em mở chi tiết đơn nào (ví dụ: DH27) để xem sản phẩm và thanh toán không ạ?', 'customer_my_orders', '2026-04-18 14:39:05'),
(9, 1, 'U02', 'user', 'những đơn năng chuyển', 'customer_my_orders', '2026-04-18 14:39:17'),
(10, 1, 'U02', 'bot', 'Dạ, TechZone đã lấy danh sách đơn hàng của tài khoản hiện tại:\n- Mã tài khoản: U02\n- Trạng thái đang xem: Đã hủy\n- Số đơn khớp: 3\n- Đang hiển thị: 3 đơn gần nhất\n- DH25 | Đã hủy | 20.900.000đ | 2026-04-02 21:32:08\n- DH24 | Đã hủy | 29.990.000đ | 2026-04-02 21:28:35\n- DH23 | Đã hủy | 4.930.000đ | 2026-04-02 21:27:57\nQuý khách muốn em mở chi tiết đơn nào (ví dụ: DH27) để xem sản phẩm và thanh toán không ạ?', 'customer_my_orders', '2026-04-18 14:39:17'),
(11, 1, 'U02', 'user', 'những đơn đã hoàn thành', 'customer_my_orders', '2026-04-18 14:39:27'),
(12, 1, 'U02', 'bot', 'Dạ, hiện tài khoản của Quý khách chưa có đơn hàng nào.\n- Quý khách có thể tham khảo sản phẩm và đặt mua ngay trên TechZone.\nEm có thể gợi ý một số sản phẩm phù hợp để mình bắt đầu không ạ?', 'customer_my_orders', '2026-04-18 14:39:27'),
(13, 1, 'U02', 'user', 'những đơn hàng đang chờ duyệt', 'customer_my_orders', '2026-04-18 14:39:44'),
(14, 1, 'U02', 'bot', 'Dạ, TechZone đã lấy danh sách đơn hàng của tài khoản hiện tại:\n- Mã tài khoản: U02\n- Trạng thái đang xem: Chờ duyệt\n- Số đơn khớp: 15\n- Đang hiển thị: 15 đơn gần nhất\n- DH26 | Chờ duyệt | 26.990.000đ | 2026-04-02 21:44:15\n- DH22 | Chờ duyệt | 18.000.000đ | 2026-04-02 21:16:05\n- DH21 | Chờ duyệt | 74.980.000đ | 2026-04-02 20:53:07\n- DH20 | Chờ duyệt | 0đ | 2026-04-02 20:47:25\n- DH19 | Chờ duyệt | 0đ | 2026-04-02 20:46:11\n- DH18 | Chờ duyệt | 29.990.000đ | 2026-04-02 20:43:50\n- DH17 | Chờ duyệt | 0đ | 2026-04-02 20:43:27\n- DH16 | Chờ duyệt | 30.000.000đ | 2026-04-02 17:43:26\n- DH15 | Chờ duyệt | 26.990.000đ | 2026-04-02 15:50:21\n- DH14 | Chờ duyệt | 20.900.000đ | 2026-04-02 11:13:27\n- DH13 | Chờ duyệt | 4.930.000đ | 2026-04-02 11:12:30\n- DH12 | Chờ duyệt | 11.670.000đ | 2026-04-02 11:12:14\n- DH11 | Chờ duyệt | 29.990.000đ | 2026-04-02 11:12:00\n- DH10 | Chờ duyệt | 29.990.000đ | 2026-04-02 11:11:38\n- DH09 | Chờ duyệt | 0đ | 2026-04-02 10:31:18\nQuý khách muốn em mở chi tiết đơn nào (ví dụ: DH27) để xem sản phẩm và thanh toán không ạ?', 'customer_my_orders', '2026-04-18 14:39:44'),
(15, 2, 'U01', 'user', 'xin chào', 'greeting', '2026-04-19 16:20:46'),
(16, 2, 'U01', 'bot', 'Chào TechZone,\n\n- Chúng tôi xin chào bạn một cách thân thiện và chào đón bạn đến với TechZone.\n- Chúng tôi có 6 sản phẩm điện thoại và phụ kiện đang được bán trên hệ thống, bao gồm Iphone Air, Xiaomi Redmi Note 15, IPhone 17 promax, Sạc Anker 20000mAh, Sạc Anker Zolo 3C1A 140W và Aripod.\n- Chúng tôi có 3 sản phẩm điện thoại của thương hiệu Appleee, 2 sản phẩm điện thoại của thương hiệu Xiaomi và 1 sản phẩm điện thoại của thương hiệu Samsung.\n- Chúng tôi có 2 sản phẩm sạc của thương hiệu Anker và 1 sản phẩm vòng đeo tay thông minh của thương hiệu Huawei.\n- Chúng tôi có tổng cộng 6 sản phẩm đang được bán trên hệ thống với giá từ 4.930.000 đồng đến 29.990.000 đồng.\n\nLời mời bước tiếp theo: Bạn có muốn xem chi tiết thông tin về từng sản phẩm không?', 'greeting', '2026-04-19 16:20:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_ctdh` varchar(20) NOT NULL,
  `ma_don_hang` varchar(20) NOT NULL,
  `ma_bien_the` varchar(20) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `gia_luc_mua` decimal(15,2) NOT NULL,
  `gia_ban_luc_mua` decimal(38,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ma_ctdh`, `ma_don_hang`, `ma_bien_the`, `so_luong`, `gia_luc_mua`, `gia_ban_luc_mua`) VALUES
('CT01', 'DH01', 'BT04', 2, '29990000.00', NULL),
('CT02', 'DH02', 'BT053', 1, '29990000.00', NULL),
('CT03', 'DH03', 'BT053', 1, '29990000.00', NULL),
('CT04', 'DH04', 'BT45', 1, '1250000.00', NULL),
('CT05', 'DH05', 'BT058', 1, '4350000.00', NULL),
('CT06', 'DH06', 'BT37', 1, '649000.00', NULL),
('CT08', 'DH08', 'BT47', 1, '39500000.00', NULL),
('CT09', 'DH09', 'BT051', 0, '35990000.00', NULL),
('CT10', 'DH10', 'BT04', 1, '29990000.00', NULL),
('CT11', 'DH11', 'BT04', 1, '29990000.00', NULL),
('CT12', 'DH12', 'BT012', 1, '11670000.00', NULL),
('CT13', 'DH13', 'BT015', 1, '4930000.00', NULL),
('CT14', 'DH14', 'BT018', 1, '20900000.00', NULL),
('CT15', 'DH15', 'BT052', 1, '26990000.00', NULL),
('CT16', 'DH16', 'BT07', 1, '30000000.00', NULL),
('CT17', 'DH17', 'BT04', 0, '29990000.00', NULL),
('CT18', 'DH18', 'BT053', 1, '29990000.00', NULL),
('CT19', 'DH19', 'BT02', 0, '18000000.00', NULL),
('CT20', 'DH20', 'BT02', 0, '18000000.00', NULL),
('CT21', 'DH20', 'BT04', 0, '29990000.00', NULL),
('CT22', 'DH21', 'BT02', 1, '18000000.00', NULL),
('CT23', 'DH21', 'BT04', 1, '29990000.00', NULL),
('CT24', 'DH21', 'BT052', 1, '26990000.00', NULL),
('CT25', 'DH22', 'BT02', 1, '18000000.00', NULL),
('CT26', 'DH23', 'BT015', 1, '4930000.00', NULL),
('CT27', 'DH24', 'BT04', 1, '29990000.00', NULL),
('CT28', 'DH25', 'BT018', 1, '20900000.00', NULL),
('CT29', 'DH26', 'BT052', 1, '26990000.00', NULL),
('CT30', 'DH27', 'BT02', 1, '18000000.00', NULL),
('CT31', 'DH28', 'BT02', 1, '18000000.00', NULL),
('CT32', 'DH29', 'BT055', 1, '19990000.00', NULL),
('CT33', 'DH30', 'BT052', 1, '26990000.00', NULL),
('CT34', 'DH31', 'BT02', 1, '18000000.00', NULL),
('CT35', 'DH32', 'BT012', 1, '11670000.00', NULL),
('CT36', 'DH33', 'BT052', 1, '26990000.00', NULL),
('CT37', 'DH34', 'BT017', 1, '4990000.00', NULL),
('CT38', 'DH35', 'BT49', 1, '39500000.00', NULL),
('CT39', 'DH36', 'BT052', 1, '26990000.00', NULL),
('CT40', 'DH37', 'BT058', 1, '4350000.00', NULL),
('CT41', 'DH38', 'BT051', 2, '35990000.00', NULL),
('CT42', 'DH39', 'BT051', 3, '35990000.00', NULL),
('CT43', 'DH40', 'BT04', 1, '29990000.00', NULL),
('CT44', 'DH41', 'BT04', 1, '29990000.00', NULL),
('CT45', 'DH42', 'BT04', 1, '29990000.00', NULL);

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
('GH01', 'BT058', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `ma_danh_gia` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ma_san_pham` varchar(20) NOT NULL,
  `so_sao` int(11) DEFAULT NULL CHECK (`so_sao` >= 1 and `so_sao` <= 5),
  `noi_dung` varchar(255) DEFAULT NULL,
  `phan_hoi` varchar(255) DEFAULT NULL,
  `ngay_danh_gia` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_gia`
--

INSERT INTO `danh_gia` (`ma_danh_gia`, `ma_user`, `ma_san_pham`, `so_sao`, `noi_dung`, `phan_hoi`, `ngay_danh_gia`) VALUES
('DG01', 'U06', 'SP01', 5, 'Sản phẩm rất tốt', '1233', '2026-01-30 21:31:40'),
('DG02', 'U07', 'SP02', 4, 'Âm thanh hay', '', '2026-01-30 21:31:40'),
('DG03', 'U08', 'SP03', 5, 'Sạc nhanh, pin trâu', 'cảm ưn', '2026-01-30 21:31:40');

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
('DM9', 'Iphone', '2026-01-31 10:38:51'),
('DM95', 'VICTUS', '2026-04-01 08:20:08'),
('DM96', 'huwue', '2026-04-01 08:04:05'),
('DM97', 'xiaomi', '2026-04-01 08:00:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_chi_giao_hang`
--

CREATE TABLE `dia_chi_giao_hang` (
  `ma_dia_chi` varchar(20) NOT NULL,
  `ma_user` varchar(20) NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(255) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `mac_dinh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dia_chi_giao_hang`
--

INSERT INTO `dia_chi_giao_hang` (`ma_dia_chi`, `ma_user`, `ho_ten`, `so_dien_thoai`, `dia_chi`, `mac_dinh`) VALUES
('DC01', 'U06', 'Nguyễn Văn Long', '0901111111', 'Hà Nội', 1),
('DC02', 'U07', 'Trần Văn Minh', '1232421411', 'quan 1 HCMinh', 1),
('DC03', 'U08', 'Lê Văn Hùng', '0903333333', 'Đà Nẵng', 1),
('DC04', 'U02', 'Đào Phúc Dân', '12312312', 'mo lao ha dong', 1),
('DC05', 'U02', 'ĐÀO VĂN VINH', '0123242149', 'hải phòng', 0),
('DC06', 'U02', 'ĐÀO VĂN VINH', '0123456789', 'hải phòng', 0),
('DC07', 'U02', 'ĐÀO VĂN VINH', '0123456782', 'hải phòng', 0),
('DC08', 'U02', 'ĐÀO VĂN VINH', '0123456789', 'hải phòng', 0),
('DC09', 'U02', 'ĐÀO VĂN VINH', '0123242141', 'hải phòng', 0),
('DC10', 'U02', 'ĐÀO VĂN VINH', '0123456789', 'hải phòng', 0),
('DC11', 'U02', 'ĐÀO VĂN VINH', '0902222222', 'hải phòng', 0),
('DC12', 'U02', 'ĐÀO VĂN VINH', '0123456782', 'hải phòng', 0),
('DC13', 'U02', 'ĐÀO VĂN VINH', '0902222222', 'hải phòng', 0),
('DC14', 'U02', 'ĐÀO VĂN VINH', '0902222222', 'hải phòng', 0),
('DC15', 'U02', 'ĐÀO VĂN VINH', '0312312312', 'hải phòng', 0),
('DC16', 'U02', 'ĐÀO VĂN VINH', '01232421415', 'hải phòng', 0),
('DC17', 'U02', 'ĐÀO VĂN VINH', '0312312312', 'hải phòng', 0),
('DC18', 'U02', 'ĐÀO VĂN VINH', '0123456782', 'hải phòng', 0),
('DC19', 'U02', 'ĐÀO VĂN VINH', '0312312312', 'hải phòng', 0),
('DC20', 'U02', 'ĐÀO VĂN VINH', '0123456782', 'hải phòng', 0),
('DC21', 'U02', 'ĐÀO VĂN VINH', '0902222222', 'hải phòng', 0),
('DC22', 'U02', 'ĐÀO VĂN dan', '0312312312', 'hải phòng', 0),
('DC23', 'U02', 'ĐÀO VĂN dan', '0123456782', 'hải phòng', 0),
('DC24', 'U02', 'ĐÀO VĂN dan', '0312312312', 'hải phòng', 0),
('DC25', 'U07', 'xuân', '0123454312', 'Hà Nội', 0),
('DC26', 'U07', 'xuân', '0123454312', 'Hà Nội', 0),
('DC27', 'U07', 'ĐÀO VĂN VINH', '1232421415', 'hải phòng', 0),
('DC28', 'U07', 'xuân', '0123454312', 'Hà Nội', 0);

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
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `hinh_thuc_thanh_toan` varchar(255) DEFAULT NULL,
  `tong_tien` decimal(38,2) DEFAULT NULL,
  `trang_thai` enum('cho_duyet','da_huy','dang_giao','hoan_thanh') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`ma_don_hang`, `ma_user`, `ma_dia_chi`, `ma_khuyen_mai`, `tong_tien_hang`, `thanh_toan`, `trang_thai_don_hang`, `ngay_tao`, `hinh_thuc_thanh_toan`, `tong_tien`, `trang_thai`) VALUES
('DH01', 'U07', 'DC02', NULL, '59980000.00', '59980000.00', 'dang_giao', '2026-02-05 07:52:05', NULL, NULL, NULL),
('DH02', 'U07', 'DC02', 'KM06', '29990000.00', '29490000.00', 'da_huy', '2026-02-05 17:15:55', NULL, NULL, NULL),
('DH03', 'U07', 'DC02', 'KM06', '29990000.00', '29490000.00', 'da_huy', '2026-02-05 17:16:01', NULL, NULL, NULL),
('DH04', 'U07', 'DC02', NULL, '1250000.00', '1250000.00', 'da_huy', '2026-02-05 17:16:27', NULL, NULL, NULL),
('DH05', 'U07', 'DC02', NULL, '4350000.00', '4350000.00', 'da_huy', '2026-02-05 19:37:13', NULL, NULL, NULL),
('DH06', 'U07', 'DC02', 'KM06', '649000.00', '149000.00', 'da_huy', '2026-02-06 00:27:37', NULL, NULL, NULL),
('DH08', 'U07', 'DC02', 'KM06', '39500000.00', '39000000.00', 'dang_giao', '2026-02-07 05:11:40', NULL, NULL, NULL),
('DH09', 'U02', 'DC04', NULL, '0.00', '0.00', 'da_huy', '2026-04-02 10:31:18', NULL, NULL, NULL),
('DH10', 'U02', 'DC04', 'KM09', '29990000.00', '28690000.00', 'da_huy', '2026-04-02 11:11:38', NULL, NULL, NULL),
('DH11', 'U02', 'DC04', NULL, '29990000.00', '29990000.00', 'da_huy', '2026-04-02 11:12:00', NULL, NULL, NULL),
('DH12', 'U02', 'DC04', NULL, '11670000.00', '11670000.00', 'da_huy', '2026-04-02 11:12:14', NULL, NULL, NULL),
('DH13', 'U02', 'DC04', NULL, '4930000.00', '4930000.00', 'da_huy', '2026-04-02 11:12:30', NULL, NULL, NULL),
('DH14', 'U02', 'DC04', NULL, '20900000.00', '20900000.00', 'da_huy', '2026-04-02 11:13:27', NULL, NULL, NULL),
('DH15', 'U02', 'DC04', NULL, '26990000.00', '26990000.00', 'da_huy', '2026-04-02 15:50:21', NULL, NULL, NULL),
('DH16', 'U02', 'DC04', 'KM08', '30000000.00', '29700000.00', 'da_huy', '2026-04-02 17:43:26', NULL, NULL, NULL),
('DH17', 'U02', 'DC04', NULL, '0.00', '0.00', 'da_huy', '2026-04-02 20:43:27', NULL, NULL, NULL),
('DH18', 'U02', 'DC04', NULL, '29990000.00', '29990000.00', 'da_huy', '2026-04-02 20:43:50', NULL, NULL, NULL),
('DH19', 'U02', 'DC04', NULL, '0.00', '0.00', 'da_huy', '2026-04-02 20:46:11', NULL, NULL, NULL),
('DH20', 'U02', 'DC04', NULL, '0.00', '0.00', 'da_huy', '2026-04-02 20:47:25', NULL, NULL, NULL),
('DH21', 'U02', 'DC04', NULL, '74980000.00', '74980000.00', 'da_huy', '2026-04-02 20:53:07', NULL, NULL, NULL),
('DH22', 'U02', 'DC05', 'KM08', '18000000.00', '17700000.00', 'da_huy', '2026-04-02 21:16:05', NULL, NULL, NULL),
('DH23', 'U02', 'DC06', 'KM08', '4930000.00', '4630000.00', 'da_huy', '2026-04-02 21:27:57', NULL, NULL, NULL),
('DH24', 'U02', 'DC07', 'KM08', '29990000.00', '29690000.00', 'da_huy', '2026-04-02 21:28:35', NULL, NULL, NULL),
('DH25', 'U02', 'DC08', 'KM09', '20900000.00', '19600000.00', 'da_huy', '2026-04-02 21:32:08', NULL, NULL, NULL),
('DH26', 'U02', 'DC09', 'KM08', '26990000.00', '0.00', 'da_huy', '2026-04-02 21:44:15', NULL, NULL, NULL),
('DH27', 'U02', 'DC11', 'KM07', '18000000.00', '17999877.00', 'da_huy', '2026-04-17 23:53:19', NULL, NULL, NULL),
('DH28', 'U02', 'DC12', NULL, '18000000.00', '18000000.00', 'da_huy', '2026-04-18 16:18:21', NULL, NULL, NULL),
('DH29', 'U02', 'DC13', 'KM06', '19990000.00', '19490000.00', 'da_huy', '2026-04-18 16:21:53', NULL, NULL, NULL),
('DH30', 'U02', 'DC14', 'KM06', '26990000.00', '26490000.00', 'da_huy', '2026-04-18 16:36:20', NULL, NULL, NULL),
('DH31', 'U02', 'DC15', 'KM07', '18000000.00', '17999877.00', 'dang_giao', '2026-04-18 16:36:33', NULL, NULL, NULL),
('DH32', 'U02', 'DC16', 'KM06', '11670000.00', '11170000.00', 'da_huy', '2026-04-18 16:42:35', NULL, NULL, NULL),
('DH33', 'U02', 'DC17', 'KM06', '26990000.00', '26490000.00', 'da_huy', '2026-04-18 16:50:22', NULL, NULL, NULL),
('DH34', 'U02', 'DC18', 'KM06', '4990000.00', '4490000.00', 'da_huy', '2026-04-18 17:04:38', NULL, NULL, NULL),
('DH35', 'U02', 'DC19', 'KM06', '39500000.00', '39000000.00', 'da_huy', '2026-04-18 17:38:55', NULL, NULL, NULL),
('DH36', 'U02', 'DC20', 'KM06', '26990000.00', '26490000.00', 'da_huy', '2026-04-18 17:55:22', NULL, NULL, NULL),
('DH37', 'U02', 'DC21', 'KM07', '4350000.00', '4349877.00', 'da_huy', '2026-04-18 17:57:52', NULL, NULL, NULL),
('DH38', 'U02', 'DC22', 'KM06', '71980000.00', '71480000.00', 'da_huy', '2026-04-18 18:02:30', NULL, NULL, NULL),
('DH39', 'U02', 'DC23', 'KM07', '107970000.00', '107969877.00', 'da_huy', '2026-04-18 18:06:13', NULL, NULL, NULL),
('DH40', 'U07', 'DC26', NULL, '29990000.00', '29990000.00', 'da_huy', '2026-04-19 00:58:44', NULL, NULL, NULL),
('DH41', 'U07', 'DC27', NULL, '29990000.00', '29990000.00', 'da_huy', '2026-04-19 01:25:52', NULL, NULL, NULL),
('DH42', 'U07', 'DC28', NULL, '29990000.00', '29990000.00', 'da_huy', '2026-04-19 21:26:25', NULL, NULL, NULL);

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
('GH01', 'U07', 'ordered', '2026-02-04 19:01:44'),
('GH02', 'U02', 'ordered', '2026-04-02 10:31:09'),
('GH03', 'U12', 'active', '2026-04-02 22:23:53'),
('GH04', 'U10', 'active', '2026-04-20 10:26:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `ma_khuyen_mai` varchar(20) NOT NULL,
  `ten_khuyen_mai` varchar(255) DEFAULT NULL,
  `tien_khuyen_mai` decimal(38,2) DEFAULT NULL,
  `ngay_bat_dau` datetime DEFAULT NULL,
  `ngay_ket_thuc` datetime DEFAULT NULL,
  `trang_thai_khuyen_mai` enum('con','het') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`ma_khuyen_mai`, `ten_khuyen_mai`, `tien_khuyen_mai`, `ngay_bat_dau`, `ngay_ket_thuc`, `trang_thai_khuyen_mai`) VALUES
('KM01', 'Chào Hè 2024', '500000.00', '2026-01-17 00:00:00', '2024-08-31 00:00:00', 'het'),
('KM02', 'Black Friday', '1000000.00', '2024-11-20 00:00:00', '2024-11-30 00:00:00', 'het'),
('KM03', 'Khách hàng mới', '200000.00', '2024-01-01 00:00:00', '2026-01-22 23:00:00', 'het'),
('KM04', 'Giảm giá Tết', '300000.00', '2026-01-25 00:00:00', '2026-01-31 00:00:00', 'con'),
('KM05', 'Sale cuối tuần', '200000.00', '2026-01-30 00:00:00', '2026-01-31 00:00:00', 'con'),
('KM06', 'Flash Sale', '500000.00', '2026-01-30 00:00:00', '2026-06-30 00:00:00', 'con'),
('KM07', 'tết', '123.00', '1970-01-01 08:00:00', '2026-06-30 09:09:00', 'con'),
('KM08', 'chữa cháy', '300000.00', '2026-01-04 08:00:00', '2026-04-10 08:00:00', 'con'),
('KM09', 'sos', '1300000.00', '2026-01-04 08:00:00', '2026-04-10 08:00:00', 'con');

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
('SP027', 'abcdef', 'DM08', 'TH07', 'NCC02', '2026-03-27 22:07:14'),
('SP028', 'Tai nghe Sony WH-1000XM3', 'DM08', 'TH07', 'NCC02', '2026-04-01 14:44:29'),
('SP029', 'MacbookNEO8', 'DM06', 'TH01', 'NCC04', '2026-04-01 15:01:46'),
('SP03', 'Sạc Anker 20000mAh', 'DM09', 'TH08', 'NCC03', '2026-01-30 21:31:40'),
('SP04', 'Macbook m2', 'DM9', 'TH01', 'NCC01', '2026-02-01 01:15:43'),
('SP05', 'Apple Watch SE 2023 GPS 40mm', 'DM9', 'TH01', 'NCC01', '2026-02-01 01:17:41'),
('SP06', 'iPhone 17 Pro Max 256GB VN/A', 'DM01', 'TH01', 'NCC03', '2026-02-01 01:21:33'),
('SP07', 'Ao ma', 'DM07', 'TH01', 'NCC02', '2026-02-01 19:19:15'),
('SP08', 'Laptop Lenovo LOQ', 'DM02', 'TH06', 'NCC03', '2026-02-02 14:48:38'),
('SP09', 'Laptop Acer Gaming Aspire 7', 'DM02', 'TH05', 'NCC04', '2026-02-02 14:54:30'),
('SP100', 'iPhone 16', 'DM01', 'TH01', 'NCC01', '2026-04-22 10:52:18'),
('SP101', 'iPhone 16 Pro', 'DM01', 'TH01', 'NCC02', '2026-04-22 10:52:18'),
('SP102', 'Samsung Galaxy A75', 'DM01', 'TH02', 'NCC03', '2026-04-22 10:52:18'),
('SP103', 'Xiaomi 14 Ultra', 'DM01', 'TH03', 'NCC02', '2026-04-22 10:52:18'),
('SP104', 'Oppo Find X8', 'DM01', 'TH04', 'NCC01', '2026-04-22 10:52:18'),
('SP105', 'Vivo X200', 'DM01', 'TH04', 'NCC03', '2026-04-22 10:52:18'),
('SP106', 'Realme GT 6', 'DM01', 'TH03', 'NCC04', '2026-04-22 10:52:18'),
('SP107', 'Google Pixel 9', 'DM01', 'TH05', 'NCC05', '2026-04-22 10:52:18'),
('SP108', 'MacBook Pro M3', 'DM02', 'TH01', 'NCC02', '2026-04-22 10:52:18'),
('SP109', 'Dell XPS 15', 'DM02', 'TH07', 'NCC03', '2026-04-22 10:52:18'),
('SP110', 'HP Spectre x360', 'DM02', 'TH06', 'NCC04', '2026-04-22 10:52:18'),
('SP111', 'Lenovo Legion 5', 'DM02', 'TH06', 'NCC01', '2026-04-22 10:52:18'),
('SP112', 'Asus ROG Strix G16', 'DM02', 'TH08', 'NCC03', '2026-04-22 10:52:18'),
('SP113', 'Acer Nitro 5 2025', 'DM02', 'TH05', 'NCC02', '2026-04-22 10:52:18'),
('SP114', 'MSI Katana 17', 'DM02', 'TH07', 'NCC01', '2026-04-22 10:52:18'),
('SP115', 'Huawei MateBook X Pro', 'DM02', 'TH09', 'NCC05', '2026-04-22 10:52:18'),
('SP116', 'Apple Watch Series 10', 'DM03', 'TH01', 'NCC01', '2026-04-22 10:52:18'),
('SP117', 'Samsung Galaxy Watch 7', 'DM03', 'TH02', 'NCC02', '2026-04-22 10:52:18'),
('SP118', 'Huawei Watch GT 5', 'DM03', 'TH09', 'NCC03', '2026-04-22 10:52:18'),
('SP119', 'Xiaomi Watch S4', 'DM03', 'TH03', 'NCC04', '2026-04-22 10:52:18'),
('SP120', 'Tai nghe Sony WF-1000XM5', 'DM08', 'TH07', 'NCC01', '2026-04-22 10:52:18'),
('SP121', 'AirPods Pro 3', 'DM08', 'TH01', 'NCC02', '2026-04-22 10:52:18'),
('SP122', 'Samsung Galaxy Buds 3', 'DM08', 'TH02', 'NCC03', '2026-04-22 10:52:18'),
('SP123', 'JBL Tune 770NC', 'DM08', 'TH06', 'NCC04', '2026-04-22 10:52:18'),
('SP124', 'Sạc nhanh Anker 65W', 'DM09', 'TH08', 'NCC01', '2026-04-22 10:52:18'),
('SP125', 'Pin dự phòng Xiaomi 30000mAh', 'DM09', 'TH03', 'NCC02', '2026-04-22 10:52:18'),
('SP126', 'Cáp sạc Type-C Baseus', 'DM09', 'TH08', 'NCC03', '2026-04-22 10:52:18'),
('SP127', 'Củ sạc Apple 20W', 'DM09', 'TH01', 'NCC04', '2026-04-22 10:52:18'),
('SP128', 'iPad Air 6', 'DM06', 'TH01', 'NCC02', '2026-04-22 10:52:18'),
('SP129', 'Samsung Galaxy Tab S10', 'DM06', 'TH02', 'NCC03', '2026-04-22 10:52:18'),
('SP130', 'Xiaomi Pad 7', 'DM06', 'TH03', 'NCC04', '2026-04-22 10:52:18'),
('SP131', 'Laptop Gaming ASUS ROG Zephyrus', 'DM02', 'TH08', 'NCC01', '2026-04-22 10:52:18'),
('SP132', 'Laptop Dell Gaming G15', 'DM02', 'TH07', 'NCC02', '2026-04-22 10:52:18'),
('SP133', 'Laptop HP Victus 16', 'DM02', 'TH06', 'NCC03', '2026-04-22 10:52:18'),
('SP134', 'Laptop Lenovo ThinkPad X1', 'DM02', 'TH06', 'NCC04', '2026-04-22 10:52:18'),
('SP135', 'Điện thoại Nokia X50', 'DM01', 'TH05', 'NCC05', '2026-04-22 10:52:18'),
('SP136', 'Điện thoại Asus Zenfone 11', 'DM01', 'TH08', 'NCC01', '2026-04-22 10:52:18'),
('SP137', 'Điện thoại Sony Xperia 1 VI', 'DM01', 'TH07', 'NCC02', '2026-04-22 10:52:18'),
('SP138', 'Tai nghe Bluetooth Soundcore', 'DM08', 'TH08', 'NCC03', '2026-04-22 10:52:18'),
('SP139', 'Tai nghe Gaming Razer Kraken', 'DM08', 'TH06', 'NCC04', '2026-04-22 10:52:18'),
('SP140', 'Chuột Logitech G Pro', 'DM07', 'TH06', 'NCC01', '2026-04-22 10:52:18'),
('SP141', 'Bàn phím cơ Akko 3068', 'DM07', 'TH06', 'NCC02', '2026-04-22 10:52:18'),
('SP142', 'Chuột Gaming Razer DeathAdder', 'DM07', 'TH06', 'NCC03', '2026-04-22 10:52:18'),
('SP143', 'Bàn phím Logitech K380', 'DM07', 'TH06', 'NCC04', '2026-04-22 10:52:18'),
('SP144', 'Camera Xiaomi Home', 'DM07', 'TH03', 'NCC01', '2026-04-22 10:52:18'),
('SP145', 'Camera Ezviz C6N', 'DM07', 'TH05', 'NCC02', '2026-04-22 10:52:18'),
('SP146', 'Loa Bluetooth JBL Flip 7', 'DM07', 'TH06', 'NCC03', '2026-04-22 10:52:18'),
('SP147', 'Loa Sony SRS-XB100', 'DM07', 'TH07', 'NCC04', '2026-04-22 10:52:18'),
('SP148', 'Router WiFi TP-Link AX3000', 'DM07', 'TH06', 'NCC01', '2026-04-22 10:52:18'),
('SP149', 'Router WiFi Asus RT-AX55', 'DM07', 'TH08', 'NCC02', '2026-04-22 10:52:18'),
('SP16', 'Laptop ASUS Zenbook', 'DM02', 'TH06', 'NCC01', '2026-02-04 13:33:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `config_key` varchar(100) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_by` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`config_key`, `config_value`, `description`, `updated_by`, `created_at`, `updated_at`) VALUES
('order_timeout_minutes', '3', 'So phut toi da cho don hang o trang thai cho_duyet', 'U10', '2026-04-18 16:13:51', '2026-04-19 01:11:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `ma_giao_dich` varchar(20) NOT NULL,
  `ma_don_hang` varchar(20) NOT NULL,
  `phuong_thuc` enum('cod','vnpay') DEFAULT NULL,
  `so_tien_thanh_toan` decimal(15,2) DEFAULT NULL,
  `trang_thai_thanh_toan` enum('da_thanh_toan','chua_thanh_toan') DEFAULT NULL,
  `ngay_thanh_toan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thanh_toan`
--

INSERT INTO `thanh_toan` (`ma_giao_dich`, `ma_don_hang`, `phuong_thuc`, `so_tien_thanh_toan`, `trang_thai_thanh_toan`, `ngay_thanh_toan`) VALUES
('GD01', 'DH01', 'vnpay', '59980000.00', 'chua_thanh_toan', '2026-02-05 07:52:05'),
('GD02', 'DH02', 'vnpay', '29490000.00', 'chua_thanh_toan', '2026-02-05 17:15:55'),
('GD03', 'DH03', 'cod', '29490000.00', 'chua_thanh_toan', '2026-02-05 17:16:01'),
('GD04', 'DH04', 'cod', '1250000.00', 'chua_thanh_toan', '2026-02-05 17:16:27'),
('GD05', 'DH05', 'cod', '4350000.00', 'chua_thanh_toan', '2026-02-05 19:37:13'),
('GD06', 'DH06', 'vnpay', '149000.00', 'chua_thanh_toan', '2026-02-06 00:27:37'),
('GD08', 'DH08', 'cod', '39000000.00', 'chua_thanh_toan', '2026-02-07 05:11:40'),
('GD09', 'DH09', 'cod', '0.00', 'chua_thanh_toan', '2026-04-02 10:31:18'),
('GD10', 'DH10', 'cod', '28690000.00', 'chua_thanh_toan', '2026-04-02 11:11:38'),
('GD11', 'DH11', 'cod', '29990000.00', 'chua_thanh_toan', '2026-04-02 11:12:00'),
('GD12', 'DH12', 'vnpay', '11670000.00', 'chua_thanh_toan', '2026-04-02 11:12:14'),
('GD13', 'DH13', 'cod', '4930000.00', 'chua_thanh_toan', '2026-04-02 11:12:30'),
('GD14', 'DH14', 'cod', '20900000.00', 'chua_thanh_toan', '2026-04-02 11:13:27'),
('GD15', 'DH15', 'cod', '26990000.00', 'chua_thanh_toan', '2026-04-02 15:50:21'),
('GD16', 'DH16', 'cod', '29700000.00', 'chua_thanh_toan', '2026-04-02 17:43:26'),
('GD17', 'DH17', 'cod', '0.00', 'chua_thanh_toan', '2026-04-02 20:43:27'),
('GD18', 'DH18', 'cod', '29990000.00', 'chua_thanh_toan', '2026-04-02 20:43:50'),
('GD19', 'DH19', 'cod', '0.00', 'chua_thanh_toan', '2026-04-02 20:46:11'),
('GD20', 'DH20', 'cod', '0.00', 'chua_thanh_toan', '2026-04-02 20:47:25'),
('GD21', 'DH21', 'cod', '74980000.00', 'chua_thanh_toan', '2026-04-02 20:53:07'),
('GD22', 'DH22', 'cod', '17700000.00', 'chua_thanh_toan', '2026-04-02 21:16:05'),
('GD23', 'DH23', 'cod', '4630000.00', '', '2026-04-02 21:43:43'),
('GD24', 'DH24', 'cod', '29690000.00', '', '2026-04-02 21:39:36'),
('GD25', 'DH25', 'cod', '19600000.00', '', '2026-04-02 21:38:57'),
('GD26', 'DH26', 'vnpay', '26990000.00', 'da_thanh_toan', '2026-04-02 21:46:00'),
('GD27', 'DH27', 'cod', '17999877.00', 'chua_thanh_toan', '2026-04-17 23:53:19'),
('GD28', 'DH28', 'cod', '18000000.00', 'chua_thanh_toan', '2026-04-18 16:18:21'),
('GD29', 'DH29', 'cod', '19490000.00', 'chua_thanh_toan', '2026-04-18 16:21:53'),
('GD30', 'DH30', 'cod', '26490000.00', 'chua_thanh_toan', '2026-04-18 16:36:20'),
('GD31', 'DH31', 'cod', '17999877.00', 'chua_thanh_toan', '2026-04-18 16:36:33'),
('GD32', 'DH32', 'cod', '11170000.00', 'chua_thanh_toan', '2026-04-18 16:42:35'),
('GD33', 'DH33', 'cod', '26490000.00', 'chua_thanh_toan', '2026-04-18 16:50:22'),
('GD34', 'DH34', 'cod', '4490000.00', 'chua_thanh_toan', '2026-04-18 17:04:38'),
('GD35', 'DH35', 'cod', '39000000.00', 'chua_thanh_toan', '2026-04-18 17:38:55'),
('GD36', 'DH36', 'cod', '26490000.00', 'chua_thanh_toan', '2026-04-18 17:55:22'),
('GD37', 'DH37', 'cod', '4349877.00', 'chua_thanh_toan', '2026-04-18 17:57:52'),
('GD38', 'DH38', 'cod', '71480000.00', 'chua_thanh_toan', '2026-04-18 18:02:30'),
('GD39', 'DH39', 'cod', '107969877.00', 'chua_thanh_toan', '2026-04-18 18:06:13'),
('GD40', 'DH40', 'cod', '29990000.00', 'chua_thanh_toan', '2026-04-19 00:58:44'),
('GD41', 'DH41', 'vnpay', '29990000.00', 'chua_thanh_toan', '2026-04-19 01:25:52'),
('GD42', 'DH42', 'cod', '29990000.00', 'chua_thanh_toan', '2026-04-19 21:26:25');

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
('TH11', 'Anker', '2026-02-02 01:38:07'),
('TH12', 'DEO', '2026-04-01 08:19:17'),
('TH13', 'SEL', '2026-04-01 08:22:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `ma_user` varchar(20) NOT NULL,
  `ten_user` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `avatar` text NOT NULL,
  `email` varchar(55) DEFAULT NULL,
  `phan_quyen` enum('admin','khach_hang') NOT NULL DEFAULT 'khach_hang',
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`ma_user`, `ten_user`, `password`, `full_name`, `avatar`, `email`, `phan_quyen`, `so_dien_thoai`, `ngay_tao`) VALUES
('U01', 'vinh', '123', 'Đào Văn Vinh', 'xe_1770298141.jpg', 'daovinhgm2005@gmail.com', 'admin', '0389783619', '2026-01-30 13:32:56'),
('U02', 'dan', '123', 'Đào Phúc Dânn', 'IMG_2983_copy_1770295086.jpg', 'dan@gmail.com', 'khach_hang', '0312312312', '2026-01-30 13:32:56'),
('U03', 'thanh', '123', 'Hoàng Văn Thành', 'z7287730553840_9d9e33116de5a88d90a36f0f66c705e1_1770295073.jpg', 'thanh@gmail.com', 'admin', '0389783619', '2026-01-30 13:34:04'),
('U04', 'qa', '123', 'Đỗ Quanh Anh', 'anh_1770295065.jpg', 'qa@gmail.com', 'khach_hang', '12312312', '2026-01-30 13:34:04'),
('U06', 'long', '123', 'Nguyễn Văn Long', 'anh_1770295007.jpg', 'long@gmail.com', 'khach_hang', '0901111111', '2026-01-30 21:31:40'),
('U07', 'minh', '1234', 'Trần Văn Minh', 'lu__n_h___i_1775038753.jpg', '12345minh@gmail.com', 'khach_hang', '0123242141', '2026-01-30 21:31:40'),
('U08', 'admin', '123', 'Lê Văn Hùng', 'z7287730553840_9d9e33116de5a88d90a36f0f66c705e1_1770294998.jpg', 'hung@gmail.com', 'admin', '0903333333', '2026-01-30 21:31:40'),
('U09', 'dan', '1234', 'Đào Phúc Dân', 'Home_Tet_1775037849.jpg', 'dunghiep6b@gmail.com', 'admin', '0862757951', '2026-02-03 08:20:14'),
('U10', 'admin', '123', 'Đào Phúc Dânn', 'avatar_1775035870.jpg', 'dunghiep6a@gmail.com', 'admin', '0902222223', '2026-04-01 16:31:10'),
('U11', 'dann', '123456', 'Đào Phúc Dân', 'Screenshot_2025_02_21_233857_1775223454.png', 'minh@gmail.com', 'admin', '0902222224', '2026-04-01 19:05:54'),
('U12', 'thanhthanh', '123', 'thanhh', 'Screenshot_2025_10_07_151438_1775223445.png', 'thanhthanh@gmail.com', 'khach_hang', '0886774231', '2026-04-02 22:23:35'),
('U13', 'minhthu', '123', 'minh thư123', 'qanh_1775223428.png', 'minhthu@gmail.com', 'admin', '0123456783', '2026-04-03 13:49:10');

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
-- Chỉ mục cho bảng `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_conversation_code` (`conversation_code`),
  ADD KEY `idx_chat_conv_user` (`ma_user`),
  ADD KEY `idx_chat_conv_guest` (`guest_token`),
  ADD KEY `idx_chat_conv_status` (`status`);

--
-- Chỉ mục cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_msg_conv` (`conversation_id`),
  ADD KEY `idx_chat_msg_user` (`ma_user`),
  ADD KEY `idx_chat_msg_created` (`created_at`);

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
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`config_key`),
  ADD KEY `idx_settings_updated_by` (`updated_by`);

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
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bien_the`
--
ALTER TABLE `bien_the`
  ADD CONSTRAINT `fk_bt_sp` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD CONSTRAINT `fk_chat_conv_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `fk_chat_msg_conv` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_msg_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE SET NULL;

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
-- Các ràng buộc cho bảng `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `fk_settings_updated_by_users` FOREIGN KEY (`updated_by`) REFERENCES `users` (`ma_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `fk_tt_dh` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE;

DELIMITER $$
--
-- Sự kiện
--
CREATE DEFINER=`root`@`localhost` EVENT `ev_auto_cancel_orders` ON SCHEDULE EVERY 10 SECOND STARTS '2026-04-19 00:58:28' ON COMPLETION PRESERVE ENABLE DO BEGIN
    DECLARE timeout_minutes INT DEFAULT 1440;

    SELECT CAST(config_value AS UNSIGNED)
    INTO timeout_minutes
    FROM settings
    WHERE config_key = 'order_timeout_minutes'
    LIMIT 1;

    IF timeout_minutes IS NULL OR timeout_minutes <= 0 THEN
        SET timeout_minutes = 1440;
    END IF;

    START TRANSACTION;

    DROP TEMPORARY TABLE IF EXISTS tmp_cancel_orders;
    CREATE TEMPORARY TABLE tmp_cancel_orders (
        ma_don_hang VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci PRIMARY KEY
    ) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    INSERT INTO tmp_cancel_orders (ma_don_hang)
    SELECT ma_don_hang
    FROM don_hang
    WHERE trang_thai_don_hang = 'cho_duyet'
      AND ngay_tao < DATE_SUB(NOW(), INTERVAL timeout_minutes MINUTE);

    UPDATE bien_the bt
    JOIN (
        SELECT ctdh.ma_bien_the, SUM(ctdh.so_luong) AS total_qty
        FROM chi_tiet_don_hang ctdh
        JOIN tmp_cancel_orders t ON t.ma_don_hang = ctdh.ma_don_hang
        GROUP BY ctdh.ma_bien_the
    ) x ON x.ma_bien_the = bt.ma_bien_the
    SET bt.so_luong_kho = bt.so_luong_kho + x.total_qty;

    UPDATE don_hang dh
    JOIN tmp_cancel_orders t ON t.ma_don_hang = dh.ma_don_hang
    SET dh.trang_thai_don_hang = 'da_huy'
    WHERE dh.trang_thai_don_hang = 'cho_duyet';

    DROP TEMPORARY TABLE IF EXISTS tmp_cancel_orders;

    COMMIT;
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
