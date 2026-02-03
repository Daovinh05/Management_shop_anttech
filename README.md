# TechZone - Hệ Thống Bán Lẻ Di Động Chính Hãng

Chào mừng bạn đến với **TechZone**, một dự án website thương mại điện tử chuyên cung cấp các sản phẩm công nghệ như điện thoại, laptop, máy tính bảng và phụ kiện.

## 🌟 Giới thiệu
Dự án được xây dựng dựa trên mô hình **MVC (Model-View-Controller)** sử dụng PHP thuần, giúp tách biệt rõ ràng giữa giao diện, logic xử lý và cơ sở dữ liệu. Đây là một hệ thống bán hàng hoàn chỉnh với đầy đủ các tính năng cho cả người quản trị (Admin) và khách hàng.

## 🚀 Chức năng chính

### Người dùng (Khách hàng)
- **Xem sản phẩm**: Duyệt sản phẩm theo danh mục, thương hiệu, tìm kiếm thông minh.
- **Chi tiết sản phẩm**: Xem hình ảnh, thông số kỹ thuật (RAM, ROM, Màu sắc), và đánh giá.
- **Giỏ hàng**: Thêm/sửa/xóa sản phẩm, cập nhật số lượng.
- **Thanh toán**: Đặt hàng, chọn địa chỉ giao hàng.
- **Tài khoản**: Đăng ký, đăng nhập, quản lý thông tin cá nhân, xem lịch sử đơn hàng.
- **Đánh giá**: Bình luận và đánh giá sao cho sản phẩm đã mua.

### Quản trị viên (Admin)
- **Quản lý sản phẩm**: Thêm, sửa, xóa sản phẩm và các biến thể.
- **Quản lý đơn hàng**: Xem danh sách đơn hàng, cập nhật trạng thái (Duyệt, Đang giao, Hoàn thành...).
- **Quản lý danh mục & Thương hiệu**: Tổ chức cây danh mục sản phẩm.

## 🛠 Yêu cầu hệ thống
- **XAMPP** (hoặc phần mềm tương tự hỗ trợ PHP & MySQL).
- **PHP**: Phiên bản 7.4 trở lên.
- **MySQL (MariaDB)**.

## ⚙️ Hướng dẫn cài đặt & Chạy dự án

### Bước 1: Chuẩn bị mã nguồn
Tải hoặc clone thư mục dự án vào thư mục gốc của server (ví dụ: `htdocs` trong XAMPP).
Đường dẫn dự kiến: `/Applications/XAMPP/xamppfiles/htdocs/Banhang/`

### Bước 2: Cài đặt Cơ sở dữ liệu
1. Mở **phpMyAdmin** (thường là `http://localhost/phpmyadmin`).
2. Tạo một cơ sở dữ liệu mới có tên: `phone_store_v2`.
3. Chọn cơ sở dữ liệu vừa tạo, vào tab **Nhập (Import)**.
4. Chọn file `phone_store_v2.sql` từ thư mục gốc của dự án và nhấn **Thực hiện (Go)**.

### Bước 3: Cấu hình kết nối (Nếu cần)
Mặc định dự án được cấu hình cho XAMPP với user `root` và mật khẩu trống. Nếu bạn dùng cấu hình khác, hãy mở file:
`MVC/Core/connectDB.php`
Và chỉnh sửa dòng sau:
```php
$this->con = mysqli_connect('localhost', 'tên_user', 'mật_khẩu', 'phone_store_v2');
```

### Bước 4: Chạy dự án
Mở trình duyệt và truy cập:
`http://localhost/Banhang/`

## 📂 Cấu trúc thư mục
- **MVC/**: Chứa mã nguồn chính theo mô hình MVC.
  - **Controllers/**: Xử lý logic điều hướng.
  - **Models/**: Tương tác với cơ sở dữ liệu.
  - **Views/**: Giao diện hiển thị (HTML/PHP).
  - **Core/**: Các lớp lõi (App, Controller, DB Connection).
- **Public/**: Chứa tài nguyên tĩnh (CSS, JS, Images).
- **index.php**: Điểm khởi chạy của ứng dụng (Router).

## 👤 Tài khoản Demo
Bạn có thể sử dụng các tài khoản có sẵn trong DB để kiểm tra:

| Vai trò | Tên đăng nhập | Mật khẩu |
| :--- | :--- | :--- |
| **Admin** | `vinh` | `123` |
| **Admin** | `hung` | `123` |
| **Khách hàng** | `long` | `123` |
| **Khách hàng** | `minh` | `1234` |

---
**TechZone** - Mang công nghệ đến tầm tay bạn!
