<?php
class Thuonghieu extends api_controller {
    private $thuonghieu_model;

    public function __construct() {
        parent::__construct();
        $this->thuonghieu_model = $this->model('ThuongHieu_m');
    }

    /**
     * Endpoint: GET /Api/Thuonghieu
     * Hỗ trợ filter: ma_thuong_hieu, ten_thuong_hieu
     * Hỗ trợ export: ?format=xlsx
     */
    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_thuong_hieu = isset($_GET['ma_thuong_hieu']) ? trim($_GET['ma_thuong_hieu']) : '';
        $ten_thuong_hieu = isset($_GET['ten_thuong_hieu']) ? trim($_GET['ten_thuong_hieu']) : '';

        $result = $this->thuonghieu_model->ThuongHieu_find($ma_thuong_hieu, $ten_thuong_hieu);
        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy danh sách thương hiệu']);
        }

        $brands = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $brands[] = $row;
        }

        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($brands);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy danh sách thương hiệu thành công',
            'total' => count($brands),
            'filters' => [
                'ma_thuong_hieu' => $ma_thuong_hieu,
                'ten_thuong_hieu' => $ten_thuong_hieu
            ],
            'data' => $brands
        ]);
    }

    private function exportXlsx($brands) {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachThuongHieu');

        $sheet->setCellValue('A1', 'Mã thương hiệu');
        $sheet->setCellValue('B1', 'Tên thương hiệu');
        $sheet->setCellValue('C1', 'Ngày tạo');

        $rowCount = 2;
        foreach ($brands as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_thuong_hieu'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['ten_thuong_hieu'] ?? '');
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
        header('Content-Disposition: attachment; filename="DanhSachThuongHieu.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    /**
     * Endpoint: GET /Api/Thuonghieu/TH01
     */
    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã thương hiệu']);
        }

        $result = $this->thuonghieu_model->ThuongHieu_getById($id);
        if ($result && mysqli_num_rows($result) > 0) {
            $brand = mysqli_fetch_assoc($result);
            $this->sendResponse(200, ['success' => true, 'data' => $brand]);
        }

        $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy thương hiệu có mã: ' . $id]);
    }

    /**
     * Endpoint: GET /Api/Thuonghieu/search?ma_thuong_hieu=TH01&ten_thuong_hieu=apple
     * Tương thích route cũ.
     */
    public function search($ma_thuong_hieu = null, $ten_thuong_hieu = null) {
        if ($ma_thuong_hieu !== null && trim($ma_thuong_hieu) !== '') {
            $_GET['ma_thuong_hieu'] = trim($ma_thuong_hieu);
        }

        if ($ten_thuong_hieu !== null && trim($ten_thuong_hieu) !== '') {
            $_GET['ten_thuong_hieu'] = trim($ten_thuong_hieu);
        }

        $this->get_all();
    }

    /**
     * Endpoint: POST /Api/Thuonghieu
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->getJsonInput();

        if (empty($data['ma_thuong_hieu']) || empty($data['ten_thuong_hieu'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_thuong_hieu và ten_thuong_hieu']);
        }

        if ($this->thuonghieu_model->checktrungMaTH($data['ma_thuong_hieu'])) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã thương hiệu đã tồn tại']);
        }

        $insertResult = $this->thuonghieu_model->thuonghieu_ins($data['ma_thuong_hieu'], $data['ten_thuong_hieu']);
        if (!$insertResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể thêm thương hiệu',
                'error' => mysqli_error($this->thuonghieu_model->con)
            ]);
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Thêm thương hiệu thành công',
            'data' => $data
        ]);
    }

    /**
     * Endpoint: PUT/PATCH /Api/Thuonghieu/TH01
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT or PATCH']);
        }

        $data = $this->getJsonInput();
        if ($id !== null && trim($id) !== '') {
            $data['ma_thuong_hieu'] = trim($id);
        }

        if (empty($data['ma_thuong_hieu']) || empty($data['ten_thuong_hieu'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_thuong_hieu và ten_thuong_hieu']);
        }

        if (!$this->thuonghieu_model->checktrungMaTH($data['ma_thuong_hieu'])) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy thương hiệu có mã: ' . $data['ma_thuong_hieu']]);
        }

        $updateResult = $this->thuonghieu_model->ThuongHieu_update($data['ma_thuong_hieu'], $data['ten_thuong_hieu']);
        if (!$updateResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật thương hiệu',
                'error' => mysqli_error($this->thuonghieu_model->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật thương hiệu thành công',
            'data' => $data
        ]);
    }

    /**
     * Endpoint: DELETE /Api/Thuonghieu/TH01
     */
    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã thương hiệu']);
        }

        if (!$this->thuonghieu_model->checktrungMaTH($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy thương hiệu có mã: ' . $id]);
        }

        if ($this->thuonghieu_model->ThuongHieu_hasProducts($id)) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Không thể xóa vì đang có sản phẩm thuộc Thương hiệu "' . $id . '"'
            ]);
        }

        $deleteResult = $this->thuonghieu_model->ThuongHieu_delete($id);
        if (!$deleteResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể xóa thương hiệu',
                'error' => mysqli_error($this->thuonghieu_model->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Xóa thương hiệu thành công']);
    }

    /**
     * Endpoint: POST /Api/Thuonghieu/import
     * Cột Excel: A ma_thuong_hieu, B ten_thuong_hieu
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
            $ma_thuong_hieu = isset($sheetData[$i]['A']) ? trim($sheetData[$i]['A']) : '';
            $ten_thuong_hieu = isset($sheetData[$i]['B']) ? trim($sheetData[$i]['B']) : '';

            if ($ma_thuong_hieu === '') {
                $skipped_empty++;
                continue;
            }

            if ($ten_thuong_hieu === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_thuong_hieu' => $ma_thuong_hieu,
                    'reason' => 'Thiếu tên thương hiệu (cột B)'
                ];
                continue;
            }

            if ($this->thuonghieu_model->checktrungMaTH($ma_thuong_hieu)) {
                $duplicated_codes[] = $ma_thuong_hieu;
                continue;
            }

            $inserted = $this->thuonghieu_model->thuonghieu_ins($ma_thuong_hieu, $ten_thuong_hieu);
            if ($inserted) {
                $created++;
            } else {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_thuong_hieu' => $ma_thuong_hieu,
                    'reason' => mysqli_error($this->thuonghieu_model->con)
                ];
            }
        }

        $response = [
            'success' => true,
            'message' => 'Import thương hiệu hoàn tất',
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