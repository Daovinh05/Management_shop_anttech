<?php
class Donhang extends controller
{
    private $dh;
    private $user;
    private $dc;
    private $km;
    private $ctdh;

    function __construct()
    {
        $this->dh = $this->model("DonHang_m");
        $this->user = $this->model("Users_m");
        $this->dc = $this->model("DiaChiGiaoHang_m");
        $this->km = $this->model("KhuyenMai_m");
        $this->ctdh = $this->model("ChiTietDonHang_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách đơn hàng
        $this->danhsach();
    }

    function danhsach()
    {
        $this->view('Master', [
            'page' => 'Danhsachdonhang_v',
            'dulieu' => null
        ]);
    }

    function themmoi()
    {
        // Lấy danh sách người dùng, địa chỉ, khuyến mãi cho dropdown
        $dsuser = $this->user->Users_getAll();
        $dsdc = $this->dc->DiaChiGiaoHang_getAll();
        $dskm = $this->km->KhuyenMai_getAll();

        $this->view('Master', [
            'page' => 'Donhang_v',
            'ma_don_hang' => '',
            'full_name' => '',
            'ma_dia_chi' => '',
            'ma_khuyen_mai' => '',
            'tong_tien_hang' => '',
            'trang_thai_don_hang' => '',
            'dsuser' => $dsuser,
            'dsdc' => $dsdc,
            'dskm' => $dskm,
            'dulieu' => null
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
                        'page' => 'Donhang_v',
                        'ma_don_hang' => $ma_don_hang,
                        'ma_user' => $ma_user,
                        'ma_dia_chi' => $ma_dia_chi,
                        'ma_khuyen_mai' => $ma_khuyen_mai,
                        'tong_tien_hang' => $tong_tien_hang,
                        'trang_thai_don_hang' => $trang_thai_don_hang,
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
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint Timkiem đã ngừng sử dụng. Vui lòng dùng GET /Api/Donhang với query: ma_don_hang, full_name'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    function sua($ma_don_hang)
    {
        $this->view('Master', [
            'page' => 'donhang_sua'
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
        $orderResult = $this->dh->DonHang_getById($ma_don_hang);
        if ($orderResult && mysqli_num_rows($orderResult) > 0) {
            $order = mysqli_fetch_assoc($orderResult);
            $status = $order['trang_thai_don_hang'] ?? '';

            if ($status === 'da_duyet' || $status === 'dang_giao') {
                echo "<script>alert('Không thể xóa đơn hàng ở trạng thái Đã xác nhận hoặc Đang giao.'); window.location='" . $this->url('Donhang/danhsach') . "';</script>";
                return;
            }
        }

        $kq = $this->dh->DonHang_delete($ma_don_hang);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Donhang/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Donhang/danhsach') . "';</script>";
    }

    // FILE: controllers/Donhang.php

    public function get_order_details($id)
    {
        try {
            // Lấy thông tin chi tiết món
            $raw_details = $this->ctdh->ChiTietDonHang_getByOrderId($id);

            // Lấy thông tin chung đơn hàng (để lấy ghi chú và thông tin khác)
            $raw_order = $this->dh->DonHang_getById($id);

            // Lấy thông tin người dùng và địa chỉ giao hàng
            $order_info = $this->dh->DonHang_getById($id);

            // --- Chuyển dữ liệu sang mảng ---
            $details_arr = [];
            $error_message = null;

            if ($raw_details === false) {
                // Nếu truy vấn thất bại, lưu lỗi
                $error_message = mysqli_error($this->ctdh->con);
            } else if ($raw_details) {
                while ($row = mysqli_fetch_assoc($raw_details)) {
                    // Map dữ liệu để JavaScript dễ đọc
                    $row['ten_san_pham'] = $row['ten_san_pham'] ?? $row['ten_bien_the'] ?? 'Sản phẩm không xác định';
                    $row['ten_mon'] = $row['ten_san_pham'];
                    $row['img_san_pham'] = $row['img_hinh_anh'] ?? '';

                    // Đảm bảo các trường giá trị luôn có mặt
                    $row['gia_tai_thoi_diem_dat'] = $row['gia_luc_mua'] ?? 0;
                    $row['so_luong'] = $row['so_luong'] ?? 0;

                    $details_arr[] = $row;
                }
            }

            // Lấy thông tin đơn hàng chính
            $order_data = null;
            if ($order_info && mysqli_num_rows($order_info) > 0) {
                $order_data = mysqli_fetch_assoc($order_info);
            }

            // Lấy thông tin người dùng
            $user_info = null;
            if (isset($order_data['ma_user'])) {
                $user_result = $this->user->Users_getById($order_data['ma_user']);
                if ($user_result && mysqli_num_rows($user_result) > 0) {
                    $user_info = mysqli_fetch_assoc($user_result);
                }
            }

            // Lấy thông tin địa chỉ giao hàng
            $address_info = null;
            if (isset($order_data['ma_dia_chi'])) {
                $address_result = $this->dc->DiaChiGiaoHang_getById($order_data['ma_dia_chi']);
                if ($address_result && mysqli_num_rows($address_result) > 0) {
                    $address_info = mysqli_fetch_assoc($address_result);
                }
            }

            // Lấy thông tin khuyến mãi
            $promotion_info = null;
            if (isset($order_data['ma_khuyen_mai']) && !empty($order_data['ma_khuyen_mai'])) {
                $promotion_result = $this->km->KhuyenMai_getById($order_data['ma_khuyen_mai']);
                if ($promotion_result && mysqli_num_rows($promotion_result) > 0) {
                    $promotion_info = mysqli_fetch_assoc($promotion_result);
                }
            }

            // --- Trả về JSON ---
            $result = [
                'order_details' => $details_arr,
                'order_info' => $order_data,
                'user_info' => $user_info,
                'address_info' => $address_info,
                'promotion_info' => $promotion_info,
                'order_notes'   => $order_data['ghi_chu'] ?? '',
                'debug_info' => [
                    'order_id' => $id,
                    'details_count' => count($details_arr),
                    'has_raw_details' => $raw_details !== false,
                    'has_order' => $order_info !== false && mysqli_num_rows($order_info) > 0,
                    'error_message' => $error_message,
                    'query_success' => $raw_details !== false
                ]
            ];

            // Xóa bộ nhớ đệm để tránh lỗi JSON
            if (ob_get_length()) ob_clean();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
        } catch (Exception $e) {
            // Trả về lỗi nếu có exception
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'error' => $e->getMessage(),
                'order_details' => [],
                'order_info' => null,
                'user_info' => null,
                'address_info' => null,
                'promotion_info' => null,
                'order_notes' => ''
            ]);
        }
        exit();
    }

    // Phương thức in hóa đơn
    public function InHoaDon($id)
    {
        // Lấy thông tin chi tiết đơn hàng
        $raw_details = $this->ctdh->ChiTietDonHang_getByOrderId($id);
        $raw_order = $this->dh->DonHang_getById($id);

        // Chuyển dữ liệu chi tiết sang mảng
        $details_arr = [];
        if ($raw_details) {
            while ($row = mysqli_fetch_assoc($raw_details)) {
                $row['ten_san_pham'] = $row['ten_san_pham'] ?? $row['ten_bien_the'] ?? 'Sản phẩm không xác định';
                $row['img_thuc_don'] = $row['img_hinh_anh'] ?? '';
                $row['gia_tai_thoi_diem_dat'] = $row['gia_luc_mua'] ?? 0;
                $row['so_luong'] = $row['so_luong'] ?? 0;
                $details_arr[] = $row;
            }
        }

        // Lấy thông tin đơn hàng
        $order_info = null;
        if ($raw_order && mysqli_num_rows($raw_order) > 0) {
            $order_info = mysqli_fetch_assoc($raw_order);
        }

        // Tính tổng tiền
        $tong_tien = 0;
        foreach ($details_arr as $item) {
            $tong_tien += ($item['so_luong'] * $item['gia_luc_mua']);
        }

        // Truyền dữ liệu vào view
        $this->view('Master', [
            'page' => 'InHoaDon_v',
            'order_info' => $order_info,
            'order_details' => $details_arr,
            'tong_tien' => $tong_tien
        ]);
    }

    // Phương thức cập nhật trạng thái đơn hàng
    public function update_status()
    {
        // Kiểm tra phương thức POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
            exit();
        }

        // Lấy dữ liệu JSON từ request
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['orderId']) || !isset($input['status'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit();
        }

        $orderId = $input['orderId'];
        $status = $input['status'];

        $currentOrderResult = $this->dh->DonHang_getById($orderId);
        if (!$currentOrderResult || mysqli_num_rows($currentOrderResult) === 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
            exit();
        }

        $currentOrder = mysqli_fetch_assoc($currentOrderResult);
        $currentStatus = $currentOrder['trang_thai_don_hang'] ?? '';

        // Cập nhật trạng thái đơn hàng
        if ($status === 'da_huy' && $currentStatus !== 'da_huy') {
            $result = $this->dh->DonHang_cancelWithRestock($orderId);
        } else {
            $result = $this->dh->DonHang_updateStatus($orderId, $status);
        }

        if ($result) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
        }
        exit();
    }
}
