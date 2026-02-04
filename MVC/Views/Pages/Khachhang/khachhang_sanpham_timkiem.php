<style>
    .search-results-header {
        margin: 20px 0;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .search-query {
        color: var(--primary-green);
        font-weight: 700;
    }

    .search-results-count {
        color: #666;
        font-size: 14px;
        margin-top: 5px;
    }

    .no-results {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .no-results i {
        font-size: 60px;
        color: #ccc;
        margin-bottom: 15px;
    }

    .no-results h3 {
        color: #666;
        margin-bottom: 10px;
    }

    .no-results p {
        color: #888;
        margin-bottom: 20px;
    }

    .suggestions {
        margin-top: 20px;
    }

    .suggestion-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .suggestion-products {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding: 10px 0;
    }

    .suggestion-product {
        min-width: 150px;
        text-align: center;
    }

    .suggestion-product-img {
        width: 100%;
        height: 120px;
        object-fit: contain;
        margin-bottom: 8px;
    }

    .suggestion-product-name {
        font-size: 12px;
        color: #555;
    }

    /* Cập nhật layout sản phẩm để hiển thị dạng lưới */
    .products-grid {
        margin: 20px 0;
    }

    .products-grid-inner {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .product-card {
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: center;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .product-img {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        background-color: #f9f9f9;
    }

    .product-img img {
        max-width: 100%;
        max-height: 150px;
        object-fit: contain;
    }

    .product-info {
        padding: 15px;
    }

    .product-name {
        font-size: 14px;
        font-weight: 500;
        margin: 0 0 8px 0;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-price {
        color: var(--tet-red);
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .product-meta {
        font-size: 12px;
        color: #888;
    }

    .product-category {
        color: var(--primary-green);
    }
</style>

<div class="container">
    <?php
    // $data['dssp'] is already an array from the controller
    $results = $data['dssp'];
    $count = count($results);
    ?>

    <div class="search-results-header">
        <h2>Kết quả tìm kiếm cho: <span class="search-query">"<?php echo htmlspecialchars($data['search_query']); ?>"</span></h2>
        <div class="search-results-count"><?php echo $count; ?> sản phẩm được tìm thấy</div>
    </div>

    <?php if ($count > 0): ?>
        <!-- Hiển thị sản phẩm tìm thấy -->
        <div class="products-grid">
            <div class="container">
                <div class="products-grid-inner">
                    <?php foreach ($results as $row): ?>
                        <?php
                        // Lấy hình ảnh biến thể đầu tiên nếu có, nếu không thì lấy hình ảnh sản phẩm
                        $sql_img = "SELECT img_bien_the FROM bien_the WHERE ma_san_pham = '" . $row['ma_san_pham'] . "' AND img_bien_the != '' LIMIT 1";
                        $result_img = mysqli_query($this->model("SanPham_m")->con, $sql_img);
                        $img_product = mysqli_fetch_assoc($result_img);
                        
                        $img_url = !empty($img_product['img_bien_the']) 
                            ? 'Public/Pictures/bien_the/' . $img_product['img_bien_the'] 
                            : (!empty($row['img_hinh_anh']) 
                                ? 'Public/Pictures/sanpham/' . $row['img_hinh_anh'] 
                                : 'Public/Images/no-image.png');
                        ?>
                        
                        <div class="product-card">
                            <a href="<?php echo $this->url('Khachhang/chitietsanpham/' . $row['ma_san_pham']); ?>">
                                <div class="product-img">
                                    <img src="<?php echo $img_url; ?>" alt="<?php echo htmlspecialchars($row['ten_san_pham']); ?>">
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?php echo htmlspecialchars($row['ten_san_pham']); ?></h3>
                                    <?php
                                    // Lấy giá thấp nhất và cao nhất của các biến thể
                                    $sql_gia = "SELECT MIN(gia) as gia_min, MAX(gia) as gia_max FROM bien_the WHERE ma_san_pham = '" . $row['ma_san_pham'] . "'";
                                    $result_gia = mysqli_query($this->model("SanPham_m")->con, $sql_gia);
                                    $gia = mysqli_fetch_assoc($result_gia);
                                    
                                    if ($gia['gia_min'] == $gia['gia_max']):
                                    ?>
                                        <div class="product-price"><?php echo number_format($gia['gia_min'], 0, ',', '.'); ?>₫</div>
                                    <?php else: ?>
                                        <div class="product-price"><?php echo number_format($gia['gia_min'], 0, ',', '.') . ' - ' . number_format($gia['gia_max'], 0, ',', '.'); ?>₫</div>
                                    <?php endif; ?>
                                    
                                    <div class="product-meta">
                                        <span class="product-category"><?php echo htmlspecialchars($row['ten_danh_muc']); ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Không tìm thấy sản phẩm -->
        <div class="no-results">
            <i class="fa-solid fa-search"></i>
            <h3>Không tìm thấy sản phẩm nào</h3>
            <p>Chúng tôi không tìm thấy sản phẩm nào phù hợp với từ khóa "<strong><?php echo htmlspecialchars($data['search_query']); ?></strong>"</p>
            <p>Vui lòng thử lại với từ khóa khác</p>

            <div class="suggestions">
                <div class="suggestion-title">Một số gợi ý tìm kiếm:</div>
                <div class="suggestion-products">
                    <?php
                    // Lấy một số sản phẩm ngẫu nhiên để gợi ý
                    $sql_suggest = "SELECT * FROM san_pham ORDER BY RAND() LIMIT 7";
                    $suggest_products = mysqli_query($this->model("SanPham_m")->con, $sql_suggest);
                    while ($suggest = mysqli_fetch_assoc($suggest_products)):
                        $sql_img = "SELECT img_bien_the FROM bien_the WHERE ma_san_pham = '" . $suggest['ma_san_pham'] . "' AND img_bien_the != '' LIMIT 1";
                        $result_img = mysqli_query($this->model("SanPham_m")->con, $sql_img);
                        $img_product = mysqli_fetch_assoc($result_img);

                        $img_url = !empty($img_product['img_bien_the'])
                            ? 'Public/Pictures/bien_the/' . $img_product['img_bien_the']
                            : (!empty($suggest['img_hinh_anh'])
                                ? 'Public/Pictures/sanpham/' . $suggest['img_hinh_anh']
                                : 'Public/Images/no-image.png');
                    ?>
                        <a href="<?php echo $this->url('Khachhang/chitietsanpham/' . $suggest['ma_san_pham']); ?>" class="suggestion-product">
                            <img src="<?php echo $img_url; ?>" alt="<?php echo htmlspecialchars($suggest['ten_san_pham']); ?>" class="suggestion-product-img">
                            <div class="suggestion-product-name"><?php echo htmlspecialchars($suggest['ten_san_pham']); ?></div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>