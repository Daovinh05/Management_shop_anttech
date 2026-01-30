<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Lịch sử mua hàng</h2>
            
            <?php if ($data['don_hang'] && mysqli_num_rows($data['don_hang']) > 0): ?>
                <?php while ($dh = mysqli_fetch_assoc($data['don_hang'])): ?>
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Đơn hàng: <?php echo $dh['ma_don_hang']; ?></strong>
                                <br>
                                <small class="text-muted">
                                    Ngày đặt: <?php echo $this->formatDate($dh['ngay_tao']); ?>
                                    | Trạng thái: 
                                    <span class="badge 
                                        <?php 
                                        switch($dh['trang_thai_don_hang']) {
                                            case 'cho_duyet': echo 'badge-warning'; break;
                                            case 'dang_giao': echo 'badge-info'; break;
                                            case 'hoan_thanh': echo 'badge-success'; break;
                                            case 'da_huy': echo 'badge-danger'; break;
                                            default: echo 'badge-secondary';
                                        }
                                        ?>">
                                        <?php 
                                        switch($dh['trang_thai_don_hang']) {
                                            case 'cho_duyet': echo 'Chờ duyệt'; break;
                                            case 'dang_giao': echo 'Đang giao'; break;
                                            case 'hoan_thanh': echo 'Hoàn thành'; break;
                                            case 'da_huy': echo 'Đã hủy'; break;
                                            default: echo ucfirst($dh['trang_thai_don_hang']);
                                        }
                                        ?>
                                    </span>
                                </small>
                            </div>
                            <a href="<?php echo $this->url('Khachhang/chitietdonhang/' . $dh['ma_don_hang']); ?>" class="btn btn-sm btn-outline-primary">
                                Xem chi tiết
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p><strong>Người nhận:</strong> <?php echo $dh['ten_nguoi_nhan']; ?></p>
                                    <p><strong>Địa chỉ:</strong> <?php echo $dh['dia_chi']; ?></p>
                                    <p><strong>Số điện thoại:</strong> <?php echo $dh['so_dien_thoai']; ?></p>
                                </div>
                                <div class="col-md-4 text-md-right">
                                    <p><strong>Tổng tiền:</strong> <?php echo number_format($dh['tong_tien_hang'], 0, ',', '.'); ?> VNĐ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h4>Bạn chưa có đơn hàng nào</h4>
                    <p>Hãy bắt đầu mua sắm để có đơn hàng đầu tiên</p>
                    <a href="<?php echo $this->url('Khachhang'); ?>" class="btn btn-primary">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>