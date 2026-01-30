<?php
class Khachhang extends controller
{
    private $sp;
    private $dm;
    private $th;
    private $bt;
    private $gh;
    private $ctgh;
    private $dh;
    private $ctdh;
    private $dc;
    private $dg;
    private $user;

    function __construct()
    {
        $this->sp = $this->model("SanPham_m");
        $this->dm = $this->model("DanhMuc_m");
        $this->th = $this->model("ThuongHieu_m");
        $this->bt = $this->model("BienThe_m");
        $this->gh = $this->model("GioHang_m");
        $this->ctgh = $this->model("ChiTietGioHang_m");
        $this->dh = $this->model("DonHang_m");
        $this->ctdh = $this->model("ChiTietDonHang_m");
        $this->dc = $this->model("DiaChiGiaoHang_m");
        $this->dg = $this->model("DanhGia_m");
        $this->user = $this->model("Users_m");
    }

    function Get_data()
    {
        // Hiển thị trang chủ cho khách hàng
        $dssp = $this->sp->SanPham_getAll();
        $dsdm = $this->dm->DanhMuc_getAll();

        $this->view('Master', [
            'page' => 'khachhang_home',
            'dssp' => $dssp,
            'dsdm' => $dsdm
        ]);
    }

    // Hiển thị sản phẩm theo danh mục
    function sanpham_theo_danhmuc($ma_danh_muc)
    {
        $sql = "SELECT s.*, dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap 
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                WHERE s.ma_danh_muc = '$ma_danh_muc'
                ORDER BY LENGTH(s.ma_san_pham), s.ma_san_pham";
        $dssp = mysqli_query($this->sp->con, $sql);
        $dsdm = $this->dm->DanhMuc_getAll();

        $this->view('Master', [
            'page' => 'khachhang_sanpham',
            'dssp' => $dssp,
            'dsdm' => $dsdm
        ]);
    }

    // Hiển thị chi tiết sản phẩm
    function chitietsanpham($ma_san_pham)
    {
        $sp = $this->sp->SanPham_getById($ma_san_pham);
        $san_pham = mysqli_fetch_assoc($sp);

        // Lấy các biến thể của sản phẩm
        $bien_the = $this->bt->BienThe_getByProduct($ma_san_pham);

        // Lấy đánh giá của sản phẩm
        $danh_gia = $this->dg->DanhGia_getByProduct($ma_san_pham);

        // Tính điểm trung bình
        $avg_rating = $this->dg->DanhGia_getAvgRatingByProduct($ma_san_pham);

        $this->view('Master', [
            'page' => 'khachhang_chitietsp',
            'san_pham' => $san_pham,
            'bien_the' => $bien_the,
            'danh_gia' => $danh_gia,
            'avg_rating' => $avg_rating
        ]);
    }

    // Thêm vào giỏ hàng
    function themvaogio($ma_bien_the)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        $ma_user = $_SESSION['user_id'];

        // Kiểm tra xem đã có giỏ hàng chưa, nếu chưa thì tạo mới
        $gio_hang = $this->gh->GioHang_getByUser($ma_user);
        $row = mysqli_fetch_assoc($gio_hang);

        if (!$row) {
            // Tạo giỏ hàng mới
            $ma_gio_hang = 'GH' . time(); // Tạo mã giỏ hàng duy nhất
            $this->gh->giohang_ins($ma_gio_hang, $ma_user);
        } else {
            $ma_gio_hang = $row['ma_gio_hang'];
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $ctgh_check = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
        $found = false;

        while ($ct_row = mysqli_fetch_assoc($ctgh_check)) {
            if ($ct_row['ma_bien_the'] == $ma_bien_the) {
                // Nếu đã có, tăng số lượng lên 1
                $new_so_luong = $ct_row['so_luong'] + 1;
                $this->ctgh->ChiTietGioHang_update($ma_gio_hang, $ma_bien_the, $new_so_luong);
                $found = true;
                break;
            }
        }

        // Nếu chưa có, thêm mới vào giỏ hàng
        if (!$found) {
            $this->ctgh->chitietgiohang_ins($ma_gio_hang, $ma_bien_the, 1);
        }

        // Quay lại trang trước đó
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    // Hiển thị giỏ hàng
    function giohang()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        $ma_user = $_SESSION['user_id'];

        // Lấy giỏ hàng của người dùng
        $gio_hang = $this->gh->GioHang_getByUser($ma_user);
        $row = mysqli_fetch_assoc($gio_hang);

        if ($row) {
            $ma_gio_hang = $row['ma_gio_hang'];
            $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
        } else {
            $chi_tiet_gio_hang = null;
        }

        $this->view('Master', [
            'page' => 'khachhang_giohang',
            'chi_tiet_gio_hang' => $chi_tiet_gio_hang
        ]);
    }

    // Cập nhật số lượng trong giỏ hàng
    function capnhatgiohang()
    {
        if (isset($_POST['update_cart'])) {
            $ma_gio_hang = $_POST['ma_gio_hang'];
            $ma_bien_the = $_POST['ma_bien_the'];
            $so_luong = $_POST['so_luong'];

            if ($so_luong <= 0) {
                // Nếu số lượng <= 0 thì xóa sản phẩm khỏi giỏ
                $this->ctgh->ChiTietGioHang_delete($ma_gio_hang, $ma_bien_the);
            } else {
                // Cập nhật số lượng
                $this->ctgh->ChiTietGioHang_update($ma_gio_hang, $ma_bien_the, $so_luong);
            }

            header('Location: http://localhost/Banhang/Khachhang/giohang');
        }
    }

    // Xóa sản phẩm khỏi giỏ hàng
    function xoakhoigio($ma_gio_hang, $ma_bien_the)
    {
        $this->ctgh->ChiTietGioHang_delete($ma_gio_hang, $ma_bien_the);
        header('Location: http://localhost/Banhang/Khachhang/giohang');
    }

    // Tiến hành thanh toán
    function thanhtoan()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        $ma_user = $_SESSION['user_id'];

        // Lấy giỏ hàng của người dùng
        $gio_hang = $this->gh->GioHang_getByUser($ma_user);
        $row = mysqli_fetch_assoc($gio_hang);

        if ($row) {
            $ma_gio_hang = $row['ma_gio_hang'];
            $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);

            // Lấy địa chỉ giao hàng của người dùng
            $dia_chi = $this->dc->DiaChiGiaoHang_getByUser($ma_user);
        } else {
            $chi_tiet_gio_hang = null;
            $dia_chi = null;
        }

        $this->view('Master', [
            'page' => 'khachhang_thanhtoan',
            'chi_tiet_gio_hang' => $chi_tiet_gio_hang,
            'dia_chi' => $dia_chi
        ]);
    }

    // Xử lý đặt hàng
    function datHang()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        if (isset($_POST['btnDatHang'])) {
            $ma_user = $_SESSION['user_id'];
            $ma_dia_chi = $_POST['ddlDiaChi'];
            $ma_khuyen_mai = $_POST['ddlKhuyenMai'] ?: null;
            $ghi_chu = $_POST['txtGhiChu'] ?? '';

            // Lấy giỏ hàng của người dùng
            $gio_hang = $this->gh->GioHang_getByUser($ma_user);
            $row = mysqli_fetch_assoc($gio_hang);

            if ($row) {
                $ma_gio_hang = $row['ma_gio_hang'];
                $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);

                // Tính tổng tiền
                $tong_tien = 0;
                while ($ct = mysqli_fetch_assoc($chi_tiet_gio_hang)) {
                    $tong_tien += $ct['gia'] * $ct['so_luong'];
                }

                // Tạo mã đơn hàng
                $ma_don_hang = 'DH' . time();

                // Thêm đơn hàng vào cơ sở dữ liệu
                $this->dh->donhang_ins($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien, 'cho_duyet');

                // Thêm chi tiết đơn hàng
                $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
                while ($ct = mysqli_fetch_assoc($chi_tiet_gio_hang)) {
                    $ma_ctdh = 'CTDH' . time() . rand(1000, 9999);
                    $this->ctdh->chitietdonhang_ins($ma_ctdh, $ma_don_hang, $ct['ma_bien_the'], $ct['so_luong'], $ct['gia']);

                    // Cập nhật số lượng kho
                    $bien_the = $this->bt->BienThe_getById($ct['ma_bien_the']);
                    $bt_info = mysqli_fetch_assoc($bien_the);
                    $new_so_luong_kho = $bt_info['so_luong_kho'] - $ct['so_luong'];
                    $this->bt->BienThe_update($ct['ma_bien_the'], $bt_info['ma_san_pham'], $bt_info['ten_bien_the'], $bt_info['mau_sac'], $bt_info['ram'], $bt_info['dung_luong'], $bt_info['gia'], $new_so_luong_kho);
                }

                // Cập nhật trạng thái giỏ hàng thành 'ordered'
                $this->gh->GioHang_update($ma_gio_hang, $ma_user, 'ordered');

                // Chuyển hướng đến trang cảm ơn
                $this->view('Master', [
                    'page' => 'khachhang_camon',
                    'ma_don_hang' => $ma_don_hang
                ]);
            }
        }
    }

    // Hiển thị lịch sử mua hàng
    function lichsumuahang()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        $ma_user = $_SESSION['user_id'];

        // Lấy các đơn hàng của người dùng
        $sql = "SELECT dh.*, dc.ho_ten as ten_nguoi_nhan, dc.dia_chi, dc.so_dien_thoai
                FROM don_hang dh
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                WHERE dh.ma_user = '$ma_user'
                ORDER BY dh.ngay_tao DESC";
        $don_hang = mysqli_query($this->dh->con, $sql);

        $this->view('Master', [
            'page' => 'khachhang_lichsu',
            'don_hang' => $don_hang
        ]);
    }

    // Hiển thị chi tiết đơn hàng
    function chitietdonhang($ma_don_hang)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        $ma_user = $_SESSION['user_id'];

        // Kiểm tra đơn hàng có thuộc về người dùng này không
        $don_hang = $this->dh->DonHang_getById($ma_don_hang);
        $dh_info = mysqli_fetch_assoc($don_hang);

        if ($dh_info['ma_user'] != $ma_user) {
            echo "<script>alert('Bạn không có quyền xem đơn hàng này!');</script>";
            header('Location: http://localhost/Banhang/Khachhang/lichsumuahang');
            exit;
        }

        // Lấy chi tiết đơn hàng
        $chi_tiet_don_hang = $this->ctdh->ChiTietDonHang_getByOrderId($ma_don_hang);

        $this->view('Master', [
            'page' => 'khachhang_chitietdh',
            'don_hang' => $dh_info,
            'chi_tiet_don_hang' => $chi_tiet_don_hang
        ]);
    }

    // Hiển thị trang quản lý tài khoản
    function taikhoan()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/Banhang/Login');
            exit;
        }

        $ma_user = $_SESSION['user_id'];
        $user_info = $this->user->Users_getById($ma_user);
        $user = mysqli_fetch_assoc($user_info);

        // Lấy địa chỉ của người dùng
        $dia_chi = $this->dc->DiaChiGiaoHang_getByUser($ma_user);

        $this->view('Master', [
            'page' => 'khachhang_taikhoan',
            'user' => $user,
            'dia_chi' => $dia_chi
        ]);
    }

    // Cập nhật thông tin tài khoản
    function capnhatTaikhoan()
    {
        if (isset($_POST['btnCapNhatTaiKhoan'])) {
            $ma_user = $_POST['txtMaUser'];
            $ten_user = $_POST['txtTenUser'];
            $full_name = $_POST['txtFullName'];
            $email = $_POST['txtEmail'];
            $so_dien_thoai = $_POST['txtSoDienThoai'];
            $password = $_POST['txtPassword'];

            // Nếu có nhập mật khẩu mới thì mã hóa, nếu không thì giữ nguyên
            $password_hash = !empty($password) ? md5($password) : $_POST['txtOldPassword'];

            $result = $this->user->Users_update($ma_user, $ten_user, $full_name, $password_hash, $email, 'khach_hang', $so_dien_thoai);

            if ($result) {
                echo "<script>alert('Cập nhật thông tin thành công!');</script>";
            } else {
                echo "<script>alert('Cập nhật thông tin thất bại!');</script>";
            }

            header('Location: http://localhost/Banhang/Khachhang/taikhoan');
        }
    }

    // Thêm địa chỉ giao hàng
    function themDiaChi()
    {
        if (isset($_POST['btnThemDiaChi'])) {
            $ma_dia_chi = $_POST['txtMaDiaChi'];
            $ma_user = $_SESSION['user_id'];
            $ho_ten = $_POST['txtHoTen'];
            $so_dien_thoai = $_POST['txtSoDienThoai'];
            $dia_chi = $_POST['txtDiaChi'];
            $mac_dinh = isset($_POST['chkMacDinh']) ? 1 : 0;

            $result = $this->dc->diachigiaohang_ins($ma_dia_chi, $ma_user, $ho_ten, $so_dien_thoai, $dia_chi, $mac_dinh);

            if ($result) {
                echo "<script>alert('Thêm địa chỉ thành công!');</script>";
            } else {
                echo "<script>alert('Thêm địa chỉ thất bại!');</script>";
            }

            header('Location: http://localhost/Banhang/Khachhang/taikhoan');
        }
    }

    // Cập nhật địa chỉ giao hàng
    function capnhatDiaChi()
    {
        if (isset($_POST['btnCapNhatDiaChi'])) {
            $ma_dia_chi = $_POST['txtMaDiaChi'];
            $ma_user = $_SESSION['user_id'];
            $ho_ten = $_POST['txtHoTen'];
            $so_dien_thoai = $_POST['txtSoDienThoai'];
            $dia_chi = $_POST['txtDiaChi'];
            $mac_dinh = isset($_POST['chkMacDinh']) ? 1 : 0;

            $result = $this->dc->DiaChiGiaoHang_update($ma_dia_chi, $ma_user, $ho_ten, $so_dien_thoai, $dia_chi, $mac_dinh);

            if ($result) {
                echo "<script>alert('Cập nhật địa chỉ thành công!');</script>";
            } else {
                echo "<script>alert('Cập nhật địa chỉ thất bại!');</script>";
            }

            header('Location: http://localhost/Banhang/Khachhang/taikhoan');
        }
    }

    // Xóa địa chỉ giao hàng
    function xoaDiaChi($ma_dia_chi)
    {
        $result = $this->dc->DiaChiGiaoHang_delete($ma_dia_chi);

        if ($result) {
            echo "<script>alert('Xóa địa chỉ thành công!');</script>";
        } else {
            echo "<script>alert('Xóa địa chỉ thất bại!');</script>";
        }

        header('Location: http://localhost/Banhang/Khachhang/taikhoan');
    }
}
