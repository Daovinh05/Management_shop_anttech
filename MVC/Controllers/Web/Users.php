<?php
class Users extends controller
{
    private $user;

    function __construct()
    {
        $this->user = $this->model("Users_m");
    }

    function index()
    {
        $this->danhsach();
    }

    function Get_data()
    {
        $data = ['page' => 'Danhsachusers_v'];
        $this->danhsach();
    }

    function danhsach()
    {
        $this->view('Master', [
            'page' => 'Danhsachusers_v'
        ]);
    }


    function themmoi()
    {
        $this->view('Master', [
            'page' => 'Users_v',
            'ma_user' => '',
            'ten_user' => '',
            'password' => '',
            'email' => '',
            'phan_quyen' => 'nhan_vien',
            'avatar' => ''
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_user = $_POST['txtMauser'];
            $ten_user = $_POST['txtTenuser'];
            $full_name = $_POST['txtHoten'] ?? ''; // Using the correct field name from the view
            $password = $_POST['txtPassword'];
            $email = $_POST['txtEmail'];
            $phan_quyen = $_POST['ddlPhanquyen'];
            $so_dien_thoai = $_POST['txtSoDienThoai'] ?? ''; // Phone number field

            // Xử lý upload avatar
            $avatar = '';
            if (isset($_FILES['txtAvatar']) && $_FILES['txtAvatar']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['txtAvatar']['name'];
                $filetmp = $_FILES['txtAvatar']['tmp_name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    // Làm sạch tên tệp gốc
                    $original_name = pathinfo($filename, PATHINFO_FILENAME);
                    $original_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $original_name);
                    $original_name = str_replace('-', '_', $original_name);
                    $new_filename = $original_name . '_' . time() . '.' . $ext;

                    // Tạo thư mục nếu chưa tồn tại - sử dụng đường dẫn tuyệt đối từ thư mục gốc của ứng dụng
                    $upload_dir = __DIR__ . '/../../Public/Pictures/users/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    // Sử dụng đường dẫn tuyệt đối đến thư mục Public/Pictures/users
                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($filetmp, $upload_path)) {
                        $avatar = $new_filename; // Chỉ lưu tên tệp vào DB
                    } else {
                        echo "<script>alert('Upload avatar thất bại!');</script>";
                        $this->themmoi();
                        return;
                    }
                } else {
                    echo "<script>alert('Định dạng avatar không hợp lệ!');</script>";
                    $this->themmoi();
                    return;
                }
            }

            if ($ma_user == '') {
                echo "<script>alert('Mã user không được rỗng!')</script>";
            } else {
                $kq1 = $this->user->checktrungMaUser($ma_user);
                $checkEmail = $this->user->checktrungEmail($email, null);

                // Also check if phone number already exists
                if ($so_dien_thoai != '' && $this->user->checkTrungSoDienThoai($so_dien_thoai)) {
                    echo "<script>alert('Số điện thoại đã được sử dụng!')</script>";
                    $this->view('Master', [
                        'page' => 'Users_v',
                        'ma_user' => $ma_user,
                        'ten_user' => $ten_user,
                        'full_name' => $full_name,
                        'password' => $password,
                        'email' => $email,
                        'phan_quyen' => $phan_quyen,
                        'so_dien_thoai' => '',
                        'avatar' => $avatar
                    ]);
                    return;
                }

                if ($kq1) {
                    echo "<script>alert('Mã user đã tồn tại!')</script>";
                    $this->view('Master', [
                        'page' => 'Users_v',
                        'ma_user' => $ma_user,
                        'ten_user' => $ten_user,
                        'full_name' => $full_name,
                        'password' => $password,
                        'email' => $email,
                        'phan_quyen' => $phan_quyen,
                        'so_dien_thoai' => $so_dien_thoai,
                        'avatar' => $avatar
                    ]);
                } else if (mysqli_num_rows($checkEmail) > 0) {
                    echo "<script>alert('Email đã được sử dụng!')</script>";
                    $this->view('Master', [
                        'page' => 'Users_v',
                        'ma_user' => $ma_user,
                        'ten_user' => $ten_user,
                        'full_name' => $full_name,
                        'password' => $password,
                        'email' => '',
                        'phan_quyen' => $phan_quyen,
                        'so_dien_thoai' => $so_dien_thoai,
                        'avatar' => $avatar
                    ]);
                    return;
                } else {
                    $kq = $this->user->users_ins($ma_user, $ten_user, $full_name, $password, $email, $phan_quyen, $so_dien_thoai, $avatar);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'Users_v',
                            'ma_user' => $ma_user,
                            'ten_user' => $ten_user,
                            'full_name' => $full_name,
                            'password' => $password,
                            'email' => $email,
                            'phan_quyen' => $phan_quyen,
                            'so_dien_thoai' => $so_dien_thoai,
                            'avatar' => $avatar
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint đã ngừng hỗ trợ. Vui lòng sử dụng GET /Api/Users'
        ]);
        return;
    }



    function sua($ma_user)
    {
        $this->view('Master', [
            'page' => 'Users_sua'
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_user = $_POST['txtMauser'];
            $full_name = $_POST['txtHoten'];
            $ten_user = $_POST['txtTenuser'];
            $password = $_POST['txtPassword'];
            $email = $_POST['txtEmail'];
            $phan_quyen = $_POST['ddlPhanquyen'];
            $so_dien_thoai = $_POST['txtSoDienThoai'] ?? '';

            // Lấy avatar hiện tại từ database trước
            $current_record = $this->user->Users_getById($ma_user);
            $current_row = mysqli_fetch_array($current_record);
            $avatar = $current_row['avatar']; // Giữ avatar hiện tại mặc định

            // Xử lý upload avatar mới (nếu có)
            if (isset($_FILES['txtAvatar']) && $_FILES['txtAvatar']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['txtAvatar']['name'];
                $filetmp = $_FILES['txtAvatar']['tmp_name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    // Làm sạch tên tệp gốc
                    $original_name = pathinfo($filename, PATHINFO_FILENAME);
                    $original_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $original_name); // Chỉ giữ các ký tự an toàn
                    $original_name = str_replace('-', '_', $original_name); // Thay thế dấu gạch nối bằng dấu gạch dưới
                    $new_filename = $original_name . '_' . time() . '.' . $ext;

                    // Tạo thư mục nếu chưa tồn tại - sử dụng đường dẫn tuyệt đối từ thư mục gốc của ứng dụng
                    $upload_dir = __DIR__ . '/../../Public/Pictures/users/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    // Sử dụng đường dẫn tuyệt đối đến thư mục Public/Pictures/users
                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($filetmp, $upload_path)) {
                        // Xóa avatar cũ nếu tồn tại
                        $old_image_path = __DIR__ . '/../../Public/Pictures/users/' . $current_row['avatar'];
                        if (!empty($current_row['avatar']) && file_exists($old_image_path) && strpos($old_image_path, '/Public/Pictures/users/') !== false) {
                            unlink($old_image_path);
                        }

                        $avatar = $new_filename; // Chỉ lưu tên tệp vào DB
                    } else {
                        echo "<script>alert('Upload avatar thất bại!');</script>";
                        $this->sua($ma_user);
                        return;
                    }
                } else {
                    echo "<script>alert('Định dạng avatar không hợp lệ!');</script>";
                    $this->sua($ma_user);
                    return;
                }
            }
            // Nếu không có file upload mới, giữ nguyên avatar hiện tại (đã được lấy ở trên)

            $check = $this->user->checktrungEmail($email, $ma_user);
            if (mysqli_num_rows($check) > 0) {
                echo "<script>alert('Email đã được sử dụng bởi tài khoản khác!');history.back();</script>";
                return;
            }

            $kq = $this->user->Users_update($ma_user, $ten_user, $full_name, $password, $email, $phan_quyen, $so_dien_thoai, $avatar);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!'); window.location='" . $this->url('Users/danhsach') . "';</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";
        }
    }

    function xoa($ma_user)
    {
        $kq = $this->user->Users_delete($ma_user);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Users/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Users/danhsach') . "';</script>";
    }



    // Hiển thị form nhập Excel
    function import_form()
    {
        $this->view('Master', [
            'page' => 'Users_up_v'
        ]);
    }


    function up_l()
    {
        if (!isset($_FILES['txtfile']) || $_FILES['txtfile']['error'] != 0) {
            echo "<script>alert('Upload file lỗi')</script>";
            return;
        }

        $file = $_FILES['txtfile']['tmp_name'];

        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objExcel  = $objReader->load($file);

        $sheet     = $objExcel->getSheet(0);
        $sheetData = $sheet->toArray(null, true, true, true);

        for ($i = 2; $i <= count($sheetData); $i++) {

            $ma_user    = trim($sheetData[$i]['A']);
            $ten_user   = trim($sheetData[$i]['B']);
            $password   = trim($sheetData[$i]['C']);
            $email      = trim($sheetData[$i]['D']);
            $phan_quyen = trim($sheetData[$i]['E']);

            if ($ma_user == '') continue;

            // ✅ CHECK GIÁ TRỊ PHÂN QUYỀN
            if ($phan_quyen != 'admin' && $phan_quyen != 'nhan_vien' && $phan_quyen != 'khach_hang') {
                echo "<script>
                alert('Phân quyền không hợp lệ cho user $ma_user! Chỉ cho phép admin, nhan_vien hoặc khach_hang.');
                window.location.href='" . $this->url('Users/import_form') . "';
            </script>";
                return;
            }

            // ✅ CHECK TRÙNG MÃ USER
            if ($this->user->checktrungMaUser($ma_user)) {
                echo "<script>
                alert('Mã user $ma_user đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='" . $this->url('Users/import_form') . "';
            </script>";
                return;
            }

            // ✅ CHECK TRÙNG EMAIL
            $checkEmail = $this->user->checktrungEmail($email, null);
            if (mysqli_num_rows($checkEmail) > 0) {
                echo "<script>
                alert('Email $email đã được sử dụng! Vui lòng kiểm tra lại file.');
                window.location.href='" . $this->url('Users/import_form') . "';
            </script>";
                return;
            }

            // Get additional fields from Excel if available (assuming column F is full_name and G is phone number)
            $full_name = isset($sheetData[$i]['F']) ? trim($sheetData[$i]['F']) : '';
            $so_dien_thoai = isset($sheetData[$i]['G']) ? trim($sheetData[$i]['G']) : '';

            // Insert
            if (!$this->user->users_ins($ma_user, $ten_user, $full_name, $password, $email, $phan_quyen, $so_dien_thoai)) {
                die(mysqli_error($this->user->con));
            }
        }

        echo "<script>alert('Upload người dùng thành công!')</script>";
        $this->view('Master', ['page' => 'Users_up_v']);
    }
}
