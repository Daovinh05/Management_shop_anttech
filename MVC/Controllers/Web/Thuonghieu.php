<?php
class Thuonghieu extends controller
{
    private $th;

    function __construct()
    {
        $this->th = $this->model("ThuongHieu_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách thương hiệu
        $this->danhsach();
    }

    function danhsach()
    {
        $this->view('Master', [
            'page' => 'Danhsachthuonghieu_v'
        ]);
    }

    function themmoi()
    {
        $this->view('Master', [
            'page' => 'Thuonghieu_v',
            'ma_thuong_hieu' => '',
            'ten_thuong_hieu' => ''
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_thuong_hieu = $_POST['txtMathuonghieu'];
            $ten_thuong_hieu = $_POST['txtTenthuonghieu'];

            if ($ma_thuong_hieu == '') {
                echo "<script>alert('Mã thương hiệu không được rỗng!')</script>";
                $this->themmoi();
            } else if ($ten_thuong_hieu == '') {
                echo "<script>alert('Tên thương hiệu không được rỗng!')</script>";
                $this->themmoi();
            } else {
                $kq1 = $this->th->checktrungMaTH($ma_thuong_hieu);
                if ($kq1) {
                    echo "<script>alert('Mã thương hiệu đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'Thuonghieu_v',
                        'ma_thuong_hieu' => $ma_thuong_hieu,
                        'ten_thuong_hieu' => $ten_thuong_hieu
                    ]);
                } else {
                    $kq = $this->th->thuonghieu_ins($ma_thuong_hieu, $ten_thuong_hieu);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        $error = mysqli_error($this->th->con);
                        echo "<script>alert('Thêm mới thất bại! Lỗi: " . addslashes($error) . "')</script>";
                        $this->view('Master', [
                            'page' => 'Thuonghieu_v',
                            'ma_thuong_hieu' => $ma_thuong_hieu,
                            'ten_thuong_hieu' => $ten_thuong_hieu
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
            'message' => 'Endpoint đã ngừng hỗ trợ. Vui lòng sử dụng GET /Api/Thuonghieu'
        ]);
        return;
    }

    function sua($ma_thuong_hieu)
    {
        $this->view('Master', [
            'page' => 'Thuonghieu_sua'
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_thuong_hieu = $_POST['txtMathuonghieu'];
            $ten_thuong_hieu = $_POST['txtTenthuonghieu'];

            $kq = $this->th->ThuongHieu_update($ma_thuong_hieu, $ten_thuong_hieu);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else {
                $error = mysqli_error($this->th->con);
                echo "<script>alert('Cập nhật thất bại! Lỗi: " . addslashes($error) . "')</script>";
            }

            $this->Get_data();
        }
    }

    function xoa($ma_thuong_hieu)
    {
        if ($this->th->ThuongHieu_hasProducts($ma_thuong_hieu)) {
            echo "<script>alert('Không thể xóa vì đang có sản phẩm thuộc Thương hiệu này. Vui lòng chuyển các sản phẩm sang thương hiệu khác trước khi xóa.'); window.location='" . $this->url('Thuonghieu/danhsach') . "';</script>";
            return;
        }

        $kq = $this->th->ThuongHieu_delete($ma_thuong_hieu);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Thuonghieu/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Thuonghieu/danhsach') . "';</script>";
    }

    function import_form()
    {
        $this->view('Master', [
            'page' => 'Thuonghieu_up_v'
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

            $ma_thuong_hieu  = trim($sheetData[$i]['A']);
            $ten_thuong_hieu = trim($sheetData[$i]['B']);

            if ($ma_thuong_hieu == '') continue;

            // ✅ CHECK TRÙNG MÃ THƯƠNG HIỆU
            if ($this->th->checktrungMaTH($ma_thuong_hieu)) {
                echo "<script>
                alert('Mã thương hiệu $ma_thuong_hieu đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='" . $this->url('Thuonghieu/import_form') . "';
            </script>";
                return;
            }

            // Insert
            if (!$this->th->thuonghieu_ins($ma_thuong_hieu, $ten_thuong_hieu)) {
                $error = mysqli_error($this->th->con);
                die("Lỗi khi thêm thương hiệu: " . $error);
            }
        }

        echo "<script>alert('Upload thương hiệu thành công!')</script>";
        $this->view('Master', ['page' => 'Thuonghieu_up_v']);
    }
}
