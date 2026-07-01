# TechZone - Hệ Thống Bán Lẻ Di Động Chính Hãng

Chào mừng bạn đến với **TechZone**, một dự án website thương mại điện tử chuyên cung cấp các sản phẩm công nghệ như điện thoại, laptop, máy tính bảng và phụ kiện.

## 🌟 Giới thiệu
Dự án được xây dựng dựa trên mô hình **MVC (Model-View-Controller)** sử dụng PHP thuần, hỗ trợ đầy đủ **RESTful API** cho việc tích hợp với frontend (React, Vue, Angular) hoặc mobile app.

## 🚀 Chức năng chính

### Người dùng (Khách hàng)
- **Xem sản phẩm**: Duyệt sản phẩm theo danh mục, thương hiệu, tìm kiếm thông minh.
- **Chi tiết sản phẩm**: Xem hình ảnh, thông số kỹ thuật (RAM, ROM, Màu sắc), và đánh giá.
- **Giỏ hàng**: Thêm/sửa/xóa sản phẩm, cập nhật số lượng qua API.
- **Thanh toán**: Đặt hàng, chọn địa chỉ giao hàng, tích hợp VNPAY.
- **Tài khoản**: Đăng ký, đăng nhập, quản lý thông tin cá nhân, xem lịch sử đơn hàng.
- **Đánh giá**: Bình luận và đánh giá sao cho sản phẩm đã mua.

### Quản trị viên (Admin)
- **Quản lý sản phẩm**: Thêm, sửa, xóa sản phẩm và các biến thể qua API.
- **Quản lý đơn hàng**: Xem danh sách đơn hàng, cập nhật trạng thái (Duyệt, Đang giao, Hoàn thành...).
- **Quản lý danh mục & Thương hiệu**: Tổ chức cây danh mục sản phẩm.
- **Xuất/Nhập Excel**: Import sản phẩm từ Excel, xuất danh sách ra Excel.

## 🛠 Yêu cầu hệ thống
- **XAMPP** (hoặc phần mềm tương tự hỗ trợ PHP & MySQL).
- **PHP**: Phiên bản 7.4 trở lên.
- **MySQL (MariaDB)**.
- **PHP Extensions**: `mysqli`, `gd`, `phpexcel` (cho xuất/nhập Excel).

## ⚙️ Hướng dẫn cài đặt & Chạy dự án

### Chạy nhanh bằng Docker

Yêu cầu: Docker Desktop hoặc Docker Engine có Docker Compose.

```bash
docker compose up -d --build
```

Sau khi các container khởi động:

- Website: `http://localhost:8080/Banhang/`
- REST API: `http://localhost:8080/Banhang/Api/`
- phpMyAdmin: `http://localhost:8081`

Database `banhang` và dữ liệu mẫu trong `banhang.sql` sẽ được import tự động ở lần chạy đầu tiên. Cấu hình mặc định có thể được thay đổi bằng cách sao chép các biến cần dùng từ `.env.docker.example` vào `.env`.

Mã nguồn được đóng gói vào image. Sau khi sửa code, chạy lại `docker compose up -d --build` để cập nhật container.

Các lệnh thường dùng:

```bash
# Xem log
docker compose logs -f

# Dừng dự án
docker compose down

# Xóa cả database volume và khởi tạo lại dữ liệu mẫu
docker compose down -v
docker compose up -d --build
```

### Bước 1: Chuẩn bị mã nguồn
Tải hoặc clone thư mục dự án vào thư mục gốc của server (ví dụ: `htdocs` trong XAMPP).
Đường dẫn dự kiến: `/Applications/XAMPP/xamppfiles/htdocs/Banhang/`

### Bước 2: Cài đặt Cơ sở dữ liệu
1. Mở **phpMyAdmin** (thường là `http://localhost/phpmyadmin`).
2. Tạo một cơ sở dữ liệu mới có tên: `phone_store_v2`.
3. Chọn cơ sở dữ liệu vừa tạo, vào tab **Nhập (Import)**.
4. Chọn file `banhang.sql` từ thư mục gốc của dự án và nhấn **Thực hiện (Go)**.

### Bước 3: Cấu hình kết nối (Nếu cần)
Mặc định dự án được cấu hình cho XAMPP với user `root` và mật khẩu trống. Nếu bạn dùng cấu hình khác, hãy mở file:
`MVC/Core/connectDB.php`
Và chỉnh sửa dòng sau:
```php
$this->con = mysqli_connect('localhost', 'tên_user', 'mật_khẩu', 'phone_store_v2');
```

### Bước 4: Chạy dự án
Mở trình duyệt và truy cập:
- **Website**: `http://localhost/Banhang/`
- **API Base URL**: `http://localhost/Banhang/Api/`

## 📡 RESTful API Documentation

### Base URL
```
http://localhost/Banhang/Api/
```

### Authentication
Một số endpoint yêu cầu đăng nhập. Session được sử dụng để xác thực người dùng.

### Response Format
Tất cả các API trả về JSON theo chuẩn:
```json
{
    "success": true,
    "message": "Thành công",
    "data": { ... },
    "total": 10
}
```

---

## 📦 Products API - Quản lý sản phẩm

### GET /Api/Products
Lấy danh sách sản phẩm hoặc tìm kiếm.

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `ma_san_pham` | string | Mã sản phẩm (tùy chọn) |
| `ten_san_pham` | string | Tên sản phẩm (tùy chọn) |
| `format` | string | `xlsx` để xuất Excel (tùy chọn) |

**Ví dụ:**
```bash
# Lấy tất cả sản phẩm
GET /Api/Products

# Tìm kiếm theo tên
GET /Api/Products?ten_san_pham=iphone

# Xuất Excel
GET /Api/Products?format=xlsx
```

**Response:**
```json
{
    "success": true,
    "message": "Lấy danh sách sản phẩm thành công",
    "total": 50,
    "data": [
        {
            "ma_san_pham": "SP01",
            "ten_san_pham": "iPhone 15 Pro Max",
            "ma_danh_muc": "DM01",
            "ten_danh_muc": "Điện thoại",
            "ten_thuong_hieu": "Apple",
            "img_bien_the": "iphone15promax.jpg",
            "gia": 34990000,
            "so_luong_kho": 100
        }
    ]
}
```

---

### GET /Api/Products/{ma_san_pham}
Lấy chi tiết một sản phẩm.

**Ví dụ:**
```bash
GET /Api/Products/SP01
```

**Response:**
```json
{
    "success": true,
    "data": {
        "ma_san_pham": "SP01",
        "ten_san_pham": "iPhone 15 Pro Max",
        "ma_danh_muc": "DM01",
        "ten_danh_muc": "Điện thoại"
    }
}
```

---

### POST /Api/Products
Tạo mới sản phẩm.

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
    "ma_san_pham": "SP02",
    "ten_san_pham": "Samsung Galaxy S24 Ultra",
    "ma_danh_muc": "DM01",
    "ma_thuong_hieu": "TH02",
    "ma_nha_cung_cap": "NCC01"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Tạo sản phẩm thành công",
    "data": { ... }
}
```

---

### PUT /Api/Products/{ma_san_pham}
Cập nhật thông tin sản phẩm.

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
    "ten_san_pham": "Samsung Galaxy S24 Ultra 5G",
    "ma_danh_muc": "DM01"
}
```

---

### DELETE /Api/Products/{ma_san_pham}
Xóa sản phẩm.

**Ví dụ:**
```bash
DELETE /Api/Products/SP02
```

---

### POST /Api/Products/import
Nhập sản phẩm từ file Excel.

**Headers:**
```
Content-Type: multipart/form-data
```

**Form Data:**
| Key | Value |
|-----|-------|
| `file` | File Excel (.xlsx/.xls) |

**Response:**
```json
{
    "success": true,
    "message": "Import sản phẩm hoàn tất",
    "created": 45,
    "duplicated_count": 3,
    "failed_count": 2,
    "failed_rows": [...]
}
```

---

## 🛒 Cart API - Giỏ hàng

### GET /Api/Cart
Lấy thông tin giỏ hàng của người dùng đã đăng nhập.

**Response:**
```json
{
    "success": true,
    "message": "Lay gio hang thanh cong",
    "data": {
        "ma_gio_hang": "GH01",
        "ma_user": "U01",
        "items": [
            {
                "ma_bien_the": "BT01",
                "ma_san_pham": "SP01",
                "ten_san_pham": "iPhone 15 Pro Max",
                "ten_bien_the": "256GB - Titan Natural",
                "gia": 34990000,
                "so_luong": 2,
                "line_total": 69980000,
                "img": "http://localhost/Banhang/Public/Pictures/bien_the/iphone15promax.jpg"
            }
        ],
        "summary": {
            "total_items": 1,
            "total_quantity": 2,
            "subtotal": 69980000
        }
    }
}
```

---

### POST /Api/Cart
Thêm sản phẩm vào giỏ hàng.

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
    "ma_bien_the": "BT01",
    "so_luong": 2
}
```

**Response:**
```json
{
    "success": true,
    "message": "Them vao gio hang thanh cong",
    "data": {
        "ma_gio_hang": "GH01",
        "items": [...],
        "summary": {...}
    }
}
```

---

### PUT /Api/Cart/{ma_bien_the}
Cập nhật số lượng sản phẩm trong giỏ.

**Body:**
```json
{
    "so_luong": 3
}
```

---

### DELETE /Api/Cart/{ma_bien_the}
Xóa sản phẩm khỏi giỏ hàng.

**Ví dụ:**
```bash
DELETE /Api/Cart/BT01
```

---

### POST /Api/Cart/bulk_update
Cập nhật nhiều sản phẩm cùng lúc.

**Body:**
```json
{
    "items": [
        {"ma_bien_the": "BT01", "so_luong": 2},
        {"ma_bien_the": "BT02", "so_luong": 0}
    ]
}
```

---

### DELETE /Api/Cart/clear
Xóa toàn bộ giỏ hàng.

---

## 📦 Orders API - Đơn hàng

### GET /Api/Donhang
Lấy danh sách đơn hàng hoặc tìm kiếm.

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `ma_don_hang` | string | Mã đơn hàng |
| `full_name` | string | Tên khách hàng |
| `format` | string | `xlsx` để xuất Excel |

**Ví dụ:**
```bash
# Lấy tất cả đơn hàng
GET /Api/Donhang

# Tìm kiếm theo mã đơn
GET /Api/Donhang?ma_don_hang=DH01

# Tìm kiếm theo tên khách
GET /Api/Donhang?full_name=Nguyen Van A
```

---

### GET /Api/Donhang/{ma_don_hang}
Lấy chi tiết đơn hàng.

**Response:**
```json
{
    "success": true,
    "data": {
        "order_info": {
            "ma_don_hang": "DH01",
            "ma_user": "U01",
            "tong_tien_hang": 34990000,
            "trang_thai_don_hang": "cho_duyet"
        },
        "order_details": [
            {
                "ma_bien_the": "BT01",
                "ten_san_pham": "iPhone 15 Pro Max",
                "so_luong": 1,
                "gia_luc_mua": 34990000
            }
        ],
        "user_info": {...},
        "address_info": {...}
    }
}
```

---

### POST /Api/Donhang
Tạo đơn hàng mới.

**Body:**
```json
{
    "ma_don_hang": "DH03",
    "ma_user": "U01",
    "ma_dia_chi": "DC01",
    "ma_khuyen_mai": "KM01",
    "tong_tien_hang": 34990000,
    "trang_thai_don_hang": "cho_duyet"
}
```

---

### PUT /Api/Donhang/{ma_don_hang}
Cập nhật đơn hàng.

**Body:**
```json
{
    "ma_dia_chi": "DC02",
    "trang_thai_don_hang": "dang_giao"
}
```

---

### PUT /Api/Donhang/{ma_don_hang}/status
Cập nhật trạng thái đơn hàng.

**Body:**
```json
{
    "trang_thai_don_hang": "hoan_thanh"
}
```

**Trạng thái hợp lệ:** `cho_duyet`, `dang_giao`, `hoan_thanh`, `da_huy`

---

### DELETE /Api/Donhang/{ma_don_hang}
Xóa đơn hàng.

---

## 🔐 Users API - Người dùng

### GET /Api/Users
Lấy danh sách người dùng.

### GET /Api/Users/{ma_user}
Lấy thông tin người dùng.

### POST /Api/Users
Tạo người dùng mới.

### PUT /Api/Users/{ma_user}
Cập nhật thông tin người dùng.

### DELETE /Api/Users/{ma_user}
Xóa người dùng.

---

## 📊 Other APIs

### Categories - Danh mục
```
GET    /Api/Danhmuc          # Lấy danh sách danh mục
GET    /Api/Danhmuc/DM01     # Chi tiết danh mục
POST   /Api/Danhmuc          # Tạo danh mục
PUT    /Api/Danhmuc/DM01     # Cập nhật danh mục
DELETE /Api/Danhmuc/DM01     # Xóa danh mục
```

### Brands - Thương hiệu
```
GET    /Api/Thuonghieu       # Lấy danh sách thương hiệu
GET    /Api/Thuonghieu/TH01  # Chi tiết thương hiệu
POST   /Api/Thuonghieu       # Tạo thương hiệu
PUT    /Api/Thuonghieu/TH01  # Cập nhật thương hiệu
DELETE /Api/Thuonghieu/TH01  # Xóa thương hiệu
```

### Variants - Biến thể sản phẩm
```
GET    /Api/Bienthe          # Lấy danh sách biến thể
GET    /Api/Bienthe/BT01     # Chi tiết biến thể
POST   /Api/Bienthe          # Tạo biến thể
PUT    /Api/Bienthe/BT01     # Cập nhật biến thể
DELETE /Api/Bienthe/BT01     # Xóa biến thể
```

### Reviews - Đánh giá
```
GET    /Api/Danhgia          # Lấy danh sách đánh giá
POST   /Api/Danhgia          # Tạo đánh giá mới
```

### Promotions - Khuyến mãi
```
GET    /Api/Khuyenmai        # Lấy danh sách khuyến mãi
GET    /Api/Khuyenmai/KM01   # Chi tiết khuyến mãi
POST   /Api/Khuyenmai        # Tạo khuyến mãi
PUT    /Api/Khuyenmai/KM01   # Cập nhật khuyến mãi
DELETE /Api/Khuyenmai/KM01   # Xóa khuyến mãi
```

### Search - Tìm kiếm
```
GET /Api/Search?q=iphone     # Tìm kiếm sản phẩm theo từ khóa
```

### Storefront - Trang chủ
```
GET /Api/Storefront          # Lấy dữ liệu trang chủ (sản phẩm nổi bật, danh mục...)
```

---

## 📂 Cấu trúc thư mục
```
Banhang/
├── MVC/
│   ├── Controllers/
│   │   ├── Api/           # RESTful API Controllers
│   │   │   ├── Products.php
│   │   │   ├── Cart.php
│   │   │   ├── Donhang.php
│   │   │   └── ...
│   │   ├── Khachhang.php  # Web Controllers
│   │   └── ...
│   ├── Models/            # Database Models
│   ├── Views/             # HTML/PHP Views
│   └── Core/              # Core Framework
│       ├── api_controller.php
│       └── ...
├── Public/                # Static Assets (CSS, JS, Images)
├── banhang.sql           # Database Schema
└── index.php             # Entry Point
```

---

## 👤 Tài khoản Demo

| Vai trò | Tên đăng nhập | Mật khẩu |
| :--- | :--- | :--- |
| **Admin** | `vinh` | `123` |
| **Admin** | `hung` | `123` |
| **Khách hàng** | `long` | `123` |
| **Khách hàng** | `minh` | `1234` |

---

## 🔧 HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Thành công |
| 201 | Created - Tạo mới thành công |
| 400 | Bad Request - Yêu cầu không hợp lệ |
| 401 | Unauthorized - Chưa đăng nhập |
| 404 | Not Found - Không tìm thấy |
| 405 | Method Not Allowed - Phương thức không hỗ trợ |
| 409 | Conflict - Dữ liệu đã tồn tại |
| 422 | Unprocessable Entity - Dữ liệu không hợp lệ (validation failed) |
| 500 | Internal Server Error - Lỗi server |

---

## 📝 Ví dụ sử dụng API với JavaScript (Fetch)

```javascript
// Lấy danh sách sản phẩm
fetch('http://localhost/Banhang/Api/Products')
    .then(res => res.json())
    .then(data => console.log(data));

// Thêm vào giỏ hàng
fetch('http://localhost/Banhang/Api/Cart', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        ma_bien_the: 'BT01',
        so_luong: 2
    })
})
.then(res => res.json())
.then(data => console.log(data));

// Đặt hàng
fetch('http://localhost/Banhang/Api/Donhang', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        ma_don_hang: 'DH03',
        ma_user: 'U01',
        ma_dia_chi: 'DC01',
        tong_tien_hang: 34990000,
        trang_thai_don_hang: 'cho_duyet'
    })
})
.then(res => res.json())
.then(data => console.log(data));
```

---

## 📞 Hỗ trợ

Nếu bạn gặp vấn đề khi sử dụng API, vui lòng tạo issue trên GitHub hoặc liên hệ nhóm phát triển.

---
**TechZone** - Mang công nghệ đến tầm tay bạn!
test
