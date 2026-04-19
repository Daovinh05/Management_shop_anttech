<?php
require_once __DIR__ . '/Config.php';

class app
{
    protected $controller = "Home";
    protected $action = "Get_data";
    protected $param = [];
    function processURL()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(trim($_GET['url']), FILTER_DEFAULT));
        }
    }
    function __construct()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        $this->checkAuth();

        $arr = $this->processURL();
        $is_api = false;
        $controller_dir = '/../Controllers/Web/';

        if ($arr != null && strtolower($arr[0]) === 'api') {
            $is_api = true;
            $controller_dir = '/../Controllers/Api/';
            unset($arr[0]);
            $arr = array_values($arr); // reset index
        }

        if ($arr != null && isset($arr[0])) {
            if (file_exists(__DIR__ . $controller_dir . $arr[0] . '.php')) {
                $this->controller = $arr[0];
                unset($arr[0]);
            } else if ($is_api) {
                // Hỗ trợ URL lowercase kiểu REST: /api/products
                $normalized_api_controller = ucfirst(strtolower($arr[0]));
                if (file_exists(__DIR__ . $controller_dir . $normalized_api_controller . '.php')) {
                    $this->controller = $normalized_api_controller;
                    unset($arr[0]);
                } else {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(404);
                    echo json_encode(['success' => false, 'error' => 'API Controller Not Found']);
                    exit;
                }
            } else if ($is_api) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'API Controller Not Found']);
                exit;
            }
        } else if ($is_api) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'API Controller Required']);
            exit;
        }

        include_once __DIR__ . $controller_dir . $this->controller . '.php';
        $this->controller = new $this->controller;
        
        //Xử lý action
        if (isset($arr[1])) {
            if (method_exists($this->controller, $arr[1])) {
                // Ưu tiên giữ tương thích route action-based hiện tại
                $this->action = $arr[1];
                unset($arr[1]);
            } else if ($is_api) {
                // Fallback RESTful route: /Api/Products/SP01
                $restful_action = $this->resolveApiRestfulAction($_SERVER['REQUEST_METHOD']);
                if ($restful_action !== null && method_exists($this->controller, $restful_action)) {
                    $this->action = $restful_action;
                } else {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(404);
                    echo json_encode(['success' => false, 'error' => 'API Endpoint Not Found']);
                    exit;
                }
            }
        } else if ($is_api) {
            // RESTful collection route: /Api/Products
            $restful_action = $this->resolveApiRestfulAction($_SERVER['REQUEST_METHOD'], true);
            if ($restful_action !== null && method_exists($this->controller, $restful_action)) {
                $this->action = $restful_action;
            } else if (!method_exists($this->controller, $this->action)) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'API Endpoint Not Found']);
                exit;
            }
        }

        //Xử lý param
        $this->param = $arr ? array_values($arr) : [];
        //Tạo biến có 3 tham số
        call_user_func_array([$this->controller, $this->action], $this->param);
    }

    private function resolveApiRestfulAction($request_method, $is_collection = false)
    {
        $request_method = strtoupper($request_method);

        if ($is_collection) {
            switch ($request_method) {
                case 'GET':
                    return 'get_all';
                case 'POST':
                    return 'create';
                case 'PUT':
                case 'PATCH':
                    return 'update';
                default:
                    return null;
            }
        }

        switch ($request_method) {
            case 'GET':
                return 'get_detail';
            case 'PUT':
            case 'PATCH':
                return 'update';
            case 'DELETE':
                return 'delete';
            default:
                return null;
        }
    }

    function checkAuth()
    {
        // Xác định các route công khai không yêu cầu xác thực
        $public_routes = ['Users/login', 'Users/logout', 'Login', 'Login/process', 'Login/register', 'Login/process_register', 'Khachhang', 'Khachhang/*', 'Home', 'Home/*'];

        // Lấy route hiện tại
        $current_route = '';
        if (isset($_GET['url'])) {
            $current_route = trim($_GET['url'], '/');
        }

        // Nếu đây là request dành cho API, bỏ qua cơ chế session checkAuth này
        if (strpos(strtolower($current_route), 'api/') === 0 || strtolower($current_route) === 'api') {
            return; // API sẽ tự bọc cơ chế Auth riêng (Token) ở các Controller của chúng
        }

        // Nếu người dùng chưa đăng nhập và truy cập trang chủ trực tiếp (root URL), cho phép truy cập trang chủ
        if (!isset($_SESSION['user_id']) && empty($current_route)) {
            // Không chuyển hướng, cho phép truy cập trang chủ
            return;
        }

        // Nếu người dùng chưa đăng nhập và cố gắng truy cập route bảo vệ (không phải login/logout hoặc khachhang hoặc home)
        if (!isset($_SESSION['user_id']) && !in_array($current_route, $public_routes) && strpos($current_route, 'Khachhang') !== 0 && strpos($current_route, 'Home') !== 0) {
            // Redirect to Home instead of Login since login is now on the Home page
            header('Location: ' . BASE_URL . 'Home');
            exit;
        }

        // Nếu người dùng đã đăng nhập nhưng cố truy cập trang đăng nhập, chuyển hướng theo vai trò
        if (isset($_SESSION['user_id']) && in_array($current_route, ['Users/login', 'Login'])) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: ' . BASE_URL . 'Quanly');
            } elseif ($_SESSION['user_role'] === 'nhan_vien') {
                header('Location: ' . BASE_URL . 'Staff');
            } elseif ($_SESSION['user_role'] === 'khach_hang') {
                header('Location: ' . BASE_URL . 'Khachhang');
            } else {
                header('Location: ' . BASE_URL . 'Khachhang');
            }
            exit;
        }

        // Nếu người dùng đã đăng nhập và truy cập trang chủ trực tiếp, chuyển hướng theo vai trò nếu là admin hoặc nhân viên
        if (isset($_SESSION['user_id']) && empty($current_route)) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: ' . BASE_URL . 'Quanly');
            } elseif ($_SESSION['user_role'] === 'nhan_vien') {
                header('Location: ' . BASE_URL . 'Staff');
            } elseif ($_SESSION['user_role'] === 'khach_hang') {
                // Cho phép khách hàng truy cập trang chủ
                return;
            } else {
                // Cho phép truy cập trang chủ
                return;
            }
            exit;
        }

        // Đảm bảo chỉ những người dùng được ủy quyền mới có thể truy cập các route nhân viên
        if (!isset($_SESSION['user_id']) && strpos($current_route, 'Staff') === 0) {
            header('Location: ' . BASE_URL . 'Login');
            exit;
        }

        // Đảm bảo chỉ những người dùng được ủy quyền mới có thể truy cập các route admin
        if (!isset($_SESSION['user_id']) && strpos($current_route, 'Quanly') === 0) {
            header('Location: ' . BASE_URL . 'Login');
            exit;
        }
    }


    
}
