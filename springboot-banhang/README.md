# TechZone - Hệ Thống Bán Lẻ Di Động Chính Hãng (Spring Boot Version)

Chào mừng bạn đến với **TechZone Spring Boot** - Phiên bản chuyển đổi từ PHP MVC sang Java Spring Boot 3.x

## 🌟 Giới thiệu

Dự án này là bản chuyển đổi hoàn chỉnh của hệ thống bán hàng TechZone từ PHP MVC sang **Java Spring Boot 3.x** với các công nghệ hiện đại:

- **Backend**: Spring Boot 3.2.0, Spring Data JPA, Spring Security 6
- **Database**: MySQL 8.0 / MariaDB 10.4+
- **Template Engine**: Thymeleaf 3
- **Build Tool**: Maven
- **Java Version**: 17+

## 🚀 Tính năng

### Người dùng (Khách hàng)
- ✅ Xem sản phẩm, lọc theo danh mục, thương hiệu
- ✅ Tìm kiếm sản phẩm
- ✅ Chi tiết sản phẩm với biến thể
- ✅ Giỏ hàng: Thêm/sửa/xóa
- ✅ Thanh toán, đặt hàng
- ✅ Đăng ký, đăng nhập
- ✅ Quản lý tài khoản cá nhân

### Quản trị viên (Admin)
- ✅ Dashboard thống kê
- ✅ Quản lý sản phẩm (CRUD)
- ✅ Quản lý danh mục, thương hiệu
- ✅ Quản lý đơn hàng
- ✅ Quản lý người dùng

## 🛠️ Yêu cầu hệ thống

| Thành phần | Yêu cầu |
|------------|---------|
| **Java** | JDK 17 trở lên |
| **Maven** | 3.6+ |
| **Database** | MySQL 8.0 hoặc MariaDB 10.4+ |
| **IDE** | IntelliJ IDEA / Eclipse / VS Code |

## ⚙️ Hướng dẫn cài đặt

### Bước 1: Cài đặt Java và Maven

**Windows:**
```bash
# Tải JDK 17: https://adoptium.net/
# Tải Maven: https://maven.apache.org/download.cgi
```

**macOS (sử dụng Homebrew):**
```bash
brew install openjdk@17
brew install maven
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install openjdk-17-jdk maven
```

### Bước 2: Cài đặt Database

1. Mở **phpMyAdmin** hoặc **MySQL Workbench**
2. Tạo database mới:
```sql
CREATE DATABASE phone_store_v2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Import file SQL:
```bash
mysql -u root -p phone_store_v2 < database/phone_store_v2.sql
```

Hoặc sử dụng phpMyAdmin:
- Chọn database `phone_store_v2`
- Vào tab **Import**
- Chọn file `database/phone_store_v2.sql`

### Bước 3: Cấu hình Database

Mở file `src/main/resources/application.properties` và cấu hình:

```properties
spring.datasource.url=jdbc:mysql://localhost:3306/phone_store_v2?useSSL=false&serverTimezone=Asia/Ho_Chi_Minh&characterEncoding=UTF-8
spring.datasource.username=root
spring.datasource.password=
```

> ⚠️ **Lưu ý**: Thay đổi `password` nếu MySQL của bạn có mật khẩu

### Bước 4: Build và Chạy Project

**Cách 1: Sử dụng Maven Wrapper (khuyến nghị)**
```bash
cd springboot-banhang
./mvnw spring-boot:run
```

**Cách 2: Sử dụng Maven trực tiếp**
```bash
cd springboot-banhang
mvn spring-boot:run
```

**Cách 3: Build JAR và chạy**
```bash
cd springboot-banhang
mvn clean package
java -jar target/banhang-1.0.0.jar
```

### Bước 5: Truy cập ứng dụng

Mở trình duyệt và truy cập:
```
http://localhost:8080/banhang
```

## 👤 Tài khoản Demo

| Vai trò | Tên đăng nhập | Mật khẩu |
|---------|--------------|----------|
| **Admin** | `vinh` | `123` |
| **Admin** | `hung` | `123` |
| **Khách hàng** | `long` | `123` |
| **Khách hàng** | `minh` | `1234` |

## 📂 Cấu trúc project

```
springboot-banhang/
├── src/
│   ├── main/
│   │   ├── java/com/techzone/banhang/
│   │   │   ├── BanHangApplication.java    # Main class
│   │   │   ├── config/                     # Cấu hình (Security, ...)
│   │   │   ├── controller/                 # Controllers
│   │   │   ├── entity/                     # JPA Entities
│   │   │   ├── repository/                 # Repositories
│   │   │   ├── service/                    # Service layer
│   │   │   └── dto/                        # DTOs
│   │   └── resources/
│   │       ├── application.properties      # Cấu hình ứng dụng
│   │       ├── templates/                  # Thymeleaf views
│   │       │   ├── home.html
│   │       │   ├── login.html
│   │       │   ├── register.html
│   │       │   └── admin/
│   │       └── static/                     # CSS, JS, Images
│   └── test/                               # Unit tests
├── database/
│   └── phone_store_v2.sql                  # Database script
├── pom.xml                                 # Maven dependencies
└── README.md
```

## 🔧 Các endpoint chính

### Public
| Method | URL | Mô tả |
|--------|-----|-------|
| GET | `/banhang/` | Redirect to home |
| GET | `/banhang/home` | Trang chủ |
| GET | `/banhang/login` | Đăng nhập |
| GET | `/banhang/register` | Đăng ký |

### Customer (Yêu cầu đăng nhập)
| Method | URL | Mô tả |
|--------|-----|-------|
| GET | `/banhang/khachhang` | Trang khách hàng |
| GET | `/banhang/khachhang/sanpham/{maDanhMuc}` | SP theo danh mục |
| GET | `/banhang/khachhang/chitietsp/{maSanPham}` | Chi tiết SP |
| GET | `/banhang/khachhang/giohang` | Giỏ hàng |
| GET | `/banhang/khachhang/thanhtoan` | Thanh toán |

### Admin (Yêu cầu quyền admin)
| Method | URL | Mô tả |
|--------|-----|-------|
| GET | `/banhang/admin` | Admin dashboard |
| GET | `/banhang/admin/sanpham` | Danh sách sản phẩm |
| GET | `/banhang/admin/sanpham/them` | Form thêm SP |
| POST | `/banhang/admin/sanpham/ins` | Lưu SP mới |
| GET | `/banhang/admin/sanpham/sua/{id}` | Form sửa SP |
| POST | `/banhang/admin/sanpham/update` | Cập nhật SP |
| GET | `/banhang/admin/sanpham/xoa/{id}` | Xóa SP |

## 🗺️ Lộ trình phát triển

### Đã hoàn thành ✅
- [x] Cấu trúc project Spring Boot 3.x
- [x] 15 Entity classes (User, SanPham, BienThe, DonHang, ...)
- [x] Repository interfaces với Spring Data JPA
- [x] Service layer (Interface + Implementation)
- [x] Spring Security configuration
- [x] Authentication (Login/Register/Logout)
- [x] Controllers (Home, Admin, Customer)
- [x] Thymeleaf templates (Home, Login, Register, Admin Dashboard)
- [x] Database script

### Đang phát triển 🚧
- [ ] Hoàn thiện CRUD cho tất cả entities
- [ ] Giỏ hàng và thanh toán
- [ ] Quản lý đơn hàng (Admin)
- [ ] Filter & Search sản phẩm
- [ ] Upload hình ảnh
- [ ] Export Excel/PDF
- [ ] REST API (nếu cần)

### Sẽ phát triển 📋
- [ ] Email confirmation
- [ ] Payment gateway (VNPay, Momo)
- [ ] Spring Boot Actuator (monitoring)
- [ ] Docker containerization
- [ ] Unit tests & Integration tests
- [ ] Frontend React/Vue.js (optional)

## 🐛 Xử lý sự cố

### Lỗi: "Port 8080 already in use"
```bash
# Windows
netstat -ano | findstr :8080
taskkill /PID <PID> /F

# Linux/Mac
lsof -i :8080
kill -9 <PID>
```

Hoặc thay đổi port trong `application.properties`:
```properties
server.port=8081
```

### Lỗi: "Access denied for user 'root'@'localhost'"
Kiểm tra lại username/password trong `application.properties`

### Lỗi: "Table doesn't exist"
Đảm bảo đã import file SQL vào database

### Lỗi: "Java version mismatch"
Đảm bảo đang sử dụng Java 17+:
```bash
java -version
```

## 📝 Công nghệ sử dụng

| Công nghệ | Version | Mục đích |
|-----------|---------|----------|
| Spring Boot | 3.2.0 | Framework |
| Spring Data JPA | 3.2.0 | ORM |
| Spring Security | 6.2.0 | Authentication & Authorization |
| Thymeleaf | 3.1.2 | Template Engine |
| MySQL Connector | 8.2.0 | Database Driver |
| Lombok | 1.18.30 | Reduce boilerplate |
| Maven | 3.9+ | Build Tool |

## 📄 License

Dự án mã nguồn mở, sử dụng cho mục đích học tập.

## 👥 Liên hệ

- **Email**: contact@techzone.com
- **Website**: https://techzone.com

---

**TechZone Spring Boot** - Mang công nghệ đến tầm tay bạn! 🚀
