<?php
/**
 * Cấu hình ứng dụng
 * Tự động detect base URL cho cả local và production
 */

// Tự động detect base URL
function getBaseUrl()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
                 || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) 
                ? "https://" 
                : "http://";
    
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = dirname($scriptName);
    
    // Xây dựng base URL
    $baseUrl = $protocol . $host;
    
    // Thêm base path nếu không phải root
    if ($basePath !== '/') {
        // Xóa các thư mục không cần thiết từ path (như public_html)
        $basePath = str_replace('\\', '/', $basePath);
        $baseUrl .= $basePath;
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
