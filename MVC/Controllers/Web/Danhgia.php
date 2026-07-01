<?php
class Danhgia extends controller
{
    private $dg;

    function __construct()
    {
        $this->dg = $this->model("DanhGia_m");
    }

    function index()
    {
        $this->danhsach();
    }

    function Get_data()
    {
        $this->danhsach();
    }

    function danhsach()
    {
        $this->view('Master', [
            'page' => 'Danhsachdanhgia_v',
            'ma_danh_gia' => '',
            'ten_danh_gia' => '',
            'dulieu' => null
        ]);
    }

    function themmoi()
    {
        $this->view('Master', [
            'page' => 'Danhgia_v',
            'ma_danh_gia' => '',
            'ma_user' => '',
            'ma_san_pham' => '',
            'so_sao' => '',
            'noi_dung' => '',
            'phan_hoi' => ''
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_danh_gia = $_POST['txtMadanhgia'];
            $ma_user = $_POST['txtMauser'];
            $ma_san_pham = $_POST['txtMasanpham'];
            $so_sao = $_POST['txtSosao'];
            $noi_dung = $_POST['txtNoidung'];
            $phan_hoi = $_POST['txtPhanhoi'];

            if ($ma_danh_gia == '') {
                echo "<script>alert('Mã đánh giá không được rỗng!')</script>";
            } else {
                $kq1 = $this->dg->checktrungMaDG($ma_danh_gia);

                if ($kq1) {
                    echo "<script>alert('Mã đánh giá đã tồn tại!')</script>";
                    $this->view('Master', [
                        'page' => 'Danhgia_v',
                        'ma_danh_gia' => $ma_danh_gia,
                        'ma_user' => $ma_user,
                        'ma_san_pham' => $ma_san_pham,
                        'so_sao' => $so_sao,
                        'noi_dung' => $noi_dung,
                        'phan_hoi' => $phan_hoi
                    ]);
                } else {
                    $kq = $this->dg->danhgia_ins($ma_danh_gia, $ma_user, $ma_san_pham, $so_sao, $noi_dung, $phan_hoi);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'Danhgia_v',
                            'ma_danh_gia' => $ma_danh_gia,
                            'ma_user' => $ma_user,
                            'ma_san_pham' => $ma_san_pham,
                            'so_sao' => $so_sao,
                            'noi_dung' => $noi_dung,
                            'phan_hoi' => $phan_hoi
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
            'message' => 'Endpoint Timkiem đã ngừng sử dụng. Vui lòng dùng GET /Api/Danhgia với query: ma_danh_gia, ten_khach_hang, ten_san_pham'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    function sua($ma_danh_gia)
    {
        $this->view('Master', [
            'page' => 'Danhgia_sua'
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_danh_gia = $_POST['txtMadanhgia'];
            $so_sao = $_POST['txtSosao'];
            $noi_dung = $_POST['txtNoidung'];
            $phan_hoi = $_POST['txtPhanhoi'];

            $kq = $this->dg->DanhGia_update($ma_danh_gia, $so_sao, $noi_dung, $phan_hoi);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!'); window.location='" . $this->url('Danhgia/danhsach') . "';</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";
        }
    }

    function xoa($ma_danh_gia)
    {
        $kq = $this->dg->DanhGia_delete($ma_danh_gia);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Danhgia/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Danhgia/danhsach') . "';</script>";
    }
}
