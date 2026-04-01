<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
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

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    </style>

    <div class="card">
        <div class="actions-top">
            <div>
                <h1><i class="fa-solid fa-user"></i> Quản lý Users</h1>
                <p class="lead">Tạo, sửa, xóa tài khoản người dùng.</p>
            </div>
            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Users/themmoi" class="btn-create"><i class="fa-solid fa-plus"></i>
                    Thêm người dùng</a>
                <a href="<?php echo BASE_URL; ?>Users/import_form" class="btn-ghost"><i
                        class="fa-solid fa-file-excel"></i> Nhập Excel</a>
            </div>
        </div>

        <form id="userSearchForm" method="post" action="<?php echo BASE_URL; ?>Users/Timkiem" class="form-search"
            style="margin-bottom:30px;border:1px dashed #cbd5e1;padding:20px;border-radius:12px;background:#f8fafc">
            <div class="search-fields">
                <div>
                    <label for="searchId">Mã user</label>
                    <input type="text" id="searchId" name="txtMauser" placeholder="Nhập mã user..."
                        value="<?php echo isset($data['ma_user']) ? htmlspecialchars($data['ma_user']) : ''; ?>" />
                </div>
                <div>
                    <label for="searchName">Tên user</label>
                    <input type="text" id="searchName" name="txtTenuser" placeholder="Nhập tên user..."
                        value="<?php echo isset($data['ten_user']) ? htmlspecialchars($data['ten_user']) : ''; ?>" />
                </div>
            </div>

            <div class="actions" style="margin-top:0;">
                <button type="submit" class="btn-primary" name="btnTim" value="1"><i class="fa-solid fa-search"></i> Tìm kiếm</button>
                <a href="<?php echo BASE_URL; ?>Users/danhsach" class="btn-ghost">Làm mới</a>
                <button type="button" name="btnXuatexcel" class="btn-excel" onclick="exportUsersExcel()">
                    <i class="fa-solid fa-solid fa-download"></i> Xuất Excel
                </button>
            </div>
        </form>

            <h2><i class="fa-solid fa-list-ul"></i> Danh sách hiện tại</h2>
        <div style="margin:10px 0">
            <strong>Kết quả: <span id="resultCount" class="hint">Đang tải dữ liệu...</span></strong>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Avatar</th>
                        <th>Mã User</th>
                        <th>Họ tên</th>
                        <th>Account</th>
                        <th>Password</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Ngày tạo</th>
                        <th style="text-align:right">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="userBody"></tbody>
            </table>
        </div>
    </div>

        <?php
        $initialUsers = [];
        if (isset($data['dulieu']) && is_a($data['dulieu'], 'mysqli_result')) {
            mysqli_data_seek($data['dulieu'], 0);
            while ($row = mysqli_fetch_assoc($data['dulieu'])) {
                $initialUsers[] = $row;
            }
        }
        ?>
        <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
        const INITIAL_USERS = <?php echo json_encode($initialUsers, JSON_UNESCAPED_UNICODE); ?>;
    const DEFAULT_AVATAR_DATA_URI = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect width="40" height="40" rx="20" fill="%23e5e7eb"/><circle cx="20" cy="15" r="7" fill="%239ca3af"/><path d="M8 34c1.7-6 6.5-10 12-10s10.3 4 12 10" fill="%239ca3af"/></svg>';
    let usersListLoading = false;
    let usersListLastFetchAt = 0;

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

    function getAvatarUrl(avatar) {
        if (avatar && String(avatar).trim() !== '') {
            return BASE_URL + 'Public/Pictures/users/' + encodeURIComponent(avatar);
        }
            return DEFAULT_AVATAR_DATA_URI;
    }

    function renderUserRows(items) {
        const tbody = document.getElementById('userBody');
        const resultCountEl = document.getElementById('resultCount');

        if (!tbody || !resultCountEl) {
            return;
        }

        resultCountEl.textContent = items.length + ' bản ghi';

        if (!items.length) {
            tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;color:#6b7280">Không có kết quả phù hợp.</td></tr>';
            return;
        }

        tbody.innerHTML = items.map((row, index) => {
            const ma = row.ma_user || '';
            const avatarUrl = getAvatarUrl(row.avatar || '');
            return '<tr>'
                + '<td><span style="font-weight:600;color:var(--accent)">' + (index + 1) + '</span></td>'
                    + '<td><img class="avatar" src="' + avatarUrl + '" alt="Avatar" loading="lazy" /></td>'
                + '<td><span style="font-weight:600;color:var(--accent)">' + escapeHtml(ma) + '</span></td>'
                + '<td>' + escapeHtml(row.full_name || '') + '</td>'
                + '<td>' + escapeHtml(row.ten_user || '') + '</td>'
                + '<td>' + escapeHtml(row.password || '') + '</td>'
                + '<td>' + escapeHtml(row.email || '') + '</td>'
                + '<td>' + escapeHtml(row.phan_quyen || '') + '</td>'
                + '<td>' + formatDateTime(row.ngay_tao || '') + '</td>'
                + '<td style="text-align:right">'
                + '<a href="' + BASE_URL + 'Users/sua/' + encodeURIComponent(ma) + '"><button class="btn-edit">✏️ Sửa</button></a> '
                + '<button type="button" class="btn-delete" onclick="deleteUser(\'' + escapeHtml(ma) + '\')">🗑️ Xóa API</button>'
                + '</td>'
                + '</tr>';
        }).join('');
    }

    function loadAllUsers(force = false) {
        const now = Date.now();
        if (!force && (now - usersListLastFetchAt) < 1500) {
            return;
        }

        if (usersListLoading) {
            return;
        }

        usersListLoading = true;
        usersListLastFetchAt = now;

        const resultCountEl = document.getElementById('resultCount');
        if (resultCountEl) {
            resultCountEl.textContent = 'Đang tải users...';
        }

        fetch(BASE_URL + 'Api/Users', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success) {
                    renderUserRows(Array.isArray(data.data) ? data.data : []);
                } else {
                    alert('Không thể tải danh sách users từ API: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                }
            })
            .catch(error => {
                alert('Không thể kết nối API users: ' + error.message);
            })
            .finally(() => {
                usersListLoading = false;
            });
    }

    function deleteUser(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa user ' + id + ' bằng REST API không?')) {
            return;
        }

        fetch(BASE_URL + 'Api/Users/' + encodeURIComponent(id), {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success) {
                    alert('Đã xóa user thành công qua REST API');
                    loadAllUsers(true);
                } else {
                    alert('Lỗi xóa: ' + ((data && data.message) ? data.message : 'Không xác định'));
                }
            })
            .catch(error => {
                alert('Không thể kết nối API xóa: ' + error.message);
            });
    }

    function exportUsersExcel() {
        const ma = (document.getElementById('searchId') || {}).value || '';
        const ten = (document.getElementById('searchName') || {}).value || '';
        const url = new URL(BASE_URL + 'Api/Users');

        if (ma.trim() !== '') {
            url.searchParams.set('ma_user', ma.trim());
        }

        if (ten.trim() !== '') {
            url.searchParams.set('ten_user', ten.trim());
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
                a.download = 'DanhSachUsers.xlsx';
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
        if (window.__usersListInitialized) {
            return;
        }
        window.__usersListInitialized = true;

            renderUserRows(Array.isArray(INITIAL_USERS) ? INITIAL_USERS : []);
            loadAllUsers(true);

        const searchForm = document.getElementById('userSearchForm');
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
            const url = new URL(BASE_URL + 'Api/Users');

            if (ma.trim() !== '') {
                url.searchParams.set('ma_user', ma.trim());
            }

            if (ten.trim() !== '') {
                url.searchParams.set('ten_user', ten.trim());
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
                        renderUserRows(Array.isArray(data.data) ? data.data : []);
                    } else {
                        alert('Tìm kiếm thất bại: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                        renderUserRows([]);
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
