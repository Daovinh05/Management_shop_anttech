<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cập nhật sản phẩm</title>

    <!-- FONT + ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

        .current-image {
            margin-top: 10px;
        }

        .current-image img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="card">
        <h1>Cập nhật sản phẩm</h1>
        <p class="lead">Chỉnh sửa thông tin sản phẩm.</p>
        <form id="formEditProduct">
            <div>
                <label>Mã sản phẩm <span style="color:red">*</span></label>
                <input type="text" name="txtMasanpham" value="<?php echo htmlspecialchars($data['ma_san_pham']); ?>"
                    readonly />
            </div>
            <div>
                <label>Tên món <span style="color:red">*</span></label>
                <input type="text" name="txtTensanpham"
                    value="<?php echo htmlspecialchars($data['ten_san_pham']); ?>" />
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
                <label>Thương hiệu <span style="color:red">*</span></label>
                <select name="ddlThuonghieu" required>
                    <option value="">-- Chọn thương hiệu --</option>
                    <?php
                    if (isset($data['dsth'])) {
                        while ($row = mysqli_fetch_array($data['dsth'])) {
                            $selected = (isset($data['ma_thuong_hieu']) && $data['ma_thuong_hieu'] == $row['ma_thuong_hieu']) ? 'selected' : '';
                            echo '<option value="' . $row['ma_thuong_hieu'] . '" ' . $selected . '>' . htmlspecialchars($row['ten_thuong_hieu']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label>Nhà cung cấp <span style="color:red">*</span></label>
                <select name="ddlNhacungcap" required>
                    <option value="">-- Chọn nhà cung cấp --</option>
                    <?php
                    if (isset($data['dsncc'])) {
                        while ($row = mysqli_fetch_array($data['dsncc'])) {
                            $selected = (isset($data['ma_nha_cung_cap']) && $data['ma_nha_cung_cap'] == $row['ma_nha_cung_cap']) ? 'selected' : '';
                            echo '<option value="' . $row['ma_nha_cung_cap'] . '" ' . $selected . '>' . htmlspecialchars($row['ten_nha_cung_cap']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label>Lưu ý</label>
                <p style="color: #666; font-style: italic;">Hình ảnh,giá,số lượng sản phẩm được quản lý ở cấp độ biến
                    thể. Vui lòng thêm hình ảnh cho từng biến thể cụ thể.</p>
            </div>

            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Sanpham/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="reset" class="btn-ghost">Reset</button>
                    <button type="submit" class="btn-primary">Cập nhật bằng API</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('formEditProduct').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const payload = {
            "ma_san_pham": formData.get('txtMasanpham'),
            "ten_san_pham": formData.get('txtTensanpham'),
            "ma_danh_muc": formData.get('ddlDanhmuc'),
            "ma_thuong_hieu": formData.get('ddlThuonghieu'),
            "ma_nha_cung_cap": formData.get('ddlNhacungcap')
        };

        console.log("Đang bắn PUT lên API:", payload);

        fetch('<?php echo BASE_URL; ?>Api/Products/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert("✅ Cập nhật thành công bằng CÒN REST API!");
                window.location.href = "<?php echo BASE_URL; ?>Sanpham/danhsach";
            } else {
                alert("❌ Lỗi: " + data.message);
            }
        })
        .catch(error => {
            console.error(error);
            alert("Lỗi sập mạng khi gọi API PUT!");
        });
    });

    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const fileNameDisplay = document.getElementById('fileName');
            if(fileNameDisplay) {
                if (e.target.files.length > 0) {
                    fileNameDisplay.textContent = 'Đã chọn: ' + e.target.files[0].name;
                } else {
                    fileNameDisplay.textContent = 'Chưa chọn file mới';
                }
            }
        });
    }
    </script>
</body>

</html>