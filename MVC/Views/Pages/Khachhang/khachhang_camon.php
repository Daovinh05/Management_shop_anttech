
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
    }

    .success-links a {
        color: var(--blue-btn);
        font-weight: 500;
        font-size: 15px;
    }

    .success-links a:hover {
        text-decoration: underline;
    }
</style>
<div class="order-success-wrapper">
    <div class="container">
        <div class="success-content">
            <div class="success-icon-circle"><i class="fa-solid fa-check"></i></div>
            <h1 class="success-title">Đặt hàng thành công!</h1>
            <p class="success-desc">
                Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi.<br>
                Mã đơn hàng của bạn là: <strong>DH65</strong><br>
                Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.
            </p>
            <div class="success-links">
                <a href="<?php echo $this->url('Khachhang'); ?>" class="btn btn-primary mr-2">Tiếp tục mua sắm</a>
                <a href="<?php echo $this->url('Khachhang/lichsumuahang'); ?>" class="btn btn-info">Xem lịch sử đơn hàng</a>
            </div>
        </div>
    </div>
</div>