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
        <h1>Thêm mới Khuyến mãi</h1>
        <p class="lead">Nhập thông tin mã khuyến mãi.</p>
        <form id="createPromotionForm" method="post" action="<?php echo BASE_URL; ?>Khuyenmai/ins">
            <div>
                <label>Mã khuyến mãi <span style="color:red">*</span></label>
                <input type="text" name="txtMakhuyenmai" data-required="true"
                    value="<?php echo isset($data['ma_khuyen_mai']) ? htmlspecialchars($data['ma_khuyen_mai']) : '' ?>" />
            </div>
            <div>
                <label>Tên khuyến mãi <span style="color:red">*</span></label>
                <input type="text" name="txtTenkhuyenmai" data-required="true"
                    value="<?php echo isset($data['ten_khuyen_mai']) ? htmlspecialchars($data['ten_khuyen_mai']) : '' ?>" />
            </div>
            <div>
                <label>Ngày bắt đầu <span style="color:red">*</span></label>
                <input type="datetime-local" name="txtNgaybatdau" data-required="true" value="<?php echo isset($data['ngay_bat_dau'])
                                                                                        ? date('Y-m-d\TH:i', strtotime($data['ngay_bat_dau']))
                                                                                        : '' ?>">
            </div>

            <div>
                <label>Ngày kết thúc <span style="color:red">*</span></label>
                <input type="datetime-local" name="txtNgayketthuc" data-required="true" value="<?php echo isset($data['ngay_ket_thuc'])
                                                                                        ? date('Y-m-d\TH:i', strtotime($data['ngay_ket_thuc']))
                                                                                        : '' ?>">
            </div>

            <div>
                <label>Tiền khuyến mãi <span style="color:red">*</span></label>
                <input type="number" name="txtTienkhuyenmai" data-required="true" min="0"
                    value="<?php echo isset($data['tien_khuyen_mai']) ? htmlspecialchars($data['tien_khuyen_mai']) : '' ?>" />
            </div>
            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Khuyenmai/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="reset" class="btn-ghost">Reset</button>
                    <button type="submit" id="createPromotionBtn" name="btnLuu" class="btn-primary">Lưu thông tin</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createPromotionForm');
        const submitBtn = document.getElementById('createPromotionBtn');

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
            submitBtn.textContent = 'Đang lưu...';

            fetch(BASE_URL + 'Api/Khuyenmai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ma_khuyen_mai: ma.trim(),
                        ten_khuyen_mai: ten.trim(),
                        tien_khuyen_mai: tien === '' ? 0 : Number(tien),
                        ngay_bat_dau: ngayBatDau,
                        ngay_ket_thuc: ngayKetThuc
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        alert('Thêm khuyến mãi thành công qua REST API');
                        window.location.href = BASE_URL + 'Khuyenmai/danhsach';
                    } else {
                        alert('Lưu thất bại: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                    }
                })
                .catch(error => {
                    alert('Không thể kết nối API tạo khuyến mãi: ' + error.message);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Lưu thông tin';
                });
        });
    });
    </script>

</body>

</html>