<?php
/**
 * API Base Controller
 * Cung cấp các phương thức chuẩn cho RESTful API
 */
class ApiController
{
    protected $method = '';
    protected $endpoint = '';
    protected $verb = '';
    protected $args = [];
    protected $file = null;
    protected $user = null;
    protected $lang = 'en';
    protected $data = null;

    public function __construct()
    {
        // Clear any previous output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Khởi tạo headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        
        // Handle preflight request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Lấy thông tin request
        $this->verb = $_SERVER['REQUEST_METHOD'];
        $this->args = $_GET;
        
        // Parse JSON body nếu có
        $this->parseRequest();
        
        // Xác thực nếu cần
        $this->authenticate();
    }

    /**
     * Parse request body
     */
    protected function parseRequest()
    {
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
        
        if (stripos($contentType, 'application/json') === 0) {
            $input = file_get_contents('php://input');
            $this->data = json_decode($input, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->data = null;
            }
        } else {
            $this->data = $_POST;
        }
    }

    /**
     * Xác thực người dùng qua token
     */
    protected function authenticate()
    {
        $headers = $this->getHeaders();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
        if (empty($authHeader)) {
            // Không có token, kiểm tra session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $this->user = isset($_SESSION['user_id']) ? $_SESSION : null;
        } else {
            // Có token, giải mã và xác thực
            $token = str_replace('Bearer ', '', $authHeader);
            $this->user = $this->verifyToken($token);
        }
    }

    /**
     * Lấy request headers theo cách tương thích với nhiều môi trường chạy PHP
     */
    protected function getHeaders()
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if (is_array($headers)) {
                return $headers;
            }
        }

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$name] = $value;
            }
        }

        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        }

        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $headers['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
        }

        return $headers;
    }

    /**
     * Verify JWT token
     */
    protected function verifyToken($token)
    {
        // Simple token verification (có thể nâng cấp lên JWT)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra token có trong session không
        if (isset($_SESSION['api_tokens'][$token])) {
            return $_SESSION['api_tokens'][$token];
        }
        
        return null;
    }

    /**
     * Tạo token mới cho user
     */
    protected function generateToken($userData)
    {
        $token = bin2hex(random_bytes(32));
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Lưu token vào session
        if (!isset($_SESSION['api_tokens'])) {
            $_SESSION['api_tokens'] = [];
        }
        $_SESSION['api_tokens'][$token] = $userData;
        
        return $token;
    }

    /**
     * Xóa token (logout)
     */
    protected function revokeToken($token)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['api_tokens'][$token])) {
            unset($_SESSION['api_tokens'][$token]);
            return true;
        }
        return false;
    }

    /**
     * Yêu cầu xác thực
     */
    protected function requireAuth()
    {
        if (!$this->user) {
            $this->response([
                'success' => false,
                'message' => 'Unauthorized. Please login first.',
                'error_code' => 'UNAUTHORIZED'
            ], 401);
            exit();
        }
        return $this->user;
    }

    /**
     * Gửi response JSON
     */
    protected function response($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Response thành công
     */
    protected function success($data, $message = 'Success', $statusCode = 200)
    {
        $this->response([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Response lỗi
     */
    protected function error($message, $statusCode = 400, $errorCode = 'BAD_REQUEST')
    {
        $this->response([
            'success' => false,
            'message' => $message,
            'error_code' => $errorCode
        ], $statusCode);
    }

    /**
     * Response không tìm thấy
     */
    protected function notFound($message = 'Resource not found')
    {
        $this->error($message, 404, 'NOT_FOUND');
    }

    /**
     * Response dữ liệu phân trang
     */
    protected function paginatedResponse($data, $total, $page, $limit, $message = 'Success')
    {
        $this->success([
            'items' => $data,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ], $message);
    }
}
