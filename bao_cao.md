# Bao Cao API Tong Hop

## Base URL

```txt
http://localhost/Banhang/Api/
```

## Products - San pham

```txt
GET    /Api/Products                 # Lay danh sach san pham
```

## Users - Nguoi dung

```txt
GET    /Api/Users                    # Lay danh sach nguoi dung
GET    /Api/Users/U01                # Chi tiet nguoi dung
POST   /Api/Users                    # Tao nguoi dung
PUT    /Api/Users/U01                # Cap nhat nguoi dung
PATCH  /Api/Users/U01                # Cap nhat nguoi dung
DELETE /Api/Users/U01                # Xoa nguoi dung
GET    /Api/Users/search             # Tim kiem nguoi dung
POST   /Api/Users/import             # Import users tu Excel
```

## Cart - Gio hang

```txt
GET    /Api/Cart                     # Lay thong tin gio hang
GET    /Api/Cart/BT01                # Chi tiet item theo ma bien the
POST   /Api/Cart                     # Them vao gio hang
PUT    /Api/Cart/BT01                # Cap nhat so luong item
PATCH  /Api/Cart/BT01                # Cap nhat so luong item
DELETE /Api/Cart/BT01                # Xoa item khoi gio
GET    /Api/Cart/summary             # Tong quan gio hang
DELETE /Api/Cart/clear               # Xoa toan bo gio hang
POST   /Api/Cart/bulk_update         # Cap nhat nhieu item cung luc
```

## Donhang - Don hang

```txt
GET    /Api/Donhang                  # Lay danh sach don hang
GET    /Api/Donhang/DH01             # Chi tiet don hang
POST   /Api/Donhang                  # Tao don hang
PUT    /Api/Donhang/DH01             # Cap nhat don hang
PATCH  /Api/Donhang/DH01             # Cap nhat don hang
DELETE /Api/Donhang/DH01             # Xoa don hang
GET    /Api/Donhang/search           # Tim kiem don hang
PUT    /Api/Donhang/update_status/DH01   # Cap nhat trang thai don hang
PATCH  /Api/Donhang/update_status/DH01   # Cap nhat trang thai don hang
```

## Auth - Xac thuc

```txt
GET    /Api/Auth/profile             # Lay thong tin tai khoan dang dang nhap
POST   /Api/Auth/login               # Dang nhap
POST   /Api/Auth/register            # Dang ky
POST   /Api/Auth/logout              # Dang xuat
```

## Profile - Tai khoan

```txt
GET    /Api/Profile                  # Lay profile hien tai
PUT    /Api/Profile                  # Cap nhat profile
PATCH  /Api/Profile                  # Cap nhat profile
PUT    /Api/Profile/password         # Doi mat khau
PATCH  /Api/Profile/password         # Doi mat khau
```

## Checkout - Thanh toan

```txt
GET    /Api/Checkout                 # Lay du lieu checkout
GET    /Api/Checkout/ID              # Lay chi tiet checkout
POST   /Api/Checkout                 # Tao checkout / tao don thanh toan
PUT    /Api/Checkout/ID              # Cap nhat checkout
PATCH  /Api/Checkout/ID              # Cap nhat checkout
DELETE /Api/Checkout/ID              # Xoa checkout
POST   /Api/Checkout/preview         # Xem truoc thanh toan
```

## Search - Tim kiem

```txt
GET    /Api/Search                   # Tim kiem tong hop
GET    /Api/Search/ID                # Chi tiet ket qua tim kiem
POST   /Api/Search                   # Tao ban ghi tim kiem (neu su dung)
PUT    /Api/Search/ID                # Cap nhat ban ghi tim kiem
PATCH  /Api/Search/ID                # Cap nhat ban ghi tim kiem
DELETE /Api/Search/ID                # Xoa ban ghi tim kiem
GET    /Api/Search/suggestions       # Goi y tu khoa
GET    /Api/Search/history           # Lich su tim kiem
```

## Storefront - Trang chu

```txt
GET    /Api/Storefront               # Du lieu trang chu (san pham, danh muc...)
GET    /Api/Storefront/filters       # Bo loc trang chu
GET    /Api/Storefront/suggestions   # Goi y tim kiem trang chu
```

## Danhmuc - Danh muc

```txt
GET    /Api/Danhmuc                  # Lay danh sach danh muc
GET    /Api/Danhmuc/DM01             # Chi tiet danh muc
POST   /Api/Danhmuc                  # Tao danh muc
PUT    /Api/Danhmuc/DM01             # Cap nhat danh muc
PATCH  /Api/Danhmuc/DM01             # Cap nhat danh muc
DELETE /Api/Danhmuc/DM01             # Xoa danh muc
GET    /Api/Danhmuc/search           # Tim kiem danh muc
POST   /Api/Danhmuc/import           # Import danh muc tu Excel
```

## Thuonghieu - Thuong hieu

```txt
GET    /Api/Thuonghieu               # Lay danh sach thuong hieu
GET    /Api/Thuonghieu/TH01          # Chi tiet thuong hieu
POST   /Api/Thuonghieu               # Tao thuong hieu
PUT    /Api/Thuonghieu/TH01          # Cap nhat thuong hieu
PATCH  /Api/Thuonghieu/TH01          # Cap nhat thuong hieu
DELETE /Api/Thuonghieu/TH01          # Xoa thuong hieu
GET    /Api/Thuonghieu/search        # Tim kiem thuong hieu
POST   /Api/Thuonghieu/import        # Import thuong hieu tu Excel
```

## Nhacungcap - Nha cung cap

```txt
GET    /Api/Nhacungcap               # Lay danh sach nha cung cap
GET    /Api/Nhacungcap/NCC01         # Chi tiet nha cung cap
POST   /Api/Nhacungcap               # Tao nha cung cap
PUT    /Api/Nhacungcap/NCC01         # Cap nhat nha cung cap
PATCH  /Api/Nhacungcap/NCC01         # Cap nhat nha cung cap
DELETE /Api/Nhacungcap/NCC01         # Xoa nha cung cap
GET    /Api/Nhacungcap/search        # Tim kiem nha cung cap
POST   /Api/Nhacungcap/import        # Import nha cung cap tu Excel
```

## Bienthe - Bien the san pham

```txt
GET    /Api/Bienthe                  # Lay danh sach bien the
GET    /Api/Bienthe/BT01             # Chi tiet bien the
POST   /Api/Bienthe                  # Tao bien the
PUT    /Api/Bienthe/BT01             # Cap nhat bien the
PATCH  /Api/Bienthe/BT01             # Cap nhat bien the
DELETE /Api/Bienthe/BT01             # Xoa bien the
GET    /Api/Bienthe/search           # Tim kiem bien the
POST   /Api/Bienthe/import           # Import bien the tu Excel
```

## Danhgia - Danh gia

```txt
GET    /Api/Danhgia                  # Lay danh sach danh gia
GET    /Api/Danhgia/DG01             # Chi tiet danh gia
POST   /Api/Danhgia                  # Tao danh gia
PUT    /Api/Danhgia/DG01             # Cap nhat danh gia
PATCH  /Api/Danhgia/DG01             # Cap nhat danh gia
DELETE /Api/Danhgia/DG01             # Xoa danh gia
GET    /Api/Danhgia/search           # Tim kiem danh gia
```

## Khuyenmai - Khuyen mai

```txt
GET    /Api/Khuyenmai                # Lay danh sach khuyen mai
GET    /Api/Khuyenmai/KM01           # Chi tiet khuyen mai
POST   /Api/Khuyenmai                # Tao khuyen mai
PUT    /Api/Khuyenmai/KM01           # Cap nhat khuyen mai
PATCH  /Api/Khuyenmai/KM01           # Cap nhat khuyen mai
DELETE /Api/Khuyenmai/KM01           # Xoa khuyen mai
GET    /Api/Khuyenmai/search         # Tim kiem khuyen mai
POST   /Api/Khuyenmai/import         # Import khuyen mai tu Excel
```

## Ghi chu

- Cac route co dang /Api/{Controller}/{id} duoc map tu get_detail, update, delete trong controller.
- Cac route khac nhu search, import, summary, clear, bulk_update, filters, suggestions, history, preview la route action.
- Xac thuc hien tai su dung session cho cac endpoint can dang nhap.
