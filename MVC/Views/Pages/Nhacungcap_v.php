<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
        /* Custom styles for the actions */
        .btn-back {
            background: #6b7280;
            padding: 8px 15px;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Các style cơ bản khác giữ nguyên */
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --accent: #2463ff;
            --muted: #6b7280;
            --radius: 12px;
            --gap: 16px;
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }

        * {
            box-sizing: border-box
        }

        .card {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: 0 8px 30px rgba(24, 99, 255, 0.08);
            padding: 28px;
        }

        h2 {
            margin: 0;
            font-size: 20px
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--gap);
        }

        .full {
            grid-column: 1 / -1
        }

        label {
            display: block;
            font-size: 15px;
            color: #253243;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e3e7ef;
            border-radius: 10px;
            background: #fbfdff;
            font-size: 14px;
            outline: none;
        }

        input:focus,
        textarea:focus {
            box-shadow: 0 0 0 4px rgba(36, 99, 255, 0.08);
            border-color: var(--accent);
        }

        textarea {
            min-height: 90px;
            resize: vertical
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            grid-column: 1 / -1;
            margin-top: 6px
        }

        .actions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 16px;
            border-radius: 10px;
            border: 0;
            font-size: 14px;
            cursor: pointer
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            transition: 0.2s;
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid #e6e9f2;
            color: var(--muted)
        }
    </style>

    <main class="card" role="main">
        <div class="actions-header">
            <h2><i class="fa-solid fa-square-plus"></i> Thêm mới Nhà cung cấp</h2>
        </div>

        <form method="post" id="nccForm" action="http://localhost/QLSP/Nhacungcap/ins" class="form-grid">
            <div>
                <label for="nccId">Mã nhà cung cấp *</label>
                <input type="text" id="nccId" name="txtMancc" placeholder="VD: NCC001" required
                    value="<?php echo isset($data['mancc']) ? $data['mancc'] : '' ?>" />
            </div>

            <div>
                <label for="nccName">Tên nhà cung cấp *</label>
                <input type="text" id="nccName" name="txtTenncc" placeholder="Nhập tên nhà cung cấp" required
                    value="<?php echo isset($data['tenncc']) ? $data['tenncc'] : '' ?>" />
            </div>

            <div>
                <label for="phone">Điện thoại(10 số)</label>
                <input type="tel" id="phone" name="txtDienthoai" placeholder="VD: 0912345678" required
                    value="<?php echo isset($data['dienthoai']) ? $data['dienthoai'] : '' ?>" maxlength="10"
                    pattern="[0-9]{10}" title="Vui lòng nhập đúng 10 chữ số điện thoại" />
            </div>

            <div class="full">
                <label for="address">Địa chỉ</label>
                <textarea id="address" name="txtDiachi" placeholder="Nhập địa chỉ nhà cung cấp"
                    required><?php echo isset($data['diachi']) ? $data['diachi'] : '' ?></textarea>
            </div>

            <div class="actions"
                style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <a href="http://localhost/QLSP/Nhacungcap/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i> Quay lại</a>
                <div style="display: flex; gap: 12px;">
                    <button type="reset" class="btn-ghost"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                    <button type="submit" class="btn-primary" name="btnLuu"><i class="fa-solid fa-save"></i> Lưu thông
                        tin</button>
                </div>
            </div>
        </form>
    </main>
</body>

</html>