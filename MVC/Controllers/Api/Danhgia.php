<?php
class Danhgia extends api_controller {
    private $danhgia_model;

    public function __construct() {
        parent::__construct();
        $this->danhgia_model = $this->model('DanhGia_m');
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

        $ma_danh_gia = isset($_GET['ma_danh_gia']) ? trim($_GET['ma_danh_gia']) : '';
        $ten_khach_hang = isset($_GET['ten_khach_hang']) ? trim($_GET['ten_khach_hang']) : '';
        $ten_san_pham = isset($_GET['ten_san_pham']) ? trim($_GET['ten_san_pham']) : '';

        $isSearch = ($ma_danh_gia !== '' || $ten_khach_hang !== '' || $ten_san_pham !== '');
        $result = $isSearch
            ? $this->danhgia_model->DanhGia_find($ma_danh_gia, $ten_khach_hang, $ten_san_pham)
            : $this->danhgia_model->DanhGia_getAll();

        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy danh sách đánh giá']);
        }

        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }

        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($reviews);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => $isSearch ? 'Tìm kiếm đánh giá thành công' : 'Lấy danh sách đánh giá thành công',
            'total' => count($reviews),
            'filters' => [
                'ma_danh_gia' => $ma_danh_gia,
                'ten_khach_hang' => $ten_khach_hang,
                'ten_san_pham' => $ten_san_pham
            ],
            'data' => $reviews
        ]);
    }

    private function exportXlsx($reviews) {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDanhGia');

        $sheet->setCellValue('A1', 'Mã đánh giá');
        $sheet->setCellValue('B1', 'Tên khách hàng');
        $sheet->setCellValue('C1', 'Tên sản phẩm');
        $sheet->setCellValue('D1', 'Số sao');
        $sheet->setCellValue('E1', 'Nội dung');
        $sheet->setCellValue('F1', 'Phản hồi');
        $sheet->setCellValue('G1', 'Ngày đánh giá');

        $rowCount = 2;
        foreach ($reviews as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_danh_gia'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['full_name'] ?? '');
            $sheet->setCellValue('C' . $rowCount, $row['ten_san_pham'] ?? '');
            $sheet->setCellValue('D' . $rowCount, $row['so_sao'] ?? '');
            $sheet->setCellValue('E' . $rowCount, $row['noi_dung'] ?? '');
            $sheet->setCellValue('F' . $rowCount, $row['phan_hoi'] ?? '');
            $sheet->setCellValue('G' . $rowCount, $row['ngay_danh_gia'] ?? '');
            $rowCount++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DanhSachDanhGia.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã đánh giá']);
        }

        $result = $this->danhgia_model->DanhGia_getById($id);
        if ($result && mysqli_num_rows($result) > 0) {
            $review = mysqli_fetch_assoc($result);
            $this->sendResponse(200, ['success' => true, 'data' => $review]);
        }

        $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy đánh giá có mã: ' . $id]);
    }

    public function search($ma_danh_gia = null, $ten_khach_hang = null, $ten_san_pham = null) {
        if ($ma_danh_gia !== null && trim($ma_danh_gia) !== '') {
            $_GET['ma_danh_gia'] = trim($ma_danh_gia);
        }
        if ($ten_khach_hang !== null && trim($ten_khach_hang) !== '') {
            $_GET['ten_khach_hang'] = trim($ten_khach_hang);
        }
        if ($ten_san_pham !== null && trim($ten_san_pham) !== '') {
            $_GET['ten_san_pham'] = trim($ten_san_pham);
        }

        $this->get_all();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->parseInputData();

        $ma_danh_gia = trim($data['ma_danh_gia'] ?? $data['txtMadanhgia'] ?? '');
        $ma_user = trim($data['ma_user'] ?? $data['txtMauser'] ?? '');
        $ma_san_pham = trim($data['ma_san_pham'] ?? $data['txtMasanpham'] ?? '');
        $so_sao = trim($data['so_sao'] ?? $data['txtSosao'] ?? '');
        $noi_dung = trim($data['noi_dung'] ?? $data['txtNoidung'] ?? '');
        $phan_hoi = trim($data['phan_hoi'] ?? $data['txtPhanhoi'] ?? '');

        if ($ma_danh_gia === '' || $ma_user === '' || $ma_san_pham === '' || $so_sao === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_danh_gia, ma_user, ma_san_pham và so_sao']);
        }

        if ($this->danhgia_model->checktrungMaDG($ma_danh_gia)) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã đánh giá đã tồn tại']);
        }

        $inserted = $this->danhgia_model->danhgia_ins($ma_danh_gia, $ma_user, $ma_san_pham, $so_sao, $noi_dung, $phan_hoi);
        if (!$inserted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể thêm đánh giá',
                'error' => mysqli_error($this->danhgia_model->con)
            ]);
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Thêm đánh giá thành công',
            'data' => [
                'ma_danh_gia' => $ma_danh_gia,
                'ma_user' => $ma_user,
                'ma_san_pham' => $ma_san_pham,
                'so_sao' => $so_sao,
                'noi_dung' => $noi_dung,
                'phan_hoi' => $phan_hoi
            ]
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT/PATCH/POST']);
        }

        $data = $this->parseInputData();
        $ma_danh_gia = trim($id ?? $data['ma_danh_gia'] ?? $data['txtMadanhgia'] ?? '');

        if ($ma_danh_gia === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã đánh giá']);
        }

        $currentResult = $this->danhgia_model->DanhGia_getById($ma_danh_gia);
        if (!$currentResult || mysqli_num_rows($currentResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy đánh giá có mã: ' . $ma_danh_gia]);
        }

        $current = mysqli_fetch_assoc($currentResult);
        $so_sao = trim($data['so_sao'] ?? $data['txtSosao'] ?? ($current['so_sao'] ?? ''));
        $noi_dung = trim($data['noi_dung'] ?? $data['txtNoidung'] ?? ($current['noi_dung'] ?? ''));
        $phan_hoi = trim($data['phan_hoi'] ?? $data['txtPhanhoi'] ?? ($current['phan_hoi'] ?? ''));

        if ($so_sao === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp số sao']);
        }

        $updated = $this->danhgia_model->DanhGia_update($ma_danh_gia, $so_sao, $noi_dung, $phan_hoi);
        if (!$updated) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật đánh giá',
                'error' => mysqli_error($this->danhgia_model->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật đánh giá thành công',
            'data' => [
                'ma_danh_gia' => $ma_danh_gia,
                'so_sao' => $so_sao,
                'noi_dung' => $noi_dung,
                'phan_hoi' => $phan_hoi
            ]
        ]);
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã đánh giá']);
        }

        if (!$this->danhgia_model->checktrungMaDG($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy đánh giá có mã: ' . $id]);
        }

        $deleted = $this->danhgia_model->DanhGia_delete($id);
        if (!$deleted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể xóa đánh giá',
                'error' => mysqli_error($this->danhgia_model->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Xóa đánh giá thành công']);
    }
}
