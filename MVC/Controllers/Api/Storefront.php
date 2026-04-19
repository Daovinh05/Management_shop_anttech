<?php
class Storefront extends api_controller {
    private $sp;
    private $dm;
    private $th;
    private $bt;
    private $dg;

    public function __construct() {
        parent::__construct();
        $this->sp = $this->model('SanPham_m');
        $this->dm = $this->model('DanhMuc_m');
        $this->th = $this->model('ThuongHieu_m');
        $this->bt = $this->model('BienThe_m');
        $this->dg = $this->model('DanhGia_m');
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
        $price_range = isset($_GET['price_range']) ? trim($_GET['price_range']) : '';
        $brand_id = isset($_GET['brand_id']) ? trim($_GET['brand_id']) : '';
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
        $page = max(1, $page);
        $limit = max(1, min(48, $limit));

        $result = $this->sp->SanPham_getStorefront($category_id, $price_range, $brand_id, $search, $page, $limit);
        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Khong the lay danh sach san pham']);
        }

        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }

        $total = $this->sp->SanPham_countStorefront($category_id, $price_range, $brand_id, $search);
        $total_pages = (int)ceil($total / $limit);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay du lieu trang chu thanh cong',
            'data' => [
                'items' => $items,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'total_pages' => $total_pages
                ],
                'filters' => [
                    'category_id' => $category_id,
                    'price_range' => $price_range,
                    'brand_id' => $brand_id,
                    'q' => $search
                ]
            ]
        ]);
    }

    public function filters() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $categoriesResult = $this->dm->DanhMuc_getAll();
        $brandsResult = $this->th->ThuongHieu_getAll();

        $categories = [];
        if ($categoriesResult) {
            while ($row = mysqli_fetch_assoc($categoriesResult)) {
                $categories[] = $row;
            }
        }

        $brands = [];
        if ($brandsResult) {
            while ($row = mysqli_fetch_assoc($brandsResult)) {
                $brands[] = $row;
            }
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay bo loc thanh cong',
            'data' => [
                'categories' => $categories,
                'brands' => $brands,
                'price_ranges' => [
                    ['value' => 'tat-ca', 'label' => 'Tat ca'],
                    ['value' => 'duoi-2-trieu', 'label' => 'Duoi 2 trieu'],
                    ['value' => '2-4-trieu', 'label' => 'Tu 2 - 4 trieu'],
                    ['value' => '4-7-trieu', 'label' => 'Tu 4 - 7 trieu'],
                    ['value' => '7-13-trieu', 'label' => 'Tu 7 - 13 trieu'],
                    ['value' => 'tren-13-trieu', 'label' => 'Tren 13 trieu']
                ]
            ]
        ]);
    }

    public function suggestions() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($q === '') {
            $this->sendResponse(200, ['success' => true, 'data' => []]);
        }

        $result = $this->sp->SanPham_searchByName($q);
        if (!$result) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Khong the tim goi y san pham']);
        }

        $suggestions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $suggestions[] = [
                'ma_san_pham' => $row['ma_san_pham'],
                'ten_san_pham' => $row['ten_san_pham']
            ];
            if (count($suggestions) >= 10) {
                break;
            }
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay goi y thanh cong',
            'data' => $suggestions
        ]);
    }

    // RESTful detail endpoint: GET /Api/Storefront/{ma_san_pham}
    public function get_detail($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $id = trim((string)$id);
        if ($id === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thieu ma san pham']);
        }

        $sp_result = $this->sp->SanPham_getStorefrontDetail($id);
        if (!$sp_result || mysqli_num_rows($sp_result) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay san pham']);
        }
        $san_pham = mysqli_fetch_assoc($sp_result);

        $bien_the = [];
        $bien_the_result = $this->bt->BienThe_getByProduct($id);
        if ($bien_the_result) {
            while ($row = mysqli_fetch_assoc($bien_the_result)) {
                $bien_the[] = $row;
            }
        }

        $danh_gia = [];
        $danh_gia_result = $this->dg->DanhGia_getByProduct($id);
        if ($danh_gia_result) {
            while ($row = mysqli_fetch_assoc($danh_gia_result)) {
                $danh_gia[] = $row;
            }
        }

        $avg_rating = $this->dg->DanhGia_getAvgRatingByProduct($id);
        $star_distribution = $this->dg->DanhGia_getStarDistribution($id);

        $similar_products = [];
        $ma_danh_muc = mysqli_real_escape_string($this->sp->con, (string)($san_pham['ma_danh_muc'] ?? ''));
        $ma_san_pham = mysqli_real_escape_string($this->sp->con, $id);
        if ($ma_danh_muc !== '') {
            $sql_similar = "SELECT s.ma_san_pham, s.ten_san_pham,
                                   (SELECT img_bien_the FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND img_bien_the != '' AND img_bien_the IS NOT NULL LIMIT 1) as img_bien_the,
                                   (SELECT gia FROM bien_the WHERE ma_san_pham = s.ma_san_pham AND gia IS NOT NULL LIMIT 1) as gia,
                                   dm.ten_danh_muc, th.ten_thuong_hieu, ncc.ten_nha_cung_cap
                            FROM san_pham s
                            LEFT JOIN danh_muc dm ON s.ma_danh_muc = dm.ma_danh_muc
                            LEFT JOIN thuong_hieu th ON s.ma_thuong_hieu = th.ma_thuong_hieu
                            LEFT JOIN nha_cung_cap ncc ON s.ma_nha_cung_cap = ncc.ma_nha_cung_cap
                            WHERE s.ma_danh_muc = '$ma_danh_muc'
                              AND s.ma_san_pham != '$ma_san_pham'
                            GROUP BY s.ma_san_pham
                            ORDER BY s.ma_san_pham DESC
                            LIMIT 4";
            $similar_result = mysqli_query($this->sp->con, $sql_similar);
            if ($similar_result) {
                while ($row = mysqli_fetch_assoc($similar_result)) {
                    $similar_products[] = $row;
                }
            }
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay chi tiet san pham thanh cong',
            'data' => [
                'san_pham' => $san_pham,
                'bien_the' => $bien_the,
                'danh_gia' => $danh_gia,
                'avg_rating' => $avg_rating,
                'star_distribution' => $star_distribution,
                'similar_products' => $similar_products
            ]
        ]);
    }
}
