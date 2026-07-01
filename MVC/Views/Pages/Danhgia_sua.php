<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
        :root {
            --accent: #2463ff;
            --muted: #6b7280
        }

        .card {
            width: 100%;
            /* max-width: 1220px; */
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
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #e3e7ef
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 6px
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
            background: var(--accent);
            color: #fff;
            padding: 10px 16px;
            border-radius: 10px;
            border: 0
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid #e6e9f2;
            color: var(--muted);
            padding: 10px 16px;
            border-radius: 10px
        }
    </style>

    <main class="card">
        <h1>Sửa thông tin Đánh giá</h1>
        <form id="updateReviewForm">
            <div>
                <label>Mã đánh giá</label>
                <input type="text" id="txtMadanhgia" name="txtMadanhgia" readonly />
            </div>
            <div>
                <label>Khách hàng</label>
                <input type="text" id="txtTenkhachhang" name="txtTenkhachhang" readonly />
            </div>
            <div>
                <label>Tên sản phẩm</label>
                <input type="text" id="txtTensanpham" name="txtTensanpham" readonly />
            </div>

            <div>
                <label>Số sao </label>
                <input type="hidden" id="txtSosao" name="txtSosao" />
                <input type="text" id="txtSosaoDisplay" readonly />
            </div>

            <div>
                <label>Nội dung đánh giá</label>
                <textarea id="txtNoidungDisplay" readonly></textarea>
                <input type="hidden" id="txtNoidung" name="txtNoidung" />
            </div>

            <div>
                <label>Phản hồi <span style="color:red">*</span></label>
                <textarea id="txtPhanhoi" name="txtPhanhoi"></textarea>
            </div>

            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Danhgia/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <button type="submit" name="btnCapnhat" class="btn-primary">Cập nhật</button>
            </div>
        </form>
    </main>

    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';

        function resolveReviewIdFromUrl() {
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

        function loadReviewByApi() {
            const reviewId = resolveReviewIdFromUrl();
            if (!reviewId) {
                alert('Không xác định được mã đánh giá từ URL.');
                return;
            }

            fetch(BASE_URL + 'Api/Danhgia/' + encodeURIComponent(reviewId), {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success && data.data) {
                        const maInput = document.getElementById('txtMadanhgia');
                        const tenKhInput = document.getElementById('txtTenkhachhang');
                        const tenSpInput = document.getElementById('txtTensanpham');
                        const soSaoInput = document.getElementById('txtSosao');
                        const soSaoDisplay = document.getElementById('txtSosaoDisplay');
                        const noiDungInput = document.getElementById('txtNoidung');
                        const noiDungDisplay = document.getElementById('txtNoidungDisplay');
                        const phanHoiInput = document.getElementById('txtPhanhoi');

                        if (maInput) maInput.value = data.data.ma_danh_gia || '';
                        if (tenKhInput) tenKhInput.value = data.data.full_name || '';
                        if (tenSpInput) tenSpInput.value = data.data.ten_san_pham || '';

                        const soSao = data.data.so_sao || '';
                        if (soSaoInput) soSaoInput.value = soSao;
                        if (soSaoDisplay) soSaoDisplay.value = soSao ? (soSao + ' sao') : '';

                        const noiDung = data.data.noi_dung || '';
                        if (noiDungInput) noiDungInput.value = noiDung;
                        if (noiDungDisplay) noiDungDisplay.value = noiDung;

                        if (phanHoiInput) phanHoiInput.value = data.data.phan_hoi || '';
                        return;
                    }

                    alert('Không thể tải thông tin đánh giá: ' + ((data && data.message) ? data.message : 'Lỗi không xác định'));
                })
                .catch(error => {
                    alert('Không thể kết nối API đánh giá: ' + error.message);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadReviewByApi();
        });

        document.getElementById('updateReviewForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = this;
            const maDanhGia = (form.querySelector('input[name="txtMadanhgia"]') || {}).value || '';

            if (!maDanhGia.trim()) {
                alert('Thiếu mã đánh giá để cập nhật');
                return;
            }

            const payload = {
                ma_danh_gia: maDanhGia.trim(),
                so_sao: (form.querySelector('input[name="txtSosao"]') || {}).value || '',
                noi_dung: (form.querySelector('input[name="txtNoidung"]') || {}).value || '',
                phan_hoi: (form.querySelector('textarea[name="txtPhanhoi"]') || {}).value || ''
            };

            fetch(BASE_URL + 'Api/Danhgia/' + encodeURIComponent(maDanhGia.trim()), {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    return {
                        status: response.status,
                        data
                    };
                })
                .then((result) => {
                    if (result.status >= 200 && result.status < 300 && result.data.success) {
                        alert('Cập nhật đánh giá thành công qua REST API');
                        window.location.href = BASE_URL + 'Danhgia/danhsach';
                        return;
                    }

                    alert('Cập nhật đánh giá thất bại: ' + (result.data.message || 'Lỗi không xác định'));
                })
                .catch((error) => {
                    alert('Không thể kết nối API cập nhật đánh giá: ' + error.message);
                });
        });
    </script>
</body>

</html>