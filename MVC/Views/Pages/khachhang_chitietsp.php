<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $this->url('Khachhang'); ?>">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="#"><?php echo $data['san_pham']['ten_danh_muc']; ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $data['san_pham']['ten_san_pham']; ?></li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
            <?php
            $img_src = !empty($data['san_pham']['img_hinh_anh']) ? $data['san_pham']['img_hinh_anh'] : $this->url('Public/Images/no-image.png');
            ?>
            <img src="<?php echo $img_src; ?>" class="img-fluid" alt="<?php echo $data['san_pham']['ten_san_pham']; ?>">
        </div>
        
        <div class="col-md-6">
            <h2><?php echo $data['san_pham']['ten_san_pham']; ?></h2>
            
            <!-- Hiển thị đánh giá trung bình -->
            <?php if ($data['avg_rating']): ?>
                <div class="rating">
                    <?php
                    $avg_rating = round($data['avg_rating'], 1);
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $avg_rating) {
                            echo '<span class="star filled">★</span>';
                        } else {
                            echo '<span class="star">☆</span>';
                        }
                    }
                    echo " (" . $avg_rating . "/5)";
                    ?>
                </div>
            <?php endif; ?>
            
            <hr>
            
            <div class="product-info">
                <p><strong>Thương hiệu:</strong> <?php echo $data['san_pham']['ten_thuong_hieu']; ?></p>
                <p><strong>Nhà cung cấp:</strong> <?php echo $data['san_pham']['ten_nha_cung_cap']; ?></p>
                <p><strong>Danh mục:</strong> <?php echo $data['san_pham']['ten_danh_muc']; ?></p>
            </div>
            
            <hr>
            
            <!-- Biến thể sản phẩm -->
            <h4>Phiên bản</h4>
            <div class="variants">
                <?php
                if (mysqli_num_rows($data['bien_the']) > 0) {
                    while ($bt = mysqli_fetch_assoc($data['bien_the'])) {
                        echo '<div class="variant-item mb-2">';
                        echo '<label>';
                        echo '<input type="radio" name="ma_bien_the" value="' . $bt['ma_bien_the'] . '" required> ';
                        echo '<strong>' . $bt['ten_bien_the'] . '</strong> - ';
                        if ($bt['mau_sac']) echo 'Màu: ' . $bt['mau_sac'] . ', ';
                        if ($bt['ram']) echo 'RAM: ' . $bt['ram'] . ', ';
                        if ($bt['dung_luong']) echo 'Bộ nhớ: ' . $bt['dung_luong'] . ', ';
                        echo '<span class="price"><strong>' . number_format($bt['gia'], 0, ',', '.') . ' VNĐ</strong></span>';
                        echo ' (Còn ' . $bt['so_luong_kho'] . ' sản phẩm)';
                        echo '</label>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Hiện chưa có phiên bản nào cho sản phẩm này.</p>';
                }
                ?>
            </div>
            
            <hr>
            
            <div class="actions">
                <?php if (mysqli_num_rows($data['bien_the']) > 0): ?>
                    <button type="button" class="btn btn-success btn-lg" onclick="addToCart()">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="fas fa-shopping-cart"></i> Tạm hết hàng
                    </button>
                <?php endif; ?>
                
                <button type="button" class="btn btn-primary btn-lg ml-2">
                    <i class="fas fa-bolt"></i> Mua ngay
                </button>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <!-- Tab điều hướng -->
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab">Mô tả sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab">Đánh giá</a>
                </li>
            </ul>
            
            <div class="tab-content" id="productTabContent">
                <!-- Mô tả sản phẩm -->
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="mt-3">
                        <h5>Thông tin chi tiết sản phẩm</h5>
                        <p>Đây là phần mô tả chi tiết cho sản phẩm <?php echo $data['san_pham']['ten_san_pham']; ?>. 
                           Sẽ được cập nhật với thông tin cụ thể về tính năng, thông số kỹ thuật, v.v.</p>
                    </div>
                </div>
                
                <!-- Đánh giá -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="mt-3">
                        <h5>Đánh giá sản phẩm</h5>
                        
                        <?php if (mysqli_num_rows($data['danh_gia']) > 0): ?>
                            <?php while ($dg = mysqli_fetch_assoc($data['danh_gia'])): ?>
                                <div class="review-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between">
                                        <h6><?php echo $dg['full_name']; ?></h6>
                                        <small class="text-muted"><?php echo $this->formatDate($dg['ngay_danh_gia']); ?></small>
                                    </div>
                                    
                                    <div class="rating mb-2">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $dg['so_sao']) {
                                                echo '<span class="star filled">★</span>';
                                            } else {
                                                echo '<span class="star">☆</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    
                                    <p><?php echo $dg['noi_dung']; ?></p>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                        <?php endif; ?>
                        
                        <!-- Form đánh giá (chỉ hiển thị nếu đã đăng nhập) -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="mt-4">
                                <h6>Viết đánh giá của bạn</h6>
                                <form>
                                    <div class="form-group">
                                        <label>Số sao:</label>
                                        <div>
                                            <input type="radio" name="rating" value="1"> ★
                                            <input type="radio" name="rating" value="2"> ★★
                                            <input type="radio" name="rating" value="3"> ★★★
                                            <input type="radio" name="rating" value="4"> ★★★★
                                            <input type="radio" name="rating" value="5"> ★★★★★
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nội dung đánh giá:</label>
                                        <textarea class="form-control" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="mt-4">
                                <p><a href="<?php echo $this->url('Login'); ?>">Đăng nhập</a> để viết đánh giá.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addToCart() {
    // Lấy biến thể được chọn
    const selectedVariant = document.querySelector('input[name="ma_bien_the"]:checked');
    
    if (!selectedVariant) {
        alert('Vui lòng chọn một phiên bản sản phẩm!');
        return;
    }
    
    // Chuyển hướng đến trang thêm vào giỏ hàng
    window.location.href = '<?php echo $this->url('Khachhang/themvaogio/'); ?>' + selectedVariant.value;
}
</script>