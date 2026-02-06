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
        // Hiển thị trang chủ cho khách hàng với phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12; // 12 sản phẩm mỗi trang
        
        // Lấy tổng số lượng sản phẩm
        $total_products = $this->sp->SanPham_getTotalCount();
        $total_pages = ceil($total_products / $limit);
        
        // Lấy sản phẩm theo trang
        $dssp = $this->sp->SanPham_getAllWithPagination($page, $limit);
        $dsdm = $this->dm->DanhMuc_getAll();

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_home',
            'dssp' => $dssp,
            'dsdm' => $dsdm,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_products' => $total_products
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

        // Lấy các biến thể của sản phẩm cùng với thông tin tồn kho
        $sql_bien_the = "SELECT bt.*, sp.ten_san_pham
                         FROM bien_the bt
                         LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                         WHERE bt.ma_san_pham = '$ma_san_pham'
                         ORDER BY bt.ma_bien_the";
        $bien_the = mysqli_query($this->bt->con, $sql_bien_the);

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

        // Lấy phân bố đánh giá theo số sao
        $star_distribution = $this->dg->DanhGia_getStarDistribution($ma_san_pham);

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
            'star_distribution' => $star_distribution,
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

        $so_luong_them = isset($_POST['so_luong']) ? (int)$_POST['so_luong'] : 1;

        // Kiểm tra xem đã có giỏ hàng chưa, nếu chưa thì tạo mới
        $gio_hang = $this->gh->GioHang_getByUser($ma_user);
        $row = mysqli_fetch_assoc($gio_hang);

        if (!$row) {
            // Tạo giỏ hàng mới
            $ma_gio_hang = $this->gh->getNextCartId(); // Tạo mã giỏ hàng duy nhất theo thứ tự tăng dần
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
                $new_so_luong = $ct_row['so_luong'] + $so_luong_them;
                $this->ctgh->ChiTietGioHang_update($ma_gio_hang, $ma_bien_the, $new_so_luong);
                $found = true;
                break;
            }
        }

        // Nếu chưa có, thêm mới vào giỏ hàng
        if (!$found) {
            $this->ctgh->chitietgiohang_ins($ma_gio_hang, $ma_bien_the, $so_luong_them);
        }

        // Quay lại trang trước đó
        if (!isset($_POST['so_luong'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
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

        $detailed_cart = []; // Mảng chứa dữ liệu đầy đủ chi tiết

        if ($row) {
            $ma_gio_hang = $row['ma_gio_hang'];
            // Lấy danh sách sản phẩm thô trong giỏ
            $result = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);

            if ($result) {
                while ($ct = mysqli_fetch_assoc($result)) {
                    // 1. Lấy chi tiết BIẾN THỂ (để có Màu, RAM, Dung lượng...)
                    $bt_query = $this->bt->BienThe_getById($ct['ma_bien_the']);
                    $bt_info = mysqli_fetch_assoc($bt_query);

                    // 2. Lấy chi tiết SẢN PHẨM (để có Tên, Ảnh gốc)
                    $sp_query = $this->sp->SanPham_getById($bt_info['ma_san_pham']);
                    $sp_info = mysqli_fetch_assoc($sp_query);

                    // 3. Gộp dữ liệu vào mảng item
                    $item = $ct; // Bắt đầu với dữ liệu giỏ hàng (số lượng...)

                    // Bổ sung thông tin từ bảng Sản Phẩm
                    $item['ten_san_pham'] = $sp_info['ten_san_pham'];

                    // Bổ sung thông tin từ bảng Biến Thể (QUAN TRỌNG)
                    $item['ten_bien_the'] = $bt_info['ten_bien_the'];
                    $item['mau_sac'] = $bt_info['mau_sac'];
                    $item['dung_luong'] = $bt_info['dung_luong'];
                    $item['ram'] = $bt_info['ram'];
                    $item['gia'] = $bt_info['gia']; // Lấy giá hiện tại
                    $item['img_bien_the'] = $bt_info['img_bien_the']; // Ảnh riêng của biến thể

                    $detailed_cart[] = $item;
                }
            }
        }

        // Truyền biến 'detailed_cart' sang View
        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_giohang',
            'detailed_cart' => $detailed_cart
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
        
        $is_buy_now = isset($_GET['buynow']) && $_GET['buynow'] == 1;
        
        $filtered_cart_items = [];
        $tong_tien_thanh_toan = 0;

        if ($is_buy_now && isset($_GET['items']) && isset($_GET['qty'])) {
            // --- TRƯỜNG HỢP MUA NGAY: Tự dựng dữ liệu không qua giỏ hàng ---
            $ma_bien_the = $_GET['items']; // Lấy 1 sản phẩm
            $so_luong = (int)$_GET['qty'];
            
            // Lấy thông tin biến thể và sản phẩm trực tiếp
            $bt_query = $this->bt->BienThe_getById($ma_bien_the);
            $bt_info = mysqli_fetch_assoc($bt_query);
            
            if ($bt_info) {
                $sp_query = $this->sp->SanPham_getById($bt_info['ma_san_pham']);
                $sp_info = mysqli_fetch_assoc($sp_query);

                // Tạo mảng item giả lập giống cấu trúc giỏ hàng
                $item = [];
                $item['ma_bien_the'] = $ma_bien_the;
                $item['ten_san_pham'] = $sp_info['ten_san_pham'];
                $item['ten_bien_the'] = $bt_info['ten_bien_the'];
                $item['mau_sac'] = $bt_info['mau_sac'];
                $item['dung_luong'] = $bt_info['dung_luong'];
                $item['gia'] = $bt_info['gia'];
                $item['so_luong'] = $so_luong; // Dùng số lượng từ URL
                
                $filtered_cart_items[] = $item;
                $tong_tien_thanh_toan = $item['gia'] * $item['so_luong'];
            }
        } 
        else {
            // --- TRƯỜNG HỢP THANH TOÁN GIỎ HÀNG BÌNH THƯỜNG (Code cũ) ---
            $gio_hang = $this->gh->GioHang_getByUser($ma_user);
            $row = mysqli_fetch_assoc($gio_hang);

            if ($row) {
                $ma_gio_hang = $row['ma_gio_hang'];
                $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
                
                $selected_items = isset($_GET['items']) ? explode(',', $_GET['items']) : [];

                if ($chi_tiet_gio_hang) {
                    while ($item = mysqli_fetch_assoc($chi_tiet_gio_hang)) {
                        // Lấy thêm thông tin chi tiết (như bạn đã sửa ở các bước trước)
                        $bt_query = $this->bt->BienThe_getById($item['ma_bien_the']);
                        $bt_info = mysqli_fetch_assoc($bt_query);
                        $sp_query = $this->sp->SanPham_getById($bt_info['ma_san_pham']);
                        $sp_info = mysqli_fetch_assoc($sp_query);

                        $item['ten_san_pham'] = $sp_info['ten_san_pham'];
                        $item['ten_bien_the'] = $bt_info['ten_bien_the'];
                        $item['mau_sac'] = $bt_info['mau_sac'];
                        $item['dung_luong'] = $bt_info['dung_luong'];
                        $item['gia'] = $bt_info['gia'];

                        if (empty($selected_items) || in_array($item['ma_bien_the'], $selected_items)) {
                            $filtered_cart_items[] = $item;
                            $tong_tien_thanh_toan += ($item['gia'] * $item['so_luong']);
                        }
                    }
                }
            }
        }

        // Lấy thông tin phụ (Địa chỉ, Khuyến mãi...)
        $dia_chi = $this->dc->DiaChiGiaoHang_getByUser($ma_user);
        $ds_khuyen_mai = $this->model("KhuyenMai_m")->KhuyenMai_getAvailable();

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_thanhtoan',
            'ds_sp_thanh_toan' => $filtered_cart_items,
            'dia_chi' => $dia_chi,
            'ds_khuyen_mai' => $ds_khuyen_mai,
            'tong_tien_du kien' => $tong_tien_thanh_toan
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
            $ma_khuyen_mai = !empty($_POST['ddlKhuyenMai']) ? trim($_POST['ddlKhuyenMai']) : null;
            $ghi_chu = trim($_POST['txtGhiChu']) ?? '';
            $payment_method = trim($_POST['payment_method']) ?? 'cod';
            $ho_ten = trim($_POST['txtHoTen']);
            $so_dien_thoai = trim($_POST['txtSoDienThoai']);
            $email = trim($_POST['txtEmail']);

            // Validate cơ bản
            $errors = [];
            if (empty($ma_dia_chi)) $errors[] = "Vui lòng chọn địa chỉ giao hàng.";

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
                // Trả về view báo lỗi (giữ nguyên logic cũ của bạn)
                $gio_hang_chi_tiet = $this->ctgh->ChiTietGioHang_getByCartId($row['ma_gio_hang'] ?? '');

                // Chuyển đổi giỏ hàng chi tiết thành định dạng phù hợp với view
                $filtered_cart_items = [];
                $tong_tien_thanh_toan = 0;

                if ($gio_hang_chi_tiet) {
                    while ($item = mysqli_fetch_assoc($gio_hang_chi_tiet)) {
                        // Lấy thông tin biến thể và sản phẩm
                        $bt_query = $this->bt->BienThe_getById($item['ma_bien_the']);
                        $bt_info = mysqli_fetch_assoc($bt_query);

                        $sp_query = $this->sp->SanPham_getById($bt_info['ma_san_pham']);
                        $sp_info = mysqli_fetch_assoc($sp_query);

                        // Gộp dữ liệu vào mảng item
                        $item['ten_san_pham'] = $sp_info['ten_san_pham'];
                        $item['ten_bien_the'] = $bt_info['ten_bien_the'];
                        $item['mau_sac'] = $bt_info['mau_sac'];
                        $item['dung_luong'] = $bt_info['dung_luong'];
                        $item['ram'] = $bt_info['ram'];
                        $item['gia'] = $bt_info['gia'];
                        $item['img_bien_the'] = $bt_info['img_bien_the'];

                        $filtered_cart_items[] = $item;
                        $tong_tien_thanh_toan += ($item['gia'] * $item['so_luong']);
                    }
                }

                $dia_chi = $this->dc->DiaChiGiaoHang_getByUser($ma_user);

                $this->view('Khachhang_Master', [
                    'page' => 'Khachhang/khachhang_thanhtoan',
                    'ds_sp_thanh_toan' => $filtered_cart_items,
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

                // 1. Lấy danh sách ID sản phẩm cần mua
                $selected_items_filter = [];
                if (isset($_POST['selected_items_str']) && !empty($_POST['selected_items_str'])) {
                    $selected_items_filter = explode(',', $_POST['selected_items_str']);
                }

                // --- 2. KHỞI TẠO BIẾN TỔNG TIỀN (FIX LỖI UNDEFINED VARIABLE) ---
                $is_buy_now = isset($_POST['is_buy_now']) && $_POST['is_buy_now'] == '1';
                $tong_tien_hang = 0; // Quan trọng: Phải khởi tạo ở đây
                $chi_tiet_gio_hang_array = [];
                $out_of_stock_items = [];

                if ($is_buy_now) {
                    // --- LOGIC MUA NGAY: Lấy dữ liệu từ form, KHÔNG lấy từ giỏ hàng ---
                    $ma_bien_the = $_POST['selected_items_str']; // ID sản phẩm
                    $forced_qty = (int)$_POST['forced_qty'];       // Số lượng khách chọn (ví dụ: 2)

                    $bt_query = $this->bt->BienThe_getById($ma_bien_the);
                    $bt_info = mysqli_fetch_assoc($bt_query);

                    // Kiểm tra tồn kho
                    if ($forced_qty > $bt_info['so_luong_kho']) {
                        $out_of_stock_items[] = $bt_info['ten_bien_the'] . " (chỉ còn " . $bt_info['so_luong_kho'] . " sản phẩm)";
                    } else {
                        // Tạo item để lát nữa insert vào đơn hàng
                        $ct = [];
                        $ct['ma_bien_the'] = $ma_bien_the;
                        $ct['so_luong'] = $forced_qty; // Đảm bảo số lượng là 2
                        $ct['gia'] = $bt_info['gia'];

                        $chi_tiet_gio_hang_array[] = $ct;
                        $tong_tien_hang = $ct['gia'] * $ct['so_luong'];
                    }

                } else {
                    // --- LOGIC GIỎ HÀNG BÌNH THƯỜNG (Giữ nguyên logic cũ) ---
                    $gio_hang = $this->gh->GioHang_getByUser($ma_user);
                    $row = mysqli_fetch_assoc($gio_hang);

                    if ($row) {
                        $ma_gio_hang = $row['ma_gio_hang'];
                        $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
                        
                        // ... Copy lại đoạn while loop lấy từ DB như code cũ của bạn ...
                        // Lưu ý: Đoạn này dùng để xử lý khi mua từ Giỏ Hàng
                        while ($ct = mysqli_fetch_assoc($chi_tiet_gio_hang)) {
                            if (!empty($selected_items_filter) && !in_array($ct['ma_bien_the'], $selected_items_filter)) {
                                continue;
                            }

                            $ct['qty_in_db'] = $ct['so_luong'];

                            // Check if forced_qty is set in POST for individual items (used in some cases)
                            $item_forced_qty = isset($_POST['forced_qty']) ? (int)$_POST['forced_qty'] : null;
                            if ($item_forced_qty !== null) {
                                $ct['so_luong'] = $item_forced_qty;
                            }

                            $bien_the = $this->bt->BienThe_getById($ct['ma_bien_the']);
                            $bt_info = mysqli_fetch_assoc($bien_the);

                            if ($ct['so_luong'] > $bt_info['so_luong_kho']) {
                                $out_of_stock_items[] = $bt_info['ten_bien_the'] . " (chỉ còn " . $bt_info['so_luong_kho'] . " sản phẩm)";
                            } else {
                                $chi_tiet_gio_hang_array[] = $ct;
                                $tong_tien_hang += $ct['gia'] * $ct['so_luong']; // Tính tiền theo số lượng mới
                            }
                        }
                    }
                }

                

                // Nếu có sản phẩm hết hàng
                if (!empty($out_of_stock_items)) {
                    $errors[] = "Một số sản phẩm đã hết hàng: " . implode(', ', $out_of_stock_items);

                    $gio_hang_chi_tiet = $this->ctgh->ChiTietGioHang_getByCartId($row['ma_gio_hang']);

                    // Chuyển đổi giỏ hàng chi tiết thành định dạng phù hợp với view
                    $filtered_cart_items = [];
                    $tong_tien_thanh_toan = 0;

                    if ($gio_hang_chi_tiet) {
                        while ($item = mysqli_fetch_assoc($gio_hang_chi_tiet)) {
                            // Lấy thông tin biến thể và sản phẩm
                            $bt_query = $this->bt->BienThe_getById($item['ma_bien_the']);
                            $bt_info = mysqli_fetch_assoc($bt_query);

                            $sp_query = $this->sp->SanPham_getById($bt_info['ma_san_pham']);
                            $sp_info = mysqli_fetch_assoc($sp_query);

                            // Gộp dữ liệu vào mảng item
                            $item['ten_san_pham'] = $sp_info['ten_san_pham'];
                            $item['ten_bien_the'] = $bt_info['ten_bien_the'];
                            $item['mau_sac'] = $bt_info['mau_sac'];
                            $item['dung_luong'] = $bt_info['dung_luong'];
                            $item['ram'] = $bt_info['ram'];
                            $item['gia'] = $bt_info['gia'];
                            $item['img_bien_the'] = $bt_info['img_bien_the'];

                            $filtered_cart_items[] = $item;
                            $tong_tien_thanh_toan += ($item['gia'] * $item['so_luong']);
                        }
                    }

                    $dia_chi = $this->dc->DiaChiGiaoHang_getByUser($ma_user);

                    $this->view('Khachhang_Master', [
                        'page' => 'Khachhang/khachhang_thanhtoan',
                        'ds_sp_thanh_toan' => $filtered_cart_items,
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

                // --- 3. TÍNH TIỀN THANH TOÁN ---
                $so_tien_thanh_toan = $tong_tien_hang;
                $tien_giam = 0;

                if ($ma_khuyen_mai) {
                    $km_model = $this->model("KhuyenMai_m");
                    $km_info_result = $km_model->KhuyenMai_getById($ma_khuyen_mai);
                    if($km_info_result && mysqli_num_rows($km_info_result) > 0){
                         $km_info = mysqli_fetch_assoc($km_info_result);
                         $tien_giam = $km_info['tien_khuyen_mai'];
                    }
                }

                $so_tien_thanh_toan = $tong_tien_hang - $tien_giam;
                if ($so_tien_thanh_toan < 0) $so_tien_thanh_toan = 0;

                // Tạo mã đơn hàng theo thứ tự tăng dần
                $ma_don_hang = $this->dh->getNextOrderId();

                // Thêm đơn hàng vào cơ sở dữ liệu
                $this->dh->donhang_ins($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien_hang, $so_tien_thanh_toan, 'cho_duyet');

                // Thêm chi tiết đơn hàng
                foreach ($chi_tiet_gio_hang_array as $ct) {
                    $ma_ctdh = $this->ctdh->getNextDetailOrderId();
                    $this->ctdh->chitietdonhang_ins($ma_ctdh, $ma_don_hang, $ct['ma_bien_the'], $ct['so_luong'], $ct['gia']);

                    // Cập nhật kho
                    $bien_the = $this->bt->BienThe_getById($ct['ma_bien_the']);
                    $bt_info = mysqli_fetch_assoc($bien_the);
                    $new_so_luong_kho = $bt_info['so_luong_kho'] - $ct['so_luong'];

                    // --- FIX LỖI ARGUMENT COUNT ERROR ---
                    // Thêm tham số $bt_info['img_bien_the'] vào vị trí thứ 4
                    $this->bt->BienThe_update(
                        $ct['ma_bien_the'],         // 1. Mã biến thể
                        $bt_info['ma_san_pham'],    // 2. Mã sản phẩm
                        $bt_info['ten_bien_the'],   // 3. Tên biến thể
                        $bt_info['img_bien_the'],   // 4. Hình ảnh (Đã thêm mới)
                        $bt_info['mau_sac'],        // 5. Màu sắc
                        $bt_info['ram'],            // 6. RAM
                        $bt_info['dung_luong'],     // 7. Dung lượng
                        $bt_info['gia'],            // 8. Giá
                        $new_so_luong_kho           // 9. Số lượng kho
                    );
                }

                // Insert thanh toán
                $thanh_toan_model = $this->model("ThanhToan_m");

                // Generate sequential transaction ID
                $last_transaction = mysqli_query($thanh_toan_model->con, "SELECT MAX(ma_giao_dich) as max_id FROM thanh_toan WHERE ma_giao_dich LIKE 'GD%'");
                $last_row = mysqli_fetch_assoc($last_transaction);
                $last_id = $last_row['max_id'];

                if ($last_id) {
                    $number = intval(substr($last_id, 2)); // Extract number after 'GD'
                    $new_number = $number + 1;
                } else {
                    $new_number = 1; // Start from 1 if no previous transactions
                }
                $ma_giao_dich = 'GD' . str_pad($new_number, 2, '0', STR_PAD_LEFT); // Format as GD01, GD02, etc.

                $phuong_thuc_luu = ($payment_method == 'bank') ? 'VNPAY' : 'COD';

                $this->model("ThanhToan_m")->thanhtoan_ins(
                    $ma_giao_dich,
                    $ma_don_hang,
                    $phuong_thuc_luu,
                    $so_tien_thanh_toan,
                    'chua_thanh_toan'
                );

                // Xóa món đã mua khỏi giỏ
                if (!$is_buy_now) {
                    // Logic xóa sản phẩm khỏi giỏ hàng sau khi mua thành công
                    foreach ($chi_tiet_gio_hang_array as $ct) {
                        if (isset($ct['qty_in_db']) && $ct['qty_in_db'] > $ct['so_luong']) {
                            $so_luong_con_lai = $ct['qty_in_db'] - $ct['so_luong'];
                            $this->ctgh->ChiTietGioHang_update($ma_gio_hang, $ct['ma_bien_the'], $so_luong_con_lai);
                        } else {
                            // Nếu mua hết số lượng đang có -> Xóa khỏi giỏ
                            $this->ctgh->ChiTietGioHang_delete($ma_gio_hang, $ct['ma_bien_the']);
                        }
                        // ... Logic xóa cũ của bạn ...
                        
                    }
                }


                // Cập nhật trạng thái giỏ hàng thành 'ordered'
                $this->gh->GioHang_update($ma_gio_hang, $ma_user, 'ordered');

                // Nếu là thanh toán online (VNPAY), chuyển hướng đến cổng thanh toán
                if ($payment_method === 'bank') {
                    // Include VNPAY helper
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/MVC/Core/VnPayHelper.php';

                    try {
                        $orderInfo = 'Thanh toán đơn hàng #' . $ma_don_hang;
                        $paymentUrl = VnPayHelper::createPaymentUrl($orderInfo, $so_tien_thanh_toan, $ma_don_hang, 'NCB', 'vn');

                        header('Location: ' . $paymentUrl);
                        die();
                    } catch (Exception $e) {
                        // Log the error
                        error_log("VNPAY Error: " . $e->getMessage());

                        // Show error to user and fallback to COD
                        $this->view('Khachhang_Master', [
                            'page' => 'Khachhang/khachhang_thanhtoan',
                            'errors' => ['Có lỗi xảy ra khi kết nối với cổng thanh toán VNPAY. Vui lòng thử lại sau.'],
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
        // Include VNPAY helper
        require_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/MVC/Core/VnPayHelper.php';

        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $vnp_ResponeCode = $_GET['vnp_ResponseCode'];
        $vnp_TxnRef = $_GET['vnp_TxnRef'];

        // Verify payment return
        if (VnPayHelper::verifyPaymentReturn($_GET)) {
            if ($vnp_ResponeCode == '00') {
                // Thanh toán thành công
                // Cập nhật trạng thái đơn hàng thành 'da_thanh_toan'
                $don_hang_model = $this->model("DonHang_m");
                $don_hang = $don_hang_model->DonHang_getById($vnp_TxnRef);
                $dh_info = mysqli_fetch_assoc($don_hang);

                if ($dh_info) {
                    // Cập nhật trạng thái đơn hàng thành hoàn thành
                    
                    // $don_hang_model->DonHang_updateStatusToComplete($vnp_TxnRef);

                    // Cập nhật trạng thái thanh toán của đơn hàng thành 'da_thanh_toan'
                    $don_hang_model->DonHang_updatePaymentStatus($vnp_TxnRef, 'da_thanh_toan');

                    // Cập nhật trạng thái thanh toán trong bảng thanh_toan
                    $thanh_toan_model = $this->model("ThanhToan_m");

                    // Cập nhật giao dịch thanh toán hiện có thay vì tạo mới
                    $existing_payment = $thanh_toan_model->ThanhToan_getByOrder($vnp_TxnRef);
                    if ($existing_payment && mysqli_num_rows($existing_payment) > 0) {
                        // Lấy mã giao dịch hiện có
                        $payment_row = mysqli_fetch_assoc($existing_payment);
                        $ma_giao_dich = $payment_row['ma_giao_dich'];

                        // Cập nhật trạng thái thanh toán
                        $thanh_toan_model->ThanhToan_update($ma_giao_dich, $vnp_TxnRef, 'VNPAY', $dh_info['tong_tien_hang'], 'da_thanh_toan');
                    } else {
                        // Nếu không có giao dịch hiện có, tạo mới (trường hợp hiếm)
                        // Generate sequential transaction ID
                        $last_transaction = mysqli_query($thanh_toan_model->con, "SELECT MAX(ma_giao_dich) as max_id FROM thanh_toan WHERE ma_giao_dich LIKE 'GD%'");
                        $last_row = mysqli_fetch_assoc($last_transaction);
                        $last_id = $last_row['max_id'];

                        if ($last_id) {
                            $number = intval(substr($last_id, 2)); // Extract number after 'GD'
                            $new_number = $number + 1;
                        } else {
                            $new_number = 1; // Start from 1 if no previous transactions
                        }
                        $ma_thanh_toan = 'GD' . str_pad($new_number, 2, '0', STR_PAD_LEFT); // Format as GD01, GD02, etc.

                        $so_tien = $dh_info['tong_tien_hang'];
                        $thanh_toan_model->thanhtoan_ins($ma_thanh_toan, $vnp_TxnRef, 'VNPAY', $so_tien, 'da_thanh_toan', date('Y-m-d H:i:s'));
                    }
                }

                $this->view('Khachhang_Master', [
                    'page' => 'Khachhang/khachhang_camon',
                    'ma_don_hang' => $vnp_TxnRef,
                    'success_message' => 'Thanh toán bằng VNPAY thành công!'
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

        // Lấy các đơn hàng của người dùng cùng với phương thức thanh toán và thông tin khuyến mãi
        $sql = "SELECT dh.*, dc.ho_ten as ten_nguoi_nhan, dc.dia_chi, dc.so_dien_thoai, tt.phuong_thuc, km.tien_khuyen_mai
                FROM don_hang dh
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                LEFT JOIN khuyen_mai km ON dh.ma_khuyen_mai = km.ma_khuyen_mai
                LEFT JOIN thanh_toan tt ON dh.ma_don_hang = tt.ma_don_hang
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

        // Count orders by status
        $status_counts = [
            'cho_duyet' => 0,
            'da_duyet' => 0,
            'dang_giao' => 0,
            'hoan_thanh' => 0,
            'da_huy' => 0
        ];

        foreach ($don_hang_with_details as $dh) {
            $status = $dh['trang_thai_don_hang'];
            if (isset($status_counts[$status])) {
                $status_counts[$status]++;
            }
        }

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_lichsu',
            'don_hang' => $don_hang_with_details,
            'status_counts' => $status_counts
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
        // Kiểm tra nếu có dữ liệu được gửi qua POST (bao gồm cả file upload)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_user = $_POST['txtMaUser'] ?? '';
            $ten_user = $_POST['txtTenUser'] ?? '';
            $full_name = $_POST['txtFullName'] ?? '';
            $email = $_POST['txtEmail'] ?? '';
            $dia_chi = $_POST['txtDiaChi'] ?? '';
            $so_dien_thoai = $_POST['txtSoDienThoai'] ?? '';

            // Xử lý upload avatar nếu có
            $avatar = '';
            if (isset($_FILES['txtAvatar']) && $_FILES['txtAvatar']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $file_type = $_FILES['txtAvatar']['type'];
                
                if (in_array($file_type, $allowed_types)) {
                    $file_size = $_FILES['txtAvatar']['size'];
                    if ($file_size <= 5 * 1024 * 1024) { // Max 5MB
                        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Pictures/users/';
                        
                        // Tạo thư mục nếu chưa tồn tại
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        // Lấy phần mở rộng của file
                        $file_extension = pathinfo($_FILES['txtAvatar']['name'], PATHINFO_EXTENSION);
                        
                        // Tạo tên file mới để tránh trùng lặp
                        $new_filename = $ma_user . '_' . time() . '.' . $file_extension;
                        $target_path = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($_FILES['txtAvatar']['tmp_name'], $target_path)) {
                            $avatar = $new_filename;
                            
                            // Nếu người dùng đã có avatar cũ, xóa file đó đi
                            $current_user = $this->user->Users_getById($ma_user);
                            $current_user_data = mysqli_fetch_assoc($current_user);
                            if ($current_user_data && !empty($current_user_data['avatar']) && $current_user_data['avatar'] != 'avatar.png') {
                                $old_avatar_path = $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Pictures/users/' . $current_user_data['avatar'];
                                if (file_exists($old_avatar_path)) {
                                    unlink($old_avatar_path);
                                }
                            }
                        } else {
                            // Trả về lỗi nếu không thể upload
                            $response = array('success' => false, 'message' => 'Không thể upload file ảnh. Vui lòng thử lại.');
                            header('Content-Type: application/json');
                            echo json_encode($response);
                            return;
                        }
                    } else {
                        $response = array('success' => false, 'message' => 'File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 5MB.');
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        return;
                    }
                } else {
                    $response = array('success' => false, 'message' => 'Định dạng file không hợp lệ. Chỉ chấp nhận JPEG, PNG, GIF, WEBP.');
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    return;
                }
            } else {
                // Nếu không có avatar mới được upload, giữ nguyên avatar cũ
                $current_user = $this->user->Users_getById($ma_user);
                $current_user_data = mysqli_fetch_assoc($current_user);
                $avatar = $current_user_data ? $current_user_data['avatar'] : '';
            }

            // Cập nhật thông tin người dùng (chỉ cập nhật những trường cần thiết)
            $result = $this->user->Users_update_profile($ma_user, $full_name, $so_dien_thoai, $email, $dia_chi, $avatar);

            if ($result) {
                // Trả về kết quả thành công theo định dạng JSON
                $response = array('success' => true, 'message' => 'Cập nhật thông tin thành công!');
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                // Trả về lỗi theo định dạng JSON
                $response = array('success' => false, 'message' => 'Cập nhật thông tin thất bại!');
                header('Content-Type: application/json');
                echo json_encode($response);
            }
            return; // Kết thúc hàm sau khi trả về JSON
        }
        
        // Nếu không phải POST request, chuyển hướng về trang tài khoản
        header('Location: http://localhost/Banhang/Khachhang/taikhoan');
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
                        $user['so_dien_thoai'],
                        $user['avatar']
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
            $brand_id = isset($_POST['brand_id']) ? $_POST['brand_id'] : '';

            // Gọi phương thức từ model để lọc sản phẩm theo mức giá
            $result = $this->sp->SanPham_filterByCategoryAndPrice('', $price_range, $brand_id);

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
            $brand_id = isset($_POST['brand_id']) ? $_POST['brand_id'] : '';

            // Gọi phương thức từ model để lọc sản phẩm theo danh mục
            $result = $this->sp->SanPham_filterByCategoryAndPrice($category_id, '', $brand_id);

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
        $brand_id = isset($_POST['brand_id']) ? $_POST['brand_id'] : '';

        // Gọi phương thức từ model để lọc sản phẩm
        $result = $this->sp->SanPham_filterByCategoryAndPrice($category_id, $price_range, $brand_id);

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

    // Lấy dữ liệu giỏ hàng dưới dạng JSON
    function getgiohang()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['cart' => []]);
            return;
        }

        $ma_user = $_SESSION['user_id'];

        // Lấy giỏ hàng của người dùng
        $gio_hang = $this->gh->GioHang_getByUser($ma_user);
        $row = mysqli_fetch_assoc($gio_hang);

        if ($row) {
            $ma_gio_hang = $row['ma_gio_hang'];
            $chi_tiet_gio_hang = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);

            $cart_items = [];
            while ($ct = mysqli_fetch_assoc($chi_tiet_gio_hang)) {
                // Lấy thông tin biến thể và sản phẩm
                $bien_the = $this->bt->BienThe_getById($ct['ma_bien_the']);
                $bt_info = mysqli_fetch_assoc($bien_the);

                $san_pham = $this->sp->SanPham_getById($bt_info['ma_san_pham']);
                $sp_info = mysqli_fetch_assoc($san_pham);

                // Tạo đường dẫn hình ảnh
                $img_url = !empty($bt_info['img_bien_the'])
                    ? '/Banhang/Public/Pictures/bien_the/' . htmlspecialchars($bt_info['img_bien_the'])
                    : (!empty($sp_info['img_hinh_anh'])
                        ? '/Banhang/Public/Pictures/sanpham/' . htmlspecialchars($sp_info['img_hinh_anh'])
                        : '/Banhang/Public/Images/no-image.png');

                // Tạo tên biến thể
                $variant_parts = [];
                if (!empty($bt_info['mau_sac'])) $variant_parts[] = $bt_info['mau_sac'];
                if (!empty($bt_info['dung_luong'])) $variant_parts[] = $bt_info['dung_luong'];
                if (!empty($bt_info['ram'])) $variant_parts[] = $bt_info['ram'];
                $variant_name = !empty($variant_parts) ? implode(' - ', $variant_parts) : $bt_info['ten_bien_the'];

                $cart_items[] = [
                    'ma_bien_the' => $ct['ma_bien_the'],
                    'img' => $img_url,
                    'name' => $sp_info['ten_san_pham'],
                    'variant' => $variant_name,
                    'quantity' => (int)$ct['so_luong'], // <-- Thêm (int) để ép kiểu số
                    'price' => (int)$bt_info['gia']     // <-- Nên ép kiểu giá tiền luôn cho chắc
                ];
            }

            echo json_encode(['cart' => $cart_items]);
        } else {
            echo json_encode(['cart' => []]);
        }
    }

    // Thêm đánh giá cho sản phẩm
    function themdanhgia()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để đánh giá sản phẩm']);
                return;
            }

            $ma_san_pham = $_POST['ma_san_pham'];
            $so_sao = (int)$_POST['so_sao'];
            $noi_dung = trim($_POST['noi_dung']);
            $ma_user = $_SESSION['user_id'];

            // Validate input
            if ($so_sao < 1 || $so_sao > 5) {
                echo json_encode(['success' => false, 'message' => 'Số sao phải từ 1 đến 5']);
                return;
            }

            if (empty($noi_dung)) {
                echo json_encode(['success' => false, 'message' => 'Nội dung đánh giá không được để trống']);
                return;
            }

            // Generate unique review ID in format DG01, DG02, etc.
            $last_review = mysqli_query($this->dg->con, "SELECT MAX(ma_danh_gia) as max_id FROM danh_gia WHERE ma_danh_gia LIKE 'DG%'");
            $last_row = mysqli_fetch_assoc($last_review);
            $last_id = $last_row['max_id'];

            if ($last_id) {
                $number = intval(substr($last_id, 2)); // Extract number after 'DG'
                $new_number = $number + 1;
            } else {
                $new_number = 1; // Start from 1 if no previous reviews
            }

            $ma_danh_gia = 'DG' . str_pad($new_number, 2, '0', STR_PAD_LEFT); // Format as DG01, DG02, etc.

            // Insert review into database
            $result = $this->dg->danhgia_ins($ma_danh_gia, $ma_user, $ma_san_pham, $so_sao, $noi_dung, '');

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Đánh giá đã được gửi thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi gửi đánh giá']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
        }
    }


    function capnhatgiohang_ajax()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        if (isset($_POST['ma_bien_the']) && isset($_POST['so_luong'])) {
            $ma_user = $_SESSION['user_id'];
            $ma_bien_the = $_POST['ma_bien_the'];
            $so_luong = (int)$_POST['so_luong'];

            $gio_hang = $this->gh->GioHang_getByUser($ma_user);
            $row = mysqli_fetch_assoc($gio_hang);

            if ($row) {
                $ma_gio_hang = $row['ma_gio_hang'];

                if ($so_luong <= 0) {
                     echo json_encode(['success' => false, 'message' => 'Số lượng phải lớn hơn 0']);
                     return;
                } else {
                    // Cập nhật số lượng mới vào DB
                    $result = $this->ctgh->ChiTietGioHang_update($ma_gio_hang, $ma_bien_the, $so_luong);
                    
                    if($result) {
                        // --- [THÊM ĐOẠN NÀY] Tính lại tổng số lượng trong giỏ ---
                        $total_qty = 0;
                        $items = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
                        if ($items) {
                            while ($item = mysqli_fetch_assoc($items)) {
                                $total_qty += $item['so_luong'];
                            }
                        }
                        // Trả về thêm 'new_total_qty'
                        echo json_encode(['success' => true, 'new_total_qty' => $total_qty]);
                        // --------------------------------------------------------
                    } else {
                         echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật database']);
                    }
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy giỏ hàng']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
        }
    }


    function xoakhoigio_ajax($ma_bien_the)
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        $ma_user = $_SESSION['user_id'];
        
        // Lấy giỏ hàng của user
        $gio_hang = $this->gh->GioHang_getByUser($ma_user);
        $row = mysqli_fetch_assoc($gio_hang);

        if ($row) {
            $ma_gio_hang = $row['ma_gio_hang'];
            // Gọi model để xóa
            $result = $this->ctgh->ChiTietGioHang_delete($ma_gio_hang, $ma_bien_the);
            
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi database']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy giỏ hàng']);
        }
    }

    // Tìm kiếm sản phẩm
    function timkiem()
    {
        $search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

        if (!empty($search_query)) {
            // Lấy danh sách sản phẩm theo từ khóa tìm kiếm
            $result = $this->sp->SanPham_searchByName($search_query);

            // Chuyển kết quả thành mảng để sử dụng trong view
            $dssp = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $dssp[] = $row;
            }

            // Lấy danh sách danh mục để hiển thị
            $dsdm = $this->dm->DanhMuc_getAll();

            $this->view('Khachhang_Master', [
                'page' => 'Khachhang/khachhang_sanpham_timkiem',
                'dssp' => $dssp,
                'dsdm' => $dsdm,
                'search_query' => $search_query
            ]);
        } else {
            // Nếu không có từ khóa tìm kiếm, chuyển hướng về trang chủ
            header('Location: ' . $this->url('Khachhang'));
        }
    }
}
