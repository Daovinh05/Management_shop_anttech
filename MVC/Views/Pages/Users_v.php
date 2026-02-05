<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
        /* Simple form styles following existing pattern */
        .card {
            width: 100%;
            background: #fff;
            padding: 28px;
            border-radius: 12px
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #e3e7ef
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px
        }

        .btn-back {
            background: #6b7280;
            color: #fff;
            padding: 8px 15px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid #e6e9f2;
            color: #6b7280;
            padding: 10px 16px;
            border-radius: 10px
        }

        .btn-primary {
            background: #2463ff;
            color: #fff;
            padding: 10px 16px;
            border-radius: 10px;
            border: 0
        }
    </style>

    <div class="card">
        <h1>Thêm mới User</h1>
        <p class="lead">Nhập thông tin user mới.</p>
        <form method="post" action="http://localhost/Banhang/Users/ins" enctype="multipart/form-data">
            <div>
                <label>Mã user <span style="color:red">*</span></label>
                <input type="text" name="txtMauser" required
                    value="<?php echo isset($data['ma_user']) ? htmlspecialchars($data['ma_user']) : '' ?>" />
            </div>
            <div>
                <label>Họ và tên <span style="color:red">*</span></label>
                <input type="text" name="txtHoten" required
                    value="<?php echo isset($data['full_name']) ? htmlspecialchars($data['full_name']) : '' ?>" />
            </div>
            <div>
                <label>Số điện thoại</label>
                <input type="text" name="txtSoDienThoai"
                    value="<?php echo isset($data['so_dien_thoai']) ? htmlspecialchars($data['so_dien_thoai']) : '' ?>" />
            </div>
            <div>
                <label>Tên tài khoản <span style="color:red">*</span></label>
                <input type="text" name="txtTenuser" required
                    value="<?php echo isset($data['ten_user']) ? htmlspecialchars($data['ten_user']) : '' ?>" />
            </div>
            <div>
                <label>Mật khẩu</label>
                <input type="password" name="txtPassword" required
                    value="<?php echo isset($data['password']) ? htmlspecialchars($data['password']) : '' ?>" />
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="txtEmail" required
                    value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : '' ?>" />
            </div>
            <div>
                <label>Phân quyền</label>
                <select name="ddlPhanquyen">
                    <option value="nhan_vien"
                        <?php echo (isset($data['phan_quyen']) && $data['phan_quyen'] == 'nhan_vien') ? 'selected' : ''; ?>>
                        Nhân viên</option>
                    <option value="admin"
                        <?php echo (isset($data['phan_quyen']) && $data['phan_quyen'] == 'admin') ? 'selected' : ''; ?>>
                        Admin
                    </option>
                    <option value="khach_hang"
                        <?php echo (isset($data['phan_quyen']) && $data['phan_quyen'] == 'khach_hang') ? 'selected' : ''; ?>>
                        Khách hàng
                    </option>
                </select>
            </div>
            <div>
                <label>Avatar</label>
                <input type="file" name="txtAvatar" accept="image/*" />
                <?php if (isset($data['avatar']) && !empty($data['avatar'])): ?>
                    <div style="margin-top: 10px;">
                        <img src="/Banhang/Public/Pictures/users/<?php echo htmlspecialchars($data['avatar']); ?>"
                             alt="Avatar người dùng" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="actions">
                <a href="http://localhost/Banhang/Users/danhsach" class="btn-back"><i
                        class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <div style="display:flex;gap:12px">
                    <button type="reset" class="btn-ghost">Reset</button>
                    <button type="submit" name="btnLuu" class="btn-primary">Lưu thông tin</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>