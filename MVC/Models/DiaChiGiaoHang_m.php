<?php
class DiaChiGiaoHang_m extends connectDB
{
    // Hàm thêm địa chỉ giao hàng
    function diachigiaohang_ins($ma_dia_chi, $ma_user, $ho_ten, $so_dien_thoai, $dia_chi, $mac_dinh)
    {
        $sql = "INSERT INTO dia_chi_giao_hang VALUES ('$ma_dia_chi', '$ma_user', '$ho_ten', '$so_dien_thoai', '$dia_chi', '$mac_dinh')";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã địa chỉ
    function checktrungMaDC($ma_dia_chi)
    {
        $sql = "SELECT * FROM dia_chi_giao_hang WHERE ma_dia_chi = '$ma_dia_chi'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã địa chỉ
        else
            return false; // Không trùng mã địa chỉ
    }

    // Hàm tìm kiếm địa chỉ giao hàng
    function DiaChiGiaoHang_find($ma_dia_chi, $ma_user)
    {
        $sql = "SELECT * FROM dia_chi_giao_hang
                WHERE ma_dia_chi LIKE '%$ma_dia_chi%' AND ma_user LIKE '%$ma_user%'
                ORDER BY mac_dinh DESC, LENGTH(ma_dia_chi), ma_dia_chi";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa địa chỉ giao hàng
    function DiaChiGiaoHang_update($ma_dia_chi, $ma_user, $ho_ten, $so_dien_thoai, $dia_chi, $mac_dinh)
    {
        // Nếu đặt làm địa chỉ mặc định, thì cập nhật các địa chỉ khác của cùng user thành không mặc định
        if($mac_dinh == 1) {
            $sql_reset = "UPDATE dia_chi_giao_hang SET mac_dinh = 0 WHERE ma_user = '$ma_user'";
            mysqli_query($this->con, $sql_reset);
        }
        
        $sql = "UPDATE dia_chi_giao_hang SET ma_user = '$ma_user', ho_ten = '$ho_ten',
                so_dien_thoai = '$so_dien_thoai', dia_chi = '$dia_chi', 
                mac_dinh = '$mac_dinh' WHERE ma_dia_chi = '$ma_dia_chi'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa địa chỉ giao hàng
    function DiaChiGiaoHang_delete($ma_dia_chi)
    {
        $sql = "DELETE FROM dia_chi_giao_hang WHERE ma_dia_chi = '$ma_dia_chi'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả địa chỉ giao hàng
    function DiaChiGiaoHang_getAll()
    {
        $sql = "SELECT dch.*, u.full_name 
                FROM dia_chi_giao_hang dch
                LEFT JOIN users u ON dch.ma_user = u.ma_user
                ORDER BY dch.mac_dinh DESC, LENGTH(dch.ma_dia_chi), dch.ma_dia_chi";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết địa chỉ giao hàng
    function DiaChiGiaoHang_getById($ma_dia_chi)
    {
        $sql = "SELECT dch.*, u.full_name 
                FROM dia_chi_giao_hang dch
                LEFT JOIN users u ON dch.ma_user = u.ma_user
                WHERE dch.ma_dia_chi = '$ma_dia_chi'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy địa chỉ giao hàng theo người dùng
    function DiaChiGiaoHang_getByUser($ma_user)
    {
        $sql = "SELECT * FROM dia_chi_giao_hang WHERE ma_user = '$ma_user' ORDER BY mac_dinh DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy địa chỉ mặc định theo người dùng
    function DiaChiGiaoHang_getDefaultByUser($ma_user)
    {
        $sql = "SELECT * FROM dia_chi_giao_hang WHERE ma_user = '$ma_user' AND mac_dinh = 1 LIMIT 1";
        return mysqli_query($this->con, $sql);
    }
}