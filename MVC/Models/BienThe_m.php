<?php
class BienThe_m extends connectDB
{
    // Hàm thêm biến thể
    function bien_the_ins($ma_bien_the, $ma_san_pham, $ten_bien_the, $mau_sac, $ram, $dung_luong, $gia, $so_luong_kho)
    {
        $ma_bien_the = mysqli_real_escape_string($this->con, $ma_bien_the);
        $ma_san_pham = mysqli_real_escape_string($this->con, $ma_san_pham);
        $ten_bien_the = mysqli_real_escape_string($this->con, $ten_bien_the);
        $mau_sac = mysqli_real_escape_string($this->con, $mau_sac);
        $ram = mysqli_real_escape_string($this->con, $ram);
        $dung_luong = mysqli_real_escape_string($this->con, $dung_luong);
        $gia = mysqli_real_escape_string($this->con, $gia);
        $so_luong_kho = mysqli_real_escape_string($this->con, $so_luong_kho);
        
        $sql = "INSERT INTO bien_the (ma_bien_the, ma_san_pham, ten_bien_the, mau_sac, ram, dung_luong, gia, so_luong_kho) 
                VALUES ('$ma_bien_the', '$ma_san_pham', '$ten_bien_the', '$mau_sac', '$ram', '$dung_luong', '$gia', '$so_luong_kho')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã biến thể
    function checktrungMaBT($ma_bien_the)
    {
        $ma_bien_the = mysqli_real_escape_string($this->con, $ma_bien_the);
        $sql = "SELECT * FROM bien_the WHERE ma_bien_the = '$ma_bien_the'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã biến thể
        else
            return false; // Không trùng mã biến thể
    }

    // Hàm tìm kiếm biến thể
    function BienThe_find($ma_bien_the, $ten_bien_the)
    {
        $ma_bien_the = mysqli_real_escape_string($this->con, $ma_bien_the);
        $ten_bien_the = mysqli_real_escape_string($this->con, $ten_bien_the);
        $sql = "SELECT bt.*, sp.ten_san_pham 
                FROM bien_the bt
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                WHERE bt.ma_bien_the LIKE '%$ma_bien_the%' AND bt.ten_bien_the LIKE '%$ten_bien_the%'
                ORDER BY LENGTH(bt.ma_bien_the), bt.ma_bien_the";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa biến thể
    function BienThe_update($ma_bien_the, $ma_san_pham, $ten_bien_the, $mau_sac, $ram, $dung_luong, $gia, $so_luong_kho)
    {
        $ma_bien_the = mysqli_real_escape_string($this->con, $ma_bien_the);
        $ma_san_pham = mysqli_real_escape_string($this->con, $ma_san_pham);
        $ten_bien_the = mysqli_real_escape_string($this->con, $ten_bien_the);
        $mau_sac = mysqli_real_escape_string($this->con, $mau_sac);
        $ram = mysqli_real_escape_string($this->con, $ram);
        $dung_luong = mysqli_real_escape_string($this->con, $dung_luong);
        $gia = mysqli_real_escape_string($this->con, $gia);
        $so_luong_kho = mysqli_real_escape_string($this->con, $so_luong_kho);
        
        $sql = "UPDATE bien_the SET ma_san_pham = '$ma_san_pham', ten_bien_the = '$ten_bien_the', 
                mau_sac = '$mau_sac', ram = '$ram', dung_luong = '$dung_luong', 
                gia = '$gia', so_luong_kho = '$so_luong_kho' 
                WHERE ma_bien_the = '$ma_bien_the'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa biến thể
    function BienThe_delete($ma_bien_the)
    {
        $ma_bien_the = mysqli_real_escape_string($this->con, $ma_bien_the);
        $sql = "DELETE FROM bien_the WHERE ma_bien_the = '$ma_bien_the'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả biến thể
    function BienThe_getAll()
    {
        $sql = "SELECT bt.*, sp.ten_san_pham 
                FROM bien_the bt
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                ORDER BY LENGTH(bt.ma_bien_the), bt.ma_bien_the";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết biến thể
    function BienThe_getById($ma_bien_the)
    {
        $ma_bien_the = mysqli_real_escape_string($this->con, $ma_bien_the);
        $sql = "SELECT bt.*, sp.ten_san_pham 
                FROM bien_the bt
                LEFT JOIN san_pham sp ON bt.ma_san_pham = sp.ma_san_pham
                WHERE bt.ma_bien_the = '$ma_bien_the'";
        return mysqli_query($this->con, $sql);
    }
    
    // Hàm lấy biến thể theo sản phẩm
    function BienThe_getByProduct($ma_san_pham)
    {
        $ma_san_pham = mysqli_real_escape_string($this->con, $ma_san_pham);
        $sql = "SELECT * FROM bien_the WHERE ma_san_pham = '$ma_san_pham'";
        return mysqli_query($this->con, $sql);
    }
}