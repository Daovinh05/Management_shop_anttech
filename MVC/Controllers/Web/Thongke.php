<?php
class Thongke extends controller
{
    private $dh;
    private $ctdh;

    function __construct()
    {
        $this->dh = $this->model("DonHang_m");
        $this->ctdh = $this->model("ChiTietDonHang_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị thống kê doanh thu
        $this->thongke();
    }

    function thongke()
    {
        // Lấy ngày hiện tại
        $ngayHienTai = date('Y-m-d');

        // Lấy thống kê tổng quan
        $tongQuan = $this->layThongKeTongQuan();

        // Lấy danh sách đơn hàng chi tiết
        $danhSachDonHang = $this->layDanhSachDonHangChiTiet();

        // Lấy thống kê theo phương thức thanh toán
        $thongKePhuongThuc = $this->layThongKePhuongThucThanhToan();

        // Lấy top sản phẩm bán chạy
        $topSanPham = $this->dh->DonHang_TopSanPham(10);

        $this->view('Master', [
            'page' => 'Thongkedoanhthu_v',
            'tongquan' => $tongQuan,
            'danhsachdonhang' => $danhSachDonHang,
            'thongkephuongthuc' => $thongKePhuongThuc,
            'top_sanpham' => $topSanPham,
            'ngayhientai' => $ngayHienTai
        ]);
    }

    private function layThongKeTongQuan()
    {
        // Lấy thống kê tổng quan về đơn hàng
        $result = $this->dh->DonHang_ThongKeTongQuan();

        $data = [
            'cho_duyet' => 0,
            'da_duyet' => 0,
            'dang_giao' => 0,
            'hoan_thanh' => 0,
            'da_huy' => 0,
            'tong_don' => 0
        ];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $trangThai = $row['trang_thai_don_hang'];
                $soLuong = $row['so_luong'];

                switch ($trangThai) {
                    case 'cho_duyet':
                        $data['cho_duyet'] = $soLuong;
                        break;
                    case 'da_duyet':
                        $data['da_duyet'] = $soLuong;
                        break;
                    case 'dang_giao':
                        $data['dang_giao'] = $soLuong;
                        break;
                    case 'hoan_thanh':
                        $data['hoan_thanh'] = $soLuong;
                        break;
                    case 'da_huy':
                        $data['da_huy'] = $soLuong;
                        break;
                }
            }
        }

        $data['tong_don'] = $data['cho_duyet'] + $data['da_duyet'] + $data['dang_giao'] + $data['hoan_thanh'];

        return $data;
    }

    private function layDanhSachDonHangChiTiet()
    {
        // Lấy danh sách đơn hàng với thông tin chi tiết
        $result = $this->dh->DonHang_ThongKeChiTiet();
        $danhSach = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $danhSach[] = $row;
            }
        }

        return $danhSach;
    }

    private function layThongKePhuongThucThanhToan()
    {
        // Lấy thống kê theo phương thức thanh toán
        $result = $this->dh->DonHang_ThongKePhuongThuc();

        $data = [
            'cod' => [
                'so_don' => 0,
                'doanh_thu' => 0
            ],
            'momo' => [
                'so_don' => 0,
                'doanh_thu' => 0
            ],
            'banking' => [
                'so_don' => 0,
                'doanh_thu' => 0
            ],
            'tong_so_don' => 0
        ];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $phuongThuc = strtolower($row['phuong_thuc']);

                if ($phuongThuc == 'cod') {
                    $data['cod']['so_don'] = $row['so_don'];
                    $data['cod']['doanh_thu'] = $row['tong_tien'];
                } else if ($phuongThuc == 'momo') {
                    $data['momo']['so_don'] = $row['so_don'];
                    $data['momo']['doanh_thu'] = $row['tong_tien'];
                } else if ($phuongThuc == 'vnpay') {
                    $data['vnpay']['so_don'] = $row['so_don'];
                    $data['vnpay']['doanh_thu'] = $row['tong_tien'];
                } else if ($phuongThuc == 'banking') {
                    $data['banking']['so_don'] = $row['so_don'];
                    $data['banking']['doanh_thu'] = $row['tong_tien'];
                }
            }
        }

        $data['tong_so_don'] = $data['cod']['so_don'] + $data['momo']['so_don'] + $data['banking']['so_don'];

        return $data;
    }

    // Hàm lọc theo ngày
    function locTheoNgay()
    {
        if (isset($_POST['btnLoc'])) {
            $tuNgay = $_POST['txtTuNgay'] ?? '';
            $denNgay = $_POST['txtDenNgay'] ?? '';

            // Lấy danh sách đơn hàng theo khoảng thời gian
            $danhSachDonHang = $this->dh->DonHang_LocTheoNgay($tuNgay, $denNgay);

            // Lấy các thống kê khác
            $tongQuan = $this->layThongKeTongQuan();
            $thongKePhuongThuc = $this->layThongKePhuongThucThanhToan();

            // Lấy top sản phẩm bán chạy
            $topSanPham = $this->dh->DonHang_TopSanPham(10);

            $this->view('Master', [
                'page' => 'Thongkedoanhthu_v',
                'tongquan' => $tongQuan,
                'danhsachdonhang' => $danhSachDonHang,
                'thongkephuongthuc' => $thongKePhuongThuc,
                'top_sanpham' => $topSanPham,
                'tungay' => $tuNgay,
                'denngay' => $denNgay,
                'ngayhientai' => date('Y-m-d')
            ]);
        }
    }

    // Hàm tìm kiếm đơn hàng
    function timKiem()
    {
        if (isset($_POST['btnTimKiem'])) {
            $maDonHang = $_POST['txtMaDonHang'] ?? '';
            $tenKhachHang = $_POST['txtTenKhachHang'] ?? '';

            // Tìm kiếm đơn hàng
            $result = $this->dh->DonHang_TimKiem($maDonHang, $tenKhachHang);
            $danhSachDonHang = [];

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $danhSachDonHang[] = $row;
                }
            }

            // Lấy các thống kê khác
            $tongQuan = $this->layThongKeTongQuan();
            $thongKePhuongThuc = $this->layThongKePhuongThucThanhToan();

            // Lấy top sản phẩm bán chạy
            $topSanPham = $this->dh->DonHang_TopSanPham(10);

            $this->view('Master', [
                'page' => 'Thongkedoanhthu_v',
                'tongquan' => $tongQuan,
                'danhsachdonhang' => $danhSachDonHang,
                'thongkephuongthuc' => $thongKePhuongThuc,
                'top_sanpham' => $topSanPham,
                'madonhang' => $maDonHang,
                'tenkhachhang' => $tenKhachHang,
                'ngayhientai' => date('Y-m-d')
            ]);
        }
    }

    // Hàm xuất Excel thống kê
    public function xuatExcel()
    {
        // Tải thư viện PHPExcel
        require_once 'Public/Classes/PHPExcel.php';

        // Lấy dữ liệu
        $danhSachDonHang = $this->dh->DonHang_ThongKeChiTiet();

        // Tạo đối tượng Excel
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet()->setTitle('ThongKeDoanhThu');

        // Thiết lập tiêu đề cột
        $sheet->setCellValue('A1', 'Mã đơn hàng');
        $sheet->setCellValue('B1', 'Ngày tạo');
        $sheet->setCellValue('C1', 'Tổng tiền hàng');
        $sheet->setCellValue('D1', 'Trạng thái đơn hàng');
        $sheet->setCellValue('E1', 'Phương thức');
        $sheet->setCellValue('F1', 'Trạng thái thanh toán');
        $sheet->setCellValue('G1', 'Tên khách hàng');
        $sheet->setCellValue('H1', 'Số điện thoại');
        $sheet->setCellValue('I1', 'Địa chỉ');
        $sheet->setCellValue('J1', 'Giá vốn');
        $sheet->setCellValue('K1', 'Lợi nhuận');
        $sheet->setCellValue('L1', 'Tỷ lệ lãi (%)');

        // Thiết lập độ rộng cột
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Thiết lập tiêu đề in đậm
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);

        // Thêm dữ liệu vào sheet
        $rowCount = 2;
        if ($danhSachDonHang && mysqli_num_rows($danhSachDonHang) > 0) {
            while ($row = mysqli_fetch_assoc($danhSachDonHang)) {
                $sheet->setCellValue('A' . $rowCount, $row['ma_don_hang']);
                $sheet->setCellValue('B' . $rowCount, $row['ngay_tao']);
                $sheet->setCellValue('C' . $rowCount, $row['tong_tien_hang']);
                $sheet->setCellValue('D' . $rowCount, $row['trang_thai_don_hang']);
                $sheet->setCellValue('E' . $rowCount, $row['phuong_thuc']);
                $sheet->setCellValue('F' . $rowCount, $row['trang_thai_thanh_toan']);
                $sheet->setCellValue('G' . $rowCount, $row['ten_khach_hang']);
                $sheet->setCellValue('H' . $rowCount, $row['so_dien_thoai']);
                $sheet->setCellValue('I' . $rowCount, $row['dia_chi']);
                $sheet->setCellValue('J' . $rowCount, $row['gia_von']);
                $sheet->setCellValue('K' . $rowCount, $row['loi_nhuan']);
                $sheet->setCellValue('L' . $rowCount, $row['ty_le_lai']);

                $rowCount++;
            }
        }

        // Thiết lập tiêu đề file
        $fileName = 'ThongKeDoanhThu_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Xóa bộ đệm để tránh lỗi
        if (ob_get_length()) ob_end_clean();

        // Thiết lập header để tải file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Xuất file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}
