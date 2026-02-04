<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #78</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #f3f4f6;
            --primary-blue: #0d6efd;
            --danger-red: #dc3545;
            --success-green: #198754;
            --text-gray: #6c757d;
            --border-color: #dee2e6;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #333; /* Nền tối để làm nổi bật modal */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Modal Container */
        .modal-container {
            background-color: #fff;
            width: 100%;
            max-width: 1200px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            overflow: hidden;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Header */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212529;
        }

        .modal-title span {
            color: var(--primary-blue);
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-delete {
            border: 1px solid var(--danger-red);
            color: var(--danger-red);
            background: #fff;
            padding: 6px 16px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-delete:hover {
            background: var(--danger-red);
            color: #fff;
        }

        .btn-close {
            color: #999;
            font-size: 1.5rem;
            cursor: pointer;
            border: none;
            background: none;
        }

        /* Modal Body Grid */
        .modal-body {
            padding: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr; /* 3 Cột đều nhau */
            gap: 24px;
            background-color: #f8f9fa;
        }

        /* Card Styles */
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            height: fit-content;
        }

        .card:last-child {
            margin-bottom: 0;
        }

        /* Column 1: Customer & Status */
        .customer-info {
            text-align: center;
        }

        .avatar {
            width: 64px;
            height: 64px;
            background-color: #e9ecef;
            color: var(--primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 12px;
        }

        .customer-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .customer-role {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .contact-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            border-top: 1px solid #f0f0f0;
            text-align: left;
            font-size: 0.95rem;
        }

        .contact-icon {
            color: var(--success-green);
            width: 20px;
            text-align: center;
        }

        .contact-label {
            font-size: 0.75rem;
            color: var(--text-gray);
            display: block;
            margin-bottom: 2px;
        }

        .contact-value {
            font-weight: 600;
            color: #333;
        }

        .status-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-gray);
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .current-status {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .badge-success {
            margin-left: 158px;
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-select-wrapper {
            position: relative;
        }

        .status-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--primary-blue);
            border-radius: 6px;
            background-color: #fff;
            color: var(--success-green);
            font-weight: 600;
            appearance: none;
            cursor: pointer;
        }

        .status-lock {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 40px;
            background: var(--primary-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            pointer-events: none;
        }

        /* Column 2: Address & Payment */
        .section-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #333;
        }

        .text-red { color: var(--danger-red); }
        
        .address-box {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.5;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
            margin-bottom: 12px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: #555;
        }

        .payment-row.total {
            border-top: 1px dashed var(--border-color);
            padding-top: 15px;
            margin-top: 10px;
            align-items: center;
        }

        .total-label {
            font-weight: 700;
            color: #333;
        }

        .total-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--danger-red);
        }

        .discount-val { color: var(--danger-red); }
        .voucher-code { color: var(--primary-blue); font-weight: 600; cursor: pointer; }

        /* Column 3: Products */
        .product-item {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f0f0f0;
            position: relative;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-meta {
            font-size: 0.8rem;
            color: var(--text-gray);
            margin-bottom: 4px;
            background: #f8f9fa;
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .product-price {
            font-weight: 600;
            color: var(--danger-red);
            font-size: 0.95rem;
        }

        .qty-badge {
            position: absolute;
            top: -5px;
            left: 50px;
            background: #6c757d;
            color: white;
            font-size: 0.6rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .btn-detail {
            align-self: center;
            padding: 4px 12px;
            border: 1px solid var(--primary-blue);
            color: var(--primary-blue);
            background: #fff;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            white-space: nowrap;
        }
        
        .btn-detail:hover {
            background-color: #e7f1ff;
        }

        .note-input {
            width: 100%;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 10px;
            font-family: inherit;
            color: var(--text-gray);
            resize: none;
            background-color: #fcfcfc;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .modal-body {
                grid-template-columns: 1fr;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="modal-container">
        <header class="modal-header">
            <div class="modal-title">Đơn hàng <span>#78</span></div>
            <div class="header-actions">
                <button class="btn-delete">
                    <i class="fa-solid fa-trash-can"></i> Xóa
                </button>
                <button class="btn-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </header>

        <div class="modal-body">
            
            <div class="column-left">
                <div class="card customer-info">
                    <div class="avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="customer-name">Hoàng Văn Thành</div>
                    <div class="customer-role">Khách hàng</div>

                    <div class="contact-row">
                        <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                        <div>
                            <span class="contact-label">SỐ ĐIỆN THOẠI</span>
                            <span class="contact-value">098765432112</span>
                        </div>
                    </div>
                    <div class="contact-row" style="border-bottom: none;">
                        <div class="contact-icon" style="color: #dc3545;"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <span class="contact-label">EMAIL</span>
                            <span class="contact-value">thanh@gmail.com</span>
                        </div>
                    </div>
                </div>

                <div class="card status-section">
                    <div class="status-title">CẬP NHẬT TRẠNG THÁI</div>
                    <div class="current-status">
                        Hiện tại: <span class="badge-success"><i class="fa-solid fa-check-double"></i> Đã xác nhận</span>
                    </div>
                    <div class="status-select-wrapper">
                        <select class="status-select">
                            <option value="confirmed" selected>Đã xác nhận</option>
                            <option value="shipping">Đang giao hàng</option>
                            <option value="done">Hoàn thành</option>
                        </select>
                        <div class="status-lock">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column-mid">
                <div class="card">
                    <div class="section-header text-red">
                        <i class="fa-solid fa-location-dot"></i> Địa chỉ nhận hàng
                    </div>
                    <div class="address-box">
                        số 100, Hà Phong, Hạ Long, Quảng Ninh
                    </div>
                </div>

                <div class="card">
                    <div class="section-header" style="color: var(--success-green);">
                        <i class="fa-solid fa-money-bill-wave"></i> Chi tiết thanh toán
                    </div>
                    
                    <div class="payment-row">
                        <span>Tạm tính:</span>
                        <b>58.980.000đ</b>
                    </div>
                    <div class="payment-row">
                    </div>
                    <div class="payment-row">
                        <span class="text-red">Giảm giá:</span>
                        <b class="discount-val">-1.000.000đ</b>
                    </div>
                    <div class="payment-row">
                        <span style="color: var(--primary-blue);"><i class="fa-solid fa-ticket"></i> Voucher:</span>
                        <b class="voucher-code">MUNGXUAN</b>
                    </div>
                    
                    <div class="payment-row total">
                        <span class="total-label">TỔNG THANH TOÁN:</span>
                        <span class="total-price">57.980.000đ</span>
                    </div>
                </div>
            </div>

            <div class="column-right">
                <div class="card">
                    <div class="section-header" style="color: #ffc107;">
                        <i class="fa-solid fa-bag-shopping"></i> Sản phẩm đơn hàng
                    </div>

                    <div class="product-item">
                        <img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/i/p/iphone-17-pro-max_3.jpg" alt="iPhone 15" class="product-img">
                        <div class="qty-badge">x1</div>
                        <div class="product-details">
                            <div class="product-name">iPhone 15 Pro Max - Titan Tự Nhiên</div>
                            <div class="product-meta">Ram: 256GB | Màu: Titan</div>
                            <div class="product-price">Giá: 33.990.000đ</div>
                        </div>
                    </div>

                    <div class="product-item">
                        <img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_24__3_5.png" alt="Samsung S24" class="product-img">
                        <div class="qty-badge">x1</div>
                        <div class="product-details">
                            <div class="product-name">Samsung Galaxy S24 Ultra - Xám Titan</div>
                            <div class="product-meta">Ram: 512GB | Màu: Xám</div>
                            <div class="product-price">Giá: 24.990.000đ</div>
                        </div>
                    </div>

                </div>

                <div class="card">
                    <div class="section-header" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-pen"></i> GHI CHÚ KHÁCH HÀNG:
                    </div>
                    <textarea class="note-input" rows="2" disabled>Không có ghi chú</textarea>
                </div>
            </div>

        </div>
    </div>

</body>
</html>