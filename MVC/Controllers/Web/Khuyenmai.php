<?php
class Khuyenmai extends controller
{
    private $km;

    function __construct()
    {
        $this->km = $this->model("KhuyenMai_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách khuyến mãi
        $this->danhsach();
    }

    function danhsach()
    {
        $this->view('Master', [
            'page' => 'Danhsachkhuyenmai_v',
            'dulieu' => null
        ]);
    }

    function themmoi()
    {
        $this->view('Master', [
            'page' => 'Khuyenmai_v',
            'ma_khuyen_mai' => '',
            'ten_khuyen_mai' => '',
            'tien_khuyen_mai' => '',
            'ngay_bat_dau' => '',
            'ngay_ket_thuc' => '',
            'dulieu' => null
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_khuyen_mai = $_POST['txtMakhuyenmai'];
            $ten_khuyen_mai = $_POST['txtTenkhuyenmai'];
            $tien_khuyen_mai = $_POST['txtTienkhuyenmai'];
            $ngay_bat_dau = $_POST['txtNgaybatdau'];
            $ngay_ket_thuc = $_POST['txtNgayketthuc'];

            // Calculate status based on current date and end date
            $current_date = date('Y-m-d');
            $trang_thai_khuyen_mai = (strtotime($ngay_ket_thuc) < strtotime($current_date)) ? 'het' : 'con';

            if ($ma_khuyen_mai == '') {
                echo "<script>alert('Mã khuyến mãi không được rỗng!')</script>";
                $this->themmoi();
            } else if ($ten_khuyen_mai == '') {
                echo "<script>alert('Tên khuyến mãi không được rỗng!')</script>";
                $this->themmoi();
            } else {
                $kq1 = $this->km->checktrungMaKM($ma_khuyen_mai);
                if ($kq1) {
                    echo "<script>alert('Mã khuyến mãi đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'Khuyenmai_v',
                        'ma_khuyen_mai' => $ma_khuyen_mai,
                        'ten_khuyen_mai' => $ten_khuyen_mai,
                        'tien_khuyen_mai' => $tien_khuyen_mai,
                        'ngay_bat_dau' => $ngay_bat_dau,
                        'ngay_ket_thuc' => $ngay_ket_thuc,
                    ]);
                } else {
                    $kq = $this->km->khuyenmai_ins($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai_khuyen_mai);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'Khuyenmai_v',
                            'ma_khuyen_mai' => $ma_khuyen_mai,
                            'ten_khuyen_mai' => $ten_khuyen_mai,
                            'tien_khuyen_mai' => $tien_khuyen_mai,
                            'ngay_bat_dau' => $ngay_bat_dau,
                            'ngay_ket_thuc' => $ngay_ket_thuc,
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
            'message' => 'Endpoint Timkiem đã ngừng sử dụng. Vui lòng dùng GET /Api/Khuyenmai với query: ma_khuyen_mai, ten_khuyen_mai'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    function sua($ma_khuyen_mai)
    {
        $this->view('Master', [
            'page' => 'Khuyenmai_sua'
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_khuyen_mai = $_POST['txtMakhuyenmai']; // Fixed field name
            $ten_khuyen_mai = $_POST['txtTenkhuyenmai'];
            $tien_khuyen_mai = $_POST['txtTienkhuyenmai'];
            $ngay_bat_dau = $_POST['txtNgaybatdau'];
            $ngay_ket_thuc = $_POST['txtNgayketthuc']; // Fixed field name

            // Calculate status based on current date and end date
            $current_date = date('Y-m-d');
            $trang_thai_khuyen_mai = (strtotime($ngay_ket_thuc) < strtotime($current_date)) ? 'het' : 'con';

            $kq = $this->km->KhuyenMai_update($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai_khuyen_mai);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";

            $this->Get_data();
        }
    }

    function xoa($ma_khuyen_mai)
    {
        $kq = $this->km->KhuyenMai_delete($ma_khuyen_mai);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Khuyenmai/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Khuyenmai/danhsach') . "';</script>";
    }

    function import_form()
    {
        $this->view('Master', [
            'page' => 'Khuyenmai_up_v'
        ]);
    }
}