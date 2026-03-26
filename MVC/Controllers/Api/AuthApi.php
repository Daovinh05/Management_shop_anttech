<?php
/**
 * Auth API Controller
 * Xử lý các endpoint xác thực: login, register, logout, profile
 */
require_once __DIR__ . '/../../Core/ApiController.php';

class AuthApi extends ApiController
{
    private $userModel;

    public function __construct()
    {
        // Clear any output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        parent::__construct();
        include_once __DIR__ . '/../../Models/Users_m.php';
        $this->userModel = new Users_m();
    }

    /**
     * GET /api/auth
     * Lấy thông tin user hiện tại
     */
    public function getAll()
    {
        $user = $this->requireAuth();
        
        $this->success([
            'user_id' => $user['user_id'],
            'username' => $user['user_name'],
            'email' => $user['email'] ?? '',
            'full_name' => $user['full_name'] ?? '',
            'phone' => $user['so_dien_thoai'] ?? '',
            'role' => $user['user_role'] ?? 'khach_hang'
        ], 'User information retrieved successfully');
    }

    /**
     * POST /api/auth/login
     * Đăng nhập
     * Body: { "username": "...", "password": "..." }
     */
    public function login()
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            if (empty($this->data['username']) || empty($this->data['password'])) {
                $this->error('Username and password are required', 400, 'MISSING_CREDENTIALS');
            }

            $username = $this->data['username'];
            $password = $this->data['password'];

            // Validate user
            $result = $this->userModel->validateUser($username, $password);

            if (!$result) {
                $this->error('Database query failed', 500, 'DATABASE_ERROR');
            }

            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                // Lưu session PHP (để App.php check auth được)
                $_SESSION['user_id'] = $user['ma_user'];
                $_SESSION['user_name'] = $user['ten_user'];
                $_SESSION['user_role'] = $user['phan_quyen'];

                // Tạo token API
                $userData = [
                    'user_id' => $user['ma_user'],
                    'user_name' => $user['ten_user'],
                    'email' => $user['email'] ?? '',
                    'full_name' => $user['full_name'] ?? '',
                    'so_dien_thoai' => $user['so_dien_thoai'] ?? '',
                    'user_role' => $user['phan_quyen']
                ];

                $token = $this->generateToken($userData);

                $this->success([
                    'token' => $token,
                    'user' => $userData,
                    'role' => $user['phan_quyen']
                ], 'Login successful', 200);
            } else {
                $this->error('Invalid username or password', 401, 'INVALID_CREDENTIALS');
            }
        } catch (Exception $e) {
            $this->error('Server error: ' . $e->getMessage(), 500, 'SERVER_ERROR');
        }
    }

    /**
     * POST /api/auth/register
     * Đăng ký user mới
     * Body: { "username": "...", "password": "...", "confirm_password": "...", "email": "...", "phone": "...", "fullname": "..." }
     */
    public function register()
    {
        // Kiểm tra dữ liệu bắt buộc
        if (empty($this->data['username']) || empty($this->data['password'])) {
            $this->error('Username and password are required', 400, 'MISSING_REQUIRED_FIELDS');
        }

        $username = $this->data['username'];
        $password = $this->data['password'];
        $confirmPassword = $this->data['confirm_password'] ?? '';
        $email = $this->data['email'] ?? '';
        $phone = $this->data['phone'] ?? '';
        $fullName = $this->data['fullname'] ?? $username;

        // Kiểm tra mật khẩu xác nhận
        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match', 400, 'PASSWORD_MISMATCH');
        }

        // Kiểm tra username đã tồn tại
        $existingUser = $this->userModel->getUserByUsername($username);
        if ($existingUser && mysqli_num_rows($existingUser) > 0) {
            $this->error('Username already exists', 409, 'USERNAME_EXISTS');
        }

        // Nếu không có email, tạo email mặc định
        if (empty($email)) {
            $email = $username . '@gmail.com';
        } else {
            // Kiểm tra email đã tồn tại
            $existingEmail = $this->userModel->checktrungEmail($email, '');
            if ($existingEmail && mysqli_num_rows($existingEmail) > 0) {
                $this->error('Email already exists', 409, 'EMAIL_EXISTS');
            }
        }

        // Kiểm tra phone đã tồn tại
        if (!empty($phone)) {
            $existingPhone = $this->userModel->checkTrungSoDienThoai($phone);
            if ($existingPhone) {
                $this->error('Phone number already exists', 409, 'PHONE_EXISTS');
            }
        }

        // Tạo user mới
        $result = $this->userModel->createUser(
            $username,
            $email,
            $fullName,
            $password,
            'khach_hang',
            $phone
        );

        if ($result) {
            $this->success([
                'username' => $username,
                'message' => 'Registration successful. You can now login.'
            ], 'Registration successful', 201);
        } else {
            $this->error('Registration failed. Please try again.', 500, 'REGISTRATION_FAILED');
        }
    }

    /**
     * POST /api/auth/logout
     * Đăng xuất
     */
    public function logout()
    {
        $headers = $this->getHeaders();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
        if (!empty($authHeader)) {
            $token = str_replace('Bearer ', '', $authHeader);
            $this->revokeToken($token);
        }

        // Destroy session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();

        $this->success([], 'Logout successful');
    }

    /**
     * PUT /api/auth/profile
     * Cập nhật thông tin user
     * Body: { "email": "...", "phone": "...", "full_name": "..." }
     */
    public function updateProfile()
    {
        $user = $this->requireAuth();
        $userId = $user['user_id'];

        $email = $this->data['email'] ?? '';
        $phone = $this->data['phone'] ?? '';
        $fullName = $this->data['full_name'] ?? '';

        // Kiểm tra email nếu có cập nhật
        if (!empty($email) && $email !== $user['email']) {
            $existingEmail = $this->userModel->checktrungEmail($email, $userId);
            if ($existingEmail && mysqli_num_rows($existingEmail) > 0) {
                $this->error('Email already exists', 409, 'EMAIL_EXISTS');
            }
        }

        // Kiểm tra phone nếu có cập nhật
        if (!empty($phone) && $phone !== $user['phone']) {
            $existingPhone = $this->userModel->checkTrungSoDienThoai($phone);
            if ($existingPhone) {
                $this->error('Phone number already exists', 409, 'PHONE_EXISTS');
            }
        }

        // Cập nhật
        $result = $this->userModel->updateUser($userId, [
            'email' => $email,
            'so_dien_thoai' => $phone,
            'full_name' => $fullName
        ]);

        if ($result) {
            $this->success([
                'user_id' => $userId,
                'email' => $email,
                'phone' => $phone,
                'full_name' => $fullName
            ], 'Profile updated successfully');
        } else {
            $this->error('Failed to update profile', 500, 'UPDATE_FAILED');
        }
    }

    /**
     * PUT /api/auth/change-password
     * Đổi mật khẩu
     * Body: { "old_password": "...", "new_password": "...", "confirm_password": "..." }
     */
    public function changePassword()
    {
        $user = $this->requireAuth();
        $userId = $user['user_id'];

        $oldPassword = $this->data['old_password'] ?? '';
        $newPassword = $this->data['new_password'] ?? '';
        $confirmPassword = $this->data['confirm_password'] ?? '';

        // Kiểm tra mật khẩu cũ
        $result = $this->userModel->validateUser($user['username'], $oldPassword);
        if (!$result || mysqli_num_rows($result) === 0) {
            $this->error('Current password is incorrect', 400, 'WRONG_PASSWORD');
        }

        // Kiểm tra mật khẩu mới
        if (strlen($newPassword) < 6) {
            $this->error('New password must be at least 6 characters', 400, 'WEAK_PASSWORD');
        }

        if ($newPassword !== $confirmPassword) {
            $this->error('New passwords do not match', 400, 'PASSWORD_MISMATCH');
        }

        // Cập nhật mật khẩu
        $updateResult = $this->userModel->changePassword($userId, $newPassword);

        if ($updateResult) {
            $this->success([], 'Password changed successfully');
        } else {
            $this->error('Failed to change password', 500, 'CHANGE_PASSWORD_FAILED');
        }
    }
}
