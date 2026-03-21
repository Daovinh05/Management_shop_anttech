<?php
// VNPAY Configuration
// IMPORTANT: Replace these with your actual VNPAY credentials from your merchant account
define('VNPAY_TMNCODE', 'FE8GKNXS'); // Website ID provided by VNPAY
define('VNPAY_HASHSECRET', 'ZDFBA7R38IRP31HKIN1GBQP6OFPGHIDN'); // Secret key provided by VNPAY
define('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'); // Use sandbox for testing
define('VNPAY_RETURN_URL', 'http://localhost/Banhang/Khachhang/xulythanhtoan'); // Return URL after payment

// Check if configuration is properly set
if (VNPAY_TMNCODE === 'FE8GKNXS' || VNPAY_HASHSECRET === 'ZDFBA7R38IRP31HKIN1GBQP6OFPGHIDN') {
    error_log("VNPAY configuration error: Please update your TMN_CODE and HASH_SECRET in vnpay_config.php");
}