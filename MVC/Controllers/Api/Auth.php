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
                'message' => 'Chưa xác thực. Vui lòng đăng nhập'
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy thông tin tài khoản thành công',
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
                'message' => 'Vui lòng cung cấp tên đăng nhập/email và mật khẩu',
                'error' => 'Vui lòng cung cấp tên đăng nhập/email và mật khẩu'
            ]);
        }

        $result = $this->users->validateUser($username, $password);
        if (!$result || mysqli_num_rows($result) === 0) {
            $this->sendResponse(401, [
                'success' => false,
                'message' => 'Tên đăng nhập hoặc mật khẩu không đúng',
                'error' => 'Tên đăng nhập hoặc mật khẩu không đúng'
            ]);
        }

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['ma_user'];
        $_SESSION['user_name'] = $user['ten_user'];
        $_SESSION['user_role'] = $user['phan_quyen'];
        session_write_close();

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Đăng nhập thành công',
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
        $fullName = trim((string)($data['full_name'] ?? $data['fullname'] ?? ''));
        $phone = trim((string)($data['phone'] ?? $data['so_dien_thoai'] ?? ''));
        $password = trim((string)($data['password'] ?? ''));
        $confirmPassword = trim((string)($data['confirm_password'] ?? ''));

        if (
            $fullName === ''
            || $email === ''
            || $phone === ''
            || $username === ''
            || $password === ''
            || $confirmPassword === ''
        ) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ họ tên, email, số điện thoại, tên tài khoản, mật khẩu và mật khẩu xác nhận',
                'error' => 'Vui lòng nhập đầy đủ họ tên, email, số điện thoại, tên tài khoản, mật khẩu và mật khẩu xác nhận'
            ]);
        }

        if ($confirmPassword !== '' && $confirmPassword !== $password) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Mật khẩu xác nhận không khớp',
                'error' => 'Mật khẩu xác nhận không khớp'
            ]);
        }

        $existingUser = $this->users->getUserByUsername($username);
        if ($existingUser) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Tên đăng nhập đã tồn tại',
                'error' => 'Tên đăng nhập đã tồn tại'
            ]);
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Email không đúng định dạng',
                'error' => 'Email không đúng định dạng'
            ]);
        }

        $existingEmail = $this->users->checktrungEmail($email, '');
        if ($existingEmail && mysqli_num_rows($existingEmail) > 0) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Email đã được sử dụng',
                'error' => 'Email đã được sử dụng'
            ]);
        }

        if ($this->users->checkTrungSoDienThoai($phone)) {
            $this->sendResponse(409, [
                'success' => false,
                'message' => 'Số điện thoại đã được sử dụng',
                'error' => 'Số điện thoại đã được sử dụng'
            ]);
        }

        $created = $this->users->createUser($username, $email, $fullName, $password, 'khach_hang', $phone);
        if (!$created) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Đăng ký thất bại',
                'error' => 'Đăng ký thất bại'
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
            'message' => 'Đăng ký thành công',
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
            'message' => 'Đăng xuất thành công'
        ]);
    }
}
?>
