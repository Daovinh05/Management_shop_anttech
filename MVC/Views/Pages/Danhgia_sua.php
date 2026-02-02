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
        <form method="post" action="http://localhost/Banhang/Danhgia/update">
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
                <a href="http://localhost/Banhang/Danhgia/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <button type="submit" name="btnCapnhat" class="btn-primary">Cập nhật</button>
            </div>
        </form>
    </main>
</body>

</html>