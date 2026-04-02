<?php
include_once __DIR__ . '/../../../../Public/Classes/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['san_pham']['ten_san_pham']; ?> - TechZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght @300;400;500;700&display=swap" rel="stylesheet">

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

        /* --- 5. PRODUCT DETAIL MAIN --- */
        .product-detail-wrapper {
            padding: 20px 0;
            background: white;
        }

        .product-header {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .product-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .product-rating {
            font-size: 14px;
            color: #f59e0b;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .rating-count {
            color: #777;
            font-size: 13px;
            margin-left: 5px;
        }

        .detail-layout {
            display: grid;
            grid-template-columns: 35% 40% 25%;
            gap: 20px;
        }

        /* Cột 1: Ảnh */
        .gallery-box {
            position: relative;
        }

        .main-image-frame {
            width: 100%;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            position: relative;
            height: 400px;
        }

        .main-image-frame img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: opacity 0.3s ease;
        }

        .discount-badge-circle {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--tet-red);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-size: 13px;
            z-index: 2;
        }

        .thumbnail-list {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .thumb-item {
            width: 60px;
            height: 60px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            cursor: pointer;
        }

        .thumb-item.active {
            border-color: var(--tet-red);
        }

        .thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
        }

        .product-meta {
            margin-top: 15px;
            font-size: 13px;
            color: #555;
            text-align: center;
        }

        /* Cột 2: Thông tin & Option */
        .price-box {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            margin-bottom: 15px;
        }

        .new-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--tet-red);
        }

        .old-price {
            font-size: 16px;
            color: #999;
            text-decoration: line-through;
        }

        .option-group {
            margin-bottom: 15px;
        }

        .option-label {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            display: block;
        }

        .option-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .color-btn {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 10px;
            cursor: pointer;
            transition: 0.2s;
            position: relative;
            min-height: 70px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .color-btn:hover {
            border-color: #999;
        }

        .color-btn.selected {
            border-color: var(--tet-red);
        }

        .color-btn.selected::after {
            content: "✔";
            position: absolute;
            top: -1px;
            right: -1px;
            background: var(--tet-red);
            color: white;
            font-size: 8px;
            width: 14px;
            height: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom-left-radius: 4px;
        }

        .color-info span {
            display: block;
            font-size: 13px;
            font-weight: 600;
        }

        .color-info small {
            font-size: 11px;
            color: #777;
        }
        
        /* Cập nhật lại layout cho color-btn để chứa 3 hàng thông tin */
        .color-btn {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 10px;
            cursor: pointer;
            transition: 0.2s;
            position: relative;
            min-height: 60px;
        }

        .storage-grid {
            display: flex;
            gap: 10px;
        }

        .storage-btn {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            position: relative;
        }

        .storage-btn.selected {
            border-color: var(--tet-red);
            color: var(--tet-red);
        }

        .storage-btn.selected::after {
            content: "✔";
            position: absolute;
            top: -1px;
            right: -1px;
            background: var(--tet-red);
            color: white;
            font-size: 8px;
            width: 12px;
            height: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Stock information styling */
        .stock-info {
            font-size: 11px;
            margin-top: 3px;
            padding: 2px 5px;
            border-radius: 3px;
            display: inline-block;
        }
        
        .in-stock {
            background-color: #d4edda;
            color: #155724;
        }
        
        .out-of-stock {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* Variant specification styling */
        .variant-specs {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 2px;
        }
        
        .variant-price {
            font-weight: 700;
            color: var(--tet-red);
            font-size: 13px;
            margin-bottom: 2px;
        }

        /* --- STYLES CHO PHẦN SỐ LƯỢNG VÀ NÚT THÊM VÀO GIỎ --- */
        .quantity-cart-box {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .quantity-selector {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 4px;
            height: 40px;
        }

        .quantity-selector button {
            width: 35px;
            border: none;
            background: #f9f9f9;
            cursor: pointer;
            font-size: 16px;
            color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-selector button:hover {
            background: #eee;
        }

        .quantity-selector input {
            width: 40px;
            border: none;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
            font-size: 14px;
            outline: none;
            color: #333;
        }

        .add-to-cart-btn {
            background: var(--tet-red);
            color: white;
            border: none;
            height: 40px;
            padding: 0 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }

        .add-to-cart-btn:hover {
            opacity: 0.8;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* --- Action Area --- */
        .action-area {
            margin-top: 0;
        }

        .buy-now-btn {
            width: 100%;
            background: var(--tet-red);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            cursor: pointer;
            margin-bottom: 10px;
            transition: 0.2s;
            border-radius: 20px;
        }

        .buy-now-btn:hover {
            opacity: 0.7;
        }

        .buy-now-sub {
            display: block;
            font-size: 12px;
            font-weight: 400;
            text-transform: none;
            margin-top: 3px;
        }

        .installment-row {
            display: flex;
            gap: 10px;
        }

        .blue-btn {
            flex: 1;
            background: var(--blue-btn);
            color: white;
            border: none;
            padding: 6px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            text-align: center;
        }

        .blue-btn:hover {
            opacity: 0.8;
        }

        .blue-btn span {
            display: block;
            font-size: 11px;
            font-weight: 400;
            text-transform: none;
            margin-top: 2px;
        }

        /* Cột 3: Khuyến mãi */
        .promo-box {
            border: 1px solid #fee2e2;
            border-radius: 8px;
            overflow: hidden;
        }

        .promo-header {
            background: #fee2e2;
            color: #d70018;
            padding: 10px 15px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
        }

        .promo-content {
            padding: 15px;
        }

        .promo-list li {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
            align-items: flex-start;
        }

        .promo-number {
            background: var(--tet-red);
            color: white;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Phần đánh giá
         /* --- CSS CHO PHẦN ĐÁNH GIÁ (REVIEW SECTION) --- */
        .review-section {
            background: #fff;
            padding: 2px 0;
            margin-bottom: 30px;
            border-top: 4px solid #f4f4f4;
            /* Ngăn cách với phần trên */
        }

        .review-header {
            font-size: 15px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            display: inline-block;
            background-color: #e0f2fe;
            /* Nền xanh nhạt tiêu đề */
            padding: 5px 15px;
            color: #0284c7;
            border-radius: 4px;
        }

        /* Khung tổng quan đánh giá (3 cột) */
        .rating-overview-box {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            display: flex;
            padding: 3px;
            margin-bottom: 20px;
            align-items: center;
        }

        /* Cột 1: Điểm số to */
        .rating-left {
            width: 20%;
            text-align: center;
            border-right: 1px solid #eee;
        }

        .score-big {
            font-size: 36px;
            font-weight: 700;
            color: #d7912a;
            /* Màu vàng cam */
            line-height: 1;
        }

        .star-big {
            color: #d7912a;
            font-size: 24px;
            margin: 5px 0;
        }

        .rating-count-text {
            font-size: 13px;
            color: #555;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Cột 2: Thanh tiến trình (Progress bars) */
        .rating-middle {
            width: 50%;
            padding: 0 30px;
            border-right: 1px solid #eee;
        }

        .rating-bar-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }

        .rating-bar-row:last-child {
            margin-bottom: 0;
        }

        .star-label {
            width: 45px;
            font-weight: 600;
            display: flex;
            /* Quan trọng: Giúp số và sao nằm ngang hàng */
            align-items: center;
            justify-content: flex-start;
            white-space: nowrap;
            /* Cấm xuống dòng */
        }

        .star-icon-small {
            color: #d7912a;
            margin-left: 5px;
            /* Cách số ra 1 chút cho đẹp */
            font-size: 12px;
        }

        .star-icon-small {
            color: #d7912a;
            margin-right: 10px;
            font-size: 12px;
        }

        .progress-bg {
            flex: 1;
            height: 10px;
            background-color: #eee;
            border-radius: 5px;
            margin: 0 15px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #d7912a;
            border-radius: 5px;
        }

        .rating-percent-text {
            width: 110px;
            text-align: right;
            color: #0284c7;
            /* Màu xanh chữ */
            font-size: 13px;
        }

        /* Cột 3: Nút đánh giá */
        .rating-right {
            width: 30%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-write-review {
            background-color: #2d72d2;
            /* Màu xanh nút */
            color: white;
            font-weight: 700;
            padding: 12px 30px;
            border-radius: 4px;
            border: none;
            text-transform: uppercase;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-write-review:hover {
            background-color: #1e4bd1;
        }

        /* --- DANH SÁCH REVIEW CHI TIẾT --- */
        .review-item {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            background-color: #ccc;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 24px;
        }

        .review-content {
            flex: 1;
        }

        .user-name {
            font-weight: 700;
            color: #333;
            margin-right: 10px;
        }

        .verified-badge {
            color: #2ea868;
            /* Màu xanh lá */
            font-size: 13px;
            font-style: italic;
        }

        .verified-badge i {
            margin-right: 3px;
        }

        .user-rating-stars {
            color: #d7912a;
            font-size: 13px;
            margin: 5px 0;
        }

        .review-text {
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .review-actions {
            font-size: 13px;
            color: #888;
        }

        .review-actions a {
            color: #2d72d2;
            margin-right: 5px;
            font-weight: 500;
        }

        /* --- MODAL FOR REVIEW --- */
        .review-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .review-modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .no-reviews {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }

        /* --- SELLER RESPONSE STYLING --- */
        .seller-response {
            margin-top: 10px;
            padding: 10px;
            background-color: #f0f8ff;
            border-left: 3px solid #2d72d2;
            border-radius: 0 4px 4px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .response-header {
            font-weight: 600;
            color: #2d72d2;
            font-size: 13px;
            white-space: nowrap;
        }

        .response-content {
            font-size: 13px;
            color: #333;
            flex: 1;
        }

        /* --- 6. SIMILAR PRODUCTS --- */
        .similar-section {
            background: white;
            padding: 30px 0;
            border-top: 10px solid #f4f4f4;
        }

        .similar-heading {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: #333;
            position: relative;
        }

        .similar-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .sim-card {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            padding: 15px;
            position: relative;
            transition: all 0.3s ease;
            background: white;
        }

        .sim-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .sim-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--tet-red);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 11px;
            font-weight: 700;
            z-index: 2;
        }

        .sim-img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sim-img img {
            max-width: 100%;
            max-height: 100%;
        }

        .sim-title {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.4;
            height: 40px;
            overflow: hidden;
        }

        .sim-price {
            color: var(--tet-red);
            font-weight: 700;
            font-size: 15px;
            display: block;
        }

        .sim-old-price {
            color: #999;
            text-decoration: line-through;
            font-size: 12px;
            margin-left: 5px;
            font-weight: 400;
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



    <div class="breadcrumb">
        <div class="container">
            <a href="<?php echo $this->url('Khachhang'); ?>">Trang chủ</a> /
            <a
                href="<?php echo $this->url('Khachhang/sanpham_theo_danhmuc/' . $data['san_pham']['ma_danh_muc']); ?>"><?php echo $data['san_pham']['ten_danh_muc']; ?></a>
            /
            <span><?php echo $data['san_pham']['ten_san_pham']; ?></span>
        </div>
    </div>

    <div class="product-detail-wrapper">
        <div class="container">

            <div class="product-header">
                <h1 class="product-title" id="productTitle"><?php echo $data['san_pham']['ten_san_pham']; ?></h1>
                <div class="product-rating">
                    <?php if ($data['avg_rating']): ?>
                        <?php
                        $avg_rating = round($data['avg_rating'], 1);
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $avg_rating) {
                                echo '<i class="fa-solid fa-star"></i>';
                            } else {
                                if ($i - $avg_rating < 1) {
                                    echo '<i class="fa-solid fa-star-half-stroke"></i>';
                                } else {
                                    echo '<i class="fa-regular fa-star"></i>';
                                }
                            }
                        }
                        ?>
                        <span class="rating-count">(<?php echo mysqli_num_rows($data['danh_gia']); ?> Đánh giá)</span>
                    <?php else: ?>
                        <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                            class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
                            class="fa-regular fa-star"></i>
                        <span class="rating-count">(0 Đánh giá)</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-layout">

                <div class="product-left">
                    <div class="gallery-box">
                        <div class="main-image-frame">
                            <span
                                class="discount-badge-circle">-<?php echo isset($data['san_pham']['giam_gia']) ? $data['san_pham']['giam_gia'] : '0'; ?>%</span>
                            <img id="mainImage"
                                src="<?php echo !empty($data['bien_the_first']['img_bien_the']) ? UrlHelper::url('Public/Pictures/bien_the/') . htmlspecialchars($data['bien_the_first']['img_bien_the']) : UrlHelper::url('Public/Images/no-image.png'); ?>"
                                alt="<?php echo $data['san_pham']['ten_san_pham']; ?>">
                        </div>
                        <div class="thumbnail-list">
                            <?php
                            $first = true;
                            if (mysqli_num_rows($data['bien_the']) > 0) {
                                mysqli_data_seek($data['bien_the'], 0); // Reset pointer to beginning
                                while ($bt = mysqli_fetch_assoc($data['bien_the'])) {
                                    $class = $first ? 'thumb-item active' : 'thumb-item';
                                    $imgUrl = !empty($bt['img_bien_the']) ? UrlHelper::url('Public/Pictures/bien_the/') . htmlspecialchars($bt['img_bien_the']) : UrlHelper::url('Public/Images/no-image.png');
                                    echo '<div class="' . $class . '" data-image="' . $imgUrl . '"><img src="' . $imgUrl . '" alt=""></div>';
                                    $first = false;
                                }
                            } else {
                                $mainImgUrl = !empty($data['san_pham']['img_hinh_anh']) ? UrlHelper::url('Public/Pictures/sanpham/') . htmlspecialchars($data['san_pham']['img_hinh_anh']) : UrlHelper::url('Public/Images/no-image.png');
                                echo '<div class="thumb-item active" data-image="' . $mainImgUrl . '"><img src="' . $mainImgUrl . '" alt=""></div>';
                            }
                            ?>
                        </div>
                        <div class="product-meta">
                            Mã sản phẩm: <strong><?php echo $data['san_pham']['ma_san_pham']; ?></strong> | Danh mục:
                            <strong><?php echo $data['san_pham']['ten_danh_muc']; ?></strong>
                        </div>
                    </div>
                </div>

                <div class="product-center">
                    <h5 class="product-title"><?php echo $data['san_pham']['ten_san_pham']; ?></h5>

                    <?php
                    // Find the lowest and highest prices among variants
                    $prices = [];
                    if (mysqli_num_rows($data['bien_the']) > 0) {
                        mysqli_data_seek($data['bien_the'], 0); // Reset pointer to beginning
                        while ($bt = mysqli_fetch_assoc($data['bien_the'])) {
                            $prices[] = isset($bt['gia']) ? $bt['gia'] : 0;
                        }
                    }
                    $min_price = !empty($prices) ? min($prices) : (isset($data['san_pham']['gia']) ? $data['san_pham']['gia'] : 0);
                    $max_price = !empty($prices) ? max($prices) : (isset($data['san_pham']['gia']) ? $data['san_pham']['gia'] : 0);
                    ?>

                    <div class="price-box">
                        <?php if (isset($data['san_pham']['gia_cu']) && $data['san_pham']['gia_cu'] > (isset($data['san_pham']['gia']) ? $data['san_pham']['gia'] : 0)): ?>
                            <span
                                class="old-price"><?php echo number_format(isset($data['san_pham']['gia_cu']) ? $data['san_pham']['gia_cu'] : 0, 0, ',', '.') . ' ₫'; ?></span>
                        <?php endif; ?>
                        <span class="new-price"
                            id="currentPrice"><?php echo number_format($min_price, 0, ',', '.') . ' ₫'; ?>
                            <?php if ($min_price != $max_price): ?>
                                - <?php echo number_format($max_price, 0, ',', '.') . ' ₫'; ?>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="option-group">
                        <span class="option-label" id="variantLabel">Phiên bản</span>
                        <div class="option-grid">
                            <?php
                            $first_variant = true;
                            if (mysqli_num_rows($data['bien_the']) > 0) {
                                mysqli_data_seek($data['bien_the'], 0); // Reset pointer to beginning
                                while ($bt = mysqli_fetch_assoc($data['bien_the'])) {
                                    $class = $first_variant ? 'color-btn selected' : 'color-btn';
                                    $variant_image = !empty($bt['img_bien_the']) ? BASE_URL . 'Public/Pictures/bien_the/' . htmlspecialchars($bt['img_bien_the']) : BASE_URL . 'Public/Images/no-image.png';
                                    echo '<div class="' . $class . '" data-variant-image="' . $variant_image . '" onclick="selectVariant(this, \'' . $bt['ten_bien_the'] . '\', \'' . $variant_image . '\')">';
                                    echo '<input type="radio" name="ma_bien_the" value="' . $bt['ma_bien_the'] . '" ' . ($first_variant ? 'checked' : '') . ' style="display:none;">';

                                    // Create display text for the variant
                                    $variant_text = '';
                                    if (isset($bt['mau_sac']) && $bt['mau_sac']) $variant_text .= $bt['mau_sac'];
                                    if (isset($bt['dung_luong']) && $bt['dung_luong']) $variant_text .= ($variant_text ? ' - ' : '') . $bt['dung_luong'];
                                    if (isset($bt['ram']) && $bt['ram']) $variant_text .= ($variant_text ? ' - ' : '') . $bt['ram'];

                                    echo '<div class="variant-specs">' . ($variant_text ? $variant_text : $bt['ten_bien_the']) . '</div>';
                                    echo '<div class="variant-price">' . number_format(isset($bt['gia']) ? $bt['gia'] : 0, 0, ',', '.') . '₫</div>';
                                    
                                    // Show stock information
                                    $stock_status = $bt['so_luong_kho'] > 0 ? 'Còn hàng' : 'Hết hàng';
                                    $stock_class = $bt['so_luong_kho'] > 0 ? 'in-stock' : 'out-of-stock';
                                    echo '<div class="stock-info ' . $stock_class . '">(' . $stock_status . ': ' . $bt['so_luong_kho'] . ' sản phẩm)</div>';
                                    
                                    echo '</div>';
                                    $first_variant = false;
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="quantity-cart-box">
                        <div class="quantity-selector">
                            <button onclick="decreaseQuantity()">-</button>
                            <input type="text" id="quantityInput" value="1" readonly>
                            <button onclick="increaseQuantity()">+</button>
                        </div>

                        <?php if (mysqli_num_rows($data['bien_the']) > 0): ?>
                            <button class="add-to-cart-btn" id="addToCartBtn" onclick="addToCart()">
                                <i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ
                            </button>
                        <?php else: ?>
                            <button class="add-to-cart-btn" disabled>
                                <i class="fa-solid fa-cart-plus"></i> TẠM HẾT HÀNG
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="action-area">
                        <button class="buy-now-btn" id="buyNowBtn" onclick="buyNow()">
                            MUA NGAY
                            <span class="buy-now-sub">Giao hàng tận nơi hoặc nhận tại cửa hàng</span>
                        </button>
                        <div class="installment-row">
                            <button class="blue-btn">
                                TRẢ GÓP 0%
                                <span>Xét duyệt qua điện thoại</span>
                            </button>
                            <button class="blue-btn">
                                TRẢ GÓP QUA THẺ
                                <span>Visa, Master Card, JCB</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-right">
                    <div class="promo-box">
                        <div class="promo-header">Thông tin sản phẩm</div>
                        <div class="promo-content">
                            <ul class="promo-list">
                                <li>
                                    <span class="promo-number">1</span>
                                    <span><strong>Thương hiệu:</strong>
                                        <?php echo $data['san_pham']['ten_thuong_hieu']; ?></span>
                                </li>
                                <li>
                                    <span class="promo-number">2</span>
                                    <span><strong>Nhà cung cấp:</strong>
                                        <?php echo $data['san_pham']['ten_nha_cung_cap']; ?></span>
                                </li>
                                <li>
                                    <span class="promo-number">3</span>
                                    <span><strong>Danh mục:</strong>
                                        <?php echo $data['san_pham']['ten_danh_muc']; ?></span>
                                </li>
                                <li>
                                    <span class="promo-number">4</span>
                                    <span><strong>Mô tả:</strong>
                                        <?php echo isset($data['san_pham']['mo_ta']) ? substr($data['san_pham']['mo_ta'], 0, 100) : 'Chưa có mô tả'; ?>...</span>
                                </li>
                                <li>
                                    <span class="promo-number">5</span>
                                    <span><strong>Bảo hành:</strong> 12 tháng chính hãng</span>
                                </li>
                                <li>
                                    <span class="promo-number">6</span>
                                    <span><strong>Khuyến mãi:</strong> Trả góp 0% lãi suất</span>
                                </li>
                                <li>
                                    <span class="promo-number">7</span>
                                    <span><strong>Ưu đãi thêm:</strong> Giảm 50k phí vận chuyển</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="review-section">
            <div class="review-header"><?php echo mysqli_num_rows($data['danh_gia']); ?> đánh giá cho <?php echo $data['san_pham']['ten_san_pham']; ?></div>

            <div class="rating-overview-box">
                <div class="rating-left">
                    <div class="score-big"><?php echo $data['avg_rating'] ? number_format($data['avg_rating'], 2) : '0.00'; ?> <i class="fa-solid fa-star" style="font-size: 24px;"></i></div>
                    <div class="rating-count-text">Đánh giá trung bình</div>
                </div>

                <div class="rating-middle">
                    <?php
                    $total_reviews = mysqli_num_rows($data['danh_gia']);
                    $star_dist = $data['star_distribution'];

                    // Calculate percentages
                    $percentages = [];
                    foreach($star_dist as $stars => $count) {
                        $percentages[$stars] = $total_reviews > 0 ? round(($count / $total_reviews) * 100, 0) : 0;
                    }
                    ?>

                    <div class="rating-bar-row">
                        <div class="star-label">5 <i class="fa-solid fa-star star-icon-small"></i></div>
                        <div class="progress-bg">
                            <div class="progress-fill" style="width: <?php echo $percentages[5]; ?>%;"></div>
                        </div>
                        <div class="rating-percent-text"><?php echo $percentages[5]; ?>% | <?php echo $star_dist[5]; ?> đánh giá</div>
                    </div>
                    <div class="rating-bar-row">
                        <div class="star-label">4 <i class="fa-solid fa-star star-icon-small"></i></div>
                        <div class="progress-bg">
                            <div class="progress-fill" style="width: <?php echo $percentages[4]; ?>%;"></div>
                        </div>
                        <div class="rating-percent-text"><?php echo $percentages[4]; ?>% | <?php echo $star_dist[4]; ?> đánh giá</div>
                    </div>
                    <div class="rating-bar-row">
                        <div class="star-label">3 <i class="fa-solid fa-star star-icon-small"></i></div>
                        <div class="progress-bg">
                            <div class="progress-fill" style="width: <?php echo $percentages[3]; ?>%;"></div>
                        </div>
                        <div class="rating-percent-text"><?php echo $percentages[3]; ?>% | <?php echo $star_dist[3]; ?> đánh giá</div>
                    </div>
                    <div class="rating-bar-row">
                        <div class="star-label">2 <i class="fa-solid fa-star star-icon-small"></i></div>
                        <div class="progress-bg">
                            <div class="progress-fill" style="width: <?php echo $percentages[2]; ?>%;"></div>
                        </div>
                        <div class="rating-percent-text"><?php echo $percentages[2]; ?>% | <?php echo $star_dist[2]; ?> đánh giá</div>
                    </div>
                    <div class="rating-bar-row">
                        <div class="star-label">1 <i class="fa-solid fa-star star-icon-small"></i></div>
                        <div class="progress-bg">
                            <div class="progress-fill" style="width: <?php echo $percentages[1]; ?>%;"></div>
                        </div>
                        <div class="rating-percent-text"><?php echo $percentages[1]; ?>% | <?php echo $star_dist[1]; ?> đánh giá</div>
                    </div>
                </div>

                <div class="rating-right">
                    <button class="btn-write-review" onclick="openReviewModal()">ĐÁNH GIÁ NGAY</button>
                </div>
            </div>

            <div class="review-list">
                <?php
                if (mysqli_num_rows($data['danh_gia']) > 0) {
                    mysqli_data_seek($data['danh_gia'], 0); // Reset pointer to beginning
                    while ($review = mysqli_fetch_assoc($data['danh_gia'])) {
                        echo '<div class="review-item">';
                        echo '<div class="user-avatar"><i class="fa-solid fa-user"></i></div>';
                        echo '<div class="review-content">';
                        echo '<div>';
                        echo '<span class="user-name">' . htmlspecialchars($review['full_name']) . '</span>';
                        echo '<span class="verified-badge"><i class="fa-solid fa-circle-check"></i> Đã mua tại TechZone</span>';
                        echo '</div>';

                        // Generate star rating
                        echo '<div class="user-rating-stars">';
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $review['so_sao']) {
                                echo '<i class="fa-solid fa-star"></i>';
                            } else {
                                if ($i - $review['so_sao'] < 1) {
                                    echo '<i class="fa-regular fa-star-half-stroke"></i>';
                                } else {
                                    echo '<i class="fa-regular fa-star"></i>';
                                }
                            }
                        }
                        echo '</div>';

                        echo '<div class="review-text">' . htmlspecialchars($review['noi_dung']) . '</div>';

                        // Display seller's response if available
                        if (!empty($review['phan_hoi'])) {
                            echo '<div class="seller-response"><div class="response-header">Phản hồi từ người bán:</div><div class="response-content">' . htmlspecialchars($review['phan_hoi']) . '</div></div>';
                        }

                        echo '<div class="review-actions">';
                        echo '<a href="#">Trả lời</a> • ' . date('d/m/Y', strtotime($review['ngay_danh_gia']));
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="no-reviews">Chưa có đánh giá nào cho sản phẩm này.</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="similar-section">
        <div class="container">
            <h3 class="similar-heading">SẢN PHẨM TƯƠNG TỰ <i class="fa-solid fa-circle-check"
                    style="color:#0fb30f; font-size:16px; margin-left:5px;"></i></h3>
            <div class="similar-grid">

                <?php
                if (isset($data['similar_products']) && mysqli_num_rows($data['similar_products']) > 0) {
                    while ($sp = mysqli_fetch_assoc($data['similar_products'])) {
                        echo '<div class="sim-card">';
                        echo '<div class="sim-badge">-' . (isset($sp['giam_gia']) ? $sp['giam_gia'] : '0') . '%</div>';
                        echo '<a href="' . $this->url('Khachhang/chitietsanpham/' . $sp['ma_san_pham']) . '" class="sim-img">';
                        $img_src = !empty($sp['img_bien_the']) ? BASE_URL . 'Public/Pictures/bien_the/' . htmlspecialchars($sp['img_bien_the']) : (!empty($sp['img_hinh_anh']) ? BASE_URL . 'Public/Pictures/sanpham/' . htmlspecialchars($sp['img_hinh_anh']) : BASE_URL . 'Public/Images/no-image.png');
                        echo '<img src="' . $img_src . '" alt="' . $sp['ten_san_pham'] . '">';
                        echo '</a>';
                        echo '<div class="sim-title">' . $sp['ten_san_pham'] . '</div>';
                        echo '<span class="sim-price">' . number_format(isset($sp['gia']) ? $sp['gia'] : (isset($sp['gia_cu']) ? $sp['gia_cu'] : 0), 0, ',', '.') . ' ₫</span>';
                        echo '</div>';
                    }
                } else {
                    // Display placeholder products if no similar products found
                    for ($i = 0; $i < 4; $i++) {
                        echo '<div class="sim-card">';
                        echo '<div class="sim-badge">-10%</div>';
                        echo '<a href="#" class="sim-img">';
                        echo '<img src="https://placehold.co/300x300" alt="Sản phẩm tương tự">';
                        echo '</a>';
                        echo '<div class="sim-title">Sản phẩm tương tự ' . ($i + 1) . '</div>';
                        echo '<span class="sim-price">' . number_format(10000000, 0, ',', '.') . ' ₫</span>';
                        echo '</div>';
                    }
                }
                ?>

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

        // --- 1. BIẾN TOÀN CỤC GIỎ HÀNG ---
        var cart = []; // Mảng chứa các object sản phẩm

        // --- 2. XỬ LÝ GIAO DIỆN CƠ BẢN ---
        function selectVariant(element, variantName, imageUrl) {
            var variants = document.querySelectorAll('.color-btn');
            variants.forEach(btn => btn.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('variantLabel').innerText = "Phiên bản: " + variantName;

            // Get the variant image from the selected element's data attribute
            var variantImage = element.getAttribute('data-variant-image');
            if (variantImage) {
                document.getElementById('mainImage').src = variantImage;
            } else {
                document.getElementById('mainImage').src = imageUrl;
            }

            // Reset active thumb
            document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));

            // Select the corresponding radio button
            var radioButton = element.querySelector('input[type="radio"]');
            if (radioButton) {
                radioButton.checked = true;
            }

            // Update price display based on selected variant
            var priceText = element.querySelector('small').textContent;
            var priceValue = priceText.replace(/[^\d]/g, ''); // Extract numeric value
            document.getElementById('currentPrice').innerHTML = formatCurrency(priceValue) + ' ₫';
            
            // Update button visibility based on selected variant stock
            updateButtonsVisibility();
        }

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

        // --- 3. LOGIC GIỎ HÀNG (MỚI & QUAN TRỌNG) ---
        var CART_API_BASE = "<?php echo $this->url('Api/Cart'); ?>";

        // Hàm mở/đóng Sidebar
        function toggleCart() {
            var overlay = document.querySelector('.cart-overlay');
            var sidebar = document.querySelector('.cart-sidebar');
            overlay.classList.toggle('active');
            sidebar.classList.toggle('active');
        }

        function addToCart() {
            // Lấy thông tin biến thể đã chọn
            var selectedVariantInput = document.querySelector('input[name="ma_bien_the"]:checked');

            if (!selectedVariantInput) {
                alert("Vui lòng chọn phiên bản sản phẩm!");
                return;
            }

            // Check if selected variant is in stock
            var selectedVariantElement = selectedVariantInput.closest('.color-btn');
            var stockInfo = selectedVariantElement.querySelector('.stock-info');
            if (stockInfo) {
                var stockText = stockInfo.textContent;
                var stockMatch = stockText.match(/(\d+)/);
                if (stockMatch) {
                    var stockQty = parseInt(stockMatch[1]);
                    if (stockQty <= 0) {
                        alert("Sản phẩm này hiện đã hết hàng!");
                        return;
                    }
                }
            }

            var ma_bien_the = selectedVariantInput.value;
            var quantity = parseInt(document.getElementById('quantityInput').value);

            // Gọi REST API để thêm vào giỏ hàng
            var xhr = new XMLHttpRequest();
            xhr.open("POST", CART_API_BASE, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status >= 200 && xhr.status < 300) {
                    // Hiển thị sidebar giỏ hàng va chi refresh 1 lan de tranh goi API trung lap
                    var sidebar = document.querySelector('.cart-sidebar');
                    if (!sidebar.classList.contains('active')) {
                        // toggleCart (tu master) se tu goi loadMiniCartFromApi() khi mo sidebar
                        toggleCart();
                    } else {
                        // Neu sidebar dang mo thi refresh thu cong 1 lan
                        updateCartFromServer();
                    }
                } else if (xhr.readyState === 4) {
                    if (xhr.status === 401) {
                        alert("Ban can dang nhap de them vao gio hang");
                        window.location.href = '<?php echo $this->url('Login'); ?>';
                        return;
                    }
                    // Neu loi thi chi mo sidebar (neu dang dong) de tranh GET trung lap
                    var sidebar = document.querySelector('.cart-sidebar');
                    if (!sidebar.classList.contains('active')) {
                        toggleCart();
                    }
                }
            };
            xhr.send("ma_bien_the=" + encodeURIComponent(ma_bien_the) + "&so_luong=" + encodeURIComponent(quantity));
        }

        // Hàm cập nhật giỏ hàng từ server
        function updateCartFromServer() {
            // Gọi REST API để lấy dữ liệu giỏ hàng mới
            var xhr = new XMLHttpRequest();
            xhr.open("GET", CART_API_BASE, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    // Cập nhật mảng cart với dữ liệu từ server
                    cart = (response && response.data && response.data.items) ? response.data.items : [];
                    // Cập nhật giao diện giỏ hàng
                    renderCart();

                    // Cập nhật số lượng trên badge trong header
                    var totalQuantity = 0;
                    cart.forEach(item => {
                        totalQuantity += parseInt(item.quantity || 0);
                    });

                    // Cập nhật badge trong header (sử dụng ID duy nhất)
                    var headerCartBadge = document.getElementById('cartBadge');
                    if(headerCartBadge) {
                        headerCartBadge.textContent = totalQuantity;
                    }
                }
            };
            xhr.send();
        }

        // Hàm xóa sản phẩm
        function removeFromCart(ma_bien_the) {
            if(!confirm("Bạn có chắc muốn xóa sản phẩm này?")) return;

            // Gọi REST API để xóa item giỏ hàng
            var xhr = new XMLHttpRequest();
            xhr.open("DELETE", CART_API_BASE + "/" + encodeURIComponent(ma_bien_the), true);
            
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Xóa thành công trên server -> Cập nhật lại giao diện client
                        // Gọi hàm updateCartFromServer() để load lại danh sách mới nhất từ DB
                        updateCartFromServer();
                    } else {
                        alert("Có lỗi xảy ra khi xóa sản phẩm.");
                    }
                }
            };
            xhr.send();
        }

        // Hàm vẽ lại giỏ hàng (Render)
        function renderCart() {
            var cartBody = document.getElementById('cartBody');
            var cartFooter = document.getElementById('cartFooter');

            // 1. Cập nhật số lượng trên icon badge (cả trong sidebar và header)
            var totalQuantity = 0;
            var totalPrice = 0;

            cart.forEach(item => {
                totalQuantity += parseInt(item.quantity || 0);
                totalPrice += ((item.price || 0) * parseInt(item.quantity || 0));
            });

            // Cập nhật badge trong header (element có ID 'cartBadge')
            var headerCartBadge = document.getElementById('cartBadge');
            if(headerCartBadge) {
                headerCartBadge.innerText = totalQuantity;
            }

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
                        <span class="cart-item-variant">${item.variant}</span>
                        <div class="cart-item-price">${item.quantity} x ${item.price.toLocaleString('vi-VN')} ₫</div>
                    </div>
                    <div class="cart-remove-btn" onclick="removeFromCart('${item.ma_bien_the}')">
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
                    <button class="btn-view-cart" onclick="location.href='<?php echo $this->url('Khachhang/giohang'); ?>'">XEM GIỎ HÀNG</button>
                    <button class="btn-checkout" onclick="location.href='<?php echo $this->url('Khachhang/thanhtoan'); ?>'">THANH TOÁN</button>
                </div>
            `;
        }

        // Helper function to format currency
        function formatCurrency(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Function to check if a variant is in stock
        function isVariantInStock(ma_bien_the) {
            // Loop through all variants to find the one with matching ma_bien_the
            var variantElements = document.querySelectorAll('.color-btn');
            for (var i = 0; i < variantElements.length; i++) {
                var radioBtn = variantElements[i].querySelector('input[name="ma_bien_the"][value="' + ma_bien_the + '"]');
                if (radioBtn) {
                    // Get the parent element to check for stock info
                    var stockInfo = variantElements[i].querySelector('.stock-info');
                    if (stockInfo) {
                        var stockText = stockInfo.textContent;
                        var stockMatch = stockText.match(/(\d+)/);
                        if (stockMatch) {
                            var stockQty = parseInt(stockMatch[1]);
                            return stockQty > 0;
                        }
                    }
                    // If no stock info found, assume it's in stock
                    return true;
                }
            }
            return false;
        }

        // Function to update button visibility based on selected variant
        function updateButtonsVisibility() {
            var selectedVariantInput = document.querySelector('input[name="ma_bien_the"]:checked');
            if (selectedVariantInput) {
                var ma_bien_the = selectedVariantInput.value;
                
                // Check if this variant is in stock by looking at the selected variant element
                var selectedVariantElement = selectedVariantInput.closest('.color-btn');
                var stockInfo = selectedVariantElement.querySelector('.stock-info');
                
                if (stockInfo) {
                    var stockText = stockInfo.textContent;
                    var stockMatch = stockText.match(/(\d+)/);
                    if (stockMatch) {
                        var stockQty = parseInt(stockMatch[1]);
                        var addToCartBtn = document.getElementById('addToCartBtn');
                        var buyNowBtn = document.getElementById('buyNowBtn');
                        
                        if (stockQty <= 0) {
                            // Out of stock - disable buttons and show message
                            if (addToCartBtn) {
                                addToCartBtn.disabled = true;
                                addToCartBtn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> TẠM HẾT HÀNG';
                                addToCartBtn.onclick = function() {
                                    alert('Sản phẩm này hiện đã hết hàng!');
                                    return false;
                                };
                            }
                            
                            if (buyNowBtn) {
                                buyNowBtn.disabled = true;
                                buyNowBtn.onclick = function() {
                                    alert('Sản phẩm này hiện đã hết hàng!');
                                    return false;
                                };
                            }
                        } else {
                            // In stock - enable buttons
                            if (addToCartBtn) {
                                addToCartBtn.disabled = false;
                                addToCartBtn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ';
                                addToCartBtn.onclick = function() { addToCart(); };
                            }
                            
                            if (buyNowBtn) {
                                buyNowBtn.disabled = false;
                                buyNowBtn.onclick = function() { buyNow(); };
                            }
                        }
                    }
                }
            }
        }

        // Hàm mua ngay - thêm sản phẩm vào giỏ hàng và chuyển sang trang thanh toán
        function buyNow() {

            <?php if (!isset($_SESSION['user_id'])): ?>
                alert('Bạn cần đăng nhập để mua hàng!');
                window.location.href = '<?php echo $this->url('Login'); ?>';
                return;
            <?php endif; ?>

            var selectedVariantInput = document.querySelector('input[name="ma_bien_the"]:checked');
            if (!selectedVariantInput) {
                alert("Vui lòng chọn phiên bản sản phẩm!");
                return;
            }

            // Check if selected variant is in stock
            var selectedVariantElement = selectedVariantInput.closest('.color-btn');
            var stockInfo = selectedVariantElement.querySelector('.stock-info');
            if (stockInfo) {
                var stockText = stockInfo.textContent;
                var stockMatch = stockText.match(/(\d+)/);
                if (stockMatch) {
                    var stockQty = parseInt(stockMatch[1]);
                    if (stockQty <= 0) {
                        alert("Sản phẩm này hiện đã hết hàng!");
                        return;
                    }
                }
            }

            var ma_bien_the = selectedVariantInput.value;
            var quantity = parseInt(document.getElementById('quantityInput').value);

            // var img = document.getElementById('mainImage').src;
            // var name = document.getElementById('productTitle').innerText;
            // var variantFull = document.getElementById('variantLabel').innerText;
            // var variant = variantFull.replace("Phiên bản: ", "");
            // var quantity = parseInt(document.getElementById('quantityInput').value);
            // var priceStr = document.getElementById('currentPrice').innerText;

            // Chuyển giá từ chuỗi "11.400.000 ₫" sang số để tính toán
            var url = '<?php echo $this->url('Khachhang/thanhtoan'); ?>?items=' + ma_bien_the + '&qty=' + quantity + '&buynow=1';
            window.location.href = url;

            //var price = parseInt(priceStr.replace(/\./g, '').replace(' ₫', ''));


            // var product = {
            //     img: img,
            //     name: name,
            //     variant: variant,
            //     quantity: quantity,
            //     price: price
            // };

            // cart.push(product);

            // renderCart();

            // setTimeout(function() {
            //     window.location.href = '<?php echo $this->url('Khachhang/thanhtoan'); ?>';
            // }, 500);
        }

        // Function to open review modal
        function openReviewModal() {
            // Check if user is logged in
            <?php if (!isset($_SESSION['user_id'])): ?>
                alert('Bạn cần đăng nhập để đánh giá sản phẩm!');
                window.location.href = '<?php echo $this->url('Login'); ?>';
                return;
            <?php else: ?>
                // Create modal HTML
                var modalHtml = `
                    <div id="reviewModal" class="review-modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                        <div class="review-modal-content" style="background: white; padding: 20px; border-radius: 8px; width: 500px; max-width: 90%; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h3 style="margin: 0;">Đánh giá sản phẩm</h3>
                                <button onclick="closeReviewModal()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Số sao:</label>
                                <div id="starRating" style="display: flex; gap: 5px;">
                                    <i class="fa-regular fa-star star-option" data-value="1" style="font-size: 24px; cursor: pointer;" onclick="selectStar(1)"></i>
                                    <i class="fa-regular fa-star star-option" data-value="2" style="font-size: 24px; cursor: pointer;" onclick="selectStar(2)"></i>
                                    <i class="fa-regular fa-star star-option" data-value="3" style="font-size: 24px; cursor: pointer;" onclick="selectStar(3)"></i>
                                    <i class="fa-regular fa-star star-option" data-value="4" style="font-size: 24px; cursor: pointer;" onclick="selectStar(4)"></i>
                                    <i class="fa-regular fa-star star-option" data-value="5" style="font-size: 24px; cursor: pointer;" onclick="selectStar(5)"></i>
                                </div>
                                <input type="hidden" id="selectedRating" value="0">
                            </div>

                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nội dung đánh giá:</label>
                                <textarea id="reviewContent" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Viết đánh giá của bạn về sản phẩm..."></textarea>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <button onclick="submitReview()" style="flex: 1; background: var(--tet-red); color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer;">Gửi đánh giá</button>
                                <button onclick="closeReviewModal()" style="flex: 1; background: #f0f0f0; border: none; padding: 10px; border-radius: 4px; cursor: pointer;">Hủy</button>
                            </div>
                        </div>
                    </div>
                `;

                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalHtml);
            <?php endif; ?>
        }

        // Function to close review modal
        function closeReviewModal() {
            var modal = document.getElementById('reviewModal');
            if(modal) {
                modal.remove();
            }
        }

        // Function to select star rating
        function selectStar(rating) {
            // Reset all stars
            var stars = document.querySelectorAll('.star-option');
            stars.forEach(star => {
                star.className = 'fa-regular fa-star star-option';
            });

            // Fill selected stars
            for(var i = 1; i <= rating; i++) {
                var star = document.querySelector(`.star-option[data-value="${i}"]`);
                if(star) {
                    star.className = 'fa-solid fa-star star-option';
                }
            }

            // Update hidden input
            document.getElementById('selectedRating').value = rating;
        }

        // Function to submit review
        function submitReview() {
            var rating = document.getElementById('selectedRating').value;
            var content = document.getElementById('reviewContent').value.trim();
            var productId = '<?php echo $data['san_pham']['ma_san_pham']; ?>';

            if(rating == 0) {
                alert('Vui lòng chọn số sao đánh giá!');
                return;
            }

            if(content == '') {
                alert('Vui lòng nhập nội dung đánh giá!');
                return;
            }

            // Prepare form data
            var formData = new FormData();
            formData.append('ma_san_pham', productId);
            formData.append('so_sao', rating);
            formData.append('noi_dung', content);

            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo $this->url('Khachhang/themdanhgia'); ?>', true);
            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4) {
                    if(xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if(response.success) {
                            alert('Đánh giá của bạn đã được gửi thành công!');
                            closeReviewModal();
                            // Reload the page to show the new review
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra khi gửi đánh giá: ' + response.message);
                        }
                    } else {
                        alert('Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
                    }
                }
            };
            xhr.send(formData);
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if(e.target.id === 'reviewModal') {
                closeReviewModal();
            }
        });
        
        // Initialize button visibility when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateButtonsVisibility();
        });
    </script>

</body>

</html>