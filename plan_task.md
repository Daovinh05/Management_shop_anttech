# Kế hoạch triển khai CI/CD với Docker & GitHub Actions

## Mục tiêu
Thiết lập pipeline CI/CD tự động: **Git push → GitHub Actions → SSH → Server → git pull → docker compose up -d --build**

## Kiến trúc

```
GitHub (source code)
    │
    ▼
GitHub Actions (.github/workflows/deploy.yml)
    │
    SSH (Private Key → GitHub Secrets)
    │
    ▼
Ubuntu Server
    │
    git pull
    docker compose up -d --build
    │
    ▼
Docker Containers
    ├── app (Apache + PHP 8.0)
    ├── db (MariaDB)
    └── phpmyadmin
```

## Danh sách công việc (Tasks)

### Task 1: Chuẩn bị Server (Ubuntu)
- [ ] Cài đặt Docker & Docker Compose trên Ubuntu Server (nếu chưa có)
- [ ] Tạo thư mục project trên server
- [ ] Clone repo từ GitHub lần đầu
- [ ] Tạo file `.env` trên server với các biến môi trường thật
- [ ] Chạy thử `docker compose up -d` để verify project hoạt động

### Task 2: Tạo SSH Key cho CI/CD
- [ ] Tạo SSH key pair riêng cho GitHub Actions (trên server hoặc local)
- [ ] Thêm public key vào `~/.ssh/authorized_keys` trên server
- [ ] Thêm private key vào GitHub Secrets với tên `SERVER_SSH_KEY`

### Task 3: Cấu hình GitHub Secrets
- [ ] `SERVER_SSH_KEY` — Private key SSH
- [ ] `SERVER_HOST` — IP hoặc domain của server
- [ ] `SERVER_USER` — Tên user SSH (ví dụ: `root` hoặc `ubuntu`)
- [ ] `SERVER_PATH` — Đường dẫn tuyệt đối đến thư mục project trên server

### Task 4: Tạo GitHub Actions Workflow
- [ ] Tạo file `.github/workflows/deploy.yml`
- [ ] Workflow trigger: `push` vào branch `main` (hoặc `master`)
- [ ] Steps: Checkout → SSH into server → git pull → docker compose up -d --build

### Task 5: Kiểm thử & Hoàn thiện
- [ ] Push code lên GitHub, verify workflow chạy thành công
- [ ] Kiểm tra container đã được build lại và chạy đúng
