<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Biến thể</title>
</head>

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
        <h1>Sửa Biến thể</h1>
        <p class="lead">Chỉnh sửa thông tin biến thể sản phẩm.</p>
        <form method="post" action="<?php echo BASE_URL; ?>BienThe/update" enctype="multipart/form-data">
            <div>
                <label>Mã biến thể <span style="color:red">*</span></label>
                <input type="text" name="txtMaBienThe" required readonly
                    value="<?php echo isset($data['mabienthe']) ? htmlspecialchars($data['mabienthe']) : '' ?>" />
            </div>
            <div>
                <label>Sản phẩm <span style="color:red">*</span></label>
                <select name="ddlSanPham" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    <?php
                    if (isset($data['dssp'])) {
                        while ($row = mysqli_fetch_array($data['dssp'])) {
                            $selected = (isset($data['masanpham']) && $data['masanpham'] == $row['ma_san_pham']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row['ma_san_pham']) . '" ' . $selected . '>' . htmlspecialchars($row['ten_san_pham']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label>Tên biến thể</label>
                <input type="text" name="txtTenBienThe"
                    value="<?php echo isset($data['tenbienthe']) ? htmlspecialchars($data['tenbienthe']) : '' ?>" />
            </div>
            <div>
                <label>Hình ảnh biến thể</label>
                <input type="file" name="txtImage" accept="image/*" />
                <?php if (isset($data['imgbienthe']) && !empty($data['imgbienthe'])): ?>
                    <div style="margin-top: 10px;">
                        <img src="<?php echo UrlHelper::url('Public/Pictures/bien_the/') . htmlspecialchars($data['imgbienthe']); ?>"
                             alt="Hình ảnh biến thể" style="max-width: 100px; max-height: 100px;">
                        <p>Chọn file mới để thay đổi hình ảnh</p>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <label>Màu sắc</label>
                <input type="text" name="txtMauSac"
                    value="<?php echo isset($data['mausac']) ? htmlspecialchars($data['mausac']) : '' ?>" />
            </div>
            <div>
                <label>Ram</label>
                <input type="text" name="txtRAM"
                    value="<?php echo isset($data['ram']) ? htmlspecialchars($data['ram']) : '' ?>" />
            </div>
            <div>
                <label>Dung lượng</label>
                <input type="text" name="txtDungLuong"
                    value="<?php echo isset($data['dungluong']) ? htmlspecialchars($data['dungluong']) : '' ?>" />
            </div>
            <div>
                <label>Giá</label>
                <input type="number" name="txtGia" step="0.01"
                    value="<?php echo isset($data['gia']) ? htmlspecialchars($data['gia']) : '' ?>" />
            </div>
            <div>
                <label>Số lượng kho</label>
                <input type="number" name="txtSoLuongKho"
                    value="<?php echo isset($data['soluongkho']) ? htmlspecialchars($data['soluongkho']) : '' ?>" />
            </div>

            <div class="actions">
                <a href="<?php echo BASE_URL; ?>BienThe/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="submit" name="btnCapnhat" class="btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>