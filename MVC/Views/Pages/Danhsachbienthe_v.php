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

    .variant-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #e5e7eb;
    }
    </style>

    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-list"></i> Quản lý biến thể</h1>
                <p class="lead">Quản lý biến thể sản phẩm.</p>
            </div>
            <div class="actions">
                <a href="<?php echo BASE_URL ?>BienThe/themmoi" class="btn-create"><i class="fa-solid fa-plus"></i>
                    Thêm mới</a>
                <a href="<?php echo BASE_URL ?>BienThe/import_form" class="btn-ghost"><i
                        class="fa-solid fa-file-excel"></i> Nhập Excel</a>
            </div>
        </div>

        <form id="variantSearchForm" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã biến thể</label>
                    <input type="text" id="searchId" name="txtMaBienThe" placeholder="Nhập mã biến thể..." />
                </div>
                <div>
                    <label for="searchName">Tên biến thể</label>
                    <input type="text" id="searchName" name="txtTenBienThe" placeholder="Nhập tên biến thể..." />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim" value="1"><i class="fa-solid fa-search"></i> Tìm kiếm</button>
                <a href="<?php echo BASE_URL ?>BienThe/danhsach" class="btn-ghost">Làm mới</a>
                <button type="button" name="btnXuatexcel" class="btn-excel" onclick="exportVariantsExcel()">
                    <i class="fa-solid fa-solid fa-download"></i> Xuất Excel
                </button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2><i class="fa-solid fa-list-ul"></i> Danh sách hiện tại</h2>
        <div style="margin:10px 0">
            <strong>Kết quả: <span id="resultCount" class="hint">Đang tải dữ liệu...</span></strong>
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
                        <th style="text-align:right">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="variantBody"></tbody>
            </table>
        </div>
    </div>

    <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
    let variantsListLoading = false;

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    }

    function formatPrice(value) {
        const num = Number(value || 0);
        return num.toLocaleString('vi-VN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }

    function variantImageHtml(fileName, tenBienThe) {
        if (!fileName || String(fileName).trim() === '') {
            return '<span>Không có hình</span>';
        }

        const src = BASE_URL + 'Public/Pictures/bien_the/' + encodeURIComponent(fileName);
        return '<img class="variant-image" src="' + src + '" alt="' + escapeHtml(tenBienThe || 'Biến thể') + '" />';
    }

    function renderVariantRows(items) {
        const tbody = document.getElementById('variantBody');
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
            const ma = row.ma_bien_the || '';
            const soLuong = Number(row.so_luong_kho || 0);
            const stockBadge = soLuong > 0
                ? '<span style="background:#d1fae5;color:#065f46;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">Còn ' + escapeHtml(soLuong) + '</span>'
                : '<span style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600">Hết hàng</span>';

            return '<tr>'
                + '<td><span style="font-weight:600;color:var(--accent)">' + (items.length - index) + '</span></td>'
                + '<td><span style="font-weight:600;color:var(--accent)">' + escapeHtml(ma) + '</span></td>'
                + '<td>' + escapeHtml(row.ten_san_pham || row.ma_san_pham || '') + '</td>'
                + '<td>' + escapeHtml(row.ten_bien_the || '') + '</td>'
                + '<td>' + variantImageHtml(row.img_bien_the || '', row.ten_bien_the || '') + '</td>'
                + '<td>' + escapeHtml(row.mau_sac || '') + '</td>'
                + '<td>' + escapeHtml(row.ram || '') + '</td>'
                + '<td>' + escapeHtml(row.dung_luong || '') + '</td>'
                + '<td>' + formatPrice(row.gia || 0) + '</td>'
                + '<td>' + stockBadge + '</td>'
                + '<td style="text-align:right">'
                + '<a href="' + BASE_URL + 'BienThe/sua/' + encodeURIComponent(ma) + '"><button class="btn-edit">✏️ Sửa</button></a> '
                + '<button type="button" class="btn-delete" onclick="deleteVariant(\'' + escapeHtml(ma) + '\')">🗑️ Xóa </button>'
                + '</td>'
                + '</tr>';
        }).join('');
    }

    function loadAllVariants() {
        if (variantsListLoading) {
            return;
        }

        variantsListLoading = true;

        fetch(BASE_URL + 'Api/Bienthe', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success) {
                    renderVariantRows(Array.isArray(data.data) ? data.data : []);
                } else {
                    alert('Không thể tải danh sách biến thể từ API: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                }
            })
            .catch(error => {
                alert('Không thể kết nối API biến thể: ' + error.message);
            })
            .finally(() => {
                variantsListLoading = false;
            });
    }

    function deleteVariant(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa biến thể ' + id + ' không?')) {
            return;
        }

        fetch(BASE_URL + 'Api/Bienthe/' + encodeURIComponent(id), {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success) {
                    alert('Đã xóa biến thể thành công');
                    loadAllVariants();
                } else {
                    alert('Lỗi xóa: ' + ((data && data.message) ? data.message : 'Không xác định'));
                }
            })
            .catch(error => {
                alert('Không thể kết nối API xóa: ' + error.message);
            });
    }

    function exportVariantsExcel() {
        const ma = (document.getElementById('searchId') || {}).value || '';
        const ten = (document.getElementById('searchName') || {}).value || '';
        const url = new URL(BASE_URL + 'Api/Bienthe');

        if (ma.trim() !== '') {
            url.searchParams.set('ma_bien_the', ma.trim());
        }

        if (ten.trim() !== '') {
            url.searchParams.set('ten_bien_the', ten.trim());
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
                a.download = 'DanhSachBienThe.xlsx';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(blobUrl);
            })
            .catch(error => {
                alert('Không thể xuất Excel qua API: ' + error.message);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.__variantListInitialized) {
            return;
        }
        window.__variantListInitialized = true;

        loadAllVariants();

        const searchForm = document.getElementById('variantSearchForm');
        if (!searchForm) {
            return;
        }

        searchForm.addEventListener('submit', function(event) {
            const submitter = event.submitter;
            const submitName = submitter && submitter.name ? submitter.name : '';

            if (submitName !== 'btnTim') {
                return;
            }

            event.preventDefault();

            const ma = (document.getElementById('searchId') || {}).value || '';
            const ten = (document.getElementById('searchName') || {}).value || '';
            const url = new URL(BASE_URL + 'Api/Bienthe');

            if (ma.trim() !== '') {
                url.searchParams.set('ma_bien_the', ma.trim());
            }

            if (ten.trim() !== '') {
                url.searchParams.set('ten_bien_the', ten.trim());
            }

            fetch(url.toString(), {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        renderVariantRows(Array.isArray(data.data) ? data.data : []);
                    } else {
                        alert('Tìm kiếm thất bại: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                        renderVariantRows([]);
                    }
                })
                .catch(error => {
                    alert('Không thể kết nối API tìm kiếm: ' + error.message);
                });
        });
    });
    </script>
</body>

</html>
