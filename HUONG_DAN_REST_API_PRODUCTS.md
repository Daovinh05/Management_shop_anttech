# Huong dan tao toan bo RESTful API Products

Tai lieu nay huong dan cach xay dung day du Product REST API theo dung kieu kien truc dang dung trong project Banhang (MVC + PHP + MySQL).

## 1) Muc tieu

Xay dung API cho tai nguyen `products` voi day du chuc nang:

- Lay danh sach san pham (co tim kiem)
- Lay chi tiet 1 san pham
- Tao san pham moi
- Cap nhat san pham
- Xoa san pham
- Import danh sach san pham tu Excel
- Export danh sach san pham ra Excel

## 2) Cau truc file can co

Trong project hien tai, Product REST API nam o cac file:

- `MVC/Core/app.php`: router tong va mapping RESTful action
- `MVC/Core/api_controller.php`: base controller tra JSON, CORS, get input
- `MVC/Controllers/Api/Products.php`: API controller cho products
- `MVC/Models/SanPham_m.php`: model truy van DB san pham

## 3) Nen tang REST trong Core

### 3.1 `api_controller.php`

Base API controller can dam bao:

- Luon tra header JSON
- Ho tro CORS (`Access-Control-Allow-Origin`, methods, headers)
- Bat preflight `OPTIONS` de browser goi API cross-origin
- Co helper `getJsonInput()` de doc payload JSON
- Co helper `sendResponse($status, $data)` de tra response chuan

Mau hanh vi chinh:

- `getJsonInput()` doc `php://input`, json_decode, fallback `[]`
- `sendResponse()` set HTTP status + `json_encode(...)` + `exit`

### 3.2 `app.php` (router)

Router dang co 2 che do:

- Web: `/Controller/Action/...`
- API: `/Api/<Controller>/...`

Voi API, can co mapping REST:

- Collection route `/Api/Products`
  - `GET` -> `get_all`
  - `POST` -> `create`
  - `PUT|PATCH` -> `update`
- Item route `/Api/Products/{id}`
  - `GET` -> `get_detail`
  - `PUT|PATCH` -> `update`
  - `DELETE` -> `delete`

Ngoai ra router dang uu tien route action-based de giu tuong thich nguoc. Vi du:

- `/Api/Products/import` -> goi truc tiep `import()`
- `/Api/Products/search` -> goi truc tiep `search()`

## 4) Xay model `SanPham_m.php`

De API hoat dong day du, model can it nhat cac ham sau:

- `SanPham_getAll()`
- `SanPham_find($ma_san_pham, $ten_san_pham)`
- `SanPham_getById($ma_san_pham)`
- `sanpham_ins(...)`
- `SanPham_update(...)`
- `SanPham_delete($ma_san_pham)`
- `checktrungMaSP($ma_san_pham)`
- `SanPham_hasOrderDetails($ma_san_pham)`

Luu y nghiep vu trong project nay:

- Khi xoa san pham, model xoa bien the lien quan trong `bien_the` truoc, sau do moi xoa o `san_pham`.
- API xoa bi chan neu san pham da ton tai trong chi tiet don hang (`SanPham_hasOrderDetails`).

## 5) Xay controller `Products.php`

Controller ke thua `api_controller` va khoi tao model:

- `__construct()` -> `$this->sanpham_model = $this->model('SanPham_m');`

### 5.1 GET `/Api/Products` -> `get_all()`

Nhiem vu:

- Kiem tra method phai la `GET`
- Nhan filter tu query:
  - `ma_san_pham`
  - `ten_san_pham`
- Neu co filter -> goi `SanPham_find(...)`
- Neu khong co filter -> goi `SanPham_getAll()`
- Chuan hoa output ve dang:

```json
{
  "success": true,
  "message": "Lay danh sach san pham thanh cong",
  "total": 10,
  "data": [ ... ]
}
```

Tinh nang bo sung:

- Ho tro export Excel khi truyen `?format=xlsx`
- Khi export, API stream file `DanhSachSanPham.xlsx` va `exit`

### 5.2 GET `/Api/Products/{id}` -> `get_detail($id)`

Nhiem vu:

- Kiem tra co `id`
- Goi `SanPham_getById($id)`
- Neu co du lieu -> 200
- Neu khong co -> 404

### 5.3 POST `/Api/Products` -> `create()`

Input JSON toi thieu:

- `ma_san_pham`
- `ten_san_pham`
- `ma_danh_muc`

Truong tuy chon:

- `ma_thuong_hieu`
- `ma_nha_cung_cap`

Flow:

- Validate method `POST`
- `getJsonInput()`
- Validate field bat buoc
- Kiem tra trung ma qua `checktrungMaSP()`
- Goi `sanpham_ins(...)`
- Thanh cong -> `201`
- Loi DB -> `500`

### 5.4 PUT/PATCH `/Api/Products/{id}` -> `update($id)`

Flow:

- Chi nhan `PUT` hoac `PATCH`
- Lay JSON payload
- Neu URL co `id` thi uu tien gan vao `ma_san_pham`
- Bat buoc phai co `ma_san_pham`
- Kiem tra ton tai truoc khi sua
- Goi `SanPham_update(...)`

Luu y:

- Implementation hien tai cap nhat truc tiep cac cot tu payload.
- Neu thieu field, controller dang day gia tri rong (`''`) cho field do.

### 5.5 DELETE `/Api/Products/{id}` -> `delete($id)`

Flow:

- Validate method `DELETE`
- Bat buoc co `id`
- Kiem tra ton tai
- Kiem tra rang buoc don hang (`SanPham_hasOrderDetails`)
- Goi `SanPham_delete($id)`

Status quan trong:

- `409`: khong cho xoa vi da phat sinh chi tiet don hang

### 5.6 POST `/Api/Products/import` -> `import()`

Dinh dang nhan:

- `multipart/form-data`
- key file: `file` hoac `txtfile`

Ho tro 2 kieu template:

- File mau A-E (`Ma san pham`, `Ten san pham`, `Ma danh muc`, `Ma thuong hieu`, `Ma nha cung cap`)
- File export A-H (co ten danh muc/thuong hieu/nha cung cap)

Flow:

- Doc Excel qua `PHPExcel_IOFactory`
- Validate header
- Duyet tung dong
- Bo qua dong trong ma SP
- Check trung ma
- Resolve danh muc/thuong hieu/nha cung cap theo ma hoac ten
- Insert vao DB
- Tong hop ket qua:
  - `created`
  - `duplicated_codes`
  - `failed_rows`

Status:

- `200`: import xong (co the co dong loi)
- `422`: khong tao duoc ban ghi nao
- `400`: file sai dinh dang/khong hop le

### 5.7 GET `/Api/Products/search` -> `search()`

Endpoint nay de tuong thich nguoc.
No map ve `get_all()` bang cach do gia tri vao `$_GET`.
Khuyen nghi hien tai van nen dung:

- `/Api/Products?ma_san_pham=...&ten_san_pham=...`

## 6) Bang endpoint tong hop

| Method | Endpoint | Chuc nang |
|---|---|---|
| GET | `/Api/Products` | Lay danh sach san pham |
| GET | `/Api/Products?ma_san_pham=SP01&ten_san_pham=iphone` | Tim kiem san pham |
| GET | `/Api/Products?format=xlsx` | Export danh sach ra Excel |
| GET | `/Api/Products/SP01` | Lay chi tiet san pham |
| POST | `/Api/Products` | Tao san pham |
| PUT/PATCH | `/Api/Products/SP01` | Cap nhat san pham |
| DELETE | `/Api/Products/SP01` | Xoa san pham |
| POST | `/Api/Products/import` | Import san pham tu Excel |
| GET | `/Api/Products/search?...` | Legacy search (tuong thich nguoc) |

## 7) Vi du test nhanh bang cURL

### Tao san pham

```bash
curl -X POST "http://localhost/Banhang/Api/Products" \
  -H "Content-Type: application/json" \
  -d "{\"ma_san_pham\":\"SP999\",\"ten_san_pham\":\"iPhone Test\",\"ma_danh_muc\":\"DM01\",\"ma_thuong_hieu\":\"TH01\",\"ma_nha_cung_cap\":\"NCC01\"}"
```

### Cap nhat san pham

```bash
curl -X PUT "http://localhost/Banhang/Api/Products/SP999" \
  -H "Content-Type: application/json" \
  -d "{\"ten_san_pham\":\"iPhone Test Updated\",\"ma_danh_muc\":\"DM01\",\"ma_thuong_hieu\":\"TH01\",\"ma_nha_cung_cap\":\"NCC01\"}"
```

### Xoa san pham

```bash
curl -X DELETE "http://localhost/Banhang/Api/Products/SP999"
```

### Import Excel

```bash
curl -X POST "http://localhost/Banhang/Api/Products/import" \
  -F "file=@D:/template_sanpham.xlsx"
```

## 8) Quy trinh tao Product REST API tu dau (checklist)

1. Tao/kiem tra base API controller (`api_controller.php`) co JSON + CORS + helper response.
2. Cap nhat router (`app.php`) de map method HTTP sang action RESTful.
3. Tao controller `Products.php` trong `Controllers/Api` voi cac action: `get_all`, `get_detail`, `create`, `update`, `delete`, `import`, `search`.
4. Hoan thien model `SanPham_m.php` voi day du ham CRUD + tim kiem + rang buoc xoa.
5. Them export Excel (`?format=xlsx`) neu can.
6. Test tung endpoint bang Postman/cURL theo dung method va payload.
7. Chuan hoa status code va message cho tung truong hop loi.

## 9) Luu y khi mo rong

- Nen bo sung validate du lieu chat hon (do dai, pattern ma, enum danh muc...).
- Nen dung prepared statements de an toan SQL tot hon.
- Co the them phan trang cho `get_all()` neu du lieu lon.
- Co the bo sung auth token cho API admin de dam bao bao mat.

---

Neu ban muon, buoc tiep theo co the viet them 1 file Postman Collection + Environment cho rieng Product API de import vao test ngay.
