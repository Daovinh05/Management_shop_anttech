<?php
class Cart extends api_controller {
    private $gh;
    private $ctgh;
    private $bt;
    private $sp;

    public function __construct() {
        parent::__construct();
        $this->gh = $this->model('GioHang_m');
        $this->ctgh = $this->model('ChiTietGioHang_m');
        $this->bt = $this->model('BienThe_m');
        $this->sp = $this->model('SanPham_m');
    }

    private function parseInputData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }
        return $this->getJsonInput();
    }

    private function requireAuthUser() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $this->sendResponse(401, [
                'success' => false,
                'message' => 'Unauthorized. Vui long dang nhap de su dung gio hang'
            ]);
        }

        return trim((string)$_SESSION['user_id']);
    }

    private function getActiveCartByUser($ma_user) {
        $active = $this->gh->GioHang_getActiveByUser($ma_user);
        if ($active && mysqli_num_rows($active) > 0) {
            return mysqli_fetch_assoc($active);
        }

        $result = $this->gh->GioHang_getByUser($ma_user);
        if (!$result || mysqli_num_rows($result) === 0) {
            return null;
        }

        return mysqli_fetch_assoc($result);
    }

    private function getOrCreateActiveCartId($ma_user) {
        $cart = $this->getActiveCartByUser($ma_user);
        if ($cart && !empty($cart['ma_gio_hang'])) {
            return $cart['ma_gio_hang'];
        }

        $ma_gio_hang = $this->gh->getNextCartId();
        $inserted = $this->gh->giohang_ins($ma_gio_hang, $ma_user);

        if (!$inserted) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Khong the tao gio hang moi',
                'error' => mysqli_error($this->gh->con)
            ]);
        }

        return $ma_gio_hang;
    }

    private function buildImageUrl($product, $variant) {
        if (!empty($variant['img_bien_the'])) {
            return BASE_URL . 'Public/Pictures/bien_the/' . htmlspecialchars($variant['img_bien_the']);
        }

        if (!empty($product['img_hinh_anh'])) {
            return BASE_URL . 'Public/Pictures/sanpham/' . htmlspecialchars($product['img_hinh_anh']);
        }

        return BASE_URL . 'Public/Images/no-image.png';
    }

    private function buildVariantName($variant) {
        $parts = [];
        if (!empty($variant['mau_sac'])) {
            $parts[] = $variant['mau_sac'];
        }
        if (!empty($variant['dung_luong'])) {
            $parts[] = $variant['dung_luong'];
        }
        if (!empty($variant['ram'])) {
            $parts[] = $variant['ram'];
        }

        if (!empty($parts)) {
            return implode(' - ', $parts);
        }

        return $variant['ten_bien_the'] ?? '';
    }

    private function collectCartItems($ma_gio_hang) {
        $result = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
        if (!$result) {
            return [
                'items' => [],
                'summary' => [
                    'total_items' => 0,
                    'total_quantity' => 0,
                    'subtotal' => 0
                ]
            ];
        }

        $items = [];
        $totalQuantity = 0;
        $subtotal = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $variantResult = $this->bt->BienThe_getById($row['ma_bien_the']);
            $variant = $variantResult ? mysqli_fetch_assoc($variantResult) : null;

            if (!$variant) {
                continue;
            }

            $productResult = $this->sp->SanPham_getById($variant['ma_san_pham']);
            $product = $productResult ? mysqli_fetch_assoc($productResult) : null;

            $quantity = (int)$row['so_luong'];
            $price = (float)$variant['gia'];
            $lineTotal = $price * $quantity;

            $items[] = [
                'ma_gio_hang' => $ma_gio_hang,
                'ma_bien_the' => $row['ma_bien_the'],
                'ma_san_pham' => $variant['ma_san_pham'],
                'ten_san_pham' => $product['ten_san_pham'] ?? '',
                'ten_bien_the' => $variant['ten_bien_the'] ?? '',
                'variant_name' => $this->buildVariantName($variant),
                // Alias de tuong thich voi JS cu
                'name' => $product['ten_san_pham'] ?? '',
                'variant' => $this->buildVariantName($variant),
                'mau_sac' => $variant['mau_sac'] ?? '',
                'dung_luong' => $variant['dung_luong'] ?? '',
                'ram' => $variant['ram'] ?? '',
                'gia' => $price,
                'price' => $price,
                'so_luong' => $quantity,
                'quantity' => $quantity,
                'so_luong_kho' => (int)($variant['so_luong_kho'] ?? 0),
                'line_total' => $lineTotal,
                'img' => $this->buildImageUrl($product ?? [], $variant)
            ];

            $totalQuantity += $quantity;
            $subtotal += $lineTotal;
        }

        return [
            'items' => $items,
            'summary' => [
                'total_items' => count($items),
                'total_quantity' => $totalQuantity,
                'subtotal' => $subtotal
            ]
        ];
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_gio_hang = $this->getOrCreateActiveCartId($ma_user);
        $cartData = $this->collectCartItems($ma_gio_hang);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay gio hang thanh cong',
            'data' => [
                'ma_gio_hang' => $ma_gio_hang,
                'ma_user' => $ma_user,
                'items' => $cartData['items'],
                'summary' => $cartData['summary']
            ]
        ]);
    }

    public function get_detail($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thieu ma bien the']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_gio_hang = $this->getOrCreateActiveCartId($ma_user);
        $cartData = $this->collectCartItems($ma_gio_hang);

        foreach ($cartData['items'] as $item) {
            if ($item['ma_bien_the'] === $id) {
                $this->sendResponse(200, [
                    'success' => true,
                    'data' => $item
                ]);
            }
        }

        $this->sendResponse(404, [
            'success' => false,
            'message' => 'Khong tim thay bien the trong gio hang'
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $ma_user = $this->requireAuthUser();
        $data = $this->parseInputData();

        $ma_bien_the = trim($data['ma_bien_the'] ?? '');
        $so_luong = isset($data['so_luong']) ? (int)$data['so_luong'] : 1;

        if ($ma_bien_the === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui long cung cap ma_bien_the']);
        }

        if ($so_luong <= 0) {
            $this->sendResponse(422, ['success' => false, 'message' => 'So luong phai lon hon 0']);
        }

        $variantResult = $this->bt->BienThe_getById($ma_bien_the);
        if (!$variantResult || mysqli_num_rows($variantResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay bien the']);
        }

        $variant = mysqli_fetch_assoc($variantResult);
        if ($so_luong > (int)$variant['so_luong_kho']) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'So luong vuot qua ton kho',
                'available_stock' => (int)$variant['so_luong_kho']
            ]);
        }

        $ma_gio_hang = $this->getOrCreateActiveCartId($ma_user);
        $currentItemResult = $this->ctgh->ChiTietGioHang_getItem($ma_gio_hang, $ma_bien_the);
        $currentItem = ($currentItemResult && mysqli_num_rows($currentItemResult) > 0)
            ? mysqli_fetch_assoc($currentItemResult)
            : null;

        $existingQty = $currentItem ? (int)$currentItem['so_luong'] : 0;

        $newQty = $existingQty + $so_luong;
        if ($newQty > (int)$variant['so_luong_kho']) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Tong so luong trong gio vuot qua ton kho',
                'available_stock' => (int)$variant['so_luong_kho'],
                'current_quantity' => $existingQty
            ]);
        }

        if ($existingQty > 0) {
            $ok = $this->ctgh->ChiTietGioHang_update($ma_gio_hang, $ma_bien_the, $newQty);
        } else {
            $ok = $this->ctgh->chitietgiohang_ins($ma_gio_hang, $ma_bien_the, $so_luong);
        }

        if (!$ok) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Khong the cap nhat gio hang',
                'error' => mysqli_error($this->ctgh->con)
            ]);
        }

        $cartData = $this->collectCartItems($ma_gio_hang);
        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Them vao gio hang thanh cong',
            'data' => [
                'ma_gio_hang' => $ma_gio_hang,
                'items' => $cartData['items'],
                'summary' => $cartData['summary']
            ]
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT/PATCH/POST']);
        }

        $ma_user = $this->requireAuthUser();
        $data = $this->parseInputData();

        $ma_bien_the = trim($id ?? $data['ma_bien_the'] ?? '');
        $so_luong = isset($data['so_luong']) ? (int)$data['so_luong'] : 0;

        if ($ma_bien_the === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thieu ma_bien_the']);
        }

        if ($so_luong <= 0) {
            $this->sendResponse(422, ['success' => false, 'message' => 'So luong phai lon hon 0']);
        }

        $variantResult = $this->bt->BienThe_getById($ma_bien_the);
        if (!$variantResult || mysqli_num_rows($variantResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay bien the']);
        }

        $variant = mysqli_fetch_assoc($variantResult);
        if ($so_luong > (int)$variant['so_luong_kho']) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'So luong vuot qua ton kho',
                'available_stock' => (int)$variant['so_luong_kho']
            ]);
        }

        $ma_gio_hang = $this->getOrCreateActiveCartId($ma_user);
        $currentItemResult = $this->ctgh->ChiTietGioHang_getItem($ma_gio_hang, $ma_bien_the);
        $exists = $currentItemResult && mysqli_num_rows($currentItemResult) > 0;

        if (!$exists) {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Bien the nay chua co trong gio hang'
            ]);
        }

        $ok = $this->ctgh->ChiTietGioHang_update($ma_gio_hang, $ma_bien_the, $so_luong);
        if (!$ok) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Khong the cap nhat so luong',
                'error' => mysqli_error($this->ctgh->con)
            ]);
        }

        $cartData = $this->collectCartItems($ma_gio_hang);
        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cap nhat gio hang thanh cong',
            'data' => [
                'ma_gio_hang' => $ma_gio_hang,
                'items' => $cartData['items'],
                'summary' => $cartData['summary']
            ]
        ]);
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE/POST']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_bien_the = trim((string)$id);

        if ($ma_bien_the === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thieu ma_bien_the']);
        }

        $ma_gio_hang = $this->getOrCreateActiveCartId($ma_user);
        $currentItemResult = $this->ctgh->ChiTietGioHang_getItem($ma_gio_hang, $ma_bien_the);
        $exists = $currentItemResult && mysqli_num_rows($currentItemResult) > 0;

        if (!$exists) {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Khong tim thay bien the trong gio hang'
            ]);
        }

        $ok = $this->ctgh->ChiTietGioHang_delete($ma_gio_hang, $ma_bien_the);
        if (!$ok) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Khong the xoa san pham khoi gio',
                'error' => mysqli_error($this->ctgh->con)
            ]);
        }

        $cartData = $this->collectCartItems($ma_gio_hang);
        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Xoa san pham khoi gio hang thanh cong',
            'data' => [
                'ma_gio_hang' => $ma_gio_hang,
                'items' => $cartData['items'],
                'summary' => $cartData['summary']
            ]
        ]);
    }

    public function summary() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_gio_hang = $this->getOrCreateActiveCartId($ma_user);
        $cartData = $this->collectCartItems($ma_gio_hang);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay tong quan gio hang thanh cong',
            'data' => [
                'ma_gio_hang' => $ma_gio_hang,
                'summary' => $cartData['summary']
            ]
        ]);
    }

    // Endpoint: DELETE/POST /Api/Cart/clear
    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE/POST']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_gio_hang = $this->getOrCreateActiveCartId($ma_user);

        $ok = $this->ctgh->ChiTietGioHang_deleteByCartId($ma_gio_hang);
        if (!$ok) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Khong the xoa toan bo gio hang',
                'error' => mysqli_error($this->ctgh->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Da xoa toan bo san pham trong gio hang',
            'data' => [
                'ma_gio_hang' => $ma_gio_hang,
                'items' => [],
                'summary' => [
                    'total_items' => 0,
                    'total_quantity' => 0,
                    'subtotal' => 0
                ]
            ]
        ]);
    }

    // Endpoint: POST/PUT/PATCH /Api/Cart/bulk_update
    // Payload JSON: {"items":[{"ma_bien_the":"BT01","so_luong":2}, ...]}
    public function bulk_update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST/PUT/PATCH']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_gio_hang = $this->getOrCreateActiveCartId($ma_user);
        $data = $this->parseInputData();

        $items = $data['items'] ?? null;
        if (!is_array($items) || count($items) === 0) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Vui long cung cap danh sach items']);
        }

        $updated = [];
        $errors = [];

        foreach ($items as $item) {
            $ma_bien_the = trim((string)($item['ma_bien_the'] ?? ''));
            $so_luong = isset($item['so_luong']) ? (int)$item['so_luong'] : 0;

            if ($ma_bien_the === '') {
                $errors[] = ['ma_bien_the' => '', 'message' => 'Thieu ma_bien_the'];
                continue;
            }

            if ($so_luong < 0) {
                $errors[] = ['ma_bien_the' => $ma_bien_the, 'message' => 'So luong khong hop le'];
                continue;
            }

            $variantResult = $this->bt->BienThe_getById($ma_bien_the);
            if (!$variantResult || mysqli_num_rows($variantResult) === 0) {
                $errors[] = ['ma_bien_the' => $ma_bien_the, 'message' => 'Khong tim thay bien the'];
                continue;
            }

            $variant = mysqli_fetch_assoc($variantResult);
            if ($so_luong > (int)$variant['so_luong_kho']) {
                $errors[] = [
                    'ma_bien_the' => $ma_bien_the,
                    'message' => 'So luong vuot ton kho',
                    'available_stock' => (int)$variant['so_luong_kho']
                ];
                continue;
            }

            $currentItemResult = $this->ctgh->ChiTietGioHang_getItem($ma_gio_hang, $ma_bien_the);
            $exists = $currentItemResult && mysqli_num_rows($currentItemResult) > 0;

            if ($so_luong === 0) {
                if ($exists) {
                    $ok = $this->ctgh->ChiTietGioHang_delete($ma_gio_hang, $ma_bien_the);
                    if ($ok) {
                        $updated[] = ['ma_bien_the' => $ma_bien_the, 'action' => 'deleted'];
                    } else {
                        $errors[] = ['ma_bien_the' => $ma_bien_the, 'message' => 'Khong the xoa item'];
                    }
                }
                continue;
            }

            if ($exists) {
                $ok = $this->ctgh->ChiTietGioHang_update($ma_gio_hang, $ma_bien_the, $so_luong);
                if ($ok) {
                    $updated[] = ['ma_bien_the' => $ma_bien_the, 'action' => 'updated', 'so_luong' => $so_luong];
                } else {
                    $errors[] = ['ma_bien_the' => $ma_bien_the, 'message' => 'Khong the cap nhat item'];
                }
            } else {
                $ok = $this->ctgh->chitietgiohang_ins($ma_gio_hang, $ma_bien_the, $so_luong);
                if ($ok) {
                    $updated[] = ['ma_bien_the' => $ma_bien_the, 'action' => 'created', 'so_luong' => $so_luong];
                } else {
                    $errors[] = ['ma_bien_the' => $ma_bien_the, 'message' => 'Khong the them item'];
                }
            }
        }

        $cartData = $this->collectCartItems($ma_gio_hang);
        $statusCode = count($errors) > 0 ? 207 : 200;

        $this->sendResponse($statusCode, [
            'success' => count($errors) === 0,
            'message' => count($errors) === 0 ? 'Cap nhat nhieu san pham thanh cong' : 'Cap nhat mot phan thanh cong',
            'data' => [
                'ma_gio_hang' => $ma_gio_hang,
                'updated' => $updated,
                'errors' => $errors,
                'items' => $cartData['items'],
                'summary' => $cartData['summary']
            ]
        ]);
    }
}
