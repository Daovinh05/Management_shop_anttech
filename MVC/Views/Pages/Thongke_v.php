<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê - Quản lý quán</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Cards for statistics */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            border-left: 4px solid var(--accent);
        }

        .stat-card.success {
            border-left-color: #9bddc5;
        }

        .stat-card.warning {
            border-left-color: #e4cba1;
        }

        .stat-card.danger {
            border-left-color: #ce8e8e;
        }

        .stat-card.destroy {
            border-left-color: #9585be;
        }

        .stat-label {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #253243;
        }

        .stat-title {
            font-size: 16px;
            font-weight: 600;
            color: #253243;
            margin-bottom: 10px;
        }

        .chart-container {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .chart-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .chart-row {
                grid-template-columns: 1fr;
            }
        }

        .date-filter {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .date-controls {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #253243;
            font-size: 14px;
        }

        input[type="date"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e3e7ef;
            border-radius: 10px;
            background: #fbfdff;
            font-size: 14px;
            outline: none;
        }

        input:focus {
            box-shadow: 0 0 0 4px rgba(36, 99, 255, 0.08);
            border-color: var(--accent);
        }

        button {
            padding: 12px 20px;
            border-radius: 10px;
            border: 0;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            transition: 0.2s;
            border: none;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }

        canvas {
            max-height: 300px;
            width: 100% !important;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-chart-line"></i> Thống kê doanh thu</h1>
                <p class="lead">Theo dõi và phân tích doanh thu theo thời gian.</p>
            </div>
        </div>

        <div class="date-filter">
            <div class="date-controls">
                <div class="form-group">
                    <label for="tu_ngay">Từ ngày:</label>
                    <input type="date" id="tu_ngay" autocomplete="off" value="<?php echo $data['tu_ngay']; ?>">
                </div>
                <div class="form-group">
                    <label for="den_ngay">Đến ngày:</label>
                    <input type="date" id="den_ngay" autocomplete="off" value="<?php echo $data['den_ngay']; ?>">
                </div>
                <button class="btn-primary" onclick="filterData()">
                    <i class="fa-solid fa-filter"></i> Lọc
                </button>
            </div>
        </div>

        <div class="stat-cards">
            <div class="stat-card">
                <div class="stat-label">Tổng số đơn hàng</div>
                <div class="stat-value"><?php echo number_format($data['stats']['total_orders']); ?></div>
                <div class="stat-title">Đơn hàng</div>
            </div>
            <div class="stat-card success">
                <div class="stat-label">Đơn đã thanh toán</div>
                <div class="stat-value"><?php echo number_format($data['stats']['paid_orders']); ?></div>
                <div class="stat-title">Đơn</div>
            </div>
            <div class="stat-card warning">
                <div class="stat-label">Tổng doanh thu</div>
                <div class="stat-value"><?php echo number_format($data['stats']['total_revenue'], 0, ',', '.'); ?> ₫
                </div>
                <div class="stat-title">Trước giảm giá</div>
            </div>
            <div class="stat-card danger">
                <div class="stat-label">Tổng khuyến mãi</div>
                <div class="stat-value"><?php echo number_format($data['stats']['total_discount'], 0, ',', '.'); ?> ₫
                </div>
                <div class="stat-title">Giảm giá</div>
            </div>
            <div class="stat-card destroy">
                <div class="stat-label">Doanh thu thực tế</div>
                <div class="stat-value"><?php echo number_format($data['stats']['actual_revenue'], 0, ',', '.'); ?> ₫
                </div>
                <div class="stat-title">Sau giảm giá</div>
            </div>
        </div>

        <div class="chart-row">
            <div class="chart-container">
                <h3>Doanh thu theo ngày</h3>
                <div class="chart-wrapper">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="chart-container">
                <h3>Doanh thu theo danh mục</h3>
                <div class="chart-wrapper">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterData() {
            const tuNgay = document.getElementById('tu_ngay').value;
            const denNgay = document.getElementById('den_ngay').value;

            // Tải lại trang với các tham số ngày mới
            window.location.href = `http://localhost/QLSP/Thongke/thongke?tu_ngay=${tuNgay}&den_ngay=${denNgay}`;
        }

        let revenueChart, categoryChart;


        // Lấy dữ liệu biểu đồ và khởi tạo các biểu đồ
        async function loadChartData() {
            const tuNgay = '<?php echo $data['tu_ngay']; ?>';
            const denNgay = '<?php echo $data['den_ngay']; ?>';

            try {
                const response = await fetch(
                    `http://localhost/QLSP/Thongke/getChartData?tu_ngay=${tuNgay}&den_ngay=${denNgay}`);
                const data = await response.json();

                // Khởi tạo biểu đồ doanh thu (biểu đồ đường)
                const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                revenueChart = new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: data.daily_revenue.map(item => item.date),
                        datasets: [{
                            label: 'Doanh thu (VND)',
                            data: data.daily_revenue.map(item => item.daily_revenue),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('vi-VN') + ' ₫';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });

                // Khởi tạo biểu đồ danh mục (biểu đồ tròn)
                const categoryCtx = document.getElementById('categoryChart').getContext('2d');
                categoryChart = new Chart(categoryCtx, {
                    type: 'pie',
                    data: {
                        labels: data.revenue_by_category.map(item => item.ten_danh_muc),
                        datasets: [{
                            label: 'Doanh thu',
                            data: data.revenue_by_category.map(item => item.revenue),
                            backgroundColor: [
                                '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                                '#ec4899', '#f97316', '#06b6d4', '#84cc16', '#f43f5e'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });

            } catch (error) {
                console.error('Error loading chart data:', error);
            }
        }

        // Khởi tạo khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            loadChartData();
        });
    </script>
</body>

</html>