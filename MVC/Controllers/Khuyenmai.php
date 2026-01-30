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
        $result = $this->km->KhuyenMai_getAll();

        $this->view('Master', [
            'page' => 'danhsachkhuyenmai_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        $result = $this->km->KhuyenMai_getAll();

        $this->view('Master', [
            'page' => 'khuyenmai_v',
            'makm' => '',
            'tenkm' => '',
            'tiengiam' => '',
            'ngaybatdau' => '',
            'ngayketthuc' => '',
            'trangthai' => '',
            'dulieu' => $result
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_khuyen_mai = $_POST['txtMaKM'];
            $ten_khuyen_mai = $_POST['txtTenKM'];
            $tien_khuyen_mai = $_POST['txtTienGiam'];
            $ngay_bat_dau = $_POST['txtNgayBatDau'];
            $ngay_ket_thuc = $_POST['txtNgayKetThuc'];
            $trang_thai_khuyen_mai = $_POST['ddlTrangThai'];

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
                        'page' => 'khuyenmai_v',
                        'makm' => $ma_khuyen_mai,
                        'tenkm' => $ten_khuyen_mai,
                        'tiengiam' => $tien_khuyen_mai,
                        'ngaybatdau' => $ngay_bat_dau,
                        'ngayketthuc' => $ngay_ket_thuc,
                        'trangthai' => $trang_thai_khuyen_mai
                    ]);
                } else {
                    $kq = $this->km->khuyenmai_ins($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai_khuyen_mai);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'khuyenmai_v',
                            'makm' => $ma_khuyen_mai,
                            'tenkm' => $ten_khuyen_mai,
                            'tiengiam' => $tien_khuyen_mai,
                            'ngaybatdau' => $ngay_bat_dau,
                            'ngayketthuc' => $ngay_ket_thuc,
                            'trangthai' => $trang_thai_khuyen_mai
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_khuyen_mai = $_POST['txtMaKM'] ?? '';
        $ten_khuyen_mai = $_POST['txtTenKM'] ?? '';

        // LẤY DỮ LIỆU THEO MÃ KHUYẾN MÃI + TÊN KHUYẾN MÃI
        $result = $this->km->KhuyenMai_find($ma_khuyen_mai, $ten_khuyen_mai);

        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'danhsachkhuyenmai_v',
            'makm' => $ma_khuyen_mai,
            'tenkm' => $ten_khuyen_mai,
            'dulieu' => $result
        ]);
    }

    function sua($ma_khuyen_mai)
    {
        $result = $this->km->KhuyenMai_getById($ma_khuyen_mai);
        $row = mysqli_fetch_array($result);

        $this->view('Master', [
            'page' => 'khuyenmai_sua',
            'makm' => $row['ma_khuyen_mai'],
            'tenkm' => $row['ten_khuyen_mai'],
            'tiengiam' => $row['tien_khuyen_mai'],
            'ngaybatdau' => $row['ngay_bat_dau'],
            'ngayketthuc' => $row['ngay_ket_thuc'],
            'trangthai' => $row['trang_thai_khuyen_mai']
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_khuyen_mai = $_POST['txtMaKM'];
            $ten_khuyen_mai = $_POST['txtTenKM'];
            $tien_khuyen_mai = $_POST['txtTienGiam'];
            $ngay_bat_dau = $_POST['txtNgayBatDau'];
            $ngay_ket_thuc = $_POST['txtNgayKetThuc'];
            $trang_thai_khuyen_mai = $_POST['ddlTrangThai'];

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
}