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
                 || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))
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

// Định nghĩa hằng số BASE_URL để sử dụng toàn ứng dụng
define('BASE_URL', getBaseUrl());

// Cấu hình database (cập nhật thông tin này trên production)
define('DB_HOST', 'localhost');
define('DB_NAME', 'udtbalbihosting_Banhang');  // Đổi tên database nếu cần
define('DB_USER', 'udtbalbihosting_root');     // Đổi username trên production
define('DB_PASS', 'Vinh@123');         // Đổi password trên production
define('DB_CHARSET', 'utf8mb4');

// Cấu hình upload path
define('UPLOAD_BASE', __DIR__ . '/../Public/');
define('UPLOAD_URL', BASE_URL . 'Public/');

// Các cấu hình khác
define('APP_NAME', 'Ban Hang');
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_ACTION', 'Get_data');
