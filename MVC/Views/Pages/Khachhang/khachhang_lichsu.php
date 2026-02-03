<?php
// Include necessary helpers
include_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Classes/TimezoneHelper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Classes/UrlHelper.php';
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

    .status-confirmed {
        background-color: #e3f9e5;
        color: #1f8b24;
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
</style>
<div class="container">
    <div class="order-history-wrapper">
        <h1 class="page-title">Đơn hàng của bạn</h1>

        <div class="order-tabs">
            <div class="tab-item active">Tất cả (<?php echo count($data['don_hang']); ?>)</div>
            <div class="tab-item">Chờ xác nhận (<?php echo $data['status_counts']['cho_duyet']; ?>)</div>
            <div class="tab-item">Đã xác nhận (<?php echo $data['status_counts']['da_duyet']; ?>)</div>
            <div class="tab-item">Đang giao (<?php echo $data['status_counts']['dang_giao']; ?>)</div>
            <div class="tab-item">Hoàn thành (<?php echo $data['status_counts']['hoan_thanh']; ?>)</div>
            <div class="tab-item">Đã hủy (<?php echo $data['status_counts']['da_huy']; ?>)</div>
        </div>

        <?php if ($data['don_hang'] && count($data['don_hang']) > 0): ?>
            <?php foreach ($data['don_hang'] as $dh): ?>
                <div class="order-card" data-status="<?php echo $dh['trang_thai_don_hang']; ?>">
                    <div class="order-header">
                        <div>
                            <span class="order-id">Đơn hàng #<?php echo $dh['ma_don_hang']; ?></span>
                            <span class="order-date">Đặt ngày: <?php echo $this->formatDate($dh['ngay_tao']); ?></span>
                        </div>
                        <span class="status-badge
                        <?php
                        switch ($dh['trang_thai_don_hang']) {
                            case 'cho_duyet':
                            case 'da_duyet':
                                echo 'status-confirmed';
                                break;
                            case 'dang_giao':
                                echo 'status-shipping';
                                break;
                            case 'hoan_thanh':
                                echo 'status-confirmed';
                                break;
                            case 'da_huy':
                                echo 'status-cancelled';
                                break;
                            default:
                                echo 'status-cancelled';
                        }
                        ?>">
                            <?php
                            switch ($dh['trang_thai_don_hang']) {
                                case 'cho_duyet':
                                    echo 'Chờ xác nhận';
                                    break;
                                case 'da_duyet':
                                    echo 'Đã xác nhận';
                                    break;
                                case 'dang_giao':
                                    echo 'Đang giao hàng';
                                    break;
                                case 'hoan_thanh':
                                    echo 'Đã hoàn thành';
                                    break;
                                case 'da_huy':
                                    echo 'Đã hủy';
                                    break;
                                default:
                                    echo ucfirst(str_replace('_', ' ', $dh['trang_thai_don_hang']));
                            }
                            ?>
                        </span>
                    </div>

                    <div class="order-body-flex">

                        <div class="order-product-list">
                            <?php
                            $chi_tiet_don_hang = $dh['chi_tiet'];
                            if ($chi_tiet_don_hang && count($chi_tiet_don_hang) > 0):
                                foreach ($chi_tiet_don_hang as $ct):
                            ?>
                                    <div class="order-product">
                                        <img src="<?php echo !empty($ct['hinh_anh']) ? '/Banhang/Public/Pictures/bien_the/' . $ct['hinh_anh'] : 'https://placehold.co/80x80?text=SP'; ?>"
                                            alt="<?php echo $ct['ten_san_pham']; ?>" class="product-thumb">
                                        <div class="product-info">
                                            <span
                                                class="product-name"><?php echo $ct['ten_san_pham'] ? $ct['ten_san_pham'] : 'Sản phẩm đã xóa'; ?></span>
                                            <div class="product-meta">Số lượng: <?php echo $ct['so_luong']; ?></div>
                                            <?php if ($ct['ten_bien_the']): ?>
                                                <div class="product-meta">Biến thể: <?php echo $ct['ten_bien_the']; ?></div>
                                            <?php endif; ?>
                                            <div class="product-price">
                                                <?php echo number_format($ct['gia_luc_mua'], 0, ',', '.'); ?>₫</div>
                                        </div>
                                    </div><?php endforeach;
                                    else: ?><div class="order-product"><img src="https://placehold.co/80x80?text=SP"
                                        alt="Sản phẩm" class="product-thumb">
                                    <div class="product-info"><span class="product-name">Không có sản phẩm</span></div>
                                </div><?php endif;
                                        ?>
                        </div>
                        <div class="order-actions-right">
                            <div class="total-money"><span class="total-label">Số tiền thanh toán</span><span
                                    class="total-value"><?php echo number_format($dh['tong_tien_hang'], 0, ',', '.');
                                                        ?>₫</span></div><button type="button" class="btn-detail"
                                data-id="<?php echo $dh['ma_don_hang']; ?>"
                                data-date="<?php echo $this->formatDate($dh['ngay_tao']); ?>" data-status="<?php
                                                                                                            switch ($dh['trang_thai_don_hang']) {
                                                                                                                case 'cho_duyet':
                                                                                                                    echo 'Chờ xác nhận';
                                                                                                                    break;
                                                                                                                case 'da_duyet':
                                                                                                                    echo 'Đã xác nhận';
                                                                                                                    break;
                                                                                                                case 'dang_giao':
                                                                                                                    echo 'Đang giao hàng';
                                                                                                                    break;
                                                                                                                case 'hoan_thanh':
                                                                                                                    echo 'Đã hoàn thành';
                                                                                                                    break;
                                                                                                                case 'da_huy':
                                                                                                                    echo 'Đã hủy';
                                                                                                                    break;
                                                                                                                default:
                                                                                                                    echo ucfirst(str_replace('_', ' ', $dh['trang_thai_don_hang']));
                                                                                                            }

                                                                                                            ?>"
                                data-status-class="
<?php switch ($dh['trang_thai_don_hang']) {
                    case 'cho_duyet':
                    case 'da_duyet':
                        echo 'status-confirmed';
                        break;
                    case 'dang_giao':
                        echo 'status-shipping';
                        break;
                    case 'hoan_thanh':
                        echo 'status-confirmed';
                        break;
                    case 'da_huy':
                        echo 'status-cancelled';
                        break;
                    default:
                        echo 'status-cancelled';
                }

?>" data-total="<?php echo number_format($dh['tong_tien_hang'], 0, ',', '.');
                ?>₫"
                                data-receiver="<?php echo htmlspecialchars($dh['ten_nguoi_nhan'] ?? 'N/A'); ?>"
                                data-phone="<?php echo htmlspecialchars($dh['so_dien_thoai'] ?? 'N/A'); ?>"
                                data-address="<?php echo htmlspecialchars($dh['dia_chi'] ?? 'N/A'); ?>"
                                data-payment-method="<?php echo htmlspecialchars($dh['phuong_thuc'] ?? 'N/A'); ?>"
                                data-discount="<?php echo number_format($dh['tien_khuyen_mai'] ?? 0, 0, ',', '.'); ?>₫"
                                data-subtotal="<?php echo number_format($dh['tong_tien_hang'] - ($dh['tien_khuyen_mai'] ?? 0), 0, ',', '.'); ?>₫"
                                data-items='<?php
                                            $items = [];

                                            foreach ($dh['chi_tiet'] as $ct) {
                                                $items[] = [
                                                    "name" => $ct['ten_san_pham'] ? $ct['ten_san_pham'] : 'Sản phẩm đã xóa',
                                                    "price" => number_format($ct['gia_luc_mua'], 0, ',', '.') . '₫',
                                                    "qty" => $ct['so_luong'],
                                                    "subtotal" => number_format($ct['gia_luc_mua'] * $ct['so_luong'], 0, ',', '.') . '₫',
                                                    "image" => !empty($ct['hinh_anh']) ? '/Banhang/Public/Pictures/bien_the/' . $ct['hinh_anh'] : 'https://placehold.co/80x80?text=SP'
                                                ];
                                            }

                                            echo json_encode($items);
                                            ?>'>
                                <i class="fa-regular fa-eye"></i>Xem chi tiết</button>
                        </div>
                    </div>
                </div><?php endforeach;
                        ?><?php else: ?><div class="text-center py-5"><i
                        class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h4>Bạn chưa có đơn hàng nào</h4>
                    <p>Hãy bắt đầu mua sắm để có đơn hàng đầu tiên</p><a href="<?php echo $this->url('Khachhang'); ?>"
                        class="btn btn-primary">Mua sắm ngay</a>
                </div><?php endif;
                        ?>
    </div>
</div>
< !-- Modal for Order Details -->
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
                        <div class="info-row"><span class="info-label">Tổng tiền:</span><span class="info-value"
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
                                <th style="width: 150px; text-align: right;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="modalProductList"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer-sum">
                <div class="sum-row"><span class="sum-label">Tạm tính:</span><span class="sum-value"
                        id="modalSubTotal">15.600.000 VNĐ</span></div>
                <div class="sum-row"><span class="sum-label">Khuyến mãi:</span><span class="sum-value"
                        id="modalDiscount">0 VNĐ</span></div>
                <div class="sum-row"><span class="sum-label" style="font-weight: 700;">Tổng cộng:</span><span
                        class="sum-value sum-total" id="modalTotalBottom">15.600.000 VNĐ</span></div>
            </div>
        </div>
    </div>
    <script>
        // Tab functionality with order filtering

        document.addEventListener('DOMContentLoaded', function() {
                const tabItems = document.querySelectorAll('.tab-item');
                const orderCards = document.querySelectorAll('.order-card');

                // Map tab indices to status values based on the order in the HTML
                // 0: "Tất cả", 1: "Chø xác nhận", 2: "Đã xác nhận", 3: "Đang giao", 4: "Hoàn thành", 5: "Đã hủy"
                const tabStatusMap = {
                    0: 'all', // Tất cả
                    1: 'cho_duyet', // Chờ xác nhận
                    2: 'da_duyet', // Đã xác nhận (same as cho_duyet in DB)
                    3: 'dang_giao', // Đang giao
                    4: 'hoan_thanh', // Hoàn thành
                    5: 'da_huy' // Đã hủy
                }

                ;

                tabItems.forEach((tab, index) => {
                        tab.addEventListener('click', function() {
                                // Remove active class from all tabs
                                tabItems.forEach(item => item.classList.remove('active'));
                                this.classList.add('active');

                                // Get the status to filter by
                                const filterStatus = tabStatusMap[index];

                                // Show/hide order cards based on status
                                orderCards.forEach(card => {
                                        const cardStatus = card.getAttribute('data-status');

                                        if (filterStatus === 'all' || cardStatus === filterStatus) {
                                            card.style.display = 'block';
                                        } else {
                                            card.style.display = 'none';
                                        }
                                    }

                                );
                            }

                        );
                    }

                );
            }

        );

        // Modal functionality
        const modal = document.getElementById('detailModal');
        const btns = document.querySelectorAll('.btn-detail');

        // Hàm mở Modal và điền dữ liệu
        btns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                        e.preventDefault(); // Ngăn load lại trang

                        // 1. Lấy dữ liệu từ attribute data- của nút bấm
                        const id = this.getAttribute('data-id');
                        const date = this.getAttribute('data-date');
                        const status = this.getAttribute('data-status');
                        const statusClass = this.getAttribute('data-status-class');
                        const total = this.getAttribute('data-total');
                        const receiver = this.getAttribute('data-receiver');
                        const phone = this.getAttribute('data-phone');
                        const address = this.getAttribute('data-address');
                        const paymentMethod = this.getAttribute('data-payment-method');
                        const discount = this.getAttribute('data-discount');
                        const subtotal = this.getAttribute('data-subtotal');
                        const items = JSON.parse(this.getAttribute('data-items'));

                        // 2. Điền dữ liệu vào Modal
                        document.getElementById('modalOrderId').innerText = '#' + id;
                        document.getElementById('modalCode').innerText = id;
                        document.getElementById('modalDate').innerText = date;

                        const statusEl = document.getElementById('modalStatus');
                        statusEl.innerText = status;
                        statusEl.className = 'status-badge ' + statusClass;

                        document.getElementById('modalTotalTop').innerText = total;
                        document.getElementById('modalSubTotal').innerText = subtotal;
                        document.getElementById('modalTotalBottom').innerText = total;
                        document.getElementById('modalDiscount').innerText = discount;

                        // Fill shipping information
                        document.getElementById('modalReceiver').innerText = receiver;
                        document.getElementById('modalPhone').innerText = phone;
                        document.getElementById('modalAddress').innerText = address;
                        document.getElementById('modalPaymentMethod').innerText = paymentMethod;

                        // 3. Render danh sách sản phẩm
                        const tbody = document.getElementById('modalProductList');
                        tbody.innerHTML = ''; // Xóa cũ

                        items.forEach(item => {
                                const tr = document.createElement('tr');

                                tr.innerHTML = `<td>${item.name}</td><td>${item.price}</td><td style="text-align: center;">${item.qty}</td><td style="text-align: right; font-weight: bold; color: var(--blue-btn);">${item.subtotal}</td>`;
                                tbody.appendChild(tr);
                            }

                        );

                        // 4. Hiển thị Modal
                        modal.style.display = 'flex';
                    }

                );
            }

        );

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