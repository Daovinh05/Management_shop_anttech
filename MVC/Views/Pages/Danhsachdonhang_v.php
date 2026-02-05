<!DOCTYPE html>
<html lang="vi">

<body>

    <style>
    /* Custom styles for the actions */
    .btn-create {
        background: #10b981;
        /* Màu xanh lá cây */
        padding: 8px 15px;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-edit {
        background: #ffc107;
        padding: 6px 10px;
        border-radius: 6px;
        margin-right: 5px;
        color: #fff;
        text-decoration: none;
        display: inline-block;
    }

    .btn-delete {
        background: #dc3545;
        padding: 6px 10px;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        display: inline-block;
    }

    /* Các style cơ bản khác giữ nguyên */
    :root {
        --bg: #f5f7fb;
        --card: #ffffff;
        --accent: #2463ff;
        --muted: #6b7280;
        --radius: 12px;
        --gap: 16px;
        font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
    }

    * {
        box-sizing: border-box
    }

    .card {
        width: 100%;
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: 0 8px 30px rgba(24, 99, 255, 0.08);
        padding: 28px;
        margin-bottom: 20px;
    }

    h1 {
        margin: 0 0 6px;
        font-size: 20px
    }

    p.lead {
        margin: 0 0 20px;
        color: var(--muted);
        font-size: 14px
    }

    .form-search {
        display: flex;
        gap: var(--gap);
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .search-fields {
        display: flex;
        gap: var(--gap);
        flex: 1;
    }

    .search-fields>div {
        flex: 1 1 200px;
    }

    .form-search>.actions {
        flex: 0 0 auto;
        display: flex;
        gap: 12px;
    }

    label {
        display: block;
        font-size: 15px;
        color: #253243;
        margin-bottom: 6px;
        font-weight: bold;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e3e7ef;
        border-radius: 10px;
        background: #fbfdff;
        font-size: 14px;
        outline: none;
    }

    input:focus {
        box-shadow: 0 0 0 4px rgba(36, 99, 255, 0.08);
        border-color: var(--accent);
    }

    .actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .actions-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    button {
        padding: 10px 16px;
        border-radius: 10px;
        border: 0;
        font-size: 14px;
        cursor: pointer
    }

    .btn-primary {
        background: var(--accent);
        color: #fff;
        transition: 0.2s;
    }

    .btn-ghost {
        background: transparent;
        border: 1px solid #e6e9f2;
        color: var(--muted);
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-block;
        line-height: 1;
    }

    .btn-excel {
        background: #e34ae5ff;
        padding: 10px 16px;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-excel:hover {
        background: #e50f9aff;
    }

    .table-container {
        max-height: 500px;
        overflow-x: auto;
        overflow-y: auto;
        margin-top: 20px;
        border: 1px solid #e3e7ef;
        border-radius: var(--radius);
        position: relative;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .order-details-table-container {
        max-height: 400px;
        overflow-y: auto;
        margin-top: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        table-layout: fixed;
    }

    thead th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 10;
        border-bottom: 2px solid #e3e7ef;
        font-weight: 600;
        padding: 9px;
        text-align: left;
        vertical-align: middle;
    }

    /* Specific column widths */
    th:nth-child(1),
    td:nth-child(1) {
        width: 5%;
    }

    /* STT */
    th:nth-child(2),
    td:nth-child(2) {
        width: 5%;
    }

    /* Mã ĐH */
    th:nth-child(3),
    td:nth-child(3) {
        width: 7%;
    }

    /* Bàn */
    th:nth-child(4),
    td:nth-child(4) {
        width: 7%;
    }

    /* Người dùng */
    th:nth-child(5),
    td:nth-child(5) {
        width: 7%;
    }

    /* Tổng tiền */
    th:nth-child(6),
    td:nth-child(6) {
        width: 7%;
    }

    /* Tiền khuyến mãi */
    th:nth-child(7),
    td:nth-child(7) {
        width: 8%;
    }

    /* Số tiền cần thanh toán */
    th:nth-child(8),
    td:nth-child(8) {
        width: 10%;
    }

    /* Trạng thái */
    th:nth-child(9),
    td:nth-child(9) {
        width: 9%;
    }

    /* Ngày tạo */
    th:nth-child(10),
    td:nth-child(10) {
        width: 9%;
    }

    /* Chi tiết */
    th:nth-child(10),
    td:nth-child(10) {
        width: 5%;
    }

    /* Thao tác */
    th:nth-child(11),
    td:nth-child(11) {
        width: 7%;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e3e7ef;
        vertical-align: top;
        word-wrap: break-word;
    }

    /* Right-align specific columns for better readability */

    td:nth-child(5),
    td:nth-child(6) {
        text-align: left;
        font-family: 'Courier New', monospace;
    }

    th:nth-child(5),
    th:nth-child(6) {
        text-align: left;
    }

    td:nth-child(1),
    td:nth-child(7),
    td:nth-child(8),
    td:nth-child(9),
    td:nth-child(10) {
        text-align: center;
    }

    th:nth-child(1),
    th:nth-child(7),
    th:nth-child(8),
    th:nth-child(9),
    th:nth-child(10) {
        text-align: center;
    }

    /* Style for currency values */
    .currency {
        font-weight: 600;
        color: #059669;
    }

    .currency.discount {
        color: #dc2626;
    }

    tbody tr:hover {
        background-color: #f8fafc;
    }

    .hint {
        font-size: 12px;
        color: var(--muted);
        margin-top: 6px
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        background-color: var(--card);
        margin: 2% auto;
        padding: 0;
        border-radius: var(--radius);
        width: 95%;
        max-width: 1400px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e3e7ef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212529;
    }

    .modal-title span {
        color: var(--accent);
    }

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-delete {
        border: 1px solid #dc3545;
        color: #dc3545;
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
        background: #dc3545;
        color: #fff;
    }

    .btn-close {
        color: #999;
        font-size: 1.5rem;
        cursor: pointer;
        border: none;
        background: none;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .modal-close:hover {
        background-color: #f3f4f6;
    }

    .modal-body {
        padding: 20px 24px;
    }

    .order-summary {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e3e7ef;
    }

    .order-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .order-summary-item {
        display: flex;
        flex-direction: column;
    }

    .order-summary-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .order-summary-value {
        font-weight: 600;
        color: #253243;
    }

    .order-details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .order-details-table th {
        background: linear-gradient(to bottom, #f1f5f9, #e2e8f0);
        padding: 12px 10px;
        text-align: left;
        border-bottom: 2px solid #cbd5e1;
        font-weight: 600;
        color: #334155;
        white-space: nowrap;
    }

    .order-details-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }

    .order-details-table th:nth-child(2),
    .order-details-table td:nth-child(2) {
        /* Cột tên sản phẩm */
        min-width: 250px;
        white-space: normal;
        word-break: break-word;
    }

    .order-details-table tr:last-child td {
        border-bottom: none;
    }

    .order-details-table tr:hover {
        background-color: #f8fafc;
    }

    .order-details-table img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .detail-btn {
        background: #4f46e5;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        transition: background-color 0.2s;
    }

    .detail-btn:hover {
        background: #4338ca;
    }

    /* Styles for order details modal similar to order_modal.php */
    .modal-body {
        padding: 32px;
        display: grid;
        
        /* --- SỬA DÒNG NÀY --- */
        /* Cũ: grid-template-columns: 1fr 1fr 1fr; */
        /* Mới: Cột trái 1 phần, Giữa 1 phần, Phải 1.3 phần */
        grid-template-columns: 1fr 1fr 1.3fr; 
        /* ------------------- */
        
        gap: 32px;
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
        color: var(--accent);
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
        color: var(--muted);
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
        color: #198754;
        width: 20px;
        text-align: center;
    }

    .contact-label {
        font-size: 0.75rem;
        color: var(--muted);
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
        color: var(--muted);
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    .current-status {
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .badge-success {
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
        border: 1px solid var(--accent);
        border-radius: 6px;
        background-color: #fff;
        color: #198754;
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
        background: var(--accent);
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

    .text-red { color: #dc3545; }

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
        border-top: 1px dashed #e3e7ef;
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
        color: #dc3545;
    }

    .discount-val { color: #dc3545; }
    .voucher-code { color: var(--accent); font-weight: 600; cursor: pointer; }

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
        color: var(--muted);
        margin-bottom: 4px;
        background: #f8f9fa;
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #eee;
    }

    .product-price {
        font-weight: 600;
        color: #dc3545;
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

    .note-input {
        width: 100%;
        border: 1px solid #e3e7ef;
        border-radius: 6px;
        padding: 10px;
        font-family: inherit;
        color: var(--muted);
        resize: none;
        background-color: #fcfcfc;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .modal-body {
            grid-template-columns: 1fr;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>

    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-receipt"></i> Quản lý Đơn Hàng</h1>
                <p class="lead">Theo dõi và quản lý các đơn hàng trong quán.</p>
            </div>
            <div class="actions">

            </div>
        </div>

        <form method="post" action="http://localhost/Banhang/Donhang/Timkiem" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã đơn hàng</label>
                    <input type="text" id="searchId" name="txtMadonhang" placeholder="Nhập mã đơn hàng..."
                        value="<?php echo isset($data['ma_don_hang']) ? htmlspecialchars($data['ma_don_hang']) : ''; ?>" />
                </div>
                <div>
                    <label for="searchName">Tên khách hàng</label>
                    <input type="text" id="searchName" name="txtTenkhachhang" placeholder="Nhập tên khách hàng..."
                        value="<?php echo isset($data['full_name']) ? htmlspecialchars($data['full_name']) : ''; ?>" />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim"><i class="fa-solid fa-search"></i> Tìm
                    kiếm</button>
                <a href="http://localhost/Banhang/Donhang/danhsach" class="btn-ghost">Làm mới</a>
                <button type="submit" name="btnXuatexcel" class="btn-excel">
                    <i class="fa-solid fa-solid fa-download"></i> Xuất Excel
                </button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2><i class="fa-solid fa-list-ul"></i> Danh sách hiện tại</h2>
        <?php
        // Đặt lại con trỏ dữ liệu
        if (isset($data['dulieu']) && is_a($data['dulieu'], 'mysqli_result')) {
            mysqli_data_seek($data['dulieu'], 0);
        }

        // Đảm bảo dữ liệu tồn tại
        if (isset($data['dulieu'])) {
            if (is_object($data['dulieu'])) {
                $count = mysqli_num_rows($data['dulieu']);
                mysqli_data_seek($data['dulieu'], 0);
            } else {
                $count = 0;
            }
        ?>
        <div style="margin:10px 0">
            <strong>Kết quả: <span id="resultCount" class="hint"></span></strong>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Khuyến mãi</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Chi tiết</th>
                        <th style="text-align:right">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="dhBody">
                    <?php
                        // Render dữ liệu tĩnh ban đầu
                        if ($count > 0) {
                            $serial = 1; // Khởi tạo bộ đếm số thứ tự
                            while ($row = mysqli_fetch_array($data['dulieu'])) {
                        ?>
                    <tr>
                        <td><span style="font-weight:600;color:var(--accent)"><?php echo $serial++; ?></span></td>
                        <td><?php echo htmlspecialchars($row['ma_don_hang']) ?></td>
                        <td>
                            <div style="font-weight: 500;">
                                <?php echo htmlspecialchars($row['full_name']) ?>
                            </div>

                            <div style="font-size: 13px; color: #666; margin-top: 2px;">
                                <i class="fa-solid fa-phone" style="font-size: 11px;"></i>
                                <?php echo htmlspecialchars($row['so_dien_thoai'] ?? 'Không có SĐT') ?>
                            </div>
                        </td>
                        <td><span class="currency"><?php echo number_format($row['tong_tien_hang'], 0, ',', '.') ?>
                                ₫</span>
                        </td>
                        <td><span
                                class="currency discount">-<?php echo number_format($row['tien_khuyen_mai'] ?? 0, 0, ',', '.') ?>
                                ₫</span></td>

                        <td><span
                                class="currency"><?php echo number_format($row['tong_tien_hang'] - ($row['tien_khuyen_mai'] ?? 0), 0, ',', '.') ?>
                                ₫</span></td>
                        <td>
                            <?php
                            $status = $row['trang_thai_don_hang'];
                            switch($status) {
                                case 'cho_duyet':
                                    $bg = '#fef3c7';
                                    $color = '#92400e';
                                    $label = 'Chờ duyệt';
                                    break;
                                case 'dang_giao':
                                    $bg = '#dbeafe';
                                    $color = '#1e40af';
                                    $label = 'Đang giao';
                                    break;
                                case 'hoan_thanh':
                                    $bg = '#d1fae5';
                                    $color = '#065f46';
                                    $label = 'Hoàn thành';
                                    break;
                                case 'da_huy':
                                    $bg = '#fee2e2';
                                    $color = '#991b1b';
                                    $label = 'Đã hủy';
                                    break;
                                default:
                                    $bg = '#f3f4f6';
                                    $color = '#374151';
                                    $label = 'Không rõ';
                                    break;
                            }
                            ?>
                            <span style="background:<?= $bg ?>; color:<?= $color ?>; padding:4px 8px; border-radius:6px; font-size:12px; font-weight:600">
                                <?= $label ?>
                            </span>
                        </td>
                        <td><?php echo isset($row['ngay_tao']) ? htmlspecialchars(TimezoneHelper::formatForDisplay($row['ngay_tao'], 'H:i:s d/m/Y')) : '' ?>
                        </td>

                        <td>
                            <button class="detail-btn" onclick="showOrderDetails('<?php echo $row['ma_don_hang']; ?>')">
                                <i class="fa-solid fa-eye"></i> Xem
                            </button>
                        </td>
                        <td style="text-align:right">
                            <a href="http://localhost/Banhang/Donhang/xoa/<?php echo urlencode($row['ma_don_hang']) ?>"
                                onclick="return confirm('Bạn có chắc chắn muốn xoá không?')"><button
                                    class="btn-delete">🗑️
                                    Xóa</button></a>
                        </td>
                    </tr>
                    <?php }
                        } ?>
                </tbody>
            </table>
        </div>
        <script>
            // --- CẤU HÌNH ---
            // Kiểm tra đúng tên thư mục trên localhost của bạn (Banhang hay QLSP?)
            const BASE_URL = 'http://localhost/Banhang'; 

            // Hiển thị số lượng bản ghi
            const resultCount = document.getElementById('resultCount');
            if(resultCount) {
                resultCount.textContent = '<?php echo $count; ?> bản ghi';
            }

            // --- HÀM XEM CHI TIẾT ---
            function showOrderDetails(orderId) {
                const modal = document.getElementById('detailModal');
                const modalBody = document.getElementById('modalBody');
                const modalTitle = document.getElementById('modalTitle');

                // Reset và hiển thị loading
                modal.style.display = 'block';
                modalTitle.innerHTML = `Đơn hàng <span>#${orderId}</span>`;
                modalBody.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><br><br>Đang tải dữ liệu...</div>';

                // Gọi API
                fetch(`${BASE_URL}/Donhang/get_order_details/${orderId}`)
                    .then(response => {
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            return response.json();
                        } else {
                            throw new Error("Server trả về lỗi (HTML) thay vì dữ liệu JSON.");
                        }
                    })
                    .then(data => {
                        if (data.order_details && data.order_details.length > 0) {
                            let html = '';

                            // --- SỬA LỖI TẠI ĐÂY: KHÔNG BAO QUANH BỞI <div class="modal-body"> NỮA ---
                            // Chúng ta bắt đầu trực tiếp bằng các cột
                            
                            // CỘT 1: TRÁI
                            html += `
                                <div class="column-left">
                                    <div class="card customer-info">
                                        <div class="avatar">
                                            ${data.user_info?.avatar ? 
                                            `<img src="/Banhang/Public/Pictures/users/${data.user_info.avatar}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">` : 
                                            `<i class="fa-solid fa-user"></i>`
                                            }
                                        </div>
                                        <div class="customer-name">${data.user_info?.full_name || 'Khách hàng'}</div>
                                        <div class="customer-role">Khách hàng</div>
                                        <div class="contact-row">
                                            <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                                            <div><span class="contact-label">SỐ ĐIỆN THOẠI</span><span class="contact-value">${data.user_info?.so_dien_thoai || 'N/A'}</span></div>
                                        </div>
                                        <div class="contact-row" style="border-bottom: none;">
                                            <div class="contact-icon" style="color: #dc3545;"><i class="fa-solid fa-envelope"></i></div>
                                            <div><span class="contact-label">EMAIL</span><span class="contact-value">${data.user_info?.email || 'N/A'}</span></div>
                                        </div>
                                    </div>

                                    <div class="card status-section">
                                        <div class="status-title">CẬP NHẬT TRẠNG THÁI</div>
                                        <div class="current-status">Hiện tại: ${getStatusBadge(data.order_info?.trang_thai_don_hang)}</div>
                                        <div class="status-select-wrapper">
                                            <select class="status-select" onchange="updateOrderStatus('${orderId}', this.value)" style="font-weight:600">
                                                <option value="cho_duyet" style="color:#92400e" ${data.order_info?.trang_thai_don_hang === 'cho_duyet' ? 'selected' : ''}>Chờ duyệt</option>
                                                <option value="dang_giao" style="color:#1e40af" ${data.order_info?.trang_thai_don_hang === 'dang_giao' ? 'selected' : ''}>Đang giao hàng</option>
                                                <option value="hoan_thanh" style="color:#065f46" ${data.order_info?.trang_thai_don_hang === 'hoan_thanh' ? 'selected' : ''}>Hoàn thành</option>
                                                <option value="da_huy" style="color:#991b1b" ${data.order_info?.trang_thai_don_hang === 'da_huy' ? 'selected' : ''}>Đã hủy</option>
                                            </select>
                                            <div class="status-lock"><i class="fa-solid fa-lock"></i></div>
                                        </div>
                                    </div>
                                </div>`;

                            // CỘT 2: GIỮA
                            html += `
                                <div class="column-mid">
                                    <div class="card">
                                        <div class="section-header text-red"><i class="fa-solid fa-location-dot"></i> Địa chỉ nhận hàng</div>
                                        <div class="address-box">${data.address_info?.dia_chi || 'N/A'}</div>
                                    </div>
                                    <div class="card">
                                        <div class="section-header" style="color: var(--success-green);"><i class="fa-solid fa-money-bill-wave"></i> Chi tiết thanh toán</div>
                                        <div class="payment-row"><span>Tạm tính:</span><b>${calculateSubtotal(data.order_details).toLocaleString('vi-VN')}đ</b></div>
                                        <div class="payment-row">
                                            <span class="text-red">Giảm giá:</span>
                                            <b class="discount-val">-${parseInt(data.promotion_info?.tien_khuyen_mai || 0).toLocaleString('vi-VN')}đ</b>
                                        </div>
                                        <div class="payment-row total">
                                            <span class="total-label">TỔNG THANH TOÁN:</span>
                                            <span class="total-price">${parseInt(data.order_info?.thanh_toan || 0).toLocaleString('vi-VN')}đ</span>
                                        </div>
                                    </div>
                                </div>`;

                            // CỘT 3: PHẢI
                            html += `
                                <div class="column-right">
                                    <div class="card">
                                        <div class="section-header" style="color: #ffc107;"><i class="fa-solid fa-bag-shopping"></i> Sản phẩm đơn hàng</div>`;
                            
                            data.order_details.forEach(item => {
                                let gia = parseFloat(item.gia_tai_thoi_diem_dat || item.gia_luc_mua || 0);
                                
                                
                                let imgName = item.img_hinh_anh; 
                                
                                // [SỬA LỖI 2]: Kiểm tra đường dẫn. Thường ảnh biến thể nằm trong folder 'bien_the'
                                // Nếu web của bạn để ảnh trong 'products' thì sửa 'bien_the' thành 'products'
                                let imgSrc = imgName 
                                    ? `/Banhang/Public/Pictures/bien_the/${imgName}` 
                                    : 'https://placehold.co/60x60?text=No+Img';

                                html += `
                                    <div class="product-item">
                                        <img src="${imgSrc}" 
                                            alt="${item.ten_san_pham}" 
                                            class="product-img" 
                                            onerror="this.src='https://placehold.co/60x60?text=Err'"> <div class="qty-badge">x${item.so_luong}</div>
                                        
                                        <div class="product-details">
                                            <div class="product-name">${item.ten_san_pham || 'Sản phẩm'}</div>
                                            <div class="product-meta">
                                                ${item.mau_sac ? `Màu: ${item.mau_sac}` : ''} 
                                                ${item.ram ? `| Ram: ${item.ram}` : ''}
                                                ${item.dung_luong ? `| ${item.dung_luong}` : ''}
                                            </div>
                                            <div class="product-price">Giá: ${gia.toLocaleString('vi-VN')}đ</div>
                                        </div>
                                    </div>`;
                            });

                            html += `   </div>
                                    <div class="card">
                                        <div class="section-header" style="font-size: 0.9rem;"><i class="fa-solid fa-pen"></i> GHI CHÚ KHÁCH HÀNG:</div>
                                        <textarea class="note-input" rows="2" disabled>${data.order_notes || 'Không có ghi chú'}</textarea>
                                    </div>
                                </div>`;

                            modalBody.innerHTML = html;
                        } else {
                            modalBody.innerHTML = '<div style="text-align:center; padding:30px; color:#666">Không tìm thấy sản phẩm nào.</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        modalBody.innerHTML = `<div style="text-align: center; padding: 20px; color: #dc3545;">Lỗi tải dữ liệu!<br><small>${error.message}</small></div>`;
                    });
            }

            // Hàm hỗ trợ để chuyển đổi trạng thái đơn hàng sang văn bản
            function getStatusText(status) {
                switch(status) {
                    case 'cho_duyet': return 'Chờ duyệt';
                    case 'dang_giao': return 'Đang giao hàng';
                    case 'hoan_thanh': return 'Hoàn thành';
                    case 'da_huy': return 'Đã hủy';
                    default: return 'Không xác định';
                }
            }

            function getStatusBadge(status) {
            let bg = '#eee', color = '#333', text = 'Không xác định';
            
            switch(status) {
                case 'cho_duyet':
                    bg = '#fef3c7'; color = '#92400e'; text = 'Chờ duyệt';
                    break;
                case 'dang_giao':
                    bg = '#dbeafe'; color = '#1e40af'; text = 'Đang giao';
                    break;
                case 'hoan_thanh':
                    bg = '#d1fae5'; color = '#065f46'; text = 'Hoàn thành';
                    break;
                case 'da_huy':
                    bg = '#fee2e2'; color = '#991b1b'; text = 'Đã hủy';
                    break;
            }
            
            // Trả về chuỗi HTML có style inline
            return `<span style="background:${bg}; color:${color}; padding:4px 8px; border-radius:6px; font-size:12px; font-weight:600">${text}</span>`;
        }

            // Hàm tính tổng phụ
            function calculateSubtotal(items) {
                let total = 0;
                items.forEach(item => {
                    let gia = parseFloat(item.gia_tai_thoi_diem_dat || item.gia_luc_mua || 0);
                    total += parseFloat(item.so_luong) * gia;
                });
                return total;
            }

            // Hàm cập nhật trạng thái đơn hàng
            function updateOrderStatus(orderId, newStatus) {
                if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?')) {
                    fetch(`${BASE_URL}/Donhang/update_status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            orderId: orderId,
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cập nhật trạng thái thành công!');
                            // Reload lại modal để hiển thị trạng thái mới
                            showOrderDetails(orderId);
                        } else {
                            alert('Cập nhật thất bại: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        alert('Có lỗi xảy ra khi cập nhật trạng thái');
                    });
                }
            }

            function closeModal() {
                // Tải lại trang để cập nhật dữ liệu mới nhất (trạng thái,...) ở danh sách
                window.location.reload();
            }
            window.onclick = function(event) {
                if (event.target == document.getElementById('detailModal')) closeModal();
            }
            document.onkeydown = function(event) {
                if (event.key === "Escape") closeModal();
            }
        </script>
        <?php } ?>
        <?php if (isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) === 0) { ?>
        <div class="hint">Không có kết quả phù hợp.</div>
        <?php } ?>
    </div>

    <!-- Modal for order details -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle">Đơn hàng <span>#78</span></div>
                <div class="header-actions">
                    <button class="btn-delete" onclick="deleteOrder()">
                        <i class="fa-solid fa-trash-can"></i> Xóa
                    </button>
                    <button class="modal-close" onclick="closeModal()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        // Hàm xóa đơn hàng
        function deleteOrder() {
            const orderId = document.getElementById('modalTitle').textContent.match(/#(\w+)/);
            if (orderId && orderId[1]) {
                if (confirm('Bạn có chắc chắn muốn xóa đơn hàng #' + orderId[1] + '?')) {
                    window.location.href = `${BASE_URL}/Donhang/xoa/${orderId[1]}`;
                }
            }
        }
    </script>
</body>

</html>