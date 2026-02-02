<?php
class GioHang_m extends connectDB
{
    // Hàm thêm giỏ hàng
    function giohang_ins($ma_gio_hang, $ma_user)
    {
        $sql = "INSERT INTO gio_hang VALUES ('$ma_gio_hang', '$ma_user', 'active', NOW())";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã giỏ hàng
    function checktrungMaGH($ma_gio_hang)
    {
        $sql = "SELECT * FROM gio_hang WHERE ma_gio_hang = '$ma_gio_hang'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã giỏ hàng
        else
            return false; // Không trùng mã giỏ hàng
    }

    // Hàm tìm kiếm giỏ hàng
    function GioHang_find($ma_gio_hang, $ma_user)
    {
        $sql = "SELECT gh.*, u.full_name 
                FROM gio_hang gh
                LEFT JOIN users u ON gh.ma_user = u.ma_user
                WHERE gh.ma_gio_hang LIKE '%$ma_gio_hang%' AND gh.ma_user LIKE '%$ma_user%'
                ORDER BY LENGTH(gh.ma_gio_hang), gh.ma_gio_hang";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa giỏ hàng
    function GioHang_update($ma_gio_hang, $ma_user, $trang_thai)
    {
        $sql = "UPDATE gio_hang SET ma_user = '$ma_user', trang_thai = '$trang_thai' WHERE ma_gio_hang = '$ma_gio_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa giỏ hàng
    function GioHang_delete($ma_gio_hang)
    {
        $sql = "DELETE FROM gio_hang WHERE ma_gio_hang = '$ma_gio_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả giỏ hàng với thông tin người dùng
    function GioHang_getAll()
    {
        $sql = "SELECT gh.*, u.full_name 
                FROM gio_hang gh
                LEFT JOIN users u ON gh.ma_user = u.ma_user
                ORDER BY LENGTH(gh.ma_gio_hang), gh.ma_gio_hang";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết giỏ hàng
    function GioHang_getById($ma_gio_hang)
    {
        $sql = "SELECT gh.*, u.full_name 
                FROM gio_hang gh
                LEFT JOIN users u ON gh.ma_user = u.ma_user
                WHERE gh.ma_gio_hang = '$ma_gio_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy giỏ hàng theo người dùng
    function GioHang_getByUser($ma_user)
    {
        $sql = "SELECT * FROM gio_hang WHERE ma_user = '$ma_user'";
        return mysqli_query($this->con, $sql);
    }
}