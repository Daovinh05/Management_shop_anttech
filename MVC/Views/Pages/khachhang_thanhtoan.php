<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Thanh toán đơn hàng</h2>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Giỏ hàng -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin sản phẩm</h5>
                </div>
                <div class="card-body">
                    <?php if ($data['chi_tiet_gio_hang'] && mysqli_num_rows($data['chi_tiet_gio_hang']) > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tong_tien = 0;
                                    while ($item = mysqli_fetch_assoc($data['chi_tiet_gio_hang'])):
                                        $thanh_tien = $item['gia'] * $item['so_luong'];
                                        $tong_tien += $thanh_tien;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo !empty($item['img_hinh_anh']) ? $item['img_hinh_anh'] : $this->url('Public/Images/no-image.png'); ?>" 
                                                     alt="<?php echo $item['ten_san_pham']; ?>" 
                                                     class="img-thumbnail mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0"><?php echo $item['ten_san_pham']; ?></h6>
                                                    <small><?php echo $item['ten_bien_the']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($item['gia'], 0, ',', '.'); ?> VNĐ</td>
                                        <td><?php echo $item['so_luong']; ?></td>
                                        <td><?php echo number_format($thanh_tien, 0, ',', '.'); ?> VNĐ</td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Tổng tạm tính:</strong></td>
                                        <td><strong><?php echo number_format($tong_tien, 0, ',', '.'); ?> VNĐ</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>Không có sản phẩm nào trong giỏ hàng.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Thông tin thanh toán -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin thanh toán</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo $this->url('Khachhang/datHang'); ?>">
                        <div class="form-group">
                            <label for="ddlDiaChi">Địa chỉ nhận hàng:</label>
                            <select class="form-control" id="ddlDiaChi" name="ddlDiaChi" required>
                                <option value="">Chọn địa chỉ</option>
                                <?php if ($data['dia_chi'] && mysqli_num_rows($data['dia_chi']) > 0): ?>
                                    <?php while ($dc = mysqli_fetch_assoc($data['dia_chi'])): ?>
                                        <option value="<?php echo $dc['ma_dia_chi']; ?>" 
                                                <?php echo $dc['mac_dinh'] == 1 ? 'selected' : ''; ?>>
                                            <?php echo $dc['ho_ten'] . ' - ' . $dc['dia_chi'] . ' - ' . $dc['so_dien_thoai']; ?>
                                            <?php echo $dc['mac_dinh'] == 1 ? '(Mặc định)' : ''; ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <option value="">Bạn chưa có địa chỉ nào</option>
                                <?php endif; ?>
                            </select>
                            <a href="#" class="mt-2 d-block">+ Thêm địa chỉ mới</a>
                        </div>
                        
                        <div class="form-group">
                            <label for="ddlKhuyenMai">Mã khuyến mãi:</label>
                            <select class="form-control" id="ddlKhuyenMai" name="ddlKhuyenMai">
                                <option value="">Không sử dụng</option>
                                <!-- Các mã khuyến mãi sẽ được load từ DB -->
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="txtGhiChu">Ghi chú:</label>
                            <textarea class="form-control" id="txtGhiChu" name="txtGhiChu" rows="3" 
                                      placeholder="Ghi chú cho đơn hàng của bạn"></textarea>
                        </div>
                        
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span><?php echo number_format($tong_tien, 0, ',', '.'); ?> VNĐ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Giảm giá:</span>
                                <span>0 VNĐ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Tổng cộng:</strong>
                                <strong><?php echo number_format($tong_tien, 0, ',', '.'); ?> VNĐ</strong>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-block" name="btnDatHang">
                                <i class="fas fa-check-circle"></i> Đặt hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>