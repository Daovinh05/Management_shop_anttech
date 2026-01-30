<?php
class Donhang extends controller
{
    private $dh;
    private $user;
    private $dc;
    private $km;

    function __construct()
    {
        $this->dh = $this->model("DonHang_m");
        $this->user = $this->model("Users_m");
        $this->dc = $this->model("DiaChiGiaoHang_m");
        $this->km = $this->model("KhuyenMai_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách đơn hàng
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->dh->DonHang_getAll();

        $this->view('Master', [
            'page' => 'danhsachdonhang_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        // Lấy danh sách người dùng, địa chỉ, khuyến mãi cho dropdown
        $dsuser = $this->user->Users_getAll();
        $dsdc = $this->dc->DiaChiGiaoHang_getAll();
        $dskm = $this->km->KhuyenMai_getAll();
        $result = $this->dh->DonHang_getAll();

        $this->view('Master', [
            'page' => 'donhang_v',
            'madonhang' => '',
            'mauser' => '',
            'madc' => '',
            'makm' => '',
            'tongtien' => '',
            'trangthai' => '',
            'dsuser' => $dsuser,
            'dsdc' => $dsdc,
            'dskm' => $dskm,
            'dulieu' => $result
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_don_hang = $_POST['txtMaDonHang'];
            $ma_user = $_POST['ddlUser'];
            $ma_dia_chi = $_POST['ddlDiaChi'];
            $ma_khuyen_mai = $_POST['ddlKhuyenMai'];
            $tong_tien_hang = $_POST['txtTongTien'];
            $trang_thai_don_hang = $_POST['ddlTrangThai'];

            if ($ma_don_hang == '') {
                echo "<script>alert('Mã đơn hàng không được rỗng!')</script>";
                $this->themmoi();
            } else if ($ma_user == '') {
                echo "<script>alert('Vui lòng chọn người dùng!')</script>";
                $this->themmoi();
            } else {
                $kq1 = $this->dh->checktrungMaDH($ma_don_hang);
                if ($kq1) {
                    echo "<script>alert('Mã đơn hàng đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'donhang_v',
                        'madonhang' => $ma_don_hang,
                        'mauser' => $ma_user,
                        'madc' => $ma_dia_chi,
                        'makm' => $ma_khuyen_mai,
                        'tongtien' => $tong_tien_hang,
                        'trangthai' => $trang_thai_don_hang,
                        'dsuser' => $this->user->Users_getAll(),
                        'dsdc' => $this->dc->DiaChiGiaoHang_getAll(),
                        'dskm' => $this->km->KhuyenMai_getAll()
                    ]);
                } else {
                    $kq = $this->dh->donhang_ins($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien_hang, $trang_thai_don_hang);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'donhang_v',
                            'madonhang' => $ma_don_hang,
                            'mauser' => $ma_user,
                            'madc' => $ma_dia_chi,
                            'makm' => $ma_khuyen_mai,
                            'tongtien' => $tong_tien_hang,
                            'trangthai' => $trang_thai_don_hang,
                            'dsuser' => $this->user->Users_getAll(),
                            'dsdc' => $this->dc->DiaChiGiaoHang_getAll(),
                            'dskm' => $this->km->KhuyenMai_getAll()
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_don_hang = $_POST['txtMaDonHang'] ?? '';
        $ma_user = $_POST['txtMaUser'] ?? '';

        // LẤY DỮ LIỆU THEO MÃ ĐƠN HÀNG + MÃ NGƯỜI DÙNG
        $result = $this->dh->DonHang_find($ma_don_hang, $ma_user);

        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'danhsachdonhang_v',
            'madonhang' => $ma_don_hang,
            'mauser' => $ma_user,
            'dulieu' => $result
        ]);
    }

    function sua($ma_don_hang)
    {
        $result = $this->dh->DonHang_getById($ma_don_hang);
        $row = mysqli_fetch_array($result);
        
        // Lấy danh sách người dùng, địa chỉ, khuyến mãi cho dropdown
        $dsuser = $this->user->Users_getAll();
        $dsdc = $this->dc->DiaChiGiaoHang_getAll();
        $dskm = $this->km->KhuyenMai_getAll();

        $this->view('Master', [
            'page' => 'donhang_sua',
            'madonhang' => $row['ma_don_hang'],
            'mauser' => $row['ma_user'],
            'madc' => $row['ma_dia_chi'],
            'makm' => $row['ma_khuyen_mai'],
            'tongtien' => $row['tong_tien_hang'],
            'trangthai' => $row['trang_thai_don_hang'],
            'dsuser' => $dsuser,
            'dsdc' => $dsdc,
            'dskm' => $dskm
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_don_hang = $_POST['txtMaDonHang'];
            $ma_user = $_POST['ddlUser'];
            $ma_dia_chi = $_POST['ddlDiaChi'];
            $ma_khuyen_mai = $_POST['ddlKhuyenMai'];
            $tong_tien_hang = $_POST['txtTongTien'];
            $trang_thai_don_hang = $_POST['ddlTrangThai'];

            $kq = $this->dh->DonHang_update($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien_hang, $trang_thai_don_hang);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";

            $this->Get_data();
        }
    }

    function xoa($ma_don_hang)
    {
        $kq = $this->dh->DonHang_delete($ma_don_hang);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Donhang/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Donhang/danhsach') . "';</script>";
    }
}