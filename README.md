# Banhang

## 1. Clone

```bash
git clone <repository-url>
cd Banhang
```

## 2. Build & chạy với Docker

```bash
cp .env.docker.example .env
docker compose up -d
```

Truy cập: `http://localhost:${APP_PORT}`

## 3. Kiểm tra database

phpMyAdmin: `http://localhost:${PHPMYADMIN_PORT}`
- Server: `db`
- User: `${DB_USER}`
- Pass: `${DB_PASS}`

Import file `banhang.sql` nếu chưa có dữ liệu.
