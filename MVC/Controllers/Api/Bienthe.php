<?php
class Bienthe extends api_controller {
    private $bienthe_model;
    private $sanpham_model;

    public function __construct() {
        parent::__construct();
        $this->bienthe_model = $this->model('BienThe_m');
        $this->sanpham_model = $this->model('SanPham_m');
    }

    private function parseInputData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }
        return $this->getJsonInput();
    }

    private function getImageUpload() {
        if (isset($_FILES['image'])) {
            return $_FILES['image'];
        }
        if (isset($_FILES['txtImage'])) {
            return $_FILES['txtImage'];
        }
        return null;
    }

    private function saveVariantImage($file, $oldImage = '') {
        if (!$file || !isset($file['error']) || $file['error'] !== 0 || empty($file['tmp_name'])) {
            return ['success' => true, 'filename' => $oldImage];
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = isset($file['name']) ? $file['name'] : '';
        $filetmp = $file['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true)) {
            return ['success' => false, 'message' => 'Định dạng hình ảnh không hợp lệ'];
        }

        $originalName = pathinfo($filename, PATHINFO_FILENAME);
        $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
        $originalName = str_replace('-', '_', $originalName);
        if ($originalName === '') {
            $originalName = 'bien_the';
        }

        $uploadDir = __DIR__ . '/../../../Public/Pictures/bien_the/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            return ['success' => false, 'message' => 'Không thể tạo thư mục upload'];
        }

        $newFilename = $originalName . '.' . $ext;
        $counter = 1;
        while (file_exists($uploadDir . $newFilename)) {
            $newFilename = $originalName . '_' . $counter . '.' . $ext;
            $counter++;
        }

        $uploadPath = $uploadDir . $newFilename;
        if (!move_uploaded_file($filetmp, $uploadPath)) {
            return ['success' => false, 'message' => 'Upload hình ảnh thất bại'];
        }

        if (!empty($oldImage)) {
            $oldPath = $uploadDir . $oldImage;
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return ['success' => true, 'filename' => $newFilename];
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_bien_the = isset($_GET['ma_bien_the']) ? trim($_GET['ma_bien_the']) : '';
        $ten_bien_the = isset($_GET['ten_bien_the']) ? trim($_GET['ten_bien_the']) : '';

        $is_search = ($ma_bien_the !== '' || $ten_bien_the !== '');
        $result = $is_search
            ? $this->bienthe_model->BienThe_find($ma_bien_the, $ten_bien_the)
            : $this->bienthe_model->BienThe_getAll();

        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy danh sách biến thể']);
        }

        $variants = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $variants[] = $row;
        }

        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($variants);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => $is_search ? 'Tìm kiếm biến thể thành công' : 'Lấy danh sách biến thể thành công',
            'total' => count($variants),
            'filters' => [
                'ma_bien_the' => $ma_bien_the,
                'ten_bien_the' => $ten_bien_the
            ],
            'data' => $variants
        ]);
    }

    private function exportXlsx($variants) {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachBienThe');

        $sheet->setCellValue('A1', 'Mã biến thể');
        $sheet->setCellValue('B1', 'Mã sản phẩm');
        $sheet->setCellValue('C1', 'Tên sản phẩm');
        $sheet->setCellValue('D1', 'Tên biến thể');
        $sheet->setCellValue('E1', 'Hình ảnh');
        $sheet->setCellValue('F1', 'Màu sắc');
        $sheet->setCellValue('G1', 'RAM');
        $sheet->setCellValue('H1', 'Dung lượng');
        $sheet->setCellValue('I1', 'Giá');
        $sheet->setCellValue('J1', 'Số lượng kho');

        $rowCount = 2;
        foreach ($variants as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_bien_the'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['ma_san_pham'] ?? '');
            $sheet->setCellValue('C' . $rowCount, $row['ten_san_pham'] ?? '');
            $sheet->setCellValue('D' . $rowCount, $row['ten_bien_the'] ?? '');
            $sheet->setCellValue('E' . $rowCount, $row['img_bien_the'] ?? '');
            $sheet->setCellValue('F' . $rowCount, $row['mau_sac'] ?? '');
            $sheet->setCellValue('G' . $rowCount, $row['ram'] ?? '');
            $sheet->setCellValue('H' . $rowCount, $row['dung_luong'] ?? '');
            $sheet->setCellValue('I' . $rowCount, $row['gia'] ?? '');
            $sheet->setCellValue('J' . $rowCount, $row['so_luong_kho'] ?? '');
            $rowCount++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DanhSachBienThe.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã biến thể']);
        }

        $result = $this->bienthe_model->BienThe_getById($id);
        if ($result && mysqli_num_rows($result) > 0) {
            $variant = mysqli_fetch_assoc($result);
            $this->sendResponse(200, ['success' => true, 'data' => $variant]);
        }

        $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy biến thể có mã: ' . $id]);
    }

    public function search($ma_bien_the = null, $ten_bien_the = null) {
        if ($ma_bien_the !== null && trim($ma_bien_the) !== '') {
            $_GET['ma_bien_the'] = trim($ma_bien_the);
        }

        if ($ten_bien_the !== null && trim($ten_bien_the) !== '') {
            $_GET['ten_bien_the'] = trim($ten_bien_the);
        }

        $this->get_all();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->parseInputData();
        $ma_bien_the = trim($data['ma_bien_the'] ?? $data['txtMaBienThe'] ?? '');
        $ma_san_pham = trim($data['ma_san_pham'] ?? $data['ddlSanPham'] ?? '');
        $ten_bien_the = trim($data['ten_bien_the'] ?? $data['txtTenBienThe'] ?? '');
        $mau_sac = trim($data['mau_sac'] ?? $data['txtMauSac'] ?? '');
        $ram = trim($data['ram'] ?? $data['txtRAM'] ?? '');
        $dung_luong = trim($data['dung_luong'] ?? $data['txtDungLuong'] ?? '');
        $gia = trim($data['gia'] ?? $data['txtGia'] ?? '0');
        $so_luong_kho = trim($data['so_luong_kho'] ?? $data['txtSoLuongKho'] ?? '0');

        if ($ma_bien_the === '' || $ma_san_pham === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp mã biến thể và mã sản phẩm']);
        }

        if ($this->bienthe_model->checktrungMaBT($ma_bien_the)) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã biến thể đã tồn tại']);
        }

        if (!$this->sanpham_model->checktrungMaSP($ma_san_pham)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Mã sản phẩm không tồn tại: ' . $ma_san_pham]);
        }

        $img_bien_the = '';
        $upload = $this->getImageUpload();
        if ($upload) {
            $uploadResult = $this->saveVariantImage($upload);
            if (!$uploadResult['success']) {
                $this->sendResponse(400, ['success' => false, 'message' => $uploadResult['message']]);
            }
            $img_bien_the = $uploadResult['filename'];
        }

        $inserted = $this->bienthe_model->bien_the_ins(
            $ma_bien_the,
            $ma_san_pham,
            $ten_bien_the,
            $img_bien_the,
            $mau_sac,
            $ram,
            $dung_luong,
            $gia,
            $so_luong_kho
        );

        if (!$inserted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể thêm biến thể',
                'error' => mysqli_error($this->bienthe_model->con)
            ]);
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Thêm biến thể thành công',
            'data' => [
                'ma_bien_the' => $ma_bien_the,
                'ma_san_pham' => $ma_san_pham,
                'ten_bien_the' => $ten_bien_the,
                'img_bien_the' => $img_bien_the,
                'mau_sac' => $mau_sac,
                'ram' => $ram,
                'dung_luong' => $dung_luong,
                'gia' => $gia,
                'so_luong_kho' => $so_luong_kho
            ]
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT/PATCH/POST']);
        }

        $data = $this->parseInputData();
        $ma_bien_the = trim($id ?? $data['ma_bien_the'] ?? $data['txtMaBienThe'] ?? '');

        if ($ma_bien_the === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã biến thể']);
        }

        $currentResult = $this->bienthe_model->BienThe_getById($ma_bien_the);
        if (!$currentResult || mysqli_num_rows($currentResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy biến thể có mã: ' . $ma_bien_the]);
        }

        $current = mysqli_fetch_assoc($currentResult);

        $ma_san_pham = trim($data['ma_san_pham'] ?? $data['ddlSanPham'] ?? ($current['ma_san_pham'] ?? ''));
        $ten_bien_the = trim($data['ten_bien_the'] ?? $data['txtTenBienThe'] ?? ($current['ten_bien_the'] ?? ''));
        $mau_sac = trim($data['mau_sac'] ?? $data['txtMauSac'] ?? ($current['mau_sac'] ?? ''));
        $ram = trim($data['ram'] ?? $data['txtRAM'] ?? ($current['ram'] ?? ''));
        $dung_luong = trim($data['dung_luong'] ?? $data['txtDungLuong'] ?? ($current['dung_luong'] ?? ''));
        $gia = trim($data['gia'] ?? $data['txtGia'] ?? ($current['gia'] ?? '0'));
        $so_luong_kho = trim($data['so_luong_kho'] ?? $data['txtSoLuongKho'] ?? ($current['so_luong_kho'] ?? '0'));

        if ($ma_san_pham === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp mã sản phẩm']);
        }

        if (!$this->sanpham_model->checktrungMaSP($ma_san_pham)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Mã sản phẩm không tồn tại: ' . $ma_san_pham]);
        }

        $img_bien_the = $current['img_bien_the'] ?? '';
        $upload = $this->getImageUpload();
        if ($upload) {
            $uploadResult = $this->saveVariantImage($upload, $img_bien_the);
            if (!$uploadResult['success']) {
                $this->sendResponse(400, ['success' => false, 'message' => $uploadResult['message']]);
            }
            $img_bien_the = $uploadResult['filename'];
        }

        $updated = $this->bienthe_model->BienThe_update(
            $ma_bien_the,
            $ma_san_pham,
            $ten_bien_the,
            $img_bien_the,
            $mau_sac,
            $ram,
            $dung_luong,
            $gia,
            $so_luong_kho
        );

        if (!$updated) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật biến thể',
                'error' => mysqli_error($this->bienthe_model->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật biến thể thành công',
            'data' => [
                'ma_bien_the' => $ma_bien_the,
                'ma_san_pham' => $ma_san_pham,
                'ten_bien_the' => $ten_bien_the,
                'img_bien_the' => $img_bien_the,
                'mau_sac' => $mau_sac,
                'ram' => $ram,
                'dung_luong' => $dung_luong,
                'gia' => $gia,
                'so_luong_kho' => $so_luong_kho
            ]
        ]);
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã biến thể']);
        }

        if (!$this->bienthe_model->checktrungMaBT($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy biến thể có mã: ' . $id]);
        }

        $deleted = $this->bienthe_model->BienThe_delete($id);
        if (!$deleted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể xóa biến thể',
                'error' => mysqli_error($this->bienthe_model->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Xóa biến thể thành công']);
    }

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

        $headerC = mb_strtolower(trim((string)($sheetData[1]['C'] ?? '')));
        $isExportLayout = (strpos($headerC, 'tên sản phẩm') !== false || strpos($headerC, 'ten san pham') !== false);

        $created = 0;
        $skipped_empty = 0;
        $duplicated_codes = [];
        $failed_rows = [];

        for ($i = 2; $i <= count($sheetData); $i++) {
            $ma_bien_the = isset($sheetData[$i]['A']) ? trim((string)$sheetData[$i]['A']) : '';
            $ma_san_pham = isset($sheetData[$i]['B']) ? trim((string)$sheetData[$i]['B']) : '';

            if ($isExportLayout) {
                $ten_bien_the = isset($sheetData[$i]['D']) ? trim((string)$sheetData[$i]['D']) : '';
                $img_bien_the = isset($sheetData[$i]['E']) ? trim((string)$sheetData[$i]['E']) : '';
                $mau_sac = isset($sheetData[$i]['F']) ? trim((string)$sheetData[$i]['F']) : '';
                $ram = isset($sheetData[$i]['G']) ? trim((string)$sheetData[$i]['G']) : '';
                $dung_luong = isset($sheetData[$i]['H']) ? trim((string)$sheetData[$i]['H']) : '';
                $gia = isset($sheetData[$i]['I']) ? trim((string)$sheetData[$i]['I']) : '0';
                $so_luong_kho = isset($sheetData[$i]['J']) ? trim((string)$sheetData[$i]['J']) : '0';
            } else {
                $ten_bien_the = isset($sheetData[$i]['C']) ? trim((string)$sheetData[$i]['C']) : '';
                $img_bien_the = '';
                $mau_sac = isset($sheetData[$i]['D']) ? trim((string)$sheetData[$i]['D']) : '';
                $ram = isset($sheetData[$i]['E']) ? trim((string)$sheetData[$i]['E']) : '';
                $dung_luong = isset($sheetData[$i]['F']) ? trim((string)$sheetData[$i]['F']) : '';
                $gia = isset($sheetData[$i]['G']) ? trim((string)$sheetData[$i]['G']) : '0';
                $so_luong_kho = isset($sheetData[$i]['H']) ? trim((string)$sheetData[$i]['H']) : '0';
            }

            if ($ma_bien_the === '') {
                $skipped_empty++;
                continue;
            }

            if ($ma_san_pham === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_bien_the' => $ma_bien_the,
                    'reason' => 'Thiếu mã sản phẩm'
                ];
                continue;
            }

            if (!$this->sanpham_model->checktrungMaSP($ma_san_pham)) {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_bien_the' => $ma_bien_the,
                    'reason' => 'Mã sản phẩm không tồn tại: ' . $ma_san_pham
                ];
                continue;
            }

            if ($this->bienthe_model->checktrungMaBT($ma_bien_the)) {
                $duplicated_codes[] = $ma_bien_the;
                continue;
            }

            $inserted = $this->bienthe_model->bien_the_ins(
                $ma_bien_the,
                $ma_san_pham,
                $ten_bien_the,
                $img_bien_the,
                $mau_sac,
                $ram,
                $dung_luong,
                $gia,
                $so_luong_kho
            );

            if ($inserted) {
                $created++;
            } else {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_bien_the' => $ma_bien_the,
                    'reason' => mysqli_error($this->bienthe_model->con)
                ];
            }
        }

        $response = [
            'success' => true,
            'message' => 'Import biến thể hoàn tất',
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