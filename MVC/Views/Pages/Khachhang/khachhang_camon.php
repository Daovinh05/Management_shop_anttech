
<style>
    .order-success-wrapper {
        padding: 80px 0;
        background: #fff;
        margin: 20px 0;
    }

    .success-content {
        max-width: 800px;
    }

    .success-icon-circle {
        width: 70px;
        height: 70px;
        background-color: #333;
        color: #fff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 35px;
        margin-bottom: 25px;
    }

    .success-title {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }

    .success-desc {
        font-size: 16px;
        color: #555;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .success-links {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .success-links a {
        color: var(--blue-btn);
        font-weight: 500;
        font-size: 15px;
        padding: 10px 20px;
        border: 1px solid var(--blue-btn);
        border-radius: 4px;
        text-decoration: none;
    }

    .success-links a:hover {
        background-color: var(--blue-btn);
        color: white;
    }

    .payment-status {
        background-color: #d4edda;
        color: #155724;
        padding: 10px 15px;
        border-radius: 4px;
        margin: 15px 0;
        border: 1px solid #c3e6cb;
    }
</style>
<div class="order-success-wrapper">
    <div class="container">
        <div class="success-content">
            <div class="success-icon-circle"><i class="fa-solid fa-check"></i></div>
            <h1 class="success-title">Đặt hàng thành công!</h1>

            <?php if (isset($data['success_message'])): ?>
                <div class="payment-status">
                    <strong><?php echo htmlspecialchars($data['success_message']); ?></strong>
                </div>
            <?php endif; ?>

            <p class="success-desc">
                Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi.<br>
                Mã đơn hàng của bạn là: <strong><?php echo isset($data['ma_don_hang']) ? htmlspecialchars($data['ma_don_hang']) : 'N/A'; ?></strong><br>
                <?php
                if (isset($data['success_message']) && strpos($data['success_message'], 'thành công') !== false) {
                    echo 'Đơn hàng của bạn đã được thanh toán thành công.';
                } else {
                    echo 'Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.';
                }
                ?>
            </p>
            <div class="success-links">
                <a href="<?php echo $this->url('Khachhang'); ?>">Tiếp tục mua sắm</a>
                <a href="<?php echo $this->url('Khachhang/lichsumuahang'); ?>">Xem lịch sử đơn hàng</a>
            </div>
        </div>
    </div>
</div>