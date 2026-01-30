<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Nhà cung cấp</title>
    <style>
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
            max-width: 820px;
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: 0 8px 30px rgba(24, 99, 255, 0.08);
            padding: 28px;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 20px
        }

        p.lead {
            margin: 0 0 20px;
            color: var(--muted);
            font-size: 14px
        }

        form {
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

        .btn-primary:hover {
            background: #174fe0;
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid #e6e9f2;
            color: var(--muted)
        }

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

        @media (max-width:720px) {
            form {
                grid-template-columns: 1fr
            }

            .actions {
                justify-content: stretch
            }

            .actions button {
                width: 100%
            }
        }
    </style>
</head>

<body>
    <main class="card" role="main">
        <h1>Sửa thông tin Nhà cung cấp</h1>
        <p class="lead">Cập nhật thông tin nhà cung cấp.</p>

        <form method="post" action="http://localhost/QLSP/Nhacungcap/update">
            <div>
                <label for="nccId">Mã nhà cung cấp *</label>
                <input type="text" id="nccId" name="txtMancc" readonly value="<?php echo $data['mancc'] ?>" />
            </div>

            <div>
                <label for="nccName">Tên nhà cung cấp *</label>
                <input type="text" id="nccName" name="txtTenncc" required value="<?php echo $data['tenncc'] ?>" />
            </div>

            <div>
                <label for="phone">Điện thoại</label>
                <input type="tel" id="phone" name="txtDienthoai" value="<?php echo $data['dienthoai'] ?>"
                    maxlength="10" pattern="[0-9]{10}"
                    title="Vui lòng nhập đúng 10 chữ số điện thoại" />
            </div>

            <div class="full">
                <label for="address">Địa chỉ</label>
                <textarea id="address" name="txtDiachi"><?php echo $data['diachi'] ?></textarea>
            </div>

            <div class="actions">
                <a href="http://localhost/QLSP/Nhacungcap/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i> Quay lại</a>
                <button type="submit" class="btn-primary" name="btnCapnhat">Cập nhật</button>
            </div>
        </form>

    </main>
</body>

</html>