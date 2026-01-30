<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Chào mừng đến với Cửa Hàng Điện Thoại</h2>
            <p>Khám phá các sản phẩm điện thoại chất lượng cao với mức giá tốt nhất thị trường.</p>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Danh mục sản phẩm -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Danh mục sản phẩm</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                        if (isset($data['dsdm']) && mysqli_num_rows($data['dsdm']) > 0) {
                            while ($dm = mysqli_fetch_assoc($data['dsdm'])) {
                                echo '<li class="list-group-item">';
                                echo '<a href="' . $this->url('Khachhang/sanpham_theo_danhmuc/' . $dm['ma_danh_muc']) . '">' . $dm['ten_danh_muc'] . '</a>';
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Danh sách sản phẩm -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Sản phẩm nổi bật</h4>
                <div>
                    <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                </div>
            </div>
            
            <div class="row">
                <?php
                if (isset($data['dssp']) && mysqli_num_rows($data['dssp']) > 0) {
                    while ($sp = mysqli_fetch_assoc($data['dssp'])) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card h-100">';
                        
                        // Hiển thị hình ảnh nếu có, nếu không thì dùng hình mặc định
                        $img_src = !empty($sp['img_hinh_anh']) ? $sp['img_hinh_anh'] : $this->url('Public/Images/no-image.png');
                        echo '<img src="' . $img_src . '" class="card-img-top" alt="' . $sp['ten_san_pham'] . '" style="height: 200px; object-fit: cover;">';
                        
                        echo '<div class="card-body d-flex flex-column">';
                        echo '<h5 class="card-title">' . $sp['ten_san_pham'] . '</h5>';
                        echo '<p class="card-text flex-grow-1">Danh mục: ' . $sp['ten_danh_muc'] . '</p>';
                        echo '<p class="card-text"><strong>' . number_format($sp['gia'], 0, ',', '.') . ' VNĐ</strong></p>';
                        echo '<a href="' . $this->url('Khachhang/chitietsanpham/' . $sp['ma_san_pham']) . '" class="btn btn-primary mt-auto">Xem chi tiết</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-12"><p class="text-center">Hiện chưa có sản phẩm nào</p></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>