# HƯỚNG DẪN DEPLOY LÊN HOSTING

## Tổng quan các thay đổi

Dự án đã được fix toàn bộ vấn đề về đường dẫn và lỗi đăng nhập. Dưới đây là các thay đổi chính:

### 1. Files đã được sửa

#### Core Files:
- **MVC/Core/Config.php**: Cải thiện hàm `getBaseUrl()` để tự động detect base URL cho cả localhost và hosting
- **MVC/Controllers/Login.php**: Fix lỗi AJAX response - giờ luôn trả về JSON đúng format khi là AJAX request
- **MVC/Controllers/Khachhang.php**: Thay thế tất cả đường dẫn cứng `http://localhost/Banhang/` bằng `$this->url()` và `BASE_URL`

#### View Files (đã được replace hàng loạt):
- Tất cả file trong `MVC/Views/Pages/` đã được thay thế đường dẫn cứng
- Các file JavaScript đã được cập nhật để sử dụng `BASE_URL` thay vì đường dẫn cứng

### 2. Cách deploy lên hosting

#### Bước 1: Chuẩn bị file
1. Nén toàn bộ thư mục `Banhang` thành file ZIP
2. Hoặc dùng FTP client (như FileZilla) để upload trực tiếp

#### Bước 2: Upload lên hosting
1. Giải nén file ZIP trên máy tính
2. Upload **TẤT CẢ** nội dung trong thư mục `Banhang` vào thư mục `public_html` trên hosting
   - Lưu ý: Không upload thư mục `Banhang`, mà upload **nội dung** của nó

#### Bước 3: Cấu hình database
1. Tạo database mới trên hosting (thông qua cPanel hoặc DirectAdmin)
2. Import file `banhang.sql` vào database mới
3. Cập nhật thông tin database trong file `MVC/Core/Config.php`:

```php
define('DB_HOST', 'localhost');           // Thường là localhost
define('DB_NAME', 'ten_database_cua_ban'); // Tên database trên hosting
define('DB_USER', 'username_cua_ban');     // Username database trên hosting
define('DB_PASS', 'mat_khau_cua_ban');     // Password database trên hosting
```

#### Bước 4: Cấu hình base URL (nếu cần)
Thông thường ứng dụng sẽ tự động detect base URL. Nếu gặp vấn đề, bạn có thể cấu hình thủ công:

Mở file `MVC/Core/Config.php` và thêm dòng này **trước** hàm `getBaseUrl()`:

```php
// Cấu hình thủ công base URL (bỏ comment nếu cần)
// define('BASE_URL_MANUAL', 'https://ten-mien-cua-ban.com/');
```

#### Bước 5: Kiểm tra permissions
Đảm bảo các thư mục sau có permission 755 hoặc 777:
- `Public/Pictures/` (và các thư mục con)
- `Public/Uploads/` (nếu có)

### 3. Kiểm tra sau khi deploy

1. **Truy cập trang chủ**: `https://ten-mien-cua-ban.com/`
2. **Đăng nhập**: Thử đăng nhập với tài khoản admin
3. **Kiểm tra các chức năng**:
   - Xem sản phẩm
   - Thêm vào giỏ hàng
   - Thanh toán
   - Upload hình ảnh

### 4. Các lỗi thường gặp và cách khắc phục

#### Lỗi 1: "Server returned invalid response format"
- **Nguyên nhân**: Đã được fix trong file `Login.php`
- **Khắc phục**: Đảm bảo file đã được upload đúng

#### Lỗi 2: Không tìm thấy trang (404)
- **Nguyên nhân**: .htaccess không hoạt động hoặc base URL sai
- **Khắc phục**:
  - Kiểm tra file `.htaccess` có được upload không
  - Kiểm tra cấu hình base URL trong `Config.php`

#### Lỗi 3: Không kết nối được database
- **Nguyên nhân**: Thông tin database sai
- **Khắc phục**: Kiểm tra lại thông tin trong `Config.php`

#### Lỗi 4: Không upload được hình ảnh
- **Nguyên nhân**: Permission thư mục sai
- **Khắc phục**: chmod 777 cho thư mục `Public/Pictures/` và các thư mục con

### 5. Lưu ý quan trọng

1. **Bảo mật**: 
   - Đổi password database sau khi deploy
   - Không để thông tin nhạy cảm trong code

2. **Sao lưu**:
   - Luôn sao lưu database trước khi cập nhật
   - Giữ bản backup của toàn bộ website

3. **Debug mode**:
   - Khi có lỗi, bật error reporting trong `Config.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

### 6. Thông tin liên hệ hỗ trợ

Nếu gặp vấn đề, kiểm tra:
1. Error logs của hosting (thường trong cPanel > Error Logs)
2. Browser Console (F12) để xem lỗi JavaScript
3. Network tab trong Browser DevTools để xem AJAX requests

---

**Cập nhật lần cuối**: 2026-03-20
**Phiên bản**: 2.0 - Đã fix toàn bộ đường dẫn và lỗi đăng nhập
