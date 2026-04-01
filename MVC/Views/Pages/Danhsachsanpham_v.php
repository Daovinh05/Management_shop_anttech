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
                <h1><i class="fa-solid fa-box-open"></i> Quản lý Sản phẩm</h1>
                <p class="lead">Tra cứu và cập nhật sản phẩm.</p>
            </div>
            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Sanpham/themmoi" class="btn-create"><i class="fa-solid fa-plus"></i>
                    Thêm sản phẩm </a>
                <a href="<?php echo BASE_URL; ?>Sanpham/import_form" class="btn-ghost"><i
                        class="fa-solid fa-file-excel"></i> Nhập
                    Excel</a>
            </div>
        </div>

        <form id="productSearchForm" method="post" action="<?php echo BASE_URL; ?>Sanpham/Timkiem" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã sản phẩm</label>
                    <input type="text" id="searchId" name="txtMasanpham" placeholder="Nhập mã sản phẩm..."
                        value="<?php echo isset($data['ma_san_pham']) ? htmlspecialchars($data['ma_san_pham']) : ''; ?>" />
                </div>
                <div>
                    <label for="searchName">Tên sản phẩm</label>
                    <input type="text" id="searchName" name="txtTensanpham" placeholder="Nhập tên sản phẩm..."
                        value="<?php echo isset($data['ten_san_pham']) ? htmlspecialchars($data['ten_san_pham']) : ''; ?>" />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim" value="1"><i class="fa-solid fa-search"></i>
                    Tìmkiếm</button>

                <a href="<?php echo BASE_URL; ?>Sanpham/danhsach" class="btn-ghost">Làm mới</a>
                <button type="button" id="btnExportApi" class="btn-excel" onclick="exportProductsExcel()">
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
                        <th>Tên biến thể</th>
                        <th>Hình ảnh</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Nhà cung cấp</th>


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
                        <td><?php echo htmlspecialchars($row['ten_bien_the']) ?></td>
                        <td>
                            <?php if ($row['img_bien_the']): ?>
                            <img src="<?php echo UrlHelper::url('Public/Pictures/bien_the/') . htmlspecialchars($row['img_bien_the']); ?>"
                                alt="<?php echo htmlspecialchars($row['ten_san_pham']) ?>"
                                style="width:50px;height:50px;object-fit:cover;border-radius:5px;" />
                            <?php else: ?>
                            <span>Không có hình</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo isset($row['gia']) && $row['gia'] ? number_format($row['gia'], 0, ',', '.') : 'N/A' ?>
                            ₫</td>
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
                        <td><?php echo isset($row['ten_danh_muc']) ? htmlspecialchars($row['ten_danh_muc']) : 'N/A' ?>
                        <td><?php echo isset($row['ten_thuong_hieu']) ? htmlspecialchars($row['ten_thuong_hieu']) : 'N/A' ?>
                        <td><?php echo isset($row['ten_nha_cung_cap']) ? htmlspecialchars($row['ten_nha_cung_cap']) : 'N/A' ?>
                        </td>
                        <td style="text-align:right">
                            <!-- <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'nhan_vien'): ?> -->
                            <a href="<?php echo BASE_URL; ?>Sanpham/sua/<?php echo urlencode($row['ma_san_pham']) ?>"><button
                                    class="btn-edit">✏️
                                    Sửa</button></a>
                            <button type="button" class="btn-delete" onclick="deleteProduct('<?php echo htmlspecialchars($row['ma_san_pham']) ?>')">🗑️ Xóa API</button>
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

    <!-- SCRIPT TEST TÍCH HỢP REST API -->
    <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';

    function deleteProduct(id) {
        if(!confirm('Bạn có chắc chắn muốn xóa sản phẩm ' + id + ' vĩnh viễn bằng REST API không?')) return;
        const endpoint = '<?php echo BASE_URL; ?>Api/Products/' + encodeURIComponent(id);
        
        console.log("Đang bắn DELETE lên:", endpoint);
        fetch(endpoint, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert("✅ Đã xóa " + id + " thành công qua REST API!");
                location.reload(); // Tải lại trang để bảng tự cập nhật
            } else {
                alert("❌ Lỗi xóa: " + data.message);
            }
        })
        .catch(error => {
            console.error("Lỗi:", error);
            alert("Lỗi sập mạng khi gọi API DELETE!");
        });
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    }

    function formatCurrency(value) {
        const numberValue = Number(value || 0);
        return numberValue.toLocaleString('vi-VN') + ' ₫';
    }

    function renderProductRows(items) {
        const tbody = document.getElementById('spBody');
        const resultCountEl = document.getElementById('resultCount');

        if (!tbody || !resultCountEl) {
            return;
        }

        resultCountEl.textContent = items.length + ' bản ghi';

        if (!items.length) {
            tbody.innerHTML = '<tr><td colspan="11" style="text-align:center;color:#6b7280">Không có kết quả phù hợp.</td></tr>';
            return;
        }

        tbody.innerHTML = items.map((row, index) => {
            const hasImage = row.img_bien_the && row.img_bien_the !== '';
            const imageHtml = hasImage
                ? '<img src="' + BASE_URL + 'Public/Pictures/bien_the/' + encodeURIComponent(row.img_bien_the) + '" alt="' + escapeHtml(row.ten_san_pham || '') + '" style="width:50px;height:50px;object-fit:cover;border-radius:5px;" />'
                : '<span>Không có hình</span>';

            const stockHtml = Number(row.so_luong_kho || 0) > 0
                ? '<span style="background:#d1fae5;color:#065f46;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">Còn ' + escapeHtml(row.so_luong_kho) + '</span>'
                : '<span style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">Hết hàng</span>';

            return '<tr>'
                + '<td><span style="font-weight:600;color:var(--accent)">' + (index + 1) + '</span></td>'
                + '<td>' + escapeHtml(row.ma_san_pham || '') + '</td>'
                + '<td>' + escapeHtml(row.ten_san_pham || '') + '</td>'
                + '<td>' + escapeHtml(row.ten_bien_the || '') + '</td>'
                + '<td>' + imageHtml + '</td>'
                + '<td>' + (row.gia ? formatCurrency(row.gia) : 'N/A ₫') + '</td>'
                + '<td>' + stockHtml + '</td>'
                + '<td>' + escapeHtml(row.ten_danh_muc || 'N/A') + '</td>'
                + '<td>' + escapeHtml(row.ten_thuong_hieu || 'N/A') + '</td>'
                + '<td>' + escapeHtml(row.ten_nha_cung_cap || 'N/A') + '</td>'
                + '<td style="text-align:right">'
                + '<a href="' + BASE_URL + 'Sanpham/sua/' + encodeURIComponent(row.ma_san_pham || '') + '"><button class="btn-edit">✏️ Sửa</button></a> '
                + '<button type="button" class="btn-delete" onclick="deleteProduct(\'' + escapeHtml(row.ma_san_pham || '') + '\')">🗑️ Xóa API</button>'
                + '</td>'
                + '</tr>';
        }).join('');
    }

    function exportProductsExcel() {
        const maSanPham = (document.getElementById('searchId') || {}).value || '';
        const tenSanPham = (document.getElementById('searchName') || {}).value || '';
        const url = new URL(BASE_URL + 'Api/Products');

        if (maSanPham.trim() !== '') {
            url.searchParams.set('ma_san_pham', maSanPham.trim());
        }

        if (tenSanPham.trim() !== '') {
            url.searchParams.set('ten_san_pham', tenSanPham.trim());
        }

        url.searchParams.set('format', 'xlsx');

        fetch(url.toString(), {
                method: 'GET'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Xuất Excel thất bại với mã HTTP ' + response.status);
                }
                return response.blob();
            })
            .then(blob => {
                const blobUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = blobUrl;
                a.download = 'DanhSachSanPham.xlsx';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(blobUrl);
            })
            .catch(error => {
                console.error('Lỗi xuất Excel API:', error);
                alert('❌ Không thể xuất Excel qua API: ' + error.message);
            });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const searchForm = document.getElementById('productSearchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function(event) {
                const submitter = event.submitter;
                const submitName = submitter && submitter.name ? submitter.name : '';

                // Chỉ chặn submit của nút tìm kiếm
                if (submitName !== 'btnTim') {
                    return;
                }

                event.preventDefault();

                const maSanPham = (document.getElementById('searchId') || {}).value || '';
                const tenSanPham = (document.getElementById('searchName') || {}).value || '';
                const url = new URL(BASE_URL + 'Api/Products');

                if (maSanPham.trim() !== '') {
                    url.searchParams.set('ma_san_pham', maSanPham.trim());
                }

                if (tenSanPham.trim() !== '') {
                    url.searchParams.set('ten_san_pham', tenSanPham.trim());
                }

                const resultCountEl = document.getElementById('resultCount');
                if (resultCountEl) {
                    resultCountEl.textContent = 'Đang tìm kiếm...';
                }

                fetch(url.toString(), {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.success) {
                            renderProductRows(Array.isArray(data.data) ? data.data : []);
                        } else {
                            alert('❌ Tìm kiếm thất bại: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                            renderProductRows([]);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi tìm kiếm API:', error);
                        alert('❌ Không thể kết nối API tìm kiếm.');
                    });
            });
        }

        console.log("Đang gọi REST API ngầm để test...");
        // Gọi API lấy danh sách sản phẩm bằng Fetch
        fetch('<?php echo BASE_URL; ?>Api/Products')
            .then(response => response.json())
            .then(data => {
                console.log("✅ REST API ĐÃ TRẢ VỀ DỮ LIỆU THÀNH CÔNG:", data);
                console.log("Bạn có thể kiểm tra tab 'Network' (F12 -> Network -> Fetch/XHR) để thấy Request này.");
            })
            .catch(error => console.error("Lỗi khi gọi API:", error));
    });
    </script>

</body>

</html>