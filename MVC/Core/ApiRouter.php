<?php
/**
 * API Router
 * Xử lý routing cho RESTful API endpoints
 */
class ApiRouter
{
    private $basePath = '/api';
    private $controller;
    private $action;
    private $params = [];

    public function __construct()
    {
        // Kiểm tra xem có phải request API không
        if (!$this->isApiRequest()) {
            return false;
        }

        $this->route();
        return true;
    }

    /**
     * Kiểm tra xem có phải request API không
     */
    private function isApiRequest()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        return strpos($url, 'api') === 0 || strpos($url, 'API') === 0;
    }

    /**
     * Xử lý routing
     */
    private function route()
    {
        // Lấy URL và loại bỏ 'api/'
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = trim($url, '/');
        
        // Loại bỏ 'api' khỏi path
        if (strpos($url, 'api') === 0 || strpos($url, 'API') === 0) {
            $url = substr($url, 4);
            $url = trim($url, '/');
        }

        // Tách các phần của URL
        $parts = explode('/', $url);
        $parts = array_filter($parts, function($part) {
            return !empty($part);
        });
        $parts = array_values($parts);

        if (empty($parts)) {
            $this->sendError('API endpoint not specified', 400);
            return;
        }

        // Phần đầu tiên là resource (controller)
        $resource = ucfirst(strtolower($parts[0]));
        
        // Xác định action dựa vào HTTP method
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $id = isset($parts[1]) && is_numeric($parts[1]) ? $parts[1] : null;
        $actionName = isset($parts[1]) && !is_numeric($parts[1]) ? $parts[1] : null;

        // Map resource sang controller file
        // Các controller API đều có hậu tố 'Api' nên dùng resource name trực tiếp
        
        // Xác định controller thực tế (dùng resource name trực tiếp)
        $resource = ucfirst(strtolower($parts[0]));
        
        // Kiểm tra file controller tồn tại
        $controllerFile = __DIR__ . '/../Controllers/Api/' . $resource . 'Api.php';
        
        if (!file_exists($controllerFile)) {
            // Thử với controller mặc định
            $controllerFile = __DIR__ . '/../Controllers/Api/' . $resource . '_Api.php';
        }

        if (!file_exists($controllerFile)) {
            $this->sendError("API resource '{$resource}' not found", 404);
            return;
        }

        // Include controller
        include_once $controllerFile;

        // Tạo instance controller
        $controllerClass = $resource . 'Api';
        
        if (!class_exists($controllerClass)) {
            $this->sendError("Controller '{$controllerClass}' not found", 500);
            return;
        }

        $this->controller = new $controllerClass();

        // Xác định action cần gọi
        $action = $this->determineAction($httpMethod, $id, $actionName, $parts);

        // Gọi action
        if (method_exists($this->controller, $action)) {
            call_user_func([$this->controller, $action], $this->params);
        } else {
            $this->sendError("Method '{$action}' not found in {$controllerClass}", 404);
        }
    }

    /**
     * Xác định action dựa vào HTTP method và params
     */
    private function determineAction($httpMethod, $id, $actionName, $parts)
    {
        // Nếu có action name cụ thể (như /products/search)
        if ($actionName && !is_numeric($actionName)) {
            return $actionName;
        }

        // RESTful mapping
        switch ($httpMethod) {
            case 'GET':
                return $id ? 'getById' : 'getAll';
            
            case 'POST':
                return 'create';
            
            case 'PUT':
            case 'PATCH':
                return $id ? 'update' : 'bulkUpdate';
            
            case 'DELETE':
                return $id ? 'delete' : 'bulkDelete';
            
            default:
                return 'getAll';
        }
    }

    /**
     * Gửi response lỗi
     */
    private function sendError($message, $statusCode = 400)
    {
        http_response_code($statusCode);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode([
            'success' => false,
            'message' => $message,
            'error_code' => 'API_ERROR'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}
