<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Doanh Thu - Phone Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

        body {
            background: var(--bg);
            margin: 0;
            padding: 20px;
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--gap);
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--accent);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            margin: 10px 0;
        }

        .stat-label {
            font-size: 14px;
            color: var(--muted);
        }

        .form-search {
            display: flex;
            gap: var(--gap);
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 20px;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
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

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e3e7ef;
            border-radius: 10px;
            background: #fbfdff;
            font-size: 14px;
            outline: none;
        }

        .currency.discount {
            color: #dc2626;
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
            margin-bottom: 20px;
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
            table-layout: auto;
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

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e3e7ef;
            vertical-align: top;
            word-wrap: break-word;
        }

        .currency {
            font-weight: 600;
            color: #059669;
        }

        .currency.loss {
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

        .status-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-cho-duyet {
            background: #fef3c7;
            color: #92400e;
        }

        .status-da-duyet {
            background: #dbeafe;
            color: #510c25;
        }

        .status-dang-giao {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-hoan-thanh {
            background: #d1fae5;
            color: #065f46;
        }

        .status-da-huy {
            background: #fee2e2;
            color: #991b1b;
        }

        .payment-cod {
            background: #ffedd5;
            color: #c2410c;
        }

        .payment-momo {
            background: #dbeafe;
            color: #1e40af;
        }

        .payment-vnpay {
            background: #ddd6fe;
            color: #7c3aed;
        }

        .payment-banking {
            background: #f0fdf4;
            color: #166534;
        }

        .chart-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .chart-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #253243;
        }

        .chart-data {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .chart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .chart-item:last-child {
            border-bottom: none;
        }

        .chart-item-label {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .revenue-chart {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .revenue-bar-container {
            width: 100%;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            height: 20px;
        }

        .revenue-bar {
            height: 100%;
            background: var(--accent);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 8px;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-chart-line"></i> Thống Kê</h1>
                <p class="lead">Theo dõi và phân tích cửa hàng điện thoại.</p>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fa-solid fa-shopping-cart fa-2x" style="color: #93c5fd;"></i>
                <div class="stat-value" id="statTongDon"><?php echo $data['tongquan']['tong_don'] ?? 0; ?></div>
                <div class="stat-label">Tổng Đơn Hàng</div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-check-circle fa-2x" style="color: #4ade80;"></i>
                <div class="stat-value" id="statHoanThanh"><?php echo $data['tongquan']['hoan_thanh'] ?? 0; ?></div>
                <div class="stat-label">Đơn Hoàn Thành</div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-truck fa-2x" style="color: #60a5fa;"></i>
                <div class="stat-value" id="statDangGiao"><?php echo $data['tongquan']['dang_giao'] ?? 0; ?></div>
                <div class="stat-label">Đang Giao</div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-clock fa-2x" style="color: #fbbf24;"></i>
                <div class="stat-value" id="statChoDuyet"><?php echo $data['tongquan']['cho_duyet'] ?? 0; ?></div>
                <div class="stat-label">Chờ Duyệt</div>
            </div>
        </div>

        <!-- Form lọc theo ngày và tìm kiếm -->
        <form id="statsFilterForm" class="form-search" onsubmit="return false;">
            <div class="search-fields">
                <div>
                    <label for="txtTuNgay">Từ ngày</label>
                    <input type="date" id="txtTuNgay" name="txtTuNgay"
                        value="<?php echo $data['tungay'] ?? ''; ?>" />
                </div>
                <div>
                    <label for="txtDenNgay">Đến ngày</label>
                    <input type="date" id="txtDenNgay" name="txtDenNgay"
                        value="<?php echo $data['denngay'] ?? ''; ?>" />
                </div>
                <div>
                    <label for="txtMaDonHang">Mã đơn hàng</label>
                    <input type="text" id="txtMaDonHang" name="txtMaDonHang" placeholder="Nhập mã đơn hàng..."
                        value="<?php echo $data['madonhang'] ?? ''; ?>" />
                </div>
                <div>
                    <label for="txtTenKhachHang">Tên khách hàng</label>
                    <input type="text" id="txtTenKhachHang" name="txtTenKhachHang" placeholder="Nhập tên khách hàng..."
                        value="<?php echo $data['tenkhachhang'] ?? ''; ?>" />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="button" class="btn-primary" id="btnLocThongKe"><i class="fa-solid fa-filter"></i> Lọc</button>
                <button type="button" class="btn-ghost" id="btnTimKiemThongKe"><i class="fa-solid fa-search"></i> Tìm
                    kiếm</button>
                <button type="button" class="btn-ghost" id="btnLamMoiThongKe">Làm mới</button>
                <button type="button" class="btn-excel" id="btnXuatExcelThongKe">
                    <i class="fa-solid fa-file-excel"></i> Xuất Excel
                </button>
            </div>
        </form>
    </div>

    <!-- Biểu đồ thống kê -->
    <div class="chart-container">
        <div class="chart-card">
            <div class="chart-title">Thống Kê Theo Phương Thức Thanh Toán</div>
            <div class="chart-data">
                <div class="chart-item">
                    <div class="chart-item-label">
                        <div class="chart-color" style="background: #f97316;"></div>
                        <span>Thanh toán khi nhận (COD)</span>
                    </div>
                    <div>
                        <span
                            class="status-badge payment-cod" id="statCodCount"><?php echo ($data['thongkephuongthuc']['cod']['so_don'] ?? 0) . ' đơn'; ?></span>
                        <span
                            class="currency" id="statCodRevenue"><?php echo number_format($data['thongkephuongthuc']['cod']['doanh_thu'] ?? 0, 0, ',', '.'); ?>
                            ₫</span>
                    </div>
                </div>
                <div class="chart-item">
                    <div class="chart-item-label">
                        <div class="chart-color" style="background: #8b5cf6;"></div>
                        <span>Ví VN_Pay</span>
                    </div>
                    <div>
                        <span
                            class="status-badge payment-vnpay" id="statVnpayCount"><?php echo ($data['thongkephuongthuc']['vnpay']['so_don'] ?? 0) . ' đơn'; ?></span>
                        <span
                            class="currency" id="statVnpayRevenue"><?php echo number_format($data['thongkephuongthuc']['vnpay']['doanh_thu'] ?? 0, 0, ',', '.'); ?>
                            ₫</span>
                    </div>
                </div>
                <!-- <div class="chart-item">
                    <div class="chart-item-label">
                        <div class="chart-color" style="background: #8b5cf6;"></div>
                        <span>Chuyển khoản ngân hàng</span>
                    </div>
                    <div>
                        <span class="status-badge payment-banking"><?php echo ($data['thongkephuongthuc']['banking']['so_don'] ?? 0) . ' đơn'; ?></span>
                        <span class="currency"><?php echo number_format($data['thongkephuongthuc']['banking']['doanh_thu'] ?? 0, 0, ',', '.'); ?> ₫</span>
                    </div>
                </div> -->
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-title">Top Sản Phẩm Bán Chạy</div>
            <div class="chart-data" id="topSanPhamContainer">
                <?php
                $topProducts = $data['top_sanpham'] ?? [];
                if (is_resource($topProducts) || $topProducts instanceof mysqli_result) {
                    $products = [];
                    while ($row = mysqli_fetch_assoc($topProducts)) {
                        $products[] = $row;
                    }
                } else {
                    $products = $topProducts;
                }

                if (!empty($products) && is_array($products)):
                    foreach (array_slice($products, 0, 5) as $index => $product):
                ?>
                        <div class="chart-item">
                            <div class="chart-item-label">
                                <span style="font-weight: 600; color: #4f46e5;"><?php echo ($index + 1); ?>.</span>
                                <span><?php echo htmlspecialchars($product['ten_san_pham'] ?? $product['ten_bien_the'] ?? 'N/A'); ?></span>
                            </div>
                            <div>
                                <span style="color: #6b7280;"><?php echo $product['tong_ban'] ?? 0; ?> cái</span>
                                <span class="currency"><?php echo number_format($product['doanh_thu'] ?? 0, 0, ',', '.'); ?>
                                    ₫</span>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <div style="text-align: center; padding: 20px; color: #6b7280;">Không có dữ liệu</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Danh sách đơn hàng chi tiết -->
    <div class="card">
        <h2><i class="fa-solid fa-list-ul"></i> Thống Kê Đơn Hàng Chi Tiết</h2>
        <div style="margin:10px 0">
            <strong>Kết quả: <span id="resultCount"
                    class="hint"><?php echo is_array($data['danhsachdonhang']) ? count($data['danhsachdonhang']) : 0; ?>
                    đơn hàng</span></strong>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Phương thức TT</th>
                        <th>Trạng thái TT</th>
                        <th>Tổng tiền</th>
                        <th>Khuyến mãi</th>
                        <th>Thanh toán</th>
                    </tr>
                </thead>
                <tbody id="dhBody">
                    <?php
                    $danhSachDonHang = $data['danhsachdonhang'] ?? [];
                    if (is_resource($danhSachDonHang) || $danhSachDonHang instanceof mysqli_result) {
                        $orders = [];
                        while ($row = mysqli_fetch_assoc($danhSachDonHang)) {
                            $orders[] = $row;
                        }
                    } else {
                        $orders = $danhSachDonHang;
                    }

                    if (!empty($orders)):
                        foreach ($orders as $order):
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['ma_don_hang'] ?? 'N/A'); ?></td>
                                <td>
                                    <div style="font-weight: 500;">
                                        <?php echo htmlspecialchars($order['ten_khach_hang'] ?? 'N/A'); ?></div>
                                    <div style="font-size: 13px; color: #666; margin-top: 2px;">
                                        <?php echo htmlspecialchars($order['so_dien_thoai'] ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo isset($order['ngay_tao']) ? date('d/m/Y H:i', strtotime($order['ngay_tao'])) : 'N/A'; ?>
                                </td>
                                <td>
                                    <?php
                                    $status = $order['trang_thai_don_hang'] ?? '';
                                    switch ($status) {
                                        case 'cho_duyet':
                                            $bg = '#fef3c7';
                                            $color = '#92400e';
                                            $label = 'Chờ xác nhận';
                                            break;
                                        case 'da_duyet':
                                            $bg = '#dbeafe';
                                            $color = '#1d4ed8';
                                            $label = 'Đã xác nhận';
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
                                    <span class="status-badge status-<?php echo str_replace('_', '-', $status); ?>"
                                        style="background:<?php echo $bg; ?>; color:<?php echo $color; ?>">
                                        <?php echo $label; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($order['phuong_thuc']): ?>
                                        <span class="status-badge payment-<?php echo strtolower($order['phuong_thuc']); ?>">
                                            <?php echo $order['phuong_thuc']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #9ca3af;">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($order['trang_thai_thanh_toan']): ?>
                                        <span class="status-badge"
                                            style="background: <?php echo $order['trang_thai_thanh_toan'] === 'da_thanh_toan' ? '#d1fae5' : '#fef3c7'; ?>;
                                      color: <?php echo $order['trang_thai_thanh_toan'] === 'da_thanh_toan' ? '#065f46' : '#92400e'; ?>">
                                            <?php echo $order['trang_thai_thanh_toan'] === 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #9ca3af;">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td><span
                                        class="currency"><?php echo number_format($order['tong_tien_hang'] ?? 0, 0, ',', '.'); ?>
                                        ₫</span></td>

                                         <td><span
                                class="currency discount ">-<?php echo number_format($order['tien_khuyen_mai'] ?? 0, 0, ',', '.') ?>
                                ₫</span></td>
                                <!-- <td><span
                                        class="currency <?php echo ($order['tien_khuyen_mai'] ?? 0) < 0 ? 'loss' : ''; ?>"><?php echo number_format($order['tien_khuyen_mai'] ?? 0, 0, ',', '.'); ?>
                                        ₫</span></td> -->
                                <td><span
                                        class="currency"><?php echo number_format($order['thanh_toan'] ?? 0, 0, ',', '.'); ?>
                                        ₫</span></td>                            
                            </tr>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="9" style="text-align: center; color: #6b7280;">Không có đơn hàng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

<script>
    const THONGKE_API_BASE = '<?php echo UrlHelper::url('Api/Thongke'); ?>';

    function formatCurrencyVn(value) {
        return Number(value || 0).toLocaleString('vi-VN') + ' ₫';
    }

    function escapeHtml(text) {
        return String(text || '').replace(/[&<>'"]/g, function(ch) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[ch];
        });
    }

    function getStatusMeta(status) {
        switch (status) {
            case 'cho_duyet':
                return { label: 'Chờ xác nhận', bg: '#fef3c7', color: '#92400e' };
            case 'da_duyet':
                return { label: 'Đã xác nhận', bg: '#dbeafe', color: '#1d4ed8' };
            case 'dang_giao':
                return { label: 'Đang giao', bg: '#dbeafe', color: '#1e40af' };
            case 'hoan_thanh':
                return { label: 'Hoàn thành', bg: '#d1fae5', color: '#065f46' };
            case 'da_huy':
                return { label: 'Đã hủy', bg: '#fee2e2', color: '#991b1b' };
            default:
                return { label: 'Không rõ', bg: '#f3f4f6', color: '#374151' };
        }
    }

    function getCurrentFilters() {
        return {
            tu_ngay: document.getElementById('txtTuNgay')?.value || '',
            den_ngay: document.getElementById('txtDenNgay')?.value || '',
            ma_don_hang: document.getElementById('txtMaDonHang')?.value || '',
            ten_khach_hang: document.getElementById('txtTenKhachHang')?.value || ''
        };
    }

    function buildQuery(filters) {
        const query = new URLSearchParams();
        Object.keys(filters || {}).forEach(function(key) {
            const value = (filters[key] || '').toString().trim();
            if (value !== '') {
                query.set(key, value);
            }
        });
        return query.toString();
    }

    function renderSummary(tongquan) {
        document.getElementById('statTongDon').textContent = Number(tongquan?.tong_don || 0);
        document.getElementById('statHoanThanh').textContent = Number(tongquan?.hoan_thanh || 0);
        document.getElementById('statDangGiao').textContent = Number(tongquan?.dang_giao || 0);
        document.getElementById('statChoDuyet').textContent = Number(tongquan?.cho_duyet || 0);
    }

    function renderPaymentStats(payment) {
        document.getElementById('statCodCount').textContent = Number(payment?.cod?.so_don || 0) + ' đơn';
        document.getElementById('statCodRevenue').textContent = formatCurrencyVn(payment?.cod?.doanh_thu || 0);
        document.getElementById('statVnpayCount').textContent = Number(payment?.vnpay?.so_don || 0) + ' đơn';
        document.getElementById('statVnpayRevenue').textContent = formatCurrencyVn(payment?.vnpay?.doanh_thu || 0);
    }

    function renderTopProducts(items) {
        const container = document.getElementById('topSanPhamContainer');
        if (!container) return;

        if (!Array.isArray(items) || items.length === 0) {
            container.innerHTML = '<div style="text-align:center; padding:20px; color:#6b7280;">Không có dữ liệu</div>';
            return;
        }

        const html = items.slice(0, 5).map(function(product, index) {
            const name = product.ten_san_pham || product.ten_bien_the || 'N/A';
            const sold = Number(product.tong_ban || 0);
            const revenue = formatCurrencyVn(product.doanh_thu || 0);
            return '<div class="chart-item">'
                + '<div class="chart-item-label">'
                + '<span style="font-weight:600; color:#4f46e5;">' + (index + 1) + '.</span>'
                + '<span>' + escapeHtml(name) + '</span>'
                + '</div>'
                + '<div><span style="color:#6b7280;">' + sold + ' cái</span> '
                + '<span class="currency">' + revenue + '</span></div>'
                + '</div>';
        }).join('');

        container.innerHTML = html;
    }

    function renderOrders(orders) {
        const body = document.getElementById('dhBody');
        const count = document.getElementById('resultCount');
        if (!body) return;

        if (!Array.isArray(orders) || orders.length === 0) {
            body.innerHTML = '<tr><td colspan="9" style="text-align:center; color:#6b7280;">Không có đơn hàng nào</td></tr>';
            if (count) count.textContent = '0 đơn hàng';
            return;
        }

        const html = orders.map(function(order) {
            const status = getStatusMeta(order.trang_thai_don_hang || '');
            const paymentMethod = (order.phuong_thuc || '').toString().toLowerCase();
            const paymentBadge = paymentMethod
                ? '<span class="status-badge payment-' + escapeHtml(paymentMethod) + '">' + escapeHtml(order.phuong_thuc) + '</span>'
                : '<span style="color:#9ca3af;">N/A</span>';

            const paymentStatus = order.trang_thai_thanh_toan
                ? '<span class="status-badge" style="background:' + (order.trang_thai_thanh_toan === 'da_thanh_toan' ? '#d1fae5' : '#fef3c7')
                + '; color:' + (order.trang_thai_thanh_toan === 'da_thanh_toan' ? '#065f46' : '#92400e') + ';">'
                + (order.trang_thai_thanh_toan === 'da_thanh_toan' ? 'Đã thanh toán' : 'Chưa thanh toán') + '</span>'
                : '<span style="color:#9ca3af;">N/A</span>';

            const created = order.ngay_tao ? new Date(order.ngay_tao.replace(' ', 'T')) : null;
            const createdText = created && !isNaN(created.getTime()) ? created.toLocaleString('vi-VN') : (order.ngay_tao || 'N/A');

            return '<tr>'
                + '<td>' + escapeHtml(order.ma_don_hang || 'N/A') + '</td>'
                + '<td><div style="font-weight:500;">' + escapeHtml(order.ten_khach_hang || 'N/A') + '</div>'
                + '<div style="font-size:13px; color:#666; margin-top:2px;">' + escapeHtml(order.so_dien_thoai || 'N/A') + '</div></td>'
                + '<td>' + escapeHtml(createdText) + '</td>'
                + '<td><span class="status-badge" style="background:' + status.bg + '; color:' + status.color + ';">' + status.label + '</span></td>'
                + '<td>' + paymentBadge + '</td>'
                + '<td>' + paymentStatus + '</td>'
                + '<td><span class="currency">' + formatCurrencyVn(order.tong_tien_hang || 0) + '</span></td>'
                + '<td><span class="currency discount">-' + formatCurrencyVn(order.tien_khuyen_mai || 0) + '</span></td>'
                + '<td><span class="currency">' + formatCurrencyVn(order.thanh_toan || 0) + '</span></td>'
                + '</tr>';
        }).join('');

        body.innerHTML = html;
        if (count) count.textContent = orders.length + ' đơn hàng';
    }

    function loadDashboard(filters) {
        const query = buildQuery(filters || {});
        const endpoint = query ? (THONGKE_API_BASE + '/dashboard?' + query) : (THONGKE_API_BASE + '/dashboard');

        return fetch(endpoint, { method: 'GET' })
            .then(function(response) {
                return response.json().catch(function() {
                    return { success: false, message: 'Phản hồi API không hợp lệ' };
                });
            })
            .then(function(json) {
                if (!json || !json.success || !json.data) {
                    throw new Error((json && json.message) ? json.message : 'Không thể tải dữ liệu thống kê');
                }

                renderSummary(json.data.tongquan || {});
                renderPaymentStats(json.data.thongkephuongthuc || {});
                renderTopProducts(json.data.top_sanpham || []);
                renderOrders(json.data.danhsachdonhang || []);
            });
    }

    function exportExcel(filters) {
        const query = buildQuery(filters || {});
        const endpoint = query ? (THONGKE_API_BASE + '/export?' + query) : (THONGKE_API_BASE + '/export');
        window.location.href = endpoint;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnLoc = document.getElementById('btnLocThongKe');
        const btnTim = document.getElementById('btnTimKiemThongKe');
        const btnRefresh = document.getElementById('btnLamMoiThongKe');
        const btnExcel = document.getElementById('btnXuatExcelThongKe');

        if (btnLoc) {
            btnLoc.addEventListener('click', function() {
                loadDashboard(getCurrentFilters()).catch(function(err) {
                    alert(err.message || 'Không thể lọc dữ liệu thống kê.');
                });
            });
        }

        if (btnTim) {
            btnTim.addEventListener('click', function() {
                loadDashboard(getCurrentFilters()).catch(function(err) {
                    alert(err.message || 'Không thể tìm kiếm dữ liệu thống kê.');
                });
            });
        }

        if (btnRefresh) {
            btnRefresh.addEventListener('click', function() {
                document.getElementById('txtTuNgay').value = '';
                document.getElementById('txtDenNgay').value = '';
                document.getElementById('txtMaDonHang').value = '';
                document.getElementById('txtTenKhachHang').value = '';
                loadDashboard({}).catch(function(err) {
                    alert(err.message || 'Không thể làm mới thống kê.');
                });
            });
        }

        if (btnExcel) {
            btnExcel.addEventListener('click', function() {
                exportExcel(getCurrentFilters());
            });
        }

        loadDashboard(getCurrentFilters()).catch(function(err) {
            alert(err.message || 'Không thể tải dữ liệu thống kê.');
        });
    });
</script>

</html>
