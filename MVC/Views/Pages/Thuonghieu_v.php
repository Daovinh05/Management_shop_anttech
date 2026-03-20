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

    <div class="card">
        <h1>Thêm mới Thương hiệu</h1>
        <p class="lead">Nhập thông tin thương hiệu mới.</p>
        <form method="post" action="<?php echo BASE_URL; ?>Thuonghieu/ins" enctype="multipart/form-data">
            <div>
                <label>Mã thương hiệu <span style="color:red">*</span></label>
                <input type="text" name="txtMathuonghieu" required
                    value="<?php echo isset($data['ma_thuong_hieu']) ? htmlspecialchars($data['ma_thuong_hieu']) : '' ?>" />
            </div>
            <div>
                <label>Tên thương hiệu <span style="color:red">*</span></label>
                <input type="text" name="txtTenthuonghieu" required
                    value="<?php echo isset($data['ten_thuong_hieu']) ? htmlspecialchars($data['ten_thuong_hieu']) : '' ?>" />
            </div>
            <!-- <div>
                <label>Hình ảnh</label>
                <div class="file-input-wrapper">
                    <span>Chọn hình ảnh...</span>
                    <input type="file" name="txtImage" accept="image/*" />
                </div>
                <div class="file-name" id="fileName">Chưa chọn file</div>
            </div> -->

            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Thuonghieu/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
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