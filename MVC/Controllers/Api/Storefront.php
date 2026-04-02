<?php
class Storefront extends api_controller {
    private $sp;
    private $dm;
    private $th;

    public function __construct() {
        parent::__construct();
        $this->sp = $this->model('SanPham_m');
        $this->dm = $this->model('DanhMuc_m');
        $this->th = $this->model('ThuongHieu_m');
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
}
