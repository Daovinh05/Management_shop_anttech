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
        <h1>Sửa Thương hiệu</h1>
        <p class="lead">Chỉnh sửa thông tin thương hiệu.</p>
        <form id="updateBrandForm">
            <div>
                <label>Mã thương hiệu <span style="color:red">*</span></label>
                <input type="text" id="txtMathuonghieu" name="txtMathuonghieu" required readonly />
            </div>
            <div>
                <label>Tên thương hiệu <span style="color:red">*</span></label>
                <input type="text" id="txtTenthuonghieu" name="txtTenthuonghieu" required />
            </div>
            <!-- Khu vực hình ảnh đang tạm ẩn cho module thương hiệu -->

            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Thuonghieu/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="submit" id="updateBrandBtn" name="btnCapnhat" class="btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';

        function resolveBrandIdFromUrl() {
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

        function loadBrandByApi() {
            const brandId = resolveBrandIdFromUrl();
            if (!brandId) {
                alert('Không xác định được mã thương hiệu từ URL.');
                return;
            }

            fetch(BASE_URL + 'Api/Thuonghieu/' + encodeURIComponent(brandId), {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success && data.data) {
                        const idInput = document.getElementById('txtMathuonghieu');
                        const nameInput = document.getElementById('txtTenthuonghieu');

                        if (idInput) {
                            idInput.value = data.data.ma_thuong_hieu || '';
                        }

                        if (nameInput) {
                            nameInput.value = data.data.ten_thuong_hieu || '';
                        }
                        return;
                    }

                    alert('Không thể tải thông tin thương hiệu: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                })
                .catch(error => {
                    alert('Không thể kết nối API thương hiệu: ' + error.message);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadBrandByApi();

            const form = document.getElementById('updateBrandForm');
            const submitBtn = document.getElementById('updateBrandBtn');

            if (!form || !submitBtn) {
                return;
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const maThuongHieu = (form.querySelector('input[name="txtMathuonghieu"]') || {}).value || '';
                const tenThuongHieu = (form.querySelector('input[name="txtTenthuonghieu"]') || {}).value || '';

                if (!maThuongHieu.trim() || !tenThuongHieu.trim()) {
                    alert('Vui lòng nhập đầy đủ mã và tên thương hiệu.');
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'Đang cập nhật...';

                fetch(BASE_URL + 'Api/Thuonghieu/' + encodeURIComponent(maThuongHieu.trim()), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            ten_thuong_hieu: tenThuongHieu.trim()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.success) {
                            alert('Cập nhật thương hiệu thành công qua REST API');
                            window.location.href = BASE_URL + 'Thuonghieu/danhsach';
                        } else {
                            alert('Cập nhật thất bại: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                        }
                    })
                    .catch(error => {
                        alert('Không thể kết nối API cập nhật thương hiệu: ' + error.message);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Cập nhật';
                    });
            });
        });
    </script>
</body>

</html>