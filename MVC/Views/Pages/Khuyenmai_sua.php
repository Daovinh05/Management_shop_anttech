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
        <form method="post" action="http://localhost/Banhang/Khuyenmai/update">
            <div>
                <label>Mã khuyến mãi <span style="color:red">*</span></label>
                <input type="text" name="txtMakhuyenmai" required
                    value="<?php echo isset($data['ma_khuyen_mai']) ? htmlspecialchars($data['ma_khuyen_mai']) : '' ?>"
                    readonly />
            </div>
            <div>
                <label>Tên khuyến mãi <span style="color:red">*</span></label>
                <input type="text" name="txtTenkhuyenmai" required
                    value="<?php echo isset($data['ten_khuyen_mai']) ? htmlspecialchars($data['ten_khuyen_mai']) : '' ?>" />
            </div>
            <div>
                <label>Ngày bắt đầu <span style="color:red">*</span></label>
                <input type="datetime-local" name="txtNgaybatdau" required value="<?php echo isset($data['ngay_bat_dau'])
                                ? date('Y-m-d\TH:i', strtotime($data['ngay_bat_dau']))
                                : '' ?>">
            </div>

            <div>
                <label>Ngày kết thúc <span style="color:red">*</span></label>
                <input type="datetime-local" name="txtNgayketthuc" required value="<?php echo isset($data['ngay_ket_thuc'])
                                ? date('Y-m-d\TH:i', strtotime($data['ngay_ket_thuc']))
                                : '' ?>">
            </div>

            <div>
                <label>Tiền khuyến mãi <span style="color:red">*</span></label>
                <input type="number" name="txtTienkhuyenmai" required min="0"
                    value="<?php echo isset($data['tien_khuyen_mai']) ? htmlspecialchars($data['tien_khuyen_mai']) : '' ?>" />
            </div>
            <div class="actions">
                <a href="http://localhost/Banhang/Khuyenmai/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="reset" class="btn-ghost">Reset</button>
                    <button type="submit" name="btnCapnhat" class="btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>