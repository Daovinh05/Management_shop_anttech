<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn - <?php echo htmlspecialchars($data['order_info']['ma_don_hang'] ?? 'N/A'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #fff;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .customer-info, .order-info {
            flex: 1;
        }
        .info-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 14px;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-around;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 40px;
        }
        .print-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>HÓA ĐƠN THANH TOÁN</h1>
            <div class="company-info">
                CỬA HÀNG ĐIỆN THOẠI PHONE STORE<br>
                Địa chỉ: 123 Đường ABC, Quận XYZ, TP. HCM<br>
                Điện thoại: (028) 1234 5678 | Email: info@phonestore.vn
            </div>
        </div>

        <div class="invoice-info">
            <div class="customer-info">
                <div class="info-title">Thông tin khách hàng:</div>
                <div><strong>Họ tên:</strong> <?php echo htmlspecialchars($data['order_info']['full_name'] ?? 'N/A'); ?></div>
                <div><strong>Điện thoại:</strong> <?php echo htmlspecialchars($data['order_info']['so_dien_thoai'] ?? 'N/A'); ?></div>
                <div><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($data['order_info']['dia_chi'] ?? 'N/A'); ?></div>
            </div>
            <div class="order-info">
                <div class="info-title">Thông tin hóa đơn:</div>
                <div><strong>Mã hóa đơn:</strong> <?php echo htmlspecialchars($data['order_info']['ma_don_hang'] ?? 'N/A'); ?></div>
                <div><strong>Ngày lập:</strong> <?php echo isset($data['order_info']['ngay_tao']) ? date('d/m/Y H:i:s', strtotime($data['order_info']['ngay_tao'])) : 'N/A'; ?></div>
                <div><strong>Trạng thái:</strong> 
                    <?php
                    $status = $data['order_info']['trang_thai_don_hang'] ?? '';
                    echo match($status) {
                        'cho_duyet'  => 'Chờ duyệt',
                        'da_duyet'   => 'Đã xác nhận)',
                        'dang_giao'  => 'Đang giao',
                        'hoan_thanh' => 'Hoàn thành',
                        'da_huy'     => 'Đã hủy',
                        default      => 'Không rõ',
                    };
                    ?>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Cấu hình</th>
                    <th>ĐVT</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $stt = 1;
                $tong_tien = 0;
                if (isset($data['order_details']) && is_array($data['order_details'])):
                    foreach ($data['order_details'] as $item):
                        $thanhtien = $item['so_luong'] * $item['gia_luc_mua'];
                        $tong_tien += $thanhtien;
                ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><?php echo htmlspecialchars($item['ten_san_pham'] ?? 'N/A'); ?></td>
                    <td>
                        <?php 
                        $config = [];
                        if (!empty($item['mau_sac'])) $config[] = $item['mau_sac'];
                        if (!empty($item['ram'])) $config[] = $item['ram'];
                        if (!empty($item['dung_luong'])) $config[] = $item['dung_luong'];
                        echo implode(' / ', $config);
                        ?>
                    </td>
                    <td>Cái</td>
                    <td><?php echo $item['so_luong']; ?></td>
                    <td><?php echo number_format($item['gia_luc_mua'], 0, ',', '.'); ?> ₫</td>
                    <td><?php echo number_format($thanhtien, 0, ',', '.'); ?> ₫</td>
                </tr>
                <?php 
                    endforeach;
                endif;
                ?>
                <tr class="total-row">
                    <td colspan="6" style="text-align: right;"><strong>Tổng cộng:</strong></td>
                    <td><strong><?php echo number_format($tong_tien, 0, ',', '.'); ?> ₫</strong></td>
                </tr>
                <?php if (isset($data['order_info']['tien_khuyen_mai']) && $data['order_info']['tien_khuyen_mai'] > 0): ?>
                <tr class="total-row">
                    <td colspan="6" style="text-align: right;"><strong>Giảm giá:</strong></td>
                    <td><strong>-<?php echo number_format($data['order_info']['tien_khuyen_mai'], 0, ',', '.'); ?> ₫</strong></td>
                </tr>
                <tr class="total-row">
                    <td colspan="6" style="text-align: right;"><strong>Thành tiền:</strong></td>
                    <td><strong><?php echo number_format($tong_tien - ($data['order_info']['tien_khuyen_mai'] ?? 0), 0, ',', '.'); ?> ₫</strong></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            <p>Xin cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của chúng tôi!</p>
            <p>Vui lòng kiểm tra kỹ hóa đơn trước khi rời khỏi cửa hàng.</p>
        </div>

        <div class="signature-section">
            <div class="signature">
                Người lập hóa đơn
                <div class="signature-line">Ký tên</div>
            </div>
            <div class="signature">
                Khách hàng
                <div class="signature-line">Ký tên</div>
            </div>
        </div>

        <button class="print-button" onclick="window.print()">In Hóa Đơn</button>
    </div>
</body>
</html>