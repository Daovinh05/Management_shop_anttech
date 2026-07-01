<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    .card {
        width: 100%;
        background: #fff;
        padding: 28px;
        border-radius: 12px
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e3e7ef
    }

    .actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px
    }

    .btn-back {
        background: #6b7280;
        color: #fff;
        padding: 8px 15px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px
    }

    .btn-ghost {
        background: transparent;
        border: 1px solid #e6e9f2;
        color: #6b7280;
        padding: 10px 16px;
        border-radius: 10px
    }

    .btn-primary {
        background: #2463ff;
        color: #fff;
        padding: 10px 16px;
        border-radius: 10px;
        border: 0
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        cursor: pointer;
        background: #f8fafc;
        padding: 10px 15px;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        transition: border-color 0.3s;
    }

    .file-input-wrapper:hover {
        border-color: #9ca3af;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-name {
        margin-top: 8px;
        font-size: 14px;
        color: #4b5563;
    }

    .current-image {
        margin-top: 10px;
        text-align: center;
    }

    .current-image img {
        max-width: 150px;
        max-height: 150px;
        object-fit: contain;
        border: 1px solid #e3e7ef;
        border-radius: 5px;
    }
    </style>

    <div class="card">
        <h1>Sửa Nhà cung cấp</h1>
        <p class="lead">Chỉnh sửa thông tin nhà cung cấp.</p>
        <form id="updateSupplierForm">
            <div>
                <label>Mã nhà cung cấp <span style="color:red">*</span></label>
                <input type="text" id="txtManhacungcap" name="txtManhacungcap" data-required="true" readonly />
            </div>
            <div>
                <label>Tên nhà cung cấp <span style="color:red">*</span></label>
                <input type="text" id="txtTennhacungcap" name="txtTennhacungcap" data-required="true" />
            </div>
            <div>
                <label for="phone">Điện thoại(10 số)</label>
                <input type="tel" id="phone" name="txtSodienthoai" placeholder="VD: 0912345678" data-required="true"
                    maxlength="10"
                    pattern="[0-9]{10}" title="Vui lòng nhập đúng 10 chữ số điện thoại" />
            </div>

            <div class="full">
                <label for="address">Địa chỉ</label>
                <input type="text" id="txtDiachi" name="txtDiachi" placeholder="Nhập địa chỉ nhà cung cấp" />
            </div>


            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Nhacungcap/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="submit" id="updateSupplierBtn" name="btnCapnhat" class="btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';

    function resolveSupplierIdFromUrl() {
        const searchParams = new URLSearchParams(window.location.search);
        const routedUrl = searchParams.get('url');

        if (routedUrl) {
            const routeParts = routedUrl.split('/').filter(Boolean);
            if (routeParts.length > 0) {
                return decodeURIComponent(routeParts[routeParts.length - 1]);
            }
        }

        const pathParts = window.location.pathname.replace(/\/+$/, '').split('/').filter(Boolean);
        return pathParts.length > 0 ? decodeURIComponent(pathParts[pathParts.length - 1]) : '';
    }

    function loadSupplierByApi() {
        const supplierId = resolveSupplierIdFromUrl();
        if (!supplierId) {
            alert('Không xác định được mã nhà cung cấp từ URL.');
            return;
        }

        fetch(BASE_URL + 'Api/Nhacungcap/' + encodeURIComponent(supplierId), {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success && data.data) {
                    const maInput = document.getElementById('txtManhacungcap');
                    const tenInput = document.getElementById('txtTennhacungcap');
                    const diaChiInput = document.getElementById('txtDiachi');
                    const soDienThoaiInput = document.getElementById('phone');

                    if (maInput) maInput.value = data.data.ma_nha_cung_cap || '';
                    if (tenInput) tenInput.value = data.data.ten_nha_cung_cap || '';
                    if (diaChiInput) diaChiInput.value = data.data.dia_chi || '';
                    if (soDienThoaiInput) soDienThoaiInput.value = data.data.dien_thoai || '';
                    return;
                }

                alert('Không thể tải thông tin nhà cung cấp: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
            })
            .catch(error => {
                alert('Không thể kết nối API nhà cung cấp: ' + error.message);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadSupplierByApi();

        const form = document.getElementById('updateSupplierForm');
        const submitBtn = document.getElementById('updateSupplierBtn');

        if (!form || !submitBtn) {
            return;
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const ma = (form.querySelector('input[name="txtManhacungcap"]') || {}).value || '';
            const ten = (form.querySelector('input[name="txtTennhacungcap"]') || {}).value || '';
            const diaChi = (form.querySelector('input[name="txtDiachi"]') || {}).value || '';
            const dienThoai = (form.querySelector('input[name="txtSodienthoai"]') || {}).value || '';

            if (!ma.trim() || !ten.trim()) {
                alert('Vui lòng nhập đầy đủ mã và tên nhà cung cấp.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang cập nhật...';

            fetch(BASE_URL + 'Api/Nhacungcap/' + encodeURIComponent(ma.trim()), {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ten_nha_cung_cap: ten.trim(),
                        dia_chi: diaChi.trim(),
                        dien_thoai: dienThoai.trim()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        alert('Cập nhật nhà cung cấp thành công qua REST API');
                        window.location.href = BASE_URL + 'Nhacungcap/danhsach';
                    } else {
                        alert('Cập nhật thất bại: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                    }
                })
                .catch(error => {
                    alert('Không thể kết nối API cập nhật nhà cung cấp: ' + error.message);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Cập nhật';
                });
        });
    });
    </script>
</body>

</html>