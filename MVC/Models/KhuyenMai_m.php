<?php
class KhuyenMai_m extends connectDB
{
    // Hàm thêm khuyến mãi
    function khuyenmai_ins($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai_khuyen_mai)
    {
        $sql = "INSERT INTO khuyen_mai VALUES ('$ma_khuyen_mai', '$ten_khuyen_mai', '$tien_khuyen_mai', '$ngay_bat_dau', '$ngay_ket_thuc', '$trang_thai_khuyen_mai')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã khuyến mãi
    function checktrungMaKM($ma_khuyen_mai)
    {
        $sql = "SELECT * FROM khuyen_mai WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã khuyến mãi
        else
            return false; // Không trùng mã khuyến mãi
    }

    // Hàm tìm kiếm khuyến mãi
    function KhuyenMai_find($ma_khuyen_mai, $ten_khuyen_mai)
    {
        $current_date = date('Y-m-d');
        $sql = "SELECT *,
                       CASE
                           WHEN ngay_ket_thuc < '$current_date' THEN 'het'
                           ELSE 'con'
                       END AS trang_thai_khuyen_mai
                FROM khuyen_mai
                WHERE ma_khuyen_mai LIKE '%$ma_khuyen_mai%' AND ten_khuyen_mai LIKE '%$ten_khuyen_mai%'
                ORDER BY LENGTH(ma_khuyen_mai), ma_khuyen_mai";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa khuyến mãi
    function KhuyenMai_update($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai_khuyen_mai)
    {
        $sql = "UPDATE khuyen_mai SET ten_khuyen_mai = '$ten_khuyen_mai', tien_khuyen_mai = '$tien_khuyen_mai',
                ngay_bat_dau = '$ngay_bat_dau', ngay_ket_thuc = '$ngay_ket_thuc', 
                trang_thai_khuyen_mai = '$trang_thai_khuyen_mai' WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa khuyến mãi
    function KhuyenMai_delete($ma_khuyen_mai)
    {
        $sql = "DELETE FROM khuyen_mai WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả khuyến mãi
    function KhuyenMai_getAll()
    {
        $current_date = date('Y-m-d');
        $sql = "SELECT *,
                       CASE
                           WHEN ngay_ket_thuc < '$current_date' THEN 'het'
                           ELSE 'con'
                       END AS trang_thai_khuyen_mai
                FROM khuyen_mai
                ORDER BY LENGTH(ma_khuyen_mai), ma_khuyen_mai";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết khuyến mãi
    function KhuyenMai_getById($ma_khuyen_mai)
    {
        $current_date = date('Y-m-d');
        $sql = "SELECT *,
                       CASE
                           WHEN ngay_ket_thuc < '$current_date' THEN 'het'
                           ELSE 'con'
                       END AS trang_thai_khuyen_mai
                FROM khuyen_mai
                WHERE ma_khuyen_mai = '$ma_khuyen_mai'";
        return mysqli_query($this->con, $sql);
    }
}