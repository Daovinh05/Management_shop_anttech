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
        
        $this->view('Master', [
            'page' => 'thongkedoanhthu_v',
            'tongquan' => $tongQuan,
            'danhsachdonhang' => $danhSachDonHang,
            'thongkephuongthuc' => $thongKePhuongThuc,
            'ngayhientai' => $ngayHienTai
        ]);
    }
    
    private function layThongKeTongQuan()
    {
        // Lấy thống kê tổng quan về đơn hàng
        $result = $this->dh->DonHang_ThongKeTongQuan();
        
        $data = [
            'cho_duyet' => 0,
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
        
        $data['tong_don'] = $data['cho_duyet'] + $data['dang_giao'] + $data['hoan_thanh'];
        
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
            
            $this->view('Master', [
                'page' => 'thongkedoanhthu_v',
                'tongquan' => $tongQuan,
                'danhsachdonhang' => $danhSachDonHang,
                'thongkephuongthuc' => $thongKePhuongThuc,
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
            
            $this->view('Master', [
                'page' => 'thongkedoanhthu_v',
                'tongquan' => $tongQuan,
                'danhsachdonhang' => $danhSachDonHang,
                'thongkephuongthuc' => $thongKePhuongThuc,
                'madonhang' => $maDonHang,
                'tenkhachhang' => $tenKhachHang,
                'ngayhientai' => date('Y-m-d')
            ]);
        }
    }
}
