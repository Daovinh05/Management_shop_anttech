<?php
class NhaCungCap_m extends connectDB
{
    // Hàm thêm nhà cung cấp
    function nhacungcap_ins($ma_nha_cung_cap, $ten_nha_cung_cap, $dia_chi, $dien_thoai)
    {
        $ma_nha_cung_cap = mysqli_real_escape_string($this->con, $ma_nha_cung_cap);
        $ten_nha_cung_cap = mysqli_real_escape_string($this->con, $ten_nha_cung_cap);
        $dia_chi = mysqli_real_escape_string($this->con, $dia_chi);
        $dien_thoai = mysqli_real_escape_string($this->con, $dien_thoai);
        $sql = "INSERT INTO nha_cung_cap VALUES ('$ma_nha_cung_cap', '$ten_nha_cung_cap', '$dia_chi', '$dien_thoai')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã nhà cung cấp
    function checktrungMaNCC($ma_nha_cung_cap)
    {
        $ma_nha_cung_cap = mysqli_real_escape_string($this->con, $ma_nha_cung_cap);
        $sql = "SELECT * FROM nha_cung_cap WHERE ma_nha_cung_cap = '$ma_nha_cung_cap'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã nhà cung cấp
        else
            return false; // Không trùng mã nhà cung cấp
    }

    // Hàm tìm kiếm nhà cung cấp
    function NhaCungCap_find($ma_nha_cung_cap, $ten_nha_cung_cap)
    {
        $ma_nha_cung_cap = mysqli_real_escape_string($this->con, $ma_nha_cung_cap);
        $ten_nha_cung_cap = mysqli_real_escape_string($this->con, $ten_nha_cung_cap);
        $sql = "SELECT * FROM nha_cung_cap
                WHERE ma_nha_cung_cap LIKE '%$ma_nha_cung_cap%' AND ten_nha_cung_cap LIKE '%$ten_nha_cung_cap%'
                ORDER BY LENGTH(ma_nha_cung_cap), ma_nha_cung_cap";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa nhà cung cấp
    function NhaCungCap_update($ma_nha_cung_cap, $ten_nha_cung_cap, $dia_chi, $dien_thoai)
    {
        $ma_nha_cung_cap = mysqli_real_escape_string($this->con, $ma_nha_cung_cap);
        $ten_nha_cung_cap = mysqli_real_escape_string($this->con, $ten_nha_cung_cap);
        $dia_chi = mysqli_real_escape_string($this->con, $dia_chi);
        $dien_thoai = mysqli_real_escape_string($this->con, $dien_thoai);
        $sql = "UPDATE nha_cung_cap SET ten_nha_cung_cap = '$ten_nha_cung_cap', 
                dia_chi = '$dia_chi', dien_thoai = '$dien_thoai' 
                WHERE ma_nha_cung_cap = '$ma_nha_cung_cap'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa nhà cung cấp
    function NhaCungCap_delete($ma_nha_cung_cap)
    {
        $ma_nha_cung_cap = mysqli_real_escape_string($this->con, $ma_nha_cung_cap);
        $sql = "DELETE FROM nha_cung_cap WHERE ma_nha_cung_cap = '$ma_nha_cung_cap'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả nhà cung cấp
    function NhaCungCap_getAll()
    {
        $sql = "SELECT * FROM nha_cung_cap
                ORDER BY LENGTH(ma_nha_cung_cap), ma_nha_cung_cap";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết nhà cung cấp
    function NhaCungCap_getById($ma_nha_cung_cap)
    {
        $ma_nha_cung_cap = mysqli_real_escape_string($this->con, $ma_nha_cung_cap);
        $sql = "SELECT * FROM nha_cung_cap
                WHERE ma_nha_cung_cap = '$ma_nha_cung_cap'";
        return mysqli_query($this->con, $sql);
    }
}