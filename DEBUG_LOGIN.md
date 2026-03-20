# HƯỚNG DẪN DEBUG LỖI ĐĂNG NHẬP

## 🔍 Các bước kiểm tra:

### Bước 1: Truy cập file test
```
https://ten-mien-cua-ban.com/test_login.php
```

File này sẽ cho biết:
- ✅ Base URL có đúng không
- ✅ Kết nối database có thành công không
- ✅ Users có trong database không
- ✅ Password có đúng không (test trực tiếp)

### Bước 2: Kiểm tra Console trên trình duyệt

1. Mở trang chủ: `https://ten-mien-cua-ban.com/`
2. Nhấn F12 để mở Developer Tools
3. Chuyển sang tab **Console**
4. Thử đăng nhập
5. Xem log như sau:

```
=== LOGIN DEBUG ===
Login URL: https://ten-mien-cua-ban.com/Login/process
Form data: {username: "admin", password: "..."}
Response status: 200
Response headers: {content-type: "application/json; charset=utf-8"}
Content-Type: application/json; charset=utf-8
```

### Bước 3: Các lỗi thường gặp

#### Lỗi 1: "Login URL: http://localhost/Banhang/Login/process"
**Nguyên nhân:** Base URL không đúng
**Khắc phục:** 
- Kiểm tra file `MVC/Core/Config.php`
- Thêm dòng này vào đầu file (trước hàm getBaseUrl):
```php
define('BASE_URL_MANUAL', 'https://ten-mien-cua-ban.com/');
```

#### Lỗi 2: "Response status: 500"
**Nguyên nhân:** Lỗi PHP server
**Khắc phục:**
- Kiểm tra error log của hosting
- Hoặc thêm vào đầu file `index.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

#### Lỗi 3: "Response status: 404"
**Nguyên nhân:** Không tìm thấy file Login.php
**Khắc phục:**
- Kiểm tra file `.htaccess` có được upload không
- Kiểm tra đường dẫn trong `.htaccess`

#### Lỗi 4: "Content-Type: text/html"
**Nguyên nhân:** Server trả về HTML thay vì JSON
**Khắc phục:**
- Xem nội dung response trả về cái gì (console.log sẽ hiển thị)
- Thường là lỗi PHP warning/notice hiển thị ra

#### Lỗi 5: "Kết nối database THẤT BẠI"
**Nguyên nhân:** Thông tin database sai
**Khắc phục:**
- Kiểm tra lại DB_HOST, DB_NAME, DB_USER, DB_PASS trong `MVC/Core/Config.php`
- Liên hệ hosting provider để xem thông tin database đúng

### Bước 4: Kiểm tra Session

Nếu đăng nhập thành công nhưng không chuyển hướng:
1. Mở Console
2. Gõ: `document.cookie`
3. Nếu không có PHPSESSID, session không hoạt động

**Khắc phục session trên HTTPS:**
Thêm vào file `index.php` (trước `session_start()`):
```php
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
```

---

## 📋 Checklist nhanh:

- [ ] File `test_login.php` hiển thị base URL đúng
- [ ] File `test_login.php` kết nối database thành công
- [ ] Console không có lỗi "Login URL: http://localhost"
- [ ] Response status = 200
- [ ] Content-Type = application/json
- [ ] Session cookie được set

---

## 🆘 Vẫn không được?

Gửi các thông tin sau để được hỗ trợ:
1. Screenshot Console (F12)
2. Screenshot file test_login.php
3. Nội dung file `MVC/Core/Config.php` (che password)
4. Tên hosting đang dùng
