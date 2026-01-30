<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Upload file — Thực đơn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary: #6b7280;
            --secondary-hover: #4b5563;
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
            background: #eef2f7;
            color: #0f1724
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px
        }

        .card {
            width: 680px;
            max-width: 100%;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        h2 {
            margin: 0 0 5px;
            font-size: 24px;
        }

        p.hint {
            margin: 0 0 16px;
            color: var(--secondary);
            font-size: 14px
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d0d7e2;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #f9fbfe;
            box-sizing: border-box;
        }

        .file-upload-wrapper {
            border: 2px dashed #d0d7e2;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s, border-color 0.3s;
            position: relative;
            margin-bottom: 20px;
        }

        .file-upload-wrapper:hover {
            background: #f0f4f9;
            border-color: var(--primary);
        }

        .file-upload-icon {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        /* Ẩn input file gốc */
        .file-upload-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .btn {
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: var(--secondary-hover);
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .info-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dashed #e5e7eb;
        }

        .info-section h4 {
            margin-bottom: 10px;
            font-size: 15px;
            color: #333;
        }

        .info-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 13px;
            color: #555;
        }

        .info-section li {
            margin-bottom: 8px;
            display: flex;
            align-items: flex-start;
            gap: 5px;
        }

        .info-section li .fa-caret-right {
            color: var(--primary);
            margin-top: 3px;
        }

        .table-container {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 15px;
            border: 1px solid #e3e7ef;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #e3e7ef;
        }

        thead th {
            background: #f8fafc;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        tbody tr:hover {
            background: #f8fafc;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2><i class="fa-solid fa-utensils"></i> Tải lên file Thực đơn</h2>
                <p class="hint">Sử dụng form dưới đây để tải lên file Thực đơn.</p>
            </div>

            <form method="POST" action="http://localhost/QLSP/Thucdon/up_l" enctype="multipart/form-data">
                <label for="txtGhichu">Ghi chú (Tùy chọn)</label>
                <input type="text" name="txtGhichu" id="txtGhichu" placeholder="Nhập ghi chú hoặc mô tả cho file này">

                <label>Chọn file (*Bắt buộc)</label>
                <div class="file-upload-wrapper" id="file-wrapper">
                    <div class="file-upload-icon">
                        <i class="fa-regular fa-file-excel"></i>
                    </div>
                    <h4 style="margin-bottom: 5px; font-size: 16px;">Nhấn vào đây để chọn file</h4>
                    <p class="hint">Hoặc kéo thả file vào khu vực này (.xls, .xlsx)</p>

                    <input type="file" id="txtfile" name="txtfile" accept=".xls,.xlsx" required
                        onchange="updateFileName(this)" />
                </div>

                <div id="fileNameDisplay"
                    style="margin-top: -10px; margin-bottom: 20px; text-align: center; font-weight: 600; color: var(--primary); display: none;">
                    <i class="fa-solid fa-check"></i> Đã chọn: <span id="fName"></span>
                </div>

                <div class="form-actions">
                    <a href="http://localhost/QLSP/Thucdon/danhsach" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" name="btnNhap" class="btn btn-primary" style="min-width: 150px;">
                        <i class="fa-solid fa-upload"></i> Tải lên ngay
                    </button>
                </div>
            </form>

            <div class="info-section">
                <h4><i class="fa-solid fa-circle-info"></i> Quy định định dạng file (Ví dụ: File Excel):</h4>
                <ul>
                    <li><i class="fa-solid fa-caret-right"></i> File phải có định dạng <strong>.xls</strong> hoặc
                        <strong>.xlsx</strong>.
                    </li>
                    <li><i class="fa-solid fa-caret-right"></i> Dữ liệu bắt đầu từ dòng thứ 2 (dòng 1 là tiêu đề).</li>
                    <li><i class="fa-solid fa-caret-right"></i> Cấu trúc cột cần tuân thủ thứ tự quy định của hệ thống.
                    </li>
                </ul>


            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const display = document.getElementById('fileNameDisplay');
            const nameSpan = document.getElementById('fName');
            const wrapper = document.getElementById('file-wrapper'); // Lấy wrapper để thay đổi kiểu dáng

            if (input.files && input.files.length > 0) {
                nameSpan.textContent = input.files[0].name;
                display.style.display = 'block';
                wrapper.style.borderColor = 'var(--primary)'; // Đổi màu border khi đã chọn file
            } else {
                display.style.display = 'none';
                wrapper.style.borderColor = '#d0d7e2'; // Trả về màu border mặc định
            }
        }
    </script>
</body>

</html>