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
        <form id="updateVariantForm" enctype="multipart/form-data">
            <div>
                <label>Mã biến thể <span style="color:red">*</span></label>
                <input type="text" id="txtMaBienThe" name="txtMaBienThe" data-required="true" readonly />
            </div>
            <div>
                <label>Sản phẩm <span style="color:red">*</span></label>
                <select id="ddlSanPham" name="ddlSanPham" data-required="true">
                    <option value="">-- Chọn sản phẩm --</option>
                </select>
            </div>
            <div>
                <label>Tên biến thể</label>
                <input type="text" id="txtTenBienThe" name="txtTenBienThe" />
            </div>
            <div>
                <label>Hình ảnh biến thể</label>
                <input type="file" id="txtImage" name="txtImage" accept="image/*" />
                <div id="currentImageWrap" style="display:none;margin-top:10px;">
                    <img id="currentImagePreview" alt="Hình ảnh biến thể" style="max-width:100px;max-height:100px;">
                    <p>Chọn file mới để thay đổi hình ảnh</p>
                </div>
            </div>
            <div>
                <label>Màu sắc</label>
                <input type="text" id="txtMauSac" name="txtMauSac" />
            </div>
            <div>
                <label>Ram</label>
                <input type="text" id="txtRAM" name="txtRAM" />
            </div>
            <div>
                <label>Dung lượng</label>
                <input type="text" id="txtDungLuong" name="txtDungLuong" />
            </div>
            <div>
                <label>Giá</label>
                <input type="number" id="txtGia" name="txtGia" step="0.01" />
            </div>
            <div>
                <label>Số lượng kho</label>
                <input type="number" id="txtSoLuongKho" name="txtSoLuongKho" />
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

    function resolveVariantIdFromUrl() {
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

    function loadProducts(selectedProductId) {
        const selectEl = document.getElementById('ddlSanPham');
        if (!selectEl) {
            return Promise.resolve();
        }

        return fetch(BASE_URL + 'Api/Products', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (!(data && data.success && Array.isArray(data.data))) {
                    throw new Error((data && data.message) ? data.message : 'Không thể tải danh sách sản phẩm');
                }

                selectEl.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';
                data.data.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.ma_san_pham || '';
                    option.textContent = product.ten_san_pham || product.ma_san_pham || '';
                    if (selectedProductId && option.value === selectedProductId) {
                        option.selected = true;
                    }
                    selectEl.appendChild(option);
                });
            });
    }

    function fillVariantForm(variant) {
        const maInput = document.getElementById('txtMaBienThe');
        const tenInput = document.getElementById('txtTenBienThe');
        const mauSacInput = document.getElementById('txtMauSac');
        const ramInput = document.getElementById('txtRAM');
        const dungLuongInput = document.getElementById('txtDungLuong');
        const giaInput = document.getElementById('txtGia');
        const soLuongInput = document.getElementById('txtSoLuongKho');

        if (maInput) maInput.value = variant.ma_bien_the || '';
        if (tenInput) tenInput.value = variant.ten_bien_the || '';
        if (mauSacInput) mauSacInput.value = variant.mau_sac || '';
        if (ramInput) ramInput.value = variant.ram || '';
        if (dungLuongInput) dungLuongInput.value = variant.dung_luong || '';
        if (giaInput) giaInput.value = variant.gia || '';
        if (soLuongInput) soLuongInput.value = variant.so_luong_kho || '';

        const imageWrap = document.getElementById('currentImageWrap');
        const imagePreview = document.getElementById('currentImagePreview');
        if (imageWrap && imagePreview) {
            const imageName = variant.img_bien_the || '';
            if (imageName) {
                imagePreview.src = BASE_URL + 'Public/Pictures/bien_the/' + encodeURIComponent(imageName);
                imageWrap.style.display = 'block';
            } else {
                imageWrap.style.display = 'none';
            }
        }

        return loadProducts(variant.ma_san_pham || '');
    }

    function loadVariantByApi() {
        const variantId = resolveVariantIdFromUrl();
        if (!variantId) {
            alert('Không xác định được mã biến thể từ URL.');
            return;
        }

        fetch(BASE_URL + 'Api/Bienthe/' + encodeURIComponent(variantId), {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success && data.data) {
                    return fillVariantForm(data.data);
                }

                throw new Error((data && data.message) ? data.message : 'Lỗi không xác định');
            })
            .catch(error => {
                alert('Không thể tải dữ liệu biến thể: ' + error.message);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadVariantByApi();
    });

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
