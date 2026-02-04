<?php
class ChiTietDonHang_m extends connectDB
{
    // Hàm thêm chi tiết đơn hàng
    function chitietdonhang_ins($ma_ctdh, $ma_don_hang, $ma_bien_the, $so_luong, $gia_luc_mua)
    {
        $sql = "INSERT INTO chi_tiet_don_hang VALUES ('$ma_ctdh', '$ma_don_hang', '$ma_bien_the', '$so_luong', '$gia_luc_mua')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm tìm kiếm chi tiết đơn hàng
    function ChiTietDonHang_find($ma_ctdh, $ma_don_hang)
    {
        $sql = "SELECT ctdh.*, bt.ten_bien_the, sp.ten_san_pham
                FROM chi_tiet_don_hang ctdh
                LEFT JOIN bien_the bt ON ctdh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                WHERE ctdh.ma_ctdh LIKE '%$ma_ctdh%' AND ctdh.ma_don_hang LIKE '%$ma_don_hang%'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa chi tiết đơn hàng
    function ChiTietDonHang_update($ma_ctdh, $ma_don_hang, $ma_bien_the, $so_luong, $gia_luc_mua)
    {
        $sql = "UPDATE chi_tiet_don_hang SET ma_don_hang = '$ma_don_hang', ma_bien_the = '$ma_bien_the',
                so_luong = '$so_luong', gia_luc_mua = '$gia_luc_mua' WHERE ma_ctdh = '$ma_ctdh'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa chi tiết đơn hàng
    function ChiTietDonHang_delete($ma_ctdh)
    {
        $sql = "DELETE FROM chi_tiet_don_hang WHERE ma_ctdh = '$ma_ctdh'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả chi tiết đơn hàng
    function ChiTietDonHang_getAll()
    {
        $sql = "SELECT ctdh.*, bt.ten_bien_the, sp.ten_san_pham
                FROM chi_tiet_don_hang ctdh
                LEFT JOIN bien_the bt ON ctdh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết đơn hàng theo mã đơn hàng
    function ChiTietDonHang_getByOrderId($ma_don_hang)
    {
        // Sử dụng hàm escape string để tránh lỗi SQL Injection
        $ma_don_hang = mysqli_real_escape_string($this->con, $ma_don_hang);

        $sql = "SELECT ctdh.*, bt.ten_bien_the, bt.mau_sac, bt.ram, bt.dung_luong, sp.ten_san_pham, sp.img_hinh_anh
                FROM chi_tiet_don_hang ctdh
                LEFT JOIN bien_the bt ON ctdh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                WHERE ctdh.ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa tất cả chi tiết đơn hàng theo mã đơn hàng
    function ChiTietDonHang_deleteByOrderId($ma_don_hang)
    {
        $sql = "DELETE FROM chi_tiet_don_hang WHERE ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy mã chi tiết đơn hàng tiếp theo theo thứ tự tăng dần
    function getNextDetailOrderId() {
        $sql = "SELECT ma_ctdh FROM chi_tiet_don_hang WHERE ma_ctdh LIKE 'CT%' ORDER BY LENGTH(ma_ctdh), ma_ctdh DESC LIMIT 1";
        $result = mysqli_query($this->con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $last_id = $row['ma_ctdh'];

            // Extract the numeric part after 'CT'
            $number = intval(substr($last_id, 2));
            $next_number = $number + 1;
        } else {
            // If no previous detail order exists, start from 1
            $next_number = 1;
        }

        // Format as CT with leading zeros (e.g., CT01, CT02, ..., CT10, CT11, etc.)
        return 'CT' . str_pad($next_number, 2, '0', STR_PAD_LEFT);
    }
}