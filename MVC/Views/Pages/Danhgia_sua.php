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
        <form id="updateReviewForm" method="post" action="<?php echo BASE_URL; ?>Danhgia/update">
            <div>
                <label>Mã đánh giá</label>
                <input type="text" name="txtMadanhgia" readonly
                    value="<?php echo isset($data['ma_danh_gia']) ? htmlspecialchars($data['ma_danh_gia']) : '' ?>" />
            </div>
            <div>
                <label>Khách hàng</label>
                <input type="text" name="txtTenkhachhang" readonly
                    value="<?php echo isset($data['full_name']) ? htmlspecialchars($data['full_name']) : '' ?>" />
            </div>
            <div>
                <label>Tên sản phẩm</label>
                <input type="text" name="txtTensanpham" readonly
                    value="<?php echo isset($data['ten_san_pham']) ? htmlspecialchars($data['ten_san_pham']) : '' ?>" />
            </div>

            <div>
                <label>Số sao </label>
                <input type="hidden" name="txtSosao"
                    value="<?php echo isset($data['so_sao']) ? htmlspecialchars($data['so_sao']) : '' ?>" />
                <input type="text" readonly
                    value="<?php echo isset($data['so_sao']) ? htmlspecialchars($data['so_sao']) . ' sao' : '' ?>" />
            </div>

            <div>
                <label>Nội dung đánh giá</label>
                <textarea
                    readonly><?php echo isset($data['noi_dung']) ? htmlspecialchars($data['noi_dung']) : '' ?></textarea>
                <input type="hidden" name="txtNoidung"
                    value="<?php echo isset($data['noi_dung']) ? htmlspecialchars($data['noi_dung']) : '' ?>" />
            </div>

            <div>
                <label>Phản hồi <span style="color:red">*</span></label>
                <textarea
                    name="txtPhanhoi"><?php echo isset($data['phan_hoi']) ? htmlspecialchars($data['phan_hoi']) : '' ?></textarea>
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