<?php
class Profile extends api_controller {
    private $users;
    private $dc;

    public function __construct() {
        parent::__construct();
        $this->users = $this->model('Users_m');
        $this->dc = $this->model('DiaChiGiaoHang_m');
    }

    private function requireAuthUser() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $this->sendResponse(401, [
                'success' => false,
                'message' => 'Chưa xác thực. Vui lòng đăng nhập để tiếp tục'
            ]);
        }

        return trim((string)$_SESSION['user_id']);
    }

    private function getCurrentUser($ma_user) {
        $userResult = $this->users->Users_getById($ma_user);
        if (!$userResult || mysqli_num_rows($userResult) === 0) {
            return null;
        }

        return mysqli_fetch_assoc($userResult);
    }

    private function getPrimaryAddress($ma_user) {
        $addressResult = $this->dc->DiaChiGiaoHang_getByUser($ma_user);
        if (!$addressResult || mysqli_num_rows($addressResult) === 0) {
            return null;
        }

        return mysqli_fetch_assoc($addressResult);
    }

    private function normalizeImageExtension($mime) {
        $mime = strtolower((string)$mime);
        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            return 'jpg';
        }
        if ($mime === 'image/png') {
            return 'png';
        }
        if ($mime === 'image/gif') {
            return 'gif';
        }
        if ($mime === 'image/webp') {
            return 'webp';
        }
        return null;
    }

    private function saveAvatarBase64($ma_user, $avatarBase64, $currentAvatar = null) {
        if (!is_string($avatarBase64) || trim($avatarBase64) === '') {
            return [
                'success' => true,
                'avatar' => $currentAvatar
            ];
        }

        $avatarBase64 = trim($avatarBase64);
        $mime = null;
        $encoded = $avatarBase64;

        if (preg_match('/^data:(image\\/[a-zA-Z0-9.+-]+);base64,(.+)$/', $avatarBase64, $matches)) {
            $mime = strtolower($matches[1]);
            $encoded = $matches[2];
        }

        $extension = $this->normalizeImageExtension($mime ?: 'image/jpeg');
        if ($extension === null) {
            return [
                'success' => false,
                'message' => 'Định dạng ảnh không hợp lệ. Chỉ hỗ trợ JPG, PNG, GIF, WEBP'
            ];
        }

        $binary = base64_decode($encoded, true);
        if ($binary === false) {
            return [
                'success' => false,
                'message' => 'Dữ liệu ảnh đại diện không hợp lệ'
            ];
        }

        if (strlen($binary) > 5 * 1024 * 1024) {
            return [
                'success' => false,
                'message' => 'Anh dai dien vuot qua gioi han 5MB'
            ];
        }

        $uploadDir = __DIR__ . '/../../../Public/Pictures/users/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        if (!is_dir($uploadDir)) {
            return [
                'success' => false,
                'message' => 'Không thể tạo thư mục lưu ảnh đại diện'
            ];
        }

        $safeUser = preg_replace('/[^a-zA-Z0-9_-]/', '', $ma_user);
        $fileName = $safeUser . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
        $filePath = $uploadDir . $fileName;

        if (file_put_contents($filePath, $binary) === false) {
            return [
                'success' => false,
                'message' => 'Không thể lưu ảnh đại diện'
            ];
        }

        if (!empty($currentAvatar) && $currentAvatar !== 'avatar.png' && $currentAvatar !== $fileName) {
            $oldPath = $uploadDir . $currentAvatar;
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return [
            'success' => true,
            'avatar' => $fileName
        ];
    }

    private function sanitizeUserPayload($user, $address = null) {
        $avatar = $user['avatar'] ?? null;
        $avatarUrl = null;
        if (!empty($avatar)) {
            $avatarUrl = BASE_URL . 'Public/Pictures/users/' . $avatar;
        }

        return [
            'ma_user' => $user['ma_user'] ?? null,
            'ten_user' => $user['ten_user'] ?? null,
            'full_name' => $user['full_name'] ?? null,
            'email' => $user['email'] ?? null,
            'so_dien_thoai' => $user['so_dien_thoai'] ?? null,
            'avatar' => $avatar,
            'avatar_url' => $avatarUrl,
            'ngay_tao' => $user['ngay_tao'] ?? null,
            'dia_chi' => $address['dia_chi'] ?? null,
            'so_dien_thoai_nhan' => $address['so_dien_thoai'] ?? null,
            'ho_ten_nhan' => $address['ho_ten'] ?? null
        ];
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use GET'
            ]);
        }

        $ma_user = $this->requireAuthUser();
        $user = $this->getCurrentUser($ma_user);
        if (!$user) {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Không tìm thấy thông tin tài khoản'
            ]);
        }

        $address = $this->getPrimaryAddress($ma_user);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy thông tin tài khoản thành công',
            'data' => $this->sanitizeUserPayload($user, $address)
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use PATCH or PUT'
            ]);
        }

        $ma_user = $this->requireAuthUser();
        $user = $this->getCurrentUser($ma_user);
        if (!$user) {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Không tìm thấy thông tin tài khoản'
            ]);
        }

        $payload = $this->getJsonInput();
        $full_name = trim((string)($payload['full_name'] ?? $user['full_name'] ?? ''));
        $so_dien_thoai = trim((string)($payload['so_dien_thoai'] ?? $user['so_dien_thoai'] ?? ''));
        $email = trim((string)($payload['email'] ?? $user['email'] ?? ''));
        $avatarBase64 = $payload['avatar_base64'] ?? null;

        if ($full_name === '' || $so_dien_thoai === '' || $email === '') {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ họ tên, số điện thoại và email'
            ]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Email không hợp lệ'
            ]);
        }

        if (!preg_match('/^[0-9]{9,11}$/', $so_dien_thoai)) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Số điện thoại không hợp lệ'
            ]);
        }

        $dupEmail = $this->users->checktrungEmail($email, $ma_user);
        if ($dupEmail && mysqli_num_rows($dupEmail) > 0) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Email da ton tai'
            ]);
        }

        $dupPhone = $this->users->checkTrungSoDienThoai($so_dien_thoai, $ma_user);
        if ($dupPhone && mysqli_num_rows($dupPhone) > 0) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Số điện thoại đã tồn tại'
            ]);
        }

        $avatar = $user['avatar'] ?? null;
        if (!empty($avatarBase64)) {
            $upload = $this->saveAvatarBase64($ma_user, $avatarBase64, $avatar);
            if (!$upload['success']) {
                $this->sendResponse(422, [
                    'success' => false,
                    'message' => $upload['message']
                ]);
            }
            $avatar = $upload['avatar'];
        }

        $updated = $this->users->Users_update_profile($ma_user, $full_name, $so_dien_thoai, $email, null, $avatar);
        if (!$updated) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể cập nhật thông tin tài khoản'
            ]);
        }

        $latestUser = $this->getCurrentUser($ma_user);
        $address = $this->getPrimaryAddress($ma_user);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật thông tin tài khoản thành công',
            'data' => $this->sanitizeUserPayload($latestUser, $address)
        ]);
    }

    public function password() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use PATCH or PUT'
            ]);
        }

        $ma_user = $this->requireAuthUser();
        $user = $this->getCurrentUser($ma_user);
        if (!$user) {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Không tìm thấy thông tin tài khoản'
            ]);
        }

        $payload = $this->getJsonInput();
        $currentPassword = trim((string)($payload['current_password'] ?? ''));
        $newPassword = trim((string)($payload['new_password'] ?? ''));
        $confirmPassword = trim((string)($payload['confirm_password'] ?? ''));

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin mật khẩu'
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp'
            ]);
        }

        if ((string)($user['password'] ?? '') !== $currentPassword) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Mật khẩu hiện tại không đúng'
            ]);
        }

        $updated = $this->users->Users_update_password($ma_user, $newPassword);
        if (!$updated) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể đổi mật khẩu'
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}
?>
