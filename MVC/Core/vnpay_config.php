<?php
// VNPAY Configuration
// IMPORTANT: Replace these with your actual VNPAY credentials from your merchant account
define('VNPAY_TMNCODE', 'FE8GKNXS'); // Website ID provided by VNPAY (replace with actual TMNCODE)
define('VNPAY_HASHSECRET', 'D124UIMTTI9E22DFF05VQWJ4CJOACJU2'); // Secret key provided by VNPAY (replace with actual HASHSECRET)
define('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'); // Use sandbox for testing
define('VNPAY_RETURN_URL', '<?php echo BASE_URL; ?>Khachhang/xulythanhtoan'); // Return URL after payment

// Check if configuration is properly set
if (VNPAY_TMNCODE === 'FE8GKNXS' || VNPAY_HASHSECRET === 'D124UIMTTI9E22DFF05VQWJ4CJOACJU2') {
    error_log("VNPAY configuration error: Please update your TMN_CODE and HASH_SECRET in vnpay_config.php");
}