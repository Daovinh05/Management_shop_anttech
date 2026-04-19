<!DOCTYPE html>
<html lang="vi">

<body>
    <style>
        :root {
            --accent: #2463ff;
            --muted: #6b7280
        }

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
            gap: 12px;
            justify-content: flex-end;
            margin-top: 6px
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

        .btn-primary {
            background: var(--accent);
            color: #fff;
            padding: 10px 16px;
            border-radius: 10px;
            border: 0
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid #e6e9f2;
            color: var(--muted);
            padding: 10px 16px;
            border-radius: 10px
        }
    </style>

    <main class="card">
        <h1>Sửa thông tin User</h1>
        <form id="updateUserForm" enctype="multipart/form-data">
            <div>
                <label>Mã user</label>
                <input type="text" id="txtMauser" name="txtMauser" readonly />
            </div>
            <div>
                <label>Họ và tên <span style="color:red">*</span></label>
                <input type="text" id="txtHoten" name="txtHoten" required />
            </div>
            <div>
                <label>Tên tài khoản</label>
                <input type="text" id="txtTenuser" name="txtTenuser" required />
            </div>

            <div>
                <label>Mật khẩu</label>
                <input type="text" id="txtPassword" name="txtPassword" />
            </div>
            <div>
                <label>Email</label>
                <input type="email" id="txtEmail" name="txtEmail" />
            </div>
            <div>
                <label>Số điện thoại</label>
                <input type="text" id="txtSoDienThoai" name="txtSoDienThoai" />
            </div>
            <div>
                <label>Phân quyền</label>
                <select id="ddlPhanquyen" name="ddlPhanquyen">
                    <option value="admin">
                        Admin
                    </option>
                    <option value="khach_hang">
                        Khách hàng
                    </option>
                    <option value="nhan_vien">
                        Nhân viên
                    </option>
                </select>
            </div>
            <div>
                <label>Avatar</label>
                <div class="avatar-section">
                    <div class="avatar-circle" style="position: relative; display: inline-block;">
                        <img src="<?php echo UrlHelper::url('Public/Images/avatar.png'); ?>"
                             alt="Avatar người dùng" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;" id="avatar-preview">

                        <div class="camera-btn" id="camera-btn" style="position: absolute; bottom: 0; right: 0; background: #2463ff; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                    </div>

                    <input type="file" name="txtAvatar" id="avatar-input" accept="image/*" style="display: none;" />
                    <p style="margin-top: 10px;">Chọn file mới để thay đổi avatar</p>
                </div>
            </div>

            <div class="actions">
                <a href="<?php echo BASE_URL; ?>Users/danhsach" class="btn-back"><i class="fa-solid fa-arrow-left"></i>
                    Quay lại</a>
                <button type="submit" name="btnCapnhat" class="btn-primary">Cập nhật</button>
            </div>
        </form>
    </main>

    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';

        function resolveUserIdFromUrl() {
            const searchParams = new URLSearchParams(window.location.search);
            const routedUrl = searchParams.get('url');

            if (routedUrl) {
                const routeParts = routedUrl.split('/').filter(Boolean);
                if (routeParts.length > 0) {
                    return decodeURIComponent(routeParts[routeParts.length - 1]);
                }
            }

            const pathParts = window.location.pathname.replace(/\/+$/, '').split('/').filter(Boolean);
            return pathParts.length > 0 ? decodeURIComponent(pathParts[pathParts.length - 1]) : '';
        }

        function fillUserForm(user) {
            const setValue = (id, value) => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = value || '';
                }
            };

            setValue('txtMauser', user.ma_user || '');
            setValue('txtHoten', user.full_name || '');
            setValue('txtTenuser', user.ten_user || '');
            setValue('txtPassword', user.password || '');
            setValue('txtEmail', user.email || '');
            setValue('txtSoDienThoai', user.so_dien_thoai || '');

            const roleSelect = document.getElementById('ddlPhanquyen');
            if (roleSelect) {
                roleSelect.value = user.phan_quyen || 'khach_hang';
            }

            const avatarPreview = document.getElementById('avatar-preview');
            if (avatarPreview && user.avatar) {
                avatarPreview.src = BASE_URL + 'Public/Pictures/users/' + encodeURIComponent(user.avatar);
            }
        }

        function loadUserByApi() {
            const userId = resolveUserIdFromUrl();
            if (!userId) {
                alert('Không xác định được mã user từ URL.');
                return;
            }

            fetch(BASE_URL + 'Api/Users/' + encodeURIComponent(userId), {
                    method: 'GET'
                })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    return {
                        status: response.status,
                        data
                    };
                })
                .then((result) => {
                    if (result.status >= 200 && result.status < 300 && result.data.success && result.data.data) {
                        fillUserForm(result.data.data);
                        return;
                    }

                    alert('Không thể tải thông tin user: ' + (result.data.message || 'Lỗi không xác định'));
                })
                .catch((error) => {
                    alert('Không thể kết nối API user: ' + error.message);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadUserByApi();

            const cameraBtn = document.getElementById('camera-btn');
            const avatarInput = document.getElementById('avatar-input');
            const avatarPreview = document.getElementById('avatar-preview');

            if (cameraBtn && avatarInput && avatarPreview) {
                cameraBtn.addEventListener('click', function() {
                    avatarInput.click();
                });

                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        if (!validTypes.includes(file.type)) {
                            alert('Vui lòng chọn file ảnh (JPEG, PNG, GIF, WEBP)');
                            return;
                        }

                        if (file.size > 5 * 1024 * 1024) {
                            alert('File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 5MB');
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            avatarPreview.src = ev.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            const form = document.getElementById('updateUserForm');
            if (!form) {
                return;
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const maUser = (document.getElementById('txtMauser') || {}).value || '';
                if (!maUser.trim()) {
                    alert('Thiếu mã user để cập nhật');
                    return;
                }

                const formData = new FormData(form);

                fetch(BASE_URL + 'Api/Users/update/' + encodeURIComponent(maUser.trim()), {
                        method: 'POST',
                        body: formData
                    })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({}));
                        return {
                            status: response.status,
                            data
                        };
                    })
                    .then((result) => {
                        if (result.status >= 200 && result.status < 300 && result.data.success) {
                            alert('Cập nhật user thành công qua REST API');
                            window.location.href = BASE_URL + 'Users/danhsach';
                            return;
                        }

                        alert('Cập nhật user thất bại: ' + (result.data.message || 'Lỗi không xác định'));
                    })
                    .catch((error) => {
                        alert('Không thể kết nối API cập nhật user: ' + error.message);
                    });
            });
        });
    </script>
</body>

</html>
