<?php
class app
{
    protected $controller = "Login";
    protected $action = "Get_data";
    protected $param = [];
    function __construct()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        $this->checkAuth();

        $arr = $this->processURL();

        if ($arr != null) {
            if (file_exists(__DIR__ . '/../Controllers/' . $arr[0] . '.php')) {
                $this->controller = $arr[0];
                unset($arr[0]);
            }
        }
        include_once __DIR__ . '/../Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        //Xử lý action
        if (isset($arr[1])) {
            if (method_exists($this->controller, $arr[1])) {
                $this->action = $arr[1];
                unset($arr[1]);
            }
        }
        //Xử lý param
        $this->param = $arr ? array_values($arr) : [];
        //Tạo biến có 3 tham số
        call_user_func_array([$this->controller, $this->action], $this->param);
    }

    function checkAuth()
    {
        // Xác định các route công khai không yêu cầu xác thực
        $public_routes = ['Users/login', 'Users/logout', 'Login', 'Login/process', 'Login/register', 'Login/process_register'];

        // Lấy route hiện tại
        $current_route = '';
        if (isset($_GET['url'])) {
            $current_route = $_GET['url'];
        } else {
            $current_route = '';
        }

        // Nếu người dùng chưa đăng nhập và truy cập trang chủ trực tiếp (root URL), chuyển hướng đến đăng nhập
        if (!isset($_SESSION['user_id']) && empty($current_route)) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        // Nếu người dùng chưa đăng nhập và cố gắng truy cập route bảo vệ (không phải login/logout)
        if (!isset($_SESSION['user_id']) && !in_array($current_route, $public_routes)) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        // Nếu người dùng đã đăng nhập nhưng cố truy cập trang đăng nhập, chuyển hướng theo vai trò
        if (isset($_SESSION['user_id']) && in_array($current_route, ['Users/login', 'Login'])) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: http://localhost/Banhang/Home');
            } elseif ($_SESSION['user_role'] === 'nhan_vien') {
                header('Location: http://localhost/Banhang/Staff');
            } elseif ($_SESSION['user_role'] === 'khach_hang') {
                header('Location: http://localhost/Banhang/Khachhang');
            } else {
                header('Location: http://localhost/Banhang/Khachhang');
            }
            exit;
        }

        // Nếu người dùng đã đăng nhập và truy cập trang chủ trực tiếp, chuyển hướng theo vai trò
        if (isset($_SESSION['user_id']) && empty($current_route)) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: http://localhost/Banhang/Home');
            } elseif ($_SESSION['user_role'] === 'nhan_vien') {
                header('Location: http://localhost/Banhang/Staff');
            } elseif ($_SESSION['user_role'] === 'khach_hang') {
                header('Location: http://localhost/Banhang/Khachhang');
            } else {
                header('Location: http://localhost/Banhang/Khachhang');
            }
            exit;
        }

        // Đảm bảo chỉ những người dùng được ủy quyền mới có thể truy cập các route nhân viên
        if (!isset($_SESSION['user_id']) && strpos($current_route, 'Staff') === 0) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        // Đảm bảo chỉ những người dùng được ủy quyền mới có thể truy cập các route khách hàng
        if (!isset($_SESSION['user_id']) && strpos($current_route, 'Khachhang') === 0) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }
    }


    function processURL()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(trim($_GET['url']), FILTER_DEFAULT));
        }
    }
}
