<?php
class Khuyenmai extends api_controller {
    private $khuyenmai_model;

    public function __construct() {
        parent::__construct();
        $this->khuyenmai_model = $this->model('KhuyenMai_m');
    }

    private function getPromotionStatus($ngay_ket_thuc) {
        if (empty($ngay_ket_thuc)) {
            return 'het';
        }

        $currentDate = date('Y-m-d');
        return (strtotime($ngay_ket_thuc) < strtotime($currentDate)) ? 'het' : 'con';
    }

    private function normalizeDateTime($value) {
        if ($value === null || trim((string)$value) === '') {
            return '';
        }

        $timestamp = strtotime((string)$value);
        if ($timestamp === false) {
            return '';
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * Endpoint: GET /Api/Khuyenmai
     * Hỗ trợ filter: ma_khuyen_mai, ten_khuyen_mai
     * Hỗ trợ export: ?format=xlsx
     */
    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_khuyen_mai = isset($_GET['ma_khuyen_mai']) ? trim($_GET['ma_khuyen_mai']) : '';
        $ten_khuyen_mai = isset($_GET['ten_khuyen_mai']) ? trim($_GET['ten_khuyen_mai']) : '';

        $result = $this->khuyenmai_model->KhuyenMai_find($ma_khuyen_mai, $ten_khuyen_mai);
        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy danh sách khuyến mãi']);
        }

        $promotions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $promotions[] = $row;
        }

        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($promotions);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy danh sách khuyến mãi thành công',
            'total' => count($promotions),
            'filters' => [
                'ma_khuyen_mai' => $ma_khuyen_mai,
                'ten_khuyen_mai' => $ten_khuyen_mai
            ],
            'data' => $promotions
        ]);
    }

    private function exportXlsx($promotions) {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachKhuyenMai');

        $sheet->setCellValue('A1', 'Mã khuyến mãi');
        $sheet->setCellValue('B1', 'Tên khuyến mãi');
        $sheet->setCellValue('C1', 'Tiền khuyến mãi');
        $sheet->setCellValue('D1', 'Ngày bắt đầu');
        $sheet->setCellValue('E1', 'Ngày kết thúc');
        $sheet->setCellValue('F1', 'Trạng thái');

        $rowCount = 2;
        foreach ($promotions as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_khuyen_mai'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['ten_khuyen_mai'] ?? '');
            $sheet->setCellValue('C' . $rowCount, $row['tien_khuyen_mai'] ?? '');
            $sheet->setCellValue('D' . $rowCount, $row['ngay_bat_dau'] ?? '');
            $sheet->setCellValue('E' . $rowCount, $row['ngay_ket_thuc'] ?? '');
            $sheet->setCellValue('F' . $rowCount, $row['trang_thai_khuyen_mai'] ?? '');
            $rowCount++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DanhSachKhuyenMai.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    /**
     * Endpoint: GET /Api/Khuyenmai/KM01
     */
    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã khuyến mãi']);
        }

        $result = $this->khuyenmai_model->KhuyenMai_getById($id);
        if ($result && mysqli_num_rows($result) > 0) {
            $promotion = mysqli_fetch_assoc($result);
            $this->sendResponse(200, ['success' => true, 'data' => $promotion]);
        }

        $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy khuyến mãi có mã: ' . $id]);
    }

    /**
     * Endpoint: GET /Api/Khuyenmai/search?ma_khuyen_mai=KM01&ten_khuyen_mai=tet
     * Tương thích route cũ.
     */
    public function search($ma_khuyen_mai = null, $ten_khuyen_mai = null) {
        if ($ma_khuyen_mai !== null && trim($ma_khuyen_mai) !== '') {
            $_GET['ma_khuyen_mai'] = trim($ma_khuyen_mai);
        }

        if ($ten_khuyen_mai !== null && trim($ten_khuyen_mai) !== '') {
            $_GET['ten_khuyen_mai'] = trim($ten_khuyen_mai);
        }

        $this->get_all();
    }

    /**
     * Endpoint: POST /Api/Khuyenmai
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->getJsonInput();

        if (empty($data['ma_khuyen_mai']) || empty($data['ten_khuyen_mai'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_khuyen_mai và ten_khuyen_mai']);
        }

        $ngay_bat_dau = $this->normalizeDateTime($data['ngay_bat_dau'] ?? '');
        $ngay_ket_thuc = $this->normalizeDateTime($data['ngay_ket_thuc'] ?? '');

        if ($ngay_bat_dau === '' || $ngay_ket_thuc === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ngay_bat_dau và ngay_ket_thuc hợp lệ']);
        }

        if ($this->khuyenmai_model->checktrungMaKM($data['ma_khuyen_mai'])) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã khuyến mãi đã tồn tại']);
        }

        $trang_thai_khuyen_mai = $this->getPromotionStatus($ngay_ket_thuc);

        $insertResult = $this->khuyenmai_model->khuyenmai_ins(
            $data['ma_khuyen_mai'],
            $data['ten_khuyen_mai'],
            $data['tien_khuyen_mai'] ?? 0,
            $ngay_bat_dau,
            $ngay_ket_thuc,
            $trang_thai_khuyen_mai
        );

        if (!$insertResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể thêm khuyến mãi',
                'error' => mysqli_error($this->khuyenmai_model->con)
            ]);
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Thêm khuyến mãi thành công',
            'data' => [
                'ma_khuyen_mai' => $data['ma_khuyen_mai'],
                'ten_khuyen_mai' => $data['ten_khuyen_mai'],
                'tien_khuyen_mai' => $data['tien_khuyen_mai'] ?? 0,
                'ngay_bat_dau' => $ngay_bat_dau,
                'ngay_ket_thuc' => $ngay_ket_thuc,
                'trang_thai_khuyen_mai' => $trang_thai_khuyen_mai
            ]
        ]);
    }

    /**
     * Endpoint: PUT/PATCH /Api/Khuyenmai/KM01
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT or PATCH']);
        }

        $data = $this->getJsonInput();
        if ($id !== null && trim($id) !== '') {
            $data['ma_khuyen_mai'] = trim($id);
        }

        if (empty($data['ma_khuyen_mai']) || empty($data['ten_khuyen_mai'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_khuyen_mai và ten_khuyen_mai']);
        }

        $ngay_bat_dau = $this->normalizeDateTime($data['ngay_bat_dau'] ?? '');
        $ngay_ket_thuc = $this->normalizeDateTime($data['ngay_ket_thuc'] ?? '');

        if ($ngay_bat_dau === '' || $ngay_ket_thuc === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ngay_bat_dau và ngay_ket_thuc hợp lệ']);
        }

        if (!$this->khuyenmai_model->checktrungMaKM($data['ma_khuyen_mai'])) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy khuyến mãi có mã: ' . $data['ma_khuyen_mai']]);
        }

        $trang_thai_khuyen_mai = $this->getPromotionStatus($ngay_ket_thuc);

        $updateResult = $this->khuyenmai_model->KhuyenMai_update(
            $data['ma_khuyen_mai'],
            $data['ten_khuyen_mai'],
            $data['tien_khuyen_mai'] ?? 0,
            $ngay_bat_dau,
            $ngay_ket_thuc,
            $trang_thai_khuyen_mai
        );

        if (!$updateResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật khuyến mãi',
                'error' => mysqli_error($this->khuyenmai_model->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật khuyến mãi thành công',
            'data' => [
                'ma_khuyen_mai' => $data['ma_khuyen_mai'],
                'ten_khuyen_mai' => $data['ten_khuyen_mai'],
                'tien_khuyen_mai' => $data['tien_khuyen_mai'] ?? 0,
                'ngay_bat_dau' => $ngay_bat_dau,
                'ngay_ket_thuc' => $ngay_ket_thuc,
                'trang_thai_khuyen_mai' => $trang_thai_khuyen_mai
            ]
        ]);
    }

    /**
     * Endpoint: DELETE /Api/Khuyenmai/KM01
     */
    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã khuyến mãi']);
        }

        if (!$this->khuyenmai_model->checktrungMaKM($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy khuyến mãi có mã: ' . $id]);
        }

        $deleteResult = $this->khuyenmai_model->KhuyenMai_delete($id);
        if (!$deleteResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể xóa khuyến mãi',
                'error' => mysqli_error($this->khuyenmai_model->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Xóa khuyến mãi thành công']);
    }

    /**
     * Endpoint: POST /Api/Khuyenmai/import
     * Cột Excel: A ma_khuyen_mai, B ten_khuyen_mai, C tien_khuyen_mai, D ngay_bat_dau, E ngay_ket_thuc
     */
    public function import() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $uploadKey = null;
        if (isset($_FILES['file'])) {
            $uploadKey = 'file';
        } elseif (isset($_FILES['txtfile'])) {
            $uploadKey = 'txtfile';
        }

        if ($uploadKey === null) {
            $this->sendResponse(400, [
                'success' => false,
                'message' => 'Thiếu file upload. Vui lòng gửi multipart/form-data với key file hoặc txtfile'
            ]);
        }

        if ($_FILES[$uploadKey]['error'] !== 0 || empty($_FILES[$uploadKey]['tmp_name'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Upload file lỗi']);
        }

        $file = $_FILES[$uploadKey]['tmp_name'];

        try {
            $objReader = PHPExcel_IOFactory::createReaderForFile($file);
            $objExcel = $objReader->load($file);
        } catch (Exception $e) {
            $this->sendResponse(400, ['success' => false, 'message' => 'File Excel không hợp lệ']);
        }

        $sheet = $objExcel->getSheet(0);
        $sheetData = $sheet->toArray(null, true, true, true);

        $created = 0;
        $skipped_empty = 0;
        $duplicated_codes = [];
        $failed_rows = [];

        for ($i = 2; $i <= count($sheetData); $i++) {
            $ma_khuyen_mai = isset($sheetData[$i]['A']) ? trim($sheetData[$i]['A']) : '';
            $ten_khuyen_mai = isset($sheetData[$i]['B']) ? trim($sheetData[$i]['B']) : '';
            $tien_khuyen_mai = isset($sheetData[$i]['C']) ? trim($sheetData[$i]['C']) : '0';
            $ngay_bat_dau = $this->normalizeDateTime($sheetData[$i]['D'] ?? '');
            $ngay_ket_thuc = $this->normalizeDateTime($sheetData[$i]['E'] ?? '');

            if ($ma_khuyen_mai === '') {
                $skipped_empty++;
                continue;
            }

            if ($ten_khuyen_mai === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_khuyen_mai' => $ma_khuyen_mai,
                    'reason' => 'Thiếu tên khuyến mãi (cột B)'
                ];
                continue;
            }

            if ($ngay_bat_dau === '' || $ngay_ket_thuc === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_khuyen_mai' => $ma_khuyen_mai,
                    'reason' => 'Ngày bắt đầu/kết thúc không hợp lệ (cột D/E)'
                ];
                continue;
            }

            if ($this->khuyenmai_model->checktrungMaKM($ma_khuyen_mai)) {
                $duplicated_codes[] = $ma_khuyen_mai;
                continue;
            }

            $trang_thai_khuyen_mai = $this->getPromotionStatus($ngay_ket_thuc);

            $inserted = $this->khuyenmai_model->khuyenmai_ins(
                $ma_khuyen_mai,
                $ten_khuyen_mai,
                $tien_khuyen_mai,
                $ngay_bat_dau,
                $ngay_ket_thuc,
                $trang_thai_khuyen_mai
            );

            if ($inserted) {
                $created++;
            } else {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_khuyen_mai' => $ma_khuyen_mai,
                    'reason' => mysqli_error($this->khuyenmai_model->con)
                ];
            }
        }

        $response = [
            'success' => true,
            'message' => 'Import khuyến mãi hoàn tất',
            'created' => $created,
            'skipped_empty_code' => $skipped_empty,
            'duplicated_count' => count($duplicated_codes),
            'duplicated_codes' => $duplicated_codes,
            'failed_count' => count($failed_rows),
            'failed_rows' => $failed_rows
        ];

        if ($created === 0 && (count($duplicated_codes) > 0 || count($failed_rows) > 0)) {
            $response['success'] = false;
            $response['message'] = 'Import không tạo được bản ghi nào';
            $this->sendResponse(422, $response);
        }

        if (count($duplicated_codes) > 0 || count($failed_rows) > 0) {
            $response['message'] = 'Import hoàn tất (có một số dòng bị bỏ qua/lỗi)';
        }

        $this->sendResponse(200, $response);
    }
}
?>