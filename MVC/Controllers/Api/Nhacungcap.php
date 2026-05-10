<?php
class Nhacungcap extends api_controller
{
    private $nhacungcap_model;

    public function __construct()
    {
        parent::__construct();
        $this->nhacungcap_model = $this->model('NhaCungCap_m');
    }

    /**
     * Endpoint: GET /Api/Nhacungcap
     * Hỗ trợ filter: ma_nha_cung_cap, ten_nha_cung_cap
     * Hỗ trợ export: ?format=xlsx
     */
    public function get_all()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_nha_cung_cap = isset($_GET['ma_nha_cung_cap']) ? trim($_GET['ma_nha_cung_cap']) : '';
        $ten_nha_cung_cap = isset($_GET['ten_nha_cung_cap']) ? trim($_GET['ten_nha_cung_cap']) : '';

        $result = $this->nhacungcap_model->NhaCungCap_find($ma_nha_cung_cap, $ten_nha_cung_cap);
        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy danh sách nhà cung cấp']);
        }

        $suppliers = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $suppliers[] = $row;
        }

        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($suppliers);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy danh sách nhà cung cấp thành công',
            'total' => count($suppliers),
            'filters' => [
                'ma_nha_cung_cap' => $ma_nha_cung_cap,
                'ten_nha_cung_cap' => $ten_nha_cung_cap
            ],
            'data' => $suppliers
        ]);
    }
    /**
     * Endpoint: GET /Api/Nhacungcap/NCC01
     */
    public function get_detail($id = null)
    {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã nhà cung cấp']);
        }

        $result = $this->nhacungcap_model->NhaCungCap_getById($id);
        if ($result && mysqli_num_rows($result) > 0) {
            $supplier = mysqli_fetch_assoc($result);
            $this->sendResponse(200, ['success' => true, 'data' => $supplier]);
        }

        $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy nhà cung cấp có mã: ' . $id]);
    }

    /**
     * Endpoint: GET /Api/Nhacungcap/search?ma_nha_cung_cap=NCC01&ten_nha_cung_cap=fpt
     * Tương thích route cũ.
     */
    // public function search($ma_nha_cung_cap = null, $ten_nha_cung_cap = null)
    // {
    //     if ($ma_nha_cung_cap !== null && trim($ma_nha_cung_cap) !== '') {
    //         $_GET['ma_nha_cung_cap'] = trim($ma_nha_cung_cap);
    //     }

    //     if ($ten_nha_cung_cap !== null && trim($ten_nha_cung_cap) !== '') {
    //         $_GET['ten_nha_cung_cap'] = trim($ten_nha_cung_cap);
    //     }

    //     $this->get_all();
    // }

    /**
     * Endpoint: POST /Api/Nhacungcap
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->getJsonInput();

        if (empty($data['ma_nha_cung_cap']) || empty($data['ten_nha_cung_cap'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_nha_cung_cap và ten_nha_cung_cap']);
        }

        if ($this->nhacungcap_model->checktrungMaNCC($data['ma_nha_cung_cap'])) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã nhà cung cấp đã tồn tại']);
        }

        $insertResult = $this->nhacungcap_model->nhacungcap_ins(
            $data['ma_nha_cung_cap'],
            $data['ten_nha_cung_cap'],
            $data['dia_chi'] ?? '',
            $data['dien_thoai'] ?? ''
        );

        if (!$insertResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể thêm nhà cung cấp',
                'error' => mysqli_error($this->nhacungcap_model->con)
            ]);
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Thêm nhà cung cấp thành công',
            'data' => $data
        ]);
    }

    /**
     * Endpoint: PUT/PATCH /Api/Nhacungcap/NCC01
     */
    public function update($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT or PATCH']);
        }

        $data = $this->getJsonInput();
        if ($id !== null && trim($id) !== '') {
            $data['ma_nha_cung_cap'] = trim($id);
        }

        if (empty($data['ma_nha_cung_cap']) || empty($data['ten_nha_cung_cap'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_nha_cung_cap và ten_nha_cung_cap']);
        }

        if (!$this->nhacungcap_model->checktrungMaNCC($data['ma_nha_cung_cap'])) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy nhà cung cấp có mã: ' . $data['ma_nha_cung_cap']]);
        }

        $updateResult = $this->nhacungcap_model->NhaCungCap_update(
            $data['ma_nha_cung_cap'],
            $data['ten_nha_cung_cap'],
            $data['dia_chi'] ?? '',
            $data['dien_thoai'] ?? ''
        );

        if (!$updateResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật nhà cung cấp',
                'error' => mysqli_error($this->nhacungcap_model->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật nhà cung cấp thành công',
            'data' => $data
        ]);
    }

    /**
     * Endpoint: DELETE /Api/Nhacungcap/NCC01
     */
    public function delete($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã nhà cung cấp']);
        }

        if (!$this->nhacungcap_model->checktrungMaNCC($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy nhà cung cấp có mã: ' . $id]);
        }

        if ($this->nhacungcap_model->NhaCungCap_hasProducts($id)) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Không thể xóa vì đang có sản phẩm thuộc Nhà cung cấp "' . $id . '"'
            ]);
        }

        $deleteResult = $this->nhacungcap_model->NhaCungCap_delete($id);
        if (!$deleteResult) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể xóa nhà cung cấp',
                'error' => mysqli_error($this->nhacungcap_model->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Xóa nhà cung cấp thành công']);
    }


    private function exportXlsx($suppliers)
    {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachNhaCungCap');

        $sheet->setCellValue('A1', 'Mã nhà cung cấp');
        $sheet->setCellValue('B1', 'Tên nhà cung cấp');
        $sheet->setCellValue('C1', 'Địa chỉ');
        $sheet->setCellValue('D1', 'Điện thoại');

        $rowCount = 2;
        foreach ($suppliers as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_nha_cung_cap'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['ten_nha_cung_cap'] ?? '');
            $sheet->setCellValue('C' . $rowCount, $row['dia_chi'] ?? '');
            $sheet->setCellValue('D' . $rowCount, $row['dien_thoai'] ?? '');
            $rowCount++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DanhSachNhaCungCap.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }


    /**
     * Endpoint: POST /Api/Nhacungcap/import
     * Cột Excel: A ma_nha_cung_cap, B ten_nha_cung_cap, C dia_chi, D dien_thoai
     */
    public function import()
    {
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
            $ma_nha_cung_cap = isset($sheetData[$i]['A']) ? trim($sheetData[$i]['A']) : '';
            $ten_nha_cung_cap = isset($sheetData[$i]['B']) ? trim($sheetData[$i]['B']) : '';
            $dia_chi = isset($sheetData[$i]['C']) ? trim($sheetData[$i]['C']) : '';
            $dien_thoai = isset($sheetData[$i]['D']) ? trim($sheetData[$i]['D']) : '';

            if ($ma_nha_cung_cap === '') {
                $skipped_empty++;
                continue;
            }

            if ($ten_nha_cung_cap === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_nha_cung_cap' => $ma_nha_cung_cap,
                    'reason' => 'Thiếu tên nhà cung cấp (cột B)'
                ];
                continue;
            }

            if ($this->nhacungcap_model->checktrungMaNCC($ma_nha_cung_cap)) {
                $duplicated_codes[] = $ma_nha_cung_cap;
                continue;
            }

            $inserted = $this->nhacungcap_model->nhacungcap_ins($ma_nha_cung_cap, $ten_nha_cung_cap, $dia_chi, $dien_thoai);
            if ($inserted) {
                $created++;
            } else {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_nha_cung_cap' => $ma_nha_cung_cap,
                    'reason' => mysqli_error($this->nhacungcap_model->con)
                ];
            }
        }

        $response = [
            'success' => true,
            'message' => 'Import nhà cung cấp hoàn tất',
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
