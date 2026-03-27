<?php
class Products extends api_controller {
    private $sanpham_model;

    public function __construct() {
        parent::__construct();
        // Khởi tạo Model Sản Phẩm thực tế
        $this->sanpham_model = $this->model('SanPham_m');
    }

    /**
     * Endpoint: GET /Api/Products/get_all
     * Lấy danh sách sản phẩm từ Database
     */
    public function get_all() {
        $result = $this->sanpham_model->SanPham_getAll();
        
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy danh sách sản phẩm thành công',
            'total' => count($products),
            'data' => $products
        ]);
    }

    /**
     * Endpoint: GET /Api/Products/get_detail/SP01
     * Lấy chi tiết 1 sản phẩm theo ID thực tế từ Database
     */
    public function get_detail($id = null) {
        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã sản phẩm (Ví dụ: SP01)']);
        }

        $result = $this->sanpham_model->SanPham_getById($id);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            $this->sendResponse(200, [
                'success' => true,
                'data' => $product
            ]);
        } else {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm có mã: ' . $id
            ]);
        }
    }

    /**
     * Endpoint: GET /Api/Products/search?ma_san_pham=SP01&ten_san_pham=iphone
     * Tìm kiếm sản phẩm theo mã và/hoặc tên sản phẩm
     */
    public function search($ma_san_pham = null, $ten_san_pham = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        // Ưu tiên query string, fallback sang tham số URL
        $ma_san_pham_query = isset($_GET['ma_san_pham']) ? trim($_GET['ma_san_pham']) : '';
        $ten_san_pham_query = isset($_GET['ten_san_pham']) ? trim($_GET['ten_san_pham']) : '';

        $ma_san_pham = $ma_san_pham_query !== '' ? $ma_san_pham_query : (($ma_san_pham !== null) ? trim($ma_san_pham) : '');
        $ten_san_pham = $ten_san_pham_query !== '' ? $ten_san_pham_query : (($ten_san_pham !== null) ? trim($ten_san_pham) : '');

        if ($ma_san_pham === '' && $ten_san_pham === '') {
            $this->sendResponse(400, [
                'success' => false,
                'message' => 'Vui lòng cung cấp ít nhất một tiêu chí tìm kiếm: ma_san_pham hoặc ten_san_pham'
            ]);
        }

        $result = $this->sanpham_model->SanPham_find($ma_san_pham, $ten_san_pham);

        if (!$result) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi tìm kiếm sản phẩm'
            ]);
        }

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Tìm kiếm sản phẩm thành công',
            'filters' => [
                'ma_san_pham' => $ma_san_pham,
                'ten_san_pham' => $ten_san_pham
            ],
            'total' => count($products),
            'data' => $products
        ]);
    }

    /**
     * Endpoint: POST /Api/Products/create
     * Tạo sản phẩm mới
     */
    public function create() {
        // Chỉ chấp nhận POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed']);
        }

        // Lấy dữ liệu gửi lên dưới dạng JSON payload
        $data = $this->getJsonInput();

        // Validate dữ liệu cơ bản dựa theo hàm sanpham_ins trong SanPham_m
        if (empty($data['ma_san_pham']) || empty($data['ten_san_pham']) || empty($data['ma_danh_muc'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp đủ thông tin: mã_san_pham, ten_san_pham và ma_danh_muc']);
        }

        // Các trường không bắt buộc
        $ma_thuong_hieu = $data['ma_thuong_hieu'] ?? null;
        $ma_nha_cung_cap = $data['ma_nha_cung_cap'] ?? null;

        // Kiểm tra xem sản phẩm đã tồn tại chưa
        if ($this->sanpham_model->checktrungMaSP($data['ma_san_pham'])) {
            $this->sendResponse(409, ['success' => false, 'message' => 'Mã sản phẩm đã tồn tại!']);
        }

        // Thực thi insert vào DB
        $insert_result = $this->sanpham_model->sanpham_ins(
            $data['ma_san_pham'], 
            $data['ten_san_pham'], 
            $data['ma_danh_muc'], 
            $ma_thuong_hieu, 
            $ma_nha_cung_cap
        );

        if ($insert_result) {
            $this->sendResponse(201, [
                'success' => true,
                'message' => 'Tạo sản phẩm thành công',
                'data' => $data
            ]);
        } else {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi lưu vào Database'
            ]);
        }
    }

    /**
     * Endpoint: PUT /Api/Products/update
     * Cập nhật thông tin sản phẩm
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT']);
        }

        // Lấy dữ liệu gửi lên dưới dạng JSON
        $data = $this->getJsonInput();

        if (empty($data['ma_san_pham'])) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui lòng cung cấp ma_san_pham để cập nhật']);
        }

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$this->sanpham_model->checktrungMaSP($data['ma_san_pham'])) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm có mã: ' . $data['ma_san_pham']]);
        }

        // Thực thi update
        $update_result = $this->sanpham_model->SanPham_update(
            $data['ma_san_pham'],
            $data['ten_san_pham'] ?? '',
            $data['ma_danh_muc'] ?? '',
            $data['ma_thuong_hieu'] ?? '',
            $data['ma_nha_cung_cap'] ?? ''
        );

        if ($update_result) {
            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Cập nhật sản phẩm thành công',
                'data' => $data
            ]);
        } else {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi cập nhật Database'
            ]);
        }
    }

    /**
     * Endpoint: DELETE /Api/Products/delete/SP01
     * Xóa sản phẩm
     */
    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã sản phẩm cần xóa trên URL']);
        }

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$this->sanpham_model->checktrungMaSP($id)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm có mã: ' . $id]);
        }

        // Thực thi xóa
        $delete_result = $this->sanpham_model->SanPham_delete($id);

        if ($delete_result) {
            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);
        } else {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi xóa dữ liệu'
            ]);
        }
    }
}
?>
