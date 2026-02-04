<?php
class ThuongHieu_m extends connectDB
{
    // Hàm thêm thương hiệu
    function thuonghieu_ins($ma_thuong_hieu, $ten_thuong_hieu)
    {
        $ma_thuong_hieu = mysqli_real_escape_string($this->con, $ma_thuong_hieu);
        $ten_thuong_hieu = mysqli_real_escape_string($this->con, $ten_thuong_hieu);
        $sql = "INSERT INTO thuong_hieu (ma_thuong_hieu, ten_thuong_hieu) VALUES ('$ma_thuong_hieu', '$ten_thuong_hieu')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã thương hiệu
    function checktrungMaTH($ma_thuong_hieu)
    {
        $ma_thuong_hieu = mysqli_real_escape_string($this->con, $ma_thuong_hieu);
        $sql = "SELECT * FROM thuong_hieu WHERE ma_thuong_hieu = '$ma_thuong_hieu'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã thương hiệu
        else
            return false; // Không trùng mã thương hiệu
    }

    // Hàm tìm kiếm thương hiệu
    function ThuongHieu_find($ma_thuong_hieu, $ten_thuong_hieu)
    {
        $ma_thuong_hieu = mysqli_real_escape_string($this->con, $ma_thuong_hieu);
        $ten_thuong_hieu = mysqli_real_escape_string($this->con, $ten_thuong_hieu);
        $sql = "SELECT * FROM thuong_hieu
                WHERE ma_thuong_hieu LIKE '%$ma_thuong_hieu%' AND ten_thuong_hieu LIKE '%$ten_thuong_hieu%'
                ORDER BY CAST(SUBSTRING(ma_thuong_hieu, 3) AS UNSIGNED) DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa thương hiệu
    function ThuongHieu_update($ma_thuong_hieu, $ten_thuong_hieu)
    {
        $ma_thuong_hieu = mysqli_real_escape_string($this->con, $ma_thuong_hieu);
        $ten_thuong_hieu = mysqli_real_escape_string($this->con, $ten_thuong_hieu);
        $sql = "UPDATE thuong_hieu SET ten_thuong_hieu = '$ten_thuong_hieu' WHERE ma_thuong_hieu = '$ma_thuong_hieu'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa thương hiệu
    function ThuongHieu_delete($ma_thuong_hieu)
    {
        $ma_thuong_hieu = mysqli_real_escape_string($this->con, $ma_thuong_hieu);
        $sql = "DELETE FROM thuong_hieu WHERE ma_thuong_hieu = '$ma_thuong_hieu'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả thương hiệu
    function ThuongHieu_getAll()
    {
        $sql = "SELECT * FROM thuong_hieu
                ORDER BY CAST(SUBSTRING(ma_thuong_hieu, 3) AS UNSIGNED) DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết thương hiệu
    function ThuongHieu_getById($ma_thuong_hieu)
    {
        $ma_thuong_hieu = mysqli_real_escape_string($this->con, $ma_thuong_hieu);
        $sql = "SELECT * FROM thuong_hieu
                WHERE ma_thuong_hieu = '$ma_thuong_hieu'";
        return mysqli_query($this->con, $sql);
    }
}
