<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Upload file biến thể</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
            background: #eef2f7;
            color: #0f1724
        }

        .wrap {
            min-height: 50vh;
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

        .file-upload-wrapper {
            border: 2px dashed #d0d7e2;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            position: relative;
            margin-bottom: 20px;
        }

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
            color: #4b5563;
            border: 1px solid #d1d5db;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .summary-box {
            margin-top: 16px;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            display: none;
            white-space: pre-wrap;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2><i class="fa-solid fa-cloud-arrow-up"></i> Tải lên file Biến thể</h2>
                <p style="color:#6b7280">Sử dụng form dưới đây để tải lên file biến thể qua REST API.</p>
            </div>

            <form id="importVariantForm" method="POST" action="<?php echo BASE_URL; ?>BienThe/up_l" enctype="multipart/form-data">
                <label>Chọn file (*Bắt buộc)</label>
                <div class="file-upload-wrapper" id="file-wrapper">
                    <h4 style="margin-bottom: 5px; font-size: 16px;">Nhấn vào đây để chọn file</h4>
                    <p style="color:#6b7280">.xls, .xlsx</p>
                    <input type="file" id="txtfile" name="txtfile" accept=".xls,.xlsx" required onchange="updateFileName(this)" />
                </div>

                <div id="fileNameDisplay" style="margin-top:-10px;margin-bottom:20px;text-align:center;font-weight:600;color:var(--primary);display:none;">
                    <i class="fa-solid fa-check"></i> Đã chọn: <span id="fName"></span>
                </div>

                <div class="form-actions">
                    <a href="<?php echo BASE_URL; ?>BienThe/danhsach" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" name="btnUpload" class="btn btn-primary">
                        <i class="fa-solid fa-upload"></i> Tải lên ngay
                    </button>
                </div>
            </form>

            <div class="summary-box" id="importSummary"></div>
        </div>
    </div>

    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';

        function updateFileName(input) {
            const display = document.getElementById('fileNameDisplay');
            const nameSpan = document.getElementById('fName');

            if (input.files && input.files.length > 0) {
                nameSpan.textContent = input.files[0].name;
                display.style.display = 'block';
            } else {
                display.style.display = 'none';
            }
        }

        function renderSummary(payload) {
            const box = document.getElementById('importSummary');
            if (!box) {
                return;
            }

            const lines = [];
            lines.push((payload && payload.message) ? payload.message : 'Hoàn tất import');
            lines.push('Tạo mới: ' + (payload.created || 0));
            lines.push('Bỏ qua mã rỗng: ' + (payload.skipped_empty_code || 0));
            lines.push('Trùng mã: ' + (payload.duplicated_count || 0));
            lines.push('Lỗi: ' + (payload.failed_count || 0));

            if (Array.isArray(payload.duplicated_codes) && payload.duplicated_codes.length > 0) {
                lines.push('Mã trùng: ' + payload.duplicated_codes.join(', '));
            }

            if (Array.isArray(payload.failed_rows) && payload.failed_rows.length > 0) {
                lines.push('Chi tiết lỗi:');
                payload.failed_rows.forEach(function(item) {
                    lines.push('- Dòng ' + item.row + ' (' + (item.ma_bien_the || 'N/A') + '): ' + (item.reason || 'Lỗi không xác định'));
                });
            }

            box.textContent = lines.join('\n');
            box.style.display = 'block';
            box.style.borderColor = payload.success ? '#16a34a' : '#dc2626';
            box.style.background = payload.success ? '#f0fdf4' : '#fef2f2';
        }

        document.getElementById('importVariantForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch(BASE_URL + 'Api/Bienthe/import', {
                    method: 'POST',
                    body: formData
                })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    return {
                        status: response.status,
                        data
                    };
                })
                .then((result) => {
                    renderSummary(result.data || {});
                    if (result.status >= 200 && result.status < 300 && result.data.success) {
                        alert('Import biến thể thành công qua REST API');
                    } else {
                        alert('Import biến thể có lỗi: ' + ((result.data && result.data.message) ? result.data.message : 'Lỗi không xác định'));
                    }
                })
                .catch((error) => {
                    alert('Không thể kết nối API import biến thể: ' + error.message);
                });
        });
    </script>
</body>

</html>
