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
        $result = $this->user->Users_getAll();
        $this->view('Master', [
            'page' => 'Danhsachusers_v',
            'ma_user' => '',
            'ten_user' => '',
            'dulieu' => $result
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
            'phan_quyen' => 'nhan_vien'
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_user = $_POST['txtMauser'];
            $ten_user = $_POST['txtTenuser'];
            $password = $_POST['txtPassword'];
            $email = $_POST['txtEmail'];
            $phan_quyen = $_POST['ddlPhanquyen'];

            if ($ma_user == '') {
                echo "<script>alert('Mã user không được rỗng!')</script>";
            } else {
                $kq1 = $this->user->checktrungMaUser($ma_user);
                $checkEmail = $this->user->checktrungEmail($email, null);
                if ($kq1) {
                    echo "<script>alert('Mã user đã tồn tại!')</script>";
                    $this->view('Master', [
                        'page' => 'Users_v',
                        'ma_user' => $ma_user,
                        'ten_user' => $ten_user,
                        'password' => $password,
                        'email' => $email,
                        'phan_quyen' => $phan_quyen
                    ]);
                } else if (mysqli_num_rows($checkEmail) > 0) {
                    echo "<script>alert('Email đã được sử dụng!')</script>";
                    $this->view('Master', [
                        'page' => 'Users_v',
                        'ma_user' => $ma_user,
                        'ten_user' => $ten_user,
                        'password' => $password,
                        'email' => '',
                        'phan_quyen' => $phan_quyen
                    ]);
                    return;
                } else {
                    $kq = $this->user->users_ins($ma_user, $ten_user, $password, $email, $phan_quyen);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'Users_v',
                            'ma_user' => $ma_user,
                            'ten_user' => $ten_user,
                            'password' => $password,
                            'email' => $email,
                            'phan_quyen' => $phan_quyen
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ form
        $ma_user = $_POST['txtMauser'] ?? '';
        $ten_user = $_POST['txtTenuser'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ USER + TÊN USER
        $result = $this->user->Users_find($ma_user, $ten_user);
        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachUsers');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã User');
            $sheet->setCellValue('B1', 'Tên User');
            $sheet->setCellValue('C1', 'Password');
            $sheet->setCellValue('D1', 'Email');
            $sheet->setCellValue('E1', 'Phân Quyền');
            $sheet->setCellValue('F1', 'Ngay Tạo');


            $rowCount = 2; // Bắt đầu từ hàng 2 vì hàng 1 là tiêu đề
            mysqli_data_seek($result, 0); // Đặt lại con trỏ kết quả về đầu
            while ($row = mysqli_fetch_assoc($result)) {
                // Ánh xạ trường theo bảng cơ sở dữ liệu
                $sheet->setCellValue('A' . $rowCount, $row['ma_user']);
                $sheet->setCellValue('B' . $rowCount, $row['ten_user']);
                $sheet->setCellValue('C' . $rowCount, $row['password']);
                $sheet->setCellValue('D' . $rowCount, $row['email']);
                $sheet->setCellValue('E' . $rowCount, $row['phan_quyen']);
                $sheet->setCellValue('F' . $rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachUsers.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachusers_v',
            'ma_user' => $ma_user, // Consistent with view variable name
            'ten_user' => $ten_user, // Consistent with view variable name
            'dulieu' => $result
        ]);
    }



    function sua($ma_user)
    {
        $result = $this->user->Users_getById($ma_user);
        $row = mysqli_fetch_array($result);
        $this->view('Master', [
            'page' => 'Users_sua',
            'ma_user' => $row['ma_user'],
            'ten_user' => $row['ten_user'],
            'password' => $row['password'],
            'email' => $row['email'],
            'phan_quyen' => $row['phan_quyen']
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_user = $_POST['txtMauser'];
            $ten_user = $_POST['txtTenuser'];
            $password = $_POST['txtPassword'];
            $email = $_POST['txtEmail'];
            $phan_quyen = $_POST['ddlPhanquyen'];

            $check = $this->user->checktrungEmail($email, $ma_user);
            if (mysqli_num_rows($check) > 0) {
                echo "<script>alert('Email đã được sử dụng bởi tài khoản khác!');history.back();</script>";
                return;
            }

            $kq = $this->user->Users_update($ma_user, $ten_user, $password, $email, $phan_quyen);
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

            // Insert
            if (!$this->user->users_ins($ma_user, $ten_user, $password, $email, $phan_quyen)) {
                die(mysqli_error($this->user->con));
            }
        }

        echo "<script>alert('Upload người dùng thành công!')</script>";
        $this->view('Master', ['page' => 'Users_up_v']);
    }
}
