<!DOCTYPE html>
<html lang="vi">

<body>

    <style>
        .btn-create {
            background: #10b981;
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
                <h1><i class="fa-solid fa-utensils"></i> Quản lý Thực Đơn</h1>
                <p class="lead">Tra cứu và cập nhật thực đơn quán.</p>
            </div>
            <div class="actions">
                <!-- <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'nhan_vien'): ?> -->
                <a href="http://localhost/QLSP/Thucdon/themmoi" class="btn-create"><i class="fa-solid fa-plus"></i>
                    Thêm món </a>
                <a href="http://localhost/QLSP/Thucdon/import_form" class="btn-ghost"><i
                        class="fa-solid fa-file-excel"></i> Nhập
                    Excel</a>
                <!-- <?php elseif ($_SESSION['user_role'] === 'khach_hang' && isset($data['ma_ban'])): ?>
                <?php endif; ?> -->
            </div>
        </div>

        <form method="post" action="http://localhost/QLSP/Thucdon/Timkiem" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã thực đơn</label>
                    <input type="text" id="searchId" name="txtMathucdon" placeholder="Nhập mã TD..."
                        value="<?php echo isset($data['ma_thuc_don']) ? htmlspecialchars($data['ma_thuc_don']) : ''; ?>" />
                </div>
                <div>
                    <label for="searchName">Tên món</label>
                    <input type="text" id="searchName" name="txtTenmon" placeholder="Nhập tên món..."
                        value="<?php echo isset($data['ten_mon']) ? htmlspecialchars($data['ten_mon']) : ''; ?>" />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim"><i class="fa-solid fa-search"></i>
                    Tìmkiếm</button>

                <a href="http://localhost/QLSP/Thucdon/danhsach" class="btn-ghost">Làm mới</a>
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
                            <th>Mã SP</th>
                            <th>Tên SP</th>
                            <th>Hình ảnh</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tên danh mục</th>
                            <th>Tên thương hiệu</th>
                            <th>Tên nhà cung cấp</th>
                            
                            
                            <th style="text-align:right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="spBody">
                        <?php

                        if ($count > 0) {
                            $serial = 1;
                            while ($row = mysqli_fetch_array($data['dulieu'])) {
                        ?>
                                <tr>
                                    <td><span style="font-weight:600;color:var(--accent)"><?php echo $serial++; ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['ma_san_pham']) ?></td>
                                    <td><?php echo htmlspecialchars($row['ten_san_pham']) ?></td>
                                    <td>
                                        <?php if ($row['img_hinh_anh']): ?>
                                            <img src="<?php echo !empty($row['img_hinh_anh']) ? '/qlsp/Public/Pictures/thucdon/' . htmlspecialchars($row['img_hinh_anh']) : '/qlsp/Public/Pictures/no-image.png'; ?>"
                                                alt="<?php echo htmlspecialchars($row['ten_san_pham']) ?>"
                                                style="width:50px;height:50px;object-fit:cover;border-radius:5px;" />
                                        <?php else: ?>
                                            <span>Không có hình</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo number_format($row['gia'], 0, ',', '.') ?> ₫</td>
                                    <td>
                                        <?php if ($row['so_luong_kho'] > 0): ?>
                                            <span
                                                style="background:#d1fae5;color:#065f46;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">
                                                Còn <?php echo htmlspecialchars($row['so_luong_kho']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span
                                                style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">
                                                Hết hàng
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo isset($row['ten_danh_muc']) ? htmlspecialchars($row['ten_danh_muc']) : 'N/A' ?>
                                    <td><?php echo isset($row['ten_thuong_hieu']) ? htmlspecialchars($row['ten_thuong_hieu']) : 'N/A' ?>
                                    <td><?php echo isset($row['ten_nha_cung_cap']) ? htmlspecialchars($row['ten_nha_cung_cap']) : 'N/A' ?>
                                    </td>
                                    <td style="text-align:right">
                                        <!-- <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'nhan_vien'): ?> -->
                                        <a href="http://localhost/QLSP/Thucdon/sua/<?php echo urlencode($row['ma_san_pham']) ?>"><button
                                                class="btn-edit">✏️
                                                Sửa</button></a>
                                        <a href="http://localhost/QLSP/Thucdon/xoa/<?php echo urlencode($row['ma_san_pham']) ?>"
                                            onclick="return confirm('Bạn có chắc chắn muốn xoá không?')"><button
                                                class="btn-delete">🗑️
                                                Xóa</button></a>
                                        <!-- <?php endif; ?> -->
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <script>
                const resultCount = document.getElementById('resultCount');
                resultCount.textContent = '<?php echo $count; ?> bản ghi';
            </script>
        <?php
        }
        if (isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) === 0):
        ?>
            <div class="hint">Không có kết quả phù hợp.</div>
        <?php endif; ?>

    </div>

</body>

</html>