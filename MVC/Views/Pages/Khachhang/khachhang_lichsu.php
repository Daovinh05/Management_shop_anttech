<?php
// Include necessary helpers
include_once __DIR__ . '/../../../../Public/Classes/TimezoneHelper.php';
include_once __DIR__ . '/../../../../Public/Classes/UrlHelper.php';
?>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 10px;
    }

    /* --- 4. ORDER HISTORY STYLES --- */
    .order-history-wrapper {
        padding: 40px 0;
        min-height: 200px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #333;
    }

    /* Tabs Style */
    .order-tabs {
        display: flex;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 30px;
        overflow-x: auto;
    }

    .tab-item {
        padding: 15px 20px;
        font-size: 15px;
        color: #777;
        cursor: pointer;
        position: relative;
        white-space: nowrap;
        transition: 0.2s;
    }

    .tab-item:hover {
        color: var(--blue-btn);
    }

    .tab-item.active {
        color: var(--blue-btn);
        font-weight: 600;
    }

    .tab-item.active::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--blue-btn);
        border-radius: 3px 3px 0 0;
    }

    /* Order Card Style */
    .order-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        overflow: hidden;
        border: 1px solid transparent;
        transition: 0.2s;
    }

    .order-card:hover {
        border-color: #dbeaff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .order-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .order-id {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    .order-date {
        font-size: 13px;
        color: #888;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-confirmed {
        background-color: #dbeafe;
        color: #1d4ed8;
    }

    .status-completed {
        background-color: #dcfce7;
        color: #166534;
    }

    /* Xanh lá nhạt */
    .status-shipping {
        background-color: #e0f2fe;
        color: #0284c7;
    }

    /* Xanh dương nhạt */
    .status-cancelled {
        background-color: #fee2e2;
        color: #dc2626;
    }

    /* Đỏ nhạt */

    /* --- SỬA CẤU TRÚC BODY: CHIA 2 CỘT NGANG HÀNG --- */
    .order-body-flex {
        display: flex;
        justify-content: space-between;
        /* Đẩy sản phẩm sang trái, tiền sang phải */
        align-items: center;
        /* Căn giữa theo chiều dọc */
        padding: 20px;
    }

    /* Cột bên trái: Danh sách sản phẩm */
    .order-product-list {
        flex: 1;
        /* Chiếm hết khoảng trống còn lại */
        margin-right: 20px;
        /* Cách cột tiền một chút */
    }

    .order-product {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .order-product:last-child {
        margin-bottom: 0;
    }

    .product-thumb {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        object-fit: contain;
        padding: 5px;
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    .product-meta {
        font-size: 13px;
        color: #777;
        margin-bottom: 5px;
    }

    .product-price {
        font-size: 14px;
        font-weight: 700;
        color: var(--blue-btn);
    }

    /* Cột bên phải: Tiền và Nút (Nằm ngang hàng với sản phẩm) */
    .order-actions-right {
        display: flex;
        flex-direction: column;
        /* Xếp dọc nội bộ cột phải */
        align-items: flex-end;
        /* Căn lề phải */
        gap: 10px;
        /* Khoảng cách giữa tiền và nút */
        min-width: 150px;
        /* Đảm bảo không bị co quá nhỏ */
    }

    .total-money {
        text-align: right;
    }

    .total-label {
        font-size: 14px;
        color: #555;
    }

    .total-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--blue-btn);
        display: block;
        /* Xuống dòng cho đẹp */
        margin-top: 2px;
    }

    .btn-detail {
        background-color: var(--blue-btn);
        color: white;
        border: none;
        padding: 8px 25px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: 0.2s;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-detail:hover {
        opacity: 0.9;
        box-shadow: 0 2px 8px rgba(45, 114, 210, 0.3);
    }

    .btn-cancel-order {
        background-color: #fff;
        color: #dc2626;
        border: 1px solid #dc2626;
        padding: 8px 22px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: 0.2s;
        white-space: nowrap;
    }

    .btn-cancel-order:hover {
        background-color: #dc2626;
        color: #fff;
    }

    .btn-cancel-order:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* --- 8. MODAL / POPUP CSS (MỚI THÊM) --- */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        display: none;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(3px);
        animation: fadeIn 0.3s ease;
    }

    .modal-container {
        background: white;
        width: 850px;
        max-width: 95%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: slideUp 0.3s ease;
    }

    .modal-header {
        background-color: var(--primary-green);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-close {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 25px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 25px;
    }

    .detail-box h3 {
        font-size: 16px;
        color: var(--primary-green);
        border-bottom: 2px solid #eee;
        padding-bottom: 8px;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .info-row {
        display: flex;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .info-label {
        width: 120px;
        color: #666;
        font-weight: 500;
    }

    .info-value {
        flex: 1;
        color: #333;
        font-weight: 600;
    }

    /* Table trong Modal */
    .modal-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .modal-table th {
        background: #f5f5f7;
        padding: 12px;
        text-align: left;
        font-size: 13px;
        color: #555;
        font-weight: 700;
        border-bottom: 1px solid #ddd;
    }

    .modal-table td {
        padding: 15px 12px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }

    .modal-table tr:last-child td {
        border-bottom: none;
    }

    .modal-footer-sum {
        background: #f9f9f9;
        padding: 20px;
        text-align: right;
        border-top: 1px solid #eee;
    }

    .sum-row {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .sum-label {
        width: 150px;
        color: #666;
        margin-top: 9px;
    }

    .sum-value {
        width: 150px;
        font-weight: 600;
        color: #333;
    }

    .sum-total {
        font-size: 18px;
        color: var(--tet-red);
        font-weight: 800;
        margin-top: 5px;
    }

    /* Custom button style for "Mua sắm ngay" */
    .btn-shopping {
        background-color: #c93c4d; /* Màu đỏ đặc trưng */
        color: white;
        border: 2px solid #d70018; /* Viền đỏ */
        padding: 12px 30px;
        border-radius: 30px; /* Bo tròn góc */
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: inline-block;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: 15px;
    }

    .btn-shopping:hover {
        background-color: #b30014; /* Màu đỏ đậm hơn khi hover */
        border-color: #b30014;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(215, 0, 24, 0.3);
    }

    .btn-shopping:active {
        transform: translateY(0);
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
<div class="container">
    <div class="order-history-wrapper">
        <?php if (!empty($data['payment_notice']['message'])): ?>
            <div style="margin-bottom:16px;padding:14px 16px;border-radius:6px;background:#fff3cd;color:#664d03;border:1px solid #ffecb5;">
                <?php echo htmlspecialchars($data['payment_notice']['message']); ?>
            </div>
        <?php endif; ?>
        <h1 class="page-title">Đơn hàng của bạn</h1>

        <div class="order-tabs">
            <div class="tab-item active" data-status-key="all" data-count="<?php echo count($data['don_hang']); ?>">Tất cả (<span class="tab-count"><?php echo count($data['don_hang']); ?></span>)</div>
            <div class="tab-item" data-status-key="cho_duyet" data-count="<?php echo $data['status_counts']['cho_duyet']; ?>">Chờ xác nhận (<span class="tab-count"><?php echo $data['status_counts']['cho_duyet']; ?></span>)</div>
            <div class="tab-item" data-status-key="da_duyet" data-count="<?php echo $data['status_counts']['da_duyet']; ?>">Đã xác nhận (<span class="tab-count"><?php echo $data['status_counts']['da_duyet']; ?></span>)</div>
            <div class="tab-item" data-status-key="dang_giao" data-count="<?php echo $data['status_counts']['dang_giao']; ?>">Đang giao (<span class="tab-count"><?php echo $data['status_counts']['dang_giao']; ?></span>)</div>
            <div class="tab-item" data-status-key="hoan_thanh" data-count="<?php echo $data['status_counts']['hoan_thanh']; ?>">Hoàn thành (<span class="tab-count"><?php echo $data['status_counts']['hoan_thanh']; ?></span>)</div>
            <div class="tab-item" data-status-key="da_huy" data-count="<?php echo $data['status_counts']['da_huy']; ?>">Đã hủy (<span class="tab-count"><?php echo $data['status_counts']['da_huy']; ?></span>)</div>
        </div>
        <div id="orderCardsContainer"></div>

                <!-- Empty state message for filtered tabs -->
                <div class="text-center py-5" id="no-orders-message" style="display: none;">
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <h4>Chưa có đơn hàng ở giai đoạn này</h4>
                        <p>Hãy bắt đầu mua sắm để có đơn hàng đầu tiên</p>
                        <a href="<?php echo $this->url('Khachhang'); ?>" class="btn-shopping">Mua sắm ngay</a>
                    </div>
                </div>
    </div>
</div>
<!-- Modal for Order Details  -->
<div class="modal-overlay" id="detailModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title"><i class="fa-solid fa-file-invoice"></i>Chi tiết đơn hàng <span
                    id="modalOrderId">#DH02</span></div><button class="btn-close" onclick="closeModal()"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-box">
                    <h3>Thông tin đơn hàng</h3>
                    <div class="info-row"><span class="info-label">Mã đơn hàng:</span><span class="info-value"
                            id="modalCode">DH02</span></div>
                    <div class="info-row"><span class="info-label">Ngày đặt:</span><span class="info-value"
                            id="modalDate">30/01/2026 21:31:40</span></div>
                    <div class="info-row"><span class="info-label">Trạng thái:</span><span class="info-value"><span
                                id="modalStatus" class="status-badge status-shipping">Đang giao hàng</span></span>
                    </div>
                    <div class="info-row"><span class="info-label">Thanh toán:</span><span class="info-value"
                            id="modalTotalTop" style="color: var(--tet-red);">15.600.000 VNĐ</span></div>
                </div>
                <div class="detail-box">
                    <h3>Thông tin giao hàng</h3>
                    <div class="info-row"><span class="info-label">Người nhận:</span><span class="info-value"
                            id="modalReceiver">Hoàng Văn Thành</span></div>
                    <div class="info-row"><span class="info-label">Số điện thoại:</span><span class="info-value"
                            id="modalPhone">0912345678</span></div>
                    <div class="info-row"><span class="info-label">Địa chỉ:</span><span class="info-value"
                            id="modalAddress">Số 1 Đại Cồ Việt,
                            Hai Bà Trưng,
                            Hà Nội</span></div>
                    <div class="info-row"><span class="info-label">Phương thức:</span><span class="info-value"
                            id="modalPaymentMethod">Thanh toán khi nhận hàng (COD)</span></div>
                </div>
            </div>
            <div class="detail-box">
                <h3>Chi tiết sản phẩm</h3>
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="width: 150px;">Đơn giá</th>
                            <th style="width: 100px; text-align: center;">Số lượng</th>
                            <th style="width: 150px; text-align: right;">Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody id="modalProductList"></tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer-sum">
            <div class="sum-row"><span class="sum-label">Tạm tính:</span><span class="sum-value"
                    id="modalSubTotal">15.600.000 VNĐ</span></div>
            <!-- <div class="sum-row"><span class="sum-label">Khuyến mãi:</span><span class="sum-value" id="modalDiscount">0
                    VNĐ</span></div> -->
            <div class="sum-row"><span class="sum-label">Khuyến mãi:</span><span class="sum-value" id="modalDiscount">0 ₫</span></div>

            <div class="sum-row"><span class="sum-label" style="font-weight: 700;">Số tiền cần thanh toán:</span><span
                    class="sum-value sum-total" id="modalTotalBottom">15.600.000 VNĐ</span></div>
        </div>
    </div>
</div>
<script>
    const CHECKOUT_API_BASE = '<?php echo UrlHelper::url('Api/Checkout'); ?>';
    const CHECKOUT_HISTORY_API = CHECKOUT_API_BASE + '/history';
    const CHECKOUT_STATUS_API = CHECKOUT_API_BASE + '/status';
    const NO_IMAGE_URL = '<?php echo UrlHelper::url('Public/Images/no-image.png'); ?>';

    const state = {
        activeStatus: 'all',
        orders: [],
        counts: {
            all: 0,
            cho_duyet: 0,
            da_duyet: 0,
            dang_giao: 0,
            hoan_thanh: 0,
            da_huy: 0
        }
    };

    function escapeHtml(text) {
        return String(text || '').replace(/[&<>'"]/g, function(ch) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[ch];
        });
    }

    function formatCurrency(value) {
        return Number(value || 0).toLocaleString('vi-VN') + '₫';
    }

    function formatDateTime(dateString) {
        if (!dateString) {
            return '';
        }

        const d = new Date(dateString.replace(' ', 'T'));
        if (isNaN(d.getTime())) {
            return dateString;
        }

        return d.toLocaleString('vi-VN');
    }

    function getStatusLabel(status) {
        switch (status) {
            case 'cho_duyet':
                return 'Chờ xác nhận';
            case 'da_duyet':
                return 'Đã xác nhận';
            case 'dang_giao':
                return 'Đang giao hàng';
            case 'hoan_thanh':
                return 'Đã hoàn thành';
            case 'da_huy':
                return 'Đã hủy';
            default:
                return status || 'Không xác định';
        }
    }

    function getStatusClass(status) {
        switch (status) {
            case 'cho_duyet':
                return 'status-pending';
            case 'da_duyet':
                return 'status-confirmed';
            case 'dang_giao':
                return 'status-shipping';
            case 'hoan_thanh':
                return 'status-completed';
            case 'da_huy':
            default:
                return 'status-cancelled';
        }
    }

    function updateTabCounts(counts) {
        document.querySelectorAll('.tab-item').forEach(function(tab) {
            const key = tab.getAttribute('data-status-key') || 'all';
            const value = Number((counts || {})[key] || 0);
            tab.setAttribute('data-count', String(value));
            const countEl = tab.querySelector('.tab-count');
            if (countEl) {
                countEl.textContent = String(value);
            }
        });
    }

    function renderOrderCards() {
        const container = document.getElementById('orderCardsContainer');
        const noOrdersMessage = document.getElementById('no-orders-message');
        if (!container) {
            return;
        }

        if (!Array.isArray(state.orders) || state.orders.length === 0) {
            container.innerHTML = '';
            if (noOrdersMessage) {
                noOrdersMessage.style.display = 'block';
            }
            return;
        }

        const html = state.orders.map(function(order) {
            const status = order.trang_thai_don_hang || '';
            const details = Array.isArray(order.details) ? order.details : [];
            const detailsHtml = details.length > 0
                ? details.map(function(item) {
                    const productName = item.ten_san_pham || 'Sản phẩm đã xóa';
                    const linePrice = formatCurrency(item.gia_luc_mua || 0);
                    const qty = Number(item.so_luong || 0);
                    const variant = item.ten_bien_the ? '<div class="product-meta">Biến thể: ' + escapeHtml(item.ten_bien_the) + '</div>' : '';
                    const image = item.hinh_anh_url || NO_IMAGE_URL;
                    return '<div class="order-product">'
                        + '<img src="' + escapeHtml(image) + '" alt="' + escapeHtml(productName) + '" class="product-thumb">'
                        + '<div class="product-info">'
                        + '<span class="product-name">' + escapeHtml(productName) + '</span>'
                        + '<div class="product-meta">Số lượng: ' + qty + '</div>'
                        + variant
                        + '<div class="product-price">' + linePrice + '</div>'
                        + '</div>'
                        + '</div>';
                }).join('')
                : '<div class="order-product"><img src="' + NO_IMAGE_URL + '" alt="Sản phẩm" class="product-thumb"><div class="product-info"><span class="product-name">Không có sản phẩm</span></div></div>';

            const amount = order.amounts || {};
            const total = formatCurrency(amount.final_total || 0);
            const cancelButton = status === 'cho_duyet'
                ? '<button type="button" class="btn-cancel-order" data-order-id="' + escapeHtml(order.ma_don_hang || '') + '"><i class="fa-solid fa-ban"></i>Hủy đơn</button>'
                : '';

            return '<div class="order-card" data-status="' + escapeHtml(status) + '">'
                + '<div class="order-header">'
                + '<div><span class="order-id">Đơn hàng #' + escapeHtml(order.ma_don_hang || '') + '</span><span class="order-date">Đặt ngày: ' + escapeHtml(formatDateTime(order.ngay_tao)) + '</span></div>'
                + '<span class="status-badge ' + getStatusClass(status) + '">' + escapeHtml(getStatusLabel(status)) + '</span>'
                + '</div>'
                + '<div class="order-body-flex">'
                + '<div class="order-product-list">' + detailsHtml + '</div>'
                + '<div class="order-actions-right">'
                + '<div class="total-money"><span class="total-label">Số tiền thanh toán</span><span class="total-value">' + total + '</span></div>'
                + '<button type="button" class="btn-detail" data-id="' + escapeHtml(order.ma_don_hang || '') + '"><i class="fa-regular fa-eye"></i>Xem chi tiết</button>'
                + cancelButton
                + '</div>'
                + '</div>'
                + '</div>';
        }).join('');

        container.innerHTML = html;
        if (noOrdersMessage) {
            noOrdersMessage.style.display = 'none';
        }
    }

    function findOrderById(orderId) {
        return (state.orders || []).find(function(order) {
            return (order.ma_don_hang || '') === orderId;
        }) || null;
    }

    function loadOrdersByStatus(statusKey) {
        const endpoint = statusKey === 'all'
            ? CHECKOUT_HISTORY_API
            : (CHECKOUT_STATUS_API + '/' + encodeURIComponent(statusKey));

        return fetch(endpoint, { method: 'GET' })
            .then(function(response) {
                return response.json().catch(function() {
                    return { success: false, message: 'Phan hoi API khong hop le' };
                });
            })
            .then(function(json) {
                if (!json || !json.success || !json.data) {
                    throw new Error((json && json.message) ? json.message : 'Khong the lay lich su don hang');
                }

                state.orders = Array.isArray(json.data.orders) ? json.data.orders : [];
                state.counts = json.data.counts || state.counts;
                updateTabCounts(state.counts);
                renderOrderCards();
            });
    }

    const modal = document.getElementById('detailModal');

    function openOrderDetail(order) {
        if (!order || !modal) {
            return;
        }

        const amount = order.amounts || {};
        const subtotal = Number(amount.subtotal || 0);
        const discount = Number(amount.discount || 0);
        const finalTotal = Number(amount.final_total || 0);

        document.getElementById('modalOrderId').innerText = '#' + (order.ma_don_hang || '');
        document.getElementById('modalCode').innerText = order.ma_don_hang || '';
        document.getElementById('modalDate').innerText = formatDateTime(order.ngay_tao);

        const statusEl = document.getElementById('modalStatus');
        statusEl.innerText = getStatusLabel(order.trang_thai_don_hang || '');
        statusEl.className = 'status-badge ' + getStatusClass(order.trang_thai_don_hang || '');

        document.getElementById('modalTotalTop').innerText = formatCurrency(finalTotal);
        document.getElementById('modalSubTotal').innerText = formatCurrency(subtotal);
        document.getElementById('modalDiscount').innerText = '-' + formatCurrency(discount);
        document.getElementById('modalTotalBottom').innerText = formatCurrency(finalTotal);

        document.getElementById('modalReceiver').innerText = order.ten_nguoi_nhan || 'N/A';
        document.getElementById('modalPhone').innerText = order.so_dien_thoai || 'N/A';
        document.getElementById('modalAddress').innerText = order.dia_chi || 'N/A';
        document.getElementById('modalPaymentMethod').innerText = order.phuong_thuc || 'N/A';

        const tbody = document.getElementById('modalProductList');
        tbody.innerHTML = '';
        (order.details || []).forEach(function(item) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td>' + escapeHtml(item.ten_san_pham || 'Sản phẩm đã xóa') + '</td>'
                + '<td>' + formatCurrency(item.gia_luc_mua || 0) + '</td>'
                + '<td style="text-align: center;">' + Number(item.so_luong || 0) + '</td>'
                + '<td style="text-align: right; font-weight: bold; color: var(--blue-btn);">' + formatCurrency(item.line_total || 0) + '</td>';
            tbody.appendChild(tr);
        });

        modal.style.display = 'flex';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tabItems = document.querySelectorAll('.tab-item');

        tabItems.forEach(function(tab) {
            tab.addEventListener('click', function() {
                tabItems.forEach(function(item) { item.classList.remove('active'); });
                this.classList.add('active');

                const key = this.getAttribute('data-status-key') || 'all';
                state.activeStatus = key;
                loadOrdersByStatus(key).catch(function(err) {
                    alert(err.message || 'Không thể tải lịch sử đơn hàng.');
                });
            });
        });

        document.addEventListener('click', function(event) {
            const detailBtn = event.target.closest('.btn-detail');
            if (detailBtn) {
                const orderId = detailBtn.getAttribute('data-id') || '';
                const order = findOrderById(orderId);
                if (order) {
                    openOrderDetail(order);
                }
                return;
            }

            const cancelBtn = event.target.closest('.btn-cancel-order');
            if (cancelBtn) {
                const orderId = cancelBtn.getAttribute('data-order-id') || '';
                if (!orderId) {
                    return;
                }

                if (!confirm('Bạn có chắc muốn hủy đơn hàng #' + orderId + ' không?')) {
                    return;
                }

                cancelBtn.disabled = true;
                cancelBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>Đang hủy...';

                fetch(CHECKOUT_API_BASE + '/' + encodeURIComponent(orderId), { method: 'DELETE' })
                    .then(function(response) {
                        return response.json().catch(function() {
                            return { success: false, message: 'Phan hoi API khong hop le' };
                        });
                    })
                    .then(function(json) {
                        if (!json || !json.success) {
                            throw new Error((json && json.message) ? json.message : 'Khong the huy don');
                        }

                        alert('Hủy đơn hàng thành công.');
                        return loadOrdersByStatus(state.activeStatus);
                    })
                    .catch(function(err) {
                        cancelBtn.disabled = false;
                        cancelBtn.innerHTML = '<i class="fa-solid fa-ban"></i>Hủy đơn';
                        alert(err.message || 'Không thể hủy đơn hàng.');
                    });
            }
        });

        loadOrdersByStatus('all').catch(function(err) {
            alert(err.message || 'Không thể tải lịch sử đơn hàng.');
        });
    });

    // Hàm đóng Modal
    function closeModal() {
        modal.style.display = 'none';
    }

    // Đóng khi click ra ngoài vùng trắng
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
