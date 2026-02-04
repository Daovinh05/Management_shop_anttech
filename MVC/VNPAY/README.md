# Hướng Dẫn Cấu Hình Và Sử Dụng VNPAY

## 1. Cấu Hình VNPAY

### Bước 1: Đăng Ký Tài Khoản VNPAY
- Truy cập https://sandbox.vnpayment.vn/ để đăng ký tài khoản merchant
- Sau khi đăng ký, bạn sẽ nhận được:
  - `TMN_CODE` (Mã website)
  - `HASH_SECRET` (Chuỗi bí mật)

### Bước 2: Cập Nhật Cấu Hình
Sửa file `MVC/Core/vnpay_config.php` với thông tin nhận được:

```php
define('VNPAY_TMNCODE', 'MÃ_TMNCODE_CỦA_BẠN'); 
define('VNPAY_HASHSECRET', 'CHUỖI_HASH_SECRET_CỦA_BẠN');
```

## 2. Cách Hoạt Động

### Quy Trình Thanh Toán:
1. Khách hàng chọn phương thức thanh toán "VNPAY QR - Thanh toán qua mã QR"
2. Nhấn nút "ĐẶT HÀNG"
3. Hệ thống sẽ tạo yêu cầu thanh toán và chuyển hướng đến cổng VNPAY
4. Khách hàng thực hiện thanh toán trên cổng VNPAY
5. VNPAY chuyển hướng khách hàng về lại website (URL: http://localhost/Banhang/Khachhang/xulythanhtoan) với kết quả thanh toán
6. Hàm `xulythanhtoan()` xử lý kết quả, cập nhật trạng thái đơn hàng thành "hoan_thanh" và hiển thị trang xác nhận

### Các File Liên Quan:
- `MVC/Controllers/Khachhang.php`: Xử lý logic thanh toán
- `MVC/Core/vnpay_config.php`: Cấu hình VNPAY
- `MVC/Core/VnPayHelper.php`: Thư viện hỗ trợ thanh toán
- `MVC/Views/Pages/Khachhang/khachhang_thanhtoan.php`: Trang thanh toán

## 3. Lưu Ý Khi Phát Triển

- Luôn sử dụng môi trường sandbox khi phát triển: `https://sandbox.vnpayment.vn/paymentv2/vpcpay.html`
- Khi chuyển sang môi trường production, thay đổi URL trong file cấu hình
- Bảo mật chuỗi `HASH_SECRET`, không để lộ ra ngoài mã nguồn
- Kiểm tra kỹ quá trình xác minh chữ ký (checksum) để đảm bảo an toàn

## 4. Kiểm Tra Tích Hợp

Để kiểm tra tích hợp hoạt động đúng:
1. Đặt một đơn hàng với phương thức thanh toán VNPAY
2. Kiểm tra việc chuyển hướng đến cổng thanh toán
3. Kiểm tra việc quay trở lại website sau khi thanh toán
4. Kiểm tra việc cập nhật trạng thái đơn hàng trong hệ thống

## 5. Gỡ Rối

Nếu gặp lỗi:
- Kiểm tra lại `TMN_CODE` và `HASH_SECRET`
- Đảm bảo URL callback (return URL) được cấu hình đúng trong tài khoản VNPAY
- Kiểm tra log lỗi trong hệ thống để xác định nguyên nhân