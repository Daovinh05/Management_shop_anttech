<?php
class SanPham_m extends connectDB
{
    private function buildStorefrontConditions($category_id = '', $price_range = '', $brand_id = '', $search_query = '')
    {
        $conditions = [];

        if (!empty($category_id) && $category_id !== 'tat-ca') {
            $category_id = mysqli_real_escape_string($this->con, $category_id);
            $conditions[] = "s.ma_danh_muc = '$category_id'";
        }

        if (!empty($brand_id) && $brand_id !== 'tat-ca') {
            $brand_id = mysqli_real_escape_string($this->con, $brand_id);
            $conditions[] = "s.ma_thuong_hieu = '$brand_id'";
        }

        if (!empty($search_query)) {
            $search_query = mysqli_real_escape_string($this->con, $search_query);
            $conditions[] = "s.ten_san_pham LIKE '%$search_query%'";
        }

        if (!empty($price_range) && $price_range !== 'tat-ca') {
            switch ($price_range) {
                case 'duoi-2-trieu':
                    $conditions[] = "EXISTS (SELECT 1 FROM bien_the bt WHERE bt.ma_san_pham = s.ma_san_pham AND bt.gia < 2000000)";
                    break;
                case '2-4-trieu':
                    $conditions[] = "EXISTS (SELECT 1 FROM bien_the bt WHERE bt.ma_san_pham = s.ma_san_pham AND bt.gia >= 2000000 AND bt.gia < 4000000)";
                    break;
                case '4-7-trieu':
                    $conditions[] = "EXISTS (SELECT 1 FROM bien_the bt WHERE bt.ma_san_pham = s.ma_san_pham AND bt.gia >= 4000000 AND bt.gia < 7000000)";
                    break;
                case '7-13-trieu':
                    $conditions[] = "EXISTS (SELECT 1 FROM bien_the bt WHERE bt.ma_san_pham = s.ma_san_pham AND bt.gia >= 7000000 AND bt.gia < 13000000)";
                    break;
                case 'tren-13-trieu':
                    $conditions[] = "EXISTS (SELECT 1 FROM bien_the bt WHERE bt.ma_san_pham = s.ma_san_pham AND bt.gia >= 13000000)";
                    break;
                default:
                    break;
            }
        }

        return $conditions;
    }

    function SanPham_getStorefront($category_id = '', $price_range = '', $brand_id = '', $search_query = '', $page = 1, $limit = 12)
    {
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;

        $conditions = $this->buildStorefrontConditions($category_id, $price_range, $brand_id, $search_query);
        $where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT s.*,
                       (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as gia,
                       (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as gia_moi,
                       (SELECT so_luong_kho FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as so_luong_kho,
                       (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL ORDER BY ma_bien_the LIMIT 1) as img_bien_the,
                       dm.ten_danh_muc, th.ten_thuong_hieu
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                $where_clause
                ORDER BY CAST(SUBSTRING(s.ma_san_pham, 3) AS UNSIGNED) DESC
                LIMIT $limit OFFSET $offset";

        return mysqli_query($this->con, $sql);
    }

    function SanPham_countStorefront($category_id = '', $price_range = '', $brand_id = '', $search_query = '')
    {
        $conditions = $this->buildStorefrontConditions($category_id, $price_range, $brand_id, $search_query);
        $where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT COUNT(*) as total
                FROM san_pham s
                $where_clause";

        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);
        return (int)($row['total'] ?? 0);
    }

    // Hàm thêm sản phẩm
    function sanpham_ins($ma_san_pham, $ten_san_pham, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap)
    {
        $sql = "INSERT INTO san_pham VALUES ('$ma_san_pham', '$ten_san_pham', '$ma_danh_muc', '$ma_thuong_hieu', '$ma_nha_cung_cap', NOW())";
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
        $sql = "SELECT s.*, bt.gia, bt.so_luong_kho, bt.img_bien_the, dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                LEFT JOIN bien_the bt ON s.ma_san_pham = bt.ma_san_pham
                WHERE s.ma_san_pham LIKE '%$ma_san_pham%' AND s.ten_san_pham LIKE '%$ten_san_pham%'
                ORDER BY CAST(SUBSTRING(s.ma_san_pham, 3) AS UNSIGNED) DESC";
        return mysqli_query($this->con, $sql);
    }

    // Hàm sửa sản phẩm
    function SanPham_update($ma_san_pham, $ten_san_pham, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap)
    {
        $sql = "UPDATE san_pham SET ten_san_pham = '$ten_san_pham',
                ma_danh_muc = '$ma_danh_muc', ma_thuong_hieu = '$ma_thuong_hieu',
                ma_nha_cung_cap = '$ma_nha_cung_cap' WHERE ma_san_pham = '$ma_san_pham'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm xóa sản phẩm
    function SanPham_delete($ma_san_pham)
    {
        // Xóa các biến thể liên quan trước
        $delete_variants_sql = "DELETE FROM bien_the WHERE ma_san_pham = '$ma_san_pham'";
        mysqli_query($this->con, $delete_variants_sql);

        // Sau đó xóa sản phẩm
        $sql = "DELETE FROM san_pham WHERE ma_san_pham = '$ma_san_pham'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lấy tất cả sản phẩm với thông tin danh mục, thương hiệu, nhà cung cấp
    function SanPham_getAll()
    {
        $sql = "SELECT s.*,
                       (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham LIMIT 1) as gia,
                       (SELECT so_luong_kho FROM bien_the WHERE ma_san_pham = s.ma_san_pham LIMIT 1) as so_luong_kho,
                       (SELECT ten_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham LIMIT 1) as ten_bien_the,
                       (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL ORDER BY ma_bien_the LIMIT 1) as img_bien_the,
                       dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                ORDER BY CAST(SUBSTRING(s.ma_san_pham, 3) AS UNSIGNED) DESC";
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

    // Hàm lọc sản phẩm theo danh mục, mức giá và thương hiệu
    function SanPham_filterByCategoryAndPrice($category_id = '', $price_range = '', $brand_id = '')
    {
        // Nếu cả ba điều kiện đều rỗng hoặc category_id là "tat-ca" (tức là chọn "Tất cả"), áp dụng logic giống SanPham_getAll()
        if ((empty($category_id) || $category_id === 'tat-ca') && empty($price_range) && (empty($brand_id) || $brand_id === 'tat-ca')) {
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
                $conditions[] = "s.ma_danh_muc = '$category_id'";
            }

            // Thêm điều kiện lọc theo mức giá nếu có
            if (!empty($price_range)) {
                switch ($price_range) {
                    case 'tat-ca':
                        // Không áp dụng điều kiện lọc giá nào cả
                        break;
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
                    default:
                        // Nếu có giá trị không hợp lệ, không áp dụng điều kiện
                        break;
                }
            }

            // Thêm điều kiện lọc theo thương hiệu nếu có
            if (!empty($brand_id) && $brand_id !== 'tat-ca') {
                $conditions[] = "s.ma_thuong_hieu = '$brand_id'";
            }

            // Tạo câu truy vấn
            $where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

            $sql = "SELECT s.*, bt.gia AS gia_moi, bt.img_bien_the, dm.ten_danh_muc, th.ten_thuong_hieu
                    FROM san_pham s
                    LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                    LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                    LEFT JOIN bien_the bt ON s.ma_san_pham = bt.ma_san_pham
                    $where_clause
                    GROUP BY s.ma_san_pham
                    ORDER BY s.ma_san_pham DESC";
        }

        $result = mysqli_query($this->con, $sql);
        return $result;
    }

    // Hàm tìm kiếm sản phẩm theo tên
    function SanPham_searchByName($search_query)
    {
        $sql = "SELECT s.*, dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                WHERE s.ten_san_pham LIKE '%" . mysqli_real_escape_string($this->con, $search_query) . "%'
                ORDER BY LENGTH(s.ma_san_pham), s.ma_san_pham";
        return mysqli_query($this->con, $sql);
    }

    function SanPham_searchStorefront($search_query, $page = 1, $limit = 12)
    {
        $search_query = trim((string)$search_query);
        if ($search_query === '') {
            return false;
        }

        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;

        $escaped = mysqli_real_escape_string($this->con, $search_query);

        $sql = "SELECT s.*,
                       (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as gia,
                       (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as gia_moi,
                       (SELECT so_luong_kho FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as so_luong_kho,
                       (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL ORDER BY ma_bien_the LIMIT 1) as img_bien_the,
                       dm.ten_danh_muc, th.ten_thuong_hieu
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                WHERE s.ten_san_pham LIKE '%$escaped%'
                   OR s.ma_san_pham LIKE '%$escaped%'
                ORDER BY CAST(SUBSTRING(s.ma_san_pham, 3) AS UNSIGNED) DESC
                LIMIT $limit OFFSET $offset";

        return mysqli_query($this->con, $sql);
    }

    function SanPham_countSearchStorefront($search_query)
    {
        $search_query = trim((string)$search_query);
        if ($search_query === '') {
            return 0;
        }

        $escaped = mysqli_real_escape_string($this->con, $search_query);
        $sql = "SELECT COUNT(*) as total
                FROM san_pham s
                WHERE s.ten_san_pham LIKE '%$escaped%'
                   OR s.ma_san_pham LIKE '%$escaped%'";

        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);
        return (int)($row['total'] ?? 0);
    }

    function SanPham_getSearchSuggestions($search_query, $limit = 8)
    {
        $search_query = trim((string)$search_query);
        if ($search_query === '') {
            return false;
        }

        $limit = max(1, min(20, (int)$limit));
        $escaped = mysqli_real_escape_string($this->con, $search_query);

        $sql = "SELECT s.ma_san_pham,
                       s.ten_san_pham,
                       (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL ORDER BY ma_bien_the LIMIT 1) as img_bien_the
                FROM san_pham s
                WHERE s.ten_san_pham LIKE '%$escaped%'
                   OR s.ma_san_pham LIKE '%$escaped%'
                ORDER BY CHAR_LENGTH(s.ten_san_pham), s.ten_san_pham ASC
                LIMIT $limit";

        return mysqli_query($this->con, $sql);
    }

    function SanPham_getStorefrontDetail($ma_san_pham)
    {
        $ma_san_pham = mysqli_real_escape_string($this->con, trim((string)$ma_san_pham));
        if ($ma_san_pham === '') {
            return false;
        }

        $sql = "SELECT s.*,
                       (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as gia,
                       (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as gia_moi,
                       (SELECT so_luong_kho FROM bien_the WHERE ma_san_pham = s.ma_san_pham ORDER BY ma_bien_the LIMIT 1) as so_luong_kho,
                       (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL ORDER BY ma_bien_the LIMIT 1) as img_bien_the,
                       dm.ten_danh_muc, th.ten_thuong_hieu
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                WHERE s.ma_san_pham = '$ma_san_pham'
                LIMIT 1";

        return mysqli_query($this->con, $sql);
    }
    
    // Hàm lấy tổng số lượng sản phẩm
    function SanPham_getTotalCount()
    {
        $sql = "SELECT COUNT(*) as total FROM san_pham";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    
    // Hàm lấy sản phẩm có phân trang
    function SanPham_getAllWithPagination($page, $limit)
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT s.*,
                       (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham LIMIT 1) as gia,
                       (SELECT so_luong_kho FROM bien_the WHERE ma_san_pham = s.ma_san_pham LIMIT 1) as so_luong_kho,
                       (SELECT ten_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham LIMIT 1) as ten_bien_the,
                       (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL ORDER BY ma_bien_the LIMIT 1) as img_bien_the,
                       dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap
                FROM san_pham s
                LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                ORDER BY CAST(SUBSTRING(s.ma_san_pham, 3) AS UNSIGNED) DESC
                LIMIT $limit OFFSET $offset";
        return mysqli_query($this->con, $sql);
    }
}