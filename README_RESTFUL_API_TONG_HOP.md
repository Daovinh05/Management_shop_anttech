# README RESTful API Tong Hop

Tai lieu nay tong hop toan bo RESTful API dang co trong he thong, duoc chia theo:
- Nhom Quan ly va Khach hang
- Tung chuc nang rieng
- Luong hoat dong API tu luc nguoi dung truy cap website

## 1. Tong quan kien truc API

- Base URL: `/Api`
- Router REST (theo `MVC/Core/app.php`):
  - Collection route: `/Api/{Controller}`
    - `GET` -> `get_all`
    - `POST` -> `create`
    - `PUT|PATCH` -> `update`
  - Item route: `/Api/{Controller}/{id}`
    - `GET` -> `get_detail`
    - `PUT|PATCH` -> `update`
    - `DELETE` -> `delete`
- Router co ho tro action endpoint de mo rong:
  - Mau: `/Api/{Controller}/{action}`
  - Vi du: `/Api/Checkout/init`, `/Api/Thongke/dashboard`, `/Api/Products/import`
- Dinh dang response JSON chuan (`success`, `message`, `data`, `total` tuy endpoint).

## 2. Nhom API Khach hang

### 2.1 Xac thuc tai khoan (`Auth`)
- `GET /Api/Auth/profile`
- `POST /Api/Auth/login`
- `POST /Api/Auth/register`
- `POST /Api/Auth/logout`

### 2.2 Mat hang va trang cua hang (xem/duyet)

#### `Storefront`
- `GET /Api/Storefront` (danh sach san pham hien thi cho storefront)
- `GET /Api/Storefront/{id}`
- `GET /Api/Storefront/filters`
- `GET /Api/Storefront/suggestions`

#### `Products` (co the dung cho trang san pham/public listing)
- `GET /Api/Products`
- `GET /Api/Products/{id}`
- `GET /Api/Products/search`
- `GET /Api/Products?format=xlsx` (xuat excel)

#### `Search`
- `GET /Api/Search`
- `GET /Api/Search/{id}`
- `GET /Api/Search/suggestions`
- `GET /Api/Search/history`
- `POST /Api/Search`
- `PUT|PATCH /Api/Search/{id}`
- `DELETE /Api/Search/{id}`

### 2.3 Gio hang (`Cart`) - yeu cau dang nhap
- `GET /Api/Cart`
- `GET /Api/Cart/{id}`
- `POST /Api/Cart`
- `PUT|PATCH /Api/Cart/{id}`
- `DELETE /Api/Cart/{id}`
- `GET /Api/Cart/summary`
- `POST /Api/Cart/clear`
- `PUT|PATCH|POST /Api/Cart/bulk_update`

### 2.4 Thanh toan va lich su mua (`Checkout`) - yeu cau dang nhap

#### Khoi tao/lay du lieu checkout
- `GET /Api/Checkout`
- `GET /Api/Checkout/init`
- `POST /Api/Checkout/preview`

#### Billing, khuyen mai, dia chi
- `GET /Api/Checkout/billing`
- `GET /Api/Checkout/promotions`
- `GET /Api/Checkout/addresses`
- `POST /Api/Checkout/addresses`
- `PUT|PATCH /Api/Checkout/addresses/{id}`
- `DELETE /Api/Checkout/addresses/{id}`

#### Tao/sua/huy don
- `POST /Api/Checkout` (tao don)
- `GET /Api/Checkout/{ma_don_hang}`
- `PUT|PATCH /Api/Checkout/{ma_don_hang}`
- `DELETE /Api/Checkout/{ma_don_hang}`

#### Lich su don cua khach
- `GET /Api/Checkout/history`
- `GET /Api/Checkout/status/{status}`
- `GET /Api/Checkout/summary`

### 2.5 Ho so tai khoan (`Profile`) - yeu cau dang nhap
- `GET /Api/Profile`
- `PUT|PATCH /Api/Profile/update`
- `PUT|PATCH /Api/Profile/password`

### 2.6 Danh gia san pham (`Danhgia`)
- `GET /Api/Danhgia`
- `GET /Api/Danhgia/{id}`
- `GET /Api/Danhgia/search`
- `POST /Api/Danhgia`
- `PUT|PATCH /Api/Danhgia/{id}`
- `DELETE /Api/Danhgia/{id}`

### 2.7 Tro ly chat (`Techbot`)
- `POST /Api/Techbot/ask`
- `GET /Api/Techbot/history`

## 3. Nhom API Quan ly

### 3.1 Quan ly san pham goc (`Products`)
- `GET /Api/Products`
- `GET /Api/Products/{id}`
- `POST /Api/Products`
- `PUT|PATCH /Api/Products/{id}`
- `DELETE /Api/Products/{id}`
- `GET /Api/Products/search`
- `POST /Api/Products/import`
- `GET /Api/Products?format=xlsx`

### 3.2 Quan ly bien the (`Bienthe`)
- `GET /Api/Bienthe`
- `GET /Api/Bienthe/{id}`
- `POST /Api/Bienthe`
- `PUT|PATCH /Api/Bienthe/{id}`
- `DELETE /Api/Bienthe/{id}`
- `GET /Api/Bienthe/search`
- `POST /Api/Bienthe/import`

### 3.3 Quan ly danh muc (`Danhmuc`)
- `GET /Api/Danhmuc`
- `GET /Api/Danhmuc/{id}`
- `POST /Api/Danhmuc`
- `PUT|PATCH /Api/Danhmuc/{id}`
- `DELETE /Api/Danhmuc/{id}`
- `GET /Api/Danhmuc/search`
- `POST /Api/Danhmuc/import`

### 3.4 Quan ly thuong hieu (`Thuonghieu`)
- `GET /Api/Thuonghieu`
- `GET /Api/Thuonghieu/{id}`
- `POST /Api/Thuonghieu`
- `PUT|PATCH /Api/Thuonghieu/{id}`
- `DELETE /Api/Thuonghieu/{id}`
- `GET /Api/Thuonghieu/search`
- `POST /Api/Thuonghieu/import`

### 3.5 Quan ly nha cung cap (`Nhacungcap`)
- `GET /Api/Nhacungcap`
- `GET /Api/Nhacungcap/{id}`
- `POST /Api/Nhacungcap`
- `PUT|PATCH /Api/Nhacungcap/{id}`
- `DELETE /Api/Nhacungcap/{id}`
- `GET /Api/Nhacungcap/search`
- `POST /Api/Nhacungcap/import`

### 3.6 Quan ly khuyen mai (`Khuyenmai`)
- `GET /Api/Khuyenmai`
- `GET /Api/Khuyenmai/{id}`
- `POST /Api/Khuyenmai`
- `PUT|PATCH /Api/Khuyenmai/{id}`
- `DELETE /Api/Khuyenmai/{id}`
- `GET /Api/Khuyenmai/search`
- `POST /Api/Khuyenmai/import`

### 3.7 Quan ly don hang (`Donhang`)
- `GET /Api/Donhang`
- `GET /Api/Donhang/{id}`
- `POST /Api/Donhang`
- `PUT|PATCH /Api/Donhang/{id}`
- `DELETE /Api/Donhang/{id}`
- `GET /Api/Donhang/search`
- `PUT|PATCH /Api/Donhang/update_status/{id}`

### 3.8 Quan ly nguoi dung (`Users`)
- `GET /Api/Users`
- `GET /Api/Users/{id}`
- `POST /Api/Users`
- `PUT|PATCH /Api/Users/{id}`
- `DELETE /Api/Users/{id}`
- `GET /Api/Users/search`
- `POST /Api/Users/import`

### 3.9 Quan ly danh gia (`Danhgia`)
- `GET /Api/Danhgia`
- `GET /Api/Danhgia/{id}`
- `GET /Api/Danhgia/search`
- `POST /Api/Danhgia`
- `PUT|PATCH /Api/Danhgia/{id}`
- `DELETE /Api/Danhgia/{id}`

### 3.10 Thong ke doanh thu (`Thongke`) - yeu cau `admin` hoac `nhan_vien`
- `GET /Api/Thongke`
- `GET /Api/Thongke/dashboard`
- `GET /Api/Thongke/summary`
- `GET /Api/Thongke/payment_methods`
- `GET /Api/Thongke/top_products`
- `GET /Api/Thongke/export`
- `GET /Api/Thongke?format=xlsx`

Filter chinh cho thong ke:
- `tu_ngay`
- `den_ngay`
- `ma_don_hang`
- `ten_khach_hang`
- `trang_thai_don_hang` (hoac `status`)

### 3.11 Cai dat he thong (`Settings`) - yeu cau `admin`
- `GET /Api/Settings/order_timeout`
- `PUT|PATCH /Api/Settings/order_timeout`

## 4. Luong hoat dong API tu khi bat dau truy cap web

### 4.1 Giai doan vao trang
1. Nguoi dung truy cap `/{BASE_URL}` (web route).
2. Frontend page (web MVC view) khoi tao va goi API can thiet qua `/Api/...`.
3. Cac endpoint public duoc goi som:
   - `Storefront`, `Products`, `Search/suggestions`, ...

### 4.2 Giai doan xac thuc
1. Dang nhap qua `POST /Api/Auth/login`.
2. He thong tao session (`user_id`, `user_role`).
3. Sau dang nhap:
   - Khach hang vao luong mua sam
   - Admin/Nhan vien vao luong quan tri

### 4.3 Luong khach hang mua hang
1. Duyet san pham: `Storefront`, `Products`, `Search`.
2. Them/sua gio: `Cart` (`create`, `update`, `delete`, `summary`).
3. Vao checkout: `GET /Api/Checkout/init` + `billing` + `promotions` + `addresses`.
4. Dat hang: `POST /Api/Checkout`.
5. Theo doi don: `GET /Api/Checkout/history`, `GET /Api/Checkout/status/{status}`, `GET /Api/Checkout/{id}`.
6. Quan ly tai khoan: `Profile`, `Auth/profile`, danh gia qua `Danhgia`.

### 4.4 Luong quan ly
1. Quan ly danh muc du lieu nen:
   - `Danhmuc`, `Thuonghieu`, `Nhacungcap`, `Khuyenmai`, `Users`.
2. Quan ly hang hoa:
   - `Products`, `Bienthe`, import/export Excel.
3. Quan ly don:
   - `Donhang`, `Donhang/update_status/{id}`.
4. Theo doi KPI:
   - `Thongke/dashboard`, `summary`, `payment_methods`, `top_products`, `export`.
5. Cau hinh he thong:
   - `Settings/order_timeout`.

## 5. Ghi chu su dung

- API base controller co CORS + JSON response (`MVC/Core/api_controller.php`).
- Co endpoint vua REST vua action-based de giu tuong thich nguoc.
- Mot so endpoint yeu cau session dang nhap; rieng `Thongke` va `Settings` co them rang buoc vai tro.
- Nen uu tien dung endpoint REST resource (`get_all`, `get_detail`, `create`, `update`, `delete`) va chi dung action endpoint khi can chuc nang dac thu (`import`, `dashboard`, `preview`, ...).
