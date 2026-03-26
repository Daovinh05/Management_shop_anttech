# TechZone RESTful API Documentation

## Giới thiệu

Tài liệu này mô tả các API endpoints cho hệ thống TechZone. Tất cả các requests và responses đều sử dụng định dạng JSON.

**Base URL:** `http://localhost/Banhang/api`

---

## Authentication (Xác thực)

API sử dụng token-based authentication. Sau khi đăng nhập, bạn sẽ nhận được một token và cần gửi kèm trong header của các requests yêu cầu xác thực.

### Gửi token trong request:
```
Authorization: Bearer <your_token>
```

---

## API Endpoints

### 1. Authentication APIs (Auth)

#### 1.1. Login (Đăng nhập)
- **Endpoint:** `POST /api/auth/login`
- **Body:**
```json
{
  "username": "vinh",
  "password": "123"
}
```
- **Response (Success):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "abc123xyz...",
    "user": {
      "user_id": "U01",
      "username": "vinh",
      "email": "vinh@example.com",
      "full_name": "Nguyễn Văn Vinh",
      "phone": "0123456789",
      "role": "admin"
    },
    "redirect_url": "http://localhost/Banhang/Quanly"
  }
}
```

#### 1.2. Register (Đăng ký)
- **Endpoint:** `POST /api/auth/register`
- **Body:**
```json
{
  "username": "newuser",
  "password": "123456",
  "confirm_password": "123456",
  "email": "newuser@example.com",
  "phone": "0987654321",
  "fullname": "Nguyễn Văn A"
}
```

#### 1.3. Logout (Đăng xuất)
- **Endpoint:** `POST /api/auth/logout`
- **Headers:** `Authorization: Bearer <token>`

#### 1.4. Get Current User (Lấy thông tin user hiện tại)
- **Endpoint:** `GET /api/auth`
- **Headers:** `Authorization: Bearer <token>`

#### 1.5. Update Profile (Cập nhật hồ sơ)
- **Endpoint:** `PUT /api/auth/profile`
- **Headers:** `Authorization: Bearer <token>`
- **Body:**
```json
{
  "email": "newemail@example.com",
  "phone": "0999888777",
  "full_name": "Nguyễn Văn B"
}
```

#### 1.6. Change Password (Đổi mật khẩu)
- **Endpoint:** `PUT /api/auth/change-password`
- **Headers:** `Authorization: Bearer <token>`
- **Body:**
```json
{
  "old_password": "123",
  "new_password": "123456",
  "confirm_password": "123456"
}
```

---

### 2. Products APIs (Sản phẩm)

#### 2.1. Get All Products (Lấy danh sách sản phẩm)
- **Endpoint:** `GET /api/products`
- **Query Parameters:**
  - `page`: Số trang (default: 1)
  - `limit`: Số lượng mỗi trang (default: 20)
  - `category`: Mã danh mục
  - `brand`: Mã thương hiệu
  - `price_range`: Khoảng giá (duoi-2-trieu, 2-4-trieu, 4-7-trieu, 7-13-trieu, tren-13-trieu)
  - `search`: Từ khóa tìm kiếm

- **Example:** `GET /api/products?page=1&limit=10&category=DM01`

- **Response:**
```json
{
  "success": true,
  "message": "Products retrieved successfully",
  "data": {
    "items": [
      {
        "ma_san_pham": "SP01",
        "ten_san_pham": "iPhone 15 Pro Max",
        "ma_danh_muc": "DM01",
        "ten_danh_muc": "Điện thoại",
        "ma_thuong_hieu": "TH01",
        "ten_thuong_hieu": "Apple",
        "ma_nha_cung_cap": "NCC01",
        "gia": 34990000,
        "so_luong_kho": 50,
        "hinh_anh": "iphone15promax.jpg",
        "ngay_tao": "2024-01-01 10:00:00"
      }
    ],
    "pagination": {
      "total": 100,
      "page": 1,
      "limit": 10,
      "total_pages": 10
    }
  }
}
```

#### 2.2. Get Product by ID (Lấy chi tiết sản phẩm)
- **Endpoint:** `GET /api/products/{ma_san_pham}`
- **Example:** `GET /api/products/SP01`

#### 2.3. Search Products (Tìm kiếm sản phẩm)
- **Endpoint:** `GET /api/products/search`
- **Query Parameters:**
  - `keyword`: Từ khóa tìm kiếm
  - `category`: Mã danh mục
  - `brand`: Mã thương hiệu
  - `min_price`: Giá tối thiểu
  - `max_price`: Giá tối đa

#### 2.4. Create Product (Thêm sản phẩm) - **Admin Only**
- **Endpoint:** `POST /api/products`
- **Headers:** `Authorization: Bearer <token>` (admin)
- **Body:**
```json
{
  "ma_san_pham": "SP100",
  "ten_san_pham": "Samsung Galaxy S24",
  "ma_danh_muc": "DM01",
  "ma_thuong_hieu": "TH02",
  "ma_nha_cung_cap": "NCC02",
  "variants": [
    {
      "ten_bien_the": "256GB Black",
      "gia": 25990000,
      "so_luong_kho": 30,
      "img_bien_the": "s24_black.jpg"
    }
  ]
}
```

#### 2.5. Update Product (Cập nhật sản phẩm) - **Admin Only**
- **Endpoint:** `PUT /api/products/{ma_san_pham}`
- **Headers:** `Authorization: Bearer <token>` (admin)
- **Body:**
```json
{
  "ten_san_pham": "Samsung Galaxy S24 Ultra",
  "ma_danh_muc": "DM01",
  "ma_thuong_hieu": "TH02",
  "ma_nha_cung_cap": "NCC02"
}
```

#### 2.6. Delete Product (Xóa sản phẩm) - **Admin Only**
- **Endpoint:** `DELETE /api/products/{ma_san_pham}`
- **Headers:** `Authorization: Bearer <token>` (admin)

#### 2.7. Get Categories (Lấy danh mục)
- **Endpoint:** `GET /api/products/categories`

#### 2.8. Get Brands (Lấy thương hiệu)
- **Endpoint:** `GET /api/products/brands`

#### 2.9. Get Suppliers (Lấy nhà cung cấp)
- **Endpoint:** `GET /api/products/suppliers`

---

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success message",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "error_code": "ERROR_CODE"
}
```

### HTTP Status Codes
- `200`: OK - Thành công
- `201`: Created - Tạo mới thành công
- `400`: Bad Request - Dữ liệu không hợp lệ
- `401`: Unauthorized - Chưa xác thực
- `403`: Forbidden - Không có quyền
- `404`: Not Found - Không tìm thấy
- `409`: Conflict - Trùng lặp
- `500`: Internal Server Error - Lỗi server

---

## Ví dụ sử dụng với JavaScript (Fetch API)

### Đăng nhập
```javascript
fetch('http://localhost/Banhang/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    username: 'vinh',
    password: '123'
  })
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    // Lưu token
    localStorage.setItem('authToken', data.data.token);
    console.log('Login successful:', data.data.user);
  }
})
.catch(error => console.error('Error:', error));
```

### Lấy danh sách sản phẩm
```javascript
fetch('http://localhost/Banhang/api/products?page=1&limit=10')
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log('Products:', data.data.items);
    }
  })
  .catch(error => console.error('Error:', error));
```

### Tạo sản phẩm mới (cần admin token)
```javascript
const token = localStorage.getItem('authToken');

fetch('http://localhost/Banhang/api/products', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token
  },
  body: JSON.stringify({
    ma_san_pham: 'SP100',
    ten_san_pham: 'iPhone 16',
    ma_danh_muc: 'DM01',
    ma_thuong_hieu: 'TH01',
    ma_nha_cung_cap: 'NCC01'
  })
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Product created:', data.data);
  } else {
    console.error('Error:', data.message);
  }
})
.catch(error => console.error('Error:', error));
```

---

## Testing với cURL

### Test login:
```bash
curl -X POST http://localhost/Banhang/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"vinh","password":"123"}'
```

### Test get products:
```bash
curl -X GET "http://localhost/Banhang/api/products?page=1&limit=5"
```

### Test create product (with token):
```bash
curl -X POST http://localhost/Banhang/api/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "ma_san_pham": "SP100",
    "ten_san_pham": "Test Product",
    "ma_danh_muc": "DM01",
    "ma_thuong_hieu": "TH01",
    "ma_nha_cung_cap": "NCC01"
  }'
```

---

## Lưu ý

1. **Token Expiry:** Token hiện tại được lưu trong session và sẽ hết hạn khi session hết hạn
2. **CORS:** API hỗ trợ CORS cho phép gọi từ các domain khác
3. **Rate Limiting:** Chưa áp dụng giới hạn request
4. **Security:** Nên sử dụng HTTPS trong production
5. **SQL Injection:** Cần cải thiện security bằng prepared statements

---

## Hỗ trợ

Mọi thắc mắc hoặc báo lỗi, vui lòng liên hệ với đội phát triển.
