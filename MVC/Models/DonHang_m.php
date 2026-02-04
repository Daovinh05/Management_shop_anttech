<?php
class DonHang_m extends connectDB
{
    // Hàm thêm đơn hàng
    function donhang_ins($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien_hang, $thanh_toan, $trang_thai_don_hang)
    {
        // Handle empty promotion code by converting to NULL for proper foreign key constraint
        $ma_khuyen_mai_value = !empty($ma_khuyen_mai) ? "'$ma_khuyen_mai'" : 'NULL';

        $sql = "INSERT INTO don_hang (ma_don_hang, ma_user, ma_dia_chi, ma_khuyen_mai, tong_tien_hang, thanh_toan, trang_thai_don_hang, ngay_tao)
                VALUES ('$ma_don_hang', '$ma_user', '$ma_dia_chi', $ma_khuyen_mai_value, '$tong_tien_hang', '$thanh_toan', '$trang_thai_don_hang', NOW())";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã đơn hàng
    function checktrungMaDH($ma_don_hang)
    {
        $sql = "SELECT * FROM don_hang WHERE ma_don_hang = '$ma_don_hang'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã đơn hàng
        else
            return false; // Không trùng mã đơn hàng
    }

    // Hàm tìm kiếm đơn hàng
    function DonHang_find($ma_don_hang, $full_name)
    {
        $sql = "SELECT dh.*, u.full_name, dc.ho_ten as ten_nguoi_nhan, dc.dia_chi, dc.so_dien_thoai
                FROM don_hang dh
                LEFT JOIN users u ON dh.ma_user = u.ma_user
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                WHERE dh.ma_don_hang LIKE '%$ma_don_hang%' AND u.full_name LIKE '%$full_name%'
                ORDER BY dh.ngay_tao DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa đơn hàng
    function DonHang_update($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien_hang, $trang_thai_don_hang)
    {
        // Handle empty promotion code by converting to NULL for proper foreign key constraint
        $ma_khuyen_mai_value = !empty($ma_khuyen_mai) ? "'$ma_khuyen_mai'" : 'NULL';

        $sql = "UPDATE don_hang SET ma_user = '$ma_user', ma_dia_chi = '$ma_dia_chi',
                ma_khuyen_mai = $ma_khuyen_mai_value, tong_tien_hang = '$tong_tien_hang',
                trang_thai_don_hang = '$trang_thai_don_hang' WHERE ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa đơn hàng
    function DonHang_delete($ma_don_hang)
    {
        $sql = "DELETE FROM don_hang WHERE ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả đơn hàng với thông tin người dùng
    function DonHang_getAll()
    {
        $sql = "SELECT dh.*, u.full_name, dc.ho_ten as ten_nguoi_nhan, dc.dia_chi, dc.so_dien_thoai , km.ten_khuyen_mai ,km.tien_khuyen_mai
                FROM don_hang dh
                LEFT JOIN users u ON dh.ma_user = u.ma_user
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                LEFT JOIN khuyen_mai km ON dh.ma_khuyen_mai = km.ma_khuyen_mai
                ORDER BY dh.ngay_tao DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết đơn hàng
    function DonHang_getById($ma_don_hang)
    {
        $sql = "SELECT dh.*, u.full_name, dc.ho_ten as ten_nguoi_nhan, dc.dia_chi, dc.so_dien_thoai
                FROM don_hang dh
                LEFT JOIN users u ON dh.ma_user = u.ma_user
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                WHERE dh.ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }


    // Lấy thống kê tổng quan về trạng thái đơn hàng
    function DonHang_ThongKeTongQuan()
    {
        $sql = "SELECT trang_thai_don_hang, COUNT(*) as so_luong
                FROM don_hang
                GROUP BY trang_thai_don_hang";
        return mysqli_query($this->con, $sql);
    }

    // Lấy danh sách đơn hàng chi tiết với tính toán lợi nhuận
    function DonHang_ThongKeChiTiet()
    {
        $sql = "SELECT
                    dh.ma_don_hang,
                    dh.ngay_tao,
                    dh.tong_tien_hang,
                    dh.trang_thai_don_hang,
                    dh.thanh_toan,
                    tt.phuong_thuc,
                    tt.trang_thai_thanh_toan,
                    u.full_name as ten_khach_hang,
                    u.so_dien_thoai,
                    dc.dia_chi,
                    km.tien_khuyen_mai,
                    COALESCE(SUM(ctdh.so_luong * bt.gia), 0) as gia_von,
                    COALESCE((dh.tong_tien_hang - km.tien_khuyen_mai), 0) as thanh_toan

                FROM don_hang dh
                INNER JOIN users u ON dh.ma_user = u.ma_user
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                LEFT JOIN thanh_toan tt ON dh.ma_don_hang = tt.ma_don_hang
                LEFT JOIN chi_tiet_don_hang ctdh ON dh.ma_don_hang = ctdh.ma_don_hang
                LEFT JOIN bien_the bt ON ctdh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN khuyen_mai km ON dh.ma_khuyen_mai = km.ma_khuyen_mai
                GROUP BY dh.ma_don_hang
                ORDER BY dh.ngay_tao DESC";
        return mysqli_query($this->con, $sql);
    }

    // Lấy thống kê theo phương thức thanh toán
    function DonHang_ThongKePhuongThuc()
    {
        $sql = "SELECT
                    tt.phuong_thuc,
                    COUNT(DISTINCT dh.ma_don_hang) as so_don,
                    SUM(dh.tong_tien_hang) as tong_tien
                FROM don_hang dh
                INNER JOIN thanh_toan tt ON dh.ma_don_hang = tt.ma_don_hang
                WHERE dh.trang_thai_don_hang != 'da_huy'
                GROUP BY tt.phuong_thuc";
        return mysqli_query($this->con, $sql);
    }

    // Lọc đơn hàng theo khoảng thời gian
    function DonHang_LocTheoNgay($tuNgay, $denNgay)
    {
        $sql = "SELECT
                    dh.ma_don_hang,
                    dh.ngay_tao,
                    dh.tong_tien_hang,
                    dh.trang_thai_don_hang,
                    tt.phuong_thuc,
                    hd.thanh_toan,
                    tt.trang_thai_thanh_toan,
                    u.full_name as ten_khach_hang,
                    u.so_dien_thoai,
                    dc.dia_chi,
                    km.tien_khuyen_mai,
                    COALESCE(SUM(ctdh.so_luong * bt.gia), 0) as gia_von,
                    COALESCE((dh.tong_tien_hang - km.tien_khuyen_mai), 0) as thanh_toan
                FROM don_hang dh
                INNER JOIN users u ON dh.ma_user = u.ma_user
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                LEFT JOIN thanh_toan tt ON dh.ma_don_hang = tt.ma_don_hang
                LEFT JOIN chi_tiet_don_hang ctdh ON dh.ma_don_hang = ctdh.ma_don_hang
                LEFT JOIN bien_the bt ON ctdh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN khuyen_mai km ON dh.ma_khuyen_mai = km.ma_khuyen_mai
                WHERE DATE(dh.ngay_tao) BETWEEN '$tuNgay' AND '$denNgay'
                GROUP BY dh.ma_don_hang
                ORDER BY dh.ngay_tao DESC";

        $result = mysqli_query($this->con, $sql);
        $danhSach = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $danhSach[] = $row;
            }
        }

        return $danhSach;
    }

    // Tìm kiếm đơn hàng theo mã đơn hàng hoặc tên khách hàng
    function DonHang_TimKiem($maDonHang, $tenKhachHang)
    {
        $sql = "SELECT
                    dh.ma_don_hang,
                    dh.ngay_tao,
                    dh.tong_tien_hang,
                    dh.trang_thai_don_hang,
                    dh.thanh_toan,
                    tt.phuong_thuc,
                    tt.trang_thai_thanh_toan,
                    u.full_name as ten_khach_hang,
                    u.so_dien_thoai,
                    dc.dia_chi,
                    km.tien_khuyen_mai,
                    COALESCE(SUM(ctdh.so_luong * bt.gia), 0) as gia_von,
                    COALESCE((dh.tong_tien_hang - km.tien_khuyen_mai), 0) as thanh_toan
                FROM don_hang dh
                INNER JOIN users u ON dh.ma_user = u.ma_user
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                LEFT JOIN thanh_toan tt ON dh.ma_don_hang = tt.ma_don_hang
                LEFT JOIN chi_tiet_don_hang ctdh ON dh.ma_don_hang = ctdh.ma_don_hang
                LEFT JOIN bien_the bt ON ctdh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN khuyen_mai km ON dh.ma_khuyen_mai = km.ma_khuyen_mai
                WHERE 1=1";

        if (!empty($maDonHang)) {
            $sql .= " AND dh.ma_don_hang LIKE '%$maDonHang%'";
        }

        if (!empty($tenKhachHang)) {
            $sql .= " AND u.full_name LIKE '%$tenKhachHang%'";
        }

        $sql .= " GROUP BY dh.ma_don_hang ORDER BY dh.ngay_tao DESC";

        return mysqli_query($this->con, $sql);
    }

    // Lấy thống kê doanh thu theo tháng
    function DonHang_ThongKeTheoThang($nam)
    {
        $sql = "SELECT
                    MONTH(ngay_tao) as thang,
                    COUNT(*) as so_don,
                    SUM(tong_tien_hang) as doanh_thu
                FROM don_hang
                WHERE YEAR(ngay_tao) = $nam
                AND trang_thai_don_hang != 'da_huy'
                GROUP BY MONTH(ngay_tao)
                ORDER BY thang";
        return mysqli_query($this->con, $sql);
    }

    // Lấy top sản phẩm bán chạy
    function DonHang_TopSanPham($limit = 10)
    {
        $sql = "SELECT
                    sp.ten_san_pham,
                    bt.ten_bien_the,
                    bt.mau_sac,
                    bt.ram,
                    bt.dung_luong,
                    SUM(ctdh.so_luong) as tong_ban,
                    SUM(ctdh.so_luong * ctdh.gia_luc_mua) as doanh_thu
                FROM chi_tiet_don_hang ctdh
                INNER JOIN bien_the bt ON ctdh.ma_bien_the = bt.ma_bien_the
                INNER JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                INNER JOIN don_hang dh ON ctdh.ma_don_hang = dh.ma_don_hang
                WHERE dh.trang_thai_don_hang != 'da_huy'
                GROUP BY ctdh.ma_bien_the
                ORDER BY tong_ban DESC
                LIMIT $limit";
        return mysqli_query($this->con, $sql);
    }

    // Lấy tổng doanh thu theo khoảng thời gian
    function DonHang_TongDoanhThu($tuNgay, $denNgay)
    {
        $sql = "SELECT
                    COUNT(*) as tong_don,
                    SUM(tong_tien_hang) as tong_doanh_thu,
                    AVG(tong_tien_hang) as trung_binh_don
                FROM don_hang
                WHERE DATE(ngay_tao) BETWEEN '$tuNgay' AND '$denNgay'
                AND trang_thai_don_hang != 'da_huy'";
        return mysqli_query($this->con, $sql);
    }

    // Lấy thống kê theo trạng thái thanh toán
    function DonHang_ThongKeThanhToan()
    {
        $sql = "SELECT
                    tt.trang_thai_thanh_toan,
                    COUNT(*) as so_luong,
                    SUM(tt.so_tien_thanh_toan) as tong_tien
                FROM thanh_toan tt
                INNER JOIN don_hang dh ON tt.ma_don_hang = dh.ma_don_hang
                WHERE dh.trang_thai_don_hang != 'da_huy'
                GROUP BY tt.trang_thai_thanh_toan";
        return mysqli_query($this->con, $sql);
    }

    // FILE: models/DonHang_m.php

    // Thêm hàm này vào cuối file Model
    function getChiTietDonHang($ma_don_hang) {
        // Sử dụng LEFT JOIN để đảm bảo nếu sản phẩm bị xóa thì vẫn hiện đơn hàng
        $sql = "SELECT
                    ct.*,
                    sp.ten_san_pham,
                    bt.img_bien_the as hinh_anh,
                    bt.ten_bien_the,
                    bt.mau_sac,
                    bt.ram,
                    bt.dung_luong
                FROM chi_tiet_don_hang ct
                LEFT JOIN bien_the bt ON ct.ma_bien_the = bt.ma_bien_the
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                WHERE ct.ma_don_hang = '$ma_don_hang'";

        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy mã đơn hàng tiếp theo theo thứ tự tăng dần
    function getNextOrderId() {
        $sql = "SELECT ma_don_hang FROM don_hang WHERE ma_don_hang LIKE 'DH%' ORDER BY LENGTH(ma_don_hang), ma_don_hang DESC LIMIT 1";
        $result = mysqli_query($this->con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $last_id = $row['ma_don_hang'];

            // Extract the numeric part after 'DH'
            $number = intval(substr($last_id, 2));
            $next_number = $number + 1;
        } else {
            // If no previous order exists, start from 1
            $next_number = 1;
        }

        // Format as DH with leading zeros (e.g., DH01, DH02, ..., DH10, DH11, etc.)
        return 'DH' . str_pad($next_number, 2, '0', STR_PAD_LEFT);
    }
}