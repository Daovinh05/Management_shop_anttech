<?php
class Thongke extends api_controller {
    private $dh;

    public function __construct() {
        parent::__construct();
        $this->dh = $this->model('DonHang_m');
    }

    private function requireManager() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $this->sendResponse(401, ['success' => false, 'message' => 'Chưa xác thực. Vui lòng đăng nhập']);
        }

        $role = strtolower((string)($_SESSION['user_role'] ?? ''));
        if (!in_array($role, ['admin', 'nhan_vien'], true)) {
            $this->sendResponse(403, ['success' => false, 'message' => 'Bạn không có quyền truy cập thống kê']);
        }
    }

    private function normalizeFilters($input) {
        $tu_ngay = trim((string)($input['tu_ngay'] ?? $input['txtTuNgay'] ?? ''));
        $den_ngay = trim((string)($input['den_ngay'] ?? $input['txtDenNgay'] ?? ''));
        $ma_don_hang = trim((string)($input['ma_don_hang'] ?? $input['txtMaDonHang'] ?? ''));
        $ten_khach_hang = trim((string)($input['ten_khach_hang'] ?? $input['txtTenKhachHang'] ?? ''));
        $trang_thai = trim((string)($input['trang_thai_don_hang'] ?? $input['status'] ?? ''));

        if ($trang_thai === 'all' || $trang_thai === 'tat_ca') {
            $trang_thai = '';
        }

        $allowedStatus = ['cho_duyet', 'da_duyet', 'dang_giao', 'hoan_thanh', 'da_huy'];
        if ($trang_thai !== '' && !in_array($trang_thai, $allowedStatus, true)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Trạng thái lọc không hợp lệ']);
        }

        if ($tu_ngay !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tu_ngay)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Định dạng từ ngày không hợp lệ']);
        }

        if ($den_ngay !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $den_ngay)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Định dạng đến ngày không hợp lệ']);
        }

        if ($tu_ngay !== '' && $den_ngay !== '' && strtotime($tu_ngay) > strtotime($den_ngay)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'tu_ngay khong duoc lon hon den_ngay']);
        }

        return [
            'tu_ngay' => $tu_ngay,
            'den_ngay' => $den_ngay,
            'ma_don_hang' => $ma_don_hang,
            'ten_khach_hang' => $ten_khach_hang,
            'trang_thai_don_hang' => $trang_thai
        ];
    }

    private function rowsFromResult($result) {
        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    private function buildSummaryPayload() {
        $summaryRows = $this->rowsFromResult($this->dh->DonHang_ThongKeTongQuan());
        $tongquan = [
            'cho_duyet' => 0,
            'da_duyet' => 0,
            'dang_giao' => 0,
            'hoan_thanh' => 0,
            'da_huy' => 0,
            'tong_don' => 0
        ];

        foreach ($summaryRows as $row) {
            $status = trim((string)($row['trang_thai_don_hang'] ?? ''));
            $so_luong = (int)($row['so_luong'] ?? 0);
            if (array_key_exists($status, $tongquan)) {
                $tongquan[$status] = $so_luong;
            }
        }

        $tongquan['tong_don'] = $tongquan['cho_duyet'] + $tongquan['da_duyet'] + $tongquan['dang_giao'] + $tongquan['hoan_thanh'];
        return $tongquan;
    }

    private function buildPaymentPayload() {
        $rows = $this->rowsFromResult($this->dh->DonHang_ThongKePhuongThuc());
        $payment = [
            'cod' => ['so_don' => 0, 'doanh_thu' => 0],
            'vnpay' => ['so_don' => 0, 'doanh_thu' => 0],
            'momo' => ['so_don' => 0, 'doanh_thu' => 0],
            'banking' => ['so_don' => 0, 'doanh_thu' => 0],
            'tong_so_don' => 0
        ];

        foreach ($rows as $row) {
            $method = strtolower(trim((string)($row['phuong_thuc'] ?? '')));
            if ($method === 'vnpay' || $method === 'cod' || $method === 'momo' || $method === 'banking') {
                $payment[$method]['so_don'] = (int)($row['so_don'] ?? 0);
                $payment[$method]['doanh_thu'] = (float)($row['tong_tien'] ?? 0);
            }
        }

        $payment['tong_so_don'] = $payment['cod']['so_don'] + $payment['vnpay']['so_don'] + $payment['momo']['so_don'] + $payment['banking']['so_don'];
        return $payment;
    }

    private function getTopProducts($limit = 10) {
        $limit = max(1, min(50, (int)$limit));
        return $this->rowsFromResult($this->dh->DonHang_TopSanPham($limit));
    }

    private function getFilteredOrders($filters) {
        $result = $this->dh->DonHang_ThongKeChiTietFiltered(
            $filters['tu_ngay'],
            $filters['den_ngay'],
            $filters['ma_don_hang'],
            $filters['ten_khach_hang'],
            $filters['trang_thai_don_hang']
        );

        return $this->rowsFromResult($result);
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $this->requireManager();
        $filters = $this->normalizeFilters($_GET);
        $orders = $this->getFilteredOrders($filters);

        $format = strtolower(trim((string)($_GET['format'] ?? '')));
        if ($format === 'xlsx') {
            $this->exportXlsx($orders, $filters);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy danh sách thống kê đơn hàng thành công',
            'total' => count($orders),
            'filters' => $filters,
            'data' => $orders
        ]);
    }

    public function dashboard() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $this->requireManager();
        $filters = $this->normalizeFilters($_GET);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy dữ liệu bảng thống kê thành công',
            'data' => [
                'tongquan' => $this->buildSummaryPayload(),
                'thongkephuongthuc' => $this->buildPaymentPayload(),
                'top_sanpham' => $this->getTopProducts((int)($_GET['top_limit'] ?? 10)),
                'danhsachdonhang' => $this->getFilteredOrders($filters),
                'filters' => $filters,
                'ngayhientai' => date('Y-m-d')
            ]
        ]);
    }

    public function summary() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $this->requireManager();
        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy thống kê tổng quan thành công',
            'data' => $this->buildSummaryPayload()
        ]);
    }

    public function payment_methods() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $this->requireManager();
        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy thống kê phương thức thanh toán thành công',
            'data' => $this->buildPaymentPayload()
        ]);
    }

    public function top_products() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $this->requireManager();
        $limit = (int)($_GET['limit'] ?? 10);
        $data = $this->getTopProducts($limit);
        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy danh sách sản phẩm nổi bật thành công',
            'total' => count($data),
            'data' => $data
        ]);
    }

    public function export() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $this->requireManager();
        $filters = $this->normalizeFilters($_GET);
        $orders = $this->getFilteredOrders($filters);
        $this->exportXlsx($orders, $filters);
    }

    private function exportXlsx($orders, $filters) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet()->setTitle('ThongKeDoanhThu');

        $sheet->setCellValue('A1', 'Mã đơn hàng');
        $sheet->setCellValue('B1', 'Ngày tạo');
        $sheet->setCellValue('C1', 'Tổng tiền hàng');
        $sheet->setCellValue('D1', 'Trạng thái đơn hàng');
        $sheet->setCellValue('E1', 'Phương thức');
        $sheet->setCellValue('F1', 'Trạng thái thanh toán');
        $sheet->setCellValue('G1', 'Tên khách hàng');
        $sheet->setCellValue('H1', 'Số điện thoại');
        $sheet->setCellValue('I1', 'Địa chỉ');
        $sheet->setCellValue('J1', 'Khuyến mãi');
        $sheet->setCellValue('K1', 'Thanh toán');

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        $rowCount = 2;
        foreach ($orders as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_don_hang'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['ngay_tao'] ?? '');
            $sheet->setCellValue('C' . $rowCount, (float)($row['tong_tien_hang'] ?? 0));
            $sheet->setCellValue('D' . $rowCount, $row['trang_thai_don_hang'] ?? '');
            $sheet->setCellValue('E' . $rowCount, $row['phuong_thuc'] ?? '');
            $sheet->setCellValue('F' . $rowCount, $row['trang_thai_thanh_toan'] ?? '');
            $sheet->setCellValue('G' . $rowCount, $row['ten_khach_hang'] ?? '');
            $sheet->setCellValue('H' . $rowCount, $row['so_dien_thoai'] ?? '');
            $sheet->setCellValue('I' . $rowCount, $row['dia_chi'] ?? '');
            $sheet->setCellValue('J' . $rowCount, (float)($row['tien_khuyen_mai'] ?? 0));
            $sheet->setCellValue('K' . $rowCount, (float)($row['thanh_toan'] ?? 0));
            $rowCount++;
        }

        $suffix = [];
        if ($filters['tu_ngay'] !== '') $suffix[] = 'from_' . $filters['tu_ngay'];
        if ($filters['den_ngay'] !== '') $suffix[] = 'to_' . $filters['den_ngay'];
        if ($filters['ma_don_hang'] !== '') $suffix[] = 'order_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $filters['ma_don_hang']);
        if ($filters['ten_khach_hang'] !== '') $suffix[] = 'customer';
        if ($filters['trang_thai_don_hang'] !== '') $suffix[] = 'status_' . $filters['trang_thai_don_hang'];

        $fileName = 'ThongKeDoanhThu_' . ($suffix ? implode('_', $suffix) . '_' : '') . date('Ymd_His') . '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }
}
