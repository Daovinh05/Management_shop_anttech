<?php
    // Tắt hiển thị lỗi trực tiếp (vẫn log nếu cấu hình trong php.ini)
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/api_error.log');

    function resolveRequestUrl()
    {
        if (!empty($_GET['url'])) {
            return trim((string) $_GET['url'], '/');
        }

        $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        $requestPath = trim((string) $requestPath, '/');

        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $basePath = trim(dirname($scriptName), '/');
        if ($basePath !== '' && strpos($requestPath, $basePath) === 0) {
            $requestPath = trim(substr($requestPath, strlen($basePath)), '/');
        }

        $scriptFile = basename($scriptName);
        if ($scriptFile !== '' && strpos($requestPath, $scriptFile) === 0) {
            $requestPath = trim(substr($requestPath, strlen($scriptFile)), '/');
        }

        return $requestPath;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Kiểm tra xem có phải API request không
    $url = resolveRequestUrl();
    $isApiRequest = (stripos($url, 'api') === 0);

    if ($isApiRequest) {
        // API request - chỉ include API handler
        // Giữ JSON response sạch, nhưng vẫn log lỗi vào file.
        ini_set('display_errors', 0);

        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json; charset=UTF-8');
        
        try {
            include_once __DIR__.'/MVC/Core/Config.php';
            include_once __DIR__.'/MVC/Core/connectDB.php';
            include_once __DIR__.'/MVC/Core/ApiController.php';
            include_once __DIR__.'/MVC/Models/Users_m.php';
            include_once __DIR__.'/MVC/Models/SanPham_m.php';
            include_once __DIR__.'/MVC/Models/DanhMuc_m.php';
            include_once __DIR__.'/MVC/Models/ThuongHieu_m.php';
            include_once __DIR__.'/MVC/Models/NhaCungCap_m.php';
            include_once __DIR__.'/MVC/Models/BienThe_m.php';

            // Xử lý routing đơn giản
            $url = trim(substr($url, 4), '/'); // Loại bỏ 'api/'
            
            // Tách query params khỏi path
            $urlParts = explode('?', $url, 2);
            $path = $urlParts[0];
            if (isset($urlParts[1])) {
                // Parse query params và merge vào $_GET
                parse_str($urlParts[1], $queryParams);
                $_GET = array_merge($_GET, $queryParams);
            }
            
            $parts = array_values(array_filter(explode('/', $path), function($part) {
                return !empty($part);
            }));
            
            if (!empty($parts)) {
                $resource = ucfirst(strtolower($parts[0]));
                $controllerFile = __DIR__ . '/MVC/Controllers/Api/' . $resource . 'Api.php';
                
                if (file_exists($controllerFile)) {
                    include_once $controllerFile;
                    $controllerClass = $resource . 'Api';
                    
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        
                        // Xác định action
                        $httpMethod = $_SERVER['REQUEST_METHOD'];
                        $id = isset($parts[1]) && is_numeric($parts[1]) ? $parts[1] : null;
                        $actionName = isset($parts[1]) && !is_numeric($parts[1]) ? $parts[1] : null;
                        
                        $action = 'getAll';
                        if ($actionName && !is_numeric($actionName)) {
                            $action = $actionName;
                        } else {
                            switch ($httpMethod) {
                                case 'GET': $action = $id ? 'getById' : 'getAll'; break;
                                case 'POST': $action = 'create'; break;
                                case 'PUT':
                                case 'PATCH': $action = $id ? 'update' : 'bulkUpdate'; break;
                                case 'DELETE': $action = $id ? 'delete' : 'bulkDelete'; break;
                            }
                        }
                        
                        if (method_exists($controller, $action)) {
                            $params = $id ? [$id] : [];
                            call_user_func_array([$controller, $action], $params);
                        } else {
                            http_response_code(404);
                            echo json_encode(['success' => false, 'message' => "Method '$action' not found"]);
                        }
                    } else {
                        http_response_code(500);
                        echo json_encode(['success' => false, 'message' => "Controller '$controllerClass' not found"]);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => "API resource '$resource' not found"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'API endpoint not specified']);
            }
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit();
    }

    // Không phải API request - chạy MVC bình thường
    include_once __DIR__.'/MVC/bridge.php';
    $myapp = new app();
?>
