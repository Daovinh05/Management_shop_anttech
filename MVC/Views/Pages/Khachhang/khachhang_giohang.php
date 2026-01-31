<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Giỏ hàng của bạn</h2>
            
            <?php if ($data['chi_tiet_gio_hang'] && mysqli_num_rows($data['chi_tiet_gio_hang']) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Hành động</th>
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
                                             class="img-thumbnail mr-3" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h6><?php echo $item['ten_san_pham']; ?></h6>
                                            <small><?php echo $item['ten_bien_the']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo number_format($item['gia'], 0, ',', '.'); ?> VNĐ</td>
                                <td>
                                    <form method="post" action="<?php echo $this->url('Khachhang/capnhatgiohang'); ?>" class="d-inline">
                                        <input type="hidden" name="ma_gio_hang" value="<?php echo $item['ma_gio_hang']; ?>">
                                        <input type="hidden" name="ma_bien_the" value="<?php echo $item['ma_bien_the']; ?>">
                                        <div class="input-group" style="width: 120px;">
                                            <input type="number" name="so_luong" value="<?php echo $item['so_luong']; ?>" 
                                                   class="form-control" min="1" onchange="this.form.submit()">
                                        </div>
                                    </form>
                                </td>
                                <td><?php echo number_format($thanh_tien, 0, ',', '.'); ?> VNĐ</td>
                                <td>
                                    <a href="<?php echo $this->url('Khachhang/xoakhoigio/' . $item['ma_gio_hang'] . '/' . $item['ma_bien_the']); ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                        Xóa
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            
                            <tr>
                                <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                                <td><strong><?php echo number_format($tong_tien, 0, ',', '.'); ?> VNĐ</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?php echo $this->url('Khachhang'); ?>" class="btn btn-secondary mr-2">Tiếp tục mua sắm</a>
                    <a href="<?php echo $this->url('Khachhang/thanhtoan'); ?>" class="btn btn-success">Tiến hành thanh toán</a>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h4>Giỏ hàng của bạn đang trống</h4>
                    <p>Thêm sản phẩm vào giỏ hàng để tiến hành mua sắm</p>
                    <a href="<?php echo $this->url('Khachhang'); ?>" class="btn btn-primary">Tiếp tục mua sắm</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>