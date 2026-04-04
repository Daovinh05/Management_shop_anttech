<?php
class Products extends api_controller {
    private $sanpham_model;

    public function __construct() {
        parent::__construct();
        // Khởi tạo Model Sản Phẩm thực tế
        $this->sanpham_model = $this->model('SanPham_m');
    }

    /**
        * Endpoint: GET /Api/Products
     * Lấy danh sách hoặc tìm kiếm sản phẩm qua query params
     * Ví dụ: /Api/Products?ma_san_pham=SP020&ten_san_pham=iphone
     */
    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_san_pham = isset($_GET['ma_san_pham']) ? trim($_GET['ma_san_pham']) : '';
        $ten_san_pham = isset($_GET['ten_san_pham']) ? trim($_GET['ten_san_pham']) : '';

        $is_search = ($ma_san_pham !== '' || $ten_san_pham !== '');
        $result = $is_search
            ? $this->sanpham_model->SanPham_find($ma_san_pham, $ten_san_pham)
            : $this->sanpham_model->SanPham_getAll();

        if (!$result) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => $is_search
                    ? 'Đã có lỗi xảy ra khi tìm kiếm sản phẩm'
                    : 'Đã có lỗi xảy ra khi lấy danh sách sản phẩm'
            ]);
        }
        
        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        // Hỗ trợ xuất Excel theo chuẩn: GET /Api/Products?format=xlsx
        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($products);
        }

        $response = [
            'success' => true,
            'message' => $is_search ? 'Tìm kiếm sản phẩm thành công' : 'Lấy danh sách sản phẩm thành công',
            'total' => count($products),
            'data' => $products
        ];

        if ($is_search) {
            $response['filters'] = [
                'ma_san_pham' => $ma_san_pham,
                'ten_san_pham' => $ten_san_pham
            ];
        }

        $this->sendResponse(200, $response);
    }

    private function exportXlsx($products) {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachSanPham');

        $sheet->setCellValue('A1', 'Mã sản phẩm');
        $sheet->setCellValue('B1', 'Tên sản phẩm');
        $sheet->setCellValue('C1', 'Hình ảnh biến thể');
        $sheet->setCellValue('D1', 'Giá');
        $sheet->setCellValue('E1', 'Số lượng');
        $sheet->setCellValue('F1', 'Tên danh mục');
        $sheet->setCellValue('G1', 'Tên thương hiệu');
        $sheet->setCellValue('H1', 'Tên nhà cung cấp');

        $rowCount = 2;
        foreach ($products as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_san_pham'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['ten_san_pham'] ?? '');
            $sheet->setCellValue('C' . $rowCount, $row['img_bien_the'] ?? '');
            $sheet->setCellValue('D' . $rowCount, $row['gia'] ?? '');
            $sheet->setCellValue('E' . $rowCount, $row['so_luong_kho'] ?? '');
            $sheet->setCellValue('F' . $rowCount, $row['ten_danh_muc'] ?? '');
            $sheet->setCellValue('G' . $rowCount, $row['ten_thuong_hieu'] ?? '');
            $sheet->setCellValue('H' . $rowCount, $row['ten_nha_cung_cap'] ?? '');
            $rowCount++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DanhSachSanPham.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    /**
        * Endpoint: GET /Api/Products/SP01
     * Lấy chi tiết 1 sản phẩm theo ID thực tế từ Database
     */
    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã sản phẩm (Ví dụ: SP01)']);
        }

        $result = $this->sanpham_model->SanPham_getById($id);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            $this->sendResponse(200, [
                'success' => true,
                'data' => $product
            ]);
        } else {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm có mã: ' . $id
            ]);
        }
    }

    /**
     * Endpoint: GET /Api/Products/search?ma_san_pham=SP01&ten_san_pham=iphone
     * Tương thích ngược. Khuyến nghị dùng GET /Api/Products?ma_san_pham=...&ten_san_pham=...
     */
    public function search($ma_san_pham = null, $ten_san_pham = null) {
        if ($ma_san_pham !== null && trim($ma_san_pham) !== '') {
            $_GET['ma_san_pham'] = trim($ma_san_pham);
        }

        if ($ten_san_pham !== null && trim($ten_san_pham) !== '') {
            $_GET['ten_san_pham'] = trim($ten_san_pham);
        }

        $this->get_all();
    }

    /**
        * Endpoint: POST /Api/Products
     * Tạo sản phẩm mới
     */
    public function create() {
        // Chỉ chấp nhận POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed']);
        }

        // Lấy dữ liệu gửi lên dưới dạng JSON payload
        $data = $this->getJsonInput();

        // Validate dữ liệu cơ bản dựa theo hàm sanpham_ins trong SanPham_m
        if (empty($data['ma_san_pham']) || empty($data['ten_san_pham']) || empty($data['ma_danh_muc'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp đủ thông tin: mã_san_pham, ten_san_pham và ma_danh_muc']);
        }

        // Các trường không bắt buộc
        $ma_thuong_hieu = $data['ma_thuong_hieu'] ?? null;
        $ma_nha_cung_cap = $data['ma_nha_cung_cap'] ?? null;

        // Kiểm tra xem sản phẩm đã tồn tại chưa
        if ($this->sanpham_model->checktrungMaSP($data['ma_san_pham'])) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã sản phẩm đã tồn tại!']);
        }

        // Thực thi insert vào DB
        $insert_result = $this->sanpham_model->sanpham_ins(
            $data['ma_san_pham'], 
            $data['ten_san_pham'], 
            $data['ma_danh_muc'], 
            $ma_thuong_hieu, 
            $ma_nha_cung_cap
        );

        if ($insert_result) {
            $this->sendResponse(201, [
                'success' => true,
                'message' => 'Tạo sản phẩm thành công',
                'data' => $data
            ]);
        } else {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi lưu vào Database'
            ]);
        }
    }

    /**
     * Endpoint: POST /Api/Products/import
     * Nhập danh sách sản phẩm từ file Excel (.xlsx/.xls)
     * Form-data key hỗ trợ: file hoặc txtfile
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

        $headerA = $this->normalizeImportHeader($sheetData[1]['A'] ?? '');
        $headerB = $this->normalizeImportHeader($sheetData[1]['B'] ?? '');
        $headerC = $this->normalizeImportHeader($sheetData[1]['C'] ?? '');
        $headerF = $this->normalizeImportHeader($sheetData[1]['F'] ?? '');

        $isTemplateAE = ($headerA === 'mã sản phẩm' && $headerB === 'tên sản phẩm' && $headerC === 'mã danh mục');
        $isExportAH = ($headerA === 'mã sản phẩm' && $headerB === 'tên sản phẩm' && $headerF === 'tên danh mục');

        if (!$isTemplateAE && !$isExportAH) {
            $this->sendResponse(400, [
                'success' => false,
                'message' => 'Sai định dạng file. Dùng file mẫu A-E hoặc file export sản phẩm A-H.'
            ]);
        }

        $created = 0;
        $skipped_empty = 0;
        $duplicated_codes = [];
        $failed_rows = [];

        for ($i = 2; $i <= count($sheetData); $i++) {
            $ma_san_pham = isset($sheetData[$i]['A']) ? trim($sheetData[$i]['A']) : '';
            $ten_san_pham = isset($sheetData[$i]['B']) ? trim($sheetData[$i]['B']) : '';

            if ($isExportAH) {
                $rawDanhMuc = isset($sheetData[$i]['F']) ? trim($sheetData[$i]['F']) : '';
                $rawThuongHieu = isset($sheetData[$i]['G']) ? trim($sheetData[$i]['G']) : '';
                $rawNhaCungCap = isset($sheetData[$i]['H']) ? trim($sheetData[$i]['H']) : '';
            } else {
                $rawDanhMuc = isset($sheetData[$i]['C']) ? trim($sheetData[$i]['C']) : '';
                $rawThuongHieu = isset($sheetData[$i]['D']) ? trim($sheetData[$i]['D']) : '';
                $rawNhaCungCap = isset($sheetData[$i]['E']) ? trim($sheetData[$i]['E']) : '';
            }

            if ($ma_san_pham === '') {
                $skipped_empty++;
                continue;
            }

            if ($ten_san_pham === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_san_pham' => $ma_san_pham,
                    'reason' => 'Thiếu tên sản phẩm (cột B)'
                ];
                continue;
            }

            if ($rawDanhMuc === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_san_pham' => $ma_san_pham,
                    'reason' => $isExportAH ? 'Thiếu tên danh mục (cột F)' : 'Thiếu mã danh mục (cột C)'
                ];
                continue;
            }

            if ($this->sanpham_model->checktrungMaSP($ma_san_pham)) {
                $duplicated_codes[] = $ma_san_pham;
                continue;
            }

            $ma_danh_muc = $this->resolveReferenceCode('danh_muc', 'ma_danh_muc', 'ten_danh_muc', $rawDanhMuc);
            if ($ma_danh_muc === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_san_pham' => $ma_san_pham,
                    'reason' => 'Danh mục không tồn tại: ' . $rawDanhMuc
                ];
                continue;
            }

            $ma_thuong_hieu = null;
            if ($rawThuongHieu !== '') {
                $ma_thuong_hieu = $this->resolveReferenceCode('thuong_hieu', 'ma_thuong_hieu', 'ten_thuong_hieu', $rawThuongHieu);
                if ($ma_thuong_hieu === '') {
                    $failed_rows[] = [
                        'row' => $i,
                        'ma_san_pham' => $ma_san_pham,
                        'reason' => 'Thương hiệu không tồn tại: ' . $rawThuongHieu
                    ];
                    continue;
                }
            }

            $ma_nha_cung_cap = null;
            if ($rawNhaCungCap !== '') {
                $ma_nha_cung_cap = $this->resolveReferenceCode('nha_cung_cap', 'ma_nha_cung_cap', 'ten_nha_cung_cap', $rawNhaCungCap);
                if ($ma_nha_cung_cap === '') {
                    $failed_rows[] = [
                        'row' => $i,
                        'ma_san_pham' => $ma_san_pham,
                        'reason' => 'Nhà cung cấp không tồn tại: ' . $rawNhaCungCap
                    ];
                    continue;
                }
            }

            $inserted = $this->sanpham_model->sanpham_ins(
                $ma_san_pham,
                $ten_san_pham,
                $ma_danh_muc,
                $ma_thuong_hieu,
                $ma_nha_cung_cap
            );

            if ($inserted) {
                $created++;
            } else {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_san_pham' => $ma_san_pham,
                    'reason' => mysqli_error($this->sanpham_model->con)
                ];
            }
        }

        $duplicated_details = [];
        foreach ($duplicated_codes as $duplicate_code) {
            $duplicated_details[] = [
                'ma_san_pham' => $duplicate_code,
                'reason' => 'Mã sản phẩm đã tồn tại'
            ];
        }

        $response = [
            'success' => true,
            'message' => 'Import sản phẩm hoàn tất',
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
            $response['duplicates'] = $duplicated_details;
            $this->sendResponse(422, $response);
        }

        if (count($duplicated_codes) > 0 || count($failed_rows) > 0) {
            $response['message'] = 'Import hoàn tất (có một số dòng bị bỏ qua/lỗi)';
            $response['duplicates'] = $duplicated_details;
        }

        $this->sendResponse(200, $response);
    }

    private function normalizeImportHeader($value) {
        $value = trim((string)$value);
        $value = preg_replace('/\s+/', ' ', $value);
        return mb_strtolower($value, 'UTF-8');
    }

    private function resolveReferenceCode($table, $codeColumn, $nameColumn, $rawValue) {
        $rawValue = trim((string)$rawValue);
        if ($rawValue === '') {
            return '';
        }

        $con = $this->sanpham_model->con;
        $escaped = mysqli_real_escape_string($con, $rawValue);

        $sqlByCode = "SELECT $codeColumn FROM $table WHERE $codeColumn = '$escaped' LIMIT 1";
        $resultByCode = mysqli_query($con, $sqlByCode);
        if ($resultByCode && mysqli_num_rows($resultByCode) > 0) {
            $row = mysqli_fetch_assoc($resultByCode);
            return $row[$codeColumn];
        }

        $sqlByName = "SELECT $codeColumn FROM $table WHERE $nameColumn = '$escaped' LIMIT 1";
        $resultByName = mysqli_query($con, $sqlByName);
        if ($resultByName && mysqli_num_rows($resultByName) > 0) {
            $row = mysqli_fetch_assoc($resultByName);
            return $row[$codeColumn];
        }

        return '';
    }

    /**
     * Endpoint: PUT/PATCH /Api/Products/SP01
     * Cập nhật thông tin sản phẩm
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT or PATCH']);
        }

        // Lấy dữ liệu gửi lên dưới dạng JSON
        $data = $this->getJsonInput();

        // Ưu tiên lấy mã sản phẩm từ URL theo chuẩn RESTful
        if ($id !== null && trim($id) !== '') {
            $data['ma_san_pham'] = trim($id);
        }

        if (empty($data['ma_san_pham'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_san_pham trên URL (Ví dụ: /Api/Products/SP01) hoặc trong payload']);
        }

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$this->sanpham_model->checktrungMaSP($data['ma_san_pham'])) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm có mã: ' . $data['ma_san_pham']]);
        }

        // Thực thi update
        $update_result = $this->sanpham_model->SanPham_update(
            $data['ma_san_pham'],
            $data['ten_san_pham'] ?? '',
            $data['ma_danh_muc'] ?? '',
            $data['ma_thuong_hieu'] ?? '',
            $data['ma_nha_cung_cap'] ?? ''
        );

        if ($update_result) {
            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Cập nhật sản phẩm thành công',
                'data' => $data
            ]);
        } else {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi cập nhật Database'
            ]);
        }
    }

    /**
        * Endpoint: DELETE /Api/Products/SP01
     * Xóa sản phẩm
     */
    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã sản phẩm cần xóa trên URL']);
        }

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$this->sanpham_model->checktrungMaSP($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm có mã: ' . $id]);
        }

        // Chặn xóa nếu sản phẩm đã phát sinh trong chi tiết đơn hàng
        if ($this->sanpham_model->SanPham_hasOrderDetails($id)) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Không thể xóa vì sản phẩm ' . $id . ' đã có trong chi tiết đơn hàng.'
            ]);
        }

        // Thực thi xóa
        $delete_result = $this->sanpham_model->SanPham_delete($id);

        if ($delete_result) {
            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);
        } else {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi xóa dữ liệu'
            ]);
        }
    }
}
?>
