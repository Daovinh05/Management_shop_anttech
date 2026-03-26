<?php
include_once __DIR__ . '/../../../../Public/Classes/UrlHelper.php';
?>
<style>
    /* Products Grid */
    .products-section {
        padding: 30px 0;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #00483d;
        margin-bottom: 20px;
        text-align: center;
    }

    .loading-spinner {
        text-align: center;
        padding: 50px;
    }

    .loading-spinner i {
        font-size: 40px;
        color: #00483d;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .product-link {
        text-decoration: none;
        color: inherit;
    }

    .product-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .sticker-sale {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #d70018;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 700;
    }

    .product-img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .product-name {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 10px;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-price-box {
        margin-bottom: 10px;
    }

    .price-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 5px;
    }

    .old-price {
        text-decoration: line-through;
        color: #999;
        font-size: 13px;
    }

    .discount-percent {
        background: #d70018;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
    }

    .current-price {
        color: #d70018;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 5px;
    }

    .price-discount-text {
        color: #008000;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .stock-status {
        font-size: 12px;
        margin-bottom: 10px;
    }

    .buy-btn {
        background: #00483d;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    .buy-btn:hover {
        background: #006a5b;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 30px;
        text-align: center;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .pagination-btn {
        padding: 8px 14px;
        border: 1px solid #e0e0e0;
        background: white;
        color: #555;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
    }

    .pagination-btn:hover,
    .pagination-btn.active {
        background: #00483d;
        color: white;
        border-color: #00483d;
    }

    .pagination-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .no-products {
        text-align: center;
        padding: 50px;
        color: #999;
    }

    .no-products i {
        font-size: 60px;
        margin-bottom: 15px;
    }
</style>

<section class="products-section">
    <div class="container">
        <h2 class="section-title">Sản phẩm nổi bật</h2>
        
        <div id="loading" class="loading-spinner">
            <i class="fa fa-spinner"></i>
        </div>

        <div id="noProducts" class="no-products" style="display: none;">
            <i class="fa fa-box-open"></i>
            <h3>Không có sản phẩm nào</h3>
            <p>Hiện tại chưa có sản phẩm nào trong cửa hàng.</p>
        </div>

        <div id="productsGrid" class="product-grid"></div>

        <div id="pagination" class="pagination-container"></div>
    </div>
</section>

<script>
    const BASE_URL = '<?php echo rtrim(BASE_URL, "/"); ?>/';
    const API_URL = BASE_URL + 'index.php?url=api';
    
    let currentPage = 1;
    let totalPages = 1;

    console.log('BASE_URL:', BASE_URL);
    console.log('API_URL:', API_URL);

    // Load products
    async function loadProducts(page = 1) {
        const loading = document.getElementById('loading');
        const productsGrid = document.getElementById('productsGrid');
        const noProducts = document.getElementById('noProducts');
        const pagination = document.getElementById('pagination');

        loading.style.display = 'block';
        productsGrid.innerHTML = '';
        noProducts.style.display = 'none';
        pagination.innerHTML = '';

        try {
            const fetchUrl = API_URL + '/products?page=' + page + '&limit=12';
            console.log('Fetching:', fetchUrl);
            
            const response = await fetch(fetchUrl);
            const data = await response.json();

            console.log('API Response:', data);
            loading.style.display = 'none';

            if (data.success && data.data.items && data.data.items.length > 0) {
                const products = data.data.items;
                const pagination = data.data.pagination;
                
                totalPages = pagination.total_pages;
                currentPage = pagination.page;

                productsGrid.innerHTML = products.map(product => `
                    <a href="${BASE_URL}Khachhang/chitietsanpham/${product.ma_san_pham}" class="product-link">
                        <div class="product-card">
                            ${product.gia > 0 ? '<span class="sticker-sale">-10%</span>' : ''}
                            <img src="${BASE_URL}Public/Pictures/bien_the/${product.hinh_anh || 'no-image.png'}" 
                                 alt="${product.ten_san_pham}" 
                                 class="product-img"
                                 onerror="this.src='${BASE_URL}Public/Images/no-image.png'">
                            <h3 class="product-name">${product.ten_san_pham}</h3>
                            <div class="product-price-box">
                                <div class="price-row">
                                    <span class="old-price">${formatPrice(product.gia * 1.1)}</span>
                                    <span class="discount-percent">-10%</span>
                                </div>
                                <div class="current-price">${formatPrice(product.gia)}</div>
                                <div class="price-discount-text">Giảm ${formatPrice(product.gia * 0.1)}</div>
                                ${product.so_luong_kho > 0 
                                    ? `<div class="stock-status"><span style="color: green;">Còn hàng (${product.so_luong_kho})</span></div>`
                                    : `<div class="stock-status"><span style="color: red;">Hết hàng</span></div>`
                                }
                            </div>
                            <button class="buy-btn" onclick="addToCart('${product.ma_san_pham}', event)">Mua ngay</button>
                        </div>
                    </a>
                `).join('');

                // Render pagination
                renderPagination(pagination);
            } else {
                noProducts.style.display = 'block';
            }
        } catch (error) {
            console.error('Error loading products:', error);
            loading.style.display = 'none';
            noProducts.style.display = 'block';
            noProducts.innerHTML = `
                <i class="fa fa-exclamation-triangle"></i>
                <h3>Lỗi tải sản phẩm</h3>
                <p>Vui lòng thử lại sau.</p>
            `;
        }
    }

    // Render pagination
    function renderPagination(pagination) {
        const container = document.getElementById('pagination');
        const { page, total_pages } = pagination;

        if (total_pages <= 1) return;

        let html = '<div class="pagination">';

        // Previous
        if (page > 1) {
            html += `<a href="#" class="pagination-btn" onclick="loadProducts(${page - 1}); return false;">&laquo; Trước</a>`;
        } else {
            html += `<span class="pagination-btn disabled">&laquo; Trước</span>`;
        }

        // Page numbers
        const startPage = Math.max(1, page - 2);
        const endPage = Math.min(total_pages, page + 2);

        for (let i = startPage; i <= endPage; i++) {
            html += `<a href="#" class="pagination-btn ${i === page ? 'active' : ''}" 
                      onclick="loadProducts(${i}); return false;">${i}</a>`;
        }

        // Next
        if (page < total_pages) {
            html += `<a href="#" class="pagination-btn" onclick="loadProducts(${page + 1}); return false;">Sau &raquo;</a>`;
        } else {
            html += `<span class="pagination-btn disabled">Sau &raquo;</span>`;
        }

        html += '</div>';
        container.innerHTML = html;
    }

    // Format price
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
    }

    // Add to cart
    function addToCart(maSanPham, event) {
        event.preventDefault();
        alert('Thêm vào giỏ hàng: ' + maSanPham);
        // TODO: Implement add to cart via API
    }

    // Initialize
    loadProducts(1);
</script>
