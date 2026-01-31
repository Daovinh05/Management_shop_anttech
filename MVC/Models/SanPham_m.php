<?php
class SanPham_m extends connectDB
{
    // Hàm thêm sản phẩm
    function sanpham_ins($ma_san_pham, $ten_san_pham, $img_hinh_anh, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap)
    {
        $sql = "INSERT INTO san_pham VALUES ('$ma_san_pham', '$ten_san_pham', '$img_hinh_anh', '$ma_danh_muc', '$ma_thuong_hieu', '$ma_nha_cung_cap', NOW())";
        return mysqli_query($this->con, $sql);
    }

    // Hàm kiểm tra trùng mã sản phẩm
    function checktrungMaSP($ma_san_pham)
    {
        $sql = "SELECT * FROM san_pham WHERE ma_san_pham = '$ma_san_pham'";
        $result = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã sản phẩm
        else
            return false; // Không trùng mã sản phẩm
    }

    // Hàm tìm kiếm sản phẩm (kèm tên danh mục, thương hiệu, nhà cung cấp)
    function SanPham_find($ma_san_pham, $ten_san_pham)
    {
        $sql = "SELECT s.*, bt.gia, bt.so_luong_kho, dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                LEFT JOIN bien_the bt ON s.ma_san_pham = bt.ma_san_pham
                WHERE s.ma_san_pham LIKE '%$ma_san_pham%' AND s.ten_san_pham LIKE '%$ten_san_pham%'
                ORDER BY LENGTH(s.ma_san_pham), s.ma_san_pham";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa sản phẩm
    function SanPham_update($ma_san_pham, $ten_san_pham, $img_hinh_anh, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap)
    {
        $sql = "UPDATE san_pham SET ten_san_pham = '$ten_san_pham', img_hinh_anh = '$img_hinh_anh',
                ma_danh_muc = '$ma_danh_muc', ma_thuong_hieu = '$ma_thuong_hieu', 
                ma_nha_cung_cap = '$ma_nha_cung_cap' WHERE ma_san_pham = '$ma_san_pham'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa sản phẩm
    function SanPham_delete($ma_san_pham)
    {
        $sql = "DELETE FROM san_pham WHERE ma_san_pham = '$ma_san_pham'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả sản phẩm với thông tin danh mục, thương hiệu, nhà cung cấp
    function SanPham_getAll()
    {
        $sql = "SELECT s.*, bt.gia, bt.so_luong_kho,  dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                LEFT JOIN bien_the bt ON s.ma_san_pham = bt.ma_san_pham
                ORDER BY LENGTH(s.ma_san_pham), s.ma_san_pham";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy chi tiết sản phẩm
    function SanPham_getById($ma_san_pham)
    {
        $sql = "SELECT s.*, dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap 
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                WHERE s.ma_san_pham = '$ma_san_pham'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lọc sản phẩm theo danh mục và mức giá
    function SanPham_filterByCategoryAndPrice($category_id = '', $price_range = '')
    {
        $conditions = array();

        // Thêm điều kiện lọc theo danh mục nếu có
        if (!empty($category_id) && $category_id !== 'tat-ca') {
            $conditions[] = "s.ma_danh_muc = '$category_id'";
        }

        // Thêm điều kiện lọc theo mức giá nếu có
        if (!empty($price_range)) {
            switch ($price_range) {
                case 'duoi-2-trieu':
                    $conditions[] = "bt.gia < 2000000";
                    break;
                case '2-4-trieu':
                    $conditions[] = "bt.gia >= 2000000 AND bt.gia < 4000000";
                    break;
                case '4-7-trieu':
                    $conditions[] = "bt.gia >= 4000000 AND bt.gia < 7000000";
                    break;
                case '7-13-trieu':
                    $conditions[] = "bt.gia >= 7000000 AND bt.gia < 13000000";
                    break;
                case 'tren-13-trieu':
                    $conditions[] = "bt.gia >= 13000000";
                    break;
            }
        }

        // Tạo câu truy vấn
        $where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "SELECT s.*, bt.gia AS gia_moi, dm.ten_danh_muc, th.ten_thuong_hieu
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN bien_the bt ON s.ma_san_pham = bt.ma_san_pham
                $where_clause
                ORDER BY bt.gia ASC";

        $result = mysqli_query($this->con, $sql);
        return $result;
    }
}