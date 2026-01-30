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
            width: 4%;
        }

        /* STT */
        th:nth-child(2),
        td:nth-child(2) {
            width: 10%;
        }

        /* Mã ĐH */
        th:nth-child(3),
        td:nth-child(3) {
            width: 7%;
        }

        /* Bàn */
        th:nth-child(4),
        td:nth-child(4) {
            width: 10%;
        }

        /* Người dùng */
        th:nth-child(5),
        td:nth-child(5) {
            width: 8%;
        }

        /* Tổng tiền */
        th:nth-child(6),
        td:nth-child(6) {
            width: 8%;
        }

        /* Tiền khuyến mãi */
        th:nth-child(7),
        td:nth-child(7) {
            width: 9%;
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
            width: 8%;
        }

        /* Chi tiết */
        th:nth-child(10),
        td:nth-child(10) {
            width: 8%;
        }

        /* Thao tác */
        th:nth-child(11),
        td:nth-child(11) {
            width: 10%;
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
        td:nth-child(1),
        td:nth-child(5),
        td:nth-child(6),
        td:nth-child(7) {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        th:nth-child(5),
        th:nth-child(6),
        th:nth-child(7) {
            text-align: right;
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
            margin: 5% auto;
            padding: 0;
            border-radius: var(--radius);
            width: 90%;
            max-width: 800px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease;
            max-height: 80vh;
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
        }

        .order-details-table th {
            background: #f8fafc;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #e3e7ef;
        }

        .order-details-table td {
            padding: 10px;
            border-bottom: 1px solid #e3e7ef;
            vertical-align: middle;
        }

        .order-details-table img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
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
                <!-- <a href="http://localhost/QLSP/Donhang/themmoi" class="btn-create"><i class="fa-solid fa-plus"></i>
                    Thêm đơn </a> -->
                <!-- <a href="http://localhost/QLSP/Donhang/import_form" class="btn-ghost"><i
                        class="fa-solid fa-file-excel"></i> Nhập
                    Excel</a> -->
            </div>
        </div>

        <form method="post" action="http://localhost/QLSP/Donhang/Timkiem" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã đơn hàng</label>
                    <input type="text" id="searchId" name="txtMadonhang" placeholder="Nhập mã đơn hàng..."
                        value="<?php echo isset($data['ma_don_hang']) ? htmlspecialchars($data['ma_don_hang']) : ''; ?>" />
                </div>
                <div>
                    <label for="searchName">Tên bàn</label>
                    <input type="text" id="searchName" name="txtTenban" placeholder="Nhập tên bàn..."
                        value="<?php echo isset($data['ten_ban']) ? htmlspecialchars($data['ten_ban']) : ''; ?>" />
                </div>
                <div>
                    <label for="searchName">Tên user</label>
                    <input type="text" id="searchName" name="txtTenuser" placeholder="Nhập tên user..."
                        value="<?php echo isset($data['ten_user']) ? htmlspecialchars($data['ten_user']) : ''; ?>" />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim"><i class="fa-solid fa-search"></i> Tìm
                    kiếm</button>
                <a href="http://localhost/QLSP/Donhang/danhsach" class="btn-ghost">Làm mới</a>
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
            // Giả định $data['dulieu'] là mysqli_result
            // Đặt lại con trỏ về đầu để có thể đếm và dùng lại bên dưới
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
                            <th>Bàn</th>
                            <th>User</th>
                            <th>Tổng tiền</th>
                            <th>Khuyến mại</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái thanh toán</th>
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
                                    <td><span style="font-weight:600;color:var(--accent)"><?php echo $serial++; ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['ma_don_hang']) ?></td>
                                    <td><?php echo isset($row['ten_ban']) ? htmlspecialchars($row['ten_ban']) : htmlspecialchars($row['ma_ban']) ?>
                                    </td>
                                    <td><?php echo isset($row['ten_user']) ? htmlspecialchars($row['ten_user']) : htmlspecialchars($row['ma_user']) ?>
                                    </td>
                                    <td><span class="currency"><?php echo number_format($row['tong_tien'], 0, ',', '.') ?> ₫</span>
                                    </td>
                                    <td><span
                                            class="currency discount">-<?php echo number_format($row['tien_khuyen_mai'] ?? 0, 0, ',', '.') ?>
                                            ₫</span></td>
                                    <td><span
                                            class="currency"><?php echo number_format($row['tong_tien'] - ($row['tien_khuyen_mai'] ?? 0), 0, ',', '.') ?>
                                            ₫</span></td>
                                    <td>
                                        <span
                                            style="background:<?php echo $row['trang_thai_thanh_toan'] == 'chua_thanh_toan' ? '#fed7aa' : '#d1fae5'; ?>;color:<?php echo $row['trang_thai_thanh_toan'] == 'chua_thanh_toan' ? '#c2410c' : '#065f46'; ?>;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">
                                            <?php echo $row['trang_thai_thanh_toan'] == 'chua_thanh_toan' ? 'Not' : 'Done'; ?>
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
                                        <a href="http://localhost/QLSP/Donhang/xoa/<?php echo urlencode($row['ma_don_hang']) ?>"
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
                // Manual search only (no AJAX)
                const idInput = document.getElementById('searchId');
                const nameInput = document.getElementById('searchName');
                const resultCount = document.getElementById('resultCount');

                // khởi tạo đếm
                resultCount.textContent = '<?php echo $count; ?> bản ghi';

                // Chi tiết đơn hàng admin -> xuất hoá đon cho bartender
                function showOrderDetails(orderId) {
                    // Hiển thị thông báo đang tải ban đầu
                    document.getElementById('detailModal').style.display = 'block';

                    const modalBody = document.getElementById('modalBody');
                    modalBody.innerHTML =
                        '<div style="text-align: center; padding: 20px;"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải chi tiết...</div>';

                    // Cập nhật tiêu đề modal với ID đơn hàng
                    document.getElementById('modalTitle').innerHTML = `Chi tiết đơn hàng: <strong>${orderId}</strong>`;

                    // Lấy chi tiết đơn hàng qua AJAX
                    fetch(`http://localhost/QLSP/Donhang/get_order_details/${orderId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.order_details && data.order_details.length > 0) {
                                let orderDetailHtml = `
                            <div class="order-summary">
                                <div class="order-summary-grid">
                                    <div class="order-summary-item">
                                        <span class="order-summary-label">Mã đơn hàng</span>
                                        <span class="order-summary-value">${orderId}</span>
                                    </div>
                                    <div class="order-summary-item">
                                        <span class="order-summary-label">Tổng món</span>
                                        <span class="order-summary-value">${data.order_details.length}</span>
                                    </div>
                                    <div class="order-summary-item">
                                        <span class="order-summary-label">Tổng tiền</span>
                                        <span class="order-summary-value">`;

                                // Tính tổng số tiền
                                let total = 0;
                                data.order_details.forEach(item => {
                                    total += parseFloat(item.so_luong) * parseFloat(item.gia_tai_thoi_diem_dat);
                                });

                                orderDetailHtml += total.toLocaleString('vi-VN') + ' ₫</span></div></div></div>';

                                orderDetailHtml += `
                            <h3>Chi tiết món ăn</h3>
                            <table class="order-details-table">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên món</th>
                                        <th>Số lượng</th>
                                        <th>Giá tại thời điểm đặt</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                                // Hiển thị ghi chú đơn hàng trong tất cả các hàng
                                const orderNotes = data.order_notes ? '<span title="' + data.order_notes + '">' + (data
                                        .order_notes.length > 20 ? data.order_notes.substring(0, 20) + '...' : data
                                        .order_notes) + '</span>' :
                                    '<span style="color: #9ca3af; font-style: italic;">Không có</span>';

                                data.order_details.forEach(item => {
                                    orderDetailHtml += `
                                <tr>
                                    <td>
                                        ${item.img_thuc_don ? `<img src="/qlsp/Public/Pictures/thucdon/${item.img_thuc_don}" alt="${item.ten_mon}">` : '<span>Không có</span>'}
                                    </td>
                                    <td>${item.ten_mon}</td>
                                    <td>${item.so_luong}</td>
                                    <td>${parseFloat(item.gia_tai_thoi_diem_dat).toLocaleString('vi-VN')} ₫</td>
                                </tr>`;
                                });

                                // Thêm phần ghi chú đơn hàng tổng thể nếu có
                                if (data.order_notes) {
                                    orderDetailHtml += `
                                <tr>
                                    <td colspan="4" style="background-color: #fff3cd; border-top: 2px solid #dee2e6; padding: 10px;">
                                        <strong>Ghi chú đơn hàng:</strong> ${data.order_notes}
                                    </td>
                                </tr>`;
                                }

                                orderDetailHtml += `
                                </tbody>
                            </table>`;

                                // Thêm nút in cho bartender
                                orderDetailHtml += `
                            <div style="margin-top: 20px; text-align: center;">
                                <a href="http://localhost/QLSP/Staff/generateInvoice_admin/${orderId}" class="btn-print" target="_blank" style="
                                    background: #71c942;
                                    color: #212529;
                                    border: none;
                                    padding: 10px 20px;
                                    border-radius: 6px;
                                    text-decoration: none;
                                    font-size: 14px;
                                    font-weight: bold;
                                    display: inline-block;">
                                    <i class="fa-solid fa-file-invoice"></i> In đơn cho bartender
                                </a>
                            </div>`;

                                modalBody.innerHTML = orderDetailHtml;
                            } else {
                                modalBody.innerHTML =
                                    '<div style="text-align: center; padding: 20px; color: #6b7280;">Không có chi tiết đơn hàng</div>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            modalBody.innerHTML =
                                '<div style="text-align: center; padding: 20px; color: #dc3545;">Lỗi khi tải chi tiết đơn hàng</div>';
                        });
                }

                function closeModal() {
                    document.getElementById('detailModal').style.display = 'none';
                }

                // Đóng modal khi nhấn vào bên ngoài modal
                window.onclick = function(event) {
                    const modal = document.getElementById('detailModal');
                    if (event.target == modal) {
                        closeModal();
                    }
                }

                // Đóng modal bằng phím Escape
                document.onkeydown = function(event) {
                    if (event.key === "Escape") {
                        closeModal();
                    }
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