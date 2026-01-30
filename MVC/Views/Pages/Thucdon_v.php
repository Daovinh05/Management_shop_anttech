<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm món ăn</title>

    <!-- FONT + ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
    </style>
</head>

<body>

    <div class="card">
        <h1>Thêm món mới</h1>
        <p class="lead">Nhập thông tin món ăn mới.</p>
        <form method="post" action="http://localhost/QLSP/Thucdon/ins" enctype="multipart/form-data">
            <div>
                <label>Mã thực đơn <span style="color:red">*</span></label>
                <input type="text" name="txtMathucdon" required
                    value="<?php echo isset($data['ma_thuc_don']) ? htmlspecialchars($data['ma_thuc_don']) : ''; ?>" />
            </div>
            <div>
                <label>Tên món <span style="color:red">*</span></label>
                <input type="text" name="txtTenmon" required
                    value="<?php echo isset($data['ten_mon']) ? htmlspecialchars($data['ten_mon']) : ''; ?>" />
            </div>
            <div>
                <label>Giá</label>
                <input type="number" name="txtGia" required
                    value="<?php echo isset($data['gia']) ? htmlspecialchars($data['gia']) : ''; ?>" />
            </div>
            <div>
                <label>Số lượng</label>
                <input type="number" name="txtSoluong" value="0" min="0" />
            </div>
            <div>
                <label>Danh mục <span style="color:red">*</span></label>
                <select name="ddlDanhmuc" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php
                    if (isset($data['dsdm'])) {
                        while ($row = mysqli_fetch_array($data['dsdm'])) {
                            $selected = (isset($data['ma_danh_muc']) && $data['ma_danh_muc'] == $row['ma_danh_muc']) ? 'selected' : '';
                            echo '<option value="' . $row['ma_danh_muc'] . '" ' . $selected . '>' . htmlspecialchars($row['ten_danh_muc']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label>Hình ảnh</label>
                <div class="file-input-wrapper">
                    <span>Chọn hình ảnh...</span>
                    <input type="file" name="txtImage" accept="image/*" />
                </div>
                <div class="file-name" id="fileName">Chưa chọn file</div>
            </div>

            <div class="actions">
                <a href="http://localhost/QLSP/Thucdon/danhsach" class="btn-back"><i class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="reset" class="btn-ghost">Reset</button>
                    <button type="submit" name="btnLuu" class="btn-primary">Lưu thông tin</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        const fileNameDisplay = document.getElementById('fileName');
        if (e.target.files.length > 0) {
            fileNameDisplay.textContent = 'Đã chọn: ' + e.target.files[0].name;
        } else {
            fileNameDisplay.textContent = 'Chưa chọn file';
        }
    });
    </script>
</body>

</html>