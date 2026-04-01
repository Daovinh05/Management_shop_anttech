<?php
class DanhGia_m extends connectDB
{
    // Hàm thêm đánh giá
    function danhgia_ins($ma_danh_gia, $ma_user, $ma_san_pham, $so_sao, $noi_dung, $phan_hoi)
    {
        $ma_danh_gia = mysqli_real_escape_string($this->con, $ma_danh_gia);
        $ma_user = mysqli_real_escape_string($this->con, $ma_user);
        $ma_san_pham = mysqli_real_escape_string($this->con, $ma_san_pham);
        $so_sao = (int)$so_sao;
        $noi_dung = mysqli_real_escape_string($this->con, $noi_dung);
        $phan_hoi = mysqli_real_escape_string($this->con, $phan_hoi);

        $sql = "INSERT INTO danh_gia (ma_danh_gia, ma_user, ma_san_pham, so_sao, noi_dung, phan_hoi, ngay_danh_gia) VALUES ('$ma_danh_gia', '$ma_user', '$ma_san_pham', $so_sao, '$noi_dung', '$phan_hoi', NOW())";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã đánh giá
    function checktrungMaDG($ma_danh_gia)
    {
        $ma_danh_gia = mysqli_real_escape_string($this->con, $ma_danh_gia);
        $sql = "SELECT * FROM danh_gia WHERE ma_danh_gia = '$ma_danh_gia'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã đánh giá
        else
            return false; // Không trùng mã đánh giá
    }

    // Hàm tìm kiếm đánh giá (kèm tên khách hàng, tên sản phẩm)
    function DanhGia_find($ma_danh_gia, $ten_khach_hang, $ten_san_pham)
    {
        $ma_danh_gia = mysqli_real_escape_string($this->con, $ma_danh_gia);
        $ten_khach_hang = mysqli_real_escape_string($this->con, $ten_khach_hang);
        $ten_san_pham = mysqli_real_escape_string($this->con, $ten_san_pham);

        $sql = "SELECT dg.*, u.full_name, sp.ten_san_pham
                FROM danh_gia dg
                LEFT JOIN users u ON dg.ma_user = u.ma_user
                LEFT JOIN san_pham sp ON dg.ma_san_pham = sp.ma_san_pham
                WHERE 1=1";

        if (!empty($ma_danh_gia)) {
            $sql .= " AND dg.ma_danh_gia LIKE '%$ma_danh_gia%'";
        }

        if (!empty($ten_khach_hang)) {
            $sql .= " AND u.full_name LIKE '%$ten_khach_hang%'";
        }

        if (!empty($ten_san_pham)) {
            $sql .= " AND sp.ten_san_pham LIKE '%$ten_san_pham%'";
        }

        $sql .= " ORDER BY dg.ngay_danh_gia DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa đánh giá
    function DanhGia_update($ma_danh_gia, $so_sao, $noi_dung, $phan_hoi)
    {
        // Extract numeric value from so_sao if it contains text like "5 sao"
        if (preg_match('/(\d+)/', $so_sao, $matches)) {
            $so_sao = $matches[1];
        }

        $ma_danh_gia = mysqli_real_escape_string($this->con, $ma_danh_gia);
        $so_sao = (int)$so_sao;
        $noi_dung = mysqli_real_escape_string($this->con, $noi_dung);
        $phan_hoi = mysqli_real_escape_string($this->con, $phan_hoi);

        $sql = "UPDATE danh_gia SET so_sao = $so_sao, noi_dung = '$noi_dung', phan_hoi = '$phan_hoi' WHERE ma_danh_gia = '$ma_danh_gia'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa đánh giá
    function DanhGia_delete($ma_danh_gia)
    {
        $ma_danh_gia = mysqli_real_escape_string($this->con, $ma_danh_gia);
        $sql = "DELETE FROM danh_gia WHERE ma_danh_gia = '$ma_danh_gia'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả đánh giá
    function DanhGia_getAll()
    {
        $sql = "SELECT dg.*, u.full_name, sp.ten_san_pham
                FROM danh_gia dg
                LEFT JOIN users u ON dg.ma_user = u.ma_user
                LEFT JOIN san_pham sp ON dg.ma_san_pham = sp.ma_san_pham
                ORDER BY CAST(SUBSTRING(dg.ma_danh_gia, 3) AS UNSIGNED) DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết đánh giá
    function DanhGia_getById($ma_danh_gia)
    {
        $ma_danh_gia = mysqli_real_escape_string($this->con, $ma_danh_gia);
        $sql = "SELECT dg.*, u.full_name, sp.ten_san_pham
                FROM danh_gia dg
                LEFT JOIN users u ON dg.ma_user = u.ma_user
                LEFT JOIN san_pham sp ON dg.ma_san_pham = sp.ma_san_pham
                WHERE dg.ma_danh_gia = '$ma_danh_gia'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy đánh giá theo sản phẩm
    function DanhGia_getByProduct($ma_san_pham)
    {
        $ma_san_pham = mysqli_real_escape_string($this->con, $ma_san_pham);
        $sql = "SELECT dg.*, u.full_name
                FROM danh_gia dg
                LEFT JOIN users u ON dg.ma_user = u.ma_user
                WHERE dg.ma_san_pham = '$ma_san_pham'
                ORDER BY dg.ngay_danh_gia DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm tính trung bình đánh giá của sản phẩm
    function DanhGia_getAvgRatingByProduct($ma_san_pham)
    {
        $ma_san_pham = mysqli_real_escape_string($this->con, $ma_san_pham);
        $sql = "SELECT AVG(so_sao) as avg_rating FROM danh_gia WHERE ma_san_pham = '$ma_san_pham'";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['avg_rating'];
    }

    // Hàm lấy phân bố đánh giá theo số sao
    function DanhGia_getStarDistribution($ma_san_pham)
    {
        $ma_san_pham = mysqli_real_escape_string($this->con, $ma_san_pham);
        $sql = "SELECT so_sao, COUNT(*) as count FROM danh_gia WHERE ma_san_pham = '$ma_san_pham' GROUP BY so_sao ORDER BY so_sao DESC";
        $result = mysqli_query($this->con, $sql);

        // Initialize all stars from 5 to 1
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];

        while ($row = mysqli_fetch_assoc($result)) {
            $distribution[$row['so_sao']] = $row['count'];
        }

        return $distribution;
    }
}
