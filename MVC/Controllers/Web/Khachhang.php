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
        // Trang chu khach hang chi render shell, du lieu danh sach/tim kiem lay tu API Storefront.
        $dsdm = $this->dm->DanhMuc_getAll();

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_home',
            'dsdm' => $dsdm
        ]);
    }

    // Hiển thị sản phẩm theo danh mục
    function sanpham_theo_danhmuc($ma_danh_muc)
    {
        // Giu tuong thich route cu, nhung du lieu duoc nap boi API Storefront tren trang chu.
        $redirectUrl = $this->url('Khachhang') . '?category_id=' . urlencode((string)$ma_danh_muc) . '&page=1';
        header('Location: ' . $redirectUrl);
        exit;
    }

    // Hiển thị chi tiết sản phẩm
    function chitietsanpham($ma_san_pham = '')
    {
        $ma_san_pham = trim((string)$ma_san_pham);
        if ($ma_san_pham === '') {
            header('Location: ' . $this->url('Khachhang'));
            exit;
        }

        // Chi render giao dien; du lieu chi tiet lay qua REST endpoint GET /Api/Storefront/{id}.
        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_chitietsp',
            'ma_san_pham' => $ma_san_pham
        ]);
    }

    // Thêm vào giỏ hàng
    function themvaogio($ma_bien_the)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung POST /Api/Cart'
        ]);
        return;
    }

    // Hiển thị giỏ hàng
    function giohang()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->url('Login'));
            exit;
        }
        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_giohang'
        ]);
    }

    // Cập nhật số lượng trong giỏ hàng
    function capnhatgiohang()
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung PATCH /Api/Cart/update/{ma_bien_the}'
        ]);
        return;
    }

    // Xóa sản phẩm khỏi giỏ hàng
    function xoakhoigio($ma_gio_hang, $ma_bien_the)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung DELETE /Api/Cart/{ma_bien_the}'
        ]);
        return;
    }

    // Tiến hành thanh toán
    function thanhtoan()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->url('Login'));
            exit;
        }
        // API-first: du lieu checkout duoc load bang REST endpoint /Api/Checkout/init.
        // Van giu fallback rong de tranh warning tren template cu.
        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_thanhtoan',
            'ds_sp_thanh_toan' => [],
            'dia_chi' => null,
            'ds_khuyen_mai' => null,
            'tong_tien_du kien' => 0
        ]);
    }

    // Xử lý đặt hàng
    function datHang()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->url('Login'));
            exit;
        }

        if (isset($_POST['btnDatHang'])) {
            $ma_user = $_SESSION['user_id'];
            $ma_khuyen_mai = !empty($_POST['ddlKhuyenMai']) ? trim($_POST['ddlKhuyenMai']) : null;
            $ghi_chu = trim($_POST['txtGhiChu']) ?? '';
            $payment_method = trim($_POST['payment_method']) ?? 'cod';
            $ho_ten = trim($_POST['txtHoTen']);
            $so_dien_thoai = trim($_POST['txtSoDienThoai']);
            $email = trim($_POST['txtEmail']);
            
            // Lấy thông tin địa chỉ giao hàng từ 3 trường nhập trực tiếp
            $ho_ten = trim($_POST['txtHoTenNguoiNhan']);
            $dia_chi = trim($_POST['txtDiaChiGiaoHang']);
            $so_dien_thoai = trim($_POST['txtSoDienThoai']);

            // Validate cơ bản
            $errors = [];

            // Validate thông tin người nhận
            if (empty($ho_ten)) {
                $errors[] = "Vui lòng nhập họ và tên người nhận.";
            } elseif (strlen($ho_ten) < 2) {
                $errors[] = "Họ và tên người nhận phải có ít nhất 2 ký tự.";
            }

            if (empty($dia_chi)) {
                $errors[] = "Vui lòng nhập địa chỉ giao hàng.";
            }

            if (empty($so_dien_thoai)) {
                $errors[] = "Vui lòng nhập số điện thoại người nhận.";
            } elseif (!preg_match('/^0[0-9]{9,10}$/', $so_dien_thoai)) {
                $errors[] = "Số điện thoại người nhận không hợp lệ.";
            }

            // Validate thông tin người nhận (đã dùng $ho_ten, $dia_chi, $so_dien_thoai ở trên)
            
            // Validate thông tin liên hệ (trùng với người nhận)
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
                        'dia_chi' => $dia_chi,
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
                            $forced_qty_raw = isset($_POST['forced_qty']) ? trim((string)$_POST['forced_qty']) : '';
                            $item_forced_qty = ($forced_qty_raw !== '' && ctype_digit($forced_qty_raw)) ? (int)$forced_qty_raw : null;
                            if ($item_forced_qty !== null && $item_forced_qty > 0) {
                                $ct['so_luong'] = $item_forced_qty;
                            }

                            $bien_the = $this->bt->BienThe_getById($ct['ma_bien_the']);
                            $bt_info = mysqli_fetch_assoc($bien_the);

                            if (!$bt_info) {
                                continue;
                            }

                            // Gia luc mua phai lay tu bien_the, bang chi_tiet_gio_hang khong co cot gia
                            $ct['gia'] = (float)$bt_info['gia'];

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
                            'dia_chi' => $dia_chi,
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

                // Tạo mã địa chỉ giao hàng mới và insert vào bảng dia_chi_giao_hang
                // Generate sequential address ID
                $last_address = mysqli_query($this->dc->con, "SELECT MAX(ma_dia_chi) as max_id FROM dia_chi_giao_hang WHERE ma_dia_chi LIKE 'DC%'");
                $last_addr_row = mysqli_fetch_assoc($last_address);
                $last_addr_id = $last_addr_row['max_id'];

                if ($last_addr_id) {
                    $addr_number = intval(substr($last_addr_id, 2)); // Extract number after 'DC'
                    $new_addr_number = $addr_number + 1;
                } else {
                    $new_addr_number = 1; // Start from 1 if no previous addresses
                }
                $ma_dia_chi = 'DC' . str_pad($new_addr_number, 2, '0', STR_PAD_LEFT); // Format as DC01, DC02, etc.

                // Insert địa chỉ giao hàng mới (không đặt làm mặc định)
                $this->dc->diachigiaohang_ins($ma_dia_chi, $ma_user, $ho_ten, $so_dien_thoai, $dia_chi, 0);

                // Tạo mã đơn hàng theo thứ tự tăng dần
                $ma_don_hang = $this->dh->getNextOrderId();

                // Thêm đơn hàng vào cơ sở dữ liệu với địa chỉ mới tạo
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
                    require_once __DIR__ . '/../Core/VnPayHelper.php';

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
                                'dia_chi' => $dia_chi,
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
        require_once __DIR__ . '/../Core/VnPayHelper.php';

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

                    // Cập nhật trạng thái thanh toán trong bảng thanh_toan
                    $thanh_toan_model = $this->model("ThanhToan_m");

                    // Cập nhật giao dịch thanh toán hiện có thay vì tạo mới
                    $existing_payment = $thanh_toan_model->ThanhToan_getByOrder($vnp_TxnRef);
                    if ($existing_payment && mysqli_num_rows($existing_payment) > 0) {
                        // Lấy mã giao dịch hiện có
                        $payment_row = mysqli_fetch_assoc($existing_payment);
                        $ma_giao_dich = $payment_row['ma_giao_dich'];

                        // Cập nhật trạng thái thanh toán
                        $amount = is_numeric($dh_info['thanh_toan']) ? (float)$dh_info['thanh_toan'] : (float)$dh_info['tong_tien_hang'];
                        $thanh_toan_model->ThanhToan_update($ma_giao_dich, $vnp_TxnRef, 'VNPAY', $amount, 'da_thanh_toan');
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

                        $so_tien = is_numeric($dh_info['thanh_toan']) ? (float)$dh_info['thanh_toan'] : (float)$dh_info['tong_tien_hang'];
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

    // Trang cảm ơn sau khi đặt hàng thành công (COD/REST redirect)
    function camon($ma_don_hang = '')
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->url('Login'));
            exit;
        }

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_camon',
            'ma_don_hang' => $ma_don_hang
        ]);
    }

    // Hiển thị lịch sử mua hàng
    function lichsumuahang()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->url('Login'));
            exit;
        }
        // API-first: du lieu lich su don hang duoc tai bang REST endpoint /Api/Checkout/history.
        $status_counts = [
            'cho_duyet' => 0,
            'da_duyet' => 0,
            'dang_giao' => 0,
            'hoan_thanh' => 0,
            'da_huy' => 0
        ];

        $this->view('Khachhang_Master', [
            'page' => 'Khachhang/khachhang_lichsu',
            'don_hang' => [],
            'status_counts' => $status_counts
        ]);
    }

    // Hiển thị chi tiết đơn hàng
    function chitietdonhang($ma_don_hang)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->url('Login'));
            exit;
        }

        $ma_user = $_SESSION['user_id'];

        // Kiểm tra đơn hàng có thuộc về người dùng này không
        $don_hang = $this->dh->DonHang_getById($ma_don_hang);
        $dh_info = mysqli_fetch_assoc($don_hang);

        if ($dh_info['ma_user'] != $ma_user) {
            echo "<script>alert('Bạn không có quyền xem đơn hàng này!');</script>";
            header('Location: ' . $this->url('Khachhang/lichsumuahang'));
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
            header('Location: ' . $this->url('Login'));
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
        http_response_code(410);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung su dung. Vui long dung PATCH /Api/Profile'
        ]);
        exit;
    }

    // Đổi mật khẩu
    function doimatkhau()
    {
        http_response_code(410);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung su dung. Vui long dung PATCH /Api/Profile/password'
        ]);
        exit;
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

            header('Location: ' . $this->url('Khachhang/taikhoan'));
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

            header('Location: ' . $this->url('Khachhang/taikhoan'));
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

        header('Location: ' . $this->url('Khachhang/taikhoan'));
    }

    // Lọc sản phẩm theo mức giá
    function filter_by_price()
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung GET /Api/Storefront'
        ]);
        return;
    }

    // Lọc sản phẩm theo danh mục
    function filter_by_category()
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung GET /Api/Storefront'
        ]);
        return;
    }

    // Lọc sản phẩm theo cả danh mục và mức giá
    function filter_by_both()
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung GET /Api/Storefront'
        ]);
        return;
    }

    // Lấy dữ liệu giỏ hàng dưới dạng JSON
    function getgiohang()
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung GET /Api/Cart'
        ]);
        return;
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
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung PATCH /Api/Cart/update/{ma_bien_the}'
        ]);
        return;
    }


    function xoakhoigio_ajax($ma_bien_the)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung ho tro. Vui long su dung DELETE /Api/Cart/{ma_bien_the}'
        ]);
        return;
    }

    // Tìm kiếm sản phẩm
    function timkiem()
    {
        $search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

        // Giu tuong thich route cu, nhung chuyen huong ve trang chu REST-first
        if (!empty($search_query)) {
            header('Location: ' . $this->url('Khachhang') . '?q=' . urlencode($search_query) . '&page=1');
            exit;
        }

        header('Location: ' . $this->url('Khachhang'));
        exit;
    }
}
