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
        <form id="updateVariantForm" method="post" action="<?php echo BASE_URL; ?>BienThe/update" enctype="multipart/form-data">
            <div>
                <label>Mã biến thể <span style="color:red">*</span></label>
                <input type="text" id="txtMaBienThe" name="txtMaBienThe" required readonly
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
                <a href="<?php echo BASE_URL; ?>BienThe/danhsach" class="btn-back"><i class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="submit" name="btnCapnhat" class="btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';

    document.getElementById('updateVariantForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const maBienThe = (document.getElementById('txtMaBienThe') || {}).value || '';
        if (!maBienThe.trim()) {
            alert('Thiếu mã biến thể để cập nhật');
            return;
        }

        const form = this;
        const formData = new FormData(form);
        const selectedImage = form.querySelector('input[name="txtImage"]') ? form.querySelector('input[name="txtImage"]').files[0] : null;

        let requestPromise;

        if (selectedImage) {
            // PHP native chỉ parse multipart upload qua POST, nên trường hợp có ảnh giữ POST.
            requestPromise = fetch(BASE_URL + 'Api/Bienthe/update/' + encodeURIComponent(maBienThe.trim()), {
                method: 'POST',
                body: formData
            });
        } else {
            const payload = {
                ma_bien_the: maBienThe.trim(),
                ma_san_pham: formData.get('ddlSanPham') || '',
                ten_bien_the: formData.get('txtTenBienThe') || '',
                mau_sac: formData.get('txtMauSac') || '',
                ram: formData.get('txtRAM') || '',
                dung_luong: formData.get('txtDungLuong') || '',
                gia: formData.get('txtGia') || '0',
                so_luong_kho: formData.get('txtSoLuongKho') || '0'
            };

            requestPromise = fetch(BASE_URL + 'Api/Bienthe/' + encodeURIComponent(maBienThe.trim()), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
        }

        requestPromise
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));
                return {
                    status: response.status,
                    data
                };
            })
            .then((result) => {
                if (result.status >= 200 && result.status < 300 && result.data.success) {
                    alert('Cập nhật biến thể thành công qua REST API');
                    window.location.href = BASE_URL + 'BienThe/danhsach';
                    return;
                }

                alert('Cập nhật biến thể thất bại: ' + (result.data.message || 'Lỗi không xác định'));
            })
            .catch((error) => {
                alert('Không thể kết nối API cập nhật biến thể: ' + error.message);
            });
    });
    </script>
</body>

</html>
