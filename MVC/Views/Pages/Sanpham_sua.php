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
                <input type="text" id="txtMasanpham" name="txtMasanpham" readonly />
            </div>
            <div>
                <label>Tên món <span style="color:red">*</span></label>
                <input type="text" id="txtTensanpham" name="txtTensanpham" required />
            </div>
            <div>
                <label>Danh mục <span style="color:red">*</span></label>
                <select id="ddlDanhmuc" name="ddlDanhmuc" required>
                    <option value="">-- Chọn danh mục --</option>
                </select>
            </div>
            <div>
                <label>Thương hiệu <span style="color:red">*</span></label>
                <select id="ddlThuonghieu" name="ddlThuonghieu" required>
                    <option value="">-- Chọn thương hiệu --</option>
                </select>
            </div>
            <div>
                <label>Nhà cung cấp <span style="color:red">*</span></label>
                <select id="ddlNhacungcap" name="ddlNhacungcap" required>
                    <option value="">-- Chọn nhà cung cấp --</option>
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
    const BASE_URL = '<?php echo BASE_URL; ?>';

    function resolveProductIdFromUrl() {
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

    function fillSelect(selectElement, items, valueField, textField, selectedValue) {
        if (!selectElement) {
            return;
        }

        selectElement.innerHTML = '<option value="">-- Chọn --</option>';

        items.forEach(item => {
            const value = item[valueField] || '';
            const text = item[textField] || '';
            const option = document.createElement('option');
            option.value = value;
            option.textContent = text;
            if (selectedValue && value === selectedValue) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
    }

    async function loadEditProductData() {
        const productId = resolveProductIdFromUrl();
        if (!productId) {
            alert('Không xác định được mã sản phẩm từ URL.');
            return;
        }

        const productUrl = BASE_URL + 'Api/Products/' + encodeURIComponent(productId);
        const categoryUrl = BASE_URL + 'Api/Danhmuc';
        const brandUrl = BASE_URL + 'Api/Thuonghieu';
        const supplierUrl = BASE_URL + 'Api/Nhacungcap';

        try {
            const responses = await Promise.all([
                fetch(productUrl, { method: 'GET' }),
                fetch(categoryUrl, { method: 'GET' }),
                fetch(brandUrl, { method: 'GET' }),
                fetch(supplierUrl, { method: 'GET' })
            ]);

            const [productJson, categoryJson, brandJson, supplierJson] = await Promise.all(
                responses.map(r => r.json().catch(() => ({ success: false })))
            );

            if (!productJson.success || !productJson.data) {
                alert('Không thể tải thông tin sản phẩm.');
                return;
            }

            const product = productJson.data;
            const categories = (categoryJson && categoryJson.success && Array.isArray(categoryJson.data)) ? categoryJson.data : [];
            const brands = (brandJson && brandJson.success && Array.isArray(brandJson.data)) ? brandJson.data : [];
            const suppliers = (supplierJson && supplierJson.success && Array.isArray(supplierJson.data)) ? supplierJson.data : [];

            const inputId = document.getElementById('txtMasanpham');
            const inputName = document.getElementById('txtTensanpham');
            const selectCategory = document.getElementById('ddlDanhmuc');
            const selectBrand = document.getElementById('ddlThuonghieu');
            const selectSupplier = document.getElementById('ddlNhacungcap');

            if (inputId) inputId.value = product.ma_san_pham || '';
            if (inputName) inputName.value = product.ten_san_pham || '';

            fillSelect(selectCategory, categories, 'ma_danh_muc', 'ten_danh_muc', product.ma_danh_muc || '');
            fillSelect(selectBrand, brands, 'ma_thuong_hieu', 'ten_thuong_hieu', product.ma_thuong_hieu || '');
            fillSelect(selectSupplier, suppliers, 'ma_nha_cung_cap', 'ten_nha_cung_cap', product.ma_nha_cung_cap || '');
        } catch (error) {
            console.error('Lỗi tải dữ liệu sửa sản phẩm:', error);
            alert('Không thể tải dữ liệu từ API. Vui lòng thử lại.');
        }
    }

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
        const productId = encodeURIComponent(payload.ma_san_pham || '');
        const endpoint = BASE_URL + 'Api/Products/' + productId;

        console.log("Đang bắn PUT lên API:", endpoint, payload);

        fetch(endpoint, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(" Cập nhật thành công bằng CÒN REST API!");
                window.location.href = BASE_URL + 'Sanpham/danhsach';
            } else {
                alert(" Lỗi: " + data.message);
            }
        })
        .catch(error => {
            console.error(error);
            alert("Lỗi sập mạng khi gọi API PUT!");
        });
    });

    document.addEventListener('DOMContentLoaded', loadEditProductData);
    </script>
</body>

</html>