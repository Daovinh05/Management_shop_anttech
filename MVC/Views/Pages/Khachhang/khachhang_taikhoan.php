<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Quản lý tài khoản</h2>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Thông tin tài khoản -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo $this->url('Khachhang/capnhatTaikhoan'); ?>">
                        <input type="hidden" name="txtMaUser" value="<?php echo $data['user']['ma_user']; ?>">
                        <input type="hidden" name="txtOldPassword" value="<?php echo $data['user']['password_hash']; ?>">
                        
                        <div class="form-group">
                            <label for="txtTenUser">Tên đăng nhập:</label>
                            <input type="text" class="form-control" id="txtTenUser" name="txtTenUser" 
                                   value="<?php echo $data['user']['ten_user']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="txtFullName">Họ và tên:</label>
                            <input type="text" class="form-control" id="txtFullName" name="txtFullName" 
                                   value="<?php echo $data['user']['full_name']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="txtEmail">Email:</label>
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" 
                                   value="<?php echo $data['user']['email']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="txtSoDienThoai">Số điện thoại:</label>
                            <input type="text" class="form-control" id="txtSoDienThoai" name="txtSoDienThoai" 
                                   value="<?php echo $data['user']['so_dien_thoai']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="txtPassword">Mật khẩu mới (để trống nếu không thay đổi):</label>
                            <input type="password" class="form-control" id="txtPassword" name="txtPassword" 
                                   placeholder="Nhập mật khẩu mới nếu muốn thay đổi">
                        </div>
                        
                        <button type="submit" class="btn btn-primary" name="btnCapNhatTaiKhoan">Cập nhật thông tin</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Địa chỉ giao hàng -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Địa chỉ giao hàng</h5>
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addAddressModal">
                        Thêm mới
                    </button>
                </div>
                <div class="card-body">
                    <?php if (mysqli_num_rows($data['dia_chi']) > 0): ?>
                        <?php while ($dc = mysqli_fetch_assoc($data['dia_chi'])): ?>
                            <div class="address-item border p-3 mb-3 rounded">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6><?php echo $dc['ho_ten']; ?></h6>
                                        <p class="mb-1"><?php echo $dc['dia_chi']; ?></p>
                                        <p class="mb-0"><?php echo $dc['so_dien_thoai']; ?></p>
                                        <?php if ($dc['mac_dinh'] == 1): ?>
                                            <span class="badge badge-primary">Mặc định</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                onclick="editAddress('<?php echo $dc['ma_dia_chi']; ?>', '<?php echo $dc['ho_ten']; ?>', '<?php echo $dc['so_dien_thoai']; ?>', '<?php echo $dc['dia_chi']; ?>', <?php echo $dc['mac_dinh']; ?>)">
                                            Sửa
                                        </button>
                                        <a href="<?php echo $this->url('Khachhang/xoaDiaChi/' . $dc['ma_dia_chi']); ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                            Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">Bạn chưa có địa chỉ giao hàng nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm địa chỉ -->
<div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm địa chỉ mới</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo $this->url('Khachhang/themDiaChi'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="txtMaDiaChi" value="DC<?php echo time(); ?>">
                    
                    <div class="form-group">
                        <label for="txtHoTen">Họ và tên:</label>
                        <input type="text" class="form-control" id="txtHoTen" name="txtHoTen" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="txtSoDienThoai">Số điện thoại:</label>
                        <input type="text" class="form-control" id="txtSoDienThoai" name="txtSoDienThoai" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="txtDiaChi">Địa chỉ:</label>
                        <textarea class="form-control" id="txtDiaChi" name="txtDiaChi" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="chkMacDinh" name="chkMacDinh" value="1">
                        <label class="form-check-label" for="chkMacDinh">Đặt làm địa chỉ mặc định</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="btnThemDiaChi">Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal sửa địa chỉ -->
<div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa địa chỉ</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo $this->url('Khachhang/capnhatDiaChi'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="txtMaDiaChi" id="editMaDiaChi" value="">
                    
                    <div class="form-group">
                        <label for="editHoTen">Họ và tên:</label>
                        <input type="text" class="form-control" id="editHoTen" name="txtHoTen" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editSoDienThoai">Số điện thoại:</label>
                        <input type="text" class="form-control" id="editSoDienThoai" name="txtSoDienThoai" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editDiaChi">Địa chỉ:</label>
                        <textarea class="form-control" id="editDiaChi" name="txtDiaChi" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="editMacDinh" name="chkMacDinh" value="1">
                        <label class="form-check-label" for="editMacDinh">Đặt làm địa chỉ mặc định</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="btnCapNhatDiaChi">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAddress(maDiaChi, hoTen, soDienThoai, diaChi, macDinh) {
    document.getElementById('editMaDiaChi').value = maDiaChi;
    document.getElementById('editHoTen').value = hoTen;
    document.getElementById('editSoDienThoai').value = soDienThoai;
    document.getElementById('editDiaChi').value = diaChi;
    
    // Check the default address checkbox if needed
    document.getElementById('editMacDinh').checked = (macDinh == 1);
    
    // Show the modal
    $('#editAddressModal').modal('show');
}
</script>