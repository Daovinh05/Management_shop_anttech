<?php
class DanhMuc_m extends connectDB
{
    // Hàm thêm danh mục
    function danhmuc_ins($ma_danh_muc, $ten_danh_muc)
    {
        $sql = "INSERT INTO danh_muc VALUES ('$ma_danh_muc', '$ten_danh_muc')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã danh mục
    function checktrungMaDM($ma_danh_muc)
    {
        $sql = "SELECT * FROM danh_muc WHERE ma_danh_muc = '$ma_danh_muc'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã danh mục
        else
            return false; // Không trùng mã danh mục
    }

    // Hàm tìm kiếm danh mục
    function DanhMuc_find($ma_danh_muc, $ten_danh_muc)
    {
        $sql = "SELECT * FROM danh_muc
                WHERE ma_danh_muc LIKE '%$ma_danh_muc%' AND ten_danh_muc LIKE '%$ten_danh_muc%'
                ORDER BY LENGTH(ma_danh_muc), ma_danh_muc";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa danh mục
    function DanhMuc_update($ma_danh_muc, $ten_danh_muc)
    {
        $sql = "UPDATE danh_muc SET ten_danh_muc = '$ten_danh_muc' WHERE ma_danh_muc = '$ma_danh_muc'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa danh mục
    function DanhMuc_delete($ma_danh_muc)
    {
        $sql = "DELETE FROM danh_muc WHERE ma_danh_muc = '$ma_danh_muc'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả danh mục
    function DanhMuc_getAll()
    {
        $sql = "SELECT * FROM danh_muc
                ORDER BY LENGTH(ma_danh_muc), ma_danh_muc";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết danh mục
    function DanhMuc_getById($ma_danh_muc)
    {
        $sql = "SELECT * FROM danh_muc
                WHERE ma_danh_muc = '$ma_danh_muc'";
        return mysqli_query($this->con, $sql);
    }
}