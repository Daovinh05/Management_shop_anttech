<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Cafe Manager</title>

    <!-- FONT + ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: url('https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') no-repeat center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 900px;
            background: white;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            display: flex;
            overflow: hidden;
        }

        /* LEFT - LOGIN */
        .left {
            width: 50%;
            padding: 40px;
            background: rgba(255, 255, 255, 0.3);
        }

        .logo {
            font-size: 40px;
            text-align: center;
            margin-bottom: 10px;
        }

        .left h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .left p {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 2px solid #ddd;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #6f4e37;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #6f4e37;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #5a3e2b;
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .error {
            background: #fdecea;
            color: #b71c1c;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 10px;
        }

        .loading i {
            color: #6f4e37;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* RIGHT - INFO */
        .right {
            width: 50%;
            background: rgba(111, 78, 55, 0.7);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .right i {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .right h2 {
            margin-bottom: 10px;
        }

        .right p {
            margin-bottom: 20px;
        }

        .right ul {
            list-style: none;
            text-align: center;
            margin-top: 40px;
            padding-left: 0;
        }

        .right ul li {
            margin-bottom: 20px;
            font-size: 15px;
            text-align: center;
        }

        .right ul li::before {
            content: " - ";
            color: #c8e6c9;
            font-weight: bold;
        }

        .right ul li::after {
            content: " - ";
            color: #c8e6c9;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- LEFT -->
        <div class="left">
            <div class="logo">☕</div>
            <h2>Đăng nhập hệ thống</h2>
            <p>Chào mừng bạn đến với thế giới di động</p>

            <div id="message"></div>

            <form id="loginForm" onsubmit="handleLogin(event); return false;">
                <div class="form-group">
                    <label>Tài khoản</label>
                    <input type="text" id="username" name="username" placeholder="Nhập tài khoản" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn" id="btnSubmit">
                    <span id="btnText">Đăng nhập</span>
                    <span id="btnLoading" class="loading" style="display: none;">
                        <i class="fa fa-spinner"></i> Đang xử lý...
                    </span>
                </button>
            </form>

            <div style="text-align: center; margin-top: 15px;">
                <p>Bạn chưa có tài khoản?
                    <a href="<?php echo BASE_URL; ?>Login/register"
                        style="color: #6f4e37; text-decoration: none; font-weight: bold;">Đăng ký ngay</a>
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="right">
            <i class="fa-solid fa-mug-hot"></i>
            <h2>Hệ thống bán điện thoại</h2>
            <p>Nền tảng mua sắm & quản lý điện thoại hiện đại – Nhanh chóng, tiện lợi, minh bạch</p>

            <ul>
                <li><strong>Dành cho khách hàng:</strong> Xem sản phẩm, so sánh giá, đặt mua điện thoại dễ dàng</li>
                <li><strong>Giỏ hàng & thanh toán:</strong> Đặt hàng trực tuyến, theo dõi trạng thái đơn hàng</li>
                <li><strong>Dành cho admin:</strong> Quản lý sản phẩm, danh mục, đơn hàng và khách hàng</li>
                <li><strong>Báo cáo & thống kê:</strong> Theo dõi doanh thu, tồn kho và hiệu quả bán hàng</li>
            </ul>

        </div>

    </div>

    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
        const API_URL = BASE_URL + 'index.php?url=api';

        async function handleLogin(event) {
            event.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const messageDiv = document.getElementById('message');
            const btnSubmit = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');

            // Clear previous messages
            messageDiv.innerHTML = '';
            
            // Show loading
            btnSubmit.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';

            try {
                const response = await fetch(API_URL + '/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Lưu token
                    localStorage.setItem('authToken', data.data.token);
                    localStorage.setItem('userInfo', JSON.stringify(data.data.user));
                    
                    // Hiển thị thành công
                    messageDiv.innerHTML = `
                        <div class="success">
                            <i class="fa fa-check-circle"></i> Đăng nhập thành công! Đang chuyển hướng...
                        </div>
                    `;
                    
                    // Redirect dựa trên role
                    const role = data.data.role || data.data.user.user_role;
                    let redirectUrl;
                    
                    if (role === 'admin') {
                        redirectUrl = BASE_URL + 'Quanly';
                    } else if (role === 'nhan_vien') {
                        redirectUrl = BASE_URL + 'Staff';
                    } else {
                        redirectUrl = BASE_URL + 'Khachhang';
                    }
                    
                    setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 1000);
                } else {
                    // Hiển thị lỗi
                    messageDiv.innerHTML = `
                        <div class="error">
                            <i class="fa fa-exclamation-circle"></i> ${data.message || 'Đăng nhập thất bại!'}
                        </div>
                    `;
                    
                    // Reset button
                    btnSubmit.disabled = false;
                    btnText.style.display = 'inline';
                    btnLoading.style.display = 'none';
                }
            } catch (error) {
                console.error('Login error:', error);
                messageDiv.innerHTML = `
                    <div class="error">
                        <i class="fa fa-exclamation-triangle"></i> Lỗi kết nối: ${error.message}
                    </div>
                `;
                
                // Reset button
                btnSubmit.disabled = false;
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
            }
        }

        // Tự động hiển thị lỗi từ session nếu có (từ PHP cũ)
        <?php if (isset($_SESSION['error'])): ?>
            document.getElementById('message').innerHTML = `
                <div class="error">
                    <i class="fa fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
                </div>
            `;
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

</body>

</html>