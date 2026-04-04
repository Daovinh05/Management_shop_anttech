<?php
class Danhmuc extends api_controller {
    private $danhmuc_model;

    public function __construct() {
        parent::__construct();
        $this->danhmuc_model = $this->model('DanhMuc_m');
    }

    /**
     * Endpoint: GET /Api/Danhmuc
     * Hỗ trợ filter: ma_danh_muc, ten_danh_muc
     * Hỗ trợ export: ?format=xlsx
     */
    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_danh_muc = isset($_GET['ma_danh_muc']) ? trim($_GET['ma_danh_muc']) : '';
        $ten_danh_muc = isset($_GET['ten_danh_muc']) ? trim($_GET['ten_danh_muc']) : '';

        $result = $this->danhmuc_model->DanhMuc_find($ma_danh_muc, $ten_danh_muc);
        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy danh sách danh mục']);
        }

        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }

        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($categories);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy danh sách danh mục thành công',
            'total' => count($categories),
            'filters' => [
                'ma_danh_muc' => $ma_danh_muc,
                'ten_danh_muc' => $ten_danh_muc
            ],
            'data' => $categories
        ]);
    }

    private function exportXlsx($categories) {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDanhMuc');

        $sheet->setCellValue('A1', 'Mã danh mục');
        $sheet->setCellValue('B1', 'Tên danh mục');
        $sheet->setCellValue('C1', 'Ngày tạo');

        $rowCount = 2;
        foreach ($categories as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_danh_muc'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['ten_danh_muc'] ?? '');
            $sheet->setCellValue('C' . $rowCount, $row['ngay_tao'] ?? '');
            $rowCount++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DanhSachDanhMuc.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    /**
     * Endpoint: GET /Api/Danhmuc/DM01
     */
    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã danh mục']);
        }

        $result = $this->danhmuc_model->DanhMuc_getById($id);
        if ($result && mysqli_num_rows($result) > 0) {
            $category = mysqli_fetch_assoc($result);
            $this->sendResponse(200, ['success' => true, 'data' => $category]);
        }

        $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy danh mục có mã: ' . $id]);
    }

    /**
     * Endpoint: GET /Api/Danhmuc/search?ma_danh_muc=DM01&ten_danh_muc=dien-thoai
     * Tương thích route cũ.
     */
    public function search($ma_danh_muc = null, $ten_danh_muc = null) {
        if ($ma_danh_muc !== null && trim($ma_danh_muc) !== '') {
            $_GET['ma_danh_muc'] = trim($ma_danh_muc);
        }

        if ($ten_danh_muc !== null && trim($ten_danh_muc) !== '') {
            $_GET['ten_danh_muc'] = trim($ten_danh_muc);
        }

        $this->get_all();
    }

    /**
     * Endpoint: POST /Api/Danhmuc
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->getJsonInput();

        if (empty($data['ma_danh_muc']) || empty($data['ten_danh_muc'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_danh_muc và ten_danh_muc']);
        }

        if ($this->danhmuc_model->checktrungMaDM($data['ma_danh_muc'])) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã danh mục đã tồn tại']);
        }

        $insertResult = $this->danhmuc_model->danhmuc_ins($data['ma_danh_muc'], $data['ten_danh_muc']);
        if (!$insertResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể thêm danh mục',
                'error' => mysqli_error($this->danhmuc_model->con)
            ]);
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Thêm danh mục thành công',
            'data' => $data
        ]);
    }

    /**
     * Endpoint: PUT/PATCH /Api/Danhmuc/DM01
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT or PATCH']);
        }

        $data = $this->getJsonInput();
        if ($id !== null && trim($id) !== '') {
            $data['ma_danh_muc'] = trim($id);
        }

        if (empty($data['ma_danh_muc']) || empty($data['ten_danh_muc'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_danh_muc và ten_danh_muc']);
        }

        if (!$this->danhmuc_model->checktrungMaDM($data['ma_danh_muc'])) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy danh mục có mã: ' . $data['ma_danh_muc']]);
        }

        $updateResult = $this->danhmuc_model->DanhMuc_update($data['ma_danh_muc'], $data['ten_danh_muc']);
        if (!$updateResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật danh mục',
                'error' => mysqli_error($this->danhmuc_model->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật danh mục thành công',
            'data' => $data
        ]);
    }

    /**
     * Endpoint: DELETE /Api/Danhmuc/DM01
     */
    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã danh mục']);
        }

        if (!$this->danhmuc_model->checktrungMaDM($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy danh mục có mã: ' . $id]);
        }

        if ($this->danhmuc_model->DanhMuc_hasProducts($id)) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Không thể xóa vì đang có sản phẩm thuộc Danh mục "' . $id . '"'
            ]);
        }

        $deleteResult = $this->danhmuc_model->DanhMuc_delete($id);
        if (!$deleteResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể xóa danh mục',
                'error' => mysqli_error($this->danhmuc_model->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Xóa danh mục thành công']);
    }

    /**
     * Endpoint: POST /Api/Danhmuc/import
     * Cột Excel: A ma_danh_muc, B ten_danh_muc
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
            $ma_danh_muc = isset($sheetData[$i]['A']) ? trim($sheetData[$i]['A']) : '';
            $ten_danh_muc = isset($sheetData[$i]['B']) ? trim($sheetData[$i]['B']) : '';

            if ($ma_danh_muc === '') {
                $skipped_empty++;
                continue;
            }

            if ($ten_danh_muc === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_danh_muc' => $ma_danh_muc,
                    'reason' => 'Thiếu tên danh mục (cột B)'
                ];
                continue;
            }

            if ($this->danhmuc_model->checktrungMaDM($ma_danh_muc)) {
                $duplicated_codes[] = $ma_danh_muc;
                continue;
            }

            $inserted = $this->danhmuc_model->danhmuc_ins($ma_danh_muc, $ten_danh_muc);
            if ($inserted) {
                $created++;
            } else {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_danh_muc' => $ma_danh_muc,
                    'reason' => mysqli_error($this->danhmuc_model->con)
                ];
            }
        }

        $response = [
            'success' => true,
            'message' => 'Import danh mục hoàn tất',
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