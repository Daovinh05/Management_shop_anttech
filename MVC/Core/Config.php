<?php
/**
 * Cấu hình ứng dụng
 * Tự động detect base URL cho cả local và production
 */

// Tự động detect base URL
function getBaseUrl()
{
    // Nếu đã có hằng số BASE_URL_MANUAL (tùy chỉnh thủ công), sử dụng nó
    if (defined('BASE_URL_MANUAL')) {
        return BASE_URL_MANUAL;
    }

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                 || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
                 || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
                ? "https://"
                : "http://";

    $host = $_SERVER['HTTP_HOST'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $requestUri = $_SERVER['REQUEST_URI'];
    
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


// --- Tự động load biến môi trường từ file .env (nếu có) ---
if (file_exists(__DIR__ . '/../../.env')) {
    $lines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        if (!getenv($name)) putenv("$name=$value");
    }
}

// Định nghĩa hằng số BASE_URL để sử dụng toàn ứng dụng
define('BASE_URL', getBaseUrl());

// Cấu hình database tự động nhận dạng môi trường
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
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
define('DB_CHARSET', 'utf8mb4');

// Cấu hình upload path
define('UPLOAD_BASE', __DIR__ . '/../Public/');
define('UPLOAD_URL', BASE_URL . 'Public/');

// Các cấu hình khác
define('APP_NAME', 'Ban Hang');
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_ACTION', 'Get_data');
