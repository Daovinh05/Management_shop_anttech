<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Banhang/MVC/Core/vnpay_config.php';

// class VnPayHelper
// {
//     public static function createPaymentUrl($orderInfo, $amount, $orderId, $bankCode = null, $language = 'vn')
//     {
//         // Check if configuration is properly set
//         if (VNPAY_TMNCODE === 'YOUR_VNP_TMNCODE' || VNPAY_HASHSECRET === 'YOUR_VNP_HASHSECRET') {
//             throw new Exception("VNPAY configuration error: Please update your TMN_CODE and HASH_SECRET in vnpay_config.php");
//         }

//         $vnp_Url = VNPAY_URL;
//         $vnp_Returnurl = VNPAY_RETURN_URL;
//         $vnp_TmnCode = VNPAY_TMNCODE;
//         $vnp_HashSecret = VNPAY_HASHSECRET;

//         $vnp_TxnRef = $orderId; // Mã đơn hàng
//         $vnp_OrderInfo = $orderInfo;
//         $vnp_OrderType = 'billpayment';
//         $vnp_Amount = $amount * 100; // Số tiền cần nhân với 100 để đúng định dạng
//         $vnp_Locale = $language;
//         $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

//         $inputData = array(
//             "vnp_Version" => "2.1.0",
//             "vnp_TmnCode" => $vnp_TmnCode,
//             "vnp_Amount" => $vnp_Amount,
//             "vnp_Command" => "pay",
//             "vnp_CreateDate" => date('YmdHis'),
//             "vnp_CurrCode" => "VND",
//             "vnp_IpAddr" => $vnp_IpAddr,
//             "vnp_Locale" => $vnp_Locale,
//             "vnp_OrderInfo" => $vnp_OrderInfo,
//             "vnp_OrderType" => $vnp_OrderType,
//             "vnp_ReturnUrl" => $vnp_Returnurl,
//             "vnp_TxnRef" => $vnp_TxnRef,
//         );

//         if (isset($bankCode) && $bankCode != "") {
//             $inputData['vnp_BankCode'] = $bankCode;
//         }

//         ksort($inputData);
//         $query = "";
//         $i = 0;
//         $hashdata = "";
//         foreach ($inputData as $key => $value) {
//             if ($i == 1) {
//                 $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
//             } else {
//                 $hashdata .= urlencode($key) . "=" . urlencode($value);
//                 $i = 1;
//             }
//             $query .= urlencode($key) . "=" . urlencode($value) . '&';
//         }

//         $vnp_Url = $vnp_Url . "?" . $query;
//         if (isset($vnp_HashSecret)) {
//             $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
//             $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
//         }

//         return $vnp_Url;
//     }

class VnPayHelper
{
    public static function createPaymentUrl($orderInfo, $amount, $orderId, $language = 'vn')
    {
        $vnp_Url = VNPAY_URL;
        $vnp_Returnurl = VNPAY_RETURN_URL;
        $vnp_TmnCode = VNPAY_TMNCODE;
        $vnp_HashSecret = VNPAY_HASHSECRET;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
            // "vnp_Locale" => $language,
            "vnp_Locale"    => "vn",
            "vnp_OrderInfo" => $orderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $orderId
        );

        ksort($inputData);

        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= urlencode($key) . "=" . urlencode($value) . '&';
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $hashdata = rtrim($hashdata, '&');
        $query = rtrim($query, '&');

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        return $vnp_Url . "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash;
    }



    public static function verifyPaymentReturn($getData)
    {
        // Check if configuration is properly set
        if (VNPAY_TMNCODE === 'YOUR_VNP_TMNCODE' || VNPAY_HASHSECRET === 'YOUR_VNP_HASHSECRET') {
            error_log("VNPAY configuration error: Please update your TMN_CODE and HASH_SECRET in vnpay_config.php");
            return false; // Return false if configuration is not set properly
        }

        $vnp_HashSecret = VNPAY_HASHSECRET;

        $vnp_SecureHash = $getData['vnp_SecureHash'];
        $inputData = array();
        foreach ($getData as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        return $vnp_SecureHash == $secureHash;
    }
}