<?php
// Include necessary helpers
include_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Classes/TimezoneHelper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Classes/UrlHelper.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng - TechZone</title>
    <base href="<?php echo UrlHelper::baseUrl(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        /* --- 1. CORE VARIABLES & RESET --- */
        :root {
            --primary-green: #00483d;
            --secondary-green: #006a5b;
            --tet-red: #d70018;
            --tet-yellow: #fce700;
            --text-gray: #555;
            --text-dark: #333;
            --border-color: #e0e0e0;
            --bg-light: #f4f4f4;
            --blue-btn: #2d72d2;
            --fb-blue: #365899;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: var(--text-dark);
            position: relative;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 10px;
        }

        /* --- 2. TOP BANNER --- */
        .top-banner {
            background-color: var(--primary-green);
            color: white;
            font-size: 13px;
            padding: 8px 0;
        }

        .top-banner .container {
            display: flex;
            justify-content: space-between;
        }

        .top-banner-left {
            display: flex;
            gap: 155px;
        }

        .top-banner-right {
            display: flex;
            gap: 20px;
        }

        .top-banner span i {
            margin-right: 5px;
        }

        /* --- 3. MAIN HEADER --- */
        .main-header {
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .main-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary-green);
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .middle-section {
            flex-grow: 1;
            max-width: 700px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .category-dropdown {
            position: relative;
        }

        .btn-category {
            height: 40px;
            background: #f5f5f7;
            border: 1px solid #e0e0e0;
            padding: 0 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            color: #333;
            transition: 0.2s;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 220px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            padding: 10px 0;
            margin-top: 10px;
            display: none;
            z-index: 1000;
            border: 1px solid #eee;
        }

        .dropdown-menu::before {
            content: "";
            position: absolute;
            top: -6px;
            left: 20px;
            width: 12px;
            height: 12px;
            background: white;
            transform: rotate(45deg);
            border-left: 1px solid #eee;
            border-top: 1px solid #eee;
        }

        .category-dropdown:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .dropdown-menu ul li a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 14px;
            color: #333;
            transition: 0.2s;
            width: 100%;
        }

        .dropdown-menu ul li a:hover {
            background-color: #f9f9f9;
            color: var(--primary-green);
        }

        .search-box {
            flex-grow: 1;
            display: flex;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            overflow: hidden;
            height: 40px;
        }

        .search-box input {
            flex-grow: 1;
            border: none;
            padding: 0 15px;
            outline: none;
            font-size: 14px;
        }

        .search-box button {
            background: white;
            border: none;
            padding: 0 20px;
            color: var(--text-gray);
            cursor: pointer;
            border-left: 1px solid #eee;
        }

        .search-box button:hover {
            color: var(--primary-green);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
        }

        .action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--text-gray);
            cursor: pointer;
            white-space: nowrap;
            position: relative;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--tet-red);
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
        }

        /* CSS Menu Tài khoản */
        .account-dropdown-menu {
            position: absolute;
            top: 45px;
            right: -10px;
            width: 200px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            display: none;
            z-index: 1100;
            border: 1px solid #eee;
        }

        .account-dropdown-menu.active {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .account-dropdown-menu::before {
            content: "";
            position: absolute;
            top: -6px;
            right: 25px;
            width: 12px;
            height: 12px;
            background: white;
            transform: rotate(45deg);
            border-top: 1px solid #eee;
            border-left: 1px solid #eee;
        }

        .account-dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            font-size: 14px;
            color: #333;
            transition: all 0.2s;
            text-decoration: none;
        }

        .account-dropdown-menu a:hover {
            background-color: #f5f5f7;
            color: var(--primary-green);
        }

        .divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- 4. ORDER HISTORY STYLES --- */
        .order-history-wrapper {
            padding: 40px 0;
            min-height: 600px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #333;
        }

        /* Tabs Style */
        .order-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .tab-item {
            padding: 15px 20px;
            font-size: 15px;
            color: #777;
            cursor: pointer;
            position: relative;
            white-space: nowrap;
            transition: 0.2s;
        }

        .tab-item:hover {
            color: var(--blue-btn);
        }

        .tab-item.active {
            color: var(--blue-btn);
            font-weight: 600;
        }

        .tab-item.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--blue-btn);
            border-radius: 3px 3px 0 0;
        }

        /* Order Card Style */
        .order-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid transparent;
            transition: 0.2s;
        }

        .order-card:hover {
            border-color: #dbeaff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .order-header {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .order-id {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .order-date {
            font-size: 13px;
            color: #888;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-confirmed {
            background-color: #e3f9e5;
            color: #1f8b24;
        }

        /* Xanh lá nhạt */
        .status-shipping {
            background-color: #e0f2fe;
            color: #0284c7;
        }

        /* Xanh dương nhạt */
        .status-cancelled {
            background-color: #fee2e2;
            color: #dc2626;
        }

        /* Đỏ nhạt */

        /* --- SỬA CẤU TRÚC BODY: CHIA 2 CỘT NGANG HÀNG --- */
        .order-body-flex {
            display: flex;
            justify-content: space-between;
            /* Đẩy sản phẩm sang trái, tiền sang phải */
            align-items: center;
            /* Căn giữa theo chiều dọc */
            padding: 20px;
        }

        /* Cột bên trái: Danh sách sản phẩm */
        .order-product-list {
            flex: 1;
            /* Chiếm hết khoảng trống còn lại */
            margin-right: 20px;
            /* Cách cột tiền một chút */
        }

        .order-product {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .order-product:last-child {
            margin-bottom: 0;
        }

        .product-thumb {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            border: 1px solid #f0f0f0;
            object-fit: contain;
            padding: 5px;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .product-meta {
            font-size: 13px;
            color: #777;
            margin-bottom: 5px;
        }

        .product-price {
            font-size: 14px;
            font-weight: 700;
            color: var(--blue-btn);
        }

        /* Cột bên phải: Tiền và Nút (Nằm ngang hàng với sản phẩm) */
        .order-actions-right {
            display: flex;
            flex-direction: column;
            /* Xếp dọc nội bộ cột phải */
            align-items: flex-end;
            /* Căn lề phải */
            gap: 10px;
            /* Khoảng cách giữa tiền và nút */
            min-width: 150px;
            /* Đảm bảo không bị co quá nhỏ */
        }

        .total-money {
            text-align: right;
        }

        .total-label {
            font-size: 14px;
            color: #555;
        }

        .total-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--blue-btn);
            display: block;
            /* Xuống dòng cho đẹp */
            margin-top: 2px;
        }

        .btn-detail {
            background-color: var(--blue-btn);
            color: white;
            border: none;
            padding: 8px 25px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-detail:hover {
            opacity: 0.9;
            box-shadow: 0 2px 8px rgba(45, 114, 210, 0.3);
        }

        /* --- 5. FOOTER --- */
        .main-footer {
            border-top: 4px solid #f4f4f4;
            padding: 40px 0 20px;
            margin-top: 0;
            background: white;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            gap: 40px;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: 800;
            color: #006a5b;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
        }

        .footer-col h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }

        .address-list li,
        .footer-links li {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
        }

        .fanpage-box {
            background: white;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 5px;
        }

        .fp-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .fp-avatar {
            width: 50px;
            height: 50px;
            border: 1px solid #ddd;
            overflow: hidden;
        }

        .fp-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fp-info {
            display: flex;
            flex-direction: column;
            padding-top: 2px;
        }

        .fp-name {
            color: var(--fb-blue);
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .fp-followers {
            color: #4b4f56;
            font-size: 12px;
        }

        .social-icons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .social-icons a {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 1px solid #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #555;
        }

        .social-icons a:hover {
            border-color: var(--tet-red);
            color: var(--tet-red);
        }

        .contact-info p {
            font-size: 13px;
            color: #333;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .hotline-large {
            font-size: 18px;
            color: #333;
            font-weight: 700;
            margin-top: 10px;
            display: block;
        }

        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .main-header .container {
                flex-direction: column;
                align-items: stretch;
            }

            .top-banner {
                display: none;
            }

            /* Responsive: Trên mobile thì cho rớt dòng */
            .order-body-flex {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-actions-right {
                width: 100%;
                align-items: flex-end;
                margin-top: 15px;
                border-top: 1px dashed #eee;
                padding-top: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="top-banner">
        <div class="container">
            <div class="top-banner-left">
                <span><i class="fa-solid fa-circle-check"></i>SẢN PHẨM CHÍNH HÃNG</span>
                <span><i class="fa-solid fa-rotate-left"></i>CAM KẾT LỖI ĐỔI LIỀN</span>
                <span><i class="fa-solid fa-phone-volume"></i>HOTLINE 1900.2091</span>
            </div>
            <div class="top-banner-right">
                <span><i class="fa-solid fa-truck-fast"></i>MIỄN PHÍ VẬN CHUYỂN TOÀN QUỐC</span>
            </div>
        </div>
    </div>

    <header class="main-header">
        <div class="container">
            <a href="<?php echo UrlHelper::url('Khachhang'); ?>" class="logo"><i class="fa-brands fa-instalod"></i>
                TECHZONE</a>

            <div class="middle-section">
                <div class="category-dropdown">
                    <button class="btn-category">
                        <i class="fa-solid fa-bars"></i> Danh mục
                    </button>
                    <div class="dropdown-menu">
                        <ul>
                            <li><a href="#">Điện thoại</a></li>
                            <li><a href="#">Laptop</a></li>
                        </ul>
                    </div>
                </div>

                <div class="search-box">
                    <input type="text" placeholder="Hôm nay bạn muốn tìm kiếm gì?">
                    <button><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
                </div>
            </div>

            <div class="header-actions">
                <div class="action-item" id="accountBtn">
                    <i class="fa-regular fa-user"></i>
                    <span>
                        <?php
                        if (isset($_SESSION['user_name'])) {
                            echo htmlspecialchars($_SESSION['user_name']);
                        } else {
                            echo 'Tài khoản';
                        }
                        ?>
                    </span>
                    <div class="account-dropdown-menu" id="accountMenu">
                        <?php if (isset($_SESSION['user_name'])): ?>
                            <a href="<?php echo UrlHelper::url('Khachhang/taikhoan'); ?>"><i
                                    class="fa-solid fa-user-gear"></i>
                                Quản lý tài khoản</a>
                            <a href="<?php echo UrlHelper::url('Khachhang/lichsumuahang'); ?>"><i
                                    class="fa-solid fa-box-open"></i> Đơn hàng của tôi</a>
                            <div class="divider"></div>
                            <a href="<?php echo UrlHelper::url('Login/logout'); ?>" style="color: #d70018;"><i
                                    class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                        <?php else: ?>
                            <a href="<?php echo UrlHelper::url('Login'); ?>"><i class="fa-solid fa-user"></i> Đăng nhập</a>
                            <a href="<?php echo UrlHelper::url('Login/register'); ?>"><i class="fa-solid fa-user-plus"></i>
                                Đăng
                                ký</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="action-item cart-icon-wrap">
                    <i class="fa-solid fa-cart-shopping"></i><span>Giỏ hàng</span><span class="cart-badge">0</span>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="order-history-wrapper">
            <h1 class="page-title">Đơn hàng của bạn</h1>

            <div class="order-tabs">
                <div class="tab-item active">Tất cả (<?php echo count($data['don_hang']); ?>)</div>
                <div class="tab-item">Chờ xác nhận (0)</div>
                <div class="tab-item">Đã xác nhận (0)</div>
                <div class="tab-item">Đang giao (0)</div>
                <div class="tab-item">Hoàn thành (0)</div>
                <div class="tab-item">Đã hủy (0)</div>
            </div>

            <?php if ($data['don_hang'] && count($data['don_hang']) > 0): ?>
                <?php foreach ($data['don_hang'] as $dh): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <span class="order-id">Đơn hàng #<?php echo $dh['ma_don_hang']; ?></span>
                                <span class="order-date">Đặt ngày: <?php echo $this->formatDate($dh['ngay_tao']); ?></span>
                            </div>
                            <span class="status-badge
                                <?php
                                switch ($dh['trang_thai_don_hang']) {
                                    case 'cho_duyet':
                                        echo 'status-confirmed';
                                        break;
                                    case 'dang_giao':
                                        echo 'status-shipping';
                                        break;
                                    case 'hoan_thanh':
                                        echo 'status-confirmed';
                                        break;
                                    case 'da_huy':
                                        echo 'status-cancelled';
                                        break;
                                    default:
                                        echo 'status-cancelled';
                                }
                                ?>">
                                <?php
                                switch ($dh['trang_thai_don_hang']) {
                                    case 'cho_duyet':
                                        echo 'Chờ xác nhận';
                                        break;
                                    case 'dang_giao':
                                        echo 'Đang giao hàng';
                                        break;
                                    case 'hoan_thanh':
                                        echo 'Đã hoàn thành';
                                        break;
                                    case 'da_huy':
                                        echo 'Đã hủy';
                                        break;
                                    default:
                                        echo ucfirst(str_replace('_', ' ', $dh['trang_thai_don_hang']));
                                }
                                ?>
                            </span>
                        </div>

                        <div class="order-body-flex">

                            <div class="order-product-list">
                                <?php
                                $chi_tiet_don_hang = $dh['chi_tiet'];
                                if ($chi_tiet_don_hang && count($chi_tiet_don_hang) > 0):
                                    foreach ($chi_tiet_don_hang as $ct):
                                ?>
                                        <div class="order-product">
                                            <img src="<?php echo !empty($ct['hinh_anh']) ? '/Banhang/Public/Pictures/bien_the/' . $ct['hinh_anh'] : 'https://placehold.co/80x80?text=SP'; ?>"
                                                alt="<?php echo $ct['ten_san_pham']; ?>" class="product-thumb">
                                            <div class="product-info">
                                                <span
                                                    class="product-name"><?php echo $ct['ten_san_pham'] ? $ct['ten_san_pham'] : 'Sản phẩm đã xóa'; ?></span>
                                                <div class="product-meta">Số lượng: <?php echo $ct['so_luong']; ?></div>
                                                <?php if ($ct['ten_bien_the']): ?>
                                                    <div class="product-meta">Biến thể: <?php echo $ct['ten_bien_the']; ?></div>
                                                <?php endif; ?>
                                                <div class="product-price">
                                                    <?php echo number_format($ct['gia_luc_mua'], 0, ',', '.'); ?>₫</div>
                                            </div>
                                        </div>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <div class="order-product">
                                        <img src="https://placehold.co/80x80?text=SP" alt="Sản phẩm" class="product-thumb">
                                        <div class="product-info">
                                            <span class="product-name">Không có sản phẩm</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="order-actions-right">
                                <div class="total-money">
                                    <span class="total-label">Tổng tiền:</span>
                                    <span
                                        class="total-value"><?php echo number_format($dh['tong_tien_hang'], 0, ',', '.'); ?>₫</span>
                                </div>
                                <a href="<?php echo $this->url('Khachhang/chitietdonhang/' . $dh['ma_don_hang']); ?>"
                                    class="btn-detail"><i class="fa-regular fa-eye"></i> Xem chi tiết</a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h4>Bạn chưa có đơn hàng nào</h4>
                    <p>Hãy bắt đầu mua sắm để có đơn hàng đầu tiên</p>
                    <a href="<?php echo $this->url('Khachhang'); ?>" class="btn btn-primary">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">

                <div class="footer-col">
                    <div class="footer-logo">
                        <i class="fa-brands fa-instalod"></i> TECHZONE
                    </div>
                    <ul class="address-list">
                        <li><strong>Địa chỉ:</strong></li>
                        <li><strong>Cơ sở 1:</strong> 221 Vũ Tông Phan - Thanh Xuân - Hà Nội</li>
                        <li><strong>Cơ sở 2:</strong> 17 Nguyễn Phong Sắc - Cầu Giấy - Hà Nội</li>
                        <li><strong>Cơ sở 3:</strong> 145 Minh Khai - Hai Bà Trưng - Hà Nội</li>
                        <li><strong>Cơ sở 4:</strong> 142 Quang Trung - Hà Đông - Hà Nội</li>
                        <li><strong>Gọi mua hàng:</strong> 0825.303.888 (8h00 - 22h00)</li>
                        <li><strong>Gọi bảo hành:</strong> 0922.702.888 (8h00 - 21h00)</li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Chính sách</h4>
                    <ul class="footer-links">
                        <li><a href="#">Chính sách mua hàng</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Cam kết chất lượng</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Hệ thống cửa hàng</a></li>
                    </ul>
                    <h4>Fanpage</h4>
                    <div class="fanpage-box">
                        <div class="fp-container">
                            <div class="fp-avatar">
                                <img src="https://tse3.mm.bing.net/th/id/OIP.YxmH1xNVNfvD5MlgINYERgHaEB?rs=1&pid=ImgDetMain&o=7&rm=3"
                                    alt="TechZone">
                            </div>
                            <div class="fp-info">
                                <a href="#" class="fp-name">TechZone - Chính Chủ</a>
                                <span class="fp-followers">96.598 người theo dõi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-col">
                    <div class="social-icons">
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                    <div class="contact-info">
                        <p>Nhận phản hồi, thắc mắc:</p>
                        <p>anttech.com.vn @gmail.com</p>
                        <p style="margin-top: 15px;">Tư vấn miễn phí 24/07</p>
                        <span class="hotline-large">0825.303.888</span>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <script>
        // Toggle account dropdown menu
        document.getElementById('accountBtn').addEventListener('click', function() {
            const menu = document.getElementById('accountMenu');
            menu.classList.toggle('active');
        });

        // Close account dropdown when clicking outside
        window.addEventListener('click', function(event) {
            const accountBtn = document.getElementById('accountBtn');
            const accountMenu = document.getElementById('accountMenu');
            if (!accountBtn.contains(event.target)) {
                accountMenu.classList.remove('active');
            }
        });

        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabItems = document.querySelectorAll('.tab-item');
            tabItems.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabItems.forEach(item => item.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>