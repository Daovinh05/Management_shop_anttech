<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Cafe Manager</title>

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
            /* More transparent white for better readability */
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            display: flex;
            overflow: hidden;
        }

        /* LEFT - REGISTER */
        .left {
            width: 50%;
            padding: 40px;
            background: rgba(255, 255, 255, 0.3);
            /* More transparent white */
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

        /* RIGHT - INFO */
        .right {
            width: 50%;
            background: rgba(111, 78, 55, 0.7);
            /* More transparent coffee brown */
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
            /* content: "✔ "; */
            content: " - ";

            color: #c8e6c9;
            font-weight: bold;
        }

        .right ul li::after {
            /* content: "✔ "; */
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
            <h2>Đăng ký tài khoản</h2>
            <p>Quản lý quán cà phê</p>

            <form method="post" action="http://localhost/QLSP/Login/process_register">
                <div class="form-group">
                    <label>Tên tài khoản</label>
                    <!-- <input type="text" name="username" placeholder="Nhập tên đăng nhập" required> -->

                    <input type="text" name="username" placeholder="Nhập tên tài khoản" required
                        value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <!-- <input type="email" name="email" placeholder="Nhập email" required> -->

                    <input type="email" name="email" placeholder="Nhập email" required
                        value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Họ và tên</label>
                    <!-- <input type="email" name="email" placeholder="Nhập email" required> -->

                    <input type="text" name="fullname" placeholder="Nhập họ và tên" required
                        value="<?php echo isset($_SESSION['form_data']['full_name']) ? htmlspecialchars($_SESSION['form_data']['full_name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <!-- <input type="email" name="email" placeholder="Nhập email" required> -->

                    <input type="text" name="phone" placeholder="Nhập số điện thoại" required
                        value="<?php echo isset($_SESSION['form_data']['phone']) ? htmlspecialchars($_SESSION['form_data']['phone']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <!-- <input type="password" name="password" placeholder="Nhập mật khẩu" required> -->
                    <input type="password" name="password" placeholder="Nhập mật khẩu" required
                        value="<?php echo isset($_SESSION['form_data']['password']) ? htmlspecialchars($_SESSION['form_data']['password']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Nhập lại mật khẩu</label>
                    <!-- <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required> -->

                    <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required
                        value="<?php echo isset($_SESSION['form_data']['confirm_password']) ? htmlspecialchars($_SESSION['form_data']['confirm_password']) : ''; ?>">
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error">
                        <?= $_SESSION['error'] ?>
                    </div>
                <?php unset($_SESSION['error']);
                endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success">
                        <?= $_SESSION['success'] ?>
                    </div>
                <?php unset($_SESSION['success']);
                endif; ?>

                <button class="btn">Đăng ký</button>
            </form>

            <div style="text-align: center; margin-top: 15px;">
                <p>Đã có tài khoản? <a href="./Login"
                        style="color: #6f4e37; text-decoration: none; font-weight: bold;">Đăng nhập ngay</a></p>
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

</body>

</html>