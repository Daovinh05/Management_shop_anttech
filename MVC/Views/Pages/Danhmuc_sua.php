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
        <h1>Sửa Danh mục</h1>
        <p class="lead">Chỉnh sửa thông tin danh mục.</p>
        <form method="post" action="http://localhost/Banhang/Danhmuc/update" enctype="multipart/form-data">
            <div>
                <label>Mã danh mục <span style="color:red">*</span></label>
                <input type="text" name="txtMadanhmuc" required readonly
                    value="<?php echo isset($data['ma_danh_muc']) ? htmlspecialchars($data['ma_danh_muc']) : '' ?>" />
            </div>
            <div>
                <label>Tên danh mục <span style="color:red">*</span></label>
                <input type="text" name="txtTendanhmuc" required
                    value="<?php echo isset($data['ten_danh_muc']) ? htmlspecialchars($data['ten_danh_muc']) : '' ?>" />
            </div>
            <div>
                <label>Hình ảnh hiện tại</label>
                <?php if (isset($data['image']) && !empty($data['image'])): ?>
                    <div class="current-image">
                        <img src="/banhang/Public/Pictures/danhmuc/<?php echo htmlspecialchars($data['image']) ?>" alt="Hình ảnh danh mục hiện tại" />
                        <p><small>Hình ảnh hiện tại (giữ nguyên nếu không chọn hình mới)</small></p>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <label>Chọn hình ảnh mới (nếu có)</label>
                <div class="file-input-wrapper">
                    <span>Chọn hình ảnh...</span>
                    <input type="file" name="txtImage" accept="image/*" />
                </div>
                <div class="file-name" id="fileName">Chưa chọn file</div>
            </div>

            <div class="actions">
                <a href="http://localhost/Banhang/Danhmuc/danhsach" class="btn-back"><i class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="submit" name="btnCapnhat" class="btn-primary">Cập nhật</button>
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