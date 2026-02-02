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

    // Helper method to get cart data for logged-in users
    private function getCartData()
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $ma_user = $_SESSION['user_id'];

        // Lấy giỏ hàng của người dùng
        $gio_hang = $this->gh->GioHang_getByUser($ma_user);
        $row = mysqli_fetch_assoc($gio_hang);

        if ($row) {
            $ma_gio_hang = $row['ma_gio_hang'];
            $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
            return $chi_tiet_gio_hang;
        } else {
            return null;
        }
    }

    // Override the view method to always include cart data
    public function view($view, $data = [])
    {
        // Add cart data to all views for logged-in users
        $data['chi_tiet_gio_hang'] = $this->getCartData();

        parent::view($view, $data);
    }

    function Get_data()
    {
        // Hiển thị trang chủ cho khách hàng
        $dssp = $this->sp->SanPham_getAll();
        $dsdm = $this->dm->DanhMuc_getAll();

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_home',
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

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_sanpham',
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

        // Lấy biến thể đầu tiên để làm hình ảnh chính
        $bien_the_first = null;
        if (mysqli_num_rows($bien_the) > 0) {
            mysqli_data_seek($bien_the, 0); // Reset pointer to beginning
            $bien_the_first = mysqli_fetch_assoc($bien_the);
            mysqli_data_seek($bien_the, 0); // Reset pointer back to beginning for later use
        }

        // Lấy đánh giá của sản phẩm
        $danh_gia = $this->dg->DanhGia_getByProduct($ma_san_pham);

        // Tính điểm trung bình
        $avg_rating = $this->dg->DanhGia_getAvgRatingByProduct($ma_san_pham);

        // Lấy sản phẩm tương tự
        $sql_similar = "SELECT s.*,
                               (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL LIMIT 1) as img_bien_the,
                               (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND gia IS NOT NULL LIMIT 1) as gia,
                               dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap
                        FROM san_pham s
                        LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                        LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                        LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                        WHERE s.ma_danh_muc = '" . mysqli_real_escape_string($this->sp->con, $san_pham['ma_danh_muc']) . "'
                        AND s.ma_san_pham != '" . mysqli_real_escape_string($this->sp->con, $ma_san_pham) . "'
                        GROUP BY s.ma_san_pham
                        ORDER BY s.ma_san_pham DESC
                        LIMIT 4";
        $similar_products = mysqli_query($this->sp->con, $sql_similar);

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_chitietsp',
            'san_pham' => $san_pham,
            'bien_the' => $bien_the,
            'bien_the_first' => $bien_the_first,
            'danh_gia' => $danh_gia,
            'avg_rating' => $avg_rating,
            'similar_products' => $similar_products
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

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_giohang',
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

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_thanhtoan',
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
            $ma_dia_chi = trim($_POST['ddlDiaChi']);
            $ma_khuyen_mai = trim($_POST['ddlKhuyenMai']) ?: null;
            $ghi_chu = trim($_POST['txtGhiChu']) ?? '';
            $payment_method = trim($_POST['payment_method']) ?? 'cod'; // cod (cash on delivery) or bank (online payment)
            $ho_ten = trim($_POST['txtHoTen']);
            $so_dien_thoai = trim($_POST['txtSoDienThoai']);
            $email = trim($_POST['txtEmail']);

            // Validate required fields
            $errors = [];

            if (empty($ma_dia_chi)) {
                $errors[] = "Vui lòng chọn địa chỉ giao hàng.";
            }

            if (empty($ho_ten)) {
                $errors[] = "Vui lòng nhập họ và tên.";
            } elseif (strlen($ho_ten) < 2) {
                $errors[] = "Họ và tên phải có ít nhất 2 ký tự.";
            }

            if (empty($so_dien_thoai)) {
                $errors[] = "Vui lòng nhập số điện thoại.";
            } elseif (!preg_match('/^0[0-9]{9,10}$/', $so_dien_thoai)) {
                $errors[] = "Số điện thoại không hợp lệ.";
            }

            if (empty($email)) {
                $errors[] = "Vui lòng nhập email.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ.";
            }

            // Check if payment method is valid
            if (!in_array($payment_method, ['cod', 'bank'])) {
                $errors[] = "Phương thức thanh toán không hợp lệ.";
            }

            // Lấy giỏ hàng của người dùng
            $gio_hang = $this->gh->GioHang_getByUser($ma_user);
            $row = mysqli_fetch_assoc($gio_hang);

            if (!$row) {
                $errors[] = "Giỏ hàng của bạn đang trống.";
            }

            if (!empty($errors)) {
                // Return to checkout page with errors
                $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($row['ma_gio_hang'] ?? '');
                $dia_chi = $this->dc->DiaChiGiaoHang_getByUser($ma_user);

                $this->view('Khachhang_Master', [
                    'page' => 'Khachhang/khachhang_thanhtoan',
                    'chi_tiet_gio_hang' => $chi_tiet_gio_hang,
                    'dia_chi' => $dia_chi,
                    'errors' => $errors,
                    'old_data' => [
                        'ho_ten' => $ho_ten,
                        'so_dien_thoai' => $so_dien_thoai,
                        'email' => $email,
                        'ghi_chu' => $ghi_chu,
                        'dia_chi_selected' => $ma_dia_chi,
                        'payment_method' => $payment_method
                    ]
                ]);
                return;
            }

            if ($row) {
                $ma_gio_hang = $row['ma_gio_hang'];
                $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);

                // Tính tổng tiền và kiểm tra số lượng tồn kho
                $tong_tien = 0;
                $chi_tiet_gio_hang_array = []; // Store cart items for later use
                $out_of_stock_items = [];

                while ($ct = mysqli_fetch_assoc($chi_tiet_gio_hang)) {
                    // Kiểm tra số lượng tồn kho
                    $bien_the = $this->bt->BienThe_getById($ct['ma_bien_the']);
                    $bt_info = mysqli_fetch_assoc($bien_the);

                    if ($ct['so_luong'] > $bt_info['so_luong_kho']) {
                        $out_of_stock_items[] = $bt_info['ten_bien_the'];
                    } else {
                        $chi_tiet_gio_hang_array[] = $ct;
                        $tong_tien += $ct['gia'] * $ct['so_luong'];
                    }
                }

                // Nếu có sản phẩm hết hàng
                if (!empty($out_of_stock_items)) {
                    $errors[] = "Một số sản phẩm đã hết hàng: " . implode(', ', $out_of_stock_items);

                    $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($row['ma_gio_hang']);
                    $dia_chi = $this->dc->DiaChiGiaoHang_getByUser($ma_user);

                    $this->view('Khachhang_Master', [
                        'page' => 'Khachhang/khachhang_thanhtoan',
                        'chi_tiet_gio_hang' => $chi_tiet_gio_hang,
                        'dia_chi' => $dia_chi,
                        'errors' => $errors,
                        'old_data' => [
                            'ho_ten' => $ho_ten,
                            'so_dien_thoai' => $so_dien_thoai,
                            'email' => $email,
                            'ghi_chu' => $ghi_chu,
                            'dia_chi_selected' => $ma_dia_chi,
                            'payment_method' => $payment_method
                        ]
                    ]);
                    return;
                }

                // Tạo mã đơn hàng
                $ma_don_hang = 'DH' . time();

                // Thêm đơn hàng vào cơ sở dữ liệu
                $this->dh->donhang_ins($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien, 'cho_duyet');

                // Thêm chi tiết đơn hàng
                foreach ($chi_tiet_gio_hang_array as $ct) {
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

                // Nếu là thanh toán online (VNPAY), chuyển hướng đến cổng thanh toán
                if ($payment_method === 'bank') {
                    // Chuyển hướng đến cổng thanh toán VNPAY
                    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                    $vnp_Returnurl = "http://localhost/Banhang/Khachhang/xulythanhtoan";

                    $vnp_TmnCode = 'YOUR_VNP_TMNCODE'; // Mã website tại VNPAY
                    $vnp_HashSecret = 'YOUR_VNP_HASHSECRET'; // Chuỗi bí mật

                    $vnp_TxnRef = $ma_don_hang; // Mã đơn hàng
                    $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $ma_don_hang;
                    $vnp_OrderType = 'billpayment';
                    $vnp_Amount = $tong_tien * 100; // Số tiền cần nhân với 100 để đúng định dạng
                    $vnp_Locale = 'vn';
                    $vnp_BankCode = 'NCB';
                    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

                    $inputData = array(
                        "vnp_Version" => "2.1.0",
                        "vnp_TmnCode" => $vnp_TmnCode,
                        "vnp_Amount" => $vnp_Amount,
                        "vnp_Command" => "pay",
                        "vnp_CreateDate" => date('YmdHis'),
                        "vnp_CurrCode" => "VND",
                        "vnp_IpAddr" => $vnp_IpAddr,
                        "vnp_Locale" => $vnp_Locale,
                        "vnp_OrderInfo" => $vnp_OrderInfo,
                        "vnp_OrderType" => $vnp_OrderType,
                        "vnp_ReturnUrl" => $vnp_Returnurl,
                        "vnp_TxnRef" => $vnp_TxnRef,
                    );

                    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                        $inputData['vnp_BankCode'] = $vnp_BankCode;
                    }

                    ksort($inputData);
                    $query = "";
                    $i = 0;
                    $hashdata = "";
                    foreach ($inputData as $key => $value) {
                        if ($i == 1) {
                            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                        } else {
                            $hashdata .= urlencode($key) . "=" . urlencode($value);
                            $i = 1;
                        }
                        $query .= urlencode($key) . "=" . urlencode($value) . '&';
                    }

                    $vnp_Url = $vnp_Url . "?" . $query;
                    if (isset($vnp_HashSecret)) {
                        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
                        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                    }

                    header('Location: ' . $vnp_Url);
                    die();
                } else {
                    // COD - Thanh toán khi nhận hàng, chuyển đến trang cảm ơn
                    $this->view('Khachhang_Master', [
                        'page' => 'Khachhang/khachhang_camon',
                        'ma_don_hang' => $ma_don_hang
                    ]);
                }
            }
        }
    }

    // Xử lý kết quả thanh toán từ VNPAY
    function xulythanhtoan()
    {
        $vnp_HashSecret = 'YOUR_VNP_HASHSECRET'; // Chuỗi bí mật

        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $vnp_ResponeCode = $_GET['vnp_ResponseCode'];
        $vnp_TxnRef = $_GET['vnp_TxnRef'];

        if ($vnp_SecureHash == $secureHash) {
            if ($vnp_ResponeCode == '00') {
                // Thanh toán thành công
                // Cập nhật trạng thái đơn hàng thành 'da_thanh_toan'
                $don_hang_model = $this->model("DonHang_m");
                $don_hang = $don_hang_model->DonHang_getById($vnp_TxnRef);
                $dh_info = mysqli_fetch_assoc($don_hang);

                if ($dh_info) {
                    // Cập nhật trạng thái đơn hàng
                    $don_hang_model->DonHang_update($vnp_TxnRef, $dh_info['ma_user'], $dh_info['ma_dia_chi'], $dh_info['ma_khuyen_mai'], $dh_info['tong_tien_hang'], 'da_thanh_toan');

                    // Thêm thông tin thanh toán
                    $thanh_toan_model = $this->model("ThanhToan_m");
                    $ma_thanh_toan = 'TT' . time();
                    $so_tien = $dh_info['tong_tien_hang'];
                    $thanh_toan_model->thanh_toan_ins($ma_thanh_toan, $vnp_TxnRef, 'VNPAY', $so_tien, 'thanh_cong', date('Y-m-d H:i:s'));
                }

                $this->view('Khachhang_Master', [
                    'page' => 'Khachhang/khachhang_camon',
                    'ma_don_hang' => $vnp_TxnRef,
                    'success_message' => 'Thanh toán thành công!'
                ]);
            } else {
                // Thanh toán thất bại
                $this->view('Khachhang_Master', [
                    'page' => 'Khachhang/khachhang_thanhtoan_thatbai',
                    'error_message' => 'Thanh toán thất bại. Vui lòng thử lại.'
                ]);
            }
        } else {
            // Lỗi xác thực
            $this->view('Khachhang_Master', [
                'page' => 'Khachhang/khachhang_thanhtoan_thatbai',
                'error_message' => 'Lỗi xác thực thanh toán. Vui lòng thử lại.'
            ]);
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

        // Lấy chi tiết sản phẩm cho từng đơn hàng
        $don_hang_with_details = [];
        while ($dh = mysqli_fetch_assoc($don_hang)) {
            $chi_tiet_don_hang = $this->dh->getChiTietDonHang($dh['ma_don_hang']);
            // Convert mysqli_result to array to allow multiple iterations
            $chi_tiet_array = [];
            while ($ct = mysqli_fetch_assoc($chi_tiet_don_hang)) {
                $chi_tiet_array[] = $ct;
            }
            $dh['chi_tiet'] = $chi_tiet_array;
            $don_hang_with_details[] = $dh;
        }

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_lichsu',
            'don_hang' => $don_hang_with_details
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

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_chitietdh',
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

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_taikhoan',
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

    // Đổi mật khẩu
    function doimatkhau()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ma_user = $_POST['txtMaUser'];
            $current_password = $_POST['txtCurrentPassword'];
            $new_password = $_POST['txtNewPassword'];

            // Lấy thông tin người dùng hiện tại
            $user_info = $this->user->Users_getById($ma_user);
            $user = mysqli_fetch_assoc($user_info);

            if ($user) {
                // Kiểm tra mật khẩu hiện tại có đúng không (mật khẩu không được mã hóa)
                if ($current_password === $user['password']) {
                    // Cập nhật mật khẩu mới (không mã hóa)
                    $result = $this->user->Users_update(
                        $ma_user,
                        $user['ten_user'],
                        $user['full_name'],
                        $new_password,
                        $user['email'],
                        $user['phan_quyen'],
                        $user['so_dien_thoai']
                    );

                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật mật khẩu!']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng!']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy người dùng!']);
            }
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

    // Lọc sản phẩm theo mức giá
    function filter_by_price()
    {
        header('Content-Type: application/json');

        if (isset($_POST['price_range'])) {
            $price_range = $_POST['price_range'];

            // Gọi phương thức từ model để lọc sản phẩm theo mức giá
            $result = $this->sp->SanPham_filterByCategoryAndPrice('', $price_range);

            // Check if query was successful
            if (!$result) {
                echo json_encode([
                    'products' => [],
                    'count' => 0,
                    'error' => 'Database query failed: ' . mysqli_error($this->sp->con)
                ]);
                return;
            }

            $products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }

            echo json_encode([
                'products' => $products,
                'count' => count($products)
            ]);
        } else {
            echo json_encode([
                'products' => [],
                'count' => 0
            ]);
        }
    }

    // Lọc sản phẩm theo danh mục
    function filter_by_category()
    {
        header('Content-Type: application/json');

        if (isset($_POST['category_id'])) {
            $category_id = $_POST['category_id'];

            // Gọi phương thức từ model để lọc sản phẩm theo danh mục
            $result = $this->sp->SanPham_filterByCategoryAndPrice($category_id, '');

            // Check if query was successful
            if (!$result) {
                echo json_encode([
                    'products' => [],
                    'count' => 0,
                    'error' => 'Database query failed: ' . mysqli_error($this->sp->con)
                ]);
                return;
            }

            $products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }

            echo json_encode([
                'products' => $products,
                'count' => count($products)
            ]);
        } else {
            echo json_encode([
                'products' => [],
                'count' => 0
            ]);
        }
    }

    // Lọc sản phẩm theo cả danh mục và mức giá
    function filter_by_both()
    {
        header('Content-Type: application/json');

        $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
        $price_range = isset($_POST['price_range']) ? $_POST['price_range'] : '';

        // Gọi phương thức từ model để lọc sản phẩm
        $result = $this->sp->SanPham_filterByCategoryAndPrice($category_id, $price_range);

        // Check if query was successful
        if (!$result) {
            echo json_encode([
                'products' => [],
                'count' => 0,
                'error' => 'Database query failed: ' . mysqli_error($this->sp->con)
            ]);
            return;
        }

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        echo json_encode([
            'products' => $products,
            'count' => count($products)
        ]);
    }
}
