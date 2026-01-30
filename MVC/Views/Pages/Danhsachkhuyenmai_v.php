<!DOCTYPE html>
<html lang="vi">

<body>

    <style>
        /* Custom styles for the actions */
        .btn-create {
            background: #10b981;
            /* Màu xanh lá cây */
            padding: 8px 15px;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-edit {
            background: #ffc107;
            padding: 6px 10px;
            border-radius: 6px;
            margin-right: 5px;
            color: #fff;
            text-decoration: none;
            display: inline-block;
        }

        .btn-delete {
            background: #dc3545;
            padding: 6px 10px;
            border-radius: 6px;
            color: #fff;
            text-decoration: none;
            display: inline-block;
        }

        /* Các style cơ bản khác giữ nguyên */
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --accent: #2463ff;
            --muted: #6b7280;
            --radius: 12px;
            --gap: 16px;
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }

        * {
            box-sizing: border-box
        }

        .card {
            width: 100%;
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: 0 8px 30px rgba(24, 99, 255, 0.08);
            padding: 28px;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 20px
        }

        p.lead {
            margin: 0 0 20px;
            color: var(--muted);
            font-size: 14px
        }

        .form-search {
            display: flex;
            gap: var(--gap);
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .search-fields {
            display: flex;
            gap: var(--gap);
            flex: 1;
        }

        .search-fields>div {
            flex: 1 1 200px;
        }

        .form-search>.actions {
            flex: 0 0 auto;
            display: flex;
            gap: 12px;
        }

        label {
            display: block;
            font-size: 15px;
            color: #253243;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"] {
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

        .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .actions-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        button {
            padding: 10px 16px;
            border-radius: 10px;
            border: 0;
            font-size: 14px;
            cursor: pointer
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            transition: 0.2s;
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid #e6e9f2;
            color: var(--muted);
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            line-height: 1;
        }

        .btn-excel {
            background: #e34ae5ff;
            padding: 10px 16px;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-excel:hover {
            background: #e50f9aff;
        }

        .table-container {
            max-height: 500px;
            overflow-y: auto;
            margin-top: 20px;
            border: 1px solid #e3e7ef;
            border-radius: var(--radius);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        thead th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            z-index: 10;
            border-bottom: 2px solid #e3e7ef;
            font-weight: 600;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e3e7ef;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        .hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px
        }
    </style>

    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-gift"></i> Quản lý Khuyến Mãi</h1>
                <p class="lead">Theo dõi và quản lý các chương trình khuyến mãi.</p>
            </div>
            <div class="actions">
                <a href="http://localhost/QLSP/Khuyenmai/themmoi" class="btn-create"><i class="fa-solid fa-plus"></i>
                    Thêm KM </a>
                <a href="http://localhost/QLSP/Khuyenmai/import_form" class="btn-ghost"><i
                        class="fa-solid fa-file-excel"></i> Nhập
                    Excel</a>
            </div>
        </div>

        <form method="post" action="http://localhost/QLSP/Khuyenmai/Timkiem" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã khuyến mãi</label>
                    <input type="text" id="searchId" name="txtMakhuyenmai" placeholder="Nhập mã khuyến mãi..."
                        value="<?php echo isset($data['ma_khuyen_mai']) ? htmlspecialchars($data['ma_khuyen_mai']) : ''; ?>" />
                </div>
                <div>
                    <label for="searchName">Tên khuyến mãi</label>
                    <input type="text" id="searchName" name="txtTenkhuyenmai" placeholder="Nhập tên khuyến mãi..."
                        value="<?php echo isset($data['ten_khuyen_mai']) ? htmlspecialchars($data['ten_khuyen_mai']) : ''; ?>" />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim"><i class="fa-solid fa-search"></i> Tìm
                    kiếm</button>
                <a href="http://localhost/QLSP/Khuyenmai/danhsach" class="btn-ghost">Làm mới</a>
                <button type="submit" name="btnXuatexcel" class="btn-excel">
                    <i class="fa-solid fa-solid fa-download"></i> Xuất Excel
                </button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2><i class="fa-solid fa-list-ul"></i> Danh sách hiện tại</h2>
        <?php
        // Đặt lại con trỏ dữ liệu
        if (isset($data['dulieu']) && is_a($data['dulieu'], 'mysqli_result')) {
            mysqli_data_seek($data['dulieu'], 0);
        }

        // Đảm bảo dữ liệu tồn tại
        if (isset($data['dulieu'])) {
            // Giả định $data['dulieu'] là mysqli_result
            // Đặt lại con trỏ về đầu để có thể đếm và dùng lại bên dưới
            if (is_object($data['dulieu'])) {
                $count = mysqli_num_rows($data['dulieu']);
                mysqli_data_seek($data['dulieu'], 0);
            } else {
                $count = 0;
            }
        ?>
            <div style="margin:10px 0">
                <strong>Kết quả: <span id="resultCount" class="hint"></span></strong>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã KM</th>
                            <th>Tên khuyến mãi</th>
                            <th>Tiền khuyến mãi</th>
                            <th>Ghi chú</th>
                            <th style="text-align:right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="kmBody">
                        <?php
                        // Render dữ liệu tĩnh ban đầu
                        if ($count > 0) {
                            $serial = 1; // Khởi tạo bộ đếm số thứ tự
                            while ($row = mysqli_fetch_array($data['dulieu'])) {
                        ?>
                                <tr>
                                    <td><span style="font-weight:600;color:var(--accent)"><?php echo $serial++; ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['ma_khuyen_mai']) ?></td>
                                    <td><?php echo htmlspecialchars($row['ten_khuyen_mai']) ?></td>
                                    <td><?php echo number_format($row['tien_khuyen_mai'], 0, ',', '.') ?> ₫</td>
                                    <td><?php echo htmlspecialchars($row['ghi_chu']) ?></td>
                                    <td style="text-align:right">
                                        <a
                                            href="http://localhost/QLSP/Khuyenmai/sua/<?php echo urlencode($row['ma_khuyen_mai']) ?>"><button
                                                class="btn-edit">✏️
                                                Sửa</button></a>
                                        <a href="http://localhost/QLSP/Khuyenmai/xoa/<?php echo urlencode($row['ma_khuyen_mai']) ?>"
                                            onclick="return confirm('Bạn có chắc chắn muốn xoá không?')"><button
                                                class="btn-delete">🗑️
                                                Xóa</button></a>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
            <script>
                // Manual search only (no AJAX)
                const idInput = document.getElementById('searchId');
                const nameInput = document.getElementById('searchName');
                const resultCount = document.getElementById('resultCount');

                // khởi tạo đếm
                resultCount.textContent = '<?php echo $count; ?> bản ghi';
            </script>
        <?php } ?>
        <?php if (isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) === 0) { ?>
            <div class="hint">Không có kết quả phù hợp.</div>
        <?php } ?>

    </div>
</body>

</html>