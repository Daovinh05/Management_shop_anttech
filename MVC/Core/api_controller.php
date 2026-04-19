<?php
class api_controller {
    public function __construct() {
        // Tắt bộ nhớ đệm output để tránh lỗi JSON
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Luôn trả về JSON headers
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *'); // Hỗ trợ CORS nếu cần
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        // Phản hồi cho preflight request (OPTIONS) của trình duyệt
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }

    // Gọi model (Giống web controller)
    protected function model($model){
        if (file_exists(__DIR__ . '/../Models/' . $model . '.php')) {
            require_once __DIR__ . '/../Models/' . $model . '.php';
            return new $model;
        }
        return null;
    }

    // Trích xuất JSON input payload (khi request là application/json) k post,put (dùng để giải mã)
    protected function getJsonInput() {
        $input = json_decode(file_get_contents('php://input'), true);
        return is_array($input) ? $input : [];
    }

    // Gửi response chuẩn hóa
    protected function sendResponse($status_code, $data) {
        http_response_code($status_code);
        echo json_encode($data);
        exit;
    }
}
?>
