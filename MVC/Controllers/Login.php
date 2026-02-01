<?php
class Login extends controller
{
    private $user;

    function __construct()
    {
        $this->user = $this->model("Users_m");
    }

    function Get_data()
    {
        $this->phan_quyen();
    }

    function phan_quyen()
    {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: ' . $this->url('admin'));
            } else {
                header('Location: ' . $this->url('Khachhang'));
            }
            exit;
        } else {
            header('Location: ' . $this->url('Home'));
            exit;
        }
    }

    function process()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $result = $this->user->validateUser($username, $password);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                $_SESSION['user_id'] = $user['ma_user'];
                $_SESSION['user_name'] = $user['ten_user'];
                $_SESSION['user_role'] = $user['phan_quyen'];

                // Check if this is an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    // Return JSON response for AJAX
                    header('Content-Type: application/json');
                    if ($user['phan_quyen'] == 'admin') {
                        echo json_encode(['success' => true, 'redirect' => $this->url('admin')]);
                    } else {
                        echo json_encode(['success' => true, 'redirect' => $this->url('Khachhang')]);
                    }
                } else {
                    // Regular redirect for non-AJAX requests
                    if ($user['phan_quyen'] == 'admin') {
                        header('Location: ' . $this->url('admin'));
                    } else {
                        header('Location: ' . $this->url('Khachhang'));
                    }
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';

                // Check if this is an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    // Return JSON response for AJAX
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'Tên đăng nhập hoặc mật khẩu không đúng!']);
                } else {
                    header('Location: ' . $this->url('Login'));
                    exit;
                }
            }
        } else {
            // Check if this is an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                // Return JSON response for AJAX
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ!']);
            } else {
                header('Location: ' . $this->url('Login'));
                exit;
            }
        }
    }

    function register()
    {
        // Only clear form data if there's no error (fresh registration page)
        if (!isset($_SESSION['error'])) {
            unset($_SESSION['form_data']);
        }
        header('Location: ' . $this->url('Home'));
        exit;
    }

    function process_register()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $phone = $_POST['phone'] ?? '';  // Changed from 'so_dien_thoai' to 'phone' to match the form
            $confirm_password = $_POST['confirm_password'];
            $email = $_POST['email'] ?? '';
            $full_name = $_POST['fullname'] ?? $username;

            // Store form data in session to preserve values on validation errors
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'confirm_password' => $confirm_password,
                'phone' => $phone,
                'full_name' => $full_name
            ];

            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';

                // Check if this is an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    // Return JSON response for AJAX
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'Mật khẩu xác nhận không khớp!']);
                } else {
                    header('Location: ' . $this->url('Login/register'));
                }
                exit;
            }

            // if (strlen($password) < 6) {
            //     $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
            //     header('Location: ' . $this->url('Login/register'));
            //     exit;
            // }

            $existing_user_result = $this->user->getUserByUsername($username);
            if ($existing_user_result) {
                $_SESSION['error'] = 'Tên đăng nhập đã tồn tại!';

                // Check if this is an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    // Return JSON response for AJAX
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'Tên đăng nhập đã tồn tại!']);
                } else {
                    header('Location: ' . $this->url('Login/register'));
                }
                exit;
            }

            if (!empty($email)) {
                // Since we're registering a new user, we don't have a ma_user yet, so we'll check if email exists in general
                $existing_email = $this->user->checktrungEmail($email, '');
                if ($existing_email && mysqli_num_rows($existing_email) > 0) {
                    $_SESSION['error'] = 'Email đã được sử dụng!';

                    // Check if this is an AJAX request
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        // Return JSON response for AJAX
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'error' => 'Email đã được sử dụng!']);
                    } else {
                        header('Location: ' . $this->url('Login/register'));
                    }
                    exit;
                }
            } else {
                $email = $username . '@gmail.com';
            }

            // Check if phone number already exists
            if (!empty($phone)) {
                $existing_phone = $this->user->checkTrungSoDienThoai($phone);
                if ($existing_phone) {
                    $_SESSION['error'] = 'Số điện thoại đã được sử dụng!';

                    // Check if this is an AJAX request
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        // Return JSON response for AJAX
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'error' => 'Số điện thoại đã được sử dụng!']);
                    } else {
                        header('Location: ' . $this->url('Login/register'));
                    }
                    exit;
                }
            }

            $result = $this->user->createUser($username, $email, $full_name, $password, 'khach_hang', $phone);
            if ($result) {
                // Clear form data after successful registration
                unset($_SESSION['form_data']);
                unset($_SESSION['error']); // Also clear any error messages

                // Check if this is an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    // Return JSON response for AJAX
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.', 'redirect' => $this->url('Home')]);
                } else {
                    echo '<script>alert("Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ."); window.location.href = "' . $this->url('Home') . '";</script>';
                }
                exit;
            } else {
                $error_msg = mysqli_error($this->user->con);
                $_SESSION['error'] = 'Đăng ký thất bại! Lỗi: ' . $error_msg;

                // Check if this is an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    // Return JSON response for AJAX
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'Đăng ký thất bại! Lỗi: ' . $error_msg]);
                } else {
                    header('Location: ' . $this->url('Login/register'));
                }
                exit;
            }
        } else {
            // Check if this is an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                // Return JSON response for AJAX
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Dữ liệu không hợp lệ!']);
            } else {
                // Clear form data if accessed without POST
                unset($_SESSION['form_data']);
                unset($_SESSION['error']);
                header('Location: ' . $this->url('Login/register'));
            }
            exit;
        }
    }
    function logout()
    {
        session_destroy();
        header('Location: ' . $this->url('Login'));
        exit;
    }
}