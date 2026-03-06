# Hướng dẫn chạy project Spring Boot trên VS Code

## ✅ Checklist trước khi chạy

- [ ] Java 17+ đã cài
- [ ] Maven đã cài
- [ ] XAMPP (MySQL) đang chạy
- [ ] Extension Java & Spring Boot đã cài trên VS Code

## 🚀 Các bước chạy

### 1. Mở Terminal trong VS Code (Ctrl+`)

### 2. Chạy MySQL (nếu chưa chạy)
```bash
# Kiểm tra MySQL đang chạy
mysql.server status

# Nếu chưa chạy, start XAMPP hoặc chạy:
mysql.server start
```

### 3. Import Database (lần đầu tiên)
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/Banhang/springboot-banhang
mysql -u root -p phone_store_v2 < database/phone_store_v2.sql
```

### 4. Chạy ứng dụng
```bash
mvn spring-boot:run
```

### 5. Mở trình duyệt
```
http://localhost:8080/banhang
```

## 👤 Tài khoản test

| User | Password | Role |
|------|----------|------|
| vinh | 123 | Admin |
| long | 123 | Customer |

## 🎯 Shortcut trong VS Code

- **Ctrl+`** : Mở Terminal
- **Ctrl+Shift+P** : Command Palette
- **Ctrl+Shift+X** : Extensions
- **F5** : Debug
- **Ctrl+F5** : Run without debugging

## 🔧 Debug

1. Đặt breakpoint (click vào lề trái của file Java)
2. Click vào **Run and Debug** (sidebar trái)
3. Chọn **Debug BanHangApplication**

---
**Chúc bạn thành công!** 🚀
