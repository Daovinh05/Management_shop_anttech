<?php
// Include necessary helpers
include_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Classes/TimezoneHelper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Classes/UrlHelper.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - TechZone</title>
    <base href="<?php echo UrlHelper::baseUrl(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        /* --- 1. CORE VARIABLES & RESET --- */
        :root {
            --primary-green: #00483d;
            --secondary-green: #006a5b;
            --tet-red: #d70018;
            --tet-yellow: #fce700;
            --text-gray: #555;
            --text-dark: #333;
            --border-color: #e0e0e0;
            --bg-light: #f4f4f4;
            --blue-btn: #2d72d2;
            --fb-blue: #365899;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Roboto', sans-serif; }
        body { background-color: #f9f9f9; color: var(--text-dark); position: relative; }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 10px; }

        /* Header & Banner */
        .top-banner { background-color: var(--primary-green); color: white; font-size: 13px; padding: 8px 0; }
        .top-banner .container { display: flex; justify-content: space-between; }
        .top-banner-left { display: flex; gap: 155px; } 
        .top-banner-right { display: flex; gap: 20px; }
        .top-banner span i { margin-right: 5px; }
        
        .main-header { background: white; padding: 15px 0; border-bottom: 1px solid var(--border-color); position: sticky; top: 0; z-index: 900; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .main-header .container { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .logo { font-size: 28px; font-weight: 800; color: var(--primary-green); display: flex; align-items: center; white-space: nowrap; }
        
        .middle-section { flex-grow: 1; max-width: 700px; display: flex; align-items: center; gap: 10px; }
        .category-dropdown button { height: 40px; background: #f5f5f7; border: 1px solid #e0e0e0; padding: 0 15px; border-radius: 4px; cursor: pointer; font-weight: 500; display: flex; align-items: center; gap: 5px; }
        .search-box { flex-grow: 1; display: flex; border: 2px solid var(--border-color); border-radius: 4px; overflow: hidden; height: 40px; }
        .search-box input { flex-grow: 1; border: none; padding: 0 15px; outline: none; font-size: 14px; }
        .search-box button { background: white; border: none; padding: 0 20px; color: var(--text-gray); cursor: pointer; border-left: 1px solid #eee; }

        .header-actions { display: flex; align-items: center; gap: 20px; font-size: 14px; }
        .action-item { display: flex; flex-direction: column; align-items: center; color: var(--text-gray); cursor: pointer; position: relative; }
        .cart-badge { position: absolute; top: -8px; right: -8px; background-color: var(--tet-red); color: white; font-size: 10px; width: 18px; height: 18px; display: flex; justify-content: center; align-items: center; border-radius: 50%; }

        /* Menu Tài khoản */
        .account-dropdown-menu {
            position: absolute; top: 45px; right: -10px; width: 200px; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); padding: 8px 0; display: none; z-index: 1100; border: 1px solid #eee;
        }
        .account-dropdown-menu.active { display: block; animation: fadeIn 0.2s ease; }
        .account-dropdown-menu::before {
            content: ""; position: absolute; top: -6px; right: 25px; width: 12px; height: 12px; background: white; transform: rotate(45deg); border-top: 1px solid #eee; border-left: 1px solid #eee;
        }
        .account-dropdown-menu a { display: flex; align-items: center; padding: 12px 20px; font-size: 14px; color: #333; transition: all 0.2s; text-decoration: none; }
        .account-dropdown-menu a:hover { background-color: #f5f5f7; color: var(--primary-green); }
        .account-dropdown-menu a i { width: 25px; color: #888; margin-right: 5px; }
        .divider { height: 1px; background-color: #eee; margin: 5px 0; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- CSS PAGE GIỎ HÀNG CHÍNH --- */
        .cart-page-container { padding: 20px 0 50px 0; }
        .page-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 25px; text-transform: uppercase; }
        
        .cart-grid-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: start; }
        
        /* Bảng sản phẩm */
        .cart-table-wrapper { background: #fff; border-top: 1px solid #eee; }
        .cart-table { width: 100%; border-collapse: collapse; }
        .cart-table th { text-align: left; padding: 15px 10px; border-bottom: 2px solid #eee; font-size: 14px; font-weight: 700; color: #333; text-transform: uppercase; }
        .cart-table th:nth-child(2) { text-align: center; } 
        .cart-table th:nth-child(3) { text-align: center; } 
        .cart-table th:nth-child(4) { text-align: right; } 
        .cart-table td { padding: 20px 10px; border-bottom: 1px solid #eee; vertical-align: middle; }

        .col-product { display: flex; align-items: center; gap: 15px; }
        .btn-remove-item { width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ccc; background: white; color: #999; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; flex-shrink: 0; }
        .btn-remove-item:hover { border-color: var(--tet-red); color: var(--tet-red); }
        .cart-prod-img { width: 80px; height: 80px; object-fit: contain; border: 1px solid #f0f0f0; padding: 5px; }
        .cart-prod-info { display: flex; flex-direction: column; gap: 5px; }
        .cart-prod-name { font-weight: 700; color: #2d72d2; font-size: 15px; }
        .cart-prod-meta { font-size: 12px; color: #888; text-transform: uppercase; }

        .col-price { font-weight: 700; color: #333; text-align: center; }
        .col-subtotal { font-weight: 700; color: var(--tet-red); text-align: right; font-size: 16px; }

        /* Nút số lượng */
        .qty-box { display: flex; align-items: center; border: 1px solid #ddd; width: 100px; margin: 0 auto; }
        .qty-btn { width: 30px; height: 35px; background: white; border: none; cursor: pointer; font-size: 16px; color: #555; display: flex; align-items: center; justify-content: center; }
        .qty-btn:hover { background: #f5f5f5; }
        .qty-input { width: 40px; height: 35px; border: none; border-left: 1px solid #ddd; border-right: 1px solid #ddd; text-align: center; font-weight: 600; outline: none; }
        .qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

        .btn-continue-shop { display: inline-block; margin-top: 20px; padding: 10px 20px; border: 2px solid var(--tet-red); color: var(--tet-red); font-weight: 700; text-transform: uppercase; font-size: 14px; transition: 0.2s; text-decoration: none; }
        .btn-continue-shop:hover { background: var(--tet-red); color: white; }
        .btn-continue-shop i { margin-right: 5px; }

        /* Cột Tổng tiền */
        .cart-summary-box { background: white; padding-left: 20px; }
        .summary-title { font-size: 16px; font-weight: 700; text-transform: uppercase; padding-bottom: 15px; border-bottom: 1px solid #eee; margin-bottom: 20px; color: #333; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; font-weight: 700; color: #333; }
        .summary-total { color: var(--tet-red); font-size: 18px; }

        .btn-checkout-page { width: 100%; background-color: var(--tet-red); color: white; border: none; padding: 15px; font-size: 14px; font-weight: 700; text-transform: uppercase; border-radius: 30px; cursor: pointer; margin-bottom: 30px; transition: 0.3s; display: block; text-align: center; text-decoration: none; }
        .btn-checkout-page:hover { background-color: #b70014; }

        .coupon-section { border-top: 1px solid #eee; padding-top: 20px; }
        .coupon-label { font-size: 14px; font-weight: 700; color: #555; margin-bottom: 10px; display: flex; align-items: center; gap: 5px; }
        .coupon-input { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; outline: none; font-size: 13px; margin-bottom: 10px; }
        .btn-apply-coupon { width: 100%; padding: 10px; background: white; border: 1px solid #ddd; font-weight: 700; text-transform: uppercase; font-size: 13px; color: #555; cursor: pointer; transition: 0.2s; }
        .btn-apply-coupon:hover { background: #f4f4f4; color: #333; }

        /* Footer */
        .main-footer { border-top: 4px solid #f4f4f4; padding: 40px 0 20px; background: white; margin-top: 0; }
        .footer-grid { display: grid; grid-template-columns: 1.6fr 1fr 1fr; gap: 40px; }
        .footer-logo { font-size: 24px; font-weight: 800; color: #006a5b; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; text-transform: uppercase; }
        .address-list li, .footer-links li { font-size: 13px; color: #555; margin-bottom: 8px; line-height: 1.6; }
        .fanpage-box { background: white; border: 1px solid #ddd; padding: 10px; margin-top: 5px; }
        .fp-container { display: flex; align-items: flex-start; gap: 10px; }
        .fp-avatar { width: 50px; height: 50px; border: 1px solid #ddd; overflow: hidden; flex-shrink: 0; }
        .fp-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .fp-info { display: flex; flex-direction: column; padding-top: 2px; }
        .fp-name { color: var(--fb-blue); font-weight: 700; font-size: 14px; margin-bottom: 3px; }
        .fp-followers { color: #4b4f56; font-size: 12px; }
        .social-icons { display: flex; gap: 10px; margin-bottom: 15px; }
        .social-icons a { width: 35px; height: 35px; border-radius: 50%; border: 1px solid #ddd; display: flex; justify-content: center; align-items: center; color: #555; }
        .contact-info p { font-size: 13px; color: #333; margin-bottom: 5px; font-weight: 700; }
        .hotline-large { font-size: 18px; color: #333; font-weight: 700; margin-top: 10px; display: block; }
        
        @media (max-width: 900px) {
            .cart-grid-layout { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="top-banner">
        <div class="container">
            <div class="top-banner-left">
                <span><i class="fa-solid fa-circle-check"></i>SẢN PHẨM CHÍNH HÃNG</span>
                <span><i class="fa-solid fa-rotate-left"></i>CAM KẾT LỖI ĐỔI LIỀN</span>
                <span><i class="fa-solid fa-phone-volume"></i>HOTLINE 1900.2091</span>
            </div>
            <div class="top-banner-right">
                <span><i class="fa-solid fa-truck-fast"></i>MIỄN PHÍ VẬN CHUYỂN TOÀN QUỐC</span>
            </div>
        </div>
    </div>

    <header class="main-header">
        <div class="container">
            <a href="<?php echo UrlHelper::url('Khachhang'); ?>" class="logo"><i class="fa-brands fa-instalod"></i> TECHZONE</a>
            
            <div class="middle-section">
                <div class="category-dropdown"><button><i class="fa-solid fa-bars"></i> Danh mục</button></div>
                <div class="search-box">
                    <input type="text" placeholder="Hôm nay bạn muốn tìm kiếm gì?">
                    <button><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
                </div>
            </div>

            <div class="header-actions">
                <div class="action-item" id="accountBtn">
                    <i class="fa-regular fa-user"></i>
                    <span>
                        <?php
                        if (isset($_SESSION['user_name'])) {
                            echo htmlspecialchars($_SESSION['user_name']);
                        } else {
                            echo 'Tài khoản';
                        }
                        ?>
                    </span>
                    <div class="account-dropdown-menu" id="accountMenu">
                        <?php if (isset($_SESSION['user_name'])): ?>
                            <a href="<?php echo UrlHelper::url('Khachhang/taikhoan'); ?>"><i class="fa-solid fa-user-gear"></i>
                                Quản lý tài khoản</a>
                            <a href="<?php echo UrlHelper::url('Khachhang/lichsumuahang'); ?>"><i
                                    class="fa-solid fa-box-open"></i> Đơn hàng của tôi</a>
                            <div class="divider"></div>
                            <a href="<?php echo UrlHelper::url('Login/logout'); ?>" style="color: #d70018;"><i
                                    class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                        <?php else: ?>
                            <a href="<?php echo UrlHelper::url('Login'); ?>"><i class="fa-solid fa-user"></i> Đăng nhập</a>
                            <a href="<?php echo UrlHelper::url('Login/register'); ?>"><i class="fa-solid fa-user-plus"></i> Đăng
                                ký</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?php echo UrlHelper::url('Khachhang/giohang'); ?>" class="action-item cart-icon-wrap">
                    <i class="fa-solid fa-cart-shopping"></i><span>Giỏ hàng</span>
                    <span class="cart-badge" id="cartBadge">
                        <?php 
                        if (isset($data['chi_tiet_gio_hang']) && $data['chi_tiet_gio_hang']) {
                            $total_qty = 0;
                            mysqli_data_seek($data['chi_tiet_gio_hang'], 0); // Reset pointer to beginning
                            while ($item = mysqli_fetch_assoc($data['chi_tiet_gio_hang'])) {
                                $total_qty += $item['so_luong'];
                            }
                            echo $total_qty;
                        } else {
                            echo '0';
                        }
                        ?>
                    </span>
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="cart-page-container">
            <h1 class="page-title">Giỏ hàng của bạn</h1>

            <div class="cart-grid-layout">
                
                <div class="cart-content-left">
                    <?php if ($data['chi_tiet_gio_hang'] && mysqli_num_rows($data['chi_tiet_gio_hang']) > 0): ?>
                        <div class="cart-table-wrapper">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>SẢN PHẨM</th>
                                        <th>GIÁ</th>
                                        <th>SỐ LƯỢNG</th>
                                        <th>TẠM TÍNH</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody">
                                    <?php
                                    $tong_tien = 0;
                                    mysqli_data_seek($data['chi_tiet_gio_hang'], 0); // Reset pointer to beginning
                                    while ($item = mysqli_fetch_assoc($data['chi_tiet_gio_hang'])):
                                        $thanh_tien = $item['gia'] * $item['so_luong'];
                                        $tong_tien += $thanh_tien;
                                        
                                        // Determine image source
                                        $img_src = !empty($item['img_bien_the']) ? '/Banhang/Public/Pictures/bien_the/' . $item['img_bien_the'] : $this->url('Public/Images/no-image.png');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="col-product">
                                                <a href="<?php echo $this->url('Khachhang/xoakhoigio/' . $item['ma_gio_hang'] . '/' . $item['ma_bien_the']); ?>"
                                                   class="btn-remove-item"
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                                   <i class="fa-solid fa-xmark"></i>
                                                </a>
                                                <img src="<?php echo $img_src; ?>" class="cart-prod-img" alt="<?php echo $item['ten_san_pham']; ?>">
                                                <div class="cart-prod-info">
                                                    <a href="#" class="cart-prod-name"><?php echo $item['ten_san_pham']; ?></a>
                                                    <div class="cart-prod-meta">MÀU SẮC: <?php echo $item['mau_sac'] ?? 'N/A'; ?></div>
                                                    <div class="cart-prod-meta">DUNG LƯỢNG: <?php echo $item['dung_luong'] ?? 'N/A'; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-price"><?php echo number_format($item['gia'], 0, ',', '.') . ' ₫'; ?></td>
                                        <td>
                                            <form method="post" action="<?php echo $this->url('Khachhang/capnhatgiohang'); ?>" class="d-inline">
                                                <input type="hidden" name="ma_gio_hang" value="<?php echo $item['ma_gio_hang']; ?>">
                                                <input type="hidden" name="ma_bien_the" value="<?php echo $item['ma_bien_the']; ?>">
                                                <div class="qty-box">
                                                    <button type="submit" class="qty-btn" name="update_quantity" value="-1">-</button>
                                                    <input type="number" name="so_luong" value="<?php echo $item['so_luong']; ?>" 
                                                           class="qty-input" min="1" readonly>
                                                    <button type="submit" class="qty-btn" name="update_quantity" value="1">+</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="col-subtotal"><?php echo number_format($thanh_tien, 0, ',', '.') . ' ₫'; ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <a href="<?php echo $this->url('Khachhang'); ?>" class="btn-continue-shop"><i class="fa-solid fa-arrow-left"></i> Tiếp tục xem sản phẩm</a>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h4>Giỏ hàng của bạn đang trống</h4>
                            <p>Thêm sản phẩm vào giỏ hàng để tiến hành mua sắm</p>
                            <a href="<?php echo $this->url('Khachhang'); ?>" class="btn btn-primary">Tiếp tục mua sắm</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="cart-summary-box">
                    <h3 class="summary-title">TỔNG CỘNG GIỎ HÀNG</h3>
                    
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span class="summary-total"><?php echo number_format($tong_tien ?? 0, 0, ',', '.') . ' ₫'; ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tổng</span>
                        <span class="summary-total"><?php echo number_format($tong_tien ?? 0, 0, ',', '.') . ' ₫'; ?></span>
                    </div>

                    <a href="<?php echo $this->url('Khachhang/thanhtoan'); ?>" class="btn-checkout-page">TIẾN HÀNH THANH TOÁN</a>

                    <div class="coupon-section">
                        <div class="coupon-label"><i class="fa-solid fa-tag"></i> Mã ưu đãi</div>
                        <input type="text" class="coupon-input" placeholder="Mã ưu đãi">
                        <button class="btn-apply-coupon">Áp dụng</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                
                <div class="footer-col">
                    <div class="footer-logo">
                        <i class="fa-brands fa-instalod"></i> TECHZONE
                    </div>
                    <ul class="address-list">
                        <li><strong>Địa chỉ:</strong></li>
                        <li><strong>Cơ sở 1:</strong> 221 Vũ Tông Phan - Thanh Xuân - Hà Nội</li>
                        <li><strong>Cơ sở 2:</strong> 17 Nguyễn Phong Sắc - Cầu Giấy - Hà Nội</li>
                        <li><strong>Cơ sở 3:</strong> 145 Minh Khai - Hai Bà Trưng - Hà Nội</li>
                        <li><strong>Cơ sở 4:</strong> 142 Quang Trung - Hà Đông - Hà Nội</li>
                        <li><strong>Gọi mua hàng:</strong> 0825.303.888 (8h00 - 22h00)</li>
                        <li><strong>Gọi bảo hành:</strong> 0922.702.888 (8h00 - 21h00)</li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Chính sách</h4>
                    <ul class="footer-links">
                        <li><a href="#">Chính sách mua hàng</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Cam kết chất lượng</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Hệ thống cửa hàng</a></li>
                    </ul>
                    <h4>Fanpage</h4>
                    <div class="fanpage-box">
                        <div class="fp-container">
                            <div class="fp-avatar">
                                <img src="https://tse3.mm.bing.net/th/id/OIP.YxmH1xNVNfvD5MlgINYERgHaEB?rs=1&pid=ImgDetMain&o=7&rm=3" alt="TechZone">
                            </div>
                            <div class="fp-info">
                                <a href="#" class="fp-name">TechZone - Chính Chủ</a>
                                <span class="fp-followers">96.598 người theo dõi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-col">
                    <div class="social-icons">
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                    <div class="contact-info">
                        <p>Nhận phản hồi, thắc mắc:</p>
                        <p>anttech.com.vn @gmail.com</p>
                        <p style="margin-top: 15px;">Tư vấn miễn phí 24/07</p>
                        <span class="hotline-large">0825.303.888</span>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <script>
        // Toggle Account Menu
        document.getElementById('accountBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('accountMenu').classList.toggle('active');
        });

        window.addEventListener('click', function(event) {
            const accountBtn = document.getElementById('accountBtn');
            const accountMenu = document.getElementById('accountMenu');
            if (!accountBtn.contains(event.target)) {
                accountMenu.classList.remove('active');
            }
        });

        // Update quantity buttons to submit form
        document.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const currentValue = parseInt(form.querySelector('.qty-input').value);
                const operation = this.getAttribute('value');
                
                if (operation === '1') {
                    form.querySelector('.qty-input').value = currentValue + 1;
                } else if (operation === '-1') {
                    if (currentValue > 1) {
                        form.querySelector('.qty-input').value = currentValue - 1;
                    }
                }
                
                form.submit();
            });
        });
    </script>

</body>
</html>