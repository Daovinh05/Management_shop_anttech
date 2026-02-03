<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - TechZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <base href="http://localhost/Banhang/">

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

        /* --- 2. HEADER & BANNER STYLES --- */
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

        .category-dropdown button {
            height: 40px;
            background: #f5f5f7;
            border: 1px solid #e0e0e0;
            padding: 0 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
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

        /* --- BỔ SUNG CSS CHO MENU TÀI KHOẢN --- */
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

        /* --- BỔ SUNG CSS CHO GIỎ HÀNG (SIDEBAR) --- */
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

        .cart-item {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
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
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .cart-item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .cart-item-name {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.3;
        }

        .cart-item-variant {
            font-size: 11px;
            color: #777;
        }

        .cart-item-price {
            font-size: 13px;
            color: #d70018;
            font-weight: 700;
            margin-top: 2px;
        }

        .cart-remove-btn {
            color: #999;
            cursor: pointer;
            padding: 0 5px;
        }

        .cart-remove-btn:hover {
            color: #d70018;
        }

        .cart-footer-box {
            padding: 15px;
            border-top: 1px solid #eee;
            background: white;
        }

        .cart-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .cart-total-price {
            color: #d70018;
            font-size: 18px;
        }

        .cart-btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-view-cart,
        .btn-checkout {
            width: 100%;
            padding: 12px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 14px;
        }

        .btn-view-cart {
            background: white;
            border: 1px solid #333;
            color: #333;
        }

        .btn-view-cart:hover {
            background: #f5f5f5;
        }

        .btn-checkout {
            background: #d70018;
            color: white;
        }

        .btn-checkout:hover {
            background: #b70014;
        }


        /* --- 3. CHECKOUT PAGE STYLES --- */
        .checkout-wrapper {
            padding: 40px 0;
            background-color: #fff;
            margin-top: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 50px;
            padding: 0 20px;
        }

        /* Form bên trái */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            display: inline-block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #444;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(0, 72, 61, 0.1);
        }

        textarea.form-control {
            height: 120px;
            resize: vertical;
        }

        /* Cột bên phải: Đơn hàng */
        .order-review-box {
            border: 2px solid var(--primary-green);
            padding: 20px;
            border-radius: 8px;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-table th {
            text-align: left;
            padding: 10px 0;
            border-bottom: 2px solid #eee;
            font-size: 14px;
            color: #333;
        }

        .order-table td {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            vertical-align: top;
        }

        .product-name-col {
            padding-right: 10px;
            color: #555;
            font-weight: 500;
        }

        .product-meta {
            display: block;
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }

        .price-col {
            text-align: right;
            font-weight: 700;
            color: #333;
            white-space: nowrap;
        }

        .subtotal-row td {
            font-weight: 700;
            color: #333;
            padding-top: 20px;
        }

        .discount-row td {
            color: var(--primary-green);
            font-style: italic;
        }

        .total-row td {
            font-size: 18px;
            color: var(--tet-red);
            font-weight: 800;
            border-top: 2px solid #eee;
            border-bottom: none;
        }

        /* Phương thức thanh toán */
        .payment-methods {
            margin-top: 20px;
        }

        .payment-option {
            margin-bottom: 15px;
        }

        .payment-option input[type="radio"] {
            margin-right: 8px;
            transform: scale(1.2);
            accent-color: var(--primary-green);
        }

        .payment-label {
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }

        /* Nút đặt hàng */
        .btn-order-confirm {
            width: 100%;
            background-color: var(--tet-red);
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }

        .btn-order-confirm:hover {
            background-color: #b70014;
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
            <div style="font-weight:700;">GIỎ HÀNG</div>
            <div style="cursor:pointer;" onclick="toggleCart()"><i class="fa-solid fa-xmark"></i></div>
        </div>
        <div class="cart-body" id="cartBody">
            <div class="empty-cart-msg">Chưa có sản phẩm trong giỏ hàng</div>
        </div>
        <div class="cart-footer-box" id="cartFooter"></div>
    </div>




    <div class="container">
        <div class="checkout-wrapper">
            <form action="<?php echo $this->url('Khachhang/datHang'); ?>" method="POST">
                <input type="hidden" name="selected_items_str" value="<?php echo htmlspecialchars($_GET['items'] ?? ''); ?>">
                <div class="checkout-grid">

                    <div class="billing-details">
                        <h3 class="section-title">Thông tin thanh toán</h3>

                        <?php
                        // Hiển thị lỗi nếu có
                        if (isset($data['errors']) && !empty($data['errors'])):
                        ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($data['errors'] as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php
                        // Lấy thông tin người dùng để điền vào form
                        $user_info = null;
                        if (isset($_SESSION['user_id'])) {
                            $user_model = $this->model("Users_m");
                            $user_result = $user_model->Users_getById($_SESSION['user_id']);
                            if ($user_result && $row = mysqli_fetch_assoc($user_result)) {
                                $user_info = $row;
                            }
                        }

                        // Lấy dữ liệu cũ nếu có lỗi
                        $old_data = isset($data['old_data']) ? $data['old_data'] : [];
                        ?>

                        <div class="form-group">
                            <label for="fullname">Họ và tên *</label>
                            <input type="text" id="fullname" class="form-control" name="txtHoTen"
                                placeholder="Nhập họ và tên của bạn"
                                value="<?php echo isset($old_data['ho_ten']) ? htmlspecialchars($old_data['ho_ten']) : ($user_info ? htmlspecialchars($user_info['full_name']) : ''); ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="address">Địa chỉ *</label>
                            <select class="form-control" id="address" name="ddlDiaChi" required>
                                <option value="">Chọn địa chỉ</option>
                                <?php if ($data['dia_chi'] && mysqli_num_rows($data['dia_chi']) > 0): ?>
                                    <?php while ($dc = mysqli_fetch_assoc($data['dia_chi'])): ?>
                                        <option value="<?php echo $dc['ma_dia_chi']; ?>" <?php
                                                                                            if (isset($old_data['dia_chi_selected'])) {
                                                                                                echo $old_data['dia_chi_selected'] == $dc['ma_dia_chi'] ? 'selected' : '';
                                                                                            } else {
                                                                                                echo $dc['mac_dinh'] == 1 ? 'selected' : '';
                                                                                            }
                                                                                            ?>>
                                            <?php echo $dc['dia_chi'] . ' - ' . $dc['ho_ten'] . ' - ' . $dc['so_dien_thoai']; ?>
                                            <?php echo $dc['mac_dinh'] == 1 ? ' (Mặc định)' : ''; ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <option value="">Bạn chưa có địa chỉ nào</option>
                                <?php endif; ?>
                            </select>
                            <a href="Khachhang/taikhoan" class="mt-2 d-block" style="color: var(--primary-green);">+
                                Thêm địa chỉ mới</a>
                        </div>

                        <div class="form-group">
                            <label for="phone">Số điện thoại *</label>
                            <input type="tel" id="phone" class="form-control" name="txtSoDienThoai"
                                placeholder="Nhập số điện thoại"
                                value="<?php echo isset($old_data['so_dien_thoai']) ? htmlspecialchars($old_data['so_dien_thoai']) : ($user_info ? htmlspecialchars($user_info['so_dien_thoai']) : ''); ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="email">Địa chỉ email *</label>
                            <input type="email" id="email" class="form-control" name="txtEmail"
                                placeholder="Nhập email để nhận thông báo đơn hàng"
                                value="<?php echo isset($old_data['email']) ? htmlspecialchars($old_data['email']) : ($user_info ? htmlspecialchars($user_info['email']) : ''); ?>"
                                required>
                        </div>

                        <h3 class="section-title" style="margin-top: 30px;">Thông tin bổ sung</h3>
                        <div class="form-group">
                            <label for="note">Ghi chú đơn hàng (Tùy chọn)</label>
                            <textarea id="note" class="form-control" name="txtGhiChu"
                                placeholder="Ví dụ: Thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn."><?php echo isset($old_data['ghi_chu']) ? htmlspecialchars($old_data['ghi_chu']) : ''; ?></textarea>
                        </div>
                    </div>

                    <div class="order-review-section">
                        <h3 class="section-title">Đơn hàng của bạn</h3>

                        <div class="order-review-box">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th>SẢN PHẨM</th>
                                        <th style="text-align: right;">TẠM TÍNH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tong_tien = 0;
                                    $tong_san_pham = 0;

                                        // Kiểm tra nếu là mảng và có dữ liệu
                                        if ($data['ds_sp_thanh_toan'] && count($data['ds_sp_thanh_toan']) > 0):
                                            
                                            // Dùng foreach để duyệt mảng thay vì while
                                            foreach ($data['ds_sp_thanh_toan'] as $item):
                                            $thanh_tien = $item['gia'] * $item['so_luong'];
                                            $tong_tien += $thanh_tien;
                                            $tong_san_pham += $item['so_luong'];
                                    ?>
                                            <tr>
                                                <td class="product-name-col">
                                                    <?php echo htmlspecialchars($item['ten_san_pham']); ?> <strong
                                                        style="color:#333">×</strong> <?php echo $item['so_luong']; ?>
                                                    <?php if (!empty($item['ten_bien_the'])): ?>
                                                        <span class="product-meta">BIẾN THỂ:
                                                            <?php echo htmlspecialchars($item['ten_bien_the']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['mau_sac'])): ?>
                                                        <span class="product-meta">MÀU SẮC:
                                                            <?php echo htmlspecialchars($item['mau_sac']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['dung_luong'])): ?>
                                                        <span class="product-meta">DUNG LƯỢNG:
                                                            <?php echo htmlspecialchars($item['dung_luong']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="price-col">
                                                    <?php echo number_format($thanh_tien, 0, ',', '.') . '₫'; ?></td>
                                            </tr>
                                        <?php
                                            endforeach;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="2" class="text-center">Không có sản phẩm nào trong giỏ hàng</td>
                                        </tr>
                                    <?php endif; ?>

                                    <tr class="subtotal-row">
                                        <td>Tạm tính (<?php echo $tong_san_pham; ?> sản phẩm)</td>
                                        <td class="price-col">
                                            <?php echo number_format($tong_tien, 0, ',', '.') . '₫'; ?></td>
                                    </tr>

                                    <tr class="voucher-row">
                                        <td>Khuyến mãi (Voucher)</td>
                                        <td class="price-col">
                                            <select name="ddlKhuyenMai" id="ddlKhuyenMai" class="form-control">
                                                <option value="">-- Chọn voucher --</option>
                                                <?php if ($data['ds_khuyen_mai'] && mysqli_num_rows($data['ds_khuyen_mai']) > 0): ?>
                                                    <?php while ($km = mysqli_fetch_assoc($data['ds_khuyen_mai'])): ?>
                                                        <option value="<?php echo $km['ma_khuyen_mai']; ?>"
                                                                data-discount="<?php echo $km['tien_khuyen_mai']; ?>">
                                                            <?php echo $km['ten_khuyen_mai']; ?> (-<?php echo number_format($km['tien_khuyen_mai'], 0, ',', '.'); ?>₫)
                                                        </option>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <option value="" disabled>Không có voucher nào</option>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr class="discount-row">
                                        <td>Giảm giá</td>
                                        <td class="price-col" id="discountAmount">0₫</td>
                                    </tr>

                                    <tr class="total-row">
                                        <td>Tổng</td>
                                        <td class="price-col">
                                            <span id="finalTotal"><?php echo number_format($tong_tien, 0, ',', '.') . '₫'; ?></span>
                                            <input type="hidden" name="final_total" id="finalTotalInput" value="<?php echo $tong_tien; ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="payment-methods">
                                <div class="payment-option">
                                    <label class="payment-label">
                                        <input type="radio" name="payment_method" value="bank"
                                            <?php echo (isset($old_data['payment_method']) && $old_data['payment_method'] === 'bank') ? 'checked' : (isset($old_data['payment_method']) ? '' : 'checked'); ?>>
                                        VNPAY QR - Thanh toán qua mã QR
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <label class="payment-label">
                                        <input type="radio" name="payment_method" value="cod"
                                            <?php echo (isset($old_data['payment_method']) && $old_data['payment_method'] === 'cod') ? 'checked' : (isset($old_data['payment_method']) ? '' : ''); ?>>
                                        Trả tiền mặt khi nhận hàng (COD)
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn-order-confirm" name="btnDatHang">ĐẶT HÀNG</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>


    <script>
        // Lấy các phần tử
        const accountBtn = document.getElementById('accountBtn');
        const accountMenu = document.getElementById('accountMenu');

        // Toggle Account Menu (GIỜ ĐÃ HOẠT ĐỘNG VÌ ĐÃ CÓ HTML accountMenu)
        accountBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            accountMenu.classList.toggle('active');
        });

        // Close when clicking outside
        window.addEventListener('click', function(event) {
            if (!accountBtn.contains(event.target)) {
                accountMenu.classList.remove('active');
            }
        });

        // --- LOGIC GIỎ HÀNG ---
        // Khởi tạo giỏ hàng từ dữ liệu PHP đã hiển thị trong bảng đơn hàng
        var cart = []; // Mảng chứa các object sản phẩm

        // Lấy số lượng từ badge hiện tại trên trang chủ/master để giữ nguyên
        document.addEventListener('DOMContentLoaded', function() {
            // Cập nhật lại giỏ hàng JavaScript dựa trên dữ liệu hiện tại từ PHP
            // Lấy số lượng sản phẩm từ phần hiển thị đơn hàng của trang thanh toán
            var currentCartBadge = document.getElementById('cartBadge');
            if(currentCartBadge) {
                // Không thay đổi số lượng từ PHP vì nó đã chính xác
                // Chỉ đảm bảo rằng JavaScript không ghi đè lên
            }
        });

        // Hàm mở/đóng Sidebar
        function toggleCart() {
            var overlay = document.querySelector('.cart-overlay');
            var sidebar = document.querySelector('.cart-sidebar');
            // Cần kiểm tra xem phần tử có tồn tại không để tránh lỗi
            if (overlay && sidebar) {
                sidebar.classList.toggle('active');
            }
        }

        // Hàm vẽ lại giỏ hàng (Render)
        function renderCart() {
            var cartBody = document.getElementById('cartBody');
            var cartFooter = document.getElementById('cartFooter');
            var cartBadge = document.getElementById('cartBadge');

            if (!cartBadge) return; // Nếu chưa load xong thì return

            // 1. Cập nhật số lượng trên icon badge
            var totalQuantity = 0;
            var totalPrice = 0;

            cart.forEach(item => {
                totalQuantity += item.quantity;
                totalPrice += (item.price * item.quantity);
            });

            // Chỉ cập nhật badge nếu JavaScript cart có dữ liệu
            // Nếu không có dữ liệu trong JS cart, giữ nguyên giá trị từ PHP
            if(cart.length > 0) {
                cartBadge.innerText = totalQuantity;
            }
            // Nếu cart.length = 0, không thay đổi giá trị badge để giữ nguyên dữ liệu từ PHP

            // 2. Xử lý hiển thị Body
            if (cart.length === 0) {
                cartBody.innerHTML = '<div class="empty-cart-msg">Chưa có sản phẩm trong giỏ hàng</div>';
                cartFooter.innerHTML = ''; // Xóa footer nếu trống
                return;
            }
            // Code render sản phẩm giỏ hàng... (Phần này sẽ chạy khi bạn thêm sản phẩm ở trang khác)
        }

        // Gọi render lần đầu (để set số lượng = 0)
        renderCart();

        // --- LOGIC KHUYẾN MÃI ---
        document.addEventListener('DOMContentLoaded', function() {
            const voucherSelect = document.getElementById('ddlKhuyenMai');
            const discountAmountElement = document.getElementById('discountAmount');
            const finalTotalElement = document.getElementById('finalTotal');
            const finalTotalInput = document.getElementById('finalTotalInput');
            const subtotalValue = <?php echo $tong_tien; ?>; // Giá trị tạm tính

            // Hàm tính toán lại tổng sau khi áp dụng khuyến mãi
            function updateTotal() {
                const selectedOption = voucherSelect.options[voucherSelect.selectedIndex];
                let discount = 0;

                if (selectedOption && selectedOption.dataset.discount) {
                    discount = parseFloat(selectedOption.dataset.discount);
                }

                // Tính tổng mới sau khi trừ khuyến mãi
                let newTotal = subtotalValue - discount;

                // Đảm bảo tổng không âm
                if (newTotal < 0) {
                    newTotal = 0;
                }

                // Cập nhật hiển thị
                discountAmountElement.textContent = '-' + discount.toLocaleString('vi-VN') + '₫';
                finalTotalElement.textContent = newTotal.toLocaleString('vi-VN') + '₫';
                finalTotalInput.value = newTotal;
            }

            // Gắn sự kiện thay đổi cho dropdown voucher
            voucherSelect.addEventListener('change', updateTotal);

            // Gọi hàm cập nhật ban đầu
            updateTotal();
        });
    </script>

</body>

</html>