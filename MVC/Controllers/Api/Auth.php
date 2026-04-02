<?php
class Auth extends api_controller {
    private $users;

    public function __construct() {
        parent::__construct();
        $this->users = $this->model('Users_m');
    }

    private function parseInputData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            return $_POST;
        }
        return $this->getJsonInput();
    }

    private function getCurrentUserBySession() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            return null;
        }

        $ma_user = trim((string)$_SESSION['user_id']);
        $result = $this->users->Users_getById($ma_user);
        if (!$result || mysqli_num_rows($result) === 0) {
            return null;
        }

        return mysqli_fetch_assoc($result);
    }

    private function sanitizeUser($user) {
        return [
            'ma_user' => $user['ma_user'] ?? null,
            'ten_user' => $user['ten_user'] ?? null,
            'full_name' => $user['full_name'] ?? null,
            'email' => $user['email'] ?? null,
            'phan_quyen' => $user['phan_quyen'] ?? null,
            'so_dien_thoai' => $user['so_dien_thoai'] ?? null,
            'avatar' => $user['avatar'] ?? null,
            'ngay_tao' => $user['ngay_tao'] ?? null
        ];
    }

    private function resolveRedirectByRole($role) {
        if ($role === 'admin') {
            return BASE_URL . 'Quanly';
        }
        if ($role === 'nhan_vien') {
            return BASE_URL . 'Staff';
        }
        return BASE_URL . 'Khachhang';
    }

    // GET /Api/Auth/profile
    public function profile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use GET'
            ]);
        }

        $user = $this->getCurrentUserBySession();
        if (!$user) {
            $this->sendResponse(401, [
                'success' => false,
                'message' => 'Unauthorized. Vui long dang nhap'
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay thong tin tai khoan thanh cong',
            'data' => $this->sanitizeUser($user)
        ]);
    }

    // POST /Api/Auth/login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use POST'
            ]);
        }

        $data = $this->parseInputData();
        $username = trim((string)($data['username'] ?? $data['ten_user'] ?? $data['email'] ?? ''));
        $password = trim((string)($data['password'] ?? ''));

        if ($username === '' || $password === '') {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Vui long cung cap username/email va password',
                'error' => 'Vui long cung cap username/email va password'
            ]);
        }

        $result = $this->users->validateUser($username, $password);
        if (!$result || mysqli_num_rows($result) === 0) {
            $this->sendResponse(401, [
                'success' => false,
                'message' => 'Ten dang nhap hoac mat khau khong dung',
                'error' => 'Ten dang nhap hoac mat khau khong dung'
            ]);
        }

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['ma_user'];
        $_SESSION['user_name'] = $user['ten_user'];
        $_SESSION['user_role'] = $user['phan_quyen'];
        session_write_close();

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Dang nhap thanh cong',
            'redirect' => $this->resolveRedirectByRole($user['phan_quyen'] ?? ''),
            'data' => $this->sanitizeUser($user)
        ]);
    }

    // POST /Api/Auth/register
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use POST'
            ]);
        }

        $data = $this->parseInputData();

        $username = trim((string)($data['username'] ?? $data['ten_user'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));
        $fullName = trim((string)($data['full_name'] ?? $data['fullname'] ?? $username));
        $phone = trim((string)($data['phone'] ?? $data['so_dien_thoai'] ?? ''));
        $password = trim((string)($data['password'] ?? ''));
        $confirmPassword = trim((string)($data['confirm_password'] ?? ''));

        if ($username === '' || $password === '') {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Vui long cung cap username va password',
                'error' => 'Vui long cung cap username va password'
            ]);
        }

        if ($confirmPassword !== '' && $confirmPassword !== $password) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Mat khau xac nhan khong khop',
                'error' => 'Mat khau xac nhan khong khop'
            ]);
        }

        $existingUser = $this->users->getUserByUsername($username);
        if ($existingUser) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Ten dang nhap da ton tai',
                'error' => 'Ten dang nhap da ton tai'
            ]);
        }

        if ($email === '') {
            $email = $username . '@gmail.com';
        } else {
            $existingEmail = $this->users->checktrungEmail($email, '');
            if ($existingEmail && mysqli_num_rows($existingEmail) > 0) {
                $this->sendResponse(409, [
                    'success' => false,
                    'message' => 'Email da duoc su dung',
                    'error' => 'Email da duoc su dung'
                ]);
            }
        }

        if ($phone !== '' && $this->users->checkTrungSoDienThoai($phone)) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'So dien thoai da duoc su dung',
                'error' => 'So dien thoai da duoc su dung'
            ]);
        }

        $created = $this->users->createUser($username, $email, $fullName, $password, 'khach_hang', $phone);
        if (!$created) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Dang ky that bai',
                'error' => 'Dang ky that bai'
            ]);
        }

        $loginResult = $this->users->validateUser($username, $password);
        $createdUser = ($loginResult && mysqli_num_rows($loginResult) > 0) ? mysqli_fetch_assoc($loginResult) : null;

        if ($createdUser) {
            $_SESSION['user_id'] = $createdUser['ma_user'];
            $_SESSION['user_name'] = $createdUser['ten_user'];
            $_SESSION['user_role'] = $createdUser['phan_quyen'];
            session_write_close();
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Dang ky thanh cong',
            'redirect' => BASE_URL . 'Home',
            'data' => $createdUser ? $this->sanitizeUser($createdUser) : null
        ]);
    }

    // POST /Api/Auth/logout
    public function logout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use POST'
            ]);
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Dang xuat thanh cong'
        ]);
    }
}
?>