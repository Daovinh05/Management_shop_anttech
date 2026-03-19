<?php
// Include necessary helpers
include_once __DIR__ . '/../../../../Public/Classes/TimezoneHelper.php';
include_once __DIR__ . '/../../../../Public/Classes/UrlHelper.php';
?>

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
        --price-discount: #008000;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }

    body {
        background-color: var(--bg-light);
        color: var(--text-dark);
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
        position: relative;
        z-index: 100;
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

    /* Layout khu vực giữa */
    .middle-section {
        flex-grow: 1;
        max-width: 700px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Nút Danh mục - Đã tối ưu CSS để hover mượt */
    .category-dropdown {
        position: relative;
        padding-bottom: 10px;
        margin-bottom: -10px;
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

    .btn-category:hover {
        background: #eee;
    }

    /* Menu Danh mục - Hiện khi hover vào category-dropdown */
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


    .popular-keywords {
        margin-top: 5px;
        font-size: 13px;
        color: var(--text-gray);
        margin-left: 356px;
    }

    .popular-keywords span {
        margin-right: 5px;
    }

    .popular-keywords a {
        margin-right: 10px;
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

    /* --- 4. MAIN LAYOUT --- */
    .main-body-wrapper {
        background: linear-gradient(180deg, #fff 0%, #fff6f6 100%);
        padding: 20px 0;
        min-height: 100vh;
    }

    .main-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 30px;
    }

    /* --- 5. SIDEBAR (FILTER) --- */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .filter-widget {
        background: transparent;
        padding-right: 10px;
    }

    .filter-main-title {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #333;
    }

    .filter-group {
        margin-bottom: 25px;
    }

    .filter-group-title {
        font-size: 16px;
        font-weight: 400;
        color: #666;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filter-option {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 14px;
        color: #555;
        position: relative;
        padding-left: 30px;
    }

    .filter-option:hover {
        color: #000;
    }

    .filter-option input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 50%;
    }

    .filter-option input:checked~.checkmark {
        border-color: var(--primary-green);
    }

    .filter-option .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        top: 4px;
        left: 4px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--primary-green);
    }

    .filter-option input:checked~.checkmark:after {
        display: block;
    }

    /* --- 6. MAIN CONTENT --- */
    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filter-header h2 {
        font-size: 18px;
        font-weight: 400;
        color: #333;
    }

    .btn-filter-now {
        background: white;
        border: 1px solid #ddd;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .results-count {
        font-size: 14px;
        margin-bottom: 15px;
        font-weight: 500;
        color: #333;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .product-card {
        background: rgba(242, 238, 238, 0.899);
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
        position: relative;
        border: 1px solid transparent;
    }

    .product-card:hover {
        border-color: var(--tet-red);
        transform: translateY(-2px);
    }

    .sticker-sale {
        position: absolute;
        top: 10px;
        left: -5px;
        background: var(--tet-red);
        color: white;
        padding: 3px 8px;
        font-size: 12px;
        font-weight: bold;
        border-radius: 4px;
        z-index: 2;
    }

    .sticker-sale::before {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        border-top: 5px solid #a10000;
        border-left: 5px solid transparent;
    }

    .product-img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .product-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        line-height: 1.4;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .current-price {
        color: #d70018;
        font-weight: 700;
        font-size: 16px;
    }

    .old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 13px;
    }

    .discount-percent {
        color: #d70018;
        font-size: 12px;
        font-weight: 700;
        background: #fff0f0;
        padding: 2px 4px;
        border-radius: 3px;
    }

    .price-discount-text {
        color: var(--price-discount);
        font-size: 12px;
        margin-top: 5px;
        font-weight: 500;
    }

    .buy-btn {
        background: var(--tet-red);
        color: white;
        text-align: center;
        padding: 8px;
        border-radius: 4px;
        font-weight: 500;
        opacity: 0;
        transition: 0.3s;
        cursor: pointer;
    }

    .product-card:hover .buy-btn {
        opacity: 1;
    }

    .product-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    /* Footer Styles */
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


    /* Loading indicator */
    .loading {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        font-size: 16px;
        color: #666;
    }

    .error {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        font-size: 16px;
        color: #d70018;
    }
    
    /* Pagination Styles */
    .pagination {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 10px 20px;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .pagination-btn {
        display: inline-block;
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        transition: all 0.2s;
        min-width: 40px;
        text-align: center;
    }
    
    .pagination-btn:hover {
        background: #f5f5f7;
        border-color: var(--primary-green);
        color: var(--primary-green);
    }
    
    .pagination-btn.active {
        background: var(--primary-green);
        color: white;
        border-color: var(--primary-green);
    }
    
    .pagination-btn.disabled {
        color: #999;
        border-color: #ddd;
        cursor: not-allowed;
    }
    
    .pagination-ellipsis {
        padding: 8px 4px;
        color: #999;
    }
</style>

<div class="main-body-wrapper">
    <div class="container">
        <div class="main-layout">

            <aside class="sidebar">
                <div class="filter-widget">
                    <h3 class="filter-main-title">Bộ lọc</h3>
                    <div class="filter-group">
                        <h4 class="filter-group-title">Danh mục</h4>

                        <label class="filter-option"><input type="radio" name="category" value="" checked><span
                                class="checkmark"></span>Tất cả</label>

                        <?php foreach ($data['dsdm'] as $dm): ?>
                            <label class="filter-option"><input type="radio" name="category"
                                    value="<?php echo $dm['ma_danh_muc']; ?>"><span
                                    class="checkmark"></span><?php echo htmlspecialchars($dm['ten_danh_muc']); ?></label>
                        <?php endforeach; ?>
                    </div>
                    <div class="filter-group">
                        <h4 class="filter-group-title">Giá</h4>
                        <label class="filter-option"><input type="radio" name="price" value="tat-ca" checked><span
                                class="checkmark"></span>Tất cả</label>
                        <label class="filter-option"><input type="radio" name="price" value="duoi-2-trieu"><span
                                class="checkmark"></span>Dưới 2 triệu</label>
                        <label class="filter-option"><input type="radio" name="price" value="2-4-trieu"><span
                                class="checkmark"></span>Từ 2 - 4 triệu</label>
                        <label class="filter-option"><input type="radio" name="price" value="4-7-trieu"><span
                                class="checkmark"></span>Từ 4 - 7 triệu</label>
                        <label class="filter-option"><input type="radio" name="price" value="7-13-trieu"><span
                                class="checkmark"></span>Từ 7 - 13 triệu</label>
                        <label class="filter-option"><input type="radio" name="price" value="tren-13-trieu"><span
                                class="checkmark"></span>Trên 13 triệu</label>
                    </div>
                    <div class="filter-group">
                        <h4 class="filter-group-title">Thương hiệu</h4>
                        <label class="filter-option"><input type="radio" name="brand" value="" checked><span
                                class="checkmark"></span>Tất cả</label>
                        <?php
                        // Lấy danh sách thương hiệu từ model
                        $thuong_hieu_model = $this->model("ThuongHieu_m");
                        $dsth = $thuong_hieu_model->ThuongHieu_getAll();
                        foreach ($dsth as $th): ?>
                            <label class="filter-option"><input type="radio" name="brand"
                                    value="<?php echo $th['ma_thuong_hieu']; ?>"><span
                                    class="checkmark"></span><?php echo htmlspecialchars($th['ten_thuong_hieu']); ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <main class="main-content">
                <div class="filter-header">
                    <h2>Tìm sản phẩm theo nhu cầu</h2>
                    <button class="btn-filter-now"><i class="fa-solid fa-filter"></i> Dùng bộ lọc ngay</button>
                </div>
                <div class="results-count">Tìm thấy <?php echo isset($data['total_products']) ? $data['total_products'] : mysqli_num_rows($data['dssp']); ?> kết quả</div>

                <section class="product-section">
                    <div class="product-grid">
                        <?php while ($sp = mysqli_fetch_assoc($data['dssp'])): ?>
                            <a href="<?php echo UrlHelper::url('Khachhang/chitietsanpham/' . $sp['ma_san_pham']); ?>"
                                class="product-link">
                                <div class="product-card">
                                    <span
                                        class="sticker-sale">-<?php echo isset($sp['giam_gia']) ? $sp['giam_gia'] : '10'; ?>%</span>
                                    <?php if (isset($sp['img_bien_the']) && $sp['img_bien_the']): ?>
                                        <img src="<?php echo UrlHelper::url('Public/Pictures/bien_the/') . htmlspecialchars($sp['img_bien_the']); ?>"
                                            alt="<?php echo htmlspecialchars($sp['ten_san_pham'] ?? ''); ?>"
                                            style="width:100%;height:180px;object-fit:contain;margin-bottom:15px;" />
                                    <?php else: ?>
                                        <img src="<?php echo UrlHelper::url('Public/Images/no-image.png'); ?>"
                                            alt="<?php echo htmlspecialchars($sp['ten_san_pham'] ?? ''); ?>"
                                            style="width:100%;height:180px;object-fit:contain;margin-bottom:15px;">
                                    <?php endif; ?>



                                    <h3 class="product-name"><?php echo htmlspecialchars($sp['ten_san_pham'] ?? ''); ?></h3>
                                    <div class="product-price-box">
                                        <div class="price-row">
                                            <span
                                                class="old-price"><?php echo isset($sp['gia_cu']) ? number_format($sp['gia_cu'], 0, ',', '.') . '₫' : (isset($sp['gia']) ? number_format($sp['gia'], 0, ',', '.') . '₫' : 'Liên hệ'); ?></span>
                                            <?php if (isset($sp['giam_gia']) && $sp['giam_gia'] > 0): ?>
                                                <span class="discount-percent">-<?php echo $sp['giam_gia']; ?>%</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="current-price">
                                            <?php echo isset($sp['gia_moi']) ? number_format($sp['gia_moi'], 0, ',', '.') . '₫' : (isset($sp['gia']) ? number_format($sp['gia'], 0, ',', '.') . '₫' : 'Liên hệ'); ?>
                                        </div>
                                        <div class="price-discount-text">Giảm
                                            <?php echo isset($sp['gia_cu']) && isset($sp['gia_moi']) ? number_format($sp['gia_cu'] - $sp['gia_moi'], 0, ',', '.') . '₫' : (isset($sp['gia']) && isset($sp['giam_gia']) ? number_format($sp['gia'] * $sp['giam_gia'] / 100, 0, ',', '.') . '₫' : 'Liên hệ'); ?>
                                        </div>
                                        <?php if (isset($sp['so_luong_kho'])): ?>
                                            <div class="stock-status">
                                                <?php 
                                                if ($sp['so_luong_kho'] > 5) {
                                                    echo '<span style="color: green;">Còn hàng</span>';
                                                } elseif ($sp['so_luong_kho'] > 0) {
                                                    echo '<span style="color: orange;">Sắp hết hàng (' . $sp['so_luong_kho'] . ')</span>';
                                                } else {
                                                    echo '<span style="color: red;">Hết hàng</span>';
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="buy-btn">Mua ngay</div>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </section>
                
                <!-- Pagination Controls -->
                <?php if (isset($data['total_pages']) && $data['total_pages'] > 1): ?>
                <div class="pagination-container" style="margin-top: 30px; text-align: center;">
                    <div class="pagination">
                        <?php
                        $current_page = $data['current_page'];
                        $total_pages = $data['total_pages'];
                        
                        // Previous button
                        if ($current_page > 1) {
                            echo '<a href="' . UrlHelper::url("Khachhang?page=" . ($current_page - 1)) . '" class="pagination-btn">&laquo; Trước</a>';
                        } else {
                            echo '<span class="pagination-btn disabled">&laquo; Trước</span>';
                        }
                        
                        // Page numbers
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        if ($start_page > 1) {
                            echo '<a href="' . UrlHelper::url("Khachhang?page=1") . '" class="pagination-btn">1</a>';
                            if ($start_page > 2) {
                                echo '<span class="pagination-ellipsis">...</span>';
                            }
                        }
                        
                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($i == $current_page) {
                                echo '<span class="pagination-btn active">' . $i . '</span>';
                            } else {
                                echo '<a href="' . UrlHelper::url("Khachhang?page=" . $i) . '" class="pagination-btn">' . $i . '</a>';
                            }
                        }
                        
                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<span class="pagination-ellipsis">...</span>';
                            }
                            echo '<a href="' . UrlHelper::url("Khachhang?page=" . $total_pages) . '" class="pagination-btn">' . $total_pages . '</a>';
                        }
                        
                        // Next button
                        if ($current_page < $total_pages) {
                            echo '<a href="' . UrlHelper::url("Khachhang?page=" . ($current_page + 1)) . '" class="pagination-btn">Tiếp &raquo;</a>';
                        } else {
                            echo '<span class="pagination-btn disabled">Tiếp &raquo;</span>';
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </main>

        </div>

    </div>

</div>

<script>
    // Lấy các phần tử
    const accountBtn = document.getElementById('accountBtn');
    const accountMenu = document.getElementById('accountMenu');

    // Toggle Account Menu
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

    // Variables to store current filter selections
    let currentCategory = '';
    let currentPriceRange = '';
    let currentBrand = '';

    // Handle price filter selection
    const priceFilters = document.querySelectorAll('input[name="price"]');
    priceFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            if (this.checked && this.value) {
                currentPriceRange = this.value;
                // Filter products based on selected price range, current category and current brand
                filterProductsByBoth(currentCategory, currentPriceRange, currentBrand);
            }
        });
    });

    // Handle category filter selection
    const categoryFilters = document.querySelectorAll('input[name="category"]');
    categoryFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            if (this.checked) {
                if (this.value && this.value !== '') {
                    currentCategory = this.value;
                } else {
                    // If "Tất cả" is selected (no value), set category to empty
                    currentCategory = '';
                }
                // Filter products based on selected category, current price range and current brand
                filterProductsByBoth(currentCategory, currentPriceRange, currentBrand);
            }
        });
    });

    // Handle brand filter selection
    const brandFilters = document.querySelectorAll('input[name="brand"]');
    brandFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            if (this.checked) {
                if (this.value && this.value !== '') {
                    currentBrand = this.value;
                } else {
                    // If "Tất cả" is selected (no value), set brand to empty
                    currentBrand = '';
                }
                // Filter products based on selected brand, current category and current price range
                filterProductsByBoth(currentCategory, currentPriceRange, currentBrand);
            }
        });
    });

    // Function to filter products by category, price range and brand
    function filterProductsByBoth(categoryId, priceRange, brandId) {
        // Show loading indicator
        const productGrid = document.querySelector('.product-grid');
        productGrid.innerHTML = '<div class="loading">Đang tải sản phẩm...</div>';

        // Prepare form data
        let formData = new FormData();
        if (categoryId) {
            formData.append('category_id', categoryId);
        }
        if (priceRange) {
            formData.append('price_range', priceRange);
        }
        if (brandId) {
            formData.append('brand_id', brandId);
        }

        // Log the URL for debugging
        console.log('Request URL:', '<?php echo UrlHelper::url("Khachhang/filter_by_both"); ?>');
        console.log('Category ID:', categoryId, 'Price Range:', priceRange, 'Brand ID:', brandId);

        // Make an AJAX request to get filtered products
        fetch('<?php echo UrlHelper::url("Khachhang/filter_by_both"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.error) {
                    console.error('Server error:', data.error);
                    productGrid.innerHTML = `<p class="error">Lỗi máy chủ: ${data.error}</p>`;
                    return;
                }
                updateProductGrid(data.products);
                document.querySelector('.results-count').textContent = `Tìm thấy ${data.count} kết quả`;
            })
            .catch(error => {
                console.error('Error details:', error);
                productGrid.innerHTML = '<p class="error">Lỗi khi tải sản phẩm. Vui lòng thử lại sau.</p>';
            });
    }

    // Function to update the product grid with new data
    function updateProductGrid(products) {
        const productGrid = document.querySelector('.product-grid');
        productGrid.innerHTML = '';

        if (products.length > 0) {
            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';

                // Format prices with proper checks
                const giaCuFormatted = product.gia_cu ?
                    new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(product.gia_cu) :
                    (product.gia ?
                        new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(product.gia) :
                        'Liên hệ');

                const giaMoiFormatted = product.gia_moi ?
                    new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(product.gia_moi) :
                    (product.gia ?
                        new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(product.gia) :
                        'Liên hệ');

                const discountAmount = product.gia_cu && product.gia_moi ?
                    new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(product.gia_cu - product.gia_moi) :
                    (product.gia && product.giam_gia ?
                        new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(product.gia * product.giam_gia / 100) :
                        'Liên hệ');

                // Create the product link wrapper
                const productLink = document.createElement('a');
                productLink.className = 'product-link';
                productLink.href =
                    `<?php echo UrlHelper::url('Khachhang/chitietsanpham/'); ?>${product.ma_san_pham}`;

                let productHtml = `<span class="sticker-sale">-${product.giam_gia || '0'}%</span>`;

                const baseUrl = '<?php echo UrlHelper::url(); ?>';
                if (product.img_bien_the) {
                    productHtml +=
                        `<img src="${baseUrl}Public/Pictures/bien_the/${encodeURIComponent(product.img_bien_the)}" alt="${product.ten_san_pham || ''}" style="width:100%;height:180px;object-fit:contain;margin-bottom:15px;" />`;
                } else {
                    productHtml +=
                        `<img src="${baseUrl}Public/Images/no-image.png" alt="${product.ten_san_pham || ''}" style="width:100%;height:180px;object-fit:contain;margin-bottom:15px;" />`;
                }

                productHtml += `<h3 class="product-name">${product.ten_san_pham || ''}</h3>
                <div class="product-price-box">
                    <div class="price-row">
                        <span class="old-price">${giaCuFormatted}</span>`;

                if (product.giam_gia && product.giam_gia > 0) {
                    productHtml += `<span class="discount-percent">-${product.giam_gia}%</span>`;
                }

                productHtml += `</div>
                <div class="current-price">${giaMoiFormatted}</div>
                <div class="price-discount-text">Giảm ${discountAmount}</div>
            </div>
            <div class="buy-btn">Mua ngay</div>`;

                productLink.innerHTML = productHtml;
                productCard.appendChild(productLink);
                productGrid.appendChild(productCard);
            });
        } else {
            productGrid.innerHTML = '<p class="no-products">Không tìm thấy sản phẩm nào phù hợp.</p>';
        }
    }
</script>