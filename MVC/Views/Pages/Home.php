<?php
include_once __DIR__ . '/../../../Public/Classes/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AntTech - Điện thoại & Laptop Chính Hãng</title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
    /* --- 1. CORE VARIABLES & RESET --- */
    :root {
        --primary-black: #000000;
        --text-gray: #888;
        --tech-blue: #0071e3;
        --sale-red: #d70018;
        --header-height: 80px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Be Vietnam Pro', sans-serif;
    }

    body {
        background-color: #f5f5f7;
        overflow-x: hidden;
    }

    button {
        cursor: pointer;
        border: none;
        outline: none;
        background: none;
    }

    /* --- 2. HEADER SECTION --- */
    header {
        height: var(--header-height);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 40px;
        background: rgba(255, 255, 255, 0.95);
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 0px #eee;
        backdrop-filter: blur(10px);
    }

    .logo {
        font-weight: 800;
        font-size: 24px;
        letter-spacing: -0.5px;
    }

    .logo span {
        color: var(--tech-blue);
    }

    .nav-menu {
        display: flex;
        gap: 30px;
        align-items: center;
    }

    .nav-item {
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        position: relative;
        color: #333;
    }

    .nav-item:hover {
        color: var(--tech-blue);
    }

    .sale-tag {
        color: var(--sale-red);
        font-weight: 700;
        position: relative;
    }

    .badge-discount {
        position: absolute;
        top: -15px;
        right: -25px;
        font-size: 10px;
        background: var(--sale-red);
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .search-bar {
        background: #f5f5f7;
        border-radius: 30px;
        padding: 8px 15px;
        display: flex;
        align-items: center;
        width: 250px;
    }

    .search-bar input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 13px;
        flex: 1;
        margin-left: 10px;
    }

    .search-icon {
        width: 16px;
        opacity: 0.5;
    }

    /* Nút Action */
    .action-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 24px;
        background: white;
        transition: all 0.2s;
        height: 40px;
    }

    .action-btn:hover {
        border-color: var(--primary-black);
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .btn-text {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .icon-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .icon-svg {
        width: 20px;
        height: 20px;
    }

    .cart-count-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #f59e0b;
        color: white;
        font-size: 10px;
        font-weight: 700;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 2px solid white;
    }

    /* --- 3. SLIDER / HERO SECTION --- */
    .hero-banner {
        position: relative;
        width: 100%;
        height: 600px;
        overflow: hidden;
        background-color: #000;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .slide.active {
        opacity: 1;
        z-index: 10;
    }

    /* Background Gradient */
    .slide-1 {
        background: radial-gradient(circle, #2c2c2e 0%, #000000 100%);
    }

    .slide-2 {
        background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
    }

    .slide-3 {
        background: linear-gradient(to right, #141e30, #243b55);
    }

    .banner-content {
        display: flex;
        width: 100%;
        max-width: 1200px;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        position: relative;
        z-index: 20;
        height: 100%;
    }

    .text-light {
        color: white;
    }

    .text-dark {
        color: #1d1d1f;
    }

    .banner-text {
        width: 50%;
        padding-left: 20px;
        z-index: 2;
    }

    .slide .banner-text {
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.8s ease-out 0.3s;
    }

    .slide.active .banner-text {
        transform: translateY(0);
        opacity: 1;
    }

    .banner-text h1 {
        font-size: 100px;
        line-height: 1;
        font-weight: 900;
        opacity: 0.1;
        position: absolute;
        left: -20px;
        top: 40%;
        transform: translateY(-50%);
        white-space: nowrap;
        pointer-events: none;
    }

    .banner-text h2 {
        font-size: 50px;
        font-weight: 800;
        margin-bottom: 15px;
        position: relative;
        line-height: 1.1;
    }

    .banner-text p {
        font-size: 18px;
        margin-bottom: 30px;
        font-weight: 400;
        max-width: 500px;
    }

    .tag-promo {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .tag-light {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: white;
    }

    .tag-dark {
        background: rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.2);
        color: #333;
    }

    .cta-btn {
        padding: 14px 35px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 15px;
        display: inline-block;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .cta-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-primary {
        background: var(--tech-blue);
        color: white;
    }

    .btn-dark {
        background: #1d1d1f;
        color: white;
    }

    /* Banner Image */
    .banner-image {
        width: 50%;
        height: 70%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        transition: transform 0.8s ease-out;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .slide.active .banner-image {
        transform: scale(1.05);
    }

    /* Navigation Buttons */
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        cursor: pointer;
        z-index: 100;
        transition: 0.3s;
        border: none;
    }

    .slider-btn.light {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .slider-btn.light:hover {
        background: white;
        color: black;
    }

    .slider-btn.dark {
        background: rgba(0, 0, 0, 0.1);
        color: #333;
    }

    .slider-btn.dark:hover {
        background: #333;
        color: white;
    }

    .prev-btn {
        left: 30px;
    }

    .next-btn {
        right: 30px;
    }

    /* --- 4. MODAL STYLES (DÙNG CHUNG CHO CẢ 2 FORM) --- */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .login-container {
        background: white;
        width: 420px;
        padding: 40px;
        border-radius: 16px;
        position: relative;
        animation: slideIn 0.3s ease;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    @keyframes slideIn {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 24px;
        cursor: pointer;
        color: #999;
    }

    .login-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
    }

    .input-group input {
        width: 100%;
        padding: 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
        font-size: 14px;
    }

    .input-group input:focus {
        border-color: var(--tech-blue);
        box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.1);
    }

    .password-wrapper {
        position: relative;
    }

    .eye-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 16px;
        color: #888;
    }

    .options {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        margin-bottom: 20px;
        color: #666;
    }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: #000;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        font-size: 15px;
    }

    .btn-submit:hover {
        opacity: 0.8;
    }

    .modal-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #666;
    }

    .modal-footer a {
        color: var(--tech-blue);
        font-weight: 700;
        text-decoration: none;
    }

    .back-nav {
        margin-top: 25px;
        text-align: center;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .back-nav a {
        font-size: 14px;
        color: #666;
        text-decoration: none;
    }

    /* --- 7. FOOTER --- */
    .main-footer {
        border-top: 4px solid #f4f4f4;
        padding: 40px 0 20px;
        margin-top: 0;
        background: white;
    }

    .main-footer .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
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
        list-style: none;
    }

    .footer-links li a {
        font-size: 13px;
        color: #555;
        text-decoration: none;
        display: block;
    }

    .footer-links li a:hover {
        color: var(--tet-red);
        text-decoration: underline;
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


    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>

<body>

    <header>
        <div class="logo">ANT<span>TECH</span></div>
        <ul class="nav-menu">
            <li class="nav-item">Hot New Mobile</li>
            <li class="nav-item">BÁN CHẠY NHAT</li>
            <li class="nav-item sale-tag">KHUYẾN MÃI SẬP SÀN <span class="badge-discount">siêu hot</span></li>
        </ul>
        <div class="header-actions">
            <div class="search-bar">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" placeholder="Tìm kiếm">
            </div>

            <button class="action-btn" id="btnCart">
                <span class="btn-text">Giỏ hàng</span>
                <div class="icon-box">
                    <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span class="cart-count-badge">0</span>
                </div>
            </button>

            <button class="action-btn" id="btnOpenLogin">
                <span class="btn-text">Đăng nhập</span>
                <div class="icon-box">
                    <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
            </button>

            <button class="action-btn" id="btnOpenRegister">
                <span class="btn-text">Đăng ký</span>
                <div class="icon-box">
                    <svg class="icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
            </button>
        </div>
    </header>

    <section class="hero-banner">
        <div class="slide slide-1 active">
            <div class="banner-content text-light">
                <div class="banner-text">
                    <h1>TITAN</h1>
                    <div class="tag-promo tag-light">PRE-ORDER NOW</div>
                    <h2>IPHONE 17 PRO MAX</h2>
                    <p>Thiết kế Titan Ultra hoàn toàn mới. Chip A19 Bionic đỉnh cao. Camera AI 100MP thế hệ mới.</p>
                    <a href="#" class="cta-btn btn-primary">ĐẶT TRƯỚC NGAY</a>
                </div>
                <div class="banner-image"
                    style="background-image: url('https://tse2.mm.bing.net/th/id/OIP.pEZyO8D2oWi6Jft18J2wHAHaJQ?rs=1&pid=ImgDetMain&o=7&rm=3');">
                </div>
            </div>
            <button class="slider-btn prev-btn light" onclick="moveSlide(-1)">&#10094;</button>
            <button class="slider-btn next-btn light" onclick="moveSlide(1)">&#10095;</button>
        </div>

        <div class="slide slide-2">
            <div class="banner-content text-dark">
                <div class="banner-text">
                    <h1>MACBOOK</h1>
                    <div class="tag-promo tag-dark">BACK TO SCHOOL</div>
                    <h2>MACBOOK AIR M3</h2>
                    <p>Siêu mỏng nhẹ. Hiệu năng M3 vượt trội. Thời lượng pin cả ngày dài.</p>
                    <a href="#" class="cta-btn btn-dark">XEM ƯU ĐÃI SV</a>
                </div>
                <div class="banner-image"
                    style="background-image: url('https://store.storeimages.cdn-apple.com/8756/as-images.apple.com/is/macbook-air-midnight-select-20220606?wid=904&hei=840&fmt=jpeg&qlt=90&.v=1653084303665');">
                </div>
            </div>
            <button class="slider-btn prev-btn dark" onclick="moveSlide(-1)">&#10094;</button>
            <button class="slider-btn next-btn dark" onclick="moveSlide(1)">&#10095;</button>
        </div>

        <div class="slide slide-3">
            <div class="banner-content text-light">
                <div class="banner-text">
                    <h1>GALAXY</h1>
                    <div class="tag-promo tag-light">COMING SOON</div>
                    <h2>SAMSUNG GALAXY S25 ULTRA</h2>
                    <p>Kỷ nguyên Galaxy AI mới. Khung viền Titan. Camera Mắt Thần Bóng Đêm 200MP.</p>
                    <a href="#" class="cta-btn btn-primary">ĐĂNG KÝ NHẬN TIN</a>
                </div>
                <div class="banner-image"
                    style="background-image: url('https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?q=80&w=800&auto=format&fit=crop');">
                </div>
            </div>
            <button class="slider-btn prev-btn light" onclick="moveSlide(-1)">&#10094;</button>
            <button class="slider-btn next-btn light" onclick="moveSlide(1)">&#10095;</button>
        </div>
    </section>

    <div class="modal-overlay" id="loginModal">
        <div class="login-container">
            <span class="close-btn" id="btnCloseLogin">&times;</span>
            <div class="login-title">ĐĂNG NHẬP</div>
            <form id="loginForm" method="post" action="<?php echo UrlHelper::url('Api/Auth/login'); ?>">
                <div class="input-group">
                    <label>Tài khoản</label>
                    <input type="text" name="username" placeholder="Nhập tài khoản" data-required="true">
                </div>
                <div class="input-group">
                    <label>MẬT KHẨU</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="loginPasswordInput" placeholder="Nhập mật khẩu"
                            data-required="true">
                        <span class="eye-icon" id="loginTogglePass">👁</span>
                    </div>
                </div>
                <div class="options">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Ghi nhớ đăng nhập</label>
                </div>
                <button type="submit" class="btn-submit">ĐĂNG NHẬP</button>

                <?php if (isset($_SESSION['error'])): ?>
                <div class="error" style="color: red; margin-top: 10px; text-align: center;">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']);
                endif; ?>
            </form>
            <div class="modal-footer">
                Chưa có tài khoản?
                <a href="#" id="linkToRegister">Đăng ký ngay</a>
            </div>
            <div class="back-nav"><a href="#" class="linkBack"> ← Tiếp tục mua sắm</a></div>
        </div>
    </div>

    <div class="modal-overlay" id="registerModal">
        <div class="login-container">
            <span class="close-btn" id="btnCloseRegister">&times;</span>
            <div class="login-title">ĐĂNG KÝ</div>
            <form id="registerForm" method="post" action="<?php echo UrlHelper::url('Api/Auth/register'); ?>">
                <div class="input-group">
                    <label>Họ và tên</label>
                    <input type="text" name="fullname" placeholder="Nhập họ và tên" data-required="true"
                        value="<?php echo isset($_SESSION['form_data']['full_name']) ? htmlspecialchars($_SESSION['form_data']['full_name']) : ''; ?>">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Ví dụ: tennguoidung@gmail.com" data-required="true"
                        value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                </div>
                <div class="input-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="phone" placeholder="Ví dụ: 0389783612" data-required="true"
                        pattern="0[0-9]{9}" maxlength="10" inputmode="numeric"
                        title="Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0"
                        value="<?php echo isset($_SESSION['form_data']['phone']) ? htmlspecialchars($_SESSION['form_data']['phone']) : ''; ?>">
                </div>
                <div class="input-group">
                    <label>Tài khoản</label>
                    <input type="text" name="username" placeholder="Nhập tài khoản" data-required="true"
                        value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>">
                </div>

                <div class="input-group">
                    <label>MẬT KHẨU</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="regPasswordInput" placeholder="Nhập mật khẩu"
                            data-required="true">
                        <span class="eye-icon" id="regTogglePass">👁</span>
                    </div>
                </div>
                <div class="input-group">
                    <label>NHẬP LẠI MẬT KHẨU</label>
                    <div class="password-wrapper">
                        <input type="password" name="confirm_password" id="regConfirmPasswordInput"
                            placeholder="Nhập lại mật khẩu" data-required="true">
                        <span class="eye-icon" id="regToggleConfirmPass">👁</span>
                    </div>
                </div>
                <button type="submit" class="btn-submit">ĐĂNG KÝ</button>

                <?php if (isset($_SESSION['error'])): ?>
                <div class="error" style="color: red; margin-top: 10px; text-align: center;">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']);
                endif; ?>
            </form>
            <div class="modal-footer">
                Đã có tài khoản?
                <a href="#" id="linkToLogin">Đăng nhập ngay</a>
            </div>
            <div class="back-nav"><a href="#" class="linkBack"> ← Tiếp tục mua sắm</a></div>
        </div>
    </div>
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">

                <div class="footer-col">
                    <div class="footer-logo">
                        <i class="fa-brands fa-instalod"></i> AntTech
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
                                <a href="#" class="fp-name">AntTech - Chính Chủ</a>
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
                        <p style="margin-top: 15px; display: inline-block;">Tư vấn miễn phí 24/07 : </p>
                        <span class="hotline-large" style="display: inline-block;">0825.303.888</span>

                    </div>
                    <div class="certification-badge" style="margin-top: 20px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5558.299033261625!2d105.8153926!3d20.995307299999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac91bbe794f9%3A0x8a803e39f1952d15!2zMjIxIFAuIFbFqSBUw7RuZyBQaGFuLCBLaMawxqFuZyBUcnVuZywgVGhhbmggWHXDom4sIEjDoCBO4buZaSAxMDAwMDAsIFZp4buHdCBOYW0!5e1!3m2!1svi!2s!4v1769956976377!5m2!1svi!2s"
                            width="250" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>>
                    </div>

                </div>
            </div>
    </footer>


    <script>
    // --- 1. SLIDER LOGIC ---
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;
    let slideInterval;

    function showSlide(index) {
        if (index >= totalSlides) currentSlide = 0;
        else if (index < 0) currentSlide = totalSlides - 1;
        else currentSlide = index;
        slides.forEach(slide => slide.classList.remove('active'));
        slides[currentSlide].classList.add('active');
    }

    function moveSlide(direction) {
        showSlide(currentSlide + direction);
        resetTimer();
    }

    function startTimer() {
        slideInterval = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 3000);
    }

    function resetTimer() {
        clearInterval(slideInterval);
        startTimer();
    }
    startTimer();

    // --- 2. MODAL LOGIC (NÂNG CẤP) ---
    // Các nút mở Modal
    const btnOpenLogin = document.getElementById('btnOpenLogin');
    const btnOpenRegister = document.getElementById('btnOpenRegister');
    const btnCart = document.getElementById('btnCart');
    const ctaButtons = document.querySelectorAll('.cta-btn');

    // Các Modal
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');

    // Các nút đóng/chuyển đổi
    const btnCloseLogin = document.getElementById('btnCloseLogin');
    const btnCloseRegister = document.getElementById('btnCloseRegister');
    const linkToRegister = document.getElementById('linkToRegister');
    const linkToLogin = document.getElementById('linkToLogin');
    const linkBacks = document.querySelectorAll('.linkBack');

    // Hàm tiện ích: Mở/Đóng Modal
    function showModal(modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hideModal(modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // --- SỰ KIỆN MỞ MODAL ---
    // 1. Nút Đăng nhập -> Mở Form Đăng nhập
    if (btnOpenLogin) btnOpenLogin.addEventListener('click', () => showModal(loginModal));

    // 2. Nút Giỏ hàng & Các nút CTA trên Banner -> Mở Form Đăng nhập (Logic cũ)
    if (btnCart) btnCart.addEventListener('click', () => showModal(loginModal));
    ctaButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            showModal(loginModal);
        });
    });

    // 3. Nút Đăng ký (Header) -> Mở Form Đăng ký
    if (btnOpenRegister) btnOpenRegister.addEventListener('click', () => showModal(registerModal));

    // --- SỰ KIỆN CHUYỂN ĐỔI FORM ---
    // 4. Từ Login -> Register
    linkToRegister.addEventListener('click', (e) => {
        e.preventDefault();
        hideModal(loginModal);
        showModal(registerModal);
    });

    // 5. Từ Register -> Login
    linkToLogin.addEventListener('click', (e) => {
        e.preventDefault();
        hideModal(registerModal);
        showModal(loginModal);
    });

    // --- SỰ KIỆN ĐÓNG MODAL ---
    // Nút X
    btnCloseLogin.addEventListener('click', () => hideModal(loginModal));
    btnCloseRegister.addEventListener('click', () => hideModal(registerModal));

    // Nút "Tiếp tục mua sắm"
    linkBacks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            hideModal(loginModal);
            hideModal(registerModal);
        });
    });

    // Click ra ngoài vùng đen
    window.addEventListener('click', (e) => {
        if (e.target === loginModal) hideModal(loginModal);
        if (e.target === registerModal) hideModal(registerModal);
    });

    // --- LOGIC ẨN/HIỆN MẬT KHẨU (Cho cả 2 form) ---
    function setupPasswordToggle(inputId, toggleId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        if (input && toggle) {
            toggle.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                toggle.textContent = type === 'password' ? '👁' : '🙈';
            });
        }
    }
    setupPasswordToggle('loginPasswordInput', 'loginTogglePass');
    setupPasswordToggle('regPasswordInput', 'regTogglePass');
    setupPasswordToggle('regConfirmPasswordInput', 'regToggleConfirmPass');

    const registerForm = document.getElementById('registerForm');

    function clearRegisterFeedback() {
        registerForm.querySelector('.error')?.remove();
        registerForm.querySelector('.success')?.remove();
    }

    registerForm.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', clearRegisterFeedback);
        input.addEventListener('invalid', clearRegisterFeedback);
    });

    // Submit Forms with AJAX to maintain modal experience
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent normal form submission

        // Get form data
        const formData = new FormData(this);

        // Get the URL
        const loginUrl = '<?php echo UrlHelper::url('Api/Auth/login'); ?>';

        // Debug log
        console.log('=== LOGIN DEBUG ===');
        console.log('Login URL:', loginUrl);
        console.log('Form data:', Object.fromEntries(formData));

        // Send AJAX request
        fetch(loginUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('Server trả về dữ liệu không hợp lệ');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    // Close the modal and redirect based on user role
                    hideModal(document.getElementById('loginModal'));
                    window.location.href = data.redirect;
                } else {
                    // Display error message
                    document.querySelector('#loginModal .error')?.remove();
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error';
                    errorDiv.style.cssText = 'color: red; margin-top: 10px; text-align: center;';
                    errorDiv.textContent = data.error || data.message || 'Đăng nhập thất bại!';
                    document.querySelector('#loginForm').appendChild(errorDiv);
                }
            })
            .catch(error => {
                // Chỉ log lỗi, không alert popup
                console.error('Login Error:', error);
            });
    });

    registerForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent normal form submission

        // Get form data
        const formData = new FormData(this);
        const form = this;
        clearRegisterFeedback();

        // Send AJAX request
        fetch('<?php echo UrlHelper::url('Api/Auth/register'); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                let data;

                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    const text = await response.text();
                    console.log('Server response (non-JSON):', text);
                    const jsonStart = text.indexOf('{');
                    const jsonEnd = text.lastIndexOf('}') + 1;
                    if (jsonStart !== -1 && jsonEnd !== 0) {
                        const jsonString = text.substring(jsonStart, jsonEnd);
                        data = JSON.parse(jsonString);
                    } else {
                        throw new Error('Server returned invalid response format');
                    }
                }

                if (!response.ok) {
                    const message = data.error || data.message ||
                        `HTTP error! status: ${response.status}`;
                    throw new Error(message);
                }

                return data;
            })
            .then(data => {
                clearRegisterFeedback();

                if (data.success) {
                    const successDiv = document.createElement('div');
                    successDiv.className = 'success';
                    successDiv.style.cssText = 'color: green; margin-top: 10px; text-align: center;';
                    successDiv.textContent = data.message || 'Đăng ký thành công!';
                    form.appendChild(successDiv);

                    setTimeout(() => {
                        hideModal(document.getElementById('registerModal'));
                        showModal(document.getElementById('loginModal'));
                    }, 800);
                } else {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error';
                    errorDiv.style.cssText = 'color: red; margin-top: 10px; text-align: center;';
                    errorDiv.textContent = data.error || data.message || 'Đăng ký thất bại!';
                    form.appendChild(errorDiv);
                }
            })
            .catch(error => {
                console.error('Registration Error:', error);
                clearRegisterFeedback();
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error';
                errorDiv.style.cssText = 'color: red; margin-top: 10px; text-align: center;';
                errorDiv.textContent = error.message || 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.';
                form.appendChild(errorDiv);
            });
    });
    </script>
</body>

</html>