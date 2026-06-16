<?php
include_once __DIR__ . '/../../Public/Classes/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] : 'Trang chủ - TechZone'; ?></title>
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="TechZone - Điện thoại & Laptop Chính Hãng">
    <meta property="og:description" content="Mua sắm điện thoại, laptop chính hãng giá tốt nhất">
    <meta property="og:image" content="<?php echo UrlHelper::url('Public/Images/4_197.png'); ?>">
    <meta property="og:url" content="<?php echo UrlHelper::baseUrl(); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TechZone">
    
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
            background-color: white;
            color: var(--text-dark);
            position: relative;
        }

        /* Added position relative for overlay */
        a {
            text-decoration: none;
            color: inherit;
            transition: 0.2s;
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

        .dropdown-menu ul li a i {
            width: 25px;
            color: #888;
            text-align: center;
            margin-right: 10px;
        }

        .dropdown-menu ul li a:hover {
            background-color: #f9f9f9;
            color: var(--primary-green);
            padding-left: 25px;
        }

        .dropdown-menu ul li a:hover i {
            color: var(--primary-green);
        }

        /* .search-box {
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
        } */
        .search-box {
            flex-grow: 1;
            height: 40px;
        }

        .search-box form {
            display: flex;
            width: 100%;
            height: 100%;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            overflow: visible;
            position: relative;
        }

        .search-box input {
            flex: 1;
            /* chiếm toàn bộ phần còn lại */
            border: none;
            padding: 0 15px;
            outline: none;
            font-size: 14px;
        }

        .search-box button {
            width: 150px;
            /* cố định ở góc phải */
            border: none;
            background: white;
            cursor: pointer;
            border-left: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            background-color: #afe5dd;

        }

        .search-box button:hover {
            background: #bdd8be;
        }

        .search-btn-text {
            margin-left: 5px;
            font-weight: 600;
        }

        .search-suggest-list {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e7e7e7;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            max-height: 360px;
            overflow-y: auto;
            z-index: 1200;
            display: none;
        }

        .search-suggest-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            text-decoration: none;
            color: #1f1f1f;
            border-bottom: 1px solid #f2f2f2;
        }

        .search-suggest-item:last-child {
            border-bottom: none;
        }

        .search-suggest-item:hover {
            background: #f3fbfa;
        }

        .search-suggest-thumb {
            width: 34px;
            height: 34px;
            object-fit: contain;
            border-radius: 6px;
            background: #f9f9f9;
            border: 1px solid #f0f0f0;
            flex-shrink: 0;
        }

        .search-suggest-name {
            font-size: 13px;
            line-height: 1.35;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-suggest-empty {
            padding: 10px 12px;
            color: #666;
            font-size: 13px;
        }

        .search-suggest-section {
            padding: 8px 12px;
            font-size: 12px;
            color: #666;
            font-weight: 700;
            border-bottom: 1px solid #f2f2f2;
            background: #fcfcfc;
            text-transform: uppercase;
        }

        .search-suggest-history-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            text-decoration: none;
            color: #1f1f1f;
            border-bottom: 1px solid #f2f2f2;
            font-size: 13px;
        }

        .search-suggest-history-item:last-child {
            border-bottom: none;
        }

        .search-suggest-history-item:hover {
            background: #f3fbfa;
        }

        .search-suggest-history-item i {
            color: #7b7b7b;
            font-size: 12px;
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

        /* CSS CHO MENU TÀI KHOẢN */
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

        .account-dropdown-menu a i {
            width: 25px;
            color: #888;
            margin-right: 5px;
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

        /* --- 4. BREADCRUMB --- */
        .breadcrumb {
            padding: 10px 0;
            font-size: 13px;
            color: #777;
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .breadcrumb .container {
            display: flex;
            gap: 5px;
        }

        .breadcrumb a:hover {
            color: var(--primary-green);
        }



        /* --- 7. FOOTER --- */
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

        .footer-logo img {
            height: 40px;
        }

        .footer-col h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }

        .address-list li {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .address-list li strong {
            color: #333;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links li a {
            font-size: 13px;
            color: #555;
        }

        .footer-links li a:hover {
            color: var(--tet-red);
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
            flex-shrink: 0;
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
            text-decoration: none;
            line-height: 1.2;
        }

        .fp-name:hover {
            text-decoration: underline;
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

        .contact-info span {
            font-weight: 400;
            color: #555;
        }

        .hotline-large {
            font-size: 18px;
            color: #333;
            font-weight: 700;
            margin-top: 10px;
            display: block;
        }

        /* --- 8. CART SIDEBAR (NEW) --- */
        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: 0.3s;
        }

        .cart-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 360px;
            height: 100%;
            background: white;
            z-index: 2100;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s cubic-bezier(0.77, 0, 0.175, 1);
            display: flex;
            flex-direction: column;
        }

        .cart-sidebar.active {
            right: 0;
        }

        .cart-header-bar {
            background: #f4f4f4;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .cart-header-title {
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            color: #333;
            flex-grow: 1;
            text-align: center;
        }

        .cart-close-btn {
            font-size: 20px;
            cursor: pointer;
            color: #555;
            margin-left: 10px;
        }

        .cart-body {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }

        /* Empty Cart State */
        .empty-cart-msg {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color: #777;
        }

        /* Cart Items */
        .cart-item {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eee;
            position: relative;
        }

        .cart-item-img {
            width: 70px;
            height: 70px;
            border: 1px solid #eee;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-img img {
            max-width: 100%;
            max-height: 100%;
        }

        .cart-item-info {
            flex: 1;
            font-size: 13px;
        }

        .cart-item-name {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            display: block;
            line-height: 1.3;
        }

        .cart-item-variant {
            display: block;
            color: #777;
            text-transform: uppercase;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: var(--tet-red);
            font-weight: 500;
        }

        .cart-remove-btn {
            position: absolute;
            top: 0;
            right: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1px solid #999;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            color: #555;
            cursor: pointer;
            background: white;
        }

        .cart-remove-btn:hover {
            border-color: var(--tet-red);
            color: var(--tet-red);
        }

        /* Footer */
        .cart-footer-box {
            padding: 15px;
            border-top: 1px solid #eee;
            background: white;
        }

        .cart-total-row {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            font-size: 15px;
            font-weight: 700;
        }

        .cart-total-price {
            color: var(--tet-red);
            margin-left: 5px;
        }

        .cart-btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-view-cart {
            background: var(--tet-red);
            color: white;
            border: none;
            padding: 12px;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 20px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
        }

        .btn-checkout {
            background: var(--blue-btn);
            color: white;
            border: none;
            padding: 12px;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 20px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
        }

        .btn-view-cart:hover,
        .btn-checkout:hover {
            opacity: 0.9;
        }

        @media (max-width: 768px) {

            .detail-layout,
            .similar-grid,
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

            .cart-sidebar {
                width: 300px;
            }
        }
    </style>
</head>

<body>

    <div class="cart-overlay" onclick="toggleCart()"></div>
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header-bar">
            <div style="width: 20px;"></div>
            <div class="cart-header-title">GIỎ HÀNG</div>
            <div class="cart-close-btn" onclick="toggleCart()"><i class="fa-solid fa-xmark"></i></div>
        </div>

        <div class="cart-body" id="cartBody">
            <div class="empty-cart-msg">Chưa có sản phẩm trong giỏ hàng</div>
        </div>

        <div class="cart-footer-box" id="cartFooter">
        </div>
    </div>


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
                <div class="search-box">
                    <form action="<?php echo $this->url('Khachhang'); ?>" method="GET" id="homeSearchForm" autocomplete="off">
                        <input
                            type="text"
                            id="homeSearchInput"
                            name="q"
                            placeholder="Hôm nay bạn muốn tìm kiếm gì?"
                            value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span class="search-btn-text">Tìm kiếm ngay </span>
                        </button>
                        <div class="search-suggest-list" id="homeSearchSuggest"></div>
                    </form>
                </div>

            </div>

            <div class="header-actions">
                <div class="action-item" id="accountBtn">
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        // Kết nối database để lấy avatar
                        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                        if ($conn) {
                            $user_id = $_SESSION['user_id'];
                            $query = "SELECT avatar FROM users WHERE ma_user = '$user_id'";
                            $result = mysqli_query($conn, $query);
                            if ($result && $row = mysqli_fetch_assoc($result)) {
                                if (!empty($row['avatar'])) {
                                    echo '<img src="' . UrlHelper::url('Public/Pictures/users/') . htmlspecialchars($row['avatar']) . '" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px; vertical-align: middle;">';
                                } else {
                                    echo '<i class="fa-regular fa-user" style="vertical-align: middle;"></i>';
                                }
                            } else {
                                echo '<i class="fa-regular fa-user"></i>';
                            }
                            mysqli_close($conn);
                        } else {
                            echo '<i class="fa-regular fa-user"></i>';
                        }
                    } else {
                        echo '<i class="fa-regular fa-user"></i>';
                    }
                    ?>
                    <span>
                        <?php
                        if (isset($_SESSION['user_id'])) {
                            // Kết nối database để lấy full_name
                            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                            if ($conn) {
                                $user_id = $_SESSION['user_id'];
                                $query = "SELECT full_name FROM users WHERE ma_user = '$user_id'";
                                $result = mysqli_query($conn, $query);
                                if ($result && $row = mysqli_fetch_assoc($result)) {
                                    if (!empty($row['full_name'])) {
                                        echo htmlspecialchars($row['full_name']);
                                    } else {
                                        // Nếu không có full_name, hiển thị user_name
                                        echo htmlspecialchars($_SESSION['user_name']);
                                    }
                                } else {
                                    echo htmlspecialchars($_SESSION['user_name']);
                                }
                                mysqli_close($conn);
                            } else {
                                echo htmlspecialchars($_SESSION['user_name']);
                            }
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
                                <a href="<?php echo UrlHelper::url('Login/logout'); ?>" class="js-api-logout" data-redirect="<?php echo UrlHelper::url('Home'); ?>" style="color: #d70018;"><i
                                    class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                        <?php else: ?>
                            <a href="<?php echo UrlHelper::url('Login'); ?>"><i class="fa-solid fa-user"></i> Đăng nhập</a>
                            <a href="<?php echo UrlHelper::url('Login/register'); ?>"><i class="fa-solid fa-user-plus"></i>
                                Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?php echo UrlHelper::url('Khachhang/giohang'); ?>" class="action-item cart-icon-wrap">
                    <i class="fa-solid fa-cart-shopping"></i><span>Giỏ hàng</span>
                    <span class="cart-badge" id="cartBadge">
                        0
                    </span>
                </a>
            </div>
        </div>
    </header>

    <div class="breadcrumb">
        <div class="container">
            <a href="<?php echo $this->url('Khachhang'); ?>">Trang chủ</a> /
            <?php
            // Xác định trang hiện tại dựa trên tên view được truyền vào
            $currentPage = '';
            if (isset($data['page'])) {
                $pageName = basename($data['page'], '.php');

                switch ($pageName) {
                    case 'khachhang_home':
                        $currentPage = 'Trang chủ';
                        break;
                    case 'khachhang_sanpham':
                        $currentPage = 'Danh sách sản phẩm';
                        break;
                    case 'khachhang_chitietsp':
                        $currentPage = 'Chi tiết sản phẩm';
                        break;
                    case 'khachhang_giohang':
                        $currentPage = 'Giỏ hàng';
                        break;
                    case 'khachhang_thanhtoan':
                        $currentPage = 'Thanh toán';
                        break;
                    case 'khachhang_lichsu':
                        $currentPage = 'Lịch sử mua hàng';
                        break;
                    case 'khachhang_chitietdh':
                        $currentPage = 'Chi tiết đơn hàng';
                        break;
                    case 'khachhang_taikhoan':
                        $currentPage = 'Quản lý tài khoản';
                        break;
                    case 'khachhang_camon':
                        $currentPage = 'Thanh toán thành công';
                        break;
                    case 'khachhang_thanhtoan_thatbai':
                        $currentPage = 'Thanh toán thất bại';
                        break;
                    default:
                        $currentPage = ucfirst(str_replace('khachhang_', '', $pageName));
                        $currentPage = str_replace('_', ' ', $currentPage);
                        break;
                }
            }

            if (isset($data['breadcrumb'])):
                echo $data['breadcrumb'];
            else:
                echo '<span>' . $currentPage . '</span>';
            endif;
            ?>
        </div>
    </div>

    <!-- Load the specific page content -->
    <?php
    if (isset($data['page'])) {
        $pagePath = __DIR__ . '/Pages/' . $data['page'] . '.php';
        if (file_exists($pagePath)) {
            include_once $pagePath;
        } else {
            echo "<div class='alert alert-danger'>Trang không tồn tại!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Không có nội dung để hiển thị!</div>";
    }
    ?>
        <?php include_once __DIR__ . '/Partials/techzone_chatbot.php'; ?>

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

    <script src="<?php echo UrlHelper::url('Public/Js/form-required-validator.js?v=1'); ?>"></script>
    <script>
        // Lấy các phần tử (dùng tên biến riêng để tránh trùng với script từng page)
        const khMasterAccountBtn = document.getElementById('accountBtn');
        const khMasterAccountMenu = document.getElementById('accountMenu');

        // Toggle Account Menu
        if (khMasterAccountBtn && khMasterAccountMenu) {
            khMasterAccountBtn.addEventListener('click', function(event) {
                event.stopPropagation();
                khMasterAccountMenu.classList.toggle('active');
            });

            // Close when clicking outside
            window.addEventListener('click', function(event) {
                if (!khMasterAccountBtn.contains(event.target)) {
                    khMasterAccountMenu.classList.remove('active');
                }
            });
        }

        document.addEventListener('click', function(event) {
            var logoutLink = event.target.closest('.js-api-logout');
            if (!logoutLink) {
                return;
            }

            event.preventDefault();
            var redirectUrl = logoutLink.getAttribute('data-redirect') || '<?php echo UrlHelper::url('Home'); ?>';

            fetch('<?php echo UrlHelper::url('Api/Auth/logout'); ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(function() {
                window.location.href = redirectUrl;
            })
            .catch(function() {
                window.location.href = logoutLink.getAttribute('href') || redirectUrl;
            });
        });

        // --- 2. XỬ LÝ GIAO DIỆN CƠ BẢN ---
        var thumbs = document.querySelectorAll('.thumb-item');
        thumbs.forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                thumbs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                var newSrc = this.getAttribute('data-image');
                document.getElementById('mainImage').src = newSrc;
            });
        });

        function decreaseQuantity() {
            var input = document.getElementById('quantityInput');
            var value = parseInt(input.value);
            if (value > 1) input.value = value - 1;
        }

        function increaseQuantity() {
            var input = document.getElementById('quantityInput');
            var value = parseInt(input.value);
            input.value = value + 1;
        }

        // --- 3. MINI CART LOGIC (REST API) ---
        var CART_API_BASE = "<?php echo $this->url('Api/Cart'); ?>";

        // Hàm mở/đóng Sidebar
        function toggleCart() {
            var overlay = document.querySelector('.cart-overlay');
            var sidebar = document.querySelector('.cart-sidebar');
            if (!overlay || !sidebar) {
                return;
            }
            overlay.classList.toggle('active');
            sidebar.classList.toggle('active');

            if (sidebar.classList.contains('active')) {
                loadMiniCartFromApi();
            }
        }

        function loadMiniCartFromApi() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', CART_API_BASE, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) {
                    return;
                }

                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var items = response && response.data ? (response.data.items || []) : [];
                    var summary = response && response.data ? (response.data.summary || {}) : {};

                    renderMiniCart(items, summary);
                    updateCartBadge(summary.total_quantity || 0);
                } else if (xhr.status === 401) {
                    renderMiniCart([], { total_quantity: 0, subtotal: 0 });
                    updateCartBadge(0);
                }
            };
            xhr.send();
        }

        function updateCartBadge(totalQty) {
            var cartBadge = document.getElementById('cartBadge');
            if (cartBadge) {
                cartBadge.innerText = parseInt(totalQty || 0, 10);
            }
        }

        function removeMiniCartItem(maBienThe) {
            if (!maBienThe) {
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', CART_API_BASE + '/' + encodeURIComponent(maBienThe), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status >= 200 && xhr.status < 300) {
                    loadMiniCartFromApi();
                }
            };
            xhr.send();
        }

        function renderMiniCart(items, summary) {
            var cartBody = document.getElementById('cartBody');
            var cartFooter = document.getElementById('cartFooter');
            if (!cartBody || !cartFooter) {
                return;
            }

            var totalPrice = Number(summary && summary.subtotal ? summary.subtotal : 0);

            if (!items || items.length === 0) {
                cartBody.innerHTML = '<div class="empty-cart-msg">Chưa có sản phẩm trong giỏ hàng</div>';
                cartFooter.innerHTML = '';
                return;
            }

            var html = '';
            items.forEach(function(item) {
                var price = Number(item.price || item.gia || 0);
                var quantity = Number(item.quantity || item.so_luong || 0);
                var name = item.name || item.ten_san_pham || '';
                var variant = item.variant || item.variant_name || '';

                html += `
                <div class="cart-item">
                    <div class="cart-item-img">
                        <img src="${item.img || ''}" alt="">
                    </div>
                    <div class="cart-item-info">
                        <span class="cart-item-name">${name}</span>
                        <span class="cart-item-variant">${variant}</span>
                        <div class="cart-item-price">${quantity} x ${price.toLocaleString('vi-VN')} ₫</div>
                    </div>
                    <div class="cart-remove-btn" onclick="removeMiniCartItem('${item.ma_bien_the || ''}')">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                `;
            });
            cartBody.innerHTML = html;

            cartFooter.innerHTML = `
                <div class="cart-total-row">
                    Tổng số phụ: <span class="cart-total-price">${totalPrice.toLocaleString('vi-VN')} ₫</span>
                </div>
                <div class="cart-btn-group">
                    <button class="btn-view-cart" onclick="location.href='<?php echo $this->url('Khachhang/giohang'); ?>'">XEM GIỎ HÀNG</button>
                    <button class="btn-checkout" onclick="location.href='<?php echo $this->url('Khachhang/thanhtoan'); ?>'">THANH TOÁN</button>
                </div>
            `;
        }

        // Helper function to format currency
        function formatCurrency(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Trang gio hang da tu goi GET /Api/Cart de render bang,
        // nen bo qua auto-load o master de tranh request trung lap.
        var khIsCartPage = <?php echo (isset($data['page']) && $data['page'] === 'Khachhang/khachhang_giohang') ? 'true' : 'false'; ?>;
        var khSearchSuggestApi = '<?php echo UrlHelper::url('Api/Search/suggestions'); ?>';
        var khSearchHistoryApi = '<?php echo UrlHelper::url('Api/Search/history'); ?>';
        var khSearchSaveApi = '<?php echo UrlHelper::url('Api/Search'); ?>';
        var khSearchHomeUrl = '<?php echo UrlHelper::url('Khachhang'); ?>';

        function khEscapeHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function khCloseSearchSuggestions() {
            var box = document.getElementById('homeSearchSuggest');
            if (!box) {
                return;
            }
            box.style.display = 'none';
            box.innerHTML = '';
        }

        function khRenderSearchSuggestions(items, keyword) {
            var box = document.getElementById('homeSearchSuggest');
            if (!box) {
                return;
            }

            if (!items || items.length === 0) {
                box.innerHTML = '<div class="search-suggest-empty">Không có gợi ý cho "' + khEscapeHtml(keyword) + '"</div>';
                box.style.display = 'block';
                return;
            }

            var html = '';
            var baseUrl = '<?php echo UrlHelper::url(); ?>';

            for (var i = 0; i < items.length; i++) {
                var item = items[i] || {};
                var productId = item.ma_san_pham || '';
                var productName = item.ten_san_pham || '';
                var img = item.img_bien_the ? (baseUrl + 'Public/Pictures/bien_the/' + encodeURIComponent(item.img_bien_the)) : (baseUrl + 'Public/Images/no-image.png');
                var href = '<?php echo UrlHelper::url('Khachhang/chitietsanpham/'); ?>' + productId;

                html += '<a class="search-suggest-item" href="' + href + '">'
                    + '<img class="search-suggest-thumb" src="' + img + '" alt="">'
                    + '<span class="search-suggest-name">' + khEscapeHtml(productName) + '</span>'
                    + '</a>';
            }

            box.innerHTML = html;
            box.style.display = 'block';
        }

        function khRenderSearchHistory(historyItems) {
            var box = document.getElementById('homeSearchSuggest');
            if (!box) {
                return;
            }

            if (!historyItems || historyItems.length === 0) {
                box.innerHTML = '<div class="search-suggest-empty">Chưa có lịch sử tìm kiếm</div>';
                box.style.display = 'block';
                return;
            }

            var html = '<div class="search-suggest-section">Tìm kiếm gần đây</div>';

            for (var i = 0; i < historyItems.length; i++) {
                var keyword = (historyItems[i] && historyItems[i].keyword) ? String(historyItems[i].keyword).trim() : '';
                if (!keyword) {
                    continue;
                }

                var params = new URLSearchParams();
                params.set('q', keyword);
                params.set('page', '1');

                html += '<a class="search-suggest-history-item" href="' + khSearchHomeUrl + '?' + params.toString() + '">'
                    + '<i class="fa-solid fa-clock-rotate-left"></i>'
                    + '<span>' + khEscapeHtml(keyword) + '</span>'
                    + '</a>';
            }

            box.innerHTML = html;
            box.style.display = 'block';
        }

        function khLoadSearchHistory() {
            return fetch(khSearchHistoryApi, { method: 'GET' })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Failed to fetch history');
                    }
                    return response.json();
                })
                .then(function(json) {
                    if (!json || !json.success) {
                        khRenderSearchHistory([]);
                        return;
                    }
                    khRenderSearchHistory(Array.isArray(json.data) ? json.data : []);
                })
                .catch(function() {
                    khRenderSearchHistory([]);
                });
        }

        function khSaveSearchKeyword(keyword) {
            return fetch(khSearchSaveApi, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        keyword: keyword
                    }),
                    keepalive: true
                })
                .then(function() {
                    return true;
                })
                .catch(function() {
                    return false;
                });
        }

        function khInitSearchRestFlow() {
            var form = document.getElementById('homeSearchForm');
            var input = document.getElementById('homeSearchInput');
            var suggestBox = document.getElementById('homeSearchSuggest');

            if (!form || !input || !suggestBox) {
                return;
            }

            var debounceTimer = null;
            var requestCounter = 0;

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var keyword = (input.value || '').trim();
                if (!keyword) {
                    window.location.href = khSearchHomeUrl;
                    return;
                }

                khSaveSearchKeyword(keyword).finally(function() {
                    var params = new URLSearchParams();
                    params.set('q', keyword);
                    params.set('page', '1');
                    window.location.href = khSearchHomeUrl + '?' + params.toString();
                });
            });

            input.addEventListener('input', function() {
                var keyword = (input.value || '').trim();
                if (debounceTimer) {
                    clearTimeout(debounceTimer);
                }

                if (!keyword || keyword.length < 2) {
                    if (!keyword) {
                        khLoadSearchHistory();
                    } else {
                        khCloseSearchSuggestions();
                    }
                    return;
                }

                debounceTimer = setTimeout(function() {
                    requestCounter += 1;
                    var currentReq = requestCounter;
                    var url = khSearchSuggestApi + '?q=' + encodeURIComponent(keyword) + '&limit=8';

                    fetch(url, { method: 'GET' })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('Failed to fetch suggestions');
                            }
                            return response.json();
                        })
                        .then(function(json) {
                            if (currentReq !== requestCounter) {
                                return;
                            }

                            if (!json || !json.success) {
                                khCloseSearchSuggestions();
                                return;
                            }

                            khRenderSearchSuggestions(json.data || [], keyword);
                        })
                        .catch(function() {
                            khCloseSearchSuggestions();
                        });
                }, 220);
            });

            input.addEventListener('focus', function() {
                var keyword = (input.value || '').trim();
                if (!keyword) {
                    khLoadSearchHistory();
                    return;
                }

                if (keyword.length >= 2 && suggestBox.innerHTML.trim() !== '') {
                    suggestBox.style.display = 'block';
                }
            });

            document.addEventListener('click', function(e) {
                if (!form.contains(e.target)) {
                    khCloseSearchSuggestions();
                }
            });
        }

        // Đồng bộ badge ngay khi tải layout
        document.addEventListener('DOMContentLoaded', function() {
            khInitSearchRestFlow();
            if (!khIsCartPage) {
                loadMiniCartFromApi();
            }
        });
    </script>
</body>

</html>
