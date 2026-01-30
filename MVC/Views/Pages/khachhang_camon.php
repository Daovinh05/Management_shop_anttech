<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
            <div class="success-message">
                <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                <h2>Đặt hàng thành công!</h2>
                <p>Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi.</p>
                <p>Mã đơn hàng của bạn là: <strong><?php echo $data['ma_don_hang']; ?></strong></p>
                <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.</p>
                
                <div class="mt-4">
                    <a href="<?php echo $this->url('Khachhang'); ?>" class="btn btn-primary mr-2">Tiếp tục mua sắm</a>
                    <a href="<?php echo $this->url('Khachhang/lichsumuahang'); ?>" class="btn btn-info">Xem lịch sử đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-message {
    padding: 40px 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    margin-top: 30px;
}
</style>