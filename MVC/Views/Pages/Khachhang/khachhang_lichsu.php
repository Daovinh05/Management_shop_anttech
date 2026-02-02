<?php
// Include necessary helpers
include_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Classes/TimezoneHelper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Classes/UrlHelper.php';
?>


<div class="order-history-wrapper">
    <h1 class="page-title">Đơn hàng của bạn</h1>

    <div class="order-tabs">
        <div class="tab-item active">Tất cả (<?php echo count($data['don_hang']); ?>)</div>
        <div class="tab-item">Chờ xác nhận (0)</div>
        <div class="tab-item">Đã xác nhận (0)</div>
        <div class="tab-item">Đang giao (0)</div>
        <div class="tab-item">Hoàn thành (0)</div>
        <div class="tab-item">Đã hủy (0)</div>
    </div>

    <?php if ($data['don_hang'] && count($data['don_hang']) > 0): ?>
        <?php foreach ($data['don_hang'] as $dh): ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-id">Đơn hàng #<?php echo $dh['ma_don_hang']; ?></span>
                        <span class="order-date">Đặt ngày: <?php echo $this->formatDate($dh['ngay_tao']); ?></span>
                    </div>
                    <span class="status-badge
                        <?php
                        switch ($dh['trang_thai_don_hang']) {
                            case 'cho_duyet':
                                echo 'status-confirmed';
                                break;
                            case 'dang_giao':
                                echo 'status-shipping';
                                break;
                            case 'hoan_thanh':
                                echo 'status-confirmed';
                                break;
                            case 'da_huy':
                                echo 'status-cancelled';
                                break;
                            default:
                                echo 'status-cancelled';
                        }
                        ?>">
                        <?php
                        switch ($dh['trang_thai_don_hang']) {
                            case 'cho_duyet':
                                echo 'Chờ xác nhận';
                                break;
                            case 'dang_giao':
                                echo 'Đang giao hàng';
                                break;
                            case 'hoan_thanh':
                                echo 'Đã hoàn thành';
                                break;
                            case 'da_huy':
                                echo 'Đã hủy';
                                break;
                            default:
                                echo ucfirst(str_replace('_', ' ', $dh['trang_thai_don_hang']));
                        }
                        ?>
                    </span>
                </div>

                <div class="order-body-flex">

                    <div class="order-product-list">
                        <?php
                        $chi_tiet_don_hang = $dh['chi_tiet'];
                        if ($chi_tiet_don_hang && count($chi_tiet_don_hang) > 0):
                            foreach ($chi_tiet_don_hang as $ct):
                        ?>
                                <div class="order-product">
                                    <img src="<?php echo !empty($ct['hinh_anh']) ? '/Banhang/Public/Pictures/bien_the/' . $ct['hinh_anh'] : 'https://placehold.co/80x80?text=SP'; ?>"
                                        alt="<?php echo $ct['ten_san_pham']; ?>" class="product-thumb">
                                    <div class="product-info">
                                        <span
                                            class="product-name"><?php echo $ct['ten_san_pham'] ? $ct['ten_san_pham'] : 'Sản phẩm đã xóa'; ?></span>
                                        <div class="product-meta">Số lượng: <?php echo $ct['so_luong']; ?></div>
                                        <?php if ($ct['ten_bien_the']): ?>
                                            <div class="product-meta">Biến thể: <?php echo $ct['ten_bien_the']; ?></div>
                                        <?php endif; ?>
                                        <div class="product-price">
                                            <?php echo number_format($ct['gia_luc_mua'], 0, ',', '.'); ?>₫</div>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <div class="order-product">
                                <img src="https://placehold.co/80x80?text=SP" alt="Sản phẩm" class="product-thumb">
                                <div class="product-info">
                                    <span class="product-name">Không có sản phẩm</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="order-actions-right">
                        <div class="total-money">
                            <span class="total-label">Tổng tiền:</span>
                            <span class="total-value"><?php echo number_format($dh['tong_tien_hang'], 0, ',', '.'); ?>₫</span>
                        </div>
                        <a href="<?php echo $this->url('Khachhang/chitietdonhang/' . $dh['ma_don_hang']); ?>"
                            class="btn-detail"><i class="fa-regular fa-eye"></i> Xem chi tiết</a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
            <h4>Bạn chưa có đơn hàng nào</h4>
            <p>Hãy bắt đầu mua sắm để có đơn hàng đầu tiên</p>
            <a href="<?php echo $this->url('Khachhang'); ?>" class="btn btn-primary">Mua sắm ngay</a>
        </div>
    <?php endif; ?>
</div>

<script>
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabItems = document.querySelectorAll('.tab-item');
        tabItems.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabItems.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
</script>