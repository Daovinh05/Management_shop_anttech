<?php
/**
 * Cấu hình ứng dụng
 * Tự động detect base URL cho cả local và production
 */

// Tự động detect base URL
function getBaseUrl()
{
    $baseUrlFromEnv = getenv('APP_BASE_URL');
    if (is_string($baseUrlFromEnv) && trim($baseUrlFromEnv) !== '') {
        return rtrim(trim($baseUrlFromEnv), '/') . '/';
    }

    // Nếu đã có hằng số BASE_URL_MANUAL (tùy chỉnh thủ công), sử dụng nó
    if (defined('BASE_URL_MANUAL')) {
        return BASE_URL_MANUAL;
    }

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                 || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
                 || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
                ? "https://"
                : "http://";

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    
    // Xác định base path từ SCRIPT_NAME
    $basePath = dirname($scriptName);

    // Xây dựng base URL
    $baseUrl = $protocol . $host;

    // Thêm base path nếu không phải root
    if ($basePath !== '/' && $basePath !== '\\') {
        // Chuẩn hóa path separators
        $basePath = str_replace('\\', '/', $basePath);
        
        // Loại bỏ các thư mục không cần thiết như 'public_html' nếu đang trên hosting
        // Chỉ giữ lại thư mục thực sự chứa ứng dụng
        $pathSegments = explode('/', trim($basePath, '/'));
        $filteredSegments = [];
        
        foreach ($pathSegments as $segment) {
            // Bỏ qua các thư mục hosting tiêu chuẩn
            if (!in_array(strtolower($segment), ['public_html', 'htdocs', 'www', 'web'])) {
                $filteredSegments[] = $segment;
            }
        }
        
        // Nếu còn segment nào, thêm vào base URL
        if (!empty($filteredSegments)) {
            $baseUrl .= '/' . implode('/', $filteredSegments);
        }
    }

    // Đảm bảo kết thúc bằng /
    if (substr($baseUrl, -1) !== '/') {
        $baseUrl .= '/';
    }

    return $baseUrl;
}

// Định nghĩa hằng số BASE_URL để sử dụng toàn ứng dụng
define('BASE_URL', getBaseUrl());

// Ưu tiên biến môi trường để hỗ trợ Docker và các nền tảng deploy.
$dbHostFromEnv = getenv('DB_HOST');
$dbNameFromEnv = getenv('DB_NAME');
$dbUserFromEnv = getenv('DB_USER');
$dbPassFromEnv = getenv('DB_PASS');

if (is_string($dbHostFromEnv) && trim($dbHostFromEnv) !== '') {
    define('DB_HOST', trim($dbHostFromEnv));
    define('DB_NAME', is_string($dbNameFromEnv) && trim($dbNameFromEnv) !== '' ? trim($dbNameFromEnv) : 'banhang');
    define('DB_USER', is_string($dbUserFromEnv) && trim($dbUserFromEnv) !== '' ? trim($dbUserFromEnv) : 'root');
    define('DB_PASS', is_string($dbPassFromEnv) ? $dbPassFromEnv : '');
} else {
    $httpHost = strtolower((string)($_SERVER['HTTP_HOST'] ?? 'localhost'));
    $hostWithoutPort = explode(':', $httpHost, 2)[0];

    // Cấu hình database tự động nhận dạng môi trường
    if ($hostWithoutPort === 'localhost' || $hostWithoutPort === '127.0.0.1') {
        // Cấu hình chạy trên Local / XAMPP
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'banhang'); // Hoặc 'phone_store_v2' nếu bạn dùng DB cũ
        define('DB_USER', 'root');
        define('DB_PASS', '');
    } else {
        // Cấu hình chạy trên Hosting (tieuchuancao.id.vn)
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'udtbalbihosting_Banhang');
        define('DB_USER', 'udtbalbihosting_root');
        define('DB_PASS', 'Vinh@123');
    }
}
define('DB_CHARSET', 'utf8mb4');

// Cấu hình upload path
define('UPLOAD_BASE', __DIR__ . '/../Public/');
define('UPLOAD_URL', BASE_URL . 'Public/');

// Các cấu hình khác
define('APP_NAME', 'Ban Hang');
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_ACTION', 'Get_data');
