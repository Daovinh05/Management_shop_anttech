<?php
class ChiTietGioHang_m extends connectDB
{
    // Hàm thêm chi tiết giỏ hàng
    function chitietgiohang_ins($ma_gio_hang, $ma_bien_the, $so_luong)
    {
        $sql = "INSERT INTO chi_tiet_gio_hang VALUES ('$ma_gio_hang', '$ma_bien_the', '$so_luong')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm tìm kiếm chi tiết giỏ hàng
    function ChiTietGioHang_find($ma_gio_hang, $ma_bien_the)
    {
        $sql = "SELECT ctgh.*, bt.ten_bien_the, sp.ten_san_pham, bt.gia
                FROM chi_tiet_gio_hang ctgh
                LEFT JOIN bien_the bt ON ctgh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                WHERE ctgh.ma_gio_hang LIKE '%$ma_gio_hang%' AND ctgh.ma_bien_the LIKE '%$ma_bien_the%'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa chi tiết giỏ hàng
    function ChiTietGioHang_update($ma_gio_hang, $ma_bien_the, $so_luong)
    {
        $sql = "UPDATE chi_tiet_gio_hang SET so_luong = '$so_luong' WHERE ma_gio_hang = '$ma_gio_hang' AND ma_bien_the = '$ma_bien_the'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa chi tiết giỏ hàng
    function ChiTietGioHang_delete($ma_gio_hang, $ma_bien_the)
    {
        $sql = "DELETE FROM chi_tiet_gio_hang WHERE ma_gio_hang = '$ma_gio_hang' AND ma_bien_the = '$ma_bien_the'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả chi tiết giỏ hàng
    function ChiTietGioHang_getAll()
    {
        $sql = "SELECT ctgh.*, bt.ten_bien_the, sp.ten_san_pham, bt.gia
                FROM chi_tiet_gio_hang ctgh
                LEFT JOIN bien_the bt ON ctgh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết giỏ hàng theo mã giỏ hàng
    function ChiTietGioHang_getByCartId($ma_gio_hang)
    {
        $sql = "SELECT ctgh.*, bt.ten_bien_the, bt.img_bien_the, sp.ten_san_pham, bt.gia, bt.so_luong_kho
                FROM chi_tiet_gio_hang ctgh
                LEFT JOIN bien_the bt ON ctgh.ma_bien_the = bt.ma_bien_the
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                WHERE ctgh.ma_gio_hang = '$ma_gio_hang'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa tất cả chi tiết giỏ hàng theo mã giỏ hàng
    function ChiTietGioHang_deleteByCartId($ma_gio_hang)
    {
        $sql = "DELETE FROM chi_tiet_gio_hang WHERE ma_gio_hang = '$ma_gio_hang'";
        return mysqli_query($this->con, $sql);
    }
}