<?php
// Include necessary helpers
include_once __DIR__ . '/../../../../Public/Classes/TimezoneHelper.php';
include_once __DIR__ . '/../../../../Public/Classes/UrlHelper.php';
?>

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
        --price-discount: #008000;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }

    body {
        background-color: var(--bg-light);
        color: var(--text-dark);
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    ul {
        list-style: none;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 10px;
    }

    /* --- 2. TOP BANNER --- */
    .top-banner {
        background-color: var(--primary-green);
        color: white;
        font-size: 13px;
        padding: 8px 0;
    }

    .top-banner .container {
        display: flex;
        justify-content: space-between;
    }

    .top-banner-left {
        display: flex;
        gap: 155px;
    }

    .top-banner-right {
        display: flex;
        gap: 20px;
    }

    .top-banner span i {
        margin-right: 5px;
    }

    /* --- 3. MAIN HEADER --- */
    .main-header {
        background: white;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-color);
        position: relative;
        z-index: 100;
    }

    .main-header .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .logo {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary-green);
        display: flex;
        align-items: center;
        white-space: nowrap;
    }

    /* Layout khu vực giữa */
    .middle-section {
        flex-grow: 1;
        max-width: 700px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Nút Danh mục - Đã tối ưu CSS để hover mượt */
    .category-dropdown {
        position: relative;
        padding-bottom: 10px;
        margin-bottom: -10px;
    }

    .btn-category {
        height: 40px;
        background: #f5f5f7;
        border: 1px solid #e0e0e0;
        padding: 0 15px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        color: #333;
        transition: 0.2s;
    }

    .btn-category:hover {
        background: #eee;
    }

    /* Menu Danh mục - Hiện khi hover vào category-dropdown */
    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        width: 220px;
        background: white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        padding: 10px 0;
        margin-top: 10px;
        display: none;
        z-index: 1000;
        border: 1px solid #eee;
    }

    .dropdown-menu::before {
        content: "";
        position: absolute;
        top: -6px;
        left: 20px;
        width: 12px;
        height: 12px;
        background: white;
        transform: rotate(45deg);
        border-left: 1px solid #eee;
        border-top: 1px solid #eee;
    }

    .category-dropdown:hover .dropdown-menu {
        display: block;
        animation: fadeIn 0.2s ease;
    }

    .dropdown-menu ul li a {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        font-size: 14px;
        color: #333;
        transition: 0.2s;
        width: 100%;
    }

    .dropdown-menu ul li a i {
        width: 25px;
        color: #888;
        text-align: center;
        margin-right: 10px;
    }

    .dropdown-menu ul li a:hover {
        background-color: #f9f9f9;
        color: var(--primary-green);
        padding-left: 25px;
    }

    .dropdown-menu ul li a:hover i {
        color: var(--primary-green);
    }


    .popular-keywords {
        margin-top: 5px;
        font-size: 13px;
        color: var(--text-gray);
        margin-left: 356px;
    }

    .popular-keywords span {
        margin-right: 5px;
    }

    .popular-keywords a {
        margin-right: 10px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 14px;
    }

    .action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--text-gray);
        cursor: pointer;
        white-space: nowrap;
        position: relative;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: var(--tet-red);
        color: white;
        font-size: 10px;
        width: 18px;
        height: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }

    /* CSS CHO MENU TÀI KHOẢN */
    .account-dropdown-menu {
        position: absolute;
        top: 45px;
        right: -10px;
        width: 200px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        padding: 8px 0;
        display: none;
        z-index: 1100;
        border: 1px solid #eee;
    }

    .account-dropdown-menu.active {
        display: block;
        animation: fadeIn 0.2s ease;
    }

    .account-dropdown-menu::before {
        content: "";
        position: absolute;
        top: -6px;
        right: 25px;
        width: 12px;
        height: 12px;
        background: white;
        transform: rotate(45deg);
        border-top: 1px solid #eee;
        border-left: 1px solid #eee;
    }

    .account-dropdown-menu a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        font-size: 14px;
        color: #333;
        transition: all 0.2s;
        text-decoration: none;
    }

    .account-dropdown-menu a:hover {
        background-color: #f5f5f7;
        color: var(--primary-green);
    }

    .account-dropdown-menu a i {
        width: 25px;
        color: #888;
        margin-right: 5px;
    }

    .divider {
        height: 1px;
        background-color: #eee;
        margin: 5px 0;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* --- 4. MAIN LAYOUT --- */
    .main-body-wrapper {
        background: linear-gradient(180deg, #fff 0%, #fff6f6 100%);
        padding: 20px 0;
        min-height: 100vh;
    }

    .main-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 30px;
    }

    /* --- 5. SIDEBAR (FILTER) --- */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .filter-widget {
        background: transparent;
        padding-right: 10px;
    }

    .filter-main-title {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #333;
    }

    .filter-group {
        margin-bottom: 25px;
    }

    .filter-group-title {
        font-size: 16px;
        font-weight: 400;
        color: #666;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filter-option {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 14px;
        color: #555;
        position: relative;
        padding-left: 30px;
    }

    .filter-option:hover {
        color: #000;
    }

    .filter-option input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 50%;
    }

    .filter-option input:checked~.checkmark {
        border-color: var(--primary-green);
    }

    .filter-option .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        top: 4px;
        left: 4px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--primary-green);
    }

    .filter-option input:checked~.checkmark:after {
        display: block;
    }

    /* --- 6. MAIN CONTENT --- */
    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filter-header h2 {
        font-size: 18px;
        font-weight: 400;
        color: #333;
    }

    .btn-filter-now {
        background: white;
        border: 1px solid #ddd;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .results-count {
        font-size: 14px;
        margin-bottom: 15px;
        font-weight: 500;
        color: #333;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .product-card {
        background: rgba(242, 238, 238, 0.899);
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
        position: relative;
        border: 1px solid transparent;
    }

    .product-card:hover {
        border-color: var(--tet-red);
        transform: translateY(-2px);
    }

    .sticker-sale {
        position: absolute;
        top: 10px;
        left: -5px;
        background: var(--tet-red);
        color: white;
        padding: 3px 8px;
        font-size: 12px;
        font-weight: bold;
        border-radius: 4px;
        z-index: 2;
    }

    .sticker-sale::before {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        border-top: 5px solid #a10000;
        border-left: 5px solid transparent;
    }

    .product-img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .product-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        line-height: 1.4;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .current-price {
        color: #d70018;
        font-weight: 700;
        font-size: 16px;
    }

    .old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 13px;
    }

    .discount-percent {
        color: #d70018;
        font-size: 12px;
        font-weight: 700;
        background: #fff0f0;
        padding: 2px 4px;
        border-radius: 3px;
    }

    .price-discount-text {
        color: var(--price-discount);
        font-size: 12px;
        margin-top: 5px;
        font-weight: 500;
    }

    .buy-btn {
        background: var(--tet-red);
        color: white;
        text-align: center;
        padding: 8px;
        border-radius: 4px;
        font-weight: 500;
        opacity: 0;
        transition: 0.3s;
        cursor: pointer;
    }

    .product-card:hover .buy-btn {
        opacity: 1;
    }

    .product-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    /* Footer Styles */
    .main-footer {
        border-top: 4px solid #f4f4f4;
        padding: 40px 0 20px;
        margin-top: 0;
        background: white;
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

    .footer-logo img {
        height: 40px;
    }

    .footer-col h4 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #333;
    }

    .address-list li {
        font-size: 13px;
        color: #555;
        margin-bottom: 8px;
        line-height: 1.6;
    }

    .address-list li strong {
        color: #333;
    }

    .footer-links li {
        margin-bottom: 8px;
    }

    .footer-links li a {
        font-size: 13px;
        color: #555;
    }

    .footer-links li a:hover {
        color: var(--tet-red);
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
        text-decoration: none;
        line-height: 1.2;
    }

    .fp-name:hover {
        text-decoration: underline;
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

    .social-icons a:hover {
        border-color: var(--tet-red);
        color: var(--tet-red);
    }

    .contact-info p {
        font-size: 13px;
        color: #333;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .contact-info span {
        font-weight: 400;
        color: #555;
    }

    .hotline-large {
        font-size: 18px;
        color: #333;
        font-weight: 700;
        margin-top: 10px;
        display: block;
    }


    /* Loading indicator */
    .loading {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        font-size: 16px;
        color: #666;
    }

    .error {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        font-size: 16px;
        color: #d70018;
    }

    .no-results-box {
        grid-column: 1 / -1;
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: 10px;
        padding: 28px 22px;
    }

    .no-results-title {
        font-size: 28px;
        color: #c7c7c7;
        text-align: center;
        margin-bottom: 10px;
    }

    .no-results-box h3 {
        text-align: center;
        margin-bottom: 8px;
    }

    .no-results-box p {
        text-align: center;
        color: #666;
        margin-bottom: 8px;
    }

    .suggestion-title {
        margin-top: 18px;
        margin-bottom: 12px;
        font-weight: 700;
        text-align: center;
    }

    .suggestion-products {
        display: flex;
        gap: 14px;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        padding-bottom: 6px;
    }

    .suggestion-product {
        text-decoration: none;
        color: inherit;
        text-align: center;
        min-width: 140px;
        max-width: 140px;
        flex: 0 0 140px;
    }

    .suggestion-product img {
        width: 100%;
        height: 110px;
        object-fit: contain;
        border: 1px solid #efefef;
        border-radius: 8px;
        padding: 8px;
        background: #fafafa;
        margin-bottom: 8px;
    }

    .suggestion-product-name {
        font-size: 12px;
        color: #444;
        line-height: 1.35;
        height: 32px;
        overflow: hidden;
    }

    /* Pagination Styles */
    .pagination {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 10px 20px;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .pagination-btn {
        display: inline-block;
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        transition: all 0.2s;
        min-width: 40px;
        text-align: center;
    }

    .pagination-btn:hover {
        background: #f5f5f7;
        border-color: var(--primary-green);
        color: var(--primary-green);
    }

    .pagination-btn.active {
        background: var(--primary-green);
        color: white;
        border-color: var(--primary-green);
    }

    .pagination-btn.disabled {
        color: #999;
        border-color: #ddd;
        cursor: not-allowed;
    }

    .pagination-ellipsis {
        padding: 8px 4px;
        color: #999;
    }
</style>

<div class="main-body-wrapper">
    <div class="container">
        <div class="main-layout">

            <aside class="sidebar">
                <div class="filter-widget">
                    <h3 class="filter-main-title">Bộ lọc</h3>
                    <div class="filter-group">
                        <h4 class="filter-group-title">Danh mục</h4>

                        <label class="filter-option"><input type="radio" name="category" value="" checked><span class="checkmark"></span>Tất cả</label>

                        <?php foreach ($data['dsdm'] as $dm): ?>
                            <label class="filter-option"><input type="radio" name="category" value="<?php echo $dm['ma_danh_muc']; ?>"><span class="checkmark"></span><?php echo htmlspecialchars($dm['ten_danh_muc']); ?></label>
                        <?php endforeach; ?>
                    </div>

                    <div class="filter-group">
                        <h4 class="filter-group-title">Giá</h4>
                        <label class="filter-option"><input type="radio" name="price" value="tat-ca" checked><span class="checkmark"></span>Tất cả</label>
                        <label class="filter-option"><input type="radio" name="price" value="duoi-2-trieu"><span class="checkmark"></span>Dưới 2 triệu</label>
                        <label class="filter-option"><input type="radio" name="price" value="2-4-trieu"><span class="checkmark"></span>Từ 2 - 4 triệu</label>
                        <label class="filter-option"><input type="radio" name="price" value="4-7-trieu"><span class="checkmark"></span>Từ 4 - 7 triệu</label>
                        <label class="filter-option"><input type="radio" name="price" value="7-13-trieu"><span class="checkmark"></span>Từ 7 - 13 triệu</label>
                        <label class="filter-option"><input type="radio" name="price" value="tren-13-trieu"><span class="checkmark"></span>Trên 13 triệu</label>
                    </div>

                    <div class="filter-group">
                        <h4 class="filter-group-title">Thương hiệu</h4>
                        <label class="filter-option"><input type="radio" name="brand" value="" checked><span class="checkmark"></span>Tất cả</label>
                        <?php
                        $thuong_hieu_model = $this->model("ThuongHieu_m");
                        $dsth = $thuong_hieu_model->ThuongHieu_getAll();
                        foreach ($dsth as $th):
                        ?>
                            <label class="filter-option"><input type="radio" name="brand" value="<?php echo $th['ma_thuong_hieu']; ?>"><span class="checkmark"></span><?php echo htmlspecialchars($th['ten_thuong_hieu']); ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <main class="main-content">
                <div class="filter-header">
                    <h2>Tìm sản phẩm theo nhu cầu</h2>
                    <button class="btn-filter-now"><i class="fa-solid fa-filter"></i> Dùng bộ lọc ngay</button>
                </div>

                <div class="results-count">Đang tải dữ liệu sản phẩm...</div>

                <section class="product-section">
                    <div class="product-grid"></div>
                </section>

                <div class="pagination-container" style="margin-top: 30px; text-align: center; display:none;"></div>
            </main>

        </div>

    </div>

</div>

<script>
    const STOREFRONT_API_BASE = '<?php echo UrlHelper::url("Api/Storefront"); ?>';

    // Variables to store current filter selections
    let currentCategory = '';
    let currentPriceRange = '';
    let currentBrand = '';
    let currentPage = 1;
    let currentSearchKeyword = '';
    const pageSize = 12;

    // bộ lọc giá
    const priceFilters = document.querySelectorAll('input[name="price"]');
    priceFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            if (this.checked && this.value) {
                currentPriceRange = this.value;
                // Filter products based on selected price range, current category and current brand
                currentPage = 1;
                fetchStorefrontProducts();
            }
        });
    });

    // bộ lọc danh mục
    const categoryFilters = document.querySelectorAll('input[name="category"]');
    categoryFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            if (this.checked) {
                if (this.value && this.value !== '') {
                    currentCategory = this.value;
                } else {
                    // If "Tất cả" is selected (no value), set category to empty
                    currentCategory = '';
                }
                // Filter products based on selected category, current price range and current brand
                currentPage = 1;
                fetchStorefrontProducts();
            }
        });
    });

    // bộ lọc thương hiệu
    const brandFilters = document.querySelectorAll('input[name="brand"]');
    brandFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            if (this.checked) {
                if (this.value && this.value !== '') {
                    currentBrand = this.value;
                } else {
                    // If "Tất cả" is selected (no value), set brand to empty
                    currentBrand = '';
                }
                // Filter products based on selected brand, current category and current price range
                currentPage = 1;
                fetchStorefrontProducts();
            }
        });
    });

    function buildApiUrl() {
        const params = new URLSearchParams();
        params.set('page', currentPage);
        params.set('limit', pageSize);

        if (currentCategory) params.set('category_id', currentCategory);
        if (currentPriceRange) params.set('price_range', currentPriceRange);
        if (currentBrand) params.set('brand_id', currentBrand);
        if (currentSearchKeyword) params.set('q', currentSearchKeyword);

        return `${STOREFRONT_API_BASE}?${params.toString()}`;
    }

    // Function to filter products by category, price range and brand
    function fetchStorefrontProducts() {
        // Show loading indicator
        const productGrid = document.querySelector('.product-grid');
        productGrid.innerHTML = '<div class="loading">Đang tải sản phẩm...</div>';

        fetch(buildApiUrl(), {
                method: 'GET'
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (!data.success) {
                    productGrid.innerHTML = `<p class="error">Lỗi máy chủ: ${data.message || 'Không xác định'}</p>`;
                    return;
                }

                const items = data.data?.items || [];
                const pagination = data.data?.pagination || {
                    total: 0,
                    total_pages: 0,
                    page: 1
                };

                updateProductGrid(items);
                updatePagination(pagination);
                const resultCountEl = document.querySelector('.results-count');
                if (resultCountEl) {
                    const suffix = currentSearchKeyword ? ` cho "${currentSearchKeyword}"` : '';
                    resultCountEl.textContent = `Tìm thấy ${pagination.total || 0} kết quả${suffix}`;
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                productGrid.innerHTML = '<p class="error">Lỗi khi tải sản phẩm. Vui lòng thử lại sau.</p>';
            });
    }

    function updatePagination(meta) {
        const container = document.querySelector('.pagination-container');
        if (!container) {
            return;
        }

        const totalPages = Number(meta.total_pages || 0);
        const page = Number(meta.page || 1);

        if (totalPages <= 1) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'block';

        let html = '<div class="pagination">';
        if (page > 1) {
            html += `<a href="#" class="pagination-btn" data-page="${page - 1}">&laquo; Trước</a>`;
        } else {
            html += '<span class="pagination-btn disabled">&laquo; Trước</span>';
        }

        const startPage = Math.max(1, page - 2);
        const endPage = Math.min(totalPages, page + 2);

        if (startPage > 1) {
            html += '<a href="#" class="pagination-btn" data-page="1">1</a>';
            if (startPage > 2) {
                html += '<span class="pagination-ellipsis">...</span>';
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            if (i === page) {
                html += `<span class="pagination-btn active">${i}</span>`;
            } else {
                html += `<a href="#" class="pagination-btn" data-page="${i}">${i}</a>`;
            }
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += '<span class="pagination-ellipsis">...</span>';
            }
            html += `<a href="#" class="pagination-btn" data-page="${totalPages}">${totalPages}</a>`;
        }

        if (page < totalPages) {
            html += `<a href="#" class="pagination-btn" data-page="${page + 1}">Tiếp &raquo;</a>`;
        } else {
            html += '<span class="pagination-btn disabled">Tiếp &raquo;</span>';
        }

        html += '</div>';
        container.innerHTML = html;

        container.querySelectorAll('a.pagination-btn[data-page]').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = Number(this.getAttribute('data-page'));
                fetchStorefrontProducts();
            });
        });
    }

    function rankSuggestionProducts(products, maxItems) {
        if (!Array.isArray(products)) {
            return [];
        }

        const strong = [];
        const normal = [];

        products.forEach(product => {
            const hasImage = !!(product && product.img_bien_the);
            const inStock = Number(product && product.so_luong_kho ? product.so_luong_kho : 0) > 0;
            const hasName = !!(product && String(product.ten_san_pham || '').trim().length >= 4);

            if (!hasName) {
                return;
            }

            if (hasImage && inStock) {
                strong.push(product);
            } else {
                normal.push(product);
            }
        });

        return strong.concat(normal).slice(0, maxItems);
    }

    function renderNoResultSuggestions(products) {
        const productGrid = document.querySelector('.product-grid');
        if (!productGrid) {
            return;
        }

        const keywordSafe = (currentSearchKeyword || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        let suggestionsHtml = '';

        const rankedProducts = rankSuggestionProducts(products, 7);

        if (rankedProducts.length > 0) {
            const baseUrl = '<?php echo UrlHelper::url(); ?>';
            suggestionsHtml += '<div class="suggestion-title">Một số gợi ý tìm kiếm:</div>';
            suggestionsHtml += '<div class="suggestion-products">';

            rankedProducts.forEach(product => {
                const href = `<?php echo UrlHelper::url('Khachhang/chitietsanpham/'); ?>${product.ma_san_pham || ''}`;
                const img = product.img_bien_the ?
                    `${baseUrl}Public/Pictures/bien_the/${encodeURIComponent(product.img_bien_the)}` :
                    `${baseUrl}Public/Images/no-image.png`;

                suggestionsHtml += `
                    <a href="${href}" class="suggestion-product">
                        <img src="${img}" alt="${product.ten_san_pham || ''}" onerror="this.onerror=null;this.src='${baseUrl}Public/Images/no-image.png';">
                        <div class="suggestion-product-name">${product.ten_san_pham || ''}</div>
                    </a>
                `;
            });

            suggestionsHtml += '</div>';
        }

        productGrid.innerHTML = `
            <div class="no-results-box">
                <div class="no-results-title"><i class="fa-solid fa-magnifying-glass"></i></div>
                <h3>Không tìm thấy sản phẩm nào</h3>
                <p>Chúng tôi không tìm thấy sản phẩm nào phù hợp với từ khóa "<strong>${keywordSafe}</strong>"</p>
                <p>Vui lòng thử lại với từ khóa khác</p>
                ${suggestionsHtml}
            </div>
        `;
    }

    function fetchNoResultSuggestions() {
        const recommendationUrl = `${STOREFRONT_API_BASE}?page=1&limit=20`;

        fetch(recommendationUrl, {
                method: 'GET'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch suggestions');
                }
                return response.json();
            })
            .then(data => {
                const items = data && data.success && data.data && Array.isArray(data.data.items) ?
                    data.data.items : [];
                renderNoResultSuggestions(items);
            })
            .catch(() => {
                renderNoResultSuggestions([]);
            });
    }

    // Function to update the product grid with new data
    function updateProductGrid(products) {
        const productGrid = document.querySelector('.product-grid');
        productGrid.innerHTML = '';

        if (products.length > 0) {
            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';

                // Format prices with proper checks
                const giaCuFormatted = product.gia_cu ?
                    new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(product.gia_cu) :
                    (product.gia ?
                        new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(product.gia) :
                        'Liên hệ');

                const giaMoiFormatted = product.gia_moi ?
                    new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(product.gia_moi) :
                    (product.gia ?
                        new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(product.gia) :
                        'Liên hệ');

                const discountAmount = product.gia_cu && product.gia_moi ?
                    new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(product.gia_cu - product.gia_moi) :
                    (product.gia && product.giam_gia ?
                        new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(product.gia * product.giam_gia / 100) :
                        'Liên hệ');

                // Create the product link wrapper
                const productLink = document.createElement('a');
                productLink.className = 'product-link';
                productLink.href =
                    `<?php echo UrlHelper::url('Khachhang/chitietsanpham/'); ?>${product.ma_san_pham}`;

                let productHtml = `<span class="sticker-sale">-${product.giam_gia || '0'}%</span>`;

                const baseUrl = '<?php echo UrlHelper::url(); ?>';
                if (product.img_bien_the) {
                    productHtml +=
                        `<img src="${baseUrl}Public/Pictures/bien_the/${encodeURIComponent(product.img_bien_the)}" alt="${product.ten_san_pham || ''}" style="width:100%;height:180px;object-fit:contain;margin-bottom:15px;" />`;
                } else {
                    productHtml +=
                        `<img src="${baseUrl}Public/Images/no-image.png" alt="${product.ten_san_pham || ''}" style="width:100%;height:180px;object-fit:contain;margin-bottom:15px;" />`;
                }

                productHtml += `<h3 class="product-name">${product.ten_san_pham || ''}</h3>
                <div class="product-price-box">
                    <div class="price-row">
                        <span class="old-price">${giaCuFormatted}</span>`;

                if (product.giam_gia && product.giam_gia > 0) {
                    productHtml += `<span class="discount-percent">-${product.giam_gia}%</span>`;
                }

                productHtml += `</div>
                <div class="current-price">${giaMoiFormatted}</div>
                <div class="price-discount-text">Giảm ${discountAmount}</div>
            </div>
            <div class="buy-btn">Mua ngay</div>`;

                productLink.innerHTML = productHtml;
                productCard.appendChild(productLink);
                productGrid.appendChild(productCard);
            });
        } else {
            if (currentSearchKeyword) {
                fetchNoResultSuggestions();
            } else {
                productGrid.innerHTML = '<p class="no-products">Không tìm thấy sản phẩm nào phù hợp.</p>';
            }
        }
    }

    // Khoi dong du lieu theo API de dong bo hoan toan REST cho trang chu
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const p = parseInt(urlParams.get('page') || '1', 10);
        const q = (urlParams.get('q') || '').trim();
        const categoryId = (urlParams.get('category_id') || '').trim();

        currentPage = Number.isNaN(p) || p < 1 ? 1 : p;
        currentSearchKeyword = q;
        currentCategory = categoryId;

        if (categoryId) {
            const categoryRadio = document.querySelector('input[name="category"][value="' + categoryId.replace(/"/g, '\\"') + '"]');
            if (categoryRadio) {
                categoryRadio.checked = true;
            }
        }

        fetchStorefrontProducts();
    });
</script>