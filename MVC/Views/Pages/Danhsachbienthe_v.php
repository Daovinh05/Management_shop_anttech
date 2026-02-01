<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
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
                <h1><i class="fa-solid fa-list"></i> Quản lý biến thể</h1>
                <p class="lead">Quản lý biến thể sản phẩm.</p>
            </div>
            <div class="actions">
                <a href="http://localhost/BanHang/BienThe/themmoi" class="btn-create"><i class="fa-solid fa-plus"></i>
                    Thêm mới</a>
                <a href="http://localhost/BanHang/BienThe/import_form" class="btn-ghost"><i
                        class="fa-solid fa-file-excel"></i> Nhập
                    Excel</a>
            </div>
        </div>

        <form method="post" action="http://localhost/BanHang/BienThe/Timkiem" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã biến thể</label>
                    <input type="text" id="searchId" name="txtMaBienThe" placeholder="Nhập mã biến thể..."
                        value="<?php echo isset($data['mabienthe']) ? htmlspecialchars($data['mabienthe']) : ''; ?>" />
                </div>
                <div>
                    <label for="searchName">Tên biến thể</label>
                    <input type="text" id="searchName" name="txtTenBienThe" placeholder="Nhập tên biến thể..."
                        value="<?php echo isset($data['tenbienthe']) ? htmlspecialchars($data['tenbienthe']) : ''; ?>" />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim"><i class="fa-solid fa-search"></i> Tìm
                    kiếm</button>
                <a href="http://localhost/BanHang/BienThe/danhsach" class="btn-ghost">Làm mới</a>
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
                        <th>Mã biến thể</th>
                        <th>Tên sản phẩm</th>
                        <th>Tên biến thể</th>
                        <th>Hình ảnh</th>
                        <th>Màu sắc</th>
                        <th>Ram</th>
                        <th>Dung lượng</th>
                        <th>Giá</th>
                        <th>Số lượng kho</th>
                        <!-- <th>Ngày tạo</th> -->
                        <th style="text-align:right">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="dmBody">
                    <?php
                        // Render dữ liệu tĩnh ban đầu
                        if ($count > 0) {
                            $serial = 1; // Khởi tạo bộ đếm số thứ tự
                            while ($row = mysqli_fetch_array($data['dulieu'])) {
                        ?>
                    <tr>
                        <td><span style="font-weight:600;color:var(--accent)"><?php echo $serial++; ?></span>
                        </td>
                        <td><span
                                style="font-weight:600;color:var(--accent)"><?php echo htmlspecialchars($row['ma_bien_the']) ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($row['ten_san_pham']) ?></td>
                        <td><?php echo htmlspecialchars($row['ten_bien_the']) ?></td>
                        <td>
                            <?php if (!empty($row['img_bien_the'])): ?>
                            <img src="/Banhang/Public/Pictures/bien_the/<?php echo htmlspecialchars($row['img_bien_the']); ?>"
                                alt="<?php echo htmlspecialchars($row['ten_bien_the']); ?>"
                                style="width:50px;height:50px;object-fit:cover;border-radius:5px;" />
                            <?php else: ?>
                            <span>Không có hình</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['mau_sac']) ?></td>
                        <td><?php echo htmlspecialchars($row['ram']) ?></td>
                        <td><?php echo htmlspecialchars($row['dung_luong']) ?></td>
                        <td><?php echo number_format($row['gia'], 2) ?></td>
                        <td>
                            <?php if (isset($row['so_luong_kho']) && $row['so_luong_kho'] > 0): ?>
                            <span
                                style="background:#d1fae5;color:#065f46;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">
                                Còn
                                <?php echo htmlspecialchars(isset($row['so_luong_kho']) ? $row['so_luong_kho'] : 'N/A'); ?>
                            </span>
                            <?php else: ?>
                            <span
                                style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">
                                Hết hàng
                            </span>
                            <?php endif; ?>
                        </td>
                        <!-- <td><?php echo isset($row['ngay_tao']) ? htmlspecialchars($row['ngay_tao']) : '' ?></td> -->
                        <td style="text-align:right">
                            <a href="http://localhost/BanHang/BienThe/sua/<?php echo urlencode($row['ma_bien_the']) ?>"><button
                                    class="btn-edit">✏️
                                    Sửa</button></a>
                            <a href="http://localhost/BanHang/BienThe/xoa/<?php echo urlencode($row['ma_bien_the']) ?>"
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