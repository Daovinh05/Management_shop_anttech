<?php
class DonHang_m extends connectDB
{
    // Hàm thêm đơn hàng
    function donhang_ins($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien_hang, $trang_thai_don_hang)
    {
        $sql = "INSERT INTO don_hang VALUES ('$ma_don_hang', '$ma_user', '$ma_dia_chi', '$ma_khuyen_mai', '$tong_tien_hang', '$trang_thai_don_hang', NOW())";
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
    function DonHang_find($ma_don_hang, $ma_user)
    {
        $sql = "SELECT dh.*, u.full_name, dc.ho_ten as ten_nguoi_nhan, dc.dia_chi, dc.so_dien_thoai
                FROM don_hang dh
                LEFT JOIN users u ON dh.ma_user = u.ma_user
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
                WHERE dh.ma_don_hang LIKE '%$ma_don_hang%' AND dh.ma_user LIKE '%$ma_user%'
                ORDER BY dh.ngay_tao DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa đơn hàng
    function DonHang_update($ma_don_hang, $ma_user, $ma_dia_chi, $ma_khuyen_mai, $tong_tien_hang, $trang_thai_don_hang)
    {
        $sql = "UPDATE don_hang SET ma_user = '$ma_user', ma_dia_chi = '$ma_dia_chi',
                ma_khuyen_mai = '$ma_khuyen_mai', tong_tien_hang = '$tong_tien_hang', 
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
        $sql = "SELECT dh.*, u.full_name, dc.ho_ten as ten_nguoi_nhan, dc.dia_chi, dc.so_dien_thoai
                FROM don_hang dh
                LEFT JOIN users u ON dh.ma_user = u.ma_user
                LEFT JOIN dia_chi_giao_hang dc ON dh.ma_dia_chi = dc.ma_dia_chi
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
}