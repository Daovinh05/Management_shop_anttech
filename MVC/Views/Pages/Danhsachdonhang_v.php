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
        width: 90%;
        max-width: 1000px;
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

    .modal-header h2 {
        margin: 0;
        font-size: 18px;
        color: #253243;
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
                modalTitle.innerHTML = `Chi tiết đơn hàng: <strong>${orderId}</strong>`;
                modalBody.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><br><br>Đang tải dữ liệu...</div>';

                // Gọi API
                fetch(`${BASE_URL}/Donhang/get_order_details/${orderId}`)
                    .then(response => {
                        // Kiểm tra xem server trả về JSON hay HTML lỗi
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            return response.json();
                        } else {
                            console.error("Server response:", response);
                            throw new Error("Server trả về lỗi (HTML) thay vì dữ liệu JSON. Kiểm tra lại Model/Controller.");
                        }
                    })
                    .then(data => {
                        console.log("Dữ liệu nhận được:", data); // F12 xem log này nếu lỗi
                        
                        if (data.order_details && data.order_details.length > 0) {
                            let html = '';
                            
                            // A. TÍNH TỔNG TIỀN
                            let totalMoney = 0;
                            data.order_details.forEach(item => {
                                let gia = parseFloat(item.gia_tai_thoi_diem_dat || item.gia_luc_mua || 0);
                                totalMoney += parseFloat(item.so_luong) * gia;
                            });
                            
                            // B. HEADER TÓM TẮT
                            html += `
                            <div class="order-summary">
                                <div class="order-summary-grid">
                                    <div class="order-summary-item">
                                        <span class="order-summary-label">Mã đơn</span>
                                        <span class="order-summary-value">${orderId}</span>
                                    </div>
                                    <div class="order-summary-item">
                                        <span class="order-summary-label">Số lượng máy</span>
                                        <span class="order-summary-value">${data.order_details.length}</span>
                                    </div>
                                    <div class="order-summary-item">
                                        <span class="order-summary-label">Tổng tiền hàng</span>
                                        <span class="order-summary-value" style="color:#059669">${totalMoney.toLocaleString('vi-VN')} ₫</span>
                                    </div>
                                </div>
                            </div>`;

                            // C. BẢNG CHI TIẾT
                            html += `
                            <h3>Danh sách sản phẩm</h3>
                            <div class="order-details-table-container">
                                <table class="order-details-table">
                                    <thead>
                                        <tr>
                                            <th style="width:60px; text-align: center;">Ảnh</th>
                                            <th style="width:160px; text-align: left;">Tên sản phẩm & Cấu hình</th>
                                            <th style="width:60px; text-align: center;">SL</th>
                                            <th style="width:60px; text-align: center;">Đơn giá</th>
                                            <th style="width:60px; text-align: center;">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                            data.order_details.forEach(item => {
                                let gia = parseFloat(item.gia_tai_thoi_diem_dat || item.gia_luc_mua || 0);
                                let thanhTien = parseFloat(item.so_luong) * gia;

                                // Xử lý ảnh (sửa đường dẫn /qlsp/ thành đúng thư mục của bạn nếu cần)
                                let imgPath = item.hinh_anh || item.img_thuc_don;
                                let imgHtml = imgPath
                                    ? `<img src="/Banhang/Public/Pictures/products/${imgPath}" onerror="this.src='https://placehold.co/40x40?text=No+Img'" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">`
                                    : `<span style="font-size:10px; color:#999">No IMG</span>`;

                                // Hiển thị màu, ram
                                let cauhinh = '';
                                if(item.mau_sac || item.ram || item.dung_luong) {
                                    cauhinh = `<div style="font-size:12px; color:#666; margin-top:4px; display:flex; flex-wrap:wrap; gap:4px;">
                                        ${item.mau_sac ? `<span style="background:#f3f4f6; padding:2px 6px; border-radius:4px; white-space:nowrap;">${item.mau_sac}</span>` : ''}
                                        ${item.ram ? `<span style="background:#f3f4f6; padding:2px 6px; border-radius:4px; white-space:nowrap;">${item.ram}</span>` : ''}
                                        ${item.dung_luong ? `<span style="background:#f3f4f6; padding:2px 6px; border-radius:4px; white-space:nowrap;">${item.dung_luong}</span>` : ''}
                                    </div>`;
                                }

                                html += `
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;">${imgHtml}</td>
                                    <td style="vertical-align: middle;">
                                        <div style="font-weight:600; color:#253243; margin-bottom: 4px;">${item.ten_san_pham || item.ten_mon || 'Sản phẩm không xác định'}</div>
                                        ${cauhinh}
                                    </td>
                                    <td style="text-align: center; vertical-align: middle; font-weight: 600;">${item.so_luong || 0}</td>
                                    <td style="text-align: right; vertical-align: middle; font-family: monospace;">${gia.toLocaleString('vi-VN')} ₫</td>
                                    <td style="text-align: right; vertical-align: middle; font-weight: 600; font-family: monospace;">${thanhTien.toLocaleString('vi-VN')} ₫</td>
                                </tr>`;
                            });

                            // D. GHI CHÚ
                            if (data.order_notes && data.order_notes.trim() !== "") {
                                html += `<tr><td colspan="5" style="background:#fffbeb; color:#92400e; padding:10px;"><i class="fa-regular fa-note-sticky"></i> <strong>Ghi chú:</strong> ${data.order_notes}</td></tr>`;
                            }

                            html += `</tbody></table></div>`;

                            // E. NÚT IN
                            html += `
                            <div style="margin-top: 20px; text-align: right; border-top:1px solid #eee; padding-top:15px">
                                <a href="${BASE_URL}/Donhang/InHoaDon/${orderId}" target="_blank" class="btn-create" style="display:inline-block">
                                    <i class="fa-solid fa-print"></i> In hóa đơn
                                </a>
                            </div>`;

                            modalBody.innerHTML = html;
                        } else {
                            modalBody.innerHTML = '<div style="text-align:center; padding:30px; color:#666">Không tìm thấy sản phẩm nào trong đơn hàng này.</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi chi tiết:', error);
                        modalBody.innerHTML = `
                            <div style="text-align: center; padding: 20px; color: #dc3545;">
                                <i class="fa-solid fa-triangle-exclamation fa-2x"></i><br><br>
                                <b>Lỗi tải dữ liệu!</b><br>
                                <small>${error.message}</small>
                            </div>`;
                    });
            }

            function closeModal() {
                document.getElementById('detailModal').style.display = 'none';
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
                <h2 id="modalTitle">Chi tiết đơn hàng</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</body>

</html>