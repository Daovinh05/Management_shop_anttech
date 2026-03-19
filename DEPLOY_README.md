# HƯỚNG DẪN DEPLOY LÊN HOSTING

## Cấu trúc thư mục cần chuyển

Toàn bộ nội dung thư mục `Banhang` cần được chuyển vào `public_html` trên hosting:

```
public_html/
├── index.php
├── .htaccess
├── MVC/
│   ├── bridge.php
│   ├── Core/
│   │   ├── Config.php      ← File mới tạo
│   │   ├── app.php
│   │   ├── controller.php
│   │   └── connectDB.php
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── VNPAY/
├── Public/
│   ├── Classes/
│   ├── Css/
│   ├── Images/
│   ├── Pictures/
│   └── ...
└── thanhtoan.html
```

## Các bước thực hiện

### 1. Upload files lên hosting

1. Truy cập hosting qua FTP hoặc File Manager
2. Upload TOÀN BỘ nội dung thư mục `Banhang` vào `public_html`
3. Đảm bảo cấu trúc thư mục được giữ nguyên

### 2. Cấu hình database

1. Tạo database mới trên hosting (qua phpMyAdmin hoặc control panel)
2. Import file `banhang.sql` hoặc `phone_store_v2.sql` vào database mới
3. Cập nhật thông tin database trong file `MVC/Core/Config.php`:

```php
define('DB_HOST', 'localhost');        // Thường là localhost
define('DB_NAME', 'ten_database_moi'); // Đổi thành tên database trên hosting
define('DB_USER', 'username_moi');     // Đổi thành username database trên hosting
define('DB_PASS', 'password_moi');     // Đổi thành password database trên hosting
define('DB_CHARSET', 'utf8mb4');
```

### 3. Cấu hình upload permissions

Đảm bảo các thư mục upload có permission 755 hoặc 777:

```
Public/Pictures/
Public/Images/
```

### 4. Kiểm tra .htaccess

File `.htaccess` đã được cấu hình sẵn để hoạt động với hosting. Nếu hosting không hỗ trợ mod_rewrite, liên hệ nhà cung cấp.

### 5. Kiểm tra SSL (HTTPS)

Code đã tự động detect HTTPS. Nếu hosting có SSL, ứng dụng sẽ tự động sử dụng https://

### 6. Test ứng dụng

1. Truy cập domain của bạn
2. Kiểm tra các chức năng:
   - Trang chủ
   - Đăng nhập/Đăng ký
   - Giỏ hàng
   - Thanh toán
   - Admin panel (nếu có)

## Các lỗi thường gặp

### Lỗi 404 khi truy cập các trang
- Kiểm tra file .htaccess có được upload không
- Đảm bảo mod_rewrite được bật trên hosting

### Lỗi database connection
- Kiểm tra thông tin database trong Config.php
- Đảm bảo database đã được tạo và import SQL

### Lỗi không hiển thị ảnh
- Kiểm tra đường dẫn ảnh trong database
- Đảm bảo thư mục Public/Pictures có permission đúng

### Lỗi 500 Internal Server Error
- Kiểm tra log lỗi của hosting
- Đảm bảo PHP version tương thích (khuyến nghị PHP 7.4+)

## Lưu ý quan trọng

1. **Không sửa các file trong Public/Classes/** trừ khi cần thiết
2. **Sao lưu database** trước khi deploy
3. **Test trên local** trước khi upload
4. **Xóa file update_urls.php** sau khi sử dụng (không cần thiết trên production)

## Hỗ trợ

Nếu gặp vấn đề, kiểm tra:
- Error logs của hosting
- PHP error logs
- Browser console (F12)
