<?php
class Donhang extends api_controller {
    private $dh;
    private $user;
    private $dc;
    private $km;
    private $ctdh;

    public function __construct() {
        parent::__construct();
        $this->dh = $this->model('DonHang_m');
        $this->user = $this->model('Users_m');
        $this->dc = $this->model('DiaChiGiaoHang_m');
        $this->km = $this->model('KhuyenMai_m');
        $this->ctdh = $this->model('ChiTietDonHang_m');
    }

    private function parseInputData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }
        return $this->getJsonInput();
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_don_hang = isset($_GET['ma_don_hang']) ? trim($_GET['ma_don_hang']) : '';
        $full_name = isset($_GET['full_name']) ? trim($_GET['full_name']) : '';

        $isSearch = ($ma_don_hang !== '' || $full_name !== '');
        $result = $isSearch
            ? $this->dh->DonHang_find($ma_don_hang, $full_name)
            : $this->dh->DonHang_getAll();

        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy danh sách đơn hàng']);
        }

        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }

        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($orders);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => $isSearch ? 'Tìm kiếm đơn hàng thành công' : 'Lấy danh sách đơn hàng thành công',
            'total' => count($orders),
            'filters' => [
                'ma_don_hang' => $ma_don_hang,
                'full_name' => $full_name
            ],
            'data' => $orders
        ]);
    }

    private function exportXlsx($orders) {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDonHang');

        $sheet->setCellValue('A1', 'Mã đơn hàng');
        $sheet->setCellValue('B1', 'Tên khách hàng');
        $sheet->setCellValue('C1', 'Tổng tiền hàng');
        $sheet->setCellValue('D1', 'Khuyến mãi');
        $sheet->setCellValue('E1', 'Thanh toán');
        $sheet->setCellValue('F1', 'Trạng thái đơn hàng');
        $sheet->setCellValue('G1', 'Ngày tạo');

        $rowCount = 2;
        foreach ($orders as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_don_hang'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['full_name'] ?? '');
            $sheet->setCellValue('C' . $rowCount, $row['tong_tien_hang'] ?? 0);
            $sheet->setCellValue('D' . $rowCount, $row['ten_khuyen_mai'] ?? '');
            $sheet->setCellValue('E' . $rowCount, $row['thanh_toan'] ?? '');
            $sheet->setCellValue('F' . $rowCount, $row['trang_thai_don_hang'] ?? '');
            $sheet->setCellValue('G' . $rowCount, $row['ngay_tao'] ?? '');
            $rowCount++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DanhSachDonHang.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã đơn hàng']);
        }

        $orderResult = $this->dh->DonHang_getById($id);
        if (!$orderResult || mysqli_num_rows($orderResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy đơn hàng có mã: ' . $id]);
        }

        $order = mysqli_fetch_assoc($orderResult);
        $detailResult = $this->ctdh->ChiTietDonHang_getByOrderId($id);

        $details = [];
        if ($detailResult) {
            while ($row = mysqli_fetch_assoc($detailResult)) {
                $row['ten_mon'] = $row['ten_san_pham'] ?? $row['ten_bien_the'] ?? 'Sản phẩm không xác định';
                $row['gia_tai_thoi_diem_dat'] = $row['gia_luc_mua'] ?? 0;
                $details[] = $row;
            }
        }

        $userInfo = null;
        if (!empty($order['ma_user'])) {
            $userResult = $this->user->Users_getById($order['ma_user']);
            if ($userResult && mysqli_num_rows($userResult) > 0) {
                $userInfo = mysqli_fetch_assoc($userResult);
            }
        }

        $addressInfo = null;
        if (!empty($order['ma_dia_chi'])) {
            $addressResult = $this->dc->DiaChiGiaoHang_getById($order['ma_dia_chi']);
            if ($addressResult && mysqli_num_rows($addressResult) > 0) {
                $addressInfo = mysqli_fetch_assoc($addressResult);
            }
        }

        $promotionInfo = null;
        if (!empty($order['ma_khuyen_mai'])) {
            $promotionResult = $this->km->KhuyenMai_getById($order['ma_khuyen_mai']);
            if ($promotionResult && mysqli_num_rows($promotionResult) > 0) {
                $promotionInfo = mysqli_fetch_assoc($promotionResult);
            }
        }

        $this->sendResponse(200, [
            'success' => true,
            'data' => [
                'order_details' => $details,
                'order_info' => $order,
                'user_info' => $userInfo,
                'address_info' => $addressInfo,
                'promotion_info' => $promotionInfo,
                'order_notes' => $order['ghi_chu'] ?? ''
            ]
        ]);
    }

    public function search($ma_don_hang = null, $full_name = null) {
        if ($ma_don_hang !== null && trim($ma_don_hang) !== '') {
            $_GET['ma_don_hang'] = trim($ma_don_hang);
        }
        if ($full_name !== null && trim($full_name) !== '') {
            $_GET['full_name'] = trim($full_name);
        }

        $this->get_all();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->parseInputData();

        $ma_don_hang = trim($data['ma_don_hang'] ?? $data['txtMaDonHang'] ?? '');
        $ma_user = trim($data['ma_user'] ?? $data['ddlUser'] ?? '');
        $ma_dia_chi = trim($data['ma_dia_chi'] ?? $data['ddlDiaChi'] ?? '');
        $ma_khuyen_mai = trim($data['ma_khuyen_mai'] ?? $data['ddlKhuyenMai'] ?? '');
        $tong_tien_hang = trim($data['tong_tien_hang'] ?? $data['txtTongTien'] ?? '0');
        $thanh_toan = trim($data['thanh_toan'] ?? 'chua_thanh_toan');
        $trang_thai_don_hang = trim($data['trang_thai_don_hang'] ?? $data['ddlTrangThai'] ?? 'cho_duyet');

        if ($ma_don_hang === '' || $ma_user === '' || $ma_dia_chi === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_don_hang, ma_user, ma_dia_chi']);
        }

        if ($this->dh->checktrungMaDH($ma_don_hang)) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã đơn hàng đã tồn tại']);
        }

        $inserted = $this->dh->donhang_ins(
            $ma_don_hang,
            $ma_user,
            $ma_dia_chi,
            $ma_khuyen_mai,
            $tong_tien_hang,
            $thanh_toan,
            $trang_thai_don_hang
        );

        if (!$inserted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể thêm đơn hàng',
                'error' => mysqli_error($this->dh->con)
            ]);
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Thêm đơn hàng thành công',
            'data' => [
                'ma_don_hang' => $ma_don_hang,
                'ma_user' => $ma_user,
                'ma_dia_chi' => $ma_dia_chi,
                'ma_khuyen_mai' => $ma_khuyen_mai,
                'tong_tien_hang' => $tong_tien_hang,
                'thanh_toan' => $thanh_toan,
                'trang_thai_don_hang' => $trang_thai_don_hang
            ]
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT/PATCH/POST']);
        }

        $data = $this->parseInputData();
        $ma_don_hang = trim($id ?? $data['ma_don_hang'] ?? $data['txtMaDonHang'] ?? '');

        if ($ma_don_hang === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã đơn hàng']);
        }

        $currentResult = $this->dh->DonHang_getById($ma_don_hang);
        if (!$currentResult || mysqli_num_rows($currentResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy đơn hàng có mã: ' . $ma_don_hang]);
        }

        $current = mysqli_fetch_assoc($currentResult);

        $ma_user = trim($data['ma_user'] ?? $data['ddlUser'] ?? ($current['ma_user'] ?? ''));
        $ma_dia_chi = trim($data['ma_dia_chi'] ?? $data['ddlDiaChi'] ?? ($current['ma_dia_chi'] ?? ''));
        $ma_khuyen_mai = trim($data['ma_khuyen_mai'] ?? $data['ddlKhuyenMai'] ?? ($current['ma_khuyen_mai'] ?? ''));
        $tong_tien_hang = trim($data['tong_tien_hang'] ?? $data['txtTongTien'] ?? ($current['tong_tien_hang'] ?? '0'));
        $trang_thai_don_hang = trim($data['trang_thai_don_hang'] ?? $data['ddlTrangThai'] ?? ($current['trang_thai_don_hang'] ?? 'cho_duyet'));

        $updated = $this->dh->DonHang_update(
            $ma_don_hang,
            $ma_user,
            $ma_dia_chi,
            $ma_khuyen_mai,
            $tong_tien_hang,
            $trang_thai_don_hang
        );

        if (!$updated) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật đơn hàng',
                'error' => mysqli_error($this->dh->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật đơn hàng thành công'
        ]);
    }

    public function update_status($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT/PATCH/POST']);
        }

        $data = $this->parseInputData();
        $ma_don_hang = trim($id ?? $data['ma_don_hang'] ?? $data['orderId'] ?? '');
        $status = trim($data['trang_thai_don_hang'] ?? $data['status'] ?? '');

        if ($ma_don_hang === '' || $status === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã đơn hàng hoặc trạng thái']);
        }

        $allowed = ['cho_duyet', 'dang_giao', 'hoan_thanh', 'da_huy'];
        if (!in_array($status, $allowed, true)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Trạng thái đơn hàng không hợp lệ']);
        }

        $orderResult = $this->dh->DonHang_getById($ma_don_hang);
        if (!$orderResult || mysqli_num_rows($orderResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy đơn hàng có mã: ' . $ma_don_hang]);
        }

        $order = mysqli_fetch_assoc($orderResult);
        $currentStatus = $order['trang_thai_don_hang'] ?? '';

        if ($status === 'da_huy' && $currentStatus !== 'da_huy') {
            $updated = $this->dh->DonHang_cancelWithRestock($ma_don_hang);
        } else {
            $updated = $this->dh->DonHang_updateStatus($ma_don_hang, $status);
        }

        if (!$updated) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái đơn hàng',
                'error' => mysqli_error($this->dh->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã đơn hàng']);
        }

        if (!$this->dh->checktrungMaDH($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy đơn hàng có mã: ' . $id]);
        }

        $deleted = $this->dh->DonHang_delete($id);
        if (!$deleted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể xóa đơn hàng',
                'error' => mysqli_error($this->dh->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Xóa đơn hàng thành công']);
    }
}
