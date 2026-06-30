# CI/CD với Docker & GitHub Actions — Hướng dẫn đầy đủ

## 1. Cấu trúc project

```
Banhang/
├── .github/
│   └── workflows/
│       └── deploy.yml          # GitHub Actions workflow
├── compose.yaml                # Docker Compose
├── Dockerfile                  # PHP 8.0 Apache
├── docker/
│   ├── apache.conf
│   └── db.Dockerfile           # MariaDB
├── .env                        # Biến môi trường (không commit)
├── .dockerignore
└── ...
```

## 2. Trên Ubuntu Server

### Cài đặt Docker (nếu chưa có)

```bash
# Cài Docker
curl -fsSL https://get.docker.com | sh

# Thêm user vào group docker (để không cần sudo)
sudo usermod -aG docker $USER

# Đăng xuất & đăng nhập lại (hoặc chạy: newgrp docker)
```

### Clone project lần đầu

```bash
mkdir -p ~/project && cd ~/project
git clone https://github.com/Congvinh2005/Banhang.git
cd Banhang
```

### Tạo file .env

```bash
nano .env
```

Nội dung (chỉnh sửa theo nhu cầu):

```
APP_PORT=8080
APP_BASE_URL=http://localhost:8080/Banhang/
DB_NAME=banhang
DB_USER=banhang
DB_PASS=banhang_secret
DB_ROOT_PASS=root_secret
DB_PORT=3307
PHPMYADMIN_PORT=8081
GROQ_API_KEY=
```

### Chạy thử Docker (kiểm tra)

```bash
cd ~/project/Banhang
docker compose up -d
```

Mở trình duyệt: `http://<IP_server>:8080/Banhang/`

### Cài GitHub Self-hosted Runner

Chọn đúng bản theo kiến trúc CPU:

| CPU | Link |
|-----|------|
| **Intel/AMD** (máy Windows thường) | `linux-x64` |
| **Apple Silicon M1/M2/M3** (Mac ARM) | `linux-arm64` |

```bash
# Tạo thư mục runner
mkdir -p ~/actions-runner && cd ~/actions-runner

# Download — chọn 1 trong 2:
# Intel/AMD (máy Windows thường):
curl -o actions-runner-linux-x64-2.322.0.tar.gz -L \
  https://github.com/actions/runner/releases/download/v2.322.0/actions-runner-linux-x64-2.322.0.tar.gz

# Apple Silicon M1/M2/M3 (Mac ARM):
curl -o actions-runner-linux-arm64-2.322.0.tar.gz -L \
  https://github.com/actions/runner/releases/download/v2.322.0/actions-runner-linux-arm64-2.322.0.tar.gz

# Giải nén
tar xzf actions-runner-linux-*.tar.gz

# Cấu hình (lấy token từ GitHub repo → Settings → Actions → Runners → New runner)
./config.sh --url https://github.com/Congvinh2005/Banhang --token <TOKEN_CỦA_BẠN>

# Chạy dưới dạng service (chạy nền 24/7)
sudo ./svc.sh install
sudo ./svc.sh start

# Kiểm tra
sudo ./svc.sh status

# (hoặc chạy tạm thời bằng: ./run.sh)
```

## 3. File .github/workflows/deploy.yml

```yaml
name: Deploy Docker

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: self-hosted

    steps:
      - name: Deploy
        run: |
          cd /home/vinh/project/Banhang
          git fetch origin main
          git reset --hard origin/main
          docker compose down
          docker compose up -d --build
```

## 4. Quy trình hoạt động

```
git push origin main
       │
       ▼
GitHub Actions nhận sự kiện push
       │
       ▼
Gửi job đến Self-hosted runner trên Ubuntu
       │
       ▼
Runner thực thi:
  1. cd /home/vinh/project/Banhang
  2. git fetch + git reset --hard (cập nhật code)
  3. docker compose down (dừng container cũ)
  4. docker compose up -d --build (build & chạy mới)
```

## 5. Lệnh thường dùng

| Mục đích | Lệnh |
|----------|------|
| Deploy thủ công | `git push origin main` |
| Kiểm tra runner | `sudo ./svc.sh status` (ở ~/actions-runner) |
| Xem log runner | `journalctl -u actions.runner.* -f` |
| Restart runner | `sudo ./svc.sh restart` (ở ~/actions-runner) |
| Dừng runner | `sudo ./svc.sh stop` (ở ~/actions-runner) |
| Xem container | `docker ps` |
| Xem log container | `docker compose logs -f` (ở ~/project/Banhang) |
| Dừng tất cả | `docker compose down` (ở ~/project/Banhang) |
| Build & chạy lại | `docker compose up -d --build` (ở ~/project/Banhang) |

## 6. Lưu ý cho Ubuntu VM trên Windows

- **Network**: Dùng **Bridge mode** để VM có IP riêng trong mạng LAN (VD: 192.168.0.x)
- **Kiến trúc**: Máy Windows thường là Intel/AMD → download runner bản `linux-x64`
- **Truy cập**: Dùng `ssh user@<IP_VM>` từ Windows (qua PowerShell hoặc WSL) để quản lý
- **Docker**: Cài Docker trong VM, không cần Docker Desktop cho Windows
- **Các bước còn lại** giống hệt Ubuntu Server ở trên
