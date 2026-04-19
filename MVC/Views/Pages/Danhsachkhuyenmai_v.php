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
                <h1><i class="fa-solid fa-gift"></i> Quản lý Khuyến mãi</h1>
                <p class="lead">Theo dõi và quản lý các chương trình khuyến mãi.</p>
            </div>
            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Khuyenmai/themmoi" class="btn-create"><i class="fa-solid fa-plus"></i>
                    Thêm mới khuyến mãi </a>
                <a href="<?php echo BASE_URL; ?>Khuyenmai/import_form" class="btn-ghost"><i class="fa-solid fa-file-excel"></i> Nhập Excel</a>
            </div>
        </div>

        <form id="promotionSearchForm" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã khuyến mãi</label>
                    <input type="text" id="searchId" name="txtMakhuyenmai" placeholder="Nhập mã khuyến mãi..." />
                </div>
                <div>
                    <label for="searchName">Tên khuyến mãi</label>
                    <input type="text" id="searchName" name="txtTenkhuyenmai" placeholder="Nhập tên khuyến mãi..." />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim" value="1"><i class="fa-solid fa-search"></i> Tìm kiếm</button>
                <a href="<?php echo BASE_URL; ?>Khuyenmai/danhsach" class="btn-ghost">Làm mới</a>
                <button type="button" name="btnXuatexcel" class="btn-excel" onclick="exportPromotionsExcel()">
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
                        <th>Mã KM</th>
                        <th>Tên khuyến mãi</th>
                        <th>Tiền khuyến mãi</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Trạng thái khuyến mại</th>
                        <th style="text-align:right">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="promotionBody"></tbody>
            </table>
        </div>

    </div>

    <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    }

    function formatDateTime(dateTimeString) {
        if (!dateTimeString) {
            return '';
        }

        const date = new Date(String(dateTimeString).replace(' ', 'T'));
        if (Number.isNaN(date.getTime())) {
            return escapeHtml(dateTimeString);
        }

        const pad = (n) => String(n).padStart(2, '0');
        return pad(date.getHours()) + ':' + pad(date.getMinutes()) + ':' + pad(date.getSeconds())
            + ' ' + pad(date.getDate()) + '/' + pad(date.getMonth() + 1) + '/' + date.getFullYear();
    }

    function formatCurrency(value) {
        const num = Number(value || 0);
        return num.toLocaleString('vi-VN') + ' ₫';
    }

    function getStatusBadge(ngayKetThuc) {
        const now = new Date();
        const end = new Date(String(ngayKetThuc || '').replace(' ', 'T'));
        const isExpired = Number.isNaN(end.getTime()) ? true : (end < now);

        if (isExpired) {
            return '<span style="background:#fed7aa;color:#c2410c;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;">Hết khuyến mãi</span>';
        }
        return '<span style="background:#d1fae5;color:#065f46;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:600;">Đang khuyến mãi</span>';
    }

    function renderPromotionRows(items) {
        const tbody = document.getElementById('promotionBody');
        const resultCountEl = document.getElementById('resultCount');

        if (!tbody || !resultCountEl) {
            return;
        }

        resultCountEl.textContent = items.length + ' bản ghi';

        if (!items.length) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:#6b7280">Không có kết quả phù hợp.</td></tr>';
            return;
        }

        tbody.innerHTML = items.map((row, index) => {
            const ma = row.ma_khuyen_mai || '';
            return '<tr>'
                + '<td><span style="font-weight:600;color:var(--accent)">' + (items.length - index) + '</span></td>'
                + '<td>' + escapeHtml(ma) + '</td>'
                + '<td>' + escapeHtml(row.ten_khuyen_mai || '') + '</td>'
                + '<td>' + formatCurrency(row.tien_khuyen_mai || 0) + '</td>'
                + '<td>' + formatDateTime(row.ngay_bat_dau || '') + '</td>'
                + '<td>' + formatDateTime(row.ngay_ket_thuc || '') + '</td>'
                + '<td>' + getStatusBadge(row.ngay_ket_thuc || '') + '</td>'
                + '<td style="text-align:right">'
                + '<a href="' + BASE_URL + 'Khuyenmai/sua/' + encodeURIComponent(ma) + '"><button class="btn-edit">✏️ Sửa</button></a> '
                + '<button type="button" class="btn-delete" onclick="deletePromotion(\'' + escapeHtml(ma) + '\')">🗑️ Xóa API</button>'
                + '</td>'
                + '</tr>';
        }).join('');
    }

    function loadAllPromotions() {
        const resultCountEl = document.getElementById('resultCount');
        if (resultCountEl) {
            resultCountEl.textContent = 'Đang tải khuyến mãi...';
        }

        fetch(BASE_URL + 'Api/Khuyenmai', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success) {
                    renderPromotionRows(Array.isArray(data.data) ? data.data : []);
                } else {
                    alert('Không thể tải danh sách khuyến mãi từ API: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                }
            })
            .catch(error => {
                alert('Không thể kết nối API khuyến mãi: ' + error.message);
            });
    }

    function deletePromotion(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa khuyến mãi ' + id + ' bằng REST API không?')) {
            return;
        }

        fetch(BASE_URL + 'Api/Khuyenmai/' + encodeURIComponent(id), {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success) {
                    alert('Đã xóa khuyến mãi thành công qua REST API');
                    loadAllPromotions();
                } else {
                    alert('Lỗi xóa: ' + ((data && data.message) ? data.message : 'Không xác định'));
                }
            })
            .catch(error => {
                alert('Không thể kết nối API xóa: ' + error.message);
            });
    }

    function exportPromotionsExcel() {
        const ma = (document.getElementById('searchId') || {}).value || '';
        const ten = (document.getElementById('searchName') || {}).value || '';
        const url = new URL(BASE_URL + 'Api/Khuyenmai');

        if (ma.trim() !== '') {
            url.searchParams.set('ma_khuyen_mai', ma.trim());
        }

        if (ten.trim() !== '') {
            url.searchParams.set('ten_khuyen_mai', ten.trim());
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
                a.download = 'DanhSachKhuyenMai.xlsx';
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
        loadAllPromotions();

        const searchForm = document.getElementById('promotionSearchForm');
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
            const url = new URL(BASE_URL + 'Api/Khuyenmai');

            if (ma.trim() !== '') {
                url.searchParams.set('ma_khuyen_mai', ma.trim());
            }

            if (ten.trim() !== '') {
                url.searchParams.set('ten_khuyen_mai', ten.trim());
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
                        renderPromotionRows(Array.isArray(data.data) ? data.data : []);
                    } else {
                        alert('Tìm kiếm thất bại: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                        renderPromotionRows([]);
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
