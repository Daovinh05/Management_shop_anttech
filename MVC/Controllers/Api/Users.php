<?php
class Users extends api_controller {
    private $users_model;
    private $allowed_roles = ['admin', 'nhan_vien', 'khach_hang'];

    public function __construct() {
        parent::__construct();
        $this->users_model = $this->model('Users_m');
    }

    private function normalizeRole($role) {
        $role = trim((string)$role);
        if ($role === '') {
            return 'khach_hang';
        }
        return $role;
    }

    private function isValidRole($role) {
        return in_array($role, $this->allowed_roles, true);
    }

    private function parseInputData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }
        return $this->getJsonInput();
    }

    private function getAvatarUpload() {
        if (isset($_FILES['avatar'])) {
            return $_FILES['avatar'];
        }
        if (isset($_FILES['txtAvatar'])) {
            return $_FILES['txtAvatar'];
        }
        return null;
    }

    private function saveAvatar($file, $oldAvatar = '') {
        if (!$file || !isset($file['error']) || $file['error'] !== 0 || empty($file['tmp_name'])) {
            return ['success' => true, 'avatar' => $oldAvatar];
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = isset($file['name']) ? $file['name'] : '';
        $filetmp = $file['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true)) {
            return ['success' => false, 'message' => 'Định dạng avatar không hợp lệ. Chỉ cho phép: jpg, jpeg, png, gif, webp'];
        }

        $original_name = pathinfo($filename, PATHINFO_FILENAME);
        $original_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $original_name);
        $original_name = str_replace('-', '_', $original_name);
        if ($original_name === '') {
            $original_name = 'avatar';
        }
        $new_filename = $original_name . '_' . time() . '.' . $ext;

        $upload_dir = __DIR__ . '/../../../Public/Pictures/users/';
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0777, true)) {
            return ['success' => false, 'message' => 'Không thể tạo thư mục upload avatar'];
        }

        $upload_path = $upload_dir . $new_filename;
        if (!move_uploaded_file($filetmp, $upload_path)) {
            return ['success' => false, 'message' => 'Upload avatar thất bại'];
        }

        if (!empty($oldAvatar)) {
            $oldPath = $upload_dir . $oldAvatar;
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return ['success' => true, 'avatar' => $new_filename];
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = isset($_GET['ma_user']) ? trim($_GET['ma_user']) : '';
        $ten_user = isset($_GET['ten_user']) ? trim($_GET['ten_user']) : '';

        $result = $this->users_model->Users_find($ma_user, $ten_user);
        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy danh sách user']);
        }

        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
        if ($format === 'xlsx') {
            $this->exportXlsx($users);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy danh sách user thành công',
            'total' => count($users),
            'filters' => [
                'ma_user' => $ma_user,
                'ten_user' => $ten_user
            ],
            'data' => $users
        ]);
    }

    private function exportXlsx($users) {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachUsers');

        $sheet->setCellValue('A1', 'Mã User');
        $sheet->setCellValue('B1', 'Tên User');
        $sheet->setCellValue('C1', 'Họ tên');
        $sheet->setCellValue('D1', 'Password');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'Phân quyền');
        $sheet->setCellValue('G1', 'Số điện thoại');
        $sheet->setCellValue('H1', 'Avatar');
        $sheet->setCellValue('I1', 'Ngày tạo');

        $rowCount = 2;
        foreach ($users as $row) {
            $sheet->setCellValue('A' . $rowCount, $row['ma_user'] ?? '');
            $sheet->setCellValue('B' . $rowCount, $row['ten_user'] ?? '');
            $sheet->setCellValue('C' . $rowCount, $row['full_name'] ?? '');
            $sheet->setCellValue('D' . $rowCount, $row['password'] ?? '');
            $sheet->setCellValue('E' . $rowCount, $row['email'] ?? '');
            $sheet->setCellValue('F' . $rowCount, $row['phan_quyen'] ?? '');
            $sheet->setCellValue('G' . $rowCount, $row['so_dien_thoai'] ?? '');
            $sheet->setCellValue('H' . $rowCount, $row['avatar'] ?? '');
            $sheet->setCellValue('I' . $rowCount, $row['ngay_tao'] ?? '');
            $rowCount++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="DanhSachUsers.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $writer->save('php://output');
        exit;
    }

    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã user']);
        }

        $result = $this->users_model->Users_getById($id);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $this->sendResponse(200, ['success' => true, 'data' => $user]);
        }

        $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy user có mã: ' . $id]);
    }

    public function search($ma_user = null, $ten_user = null) {
        if ($ma_user !== null && trim($ma_user) !== '') {
            $_GET['ma_user'] = trim($ma_user);
        }

        if ($ten_user !== null && trim($ten_user) !== '') {
            $_GET['ten_user'] = trim($ten_user);
        }

        $this->get_all();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->parseInputData();

        $ma_user = trim($data['ma_user'] ?? $data['txtMauser'] ?? '');
        $ten_user = trim($data['ten_user'] ?? $data['txtTenuser'] ?? '');
        $full_name = trim($data['full_name'] ?? $data['txtHoten'] ?? '');
        $password = trim($data['password'] ?? $data['txtPassword'] ?? '');
        $email = trim($data['email'] ?? $data['txtEmail'] ?? '');
        $phan_quyen = $this->normalizeRole($data['phan_quyen'] ?? $data['ddlPhanquyen'] ?? 'khach_hang');
        $so_dien_thoai = trim($data['so_dien_thoai'] ?? $data['txtSoDienThoai'] ?? '');

        if ($ma_user === '' || $ten_user === '' || $password === '' || $email === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_user, ten_user, password và email']);
        }

        if (!$this->isValidRole($phan_quyen)) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Phân quyền không hợp lệ']);
        }

        if ($this->users_model->checktrungMaUser($ma_user)) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã user đã tồn tại']);
        }

        $checkEmail = $this->users_model->checktrungEmail($email, null);
        if ($checkEmail && mysqli_num_rows($checkEmail) > 0) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Email đã được sử dụng']);
        }

        if ($so_dien_thoai !== '' && $this->users_model->checkTrungSoDienThoai($so_dien_thoai)) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Số điện thoại đã được sử dụng']);
        }

        $avatar = '';
        $avatarUpload = $this->getAvatarUpload();
        if ($avatarUpload) {
            $uploadResult = $this->saveAvatar($avatarUpload);
            if (!$uploadResult['success']) {
                $this->sendResponse(400, ['success' => false, 'message' => $uploadResult['message']]);
            }
            $avatar = $uploadResult['avatar'];
        }

        $inserted = $this->users_model->users_ins(
            $ma_user,
            $ten_user,
            $full_name,
            $password,
            $email,
            $phan_quyen,
            $so_dien_thoai,
            $avatar
        );

        if (!$inserted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể thêm user',
                'error' => mysqli_error($this->users_model->con)
            ]);
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Thêm user thành công',
            'data' => [
                'ma_user' => $ma_user,
                'ten_user' => $ten_user,
                'full_name' => $full_name,
                'password' => $password,
                'email' => $email,
                'phan_quyen' => $phan_quyen,
                'so_dien_thoai' => $so_dien_thoai,
                'avatar' => $avatar
            ]
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT/PATCH/POST']);
        }

        $data = $this->parseInputData();
        $ma_user = trim($id ?? $data['ma_user'] ?? $data['txtMauser'] ?? '');

        if ($ma_user === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã user']);
        }

        $currentResult = $this->users_model->Users_getById($ma_user);
        if (!$currentResult || mysqli_num_rows($currentResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy user có mã: ' . $ma_user]);
        }

        $currentUser = mysqli_fetch_assoc($currentResult);

        $ten_user = trim($data['ten_user'] ?? $data['txtTenuser'] ?? ($currentUser['ten_user'] ?? ''));
        $full_name = trim($data['full_name'] ?? $data['txtHoten'] ?? ($currentUser['full_name'] ?? ''));
        $password = trim($data['password'] ?? $data['txtPassword'] ?? ($currentUser['password'] ?? ''));
        $email = trim($data['email'] ?? $data['txtEmail'] ?? ($currentUser['email'] ?? ''));
        $phan_quyen = $this->normalizeRole($data['phan_quyen'] ?? $data['ddlPhanquyen'] ?? ($currentUser['phan_quyen'] ?? 'khach_hang'));
        $so_dien_thoai = trim($data['so_dien_thoai'] ?? $data['txtSoDienThoai'] ?? ($currentUser['so_dien_thoai'] ?? ''));

        if ($ten_user === '' || $password === '' || $email === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ten_user, password và email']);
        }

        if (!$this->isValidRole($phan_quyen)) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Phân quyền không hợp lệ']);
        }

        $checkEmail = $this->users_model->checktrungEmail($email, $ma_user);
        if ($checkEmail && mysqli_num_rows($checkEmail) > 0) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Email đã được sử dụng bởi tài khoản khác']);
        }

        if ($so_dien_thoai !== '' && $this->users_model->checkTrungSoDienThoai($so_dien_thoai, $ma_user)) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Số điện thoại đã được sử dụng bởi tài khoản khác']);
        }

        $avatar = $currentUser['avatar'] ?? '';
        $avatarUpload = $this->getAvatarUpload();
        if ($avatarUpload) {
            $uploadResult = $this->saveAvatar($avatarUpload, $avatar);
            if (!$uploadResult['success']) {
                $this->sendResponse(400, ['success' => false, 'message' => $uploadResult['message']]);
            }
            $avatar = $uploadResult['avatar'];
        }

        $updated = $this->users_model->Users_update(
            $ma_user,
            $ten_user,
            $full_name,
            $password,
            $email,
            $phan_quyen,
            $so_dien_thoai,
            $avatar
        );

        if (!$updated) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật user',
                'error' => mysqli_error($this->users_model->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật user thành công',
            'data' => [
                'ma_user' => $ma_user,
                'ten_user' => $ten_user,
                'full_name' => $full_name,
                'password' => $password,
                'email' => $email,
                'phan_quyen' => $phan_quyen,
                'so_dien_thoai' => $so_dien_thoai,
                'avatar' => $avatar
            ]
        ]);
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã user']);
        }

        if (!$this->users_model->checktrungMaUser($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy user có mã: ' . $id]);
        }

        $deleted = $this->users_model->Users_delete($id);
        if (!$deleted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể xóa user',
                'error' => mysqli_error($this->users_model->con)
            ]);
        }

        $this->sendResponse(200, ['success' => true, 'message' => 'Xóa user thành công']);
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

        $created = 0;
        $skipped_empty = 0;
        $duplicated_codes = [];
        $failed_rows = [];

        for ($i = 2; $i <= count($sheetData); $i++) {
            $ma_user = isset($sheetData[$i]['A']) ? trim((string)$sheetData[$i]['A']) : '';
            $ten_user = isset($sheetData[$i]['B']) ? trim((string)$sheetData[$i]['B']) : '';

            // Ho tro 2 dinh dang cot:
            // 1) Mau import cu: C=password, D=email, E=phan_quyen, F=full_name, G=so_dien_thoai, H=avatar
            // 2) File export moi: C=full_name, D=password, E=email, F=phan_quyen, G=so_dien_thoai, H=avatar
            $password_template = isset($sheetData[$i]['C']) ? trim((string)$sheetData[$i]['C']) : '';
            $email_template = isset($sheetData[$i]['D']) ? trim((string)$sheetData[$i]['D']) : '';
            $role_template = isset($sheetData[$i]['E']) ? trim((string)$sheetData[$i]['E']) : '';

            $full_name_export = isset($sheetData[$i]['C']) ? trim((string)$sheetData[$i]['C']) : '';
            $password_export = isset($sheetData[$i]['D']) ? trim((string)$sheetData[$i]['D']) : '';
            $email_export = isset($sheetData[$i]['E']) ? trim((string)$sheetData[$i]['E']) : '';
            $role_export = isset($sheetData[$i]['F']) ? trim((string)$sheetData[$i]['F']) : '';

            $use_export_layout = false;

            // Neu cot E khong phai role hop le nhung cot F la role hop le -> la file export
            if (!$this->isValidRole($this->normalizeRole($role_template))
                && $this->isValidRole($this->normalizeRole($role_export))) {
                $use_export_layout = true;
            }

            // Neu cot E trong giong email thi uu tien layout export
            if (strpos($role_template, '@') !== false) {
                $use_export_layout = true;
            }

            if ($use_export_layout) {
                $password = $password_export;
                $email = $email_export;
                $phan_quyen = $this->normalizeRole($role_export);
                $full_name = $full_name_export;
            } else {
                $password = $password_template;
                $email = $email_template;
                $phan_quyen = $this->normalizeRole($role_template);
                $full_name = isset($sheetData[$i]['F']) ? trim((string)$sheetData[$i]['F']) : '';
            }

            $so_dien_thoai = isset($sheetData[$i]['G']) ? trim((string)$sheetData[$i]['G']) : '';
            $avatar = isset($sheetData[$i]['H']) ? trim((string)$sheetData[$i]['H']) : '';

            if ($ma_user === '') {
                $skipped_empty++;
                continue;
            }

            if ($ten_user === '' || $password === '' || $email === '') {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_user' => $ma_user,
                    'reason' => 'Thiếu tên user, password hoặc email'
                ];
                continue;
            }

            if (!$this->isValidRole($phan_quyen)) {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_user' => $ma_user,
                    'reason' => 'Phân quyền không hợp lệ'
                ];
                continue;
            }

            if ($this->users_model->checktrungMaUser($ma_user)) {
                $duplicated_codes[] = $ma_user;
                continue;
            }

            $checkEmail = $this->users_model->checktrungEmail($email, null);
            if ($checkEmail && mysqli_num_rows($checkEmail) > 0) {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_user' => $ma_user,
                    'reason' => 'Email đã được sử dụng'
                ];
                continue;
            }

            if ($so_dien_thoai !== '' && $this->users_model->checkTrungSoDienThoai($so_dien_thoai)) {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_user' => $ma_user,
                    'reason' => 'Số điện thoại đã được sử dụng'
                ];
                continue;
            }

            $inserted = $this->users_model->users_ins(
                $ma_user,
                $ten_user,
                $full_name,
                $password,
                $email,
                $phan_quyen,
                $so_dien_thoai,
                $avatar
            );

            if ($inserted) {
                $created++;
            } else {
                $failed_rows[] = [
                    'row' => $i,
                    'ma_user' => $ma_user,
                    'reason' => mysqli_error($this->users_model->con)
                ];
            }
        }

        $response = [
            'success' => true,
            'message' => 'Import user hoàn tất',
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