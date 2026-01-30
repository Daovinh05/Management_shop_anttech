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
        $result = $this->th->ThuongHieu_getAll();

        $this->view('Master', [
            'page' => 'danhsachthuonghieu_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        $result = $this->th->ThuongHieu_getAll();

        $this->view('Master', [
            'page' => 'thuonghieu_v',
            'mathuonghieu' => '',
            'tenthuonghieu' => '',
            'dulieu' => $result
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_thuong_hieu = $_POST['txtMaThuongHieu'];
            $ten_thuong_hieu = $_POST['txtTenThuongHieu'];

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
                        'page' => 'thuonghieu_v',
                        'mathuonghieu' => $ma_thuong_hieu,
                        'tenthuonghieu' => $ten_thuong_hieu
                    ]);
                } else {
                    $kq = $this->th->thuonghieu_ins($ma_thuong_hieu, $ten_thuong_hieu);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'thuonghieu_v',
                            'mathuonghieu' => $ma_thuong_hieu,
                            'tenthuonghieu' => $ten_thuong_hieu
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_thuong_hieu = $_POST['txtMaThuongHieu'] ?? '';
        $ten_thuong_hieu = $_POST['txtTenThuongHieu'] ?? '';

        // LẤY DỮ LIỆU THEO MÃ THƯƠNG HIỆU + TÊN THƯƠNG HIỆU
        $result = $this->th->ThuongHieu_find($ma_thuong_hieu, $ten_thuong_hieu);

        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'danhsachthuonghieu_v',
            'mathuonghieu' => $ma_thuong_hieu,
            'tenthuonghieu' => $ten_thuong_hieu,
            'dulieu' => $result
        ]);
    }

    function sua($ma_thuong_hieu)
    {
        $result = $this->th->ThuongHieu_getById($ma_thuong_hieu);
        $row = mysqli_fetch_array($result);

        $this->view('Master', [
            'page' => 'thuonghieu_sua',
            'mathuonghieu' => $row['ma_thuong_hieu'],
            'tenthuonghieu' => $row['ten_thuong_hieu']
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_thuong_hieu = $_POST['txtMaThuongHieu'];
            $ten_thuong_hieu = $_POST['txtTenThuongHieu'];

            $kq = $this->th->ThuongHieu_update($ma_thuong_hieu, $ten_thuong_hieu);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";

            $this->Get_data();
        }
    }

    function xoa($ma_thuong_hieu)
    {
        $kq = $this->th->ThuongHieu_delete($ma_thuong_hieu);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Thuonghieu/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Thuonghieu/danhsach') . "';</script>";
    }
}