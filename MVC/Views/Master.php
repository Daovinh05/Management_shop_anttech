<?php
include_once __DIR__ . '/../../Public/Classes/TimezoneHelper.php';
include_once __DIR__ . '/../../Public/Classes/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Bán Hàng Điện Thoại</title>
    <base href="<?php echo UrlHelper::baseUrl(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo UrlHelper::url('Public/Css/style.css?v=3'); ?>">
    <style>

    </style>
</head>

<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="brand">
                📱 Phone Store
            </div>
            <?php $current = isset($data['page']) ? $data['page'] : ''; ?>
            <nav class="menu_left1">
                <ul>
                    <li>
                        <a href="http://localhost/Banhang/Home"
                            class="<?php echo (strpos($current, 'home') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-chart-pie"></i> Tổng quan
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/Users/danhsach"
                            class="<?php echo (strpos($current, 'danhsachusers_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-users"></i> Quản lý người dùng
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/Danhmuc/danhsach"
                            class="<?php echo (strpos($current, 'danhsachdanhmuc_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-list"></i> Quản lý danh mục
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/Thuonghieu/danhsach"
                            class="<?php echo (strpos($current, 'danhsachthuonghieu_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-copyright"></i> Quản lý thương hiệu
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/Nhacungcap/danhsach"
                            class="<?php echo (strpos($current, 'danhsachnhacungcap_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-truck"></i> Quản lý nhà cung cấp
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/Sanpham/danhsach"
                            class="<?php echo (strpos($current, 'danhsachsanpham_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-mobile-alt"></i> Quản lý sản phẩm
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/BienThe/danhsach"
                            class="<?php echo (strpos($current, 'danhsachbienthe_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-sliders-h"></i> Quản lý biến thể
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/Khuyenmai/danhsach"
                            class="<?php echo (strpos($current, 'danhsachkhuyenmai_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-percentage"></i> Quản lý khuyến mãi
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/Donhang/danhsach"
                            class="<?php echo (strpos($current, 'danhsachdonhang_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-shopping-cart"></i> Quản lý đơn hàng
                        </a>
                    </li>
                    <li>
                        <a href="http://localhost/Banhang/Login/logout" class="logout-btn1" title="Đăng xuất">
                            <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="page-title">
                    <?php
                    if (strpos($current, 'home') !== false) echo 'Dashboard';
                    elseif (strpos($current, 'Sanpham') !== false) echo 'Sản phẩm';
                    elseif (strpos($current, 'Danhmuc') !== false) echo 'Danh mục';
                    elseif (strpos($current, 'Thuonghieu') !== false) echo 'Thương hiệu';
                    elseif (strpos($current, 'Nhacungcap') !== false) echo 'Nhà cung cấp';
                    elseif (strpos($current, 'BienThe') !== false) echo 'Biến thể sản phẩm';
                    elseif (strpos($current, 'Khuyenmai') !== false) echo 'Khuyến mãi';
                    elseif (strpos($current, 'Donhang') !== false) echo 'Đơn hàng';
                    elseif (strpos($current, 'Users') !== false) echo 'Người dùng';
                    elseif (strpos($current, 'login') !== false) echo 'Đăng nhập';
                    elseif (strpos($current, 'register') !== false) echo 'Đăng ký';
                    else echo 'Quản trị hệ thống';
                    ?>
                </div>
                <div class="user-info">
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <span>
                            Xin chào: <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            <?php if (isset($_SESSION['user_role'])): ?>
                                <span
                                    class="user-role">(<?php echo $_SESSION['user_role'] == 'admin' ? 'Quản trị viên' : 'Khách hàng'; ?>)</span>
                            <?php endif; ?>
                        </span>
                        <div class="avatar">
                            <img src="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/Banhang/Public/Images/avatar.png'; ?>"
                                alt="Avatar">
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="content-area">
                <?php
                if (isset($data['page'])) {
                    $pagePath = __DIR__ . '/Pages/' . $data['page'] . ".php";
                    if (file_exists($pagePath)) {
                        include_once $pagePath;
                    } else {
                        echo "<div class='alert alert-danger'>Trang không tồn tại!</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for additional functionality if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Add any JavaScript functionality here
        });
    </script>
</body>

</html>