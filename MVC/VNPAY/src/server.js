const express = require('express');
const cors = require('cors'); // Thêm dòng này
const path = require('path');
const app = express();
const port = 3000;

app.use(cors()); // Cho phép gọi API từ bên ngoài
app.use(express.json()); // Để đọc dữ liệu JSON từ body (nếu cần)
app.use(express.static(path.join(__dirname))); // Serve static files from src directory

const { VNPay, ignoreLogger, ProductCode, VnpLocale, dateFormat } = require('vnpay');

app.post('/api/create-qr', async (req, res) => {
    const vnpay = new VNPay({
        tmnCode: 'FE8GKNXS',
        secureSecret: 'D124UIMTTI9E22DFF05VQWJ4CJOACJU2',
        vnpayHost: 'https://sandbox.vnpayment.vn',
        testMode: true,
        hashAlgorithm: 'SHA512',
        loggerFn: ignoreLogger
    });

    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);

    // Bạn có thể lấy số tiền từ req.body.amount gửi từ HTML lên
    const amount = req.body.amount || 18000000; 

    const paymentUrl = await vnpay.buildPaymentUrl({
        vnp_Amount: amount,
        vnp_IpAddr: req.headers['x-forwarded-for'] || req.socket.remoteAddress, // Lấy IP thật
        vnp_TxnRef: Date.now().toString(), // Số hóa đơn duy nhất
        vnp_OrderInfo: `Thanh toan don hang ${Date.now()}`,
        vnp_OrderType: ProductCode.Other,
        vnp_ReturnUrl: 'http://localhost:3000/api/check-payment-vnpay', // Trang nhận kết quả
        vnp_Locale: VnpLocale.VN,
        vnp_CreateDate: dateFormat(new Date()),
        vnp_ExpireDate: dateFormat(tomorrow),
    });

    // Trả về URL để Client tự chuyển hướng
    return res.status(201).json({ paymentUrl });
});

// Giữ nguyên phần check-payment và listen...

app.get('/api/check-payment-vnpay', (req, res) => {
    const vnp_Params = req.query;
    const responseCode = vnp_Params['vnp_ResponseCode'];

    // '00' có nghĩa là giao dịch thành công trong hệ thống VNPAY
    if (responseCode === '00') {
        // Chuyển hướng trình duyệt của khách về trang thông báo thành công
        // Bạn có thể gửi kèm mã giao dịch để hiển thị
        res.redirect(`/success.html?vnp_TransactionNo=${vnp_Params['vnp_TransactionNo']}&vnp_Amount=${vnp_Params['vnp_Amount']/100}`);
    } else {
        // Nếu thất bại (người dùng hủy hoặc lỗi thẻ)
        res.redirect(`/fail.html`); // Assuming you might create a fail.html later
    }
});

app.listen(port, () => {
    console.log(`Example app listening on port ${port}`)
})
