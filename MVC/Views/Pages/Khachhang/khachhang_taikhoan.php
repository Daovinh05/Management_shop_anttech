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
    <title>Tài khoản của tôi - TechZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        /* --- 1. CORE VARIABLES & RESET (Giữ nguyên của bạn) --- */
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

        /* --- 2. TOP BANNER (Giữ nguyên) --- */
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

        /* --- 3. MAIN HEADER (Giữ nguyên) --- */
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


        /* --- 4. PROFILE PAGE STYLES (NEW - CLONE COOLMATE) --- */
        .profile-wrapper {
            padding: 40px 0;
        }

        /* Header Profile Card */
        .profile-header-card {
            background: rgb(236, 233, 233);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .welcome-text h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #000;
        }

        .member-date {
            color: #666;
            font-size: 14px;
        }

        .mascot-img {
            width: 20px;
            height: auto;
            opacity: 0.8;
        }

        .mascot-img2 {
            width: 100px;
            height: auto;
            opacity: 0.8;
            margin-right: 100px;
        }

        /* Grid Layout 2 Cột */
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        /* Info Cards */
        .info-card {
            background: rgb(236, 233, 233);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #000;
        }

        .info-row {
            display: flex;
            margin-bottom: 20px;
            align-items: center;
        }

        .info-label {
            width: 140px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .info-value {
            flex: 1;
            font-weight: 700;
            color: #000;
            font-size: 14px;
        }

        /* Input Readonly */
        .readonly-input {
            background-color: #e0e0e0;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            width: 100%;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        /* Buttons */
        .btn-black-outline {
            margin-top: auto;
            align-self: flex-start;
            background: white;
            border: 1px solid #000;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: 0.2s;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-black-outline:hover {
            background: #000;
            color: white;
        }

        /* --- 5. MODAL STYLES (CLONE ẢNH 2) --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 3000;
            /* Z-index cao hơn Cart Sidebar */
            display: none;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
            animation: fadeIn 0.2s;
        }

        .modal-box {
            background: white;
            width: 850px;
            max-width: 95%;
            border-radius: 12px;
            padding: 30px 40px;
            position: relative;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 800;
            color: #333;
        }

        .close-btn {
            font-size: 24px;
            color: #999;
            cursor: pointer;
            transition: 0.2s;
        }

        .close-btn:hover {
            color: #333;
        }

        .modal-body {
            display: flex;
            gap: 50px;
        }

        /* Cột Avatar */
        .avatar-section {
            width: 25%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .avatar-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 1px solid #eee;
            position: relative;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
        }

        .avatar-circle img {
            width: 70%;
            height: auto;
            opacity: 0.8;
        }

        .camera-btn {
            position: absolute;
            bottom: 5px;
            right: 10px;
            width: 35px;
            height: 35px;
            background: #2f3036;
            border-radius: 50%;
            color: white;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
        }

        .avatar-label {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .avatar-hint {
            font-size: 12px;
            color: #888;
        }

        /* Cột Form */
        .form-section {
            flex: 1;
        }

        .modal-form-group {
            margin-bottom: 15px;
        }

        .modal-label {
            font-size: 11px;
            font-weight: 700;
            color: #888;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-input {
            width: 100%;
            padding: 12px 15px;
            background: #f5f5f5;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            outline: none;
            font-weight: 500;
        }

        .modal-input:focus {
            background: #eee;
        }

        .address-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .modal-select {
            width: 100%;
            padding: 12px;
            background: #f5f5f5;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 15px top 50%;
            background-size: 10px auto;
        }

        .btn-save {
            background: #222;
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 15px;
            float: right;
            transition: 0.2s;
        }

        .btn-save:hover {
            background: #444;
        }

        /* --- 6. CART SIDEBAR (Giữ nguyên của bạn) --- */
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
            transition: 0.3s;
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

        .empty-cart-msg {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color: #777;
        }

        .cart-footer-box {
            padding: 15px;
            border-top: 1px solid #eee;
            background: white;
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
        <div class="cart-footer-box" id="cartFooter"></div>
    </div>



    <?php
    // Access the variables passed from the controller
    $user = isset($data['user']) ? $data['user'] : null;
    $dia_chi = isset($data['dia_chi']) ? $data['dia_chi'] : null;

    // Extract user information
    $full_name = $user ? $user['full_name'] : 'Khách hàng';
    $email = $user ? $user['email'] : '';
    $so_dien_thoai = $user ? $user['so_dien_thoai'] : '';
    $ten_user = $user ? $user['ten_user'] : '';
    $ngay_tao = $user && isset($user['ngay_tao'])
        ? TimezoneHelper::formatForDisplay($user['ngay_tao'], 'H:i:s d/m/Y')
        : date('H:i:s d/m/Y');


    // Extract address information (using default address if available)
    $dia_chi_text = '';
    if ($dia_chi && mysqli_num_rows($dia_chi) > 0) {
        mysqli_data_seek($dia_chi, 0); // Reset pointer to beginning
        while ($dc = mysqli_fetch_assoc($dia_chi)) {
            if ($dc['mac_dinh'] == 1) { // Default address
                $dia_chi_text = $dc['dia_chi'] . ', ' . $dc['ho_ten'] . ', ' . $dc['so_dien_thoai'];
                break;
            }
        }

        // If no default address found, use the first one
        if (empty($dia_chi_text)) {
            mysqli_data_seek($dia_chi, 0); // Reset pointer to beginning
            $first_dc = mysqli_fetch_assoc($dia_chi);
            $dia_chi_text = $first_dc['dia_chi'] . ', ' . $first_dc['ho_ten'] . ', ' . $first_dc['so_dien_thoai'];
        }
    } else {
        $dia_chi_text = 'Chưa có địa chỉ giao hàng';
    }
    ?>


    <div class="container">
        <div class="profile-wrapper">

            <div class="profile-header-card">
                <div class="welcome-text">
                    <h1>Chào <?php echo htmlspecialchars($full_name); ?></h1>
                    <div class="member-date">Tài khoản được tạo vào ngày <?php echo $ngay_tao; ?></div>
                </div>
                <?php
                if ($user && !empty($user['avatar'])) {
                    echo '<img src="/Banhang/Public/Pictures/users/' . htmlspecialchars($user['avatar']) . '" alt="Avatar" style="width: 60px; height: 60px; 
       border-radius: 50%; margin-right: 8px; vertical-align: middle;">';
                } else {
                    echo '<i class="fa-regular fa-user" style="vertical-align: middle;"></i>';
                }
                ?>
            </div>

            <div class="profile-grid">
                <div class="info-card">
                    <h3 class="card-title">Thông tin tài khoản</h3>

                    <div class="info-row">
                        <span class="info-label">Họ và tên</span>
                        <span class="info-value"><?php echo htmlspecialchars($full_name); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số điện thoại</span>
                        <span class="info-value"><?php echo htmlspecialchars($so_dien_thoai); ?></span>
                    </div>
                      <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($email); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Địa chỉ</span>
                        <span class="info-value"><?php echo htmlspecialchars(is_string($dia_chi_text) ? $dia_chi_text : ''); ?></span>
                    </div>

                    <button class="btn-black-outline" onclick="openModal()">CẬP NHẬT</button>
                </div>

                <div class="info-card">
                    <h3 class="card-title">Thông tin đăng nhập</h3>

                    <div class="info-row">
                        <span class="info-label">Tài khoản</span>
                        <div style="flex: 1;">
                            <input type="text" class="readonly-input" value="<?php echo htmlspecialchars($ten_user); ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mật khẩu</span>
                        <span class="info-value">........</span>
                    </div>

                    <button class="btn-black-outline" onclick="openPasswordModal()"><i class="fa-solid fa-key"></i> ĐỔI
                        MẬT KHẨU</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Hidden form for updating user info -->
    <form id="updateUserInfoForm" action="http://localhost/Banhang/Khachhang/capnhatTaikhoan" method="post"
        style="display: none;">
        <input type="hidden" name="txtMaUser" value="<?php echo $user ? $user['ma_user'] : ''; ?>">
        <input type="hidden" name="txtTenUser" value="<?php echo $user ? $user['ten_user'] : ''; ?>">
        <input type="hidden" name="txtEmail" value="<?php echo $user ? $user['email'] : ''; ?>">
        <input type="hidden" name="txtOldPassword" value="<?php echo $user ? $user['password'] : ''; ?>">
        <input type="text" name="txtFullName" id="form-fullname" value="">
        <input type="text" name="txtSoDienThoai" id="form-phone" value="">
        <input type="password" name="txtPassword" id="form-password" value="">
    </form>

    <!-- Password Change Modal -->
    <div class="modal-overlay" id="passwordModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Đổi mật khẩu</div>
                <div class="close-btn" onclick="closePasswordModal()"><i class="fa-solid fa-xmark"></i></div>
            </div>

            <div class="modal-body">
                <div class="form-section">
                    <div class="modal-form-group">
                        <label class="modal-label">MẬT KHẨU CŨ</label>
                        <input type="password" class="modal-input" id="currentPassword"
                            placeholder="Nhập mật khẩu hiện tại">
                    </div>

                    <div class="modal-form-group">
                        <label class="modal-label">MẬT KHẨU MỚI</label>
                        <input type="password" class="modal-input" id="newPassword" placeholder="Nhập mật khẩu mới">
                    </div>

                    <div class="modal-form-group">
                        <label class="modal-label">XÁC NHẬN MẬT KHẨU MỚI</label>
                        <input type="password" class="modal-input" id="confirmNewPassword"
                            placeholder="Nhập lại mật khẩu mới">
                    </div>

                    <button class="btn-save" onclick="changePassword()">LƯU THAY ĐỔI</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="updateModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">Chỉnh sửa thông tin tài khoản</div>
                <div class="close-btn" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></div>
            </div>

            <div class="modal-body">
                <div class="avatar-section">
                    <form method="post" action="http://localhost/Banhang/Khachhang/capnhatTaikhoan" enctype="multipart/form-data">
                        <div class="avatar-container">
                            <div class="avatar-circle" style="position: relative; display: inline-block;">
                                <?php if ($user && !empty($user['avatar'])): ?>
                                    <img src="/Banhang/Public/Pictures/users/<?php echo htmlspecialchars($user['avatar'] ?? ''); ?>"
                                         alt="Avatar người dùng" style="width: 160px; height: 140px; border-radius: 50%; object-fit: fill;" id="avatar-preview">
                                <?php else: ?>
                                    <img src="/Banhang/Public/Images/avatar.png"
                                         alt="Avatar người dùng" style="width: 160px; height: 140px; border-radius: 50%; object-fit: fill;" id="avatar-preview">
                                <?php endif; ?>

                                <div class="camera-btn" id="camera-btn" style="position: absolute; bottom: 0; right: 0; background: #2463ff; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                            </div>

                            <input type="file" name="txtAvatar" id="avatar-input" accept="image/*" style="display: none;" />
                        </div>
                    </form>
                    <div class="avatar-label">ẢNH ĐẠI DIỆN</div>
                    <div class="avatar-hint">Bấm vào camera để thay đổi ảnh</div>
                </div>

                <div class="form-section">
                    <div class="modal-form-group">
                        <label class="modal-label">HỌ VÀ TÊN</label>
                        <input type="text" class="modal-input" id="modal-fullname"
                            value="<?php echo htmlspecialchars($full_name); ?>">
                    </div>

                    <div class="modal-form-group">
                        <label class="modal-label">SỐ ĐIỆN THOẠI</label>
                        <input type="text" class="modal-input" id="modal-phone"
                            value="<?php echo htmlspecialchars($so_dien_thoai); ?>">
                    </div>
                  
                     <div class="modal-form-group">
                        <label class="modal-label">Email</label>
                        <input type="text" class="modal-input" id="modal-email"
                            value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <!-- <div class="modal-form-group">
                        <label class="modal-label">ĐỊA CHỈ GIAO HÀNG</label>
                        <div class="address-row">
                            <div>
                                <label class="modal-label" style="font-weight:400; font-size:10px;">TỈNH / THÀNH</label>
                                <select class="modal-select" id="modal-province">
                                    <option value="">Chọn tỉnh/thành</option>
                                    <option value="Quảng Ninh"
                                        <?php echo (strpos(strtolower($dia_chi_text), 'quảng ninh') !== false) ? 'selected' : ''; ?>>
                                        Quảng Ninh</option>
                                    <option value="Hà Nội"
                                        <?php echo (strpos(strtolower($dia_chi_text), 'hà nội') !== false) ? 'selected' : ''; ?>>
                                        Hà Nội</option>
                                    <option value="TP.HCM"
                                        <?php echo (strpos(strtolower($dia_chi_text), 'hồ chí minh') !== false) ? 'selected' : ''; ?>>
                                        TP.HCM</option>
                                </select>
                            </div>
                            <div>
                                <label class="modal-label" style="font-weight:400; font-size:10px;">QUẬN / HUYỆN</label>
                                <select class="modal-select" id="modal-district">
                                    <option value="">Chọn quận/huyện</option>
                                    <option value="Cẩm Phả"
                                        <?php echo (strpos(strtolower($dia_chi_text), 'cẩm phả') !== false) ? 'selected' : ''; ?>>
                                        Cẩm Phả</option>
                                    <option value="Hạ Long"
                                        <?php echo (strpos(strtolower($dia_chi_text), 'hạ long') !== false) ? 'selected' : ''; ?>>
                                        Hạ Long</option>
                                </select>
                            </div>
                            <div>
                                <label class="modal-label" style="font-weight:400; font-size:10px;">PHƯỜNG / XÃ</label>
                                <select class="modal-select" id="modal-ward">
                                    <option value="">Chọn phường/xã</option>
                                    <option value="Cẩm Thạch"
                                        <?php echo (strpos(strtolower($dia_chi_text), 'cẩm thạch') !== false) ? 'selected' : ''; ?>>
                                        Cẩm Thạch</option>
                                    <option value="Cẩm Bình"
                                        <?php echo (strpos(strtolower($dia_chi_text), 'cẩm bình') !== false) ? 'selected' : ''; ?>>
                                        Cẩm Bình</option>
                                </select>
                            </div>
                        </div>
                    </div> -->

                    <div class="modal-form-group">
                        <label class="modal-label">ĐỊA CHỈ CHI TIẾT</label>
                        <input type="text" class="modal-input" id="modal-address-detail"
                            value="<?php echo htmlspecialchars($dia_chi_text); ?>">
                    </div>

                    <button class="btn-save" onclick="saveChanges()">LƯU THAY ĐỔI</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Các hàm xử lý modal riêng biệt để tránh xung đột với các phần khác
        const modal = document.getElementById('updateModal');
        const passwordModal = document.getElementById('passwordModal');

        function openModal() {
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        function openPasswordModal() {
            passwordModal.classList.add('active');
        }

        function closePasswordModal() {
            passwordModal.classList.remove('active');
        }

        // Function to save changes
        function saveChanges() {
            // Get the form elements
            const fullnameElement = document.getElementById('modal-fullname');
            const phoneElement = document.getElementById('modal-phone');
            const emailElement = document.getElementById('modal-email');
            const avatarInputElement = document.getElementById('avatar-input');
            
            if (!fullnameElement || !phoneElement || !emailElement || !avatarInputElement) {
                console.error('One or more form elements not found');
                alert('Không tìm thấy các trường cần thiết để cập nhật thông tin!');
                return;
            }

            // Get updated values from the modal form
            const fullname = fullnameElement.value;
            const phone = phoneElement.value;
            const email = emailElement.value;

            // Create a FormData object to handle file upload along with other data
            const formData = new FormData();
            
            // Add text fields
            formData.append('txtMaUser', '<?php echo $user['ma_user']; ?>');
            formData.append('txtFullName', fullname);
            formData.append('txtSoDienThoai', phone);
            formData.append('txtEmail', email);
            
            // Add avatar file if selected
            if (avatarInputElement.files.length > 0) {
                formData.append('txtAvatar', avatarInputElement.files[0]);
            }

            // Log form data for debugging
            console.log('Sending form data:');
            for (let pair of formData.entries()) {
                console.log(pair[0]+ ': ' + pair[1]);
            }

            // Send AJAX request to update user info
            fetch('http://localhost/Banhang/Khachhang/capnhatTaikhoan', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text();
                })
                .then(data => {
                    console.log('Server response:', data);
                    try {
                        const result = JSON.parse(data);
                        if (result.success) {
                            alert('Cập nhật thông tin thành công!');
                            // Reload the page to reflect changes
                            location.reload();
                        } else {
                            alert(result.message || 'Có lỗi xảy ra khi cập nhật thông tin!');
                        }
                    } catch (e) {
                        // If response is not JSON, it might be a redirect or error page
                        // Check if it looks like a success message
                        if (data.toLowerCase().includes('thành công') || data.includes('success')) {
                            alert('Cập nhật thông tin thành công!');
                            location.reload();
                        } else {
                            alert('Cập nhật thất bại. Vui lòng thử lại. Chi tiết: ' + data.substring(0, 200));
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật thông tin! Chi tiết: ' + error.message);
                });
        }

        // Function to change password
        function changePassword() {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;

            // Basic validation
            if (!currentPassword || !newPassword || !confirmNewPassword) {
                alert('Vui lòng điền đầy đủ thông tin!');
                return;
            }

            if (newPassword !== confirmNewPassword) {
                alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
                return;
            }

            // if (newPassword.length < 6) {
            //     alert('Mật khẩu mới phải có ít nhất 6 ký tự!');
            //     return;
            // }

            // Create form data to send to server
            const formData = new FormData();
            formData.append('txtMaUser', '<?php echo $user['ma_user']; ?>');
            formData.append('txtCurrentPassword', currentPassword); // Mật khẩu hiện tại người dùng nhập
            formData.append('txtNewPassword', newPassword); // Mật khẩu mới

            // Send AJAX request to update password
            fetch('http://localhost/Banhang/Khachhang/doimatkhau', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    const result = JSON.parse(data);
                    if (result.success) {
                        alert('Đổi mật khẩu thành công!');
                        // Clear the password fields
                        document.getElementById('currentPassword').value = '';
                        document.getElementById('newPassword').value = '';
                        document.getElementById('confirmNewPassword').value = '';
                        // Close the modal
                        closePasswordModal();
                    } else {
                        alert(result.message || 'Có lỗi xảy ra khi đổi mật khẩu!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi đổi mật khẩu!');
                });
        }

        // Đóng modal khi click ra ngoài (riêng cho các modal của trang này)
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                closeModal();
            }
            if (event.target == passwordModal) {
                closePasswordModal();
            }
        });

        // --- LOGIC GIỎ HÀNG (GIỐNG TRANG CHI TIẾT) ---
        var cart = []; // Mảng chứa các object sản phẩm

        // Hàm mở/đóng Sidebar
        function toggleCart() {
            var overlay = document.querySelector('.cart-overlay');
            var sidebar = document.querySelector('.cart-sidebar');
            overlay.classList.toggle('active');
            sidebar.classList.toggle('active');
        }

        // Hàm thêm vào giỏ (Được gọi từ nút Mua ngay trên card)
        function addToCart(name, price, img) {
            // Tạo object sản phẩm
            var product = {
                img: img,
                name: name,
                color: 'Mặc định', // Ở trang chủ không chọn màu nên để mặc định
                storage: 'Mặc định',
                quantity: 1,
                price: price
            };

            // Thêm vào mảng (Ở đây làm đơn giản là cứ thêm mới, chưa gộp sản phẩm trùng)
            cart.push(product);

            // Cập nhật giao diện giỏ hàng
            renderCart();

            // Mở giỏ hàng cho người dùng thấy
            var overlay = document.querySelector('.cart-overlay');
            var sidebar = document.querySelector('.cart-sidebar');
            if (!sidebar.classList.contains('active')) {
                toggleCart();
            }
        }

        // Hàm xóa sản phẩm
        function removeFromCart(index) {
            cart.splice(index, 1); // Xóa 1 phần tử tại vị trí index
            renderCart(); // Vẽ lại giỏ hàng
        }

        // Hàm vẽ lại giỏ hàng (Render)
        function renderCart() {
            var cartBody = document.getElementById('cartBody');
            var cartFooter = document.getElementById('cartFooter');
            var cartBadge = document.getElementById('cartBadge');

            // 1. Cập nhật số lượng trên icon badge
            var totalQuantity = 0;
            var totalPrice = 0;

            cart.forEach(item => {
                totalQuantity += item.quantity;
                totalPrice += (item.price * item.quantity);
            });
            cartBadge.innerText = totalQuantity;

            // 2. Xử lý hiển thị Body
            if (cart.length === 0) {
                cartBody.innerHTML = '<div class="empty-cart-msg">Chưa có sản phẩm trong giỏ hàng</div>';
                cartFooter.innerHTML = ''; // Xóa footer nếu trống
                return;
            }

            // Nếu có sản phẩm, tạo HTML
            var html = '';
            cart.forEach((item, index) => {
                var itemTotal = (item.price * item.quantity).toLocaleString('vi-VN');
                html += `
                <div class="cart-item">
                    <div class="cart-item-img">
                        <img src="${item.img}" alt="">
                    </div>
                    <div class="cart-item-info">
                        <span class="cart-item-name">${item.name}</span>
                        <span class="cart-item-variant">MÀU SẮC: ${item.color.toUpperCase()}</span>
                        <span class="cart-item-variant">DUNG LƯỢNG Ổ CỨNG: ${item.storage}</span>
                        <div class="cart-item-price">${item.quantity} x ${item.price.toLocaleString('vi-VN')} ₫</div>
                    </div>
                    <div class="cart-remove-btn" onclick="removeFromCart(${index})">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                `;
            });
            cartBody.innerHTML = html;

            // 3. Xử lý hiển thị Footer (Tổng tiền & Button)
            cartFooter.innerHTML = `
                <div class="cart-total-row">
                    Tổng số phụ: <span class="cart-total-price">${totalPrice.toLocaleString('vi-VN')} ₫</span>
                </div>
                <div class="cart-btn-group">
                    <button class="btn-view-cart">XEM GIỎ HÀNG</button>
                    <button class="btn-checkout">THANH TOÁN</button>
                </div>
            `;
        }

        // Function to open password change modal
        function openPasswordModal() {
            document.getElementById('passwordModal').classList.add('active');
        }

        // Function to close password change modal
        function closePasswordModal() {
            document.getElementById('passwordModal').classList.remove('active');
        }

        // Đóng khi click ra ngoài
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                closeModal();
            }
            if (event.target == document.getElementById('passwordModal')) {
                closePasswordModal();
            }
            // Đóng menu tài khoản khi click ra ngoài
            if (!accountBtn.contains(event.target)) {
                accountMenu.classList.remove('active');
            }
        });

        // Avatar upload functionality for customer profile page
        document.addEventListener('DOMContentLoaded', function() {
            const cameraBtn = document.getElementById('camera-btn');
            const avatarInput = document.getElementById('avatar-input');
            const avatarPreview = document.getElementById('avatar-preview');

            if (cameraBtn && avatarInput && avatarPreview) {
                cameraBtn.addEventListener('click', function() {
                    avatarInput.click();
                });

                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            alert('Vui lòng chọn file ảnh (JPEG, PNG, GIF, WEBP)');
                            return;
                        }

                        // Validate file size (max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            alert('File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 5MB');
                            return;
                        }

                        // Preview the selected image
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            avatarPreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>

</body>

</html>