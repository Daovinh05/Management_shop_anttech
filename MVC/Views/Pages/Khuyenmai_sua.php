<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
    /* Simple form styles following existing pattern */
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
    </style>

    <div class="card">
        <h1>Cập nhật Khuyến mãi</h1>
        <p class="lead">Cập nhật thông tin mã khuyến mãi.</p>
        <form id="updatePromotionForm">
            <div>
                <label>Mã khuyến mãi <span style="color:red">*</span></label>
                <input type="text" id="txtMakhuyenmai" name="txtMakhuyenmai" required readonly />
            </div>
            <div>
                <label>Tên khuyến mãi <span style="color:red">*</span></label>
                <input type="text" id="txtTenkhuyenmai" name="txtTenkhuyenmai" required />
            </div>
            <div>
                <label>Ngày bắt đầu <span style="color:red">*</span></label>
                <input type="datetime-local" id="txtNgaybatdau" name="txtNgaybatdau" required>
            </div>

            <div>
                <label>Ngày kết thúc <span style="color:red">*</span></label>
                <input type="datetime-local" id="txtNgayketthuc" name="txtNgayketthuc" required>
            </div>

            <div>
                <label>Tiền khuyến mãi <span style="color:red">*</span></label>
                <input type="number" id="txtTienkhuyenmai" name="txtTienkhuyenmai" required min="0" />
            </div>
            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Khuyenmai/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="reset" class="btn-ghost">Reset</button>
                    <button type="submit" id="updatePromotionBtn" name="btnCapnhat" class="btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';

    function resolvePromotionIdFromUrl() {
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

    function toDatetimeLocalString(value) {
        if (!value) {
            return '';
        }

        const date = new Date(String(value).replace(' ', 'T'));
        if (Number.isNaN(date.getTime())) {
            return '';
        }

        const pad = (n) => String(n).padStart(2, '0');
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate())
            + 'T' + pad(date.getHours()) + ':' + pad(date.getMinutes());
    }

    function loadPromotionByApi() {
        const promotionId = resolvePromotionIdFromUrl();
        if (!promotionId) {
            alert('Không xác định được mã khuyến mãi từ URL.');
            return;
        }

        fetch(BASE_URL + 'Api/Khuyenmai/' + encodeURIComponent(promotionId), {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success && data.data) {
                    const maInput = document.getElementById('txtMakhuyenmai');
                    const tenInput = document.getElementById('txtTenkhuyenmai');
                    const tienInput = document.getElementById('txtTienkhuyenmai');
                    const ngayBatDauInput = document.getElementById('txtNgaybatdau');
                    const ngayKetThucInput = document.getElementById('txtNgayketthuc');

                    if (maInput) maInput.value = data.data.ma_khuyen_mai || '';
                    if (tenInput) tenInput.value = data.data.ten_khuyen_mai || '';
                    if (tienInput) tienInput.value = data.data.tien_khuyen_mai || '';
                    if (ngayBatDauInput) ngayBatDauInput.value = toDatetimeLocalString(data.data.ngay_bat_dau || '');
                    if (ngayKetThucInput) ngayKetThucInput.value = toDatetimeLocalString(data.data.ngay_ket_thuc || '');
                    return;
                }

                alert('Không thể tải thông tin khuyến mãi: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
            })
            .catch(error => {
                alert('Không thể kết nối API khuyến mãi: ' + error.message);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadPromotionByApi();

        const form = document.getElementById('updatePromotionForm');
        const submitBtn = document.getElementById('updatePromotionBtn');

        if (!form || !submitBtn) {
            return;
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const ma = (form.querySelector('input[name="txtMakhuyenmai"]') || {}).value || '';
            const ten = (form.querySelector('input[name="txtTenkhuyenmai"]') || {}).value || '';
            const tien = (form.querySelector('input[name="txtTienkhuyenmai"]') || {}).value || '';
            const ngayBatDau = (form.querySelector('input[name="txtNgaybatdau"]') || {}).value || '';
            const ngayKetThuc = (form.querySelector('input[name="txtNgayketthuc"]') || {}).value || '';

            if (!ma.trim() || !ten.trim() || !ngayBatDau.trim() || !ngayKetThuc.trim()) {
                alert('Vui lòng nhập đầy đủ thông tin bắt buộc.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang cập nhật...';

            fetch(BASE_URL + 'Api/Khuyenmai/' + encodeURIComponent(ma.trim()), {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ten_khuyen_mai: ten.trim(),
                        tien_khuyen_mai: tien === '' ? 0 : Number(tien),
                        ngay_bat_dau: ngayBatDau,
                        ngay_ket_thuc: ngayKetThuc
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        alert('Cập nhật khuyến mãi thành công qua REST API');
                        window.location.href = BASE_URL + 'Khuyenmai/danhsach';
                    } else {
                        alert('Cập nhật thất bại: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                    }
                })
                .catch(error => {
                    alert('Không thể kết nối API cập nhật khuyến mãi: ' + error.message);
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