<?php
/**
 * Products API Controller
 * Xử lý các endpoint CRUD cho sản phẩm
 */
require_once __DIR__ . '/../../Core/ApiController.php';

class ProductsApi extends ApiController
{
    private $sanPhamModel;
    private $danhMucModel;
    private $thuongHieuModel;
    private $nhaCungCapModel;
    private $bienTheModel;

    public function __construct()
    {
        parent::__construct();
        
        include_once __DIR__ . '/../../Models/SanPham_m.php';
        include_once __DIR__ . '/../../Models/DanhMuc_m.php';
        include_once __DIR__ . '/../../Models/ThuongHieu_m.php';
        include_once __DIR__ . '/../../Models/NhaCungCap_m.php';
        include_once __DIR__ . '/../../Models/BienThe_m.php';
        
        $this->sanPhamModel = new SanPham_m();
        $this->danhMucModel = new DanhMuc_m();
        $this->thuongHieuModel = new ThuongHieu_m();
        $this->nhaCungCapModel = new NhaCungCap_m();
        $this->bienTheModel = new BienThe_m();
    }

    /**
     * GET /api/products
     * Lấy danh sách sản phẩm (có phân trang, lọc, tìm kiếm)
     * Query params: page, limit, category, brand, price_range, search
     */
    public function getAll()
    {
        $page = isset($this->args['page']) ? max(1, intval($this->args['page'])) : 1;
        $limit = isset($this->args['limit']) ? max(1, intval($this->args['limit'])) : 20;
        $category = $this->args['category'] ?? '';
        $brand = $this->args['brand'] ?? '';
        $priceRange = $this->args['price_range'] ?? '';
        $search = $this->args['search'] ?? '';

        // Lấy dữ liệu
        if (!empty($search)) {
            // Tìm kiếm theo tên
            $result = $this->sanPhamModel->SanPham_searchByName($search);
        } elseif (!empty($category) || !empty($priceRange) || !empty($brand)) {
            // Lọc theo danh mục, giá, thương hiệu
            $result = $this->sanPhamModel->SanPham_filterByCategoryAndPrice($category, $priceRange, $brand);
        } else {
            // Lấy tất cả với phân trang
            $result = $this->sanPhamModel->SanPham_getAllWithPagination($page, $limit);
        }

        // Chuyển kết quả thành mảng
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $this->formatProduct($row);
            }
        }

        // Lấy tổng số sản phẩm
        $total = $this->sanPhamModel->SanPham_getTotalCount();

        // Trả về response phân trang
        $this->paginatedResponse($products, $total, $page, $limit, 'Products retrieved successfully');
    }

    /**
     * GET /api/products/{id}
     * Lấy chi tiết sản phẩm theo ID
     */
    public function getById($params)
    {
        $ma_san_pham = is_array($params) ? ($params[0] ?? null) : $params;

        if (empty($ma_san_pham)) {
            $this->error('Product ID is required', 400, 'MISSING_ID');
        }

        $result = $this->sanPhamModel->SanPham_getById($ma_san_pham);

        if (!$result || mysqli_num_rows($result) === 0) {
            $this->notFound('Product not found');
        }

        $product = mysqli_fetch_assoc($result);

        // Lấy các biến thể của sản phẩm
        $variants = $this->getProductVariants($ma_san_pham);

        $this->success([
            'product' => $this->formatProduct($product),
            'variants' => $variants
        ], 'Product details retrieved successfully');
    }

    /**
     * POST /api/products
     * Tạo sản phẩm mới (yêu cầu admin)
     * Body: { "ma_san_pham": "...", "ten_san_pham": "...", "ma_danh_muc": "...", "ma_thuong_hieu": "...", "ma_nha_cung_cap": "..." }
     */
    public function create()
    {
        // Yêu cầu admin
        $user = $this->requireAuth();
        if ($user['user_role'] !== 'admin') {
            $this->error('Admin access required', 403, 'FORBIDDEN');
        }

        // Kiểm tra dữ liệu bắt buộc
        if (empty($this->data['ma_san_pham']) || empty($this->data['ten_san_pham'])) {
            $this->error('Product ID and name are required', 400, 'MISSING_REQUIRED_FIELDS');
        }

        $ma_san_pham = $this->data['ma_san_pham'];
        $ten_san_pham = $this->data['ten_san_pham'];
        $ma_danh_muc = $this->data['ma_danh_muc'] ?? '';
        $ma_thuong_hieu = $this->data['ma_thuong_hieu'] ?? '';
        $ma_nha_cung_cap = $this->data['ma_nha_cung_cap'] ?? '';

        // Kiểm tra trùng mã
        if ($this->sanPhamModel->checktrungMaSP($ma_san_pham)) {
            $this->error('Product ID already exists', 409, 'PRODUCT_EXISTS');
        }

        // Tạo sản phẩm
        $result = $this->sanPhamModel->sanpham_ins(
            $ma_san_pham,
            $ten_san_pham,
            $ma_danh_muc,
            $ma_thuong_hieu,
            $ma_nha_cung_cap
        );

        if ($result) {
            // Tạo biến thể mặc định nếu có dữ liệu
            if (!empty($this->data['variants']) && is_array($this->data['variants'])) {
                foreach ($this->data['variants'] as $variant) {
                    $this->bienTheModel->insertBienThe(
                        $ma_san_pham,
                        $variant['ten_bien_the'] ?? '',
                        $variant['gia'] ?? 0,
                        $variant['so_luong_kho'] ?? 0,
                        $variant['img_bien_the'] ?? ''
                    );
                }
            }

            $this->success([
                'ma_san_pham' => $ma_san_pham,
                'ten_san_pham' => $ten_san_pham
            ], 'Product created successfully', 201);
        } else {
            $this->error('Failed to create product', 500, 'CREATE_FAILED');
        }
    }

    /**
     * PUT /api/products/{id}
     * Cập nhật sản phẩm (yêu cầu admin)
     * Body: { "ten_san_pham": "...", "ma_danh_muc": "...", "ma_thuong_hieu": "...", "ma_nha_cung_cap": "..." }
     */
    public function update($params)
    {
        // Yêu cầu admin
        $user = $this->requireAuth();
        if ($user['user_role'] !== 'admin') {
            $this->error('Admin access required', 403, 'FORBIDDEN');
        }

        $ma_san_pham = is_array($params) ? ($params[0] ?? null) : $params;

        if (empty($ma_san_pham)) {
            $this->error('Product ID is required', 400, 'MISSING_ID');
        }

        // Kiểm tra sản phẩm tồn tại
        $existing = $this->sanPhamModel->SanPham_getById($ma_san_pham);
        if (!$existing || mysqli_num_rows($existing) === 0) {
            $this->notFound('Product not found');
        }

        $ten_san_pham = $this->data['ten_san_pham'] ?? '';
        $ma_danh_muc = $this->data['ma_danh_muc'] ?? '';
        $ma_thuong_hieu = $this->data['ma_thuong_hieu'] ?? '';
        $ma_nha_cung_cap = $this->data['ma_nha_cung_cap'] ?? '';

        if (empty($ten_san_pham)) {
            $this->error('Product name is required', 400, 'MISSING_NAME');
        }

        $result = $this->sanPhamModel->SanPham_update(
            $ma_san_pham,
            $ten_san_pham,
            $ma_danh_muc,
            $ma_thuong_hieu,
            $ma_nha_cung_cap
        );

        if ($result) {
            $this->success([
                'ma_san_pham' => $ma_san_pham,
                'ten_san_pham' => $ten_san_pham
            ], 'Product updated successfully');
        } else {
            $this->error('Failed to update product', 500, 'UPDATE_FAILED');
        }
    }

    /**
     * DELETE /api/products/{id}
     * Xóa sản phẩm (yêu cầu admin)
     */
    public function delete($params)
    {
        // Yêu cầu admin
        $user = $this->requireAuth();
        if ($user['user_role'] !== 'admin') {
            $this->error('Admin access required', 403, 'FORBIDDEN');
        }

        $ma_san_pham = is_array($params) ? ($params[0] ?? null) : $params;

        if (empty($ma_san_pham)) {
            $this->error('Product ID is required', 400, 'MISSING_ID');
        }

        // Kiểm tra sản phẩm tồn tại
        $existing = $this->sanPhamModel->SanPham_getById($ma_san_pham);
        if (!$existing || mysqli_num_rows($existing) === 0) {
            $this->notFound('Product not found');
        }

        $result = $this->sanPhamModel->SanPham_delete($ma_san_pham);

        if ($result) {
            $this->success([
                'ma_san_pham' => $ma_san_pham
            ], 'Product deleted successfully');
        } else {
            $this->error('Failed to delete product', 500, 'DELETE_FAILED');
        }
    }

    /**
     * GET /api/products/search
     * Tìm kiếm sản phẩm
     * Query params: keyword, category, brand, min_price, max_price
     */
    public function search()
    {
        $keyword = $this->args['keyword'] ?? '';
        $category = $this->args['category'] ?? '';
        $brand = $this->args['brand'] ?? '';
        $minPrice = isset($this->args['min_price']) ? floatval($this->args['min_price']) : 0;
        $maxPrice = isset($this->args['max_price']) ? floatval($this->args['max_price']) : PHP_INT_MAX;

        $result = $this->sanPhamModel->SanPham_searchByName($keyword);

        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Lọc theo giá
                $gia = floatval($row['gia'] ?? 0);
                if ($gia >= $minPrice && $gia <= $maxPrice) {
                    // Lọc theo danh mục và thương hiệu
                    if ((!empty($category) && $row['ma_danh_muc'] !== $category) ||
                        (!empty($brand) && $row['ma_thuong_hieu'] !== $brand)) {
                        continue;
                    }
                    $products[] = $this->formatProduct($row);
                }
            }
        }

        $this->success([
            'keyword' => $keyword,
            'count' => count($products),
            'products' => $products
        ], 'Search completed successfully');
    }

    /**
     * GET /api/products/categories
     * Lấy danh sách danh mục
     */
    public function categories()
    {
        $result = $this->danhMucModel->DanhMuc_getAll();
        
        $categories = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $categories[] = $row;
            }
        }

        $this->success($categories, 'Categories retrieved successfully');
    }

    /**
     * GET /api/products/brands
     * Lấy danh sách thương hiệu
     */
    public function brands()
    {
        $result = $this->thuongHieuModel->ThuongHieu_getAll();
        
        $brands = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $brands[] = $row;
            }
        }

        $this->success($brands, 'Brands retrieved successfully');
    }

    /**
     * GET /api/products/suppliers
     * Lấy danh sách nhà cung cấp
     */
    public function suppliers()
    {
        $result = $this->nhaCungCapModel->NhaCungCap_getAll();
        
        $suppliers = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $suppliers[] = $row;
            }
        }

        $this->success($suppliers, 'Suppliers retrieved successfully');
    }

    /**
     * Format product data
     */
    private function formatProduct($row)
    {
        return [
            'ma_san_pham' => $row['ma_san_pham'],
            'ten_san_pham' => $row['ten_san_pham'],
            'ma_danh_muc' => $row['ma_danh_muc'],
            'ten_danh_muc' => $row['ten_danh_muc'] ?? '',
            'ma_thuong_hieu' => $row['ma_thuong_hieu'],
            'ten_thuong_hieu' => $row['ten_thuong_hieu'] ?? '',
            'ma_nha_cung_cap' => $row['ma_nha_cung_cap'],
            'ten_nha_cung_cap' => $row['ten_nha_cung_cap'] ?? '',
            'gia' => floatval($row['gia'] ?? 0),
            'so_luong_kho' => intval($row['so_luong_kho'] ?? 0),
            'hinh_anh' => $row['img_bien_the'] ?? '',
            'ngay_tao' => $row['ngay_tao'] ?? null
        ];
    }

    /**
     * Get product variants
     */
    private function getProductVariants($ma_san_pham)
    {
        $result = $this->bienTheModel->getBienTheByMaSanPham($ma_san_pham);
        
        $variants = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $variants[] = [
                    'ma_bien_the' => $row['ma_bien_the'],
                    'ten_bien_the' => $row['ten_bien_the'],
                    'gia' => floatval($row['gia']),
                    'so_luong_kho' => intval($row['so_luong_kho']),
                    'hinh_anh' => $row['img_bien_the'] ?? ''
                ];
            }
        }
        
        return $variants;
    }
}
