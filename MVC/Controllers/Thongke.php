<?php
class Thongke extends controller
{
    private $dh;
    private $ctdh;

    function __construct()
    {
        $this->dh = $this->model("Donhang_m");
        $this->ctdh = $this->model("Chitietdonhang_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị thống kê
        $this->thongke();
    }

    function index()
    {
        $this->thongke();
    }

    function thongke()
    {
        // Default to current month if no dates provided
        $tu_ngay = isset($_GET['tu_ngay']) ? $_GET['tu_ngay'] : date('Y-m-01');
        $den_ngay = isset($_GET['den_ngay']) ? $_GET['den_ngay'] : date('Y-m-d');

        // Lấy dữ liệu thống kê bằng phương thức của mô hình
        $stats = $this->dh->getStatisticsByDate($tu_ngay, $den_ngay);

        $this->view('Master', [
            'page' => 'Thongke_v',
            'stats' => $stats,
            'tu_ngay' => $tu_ngay,
            'den_ngay' => $den_ngay
        ]);
    }

    // Phương thức để lấy dữ liệu biểu đồ cho các yêu cầu AJAX
    function getChartData()
    {
        $tu_ngay = isset($_GET['tu_ngay']) ? $_GET['tu_ngay'] : date('Y-m-01');
        $den_ngay = isset($_GET['den_ngay']) ? $_GET['den_ngay'] : date('Y-m-d');

        // Lấy dữ liệu doanh thu hàng ngày cho biểu đồ đường
        $daily_revenue = $this->dh->getDailyRevenue($tu_ngay, $den_ngay);

        // Lấy doanh thu theo danh mục cho biểu đồ tròn
        $revenue_by_category = $this->dh->getRevenueByCategory($tu_ngay, $den_ngay);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'daily_revenue' => $daily_revenue,
            'revenue_by_category' => $revenue_by_category
        ]);
        exit;
    }
}
