<?php
class Thongke extends controller
{
    function __construct() {}

    function Get_data()
    {
        // Hàm mặc định - hiển thị thống kê doanh thu
        $this->thongke();
    }

    function thongke()
    {
        // API-first: du lieu thong ke duoc lay qua REST endpoint /Api/Thongke/dashboard.
        $ngayHienTai = date('Y-m-d');

        $this->view('Master', [
            'page' => 'Thongkedoanhthu_v',
            'tongquan' => [
                'cho_duyet' => 0,
                'da_duyet' => 0,
                'dang_giao' => 0,
                'hoan_thanh' => 0,
                'da_huy' => 0,
                'tong_don' => 0
            ],
            'danhsachdonhang' => [],
            'thongkephuongthuc' => [
                'cod' => ['so_don' => 0, 'doanh_thu' => 0],
                'vnpay' => ['so_don' => 0, 'doanh_thu' => 0],
                'momo' => ['so_don' => 0, 'doanh_thu' => 0],
                'banking' => ['so_don' => 0, 'doanh_thu' => 0],
                'tong_so_don' => 0
            ],
            'top_sanpham' => [],
            'ngayhientai' => $ngayHienTai
        ]);
    }

    // Hàm lọc theo ngày
    function locTheoNgay()
    {
        http_response_code(410);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung su dung. Vui long dung GET /Api/Thongke/dashboard voi query tu_ngay, den_ngay'
        ]);
        exit;
    }

    // Hàm tìm kiếm đơn hàng
    function timKiem()
    {
        http_response_code(410);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint da ngung su dung. Vui long dung GET /Api/Thongke/dashboard voi query ma_don_hang, ten_khach_hang'
        ]);
        exit;
    }

    // Hàm xuất Excel thống kê
    public function xuatExcel()
    {
        $query = $_GET;
        $url = BASE_URL . 'Api/Thongke/export';
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        header('Location: ' . $url);
        exit;
    }
}
