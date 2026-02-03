<?php
class SanPham_m extends connectDB
{
    // Hàm thêm sản phẩm
    function sanpham_ins($ma_san_pham, $ten_san_pham, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap)
    {
        $sql = "INSERT INTO san_pham VALUES ('" . mysqli_real_escape_string($this->con, $ma_san_pham) . "',
                                               '" . mysqli_real_escape_string($this->con, $ten_san_pham) . "',
                                               '" . mysqli_real_escape_string($this->con, $ma_danh_muc) . "',
                                               '" . mysqli_real_escape_string($this->con, $ma_thuong_hieu) . "',
                                               '" . mysqli_real_escape_string($this->con, $ma_nha_cung_cap) . "',
                                               NOW())";
        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            error_log("Lỗi khi thêm sản phẩm: " . mysqli_error($this->con));
        }

        return $result;
    }

    // Hàm kiểm tra trùng mã sản phẩm
    function checktrungMaSP($ma_san_pham)
    {
        $sql = "SELECT * FROM san_pham WHERE ma_san_pham = '" . mysqli_real_escape_string($this->con, $ma_san_pham) . "'";
        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            error_log("Lỗi khi kiểm tra trùng mã sản phẩm: " . mysqli_error($this->con));
            return false; // Trong trường hợp lỗi, giả định là không trùng
        }

        if (mysqli_num_rows($result) > 0)
            return true; // Trùng mã sản phẩm
        else
            return false; // Không trùng mã sản phẩm
    }

    // Hàm tìm kiếm sản phẩm (kèm tên danh mục, thương hiệu, nhà cung cấp)
    function SanPham_find($ma_san_pham, $ten_san_pham)
    {
        $sql = "SELECT s.*,
                       NULL as gia,
                       NULL as so_luong_kho,
                       NULL as ten_bien_the,
                       '' as img_bien_the,
                       NULL as ten_danh_muc,
                       NULL as ten_thuong_hieu,
                       NULL as ten_nha_cung_cap
                FROM san_pham s
                WHERE s.ma_san_pham LIKE '%" . mysqli_real_escape_string($this->con, $ma_san_pham) . "'
                AND s.ten_san_pham LIKE '%" . mysqli_real_escape_string($this->con, $ten_san_pham) . "'
                ORDER BY LENGTH(s.ma_san_pham), s.ma_san_pham";

        $result = mysqli_query($this->con, $sql);

        // Kiểm tra nếu truy vấn thất bại
        if (!$result) {
            error_log("Lỗi truy vấn SQL trong SanPham_find: " . mysqli_error($this->con));
            return false;
        }

        return $result;
    }

    // Hàm sửa sản phẩm
    function SanPham_update($ma_san_pham, $ten_san_pham, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap)
    {
        $sql = "UPDATE san_pham SET ten_san_pham = '" . mysqli_real_escape_string($this->con, $ten_san_pham) . "',
                ma_danh_muc = '" . mysqli_real_escape_string($this->con, $ma_danh_muc) . "',
                ma_thuong_hieu = '" . mysqli_real_escape_string($this->con, $ma_thuong_hieu) . "',
                ma_nha_cung_cap = '" . mysqli_real_escape_string($this->con, $ma_nha_cung_cap) . "'
                WHERE ma_san_pham = '" . mysqli_real_escape_string($this->con, $ma_san_pham) . "'";
        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            error_log("Lỗi khi cập nhật sản phẩm: " . mysqli_error($this->con));
        }

        return $result;
    }

    // Hàm xóa sản phẩm
    function SanPham_delete($ma_san_pham)
    {
        $escaped_ma_san_pham = mysqli_real_escape_string($this->con, $ma_san_pham);

        // Xóa các biến thể liên quan trước (nếu bảng tồn tại)
        $check_bien_the = mysqli_query($this->con, "SHOW TABLES LIKE 'bien_the'");
        if (mysqli_num_rows($check_bien_the) > 0) {
            $delete_variants_sql = "DELETE FROM bien_the WHERE ma_san_pham = '$escaped_ma_san_pham'";
            mysqli_query($this->con, $delete_variants_sql);
        }

        // Sau đó xóa sản phẩm
        $sql = "DELETE FROM san_pham WHERE ma_san_pham = '$escaped_ma_san_pham'";
        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            error_log("Lỗi khi xóa sản phẩm: " . mysqli_error($this->con));
        }

        return $result;
    }

    // Hàm lấy tất cả sản phẩm với thông tin cơ bản
    function SanPham_getAll()
    {
        $sql = "SELECT s.*,
                       NULL as gia,
                       NULL as so_luong_kho,
                       NULL as ten_bien_the,
                       '' as img_bien_the,
                       NULL as ten_danh_muc,
                       NULL as ten_thuong_hieu,
                       NULL as ten_nha_cung_cap
                FROM san_pham s
                ORDER BY s.ma_san_pham DESC";
        $result = mysqli_query($this->con, $sql);

        // Kiểm tra nếu truy vấn thất bại
        if (!$result) {
            error_log("Lỗi truy vấn SQL trong SanPham_getAll: " . mysqli_error($this->con));
            return false;
        }

        return $result;
    }

    // Hàm lấy chi tiết sản phẩm
    function SanPham_getById($ma_san_pham)
    {
        $sql = "SELECT s.*,
                       NULL as ten_danh_muc,
                       NULL as ten_thuong_hieu,
                       NULL as ten_nha_cung_cap
                FROM san_pham s
                WHERE s.ma_san_pham = '" . mysqli_real_escape_string($this->con, $ma_san_pham) . "'";
        $result = mysqli_query($this->con, $sql);

        // Kiểm tra nếu truy vấn thất bại
        if (!$result) {
            error_log("Lỗi truy vấn SQL trong SanPham_getById: " . mysqli_error($this->con));
            return false;
        }

        return $result;
    }

    // Hàm lọc sản phẩm theo danh mục và mức giá
    function SanPham_filterByCategoryAndPrice($category_id = '', $price_range = '')
    {
        // Nếu cả hai điều kiện đều rỗng hoặc category_id là "tat-ca" (tức là chọn "Tất cả"), áp dụng logic giống SanPham_getAll()
        if ((empty($category_id) || $category_id === 'tat-ca') && empty($price_range)) {
            $sql = "SELECT s.*,
                           (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham LIMIT 1) as gia_moi,
                           (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL ORDER BY ma_bien_the LIMIT 1) as img_bien_the,
                           dm.ten_danh_muc, th.ten_thuong_hieu
                    FROM san_pham s
                    LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                    LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                    ORDER BY s.ma_san_pham DESC";
        } else {
            $conditions = array();

        // Thêm điều kiện lọc theo danh mục nếu có
        if (!empty($category_id) && $category_id !== 'tat-ca') {
            $conditions[] = "s.ma_danh_muc = '" . mysqli_real_escape_string($this->con, $category_id) . "'";
        }

            // Tạo câu truy vấn
            $where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "SELECT s.*,
                       NULL as gia_moi,
                       '' as img_bien_the,
                       NULL as ten_danh_muc,
                       NULL as ten_thuong_hieu
                FROM san_pham s
                $where_clause
                ORDER BY s.ma_san_pham DESC";

        $result = mysqli_query($this->con, $sql);

        // Kiểm tra nếu truy vấn thất bại
        if (!$result) {
            error_log("Lỗi truy vấn SQL trong SanPham_filterByCategoryAndPrice: " . mysqli_error($this->con));
            return false;
        }

        return $result;
    }
}