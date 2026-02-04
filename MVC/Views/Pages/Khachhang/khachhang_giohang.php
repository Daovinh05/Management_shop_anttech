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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: var(--text-dark);
            position: relative;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 10px;
        }


        /* --- CSS PAGE GIỎ HÀNG CHÍNH --- */
        .cart-page-container {
            padding: 20px 0 50px 0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            text-transform: uppercase;
        }

        .cart-grid-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            align-items: start;
        }

        /* Bảng sản phẩm */
        .cart-table-wrapper {
            background: #fff;
            border-top: 1px solid #eee;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th {
            text-align: left;
            padding: 15px 10px;
            border-bottom: 2px solid #eee;
            font-size: 14px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
        }

        .cart-table th:nth-child(2) {
            text-align: center;
        }

        .cart-table th:nth-child(3) {
            text-align: center;
        }

        .cart-table th:nth-child(4) {
            text-align: right;
        }

        .cart-table td {
            padding: 20px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .col-product {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-remove-item {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #ccc;
            background: white;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
            flex-shrink: 0;
        }

        .btn-remove-item:hover {
            border-color: var(--tet-red);
            color: var(--tet-red);
        }

        .cart-prod-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border: 1px solid #f0f0f0;
            padding: 5px;
        }

        .cart-prod-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .cart-prod-name {
            font-weight: 700;
            color: #2d72d2;
            font-size: 15px;
        }

        .cart-prod-meta {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
        }

        .col-price {
            font-weight: 700;
            color: #333;
            text-align: center;
        }

        .col-subtotal {
            font-weight: 700;
            color: var(--tet-red);
            text-align: right;
            font-size: 16px;
        }

        /* Nút số lượng */
        .qty-box {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            width: 100px;
            margin: 0 auto;
        }

        .qty-btn {
            width: 30px;
            height: 35px;
            background: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: #f5f5f5;
        }

        .qty-input {
            width: 40px;
            height: 35px;
            border: none;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
            font-weight: 600;
            outline: none;
        }

        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .btn-continue-shop {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border: 2px solid var(--tet-red);
            color: var(--tet-red);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
            transition: 0.2s;
            text-decoration: none;
        }

        .btn-continue-shop:hover {
            background: var(--tet-red);
            color: white;
        }

        .btn-continue-shop i {
            margin-right: 5px;
        }

        /* Cột Tổng tiền */
        .cart-summary-box {
            background: white;
            padding-left: 20px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }

        .summary-total {
            color: var(--tet-red);
            font-size: 18px;
        }

        .btn-checkout-page {
            width: 100%;
            background-color: var(--tet-red);
            color: white;
            border: none;
            padding: 15px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 30px;
            cursor: pointer;
            margin-bottom: 30px;
            transition: 0.3s;
            display: block;
            text-align: center;
            text-decoration: none;
        }

        .btn-checkout-page:hover {
            background-color: #b70014;
        }

        .coupon-section {
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .coupon-label {
            font-size: 14px;
            font-weight: 700;
            color: #555;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .coupon-input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .btn-apply-coupon {
            width: 100%;
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            color: #555;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-apply-coupon:hover {
            background: #f4f4f4;
            color: #333;
        }

        /* Footer */
        .main-footer {
            border-top: 4px solid #f4f4f4;
            padding: 40px 0 20px;
            background: white;
            margin-top: 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            gap: 40px;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: 800;
            color: #006a5b;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
        }

        .address-list li,
        .footer-links li {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .fanpage-box {
            background: white;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 5px;
        }

        .fp-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .fp-avatar {
            width: 50px;
            height: 50px;
            border: 1px solid #ddd;
            overflow: hidden;
            flex-shrink: 0;
        }

        .fp-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fp-info {
            display: flex;
            flex-direction: column;
            padding-top: 2px;
        }

        .fp-name {
            color: var(--fb-blue);
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .fp-followers {
            color: #4b4f56;
            font-size: 12px;
        }

        .social-icons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .social-icons a {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 1px solid #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #555;
        }

        .contact-info p {
            font-size: 13px;
            color: #333;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .hotline-large {
            font-size: 18px;
            color: #333;
            font-weight: 700;
            margin-top: 10px;
            display: block;
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--tet-red); /* Màu khi check */
        }

        .selected-items-container {
            margin: 15px 0;
            max-height: 300px; /* Giới hạn chiều cao, nếu dài quá thì cuộn */
            overflow-y: auto;
            border-top: 1px dashed #eee;
            border-bottom: 1px dashed #eee;
            padding: 10px 0;
        }

        .mini-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f9f9f9;
        }

        .mini-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .mini-item-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border: 1px solid #eee;
            padding: 2px;
        }

        .mini-item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .mini-item-name {
            font-size: 13px;
            font-weight: 700;
            color: #333;
            line-height: 1.2;
            margin-bottom: 2px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .mini-item-meta {
            font-size: 11px;
            color: #888;
        }

        .mini-item-price {
            font-size: 12px;
            font-weight: 700;
            color: var(--tet-red);
        }

        @media (max-width: 900px) {
            .cart-grid-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>


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
                                        <th style="width: 50px; text-align: center;">
                                            <label for="checkAll" style="cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px;">
                                                <input type="checkbox" id="checkAll" class="custom-checkbox" onclick="toggleAll(this)" checked>
                                                <span style="font-size: 14px; font-weight: 600;">All</span>
                                            </label>
                                        </th>
                                        <th>SẢN PHẨM</th>
                                        <th>GIÁ</th>
                                        <th style="text-align: center;">SỐ LƯỢNG</th>
                                        <th style="text-align: center;">TẠM TÍNH</th>
                                        <th>XÓA</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody">
                                    <?php
                                    $tong_tien = 0;
                                    foreach ($data['detailed_cart'] as $item):
                                        $thanh_tien = $item['gia'] * $item['so_luong'];
                                        $tong_tien += $thanh_tien;

                                        // Tạo chuỗi biến thể đầy đủ (bao gồm RAM)
                                        $variant_text = [];
                                        if(!empty($item['mau_sac'])) $variant_text[] = $item['mau_sac'];
                                        if(!empty($item['dung_luong'])) $variant_text[] = $item['dung_luong'];
                                        if(!empty($item['ram'])) $variant_text[] = $item['ram']; // Laptop thường có RAM
                                        
                                        // Nếu mảng rỗng thì dùng tên biến thể gốc
                                        $variant_str = !empty($variant_text) ? implode(' - ', $variant_text) : ($item['ten_bien_the'] ?? '');

                                        // Determine image source
                                        $img_src = !empty($item['img_bien_the']) ? '/Banhang/Public/Pictures/bien_the/' . $item['img_bien_the'] : $this->url('Public/Images/no-image.png');
                                    ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <input type="checkbox" 
                                                    name="selected_items[]" 
                                                    value="<?php echo $item['ma_bien_the']; ?>" 
                                                    class="custom-checkbox item-checkbox"

                                                    data-price="<?php echo $item['gia']; ?>"
                                                    data-quantity="<?php echo $item['so_luong']; ?>"
                                                    data-name="<?php echo htmlspecialchars($item['ten_san_pham']); ?>"
                                                    data-img="<?php echo $img_src; ?>"
                                                    data-variant="<?php echo htmlspecialchars($variant_str); ?>"

                                                    onclick="updateTotal()"
                                                    checked>
                                            </td>
                                            <td>
                                                <div class="col-product">
                                                    
                                                    <img src="<?php echo $img_src; ?>" class="cart-prod-img"
                                                        alt="<?php echo $item['ten_san_pham']; ?>">
                                                    <div class="cart-prod-info">
                                                        <a href="#"
                                                            class="cart-prod-name"><?php echo $item['ten_san_pham']; ?></a>
                                                        <?php if (!empty($item['ten_bien_the'])): ?>
                                                        <div class="cart-prod-meta" style="color:#333; font-weight:600;">
                                                            <?php echo $item['ten_bien_the']; ?>
                                                        </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($item['mau_sac'])): ?>
                                                            <div class="cart-prod-meta">MÀU SẮC: <?php echo $item['mau_sac']; ?></div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($item['ram'])): ?>
                                                            <div class="cart-prod-meta">RAM: <?php echo $item['ram']; ?></div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($item['dung_luong'])): ?>
                                                            <div class="cart-prod-meta">DUNG LƯỢNG: <?php echo $item['dung_luong']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="col-price"><?php echo number_format($item['gia'], 0, ',', '.') . ' ₫'; ?>
                                            </td>
                                            <td>
                                                <div class="qty-box">
                                                    <button type="button" class="qty-btn" 
                                                            onclick="updateCartItem(this, -1, '<?php echo $item['ma_bien_the']; ?>', <?php echo $item['gia']; ?>)">
                                                        -
                                                    </button>
                                                    
                                                    <input type="number" 
                                                        id="qty_<?php echo $item['ma_bien_the']; ?>" 
                                                        value="<?php echo $item['so_luong']; ?>" 
                                                        class="qty-input" 
                                                        min="1" 
                                                        onchange="updateCartItem(this, 0, '<?php echo $item['ma_bien_the']; ?>', <?php echo $item['gia']; ?>)">
                                                    
                                                    <button type="button" class="qty-btn" 
                                                            onclick="updateCartItem(this, 1, '<?php echo $item['ma_bien_the']; ?>', <?php echo $item['gia']; ?>)">
                                                        +
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="col-subtotal" style="text-align: center;" id="subtotal_<?php echo $item['ma_bien_the']; ?>">
                                                <?php echo number_format($thanh_tien, 0, ',', '.') . ' ₫'; ?>
                                            </td>
                                            
                                            <td class="col-actions" style="text-align: center;">
                                                <a href="<?php echo $this->url('Khachhang/xoakhoigio/' . $item['ma_gio_hang'] . '/' . $item['ma_bien_the']); ?>"
                                                class="btn-remove-item"
                                                style="margin: 0 auto;"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <a href="<?php echo $this->url('Khachhang'); ?>" class="btn-continue-shop"><i
                                class="fa-solid fa-arrow-left"></i> Tiếp tục xem sản phẩm</a>
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
                        <span>Đã chọn :</span>
                        <span id="selectedCount">0 loại sản phẩm</span>
                    </div>

                    <div id="selectedItemsList" class="selected-items-container">
                    </div>

                    <div class="summary-row">
                        <span>Tổng tiền :</span>
                        <span class="summary-total" id="displayTotal">0 ₫</span>
                    </div>

                    <button onclick="proceedToCheckout()" class="btn-checkout-page">TIẾN HÀNH THANH TOÁN</button>

                    </div>

            </div>
        </div>
    </div>


    <script>
        

        // Hàm format tiền tệ VNĐ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', '') + ' ₫';
        }

        // Check all / Uncheck all
        function toggleAll(source) {
            checkboxes = document.querySelectorAll('.item-checkbox');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
            updateTotal();
        }

        // Tính tổng tiền dựa trên các checkbox được chọn
        function updateTotal() {
            var checkboxes = document.querySelectorAll('.item-checkbox:checked');
            var total = 0;
            var count = 0;
            var listHtml = '';

            checkboxes.forEach(function(checkbox) {
                var price = parseInt(checkbox.getAttribute('data-price'));
                var quantity = parseInt(checkbox.getAttribute('data-quantity'));
                var name = checkbox.getAttribute('data-name');
                var img = checkbox.getAttribute('data-img');
                var variant = checkbox.getAttribute('data-variant');
                total += price * quantity;
                count++;

                listHtml += `
                <div class="mini-item">
                    <img src="${img}" class="mini-item-img" alt="${name}">
                    <div class="mini-item-info">
                        <div class="mini-item-name">${name}</div>
                        <div class="mini-item-meta">${variant}</div>
                        <div class="mini-item-price">
                            ${quantity} x ${formatCurrency(price)}
                        </div>
                    </div>
                </div>
            `;
            });

            // Cập nhật giao diện
            document.getElementById('displayTotal').innerText = formatCurrency(total);
            document.getElementById('selectedCount').innerText = count + " loại sản phẩm";
            document.getElementById('selectedItemsList').innerHTML = listHtml;
            
            // Cập nhật trạng thái checkbox tổng
            var allCheckboxes = document.querySelectorAll('.item-checkbox');
            var checkAll = document.getElementById('checkAll');
            if(checkAll) {
                checkAll.checked = (checkboxes.length > 0 && checkboxes.length === allCheckboxes.length);
            }        
        }

        // Xử lý khi nhấn nút Thanh Toán
        function proceedToCheckout() {
            var checkboxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkboxes.length === 0) {
                alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
                return;
            }

            // Tạo mảng các mã biến thể đã chọn
            var selectedVariants = [];
            checkboxes.forEach(function(checkbox) {
                selectedVariants.push(checkbox.value);
            });

            // Chuyển hướng sang trang thanh toán kèm theo tham số items
            // Ví dụ: thanhtoan?items=BT01,BT02
            var url = "<?php echo $this->url('Khachhang/thanhtoan'); ?>?items=" + selectedVariants.join(',');
            window.location.href = url;
        }

        updateTotal();

        // Hàm xử lý cập nhật số lượng (AJAX)
        function updateCartItem(element, change, ma_bien_the, don_gia) {
            var input;
            
            // Xác định thẻ input dựa vào element người dùng click
            if (change === 0) {
                // Nếu người dùng nhập trực tiếp vào input
                input = element;
            } else {
                // Nếu người dùng bấm nút + hoặc -
                // Tìm thẻ input trong cùng cha (div.qty-box)
                input = element.parentElement.querySelector('.qty-input');
            }

            var currentQty = parseInt(input.value);
            var newQty = currentQty;

            if (change !== 0) {
                newQty = currentQty + change;
            }

            // Kiểm tra số lượng tối thiểu là 1
            if (newQty < 1) {
                alert("Số lượng tối thiểu là 1");
                input.value = 1; 
                newQty = 1;
                // Nếu bạn muốn cho phép xóa khi về 0 thì xử lý logic xóa ở đây
                return;
            }

            // Cập nhật giá trị hiển thị trên input ngay lập tức cho mượt
            input.value = newQty;

            // Gửi AJAX lên server
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $this->url('Khachhang/capnhatgiohang_ajax'); ?>", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // 1. Cập nhật thành tiền của dòng sản phẩm đó
                            var newSubtotal = newQty * don_gia;
                            var subtotalCell = document.getElementById('subtotal_' + ma_bien_the);
                            if (subtotalCell) {
                                subtotalCell.innerText = formatCurrency(newSubtotal);
                            }

                            // 2. Cập nhật dữ liệu trong Checkbox (để hàm updateTotal tính toán đúng)
                            var checkbox = document.querySelector(`.item-checkbox[value="${ma_bien_the}"]`);
                            if (checkbox) {
                                checkbox.setAttribute('data-quantity', newQty);
                            }

                            // 3. Gọi lại hàm tính tổng giỏ hàng
                            updateTotal();

                            var headerBadge = document.getElementById('cartBadge');
                            if (headerBadge && response.new_total_qty !== undefined) {
                                headerBadge.innerText = response.new_total_qty;
                            }
                            
                        } else {
                            alert("Lỗi: " + response.message);
                            // Reset lại số lượng cũ nếu lỗi
                            input.value = currentQty; 
                        }
                    }
                }
            };
            
            xhr.send("ma_bien_the=" + ma_bien_the + "&so_luong=" + newQty);
        }

        // Chạy tính tổng lần đầu khi trang tải xong (nếu muốn mặc định check all thì thêm code check ở đây)
        // Hiện tại để mặc định là 0
        
    </script>

</body>

</html>