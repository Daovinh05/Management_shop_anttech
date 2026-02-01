<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
            <div class="error-message">
                <i class="fas fa-times-circle fa-5x text-danger mb-3"></i>
                <h2>Thanh toán thất bại!</h2>
                <p><?php echo isset($data['error_message']) ? htmlspecialchars($data['error_message']) : 'Đã có lỗi xảy ra trong quá trình thanh toán.'; ?></p>
                
                <div class="mt-4">
                    <a href="<?php echo $this->url('Khachhang/giohang'); ?>" class="btn btn-primary mr-2">Quay lại giỏ hàng</a>
                    <a href="<?php echo $this->url('Khachhang/thanhtoan'); ?>" class="btn btn-warning">Thử lại thanh toán</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-message {
    padding: 40px 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    margin-top: 30px;
}
</style>