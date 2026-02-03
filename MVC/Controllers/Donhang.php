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
        $result = $this->dh->DonHang_getAll();

        $this->view('Master', [
            'page' => 'Danhsachdonhang_v',
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
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_don_hang = $_POST['txtMadonhang'] ?? '';
        $full_name = $_POST['txtTenkhachhang'] ?? '';
        $result = $this->dh->DonHang_find($ma_don_hang, $full_name);
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDonHang');

            $sheet->setCellValue('A1', 'Mã đơn hàng');
            $sheet->setCellValue('B1', 'Tên khách hàng');
            $sheet->setCellValue('E1', 'Tổng tiền hàng');
            $sheet->setCellValue('D1', 'Tên khuyến mãi');
            $sheet->setCellValue('F1', 'Thanh toán');
            $sheet->setCellValue('G1', 'Trạng thái đơn hàng');
            $sheet->setCellValue('H1', 'Ngày tạo đơn hàng');

            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                $sheet->setCellValue('A' . $rowCount, $row['ma_don_hang']);
                $sheet->setCellValue('B' . $rowCount, $row['full_name']);
                $sheet->setCellValue('C' . $rowCount, $row['tong_tien_hang']);
                $sheet->setCellValue('D' . $rowCount, $row['ten_khuyen_mai']);
                $sheet->setCellValue('E' . $rowCount, $row['thanh_toan']);
                $sheet->setCellValue('F' . $rowCount, $row['trang_thai_don_hang']);
                $sheet->setCellValue('G' . $rowCount, $row['ngay_tao_don_hang']);
                $rowCount++;
            }

            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachDonHang.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'Danhsachdonhang_v',
            'ma_don_hang' => $ma_don_hang,
            'full_name' => $full_name,
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

    // FILE: controllers/Donhang.php

    public function get_order_details($id)
    {
        // Lấy thông tin chi tiết món
        $raw_details = $this->ctdh->ChiTietDonHang_getByOrderId($id);

        // Lấy thông tin chung đơn hàng (để lấy ghi chú)
        $raw_order = $this->dh->DonHang_getById($id);

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

        $order_note = '';
        if ($raw_order && mysqli_num_rows($raw_order) > 0) {
            $order_data = mysqli_fetch_assoc($raw_order);
            $order_note = $order_data['ghi_chu'] ?? '';
        }

        // --- Trả về JSON ---
        $result = [
            'order_details' => $details_arr,
            'order_notes'   => $order_note,
            'debug_info' => [
                'order_id' => $id,
                'details_count' => count($details_arr),
                'has_raw_details' => $raw_details !== false,
                'has_order' => $raw_order !== false && mysqli_num_rows($raw_order) > 0,
                'error_message' => $error_message,
                'query_success' => $raw_details !== false
            ]
        ];

        // Xóa bộ nhớ đệm để tránh lỗi JSON
        if (ob_get_length()) ob_clean();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
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
}
