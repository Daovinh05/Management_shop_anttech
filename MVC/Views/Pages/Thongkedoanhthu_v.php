<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hệ thống - Thống kê doanh thu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .revenue-container {
            padding: 20px;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #4361ee;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Thống kê tổng quan */
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .stat-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .stat-icon.blue {
            background: #e7f0ff;
            color: #4361ee;
        }
        
        .stat-icon.orange {
            background: #fff4ed;
            color: #ff6b35;
        }
        
        .stat-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .stat-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-list {
            flex: 1;
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .stat-label {
            color: #666;
        }
        
        .stat-value {
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .stat-value.red {
            color: #ef4444;
        }
        
        .stat-value.green {
            color: #10b981;
        }
        
        /* Chart */
        .stat-chart {
            position: relative;
            width: 120px;
            height: 120px;
        }
        
        .circle-chart {
            transform: rotate(-90deg);
        }
        
        .circle-bg {
            fill: none;
            stroke: #e5e7eb;
            stroke-width: 10;
        }
        
        .circle-progress {
            fill: none;
            stroke: #10b981;
            stroke-width: 10;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.5s ease;
        }
        
        .chart-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        
        .chart-number {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .chart-total {
            font-size: 12px;
            color: #666;
        }
        
        .chart-legend {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            font-size: 12px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .legend-dot.green {
            background: #10b981;
        }
        
        .legend-dot.gray {
            background: #9ca3af;
        }
        
        /* Payment methods */
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 15px;
        }
        
        .payment-method {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 12px;
        }
        
        .payment-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        
        .payment-icon.cod {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .payment-icon.momo {
            background: #fce7f3;
            color: #db2777;
        }
        
        .payment-icon.banking {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .payment-info {
            flex: 1;
        }
        
        .payment-name {
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .payment-count {
            font-size: 12px;
            color: #999;
        }
        
        .payment-amount {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        /* Orders table */
        .orders-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .section-icon {
            font-size: 20px;
            color: #4361ee;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .orders-table thead th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .orders-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
        }
        
        .orders-table tbody tr:hover {
            background: #f9fafb;
        }
        
        .orders-table tbody td {
            padding: 16px;
            font-size: 14px;
            color: #1a1a1a;
        }
        
        .order-id {
            color: #4361ee;
            font-weight: 600;
        }
        
        .customer-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .customer-name {
            font-weight: 600;
        }
        
        .customer-phone {
            font-size: 12px;
            color: #666;
        }
        
        .amount {
            font-weight: 600;
        }
        
        .profit {
            color: #10b981;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-badge.pending {
            background: #fff7ed;
            color: #ea580c;
        }
        
        .status-badge.shipping {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .status-badge.completed {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .status-badge.cancelled {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .payment-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background: #f3f4f6;
            color: #666;
        }
        
        .payment-badge.paid {
            background: #dcfce7;
            color: #16a34a;
        }
        
        .payment-badge.unpaid {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="revenue-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Quản lý hệ thống</h1>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <span>admin</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <!-- Stats Overview -->
        <div class="stats-overview">
            <!-- Card 1: Tình trạng đơn hàng -->
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon blue">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="stat-title">Tình trạng đơn hàng</h3>
                </div>
                <div class="stat-content">
                    <div class="stat-list">
                        <div class="stat-item">
                            <span class="stat-label">Chờ duyệt</span>
                            <span class="stat-value"><?php echo isset($tongquan['cho_duyet']) ? $tongquan['cho_duyet'] : 0; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Đang giao</span>
                            <span class="stat-value"><?php echo isset($tongquan['dang_giao']) ? $tongquan['dang_giao'] : 0; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Hoàn thành</span>
                            <span class="stat-value green"><?php echo isset($tongquan['hoan_thanh']) ? $tongquan['hoan_thanh'] : 0; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Đã hủy</span>
                            <span class="stat-value red"><?php echo isset($tongquan['da_huy']) ? $tongquan['da_huy'] : 0; ?></span>
                        </div>
                    </div>
                    <div class="stat-chart">
                        <?php 
                            $tongDon = isset($tongquan['tong_don']) ? $tongquan['tong_don'] : 0;
                            $hoanThanh = isset($tongquan['hoan_thanh']) ? $tongquan['hoan_thanh'] : 0;
                            $tiLeHoanThanh = $tongDon > 0 ? ($hoanThanh / $tongDon) * 100 : 0;
                            $circumference = 2 * 3.14159 * 50;
                            $offset = $circumference - ($tiLeHoanThanh / 100) * $circumference;
                        ?>
                        <svg class="circle-chart" width="120" height="120">
                            <circle class="circle-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="circle-progress" cx="60" cy="60" r="50" 
                                    stroke-dasharray="<?php echo $circumference; ?>" 
                                    stroke-dashoffset="<?php echo $offset; ?>"></circle>
                        </svg>
                        <div class="chart-label">
                            <div class="chart-number"><?php echo $tongDon; ?></div>
                            <div class="chart-total">Tổng</div>
                        </div>
                    </div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-dot green"></span>
                        <span>Oki</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot gray"></span>
                        <span>Hủy</span>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Kênh thanh toán -->
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon orange">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="stat-title">Kênh thanh toán</h3>
                </div>
                <div class="stat-content">
                    <div class="stat-chart">
                        <?php 
                            $tongSoDon = isset($thongkephuongthuc['tong_so_don']) ? $thongkephuongthuc['tong_so_don'] : 0;
                            $codDon = isset($thongkephuongthuc['cod']['so_don']) ? $thongkephuongthuc['cod']['so_don'] : 0;
                            $tiLeCod = $tongSoDon > 0 ? ($codDon / $tongSoDon) * 100 : 0;
                            $offsetPayment = $circumference - ($tiLeCod / 100) * $circumference;
                        ?>
                        <svg class="circle-chart" width="120" height="120">
                            <circle class="circle-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="circle-progress" cx="60" cy="60" r="50" 
                                    stroke-dasharray="<?php echo $circumference; ?>" 
                                    stroke-dashoffset="<?php echo $offsetPayment; ?>"></circle>
                        </svg>
                        <div class="chart-label">
                            <div class="chart-number"><?php echo $tongSoDon; ?></div>
                            <div class="chart-total">Tổng</div>
                        </div>
                    </div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-dot green"></span>
                        <span>COD</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot gray"></span>
                        <span>Online</span>
                    </div>
                </div>
                <div class="payment-methods">
                    <div class="payment-method">
                        <div class="payment-icon cod">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="payment-info">
                            <div class="payment-name">COD</div>
                            <div class="payment-count"><?php echo isset($thongkephuongthuc['cod']['so_don']) ? $thongkephuongthuc['cod']['so_don'] : 0; ?> đơn</div>
                        </div>
                        <div class="payment-amount">
                            <?php echo isset($thongkephuongthuc['cod']['doanh_thu']) ? number_format($thongkephuongthuc['cod']['doanh_thu'] / 1000, 0, ',', '.') : '0'; ?>K
                        </div>
                    </div>
                    <div class="payment-method">
                        <div class="payment-icon momo">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="payment-info">
                            <div class="payment-name">MOMO</div>
                            <div class="payment-count"><?php echo isset($thongkephuongthuc['momo']['so_don']) ? $thongkephuongthuc['momo']['so_don'] : 0; ?> đơn</div>
                        </div>
                        <div class="payment-amount">
                            <?php echo isset($thongkephuongthuc['momo']['doanh_thu']) ? number_format($thongkephuongthuc['momo']['doanh_thu'] / 1000, 0, ',', '.') : '0'; ?>K
                        </div>
                    </div>
                    <div class="payment-method">
                        <div class="payment-icon banking">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="payment-info">
                            <div class="payment-name">BANKING</div>
                            <div class="payment-count"><?php echo isset($thongkephuongthuc['banking']['so_don']) ? $thongkephuongthuc['banking']['so_don'] : 0; ?> đơn</div>
                        </div>
                        <div class="payment-amount">
                            <?php echo isset($thongkephuongthuc['banking']['doanh_thu']) ? number_format($thongkephuongthuc['banking']['doanh_thu'] / 1000, 0, ',', '.') : '0'; ?>K
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Orders List -->
        <div class="orders-section">
            <div class="section-header">
                <i class="fas fa-list section-icon"></i>
                <h3 class="section-title">Danh sách đơn hàng chi tiết</h3>
            </div>
            
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>MÃ ĐƠN / KHÁCH</th>
                        <th>NGÀY ĐẶT</th>
                        <th>DOANH THU</th>
                        <th>GIÁ VỐN</th>
                        <th>LỢI NHUẬN</th>
                        <th>% LÃI</th>
                        <th>THANH TOÁN</th>
                        <th>TRẠNG THÁI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (isset($danhsachdonhang) && count($danhsachdonhang) > 0) {
                        foreach ($danhsachdonhang as $donhang) {
                            $maDon = $donhang['ma_don_hang'];
                            $tenKhach = $donhang['ten_khach_hang'] ?? 'N/A';
                            $sdt = $donhang['so_dien_thoai'] ?? '';
                            $ngayDat = date('d/m/Y H:i', strtotime($donhang['ngay_tao']));
                            $doanhThu = number_format($donhang['tong_tien_hang'], 0, ',', '.');
                            $giaVon = number_format($donhang['gia_von'] ?? 0, 0, ',', '.');
                            $loiNhuan = number_format($donhang['loi_nhuan'] ?? 0, 0, ',', '.');
                            $tyLeLai = number_format($donhang['ty_le_lai'] ?? 0, 1);
                            $phuongThuc = strtoupper($donhang['phuong_thuc'] ?? 'N/A');
                            $trangThai = $donhang['trang_thai_don_hang'];
                            $trangThaiTT = $donhang['trang_thai_thanh_toan'] ?? 'chua_thanh_toan';
                            
                            // Xác định class cho trạng thái đơn hàng
                            $statusClass = 'pending';
                            $statusText = '';
                            
                            switch($trangThai) {
                                case 'cho_duyet':
                                    $statusClass = 'pending';
                                    $statusText = 'Chờ duyệt';
                                    break;
                                case 'dang_giao':
                                    $statusClass = 'shipping';
                                    $statusText = 'Đang giao';
                                    break;
                                case 'hoan_thanh':
                                    $statusClass = 'completed';
                                    $statusText = 'Hoàn thành';
                                    break;
                                case 'da_huy':
                                    $statusClass = 'cancelled';
                                    $statusText = 'Đã hủy';
                                    break;
                                default:
                                    $statusText = $trangThai;
                            }
                            
                            // Xác định class cho trạng thái thanh toán
                            $paymentClass = ($trangThaiTT == 'da_thanh_toan') ? 'paid' : 'unpaid';
                            $paymentText = ($trangThaiTT == 'da_thanh_toan') ? 'Đã TT' : 'Chưa TT';
                    ?>
                    <tr>
                        <td>
                            <div class="customer-info">
                                <span class="order-id">#<?php echo $maDon; ?></span>
                                <span class="customer-name"><?php echo $tenKhach; ?></span>
                                <?php if ($sdt): ?>
                                <span class="customer-phone"><?php echo $sdt; ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo $ngayDat; ?></td>
                        <td class="amount"><?php echo $doanhThu; ?>đ</td>
                        <td><?php echo $giaVon; ?>đ</td>
                        <td class="profit"><?php echo $loiNhuan; ?>đ</td>
                        <td>
                            <span class="status-badge pending"><?php echo $tyLeLai; ?>%</span>
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <span class="payment-badge"><?php echo $phuongThuc; ?></span>
                                <span class="payment-badge <?php echo $paymentClass; ?>"><?php echo $paymentText; ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px;"></i>
                            <p>Không có đơn hàng nào</p>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>