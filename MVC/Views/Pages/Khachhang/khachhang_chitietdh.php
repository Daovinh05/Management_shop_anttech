<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Chi tiết đơn hàng #<?php echo $data['don_hang']['ma_don_hang']; ?></h2>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã đơn hàng:</strong> <?php echo $data['don_hang']['ma_don_hang']; ?></p>
                            <p><strong>Ngày đặt:</strong> <?php echo $this->formatDate($data['don_hang']['ngay_tao']); ?></p>
                            <p><strong>Trạng thái:</strong>
                                <span class="badge
                                    <?php
                                    switch($data['don_hang']['trang_thai_don_hang']) {
                                        case 'cho_duyet':
                                        case 'da_duyet': echo 'badge-warning'; break;
                                        case 'dang_giao': echo 'badge-info'; break;
                                        case 'hoan_thanh': echo 'badge-success'; break;
                                        case 'da_huy': echo 'badge-danger'; break;
                                        default: echo 'badge-secondary';
                                    }
                                    ?>">
                                    <?php
                                    switch($data['don_hang']['trang_thai_don_hang']) {
                                        case 'cho_duyet': echo 'Chờ xác nhận'; break;
                                        case 'da_duyet': echo 'Đã xác nhận'; break;
                                        case 'dang_giao': echo 'Đang giao hàng'; break;
                                        case 'hoan_thanh': echo 'Đã hoàn thành'; break;
                                        case 'da_huy': echo 'Đã hủy'; break;
                                        default: echo ucfirst(str_replace('_', ' ', $data['don_hang']['trang_thai_don_hang']));
                                    }
                                    ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <p><strong>Tổng tiền:</strong> <?php echo number_format($data['don_hang']['tong_tien_hang'], 0, ',', '.'); ?> VNĐ</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Người nhận:</strong> <?php echo $data['don_hang']['ten_nguoi_nhan']; ?></p>
                            <p><strong>Số điện thoại:</strong> <?php echo $data['don_hang']['so_dien_thoai']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Địa chỉ:</strong> <?php echo $data['don_hang']['dia_chi']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Chi tiết sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($data['chi_tiet_don_hang'] && mysqli_num_rows($data['chi_tiet_don_hang']) > 0): ?>
                                    <?php while ($ctdh = mysqli_fetch_assoc($data['chi_tiet_don_hang'])): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo !empty($ctdh['img_hinh_anh']) ? $ctdh['img_hinh_anh'] : $this->url('Public/Images/no-image.png'); ?>"
                                                         alt="<?php echo $ctdh['ten_san_pham']; ?>"
                                                         class="img-thumbnail mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0"><?php echo $ctdh['ten_san_pham']; ?></h6>
                                                        <small><?php echo $ctdh['ten_bien_the']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo number_format($ctdh['gia_luc_mua'], 0, ',', '.'); ?> VNĐ</td>
                                            <td><?php echo $ctdh['so_luong']; ?></td>
                                            <td><?php echo number_format($ctdh['gia_luc_mua'] * $ctdh['so_luong'], 0, ',', '.'); ?> VNĐ</td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Không có sản phẩm trong đơn hàng này</td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                                    <td><strong><?php echo number_format($data['don_hang']['tong_tien_hang'], 0, ',', '.'); ?> VNĐ</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="<?php echo $this->url('Khachhang/lichsumuahang'); ?>" class="btn btn-secondary">Quay lại lịch sử đơn hàng</a>
            </div>
        </div>
    </div>
</div>