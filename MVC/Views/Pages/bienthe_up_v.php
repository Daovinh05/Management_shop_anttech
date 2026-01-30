<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập khẩu Biến thể</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="file"] {
            width: 100%;
            padding: 8px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Nhập khẩu Biến thể từ Excel</h1>
        
        <form method="post" action="http://localhost/Banhang/BienThe/up_l" enctype="multipart/form-data">
            <div class="form-group">
                <label>Chọn file Excel:</label>
                <div class="file-input-wrapper">
                    <span>Chọn file Excel...</span>
                    <input type="file" name="txtfile" accept=".xlsx,.xls" required />
                </div>
                <div class="file-name" id="fileName">Chưa chọn file</div>
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Upload</button>
                <a href="http://localhost/Banhang/BienThe/danhsach" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
        
        <div style="margin-top: 30px;">
            <h3>Hướng dẫn:</h3>
            <p>- File Excel phải có các cột theo thứ tự: Mã biến thể, Mã sản phẩm, Tên biến thể, Màu sắc, Ram, Dung lượng, Giá, Số lượng kho</p>
            <p>- Dữ liệu bắt đầu từ dòng thứ 2 (dòng đầu tiên là tiêu đề cột)</p>
        </div>
    </div>

    <script>
        document.querySelector('input[type="file"]').addEventListener('change', function(e) {
            const fileNameDisplay = document.getElementById('fileName');
            if (e.target.files.length > 0) {
                fileNameDisplay.textContent = 'Đã chọn: ' + e.target.files[0].name;
            } else {
                fileNameDisplay.textContent = 'Chưa chọn file';
            }
        });
    </script>
</body>

</html>