<?php
    // Bật output buffering để ngăn chặn bầy kỳ output nào không mong muốn làm hỏng JSON response
    ob_start();
    
    // Tắt hiển thị lỗi trực tiếp (vẫn log nếu cấu hình trong php.ini) 
    // Điều này cực kỳ quan trọng trên hosting để tránh rò rỉ cảnh báo/thông báo vào JSON
    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    include_once __DIR__.'/MVC/bridge.php';
    $myapp = new app();
?>