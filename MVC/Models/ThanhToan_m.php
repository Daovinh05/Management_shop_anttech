<?php
class ThanhToan_m extends connectDB
{
    // Hàm thêm thanh toán
    function thanhtoan_ins($ma_giao_dich, $ma_don_hang, $phuong_thuc, $so_tien_thanh_toan, $trang_thai_thanh_toan)
    {
        $sql = "INSERT INTO thanh_toan VALUES ('$ma_giao_dich', '$ma_don_hang', '$phuong_thuc', '$so_tien_thanh_toan', '$trang_thai_thanh_toan', NOW())";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã giao dịch
    function checktrungMaGD($ma_giao_dich)
    {
        $sql = "SELECT * FROM thanh_toan WHERE ma_giao_dich = '$ma_giao_dich'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã giao dịch
        else
            return false; // Không trùng mã giao dịch
    }

    // Hàm tìm kiếm thanh toán
    function ThanhToan_find($ma_giao_dich, $ma_don_hang)
    {
        $sql = "SELECT tt.*, dh.ma_user
                FROM thanh_toan tt
                LEFT JOIN don_hang dh ON tt.ma_don_hang = dh.ma_don_hang
                WHERE tt.ma_giao_dich LIKE '%$ma_giao_dich%' AND tt.ma_don_hang LIKE '%$ma_don_hang%'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa thanh toán
    function ThanhToan_update($ma_giao_dich, $ma_don_hang, $phuong_thuc, $so_tien_thanh_toan, $trang_thai_thanh_toan)
    {
        $sql = "UPDATE thanh_toan SET ma_don_hang = '$ma_don_hang', phuong_thuc = '$phuong_thuc',
                so_tien_thanh_toan = '$so_tien_thanh_toan', trang_thai_thanh_toan = '$trang_thai_thanh_toan',
                ngay_thanh_toan = NOW() WHERE ma_giao_dich = '$ma_giao_dich'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa thanh toán
    function ThanhToan_delete($ma_giao_dich)
    {
        $sql = "DELETE FROM thanh_toan WHERE ma_giao_dich = '$ma_giao_dich'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả thanh toán
    function ThanhToan_getAll()
    {
        $sql = "SELECT tt.*, dh.ma_user
                FROM thanh_toan tt
                LEFT JOIN don_hang dh ON tt.ma_don_hang = dh.ma_don_hang";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết thanh toán
    function ThanhToan_getById($ma_giao_dich)
    {
        $sql = "SELECT tt.*, dh.ma_user
                FROM thanh_toan tt
                LEFT JOIN don_hang dh ON tt.ma_don_hang = dh.ma_don_hang
                WHERE tt.ma_giao_dich = '$ma_giao_dich'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy thanh toán theo đơn hàng
    function ThanhToan_getByOrder($ma_don_hang)
    {
        $sql = "SELECT * FROM thanh_toan WHERE ma_don_hang = '$ma_don_hang'";
        return mysqli_query($this->con, $sql);
    }
}