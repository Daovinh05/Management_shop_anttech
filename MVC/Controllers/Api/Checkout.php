<?php
class Checkout extends api_controller {
    private $dh;
    private $ctdh;
    private $gh;
    private $ctgh;
    private $bt;
    private $sp;
    private $dc;
    private $km;
    private $tt;
    private $users;

    public function __construct() {
        parent::__construct();
        $this->dh = $this->model('DonHang_m');
        $this->ctdh = $this->model('ChiTietDonHang_m');
        $this->gh = $this->model('GioHang_m');
        $this->ctgh = $this->model('ChiTietGioHang_m');
        $this->bt = $this->model('BienThe_m');
        $this->sp = $this->model('SanPham_m');
        $this->dc = $this->model('DiaChiGiaoHang_m');
        $this->km = $this->model('KhuyenMai_m');
        $this->tt = $this->model('ThanhToan_m');
        $this->users = $this->model('Users_m');
    }

    private function requireAuthUser() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $this->sendResponse(401, [
                'success' => false,
                'message' => 'Unauthorized. Vui long dang nhap de thanh toan'
            ]);
        }

        return trim((string)$_SESSION['user_id']);
    }

    private function parseInputData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST)) {
                return $_POST;
            }
            return $this->getJsonInput();
        }
        return $this->getJsonInput();
    }

    private function nextAddressId() {
        $result = mysqli_query($this->dc->con, "SELECT MAX(ma_dia_chi) as max_id FROM dia_chi_giao_hang WHERE ma_dia_chi LIKE 'DC%'");
        $row = $result ? mysqli_fetch_assoc($result) : null;
        $lastId = $row['max_id'] ?? null;

        $nextNumber = $lastId ? (intval(substr($lastId, 2)) + 1) : 1;
        return 'DC' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    private function nextPaymentId() {
        $result = mysqli_query($this->tt->con, "SELECT MAX(ma_giao_dich) as max_id FROM thanh_toan WHERE ma_giao_dich LIKE 'GD%'");
        $row = $result ? mysqli_fetch_assoc($result) : null;
        $lastId = $row['max_id'] ?? null;

        $nextNumber = $lastId ? (intval(substr($lastId, 2)) + 1) : 1;
        return 'GD' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    private function getCartByUser($ma_user) {
        $active = $this->gh->GioHang_getActiveByUser($ma_user);
        if ($active && mysqli_num_rows($active) > 0) {
            return mysqli_fetch_assoc($active);
        }

        $fallback = $this->gh->GioHang_getByUser($ma_user);
        if ($fallback && mysqli_num_rows($fallback) > 0) {
            return mysqli_fetch_assoc($fallback);
        }

        return null;
    }

    private function formatCheckoutItem($ma_bien_the, $quantity, $price, $variantRow = null) {
        $variant = $variantRow;
        if (!$variant) {
            $variantResult = $this->bt->BienThe_getById($ma_bien_the);
            $variant = ($variantResult && mysqli_num_rows($variantResult) > 0) ? mysqli_fetch_assoc($variantResult) : null;
        }

        if (!$variant) {
            return null;
        }

        $productResult = $this->sp->SanPham_getById($variant['ma_san_pham']);
        $product = ($productResult && mysqli_num_rows($productResult) > 0) ? mysqli_fetch_assoc($productResult) : null;

        return [
            'ma_bien_the' => $ma_bien_the,
            'ma_san_pham' => $variant['ma_san_pham'],
            'ten_san_pham' => $product['ten_san_pham'] ?? '',
            'ten_bien_the' => $variant['ten_bien_the'] ?? '',
            'mau_sac' => $variant['mau_sac'] ?? '',
            'ram' => $variant['ram'] ?? '',
            'dung_luong' => $variant['dung_luong'] ?? '',
            'so_luong' => (int)$quantity,
            'gia' => (float)$price,
            'line_total' => ((float)$price * (int)$quantity),
            'so_luong_kho' => (int)($variant['so_luong_kho'] ?? 0)
        ];
    }

    private function buildCheckoutPayload($ma_user, $payload, $fromQuery = false) {
        $isBuyNow = false;
        if (isset($payload['mode'])) {
            $isBuyNow = trim((string)$payload['mode']) === 'buy_now';
        }
        if (isset($payload['is_buy_now'])) {
            $isBuyNow = ((string)$payload['is_buy_now'] === '1' || strtolower((string)$payload['is_buy_now']) === 'true');
        }
        if (isset($payload['buynow'])) {
            $isBuyNow = ((string)$payload['buynow'] === '1' || strtolower((string)$payload['buynow']) === 'true');
        }

        $items = [];
        $subtotal = 0;
        $stockErrors = [];
        $ma_gio_hang = null;

        if ($isBuyNow) {
            $ma_bien_the = trim((string)($payload['ma_bien_the'] ?? $payload['items'] ?? $payload['selected_items_str'] ?? ''));
            $so_luong = isset($payload['so_luong']) ? (int)$payload['so_luong'] : (isset($payload['qty']) ? (int)$payload['qty'] : (isset($payload['forced_qty']) ? (int)$payload['forced_qty'] : 1));
            $so_luong = max(1, $so_luong);

            if ($ma_bien_the === '') {
                return ['error' => 'Thieu ma_bien_the cho luong mua ngay'];
            }

            $variantResult = $this->bt->BienThe_getById($ma_bien_the);
            if (!$variantResult || mysqli_num_rows($variantResult) === 0) {
                return ['error' => 'Khong tim thay bien the mua ngay'];
            }

            $variant = mysqli_fetch_assoc($variantResult);
            if ($so_luong > (int)$variant['so_luong_kho']) {
                $stockErrors[] = ($variant['ten_bien_the'] ?? $ma_bien_the) . ' (chi con ' . (int)$variant['so_luong_kho'] . ' san pham)';
            } else {
                $item = $this->formatCheckoutItem($ma_bien_the, $so_luong, (float)$variant['gia'], $variant);
                if ($item) {
                    $items[] = $item;
                    $subtotal += $item['line_total'];
                }
            }
        } else {
            $cart = $this->getCartByUser($ma_user);
            if (!$cart) {
                return ['error' => 'Khong tim thay gio hang cua nguoi dung'];
            }

            $ma_gio_hang = $cart['ma_gio_hang'];
            $selectedItems = [];

            if (isset($payload['selected_items']) && is_array($payload['selected_items'])) {
                $selectedItems = $payload['selected_items'];
            } else {
                $raw = trim((string)($payload['items'] ?? $payload['selected_items_str'] ?? ''));
                if ($raw !== '') {
                    $selectedItems = array_map('trim', explode(',', $raw));
                }
            }

            $cartRows = $this->ctgh->ChiTietGioHang_getByCartId($ma_gio_hang);
            if ($cartRows) {
                while ($row = mysqli_fetch_assoc($cartRows)) {
                    $ma_bien_the = $row['ma_bien_the'];
                    if (!empty($selectedItems) && !in_array($ma_bien_the, $selectedItems, true)) {
                        continue;
                    }

                    $variantResult = $this->bt->BienThe_getById($ma_bien_the);
                    if (!$variantResult || mysqli_num_rows($variantResult) === 0) {
                        continue;
                    }

                    $variant = mysqli_fetch_assoc($variantResult);
                    $quantity = (int)$row['so_luong'];
                    if ($quantity <= 0) {
                        continue;
                    }

                    if ($quantity > (int)$variant['so_luong_kho']) {
                        $stockErrors[] = ($variant['ten_bien_the'] ?? $ma_bien_the) . ' (chi con ' . (int)$variant['so_luong_kho'] . ' san pham)';
                        continue;
                    }

                    $item = $this->formatCheckoutItem($ma_bien_the, $quantity, (float)$variant['gia'], $variant);
                    if (!$item) {
                        continue;
                    }

                    $item['qty_in_db'] = $quantity;
                    $items[] = $item;
                    $subtotal += $item['line_total'];
                }
            }
        }

        if (count($stockErrors) > 0) {
            return ['error' => 'Mot so san pham vuot ton kho', 'stock_errors' => $stockErrors];
        }

        if (count($items) === 0) {
            return ['error' => $isBuyNow ? 'Khong co san pham mua ngay hop le' : 'Khong co san pham hop le de thanh toan'];
        }

        $ma_khuyen_mai = trim((string)($payload['ma_khuyen_mai'] ?? $payload['ddlKhuyenMai'] ?? ''));
        $discount = 0;

        if ($ma_khuyen_mai !== '') {
            $kmResult = $this->km->KhuyenMai_getById($ma_khuyen_mai);
            if ($kmResult && mysqli_num_rows($kmResult) > 0) {
                $kmInfo = mysqli_fetch_assoc($kmResult);
                $discount = (float)($kmInfo['tien_khuyen_mai'] ?? 0);
            } else if (!$fromQuery) {
                return ['error' => 'Ma khuyen mai khong hop le'];
            }
        }

        $finalTotal = $subtotal - $discount;
        if ($finalTotal < 0) {
            $finalTotal = 0;
        }

        return [
            'is_buy_now' => $isBuyNow,
            'ma_gio_hang' => $ma_gio_hang,
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'final_total' => $finalTotal,
            'ma_khuyen_mai' => ($ma_khuyen_mai !== '' ? $ma_khuyen_mai : null)
        ];
    }

    private function getAddressInput($payload) {
        $ma_dia_chi = trim((string)($payload['ma_dia_chi'] ?? $payload['ddlDiaChi'] ?? ''));
        if ($ma_dia_chi !== '') {
            return ['ma_dia_chi' => $ma_dia_chi, 'created' => false];
        }

        $ho_ten = trim((string)($payload['txtHoTenNguoiNhan'] ?? $payload['txtHoTen'] ?? $payload['ho_ten'] ?? ''));
        $so_dien_thoai = trim((string)($payload['txtSoDienThoai'] ?? $payload['so_dien_thoai'] ?? ''));
        $dia_chi = trim((string)($payload['txtDiaChiGiaoHang'] ?? $payload['dia_chi'] ?? ''));

        if ($ho_ten === '' || $so_dien_thoai === '' || $dia_chi === '') {
            return ['error' => 'Vui long chon dia chi hoac nhap thong tin nguoi nhan day du'];
        }

        return [
            'create_new' => true,
            'ho_ten' => $ho_ten,
            'so_dien_thoai' => $so_dien_thoai,
            'dia_chi' => $dia_chi
        ];
    }

    private function getCurrentUserInfo($ma_user) {
        $userResult = $this->users->Users_getById($ma_user);
        if (!$userResult || mysqli_num_rows($userResult) === 0) {
            return null;
        }

        return mysqli_fetch_assoc($userResult);
    }

    private function getAddressListByUser($ma_user) {
        $addresses = [];
        $addrResult = $this->dc->DiaChiGiaoHang_getByUser($ma_user);
        if ($addrResult) {
            while ($row = mysqli_fetch_assoc($addrResult)) {
                $addresses[] = $row;
            }
        }

        return $addresses;
    }

    private function getPrimaryAddress($addresses) {
        foreach ($addresses as $address) {
            if ((int)($address['mac_dinh'] ?? 0) === 1) {
                return $address;
            }
        }

        return !empty($addresses) ? $addresses[0] : null;
    }

    private function getAvailablePromotions() {
        $promotions = [];
        $kmResult = $this->km->KhuyenMai_getAvailable();
        if ($kmResult) {
            while ($row = mysqli_fetch_assoc($kmResult)) {
                $promotions[] = $row;
            }
        }

        return $promotions;
    }

    private function normalizeHistoryStatus($status) {
        $status = trim((string)$status);
        if ($status === '' || strtolower($status) === 'all' || strtolower($status) === 'tat_ca') {
            return '';
        }

        $allowed = ['cho_duyet', 'da_duyet', 'dang_giao', 'hoan_thanh', 'da_huy'];
        return in_array($status, $allowed, true) ? $status : null;
    }

    private function getHistoryStatusLabel($status) {
        switch ((string)$status) {
            case 'cho_duyet':
                return 'Cho xac nhan';
            case 'da_duyet':
                return 'Da xac nhan';
            case 'dang_giao':
                return 'Dang giao';
            case 'hoan_thanh':
                return 'Hoan thanh';
            case 'da_huy':
                return 'Da huy';
            default:
                return (string)$status;
        }
    }

    private function getHistoryStatusCounts($ma_user) {
        $counts = [
            'all' => 0,
            'cho_duyet' => 0,
            'da_duyet' => 0,
            'dang_giao' => 0,
            'hoan_thanh' => 0,
            'da_huy' => 0
        ];

        $countResult = $this->dh->DonHang_countStatusByUser($ma_user);
        if ($countResult) {
            while ($row = mysqli_fetch_assoc($countResult)) {
                $status = trim((string)($row['trang_thai_don_hang'] ?? ''));
                $soLuong = (int)($row['so_luong'] ?? 0);
                if (array_key_exists($status, $counts)) {
                    $counts[$status] = $soLuong;
                }
            }
        }

        $counts['all'] = $counts['cho_duyet'] + $counts['da_duyet'] + $counts['dang_giao'] + $counts['hoan_thanh'] + $counts['da_huy'];
        return $counts;
    }

    private function buildHistoryOrderItem($orderRow) {
        $ma_don_hang = trim((string)($orderRow['ma_don_hang'] ?? ''));
        $details = [];
        $detailResult = $this->ctdh->ChiTietDonHang_getByOrderId($ma_don_hang);
        if ($detailResult) {
            while ($ct = mysqli_fetch_assoc($detailResult)) {
                $img = trim((string)($ct['img_hinh_anh'] ?? ''));
                $details[] = [
                    'ma_ctdh' => $ct['ma_ctdh'] ?? null,
                    'ma_bien_the' => $ct['ma_bien_the'] ?? null,
                    'ten_san_pham' => $ct['ten_san_pham'] ?? 'San pham da xoa',
                    'ten_bien_the' => $ct['ten_bien_the'] ?? '',
                    'mau_sac' => $ct['mau_sac'] ?? '',
                    'ram' => $ct['ram'] ?? '',
                    'dung_luong' => $ct['dung_luong'] ?? '',
                    'so_luong' => (int)($ct['so_luong'] ?? 0),
                    'gia_luc_mua' => (float)($ct['gia_luc_mua'] ?? 0),
                    'line_total' => ((float)($ct['gia_luc_mua'] ?? 0) * (int)($ct['so_luong'] ?? 0)),
                    'hinh_anh' => $img,
                    'hinh_anh_url' => ($img !== '' ? BASE_URL . 'Public/Pictures/bien_the/' . $img : BASE_URL . 'Public/Images/no-image.png')
                ];
            }
        }

        $discount = (float)($orderRow['tien_khuyen_mai'] ?? 0);
        $subtotal = (float)($orderRow['tong_tien_hang'] ?? 0);
        $paymentTotal = isset($orderRow['so_tien_thanh_toan']) && $orderRow['so_tien_thanh_toan'] !== null
            ? (float)$orderRow['so_tien_thanh_toan']
            : (float)($orderRow['thanh_toan'] ?? $subtotal);

        return [
            'ma_don_hang' => $ma_don_hang,
            'ma_user' => $orderRow['ma_user'] ?? null,
            'ma_dia_chi' => $orderRow['ma_dia_chi'] ?? null,
            'ma_khuyen_mai' => $orderRow['ma_khuyen_mai'] ?? null,
            'trang_thai_don_hang' => $orderRow['trang_thai_don_hang'] ?? '',
            'trang_thai_label' => $this->getHistoryStatusLabel($orderRow['trang_thai_don_hang'] ?? ''),
            'ngay_tao' => $orderRow['ngay_tao'] ?? null,
            'ten_nguoi_nhan' => $orderRow['ten_nguoi_nhan'] ?? '',
            'so_dien_thoai' => $orderRow['so_dien_thoai'] ?? '',
            'dia_chi' => $orderRow['dia_chi'] ?? '',
            'phuong_thuc' => $orderRow['phuong_thuc'] ?? '',
            'trang_thai_thanh_toan' => $orderRow['trang_thai_thanh_toan'] ?? '',
            'ten_khuyen_mai' => $orderRow['ten_khuyen_mai'] ?? '',
            'amounts' => [
                'subtotal' => $subtotal,
                'discount' => $discount,
                'final_total' => $paymentTotal
            ],
            'details' => $details
        ];
    }

    private function getHistoryOrders($ma_user, $status = '') {
        $orders = [];
        $result = $this->dh->DonHang_getHistoryByUser($ma_user, $status);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $orders[] = $this->buildHistoryOrderItem($row);
            }
        }

        return $orders;
    }

    private function buildCheckoutInitData($ma_user, $payload, $fromQuery = true) {
        $checkout = $this->buildCheckoutPayload($ma_user, $payload, $fromQuery);
        if (isset($checkout['error'])) {
            return $checkout;
        }

        $user = $this->getCurrentUserInfo($ma_user);
        if (!$user) {
            return ['error' => 'Khong tim thay thong tin nguoi dung', 'not_found_user' => true];
        }

        $addresses = $this->getAddressListByUser($ma_user);
        $primaryAddress = $this->getPrimaryAddress($addresses);
        $promotions = $this->getAvailablePromotions();

        $selectedPromotion = null;
        if (!empty($checkout['ma_khuyen_mai'])) {
            foreach ($promotions as $promotion) {
                if (($promotion['ma_khuyen_mai'] ?? '') === $checkout['ma_khuyen_mai']) {
                    $selectedPromotion = $promotion;
                    break;
                }
            }
        }

        return [
            'checkout' => $checkout,
            'billing_profile' => [
                'ma_user' => $user['ma_user'] ?? null,
                'ten_user' => $user['ten_user'] ?? null,
                'full_name' => $user['full_name'] ?? null,
                'email' => $user['email'] ?? null,
                'so_dien_thoai' => $user['so_dien_thoai'] ?? null
            ],
            'addresses' => $addresses,
            'selected_address' => $primaryAddress,
            'promotions' => $promotions,
            'selected_promotion' => $selectedPromotion,
            'payment_methods' => [
                ['value' => 'bank', 'label' => 'VNPAY QR - Thanh toan qua ma QR'],
                ['value' => 'cod', 'label' => 'Tra tien mat khi nhan hang (COD)']
            ],
            'form_defaults' => [
                'ddlDiaChi' => $primaryAddress['ma_dia_chi'] ?? '',
                'txtHoTen' => $user['full_name'] ?? '',
                'txtHoTenNguoiNhan' => '',
                'txtDiaChiGiaoHang' => '',
                'txtSoDienThoai' => '',
                'txtEmail' => '',
                'ddlKhuyenMai' => $checkout['ma_khuyen_mai'] ?? '',
                'payment_method' => 'cod',
                'txtGhiChu' => ''
            ],
            'meta' => [
                'server_time' => date('c'),
                'is_buy_now' => (bool)($checkout['is_buy_now'] ?? false),
                'item_count' => count($checkout['items'] ?? [])
            ]
        ];
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $initData = $this->buildCheckoutInitData($ma_user, $_GET, true);
        if (isset($initData['error'])) {
            if (!empty($initData['not_found_user'])) {
                $this->sendResponse(404, ['success' => false, 'message' => $initData['error']]);
            }

            $code = isset($initData['stock_errors']) ? 422 : 400;
            $this->sendResponse($code, ['success' => false, 'message' => $initData['error'], 'errors' => $initData['stock_errors'] ?? []]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay du lieu checkout thanh cong',
            'data' => $initData
        ]);
    }

    public function init() {
        // Endpoint de front-end form checkout goi 1 lan de lay toan bo du lieu can render.
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $initData = $this->buildCheckoutInitData($ma_user, $_GET, true);
        if (isset($initData['error'])) {
            if (!empty($initData['not_found_user'])) {
                $this->sendResponse(404, ['success' => false, 'message' => $initData['error']]);
            }

            $code = isset($initData['stock_errors']) ? 422 : 400;
            $this->sendResponse($code, ['success' => false, 'message' => $initData['error'], 'errors' => $initData['stock_errors'] ?? []]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay du lieu khoi tao form checkout thanh cong',
            'data' => $initData
        ]);
    }

    public function billing() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $user = $this->getCurrentUserInfo($ma_user);
        if (!$user) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay thong tin nguoi dung']);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay thong tin billing thanh cong',
            'data' => [
                'ma_user' => $user['ma_user'] ?? null,
                'ten_user' => $user['ten_user'] ?? null,
                'full_name' => $user['full_name'] ?? null,
                'email' => $user['email'] ?? null,
                'so_dien_thoai' => $user['so_dien_thoai'] ?? null
            ]
        ]);
    }

    public function promotions() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $this->requireAuthUser();
        $promotions = $this->getAvailablePromotions();

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay danh sach khuyen mai thanh cong',
            'total' => count($promotions),
            'data' => $promotions
        ]);
    }

    public function addresses($id = null) {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $ma_user = $this->requireAuthUser();

        if ($method === 'GET') {
            $addresses = $this->getAddressListByUser($ma_user);
            if ($id === null || trim((string)$id) === '') {
                $this->sendResponse(200, [
                    'success' => true,
                    'message' => 'Lay danh sach dia chi thanh cong',
                    'total' => count($addresses),
                    'data' => $addresses
                ]);
            }

            $targetId = trim((string)$id);
            foreach ($addresses as $address) {
                if (($address['ma_dia_chi'] ?? '') === $targetId) {
                    $this->sendResponse(200, [
                        'success' => true,
                        'message' => 'Lay chi tiet dia chi thanh cong',
                        'data' => $address
                    ]);
                }
            }

            $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay dia chi giao hang']);
        }

        if ($method === 'POST') {
            $payload = $this->parseInputData();
            $ho_ten = trim((string)($payload['ho_ten'] ?? $payload['txtHoTenNguoiNhan'] ?? ''));
            $so_dien_thoai = trim((string)($payload['so_dien_thoai'] ?? $payload['txtSoDienThoai'] ?? ''));
            $dia_chi = trim((string)($payload['dia_chi'] ?? $payload['txtDiaChiGiaoHang'] ?? ''));
            $mac_dinh = (int)($payload['mac_dinh'] ?? 0) === 1 ? 1 : 0;

            if ($ho_ten === '' || $so_dien_thoai === '' || $dia_chi === '') {
                $this->sendResponse(422, ['success' => false, 'message' => 'Vui long nhap day du ho ten, so dien thoai va dia chi']);
            }

            if (!preg_match('/^[0-9]{9,11}$/', $so_dien_thoai)) {
                $this->sendResponse(422, ['success' => false, 'message' => 'So dien thoai khong hop le']);
            }

            $ma_dia_chi = $this->nextAddressId();
            if ($mac_dinh === 1) {
                $existing = $this->getAddressListByUser($ma_user);
                foreach ($existing as $addr) {
                    $this->dc->DiaChiGiaoHang_update(
                        $addr['ma_dia_chi'],
                        $ma_user,
                        $addr['ho_ten'],
                        $addr['so_dien_thoai'],
                        $addr['dia_chi'],
                        0
                    );
                }
            }

            $ok = $this->dc->diachigiaohang_ins($ma_dia_chi, $ma_user, $ho_ten, $so_dien_thoai, $dia_chi, $mac_dinh);
            if (!$ok) {
                $this->sendResponse(500, ['success' => false, 'message' => 'Khong the tao dia chi giao hang']);
            }

            $detail = $this->dc->DiaChiGiaoHang_getById($ma_dia_chi);
            $address = ($detail && mysqli_num_rows($detail) > 0) ? mysqli_fetch_assoc($detail) : [
                'ma_dia_chi' => $ma_dia_chi,
                'ma_user' => $ma_user,
                'ho_ten' => $ho_ten,
                'so_dien_thoai' => $so_dien_thoai,
                'dia_chi' => $dia_chi,
                'mac_dinh' => $mac_dinh
            ];

            $this->sendResponse(201, [
                'success' => true,
                'message' => 'Tao dia chi giao hang thanh cong',
                'data' => $address
            ]);
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            $payload = $this->parseInputData();
            $ma_dia_chi = trim((string)($id ?? $payload['ma_dia_chi'] ?? $payload['ddlDiaChi'] ?? ''));
            if ($ma_dia_chi === '') {
                $this->sendResponse(422, ['success' => false, 'message' => 'Thieu ma_dia_chi can cap nhat']);
            }

            $existingResult = $this->dc->DiaChiGiaoHang_getById($ma_dia_chi);
            if (!$existingResult || mysqli_num_rows($existingResult) === 0) {
                $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay dia chi giao hang']);
            }

            $current = mysqli_fetch_assoc($existingResult);
            if (($current['ma_user'] ?? '') !== $ma_user) {
                $this->sendResponse(403, ['success' => false, 'message' => 'Ban khong co quyen sua dia chi nay']);
            }

            $ho_ten = trim((string)($payload['ho_ten'] ?? $payload['txtHoTenNguoiNhan'] ?? $current['ho_ten'] ?? ''));
            $so_dien_thoai = trim((string)($payload['so_dien_thoai'] ?? $payload['txtSoDienThoai'] ?? $current['so_dien_thoai'] ?? ''));
            $dia_chi = trim((string)($payload['dia_chi'] ?? $payload['txtDiaChiGiaoHang'] ?? $current['dia_chi'] ?? ''));
            $mac_dinh = isset($payload['mac_dinh']) ? ((int)$payload['mac_dinh'] === 1 ? 1 : 0) : (int)($current['mac_dinh'] ?? 0);

            if ($ho_ten === '' || $so_dien_thoai === '' || $dia_chi === '') {
                $this->sendResponse(422, ['success' => false, 'message' => 'Vui long nhap day du ho ten, so dien thoai va dia chi']);
            }

            if (!preg_match('/^[0-9]{9,11}$/', $so_dien_thoai)) {
                $this->sendResponse(422, ['success' => false, 'message' => 'So dien thoai khong hop le']);
            }

            if ($mac_dinh === 1) {
                $existing = $this->getAddressListByUser($ma_user);
                foreach ($existing as $addr) {
                    if (($addr['ma_dia_chi'] ?? '') === $ma_dia_chi) {
                        continue;
                    }

                    $this->dc->DiaChiGiaoHang_update(
                        $addr['ma_dia_chi'],
                        $ma_user,
                        $addr['ho_ten'],
                        $addr['so_dien_thoai'],
                        $addr['dia_chi'],
                        0
                    );
                }
            }

            $ok = $this->dc->DiaChiGiaoHang_update($ma_dia_chi, $ma_user, $ho_ten, $so_dien_thoai, $dia_chi, $mac_dinh);
            if (!$ok) {
                $this->sendResponse(500, ['success' => false, 'message' => 'Khong the cap nhat dia chi giao hang']);
            }

            $updatedResult = $this->dc->DiaChiGiaoHang_getById($ma_dia_chi);
            $updated = ($updatedResult && mysqli_num_rows($updatedResult) > 0) ? mysqli_fetch_assoc($updatedResult) : null;

            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Cap nhat dia chi giao hang thanh cong',
                'data' => $updated
            ]);
        }

        if ($method === 'DELETE') {
            $ma_dia_chi = trim((string)($id ?? ''));
            if ($ma_dia_chi === '') {
                $this->sendResponse(422, ['success' => false, 'message' => 'Thieu ma_dia_chi can xoa']);
            }

            $existingResult = $this->dc->DiaChiGiaoHang_getById($ma_dia_chi);
            if (!$existingResult || mysqli_num_rows($existingResult) === 0) {
                $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay dia chi giao hang']);
            }

            $current = mysqli_fetch_assoc($existingResult);
            if (($current['ma_user'] ?? '') !== $ma_user) {
                $this->sendResponse(403, ['success' => false, 'message' => 'Ban khong co quyen xoa dia chi nay']);
            }

            $ok = $this->dc->DiaChiGiaoHang_delete($ma_dia_chi);
            if (!$ok) {
                $this->sendResponse(500, ['success' => false, 'message' => 'Khong the xoa dia chi giao hang']);
            }

            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Xoa dia chi giao hang thanh cong',
                'data' => ['ma_dia_chi' => $ma_dia_chi]
            ]);
        }

        $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed']);
    }

    public function history() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $status = $this->normalizeHistoryStatus($_GET['status'] ?? '');
        if ($status === null) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Trang thai loc khong hop le']);
        }

        $orders = $this->getHistoryOrders($ma_user, $status);
        $counts = $this->getHistoryStatusCounts($ma_user);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay lich su don hang thanh cong',
            'data' => [
                'orders' => $orders,
                'counts' => $counts,
                'filters' => [
                    'status' => ($status === '' ? 'all' : $status)
                ],
                'total' => count($orders)
            ]
        ]);
    }

    public function status($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $status = $this->normalizeHistoryStatus($id);
        if ($status === null || $status === '') {
            $this->sendResponse(422, ['success' => false, 'message' => 'Trang thai loc khong hop le']);
        }

        $ma_user = $this->requireAuthUser();
        $orders = $this->getHistoryOrders($ma_user, $status);
        $counts = $this->getHistoryStatusCounts($ma_user);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay lich su don hang theo trang thai thanh cong',
            'data' => [
                'orders' => $orders,
                'counts' => $counts,
                'filters' => [
                    'status' => $status
                ],
                'total' => count($orders)
            ]
        ]);
    }

    public function summary() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $counts = $this->getHistoryStatusCounts($ma_user);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay tong quan trang thai don hang thanh cong',
            'data' => $counts
        ]);
    }

    public function get_detail($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_don_hang = trim((string)$id);
        if ($ma_don_hang === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thieu ma don hang']);
        }

        $orderResult = $this->dh->DonHang_getById($ma_don_hang);
        if (!$orderResult || mysqli_num_rows($orderResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay don hang']);
        }

        $order = mysqli_fetch_assoc($orderResult);
        if (($order['ma_user'] ?? '') !== $ma_user) {
            $this->sendResponse(403, ['success' => false, 'message' => 'Ban khong co quyen truy cap don hang nay']);
        }

        $details = [];
        $detailResult = $this->ctdh->ChiTietDonHang_getByOrderId($ma_don_hang);
        if ($detailResult) {
            while ($row = mysqli_fetch_assoc($detailResult)) {
                $details[] = $row;
            }
        }

        $payment = null;
        $paymentResult = $this->tt->ThanhToan_getByOrder($ma_don_hang);
        if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
            $payment = mysqli_fetch_assoc($paymentResult);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay chi tiet checkout thanh cong',
            'data' => [
                'order' => $order,
                'details' => $details,
                'payment' => $payment
            ]
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $ma_user = $this->requireAuthUser();
        $payload = $this->parseInputData();

        $payment_method = trim((string)($payload['payment_method'] ?? 'cod'));
        if (!in_array($payment_method, ['cod', 'bank'], true)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Phuong thuc thanh toan khong hop le']);
        }

        $email = trim((string)($payload['txtEmail'] ?? $payload['email'] ?? ''));
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Email khong hop le']);
        }

        $checkout = $this->buildCheckoutPayload($ma_user, $payload, false);
        if (isset($checkout['error'])) {
            $code = isset($checkout['stock_errors']) ? 422 : 400;
            $this->sendResponse($code, ['success' => false, 'message' => $checkout['error'], 'errors' => $checkout['stock_errors'] ?? []]);
        }

        $addressInput = $this->getAddressInput($payload);
        if (isset($addressInput['error'])) {
            $this->sendResponse(422, ['success' => false, 'message' => $addressInput['error']]);
        }

        $ma_dia_chi = null;
        if (!empty($addressInput['create_new'])) {
            $ma_dia_chi = $this->nextAddressId();
            $okAddress = $this->dc->diachigiaohang_ins(
                $ma_dia_chi,
                $ma_user,
                $addressInput['ho_ten'],
                $addressInput['so_dien_thoai'],
                $addressInput['dia_chi'],
                0
            );

            if (!$okAddress) {
                $this->sendResponse(500, ['success' => false, 'message' => 'Khong the tao dia chi giao hang']);
            }
        } else {
            $ma_dia_chi = $addressInput['ma_dia_chi'];
            $addrCheck = $this->dc->DiaChiGiaoHang_getById($ma_dia_chi);
            if (!$addrCheck || mysqli_num_rows($addrCheck) === 0) {
                $this->sendResponse(422, ['success' => false, 'message' => 'Dia chi giao hang khong ton tai']);
            }
            $addrRow = mysqli_fetch_assoc($addrCheck);
            if (($addrRow['ma_user'] ?? '') !== $ma_user) {
                $this->sendResponse(403, ['success' => false, 'message' => 'Dia chi giao hang khong thuoc tai khoan hien tai']);
            }
        }

        $ma_don_hang = $this->dh->getNextOrderId();
        $okOrder = $this->dh->donhang_ins(
            $ma_don_hang,
            $ma_user,
            $ma_dia_chi,
            $checkout['ma_khuyen_mai'],
            $checkout['subtotal'],
            $checkout['final_total'],
            'cho_duyet'
        );

        if (!$okOrder) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Khong the tao don hang']);
        }

        foreach ($checkout['items'] as $item) {
            $ma_ctdh = $this->ctdh->getNextDetailOrderId();
            $okDetail = $this->ctdh->chitietdonhang_ins(
                $ma_ctdh,
                $ma_don_hang,
                $item['ma_bien_the'],
                $item['so_luong'],
                $item['gia']
            );

            if (!$okDetail) {
                $this->sendResponse(500, ['success' => false, 'message' => 'Khong the tao chi tiet don hang']);
            }

            $variantResult = $this->bt->BienThe_getById($item['ma_bien_the']);
            if ($variantResult && mysqli_num_rows($variantResult) > 0) {
                $variant = mysqli_fetch_assoc($variantResult);
                $newStock = (int)$variant['so_luong_kho'] - (int)$item['so_luong'];
                if ($newStock < 0) {
                    $newStock = 0;
                }

                $this->bt->BienThe_update(
                    $item['ma_bien_the'],
                    $variant['ma_san_pham'],
                    $variant['ten_bien_the'],
                    $variant['img_bien_the'],
                    $variant['mau_sac'],
                    $variant['ram'],
                    $variant['dung_luong'],
                    $variant['gia'],
                    $newStock
                );
            }
        }

        $ma_giao_dich = $this->nextPaymentId();
        $phuong_thuc_luu = ($payment_method === 'bank') ? 'VNPAY' : 'COD';
        $this->tt->thanhtoan_ins(
            $ma_giao_dich,
            $ma_don_hang,
            $phuong_thuc_luu,
            $checkout['final_total'],
            'chua_thanh_toan'
        );

        if (!$checkout['is_buy_now'] && !empty($checkout['ma_gio_hang'])) {
            foreach ($checkout['items'] as $item) {
                $qtyInDb = isset($item['qty_in_db']) ? (int)$item['qty_in_db'] : (int)$item['so_luong'];
                $buyQty = (int)$item['so_luong'];

                if ($qtyInDb > $buyQty) {
                    $this->ctgh->ChiTietGioHang_update($checkout['ma_gio_hang'], $item['ma_bien_the'], $qtyInDb - $buyQty);
                } else {
                    $this->ctgh->ChiTietGioHang_delete($checkout['ma_gio_hang'], $item['ma_bien_the']);
                }
            }

            $this->gh->GioHang_update($checkout['ma_gio_hang'], $ma_user, 'ordered');
        }

        $paymentUrl = null;
        if ($payment_method === 'bank') {
            require_once __DIR__ . '/../../Core/VnPayHelper.php';
            $orderInfo = 'Thanh toan don hang #' . $ma_don_hang;
            $paymentUrl = VnPayHelper::createPaymentUrl($orderInfo, $checkout['final_total'], $ma_don_hang, 'vn');
        }

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Tao don hang thanh cong',
            'data' => [
                'ma_don_hang' => $ma_don_hang,
                'ma_giao_dich' => $ma_giao_dich,
                'is_buy_now' => $checkout['is_buy_now'],
                'subtotal' => $checkout['subtotal'],
                'discount' => $checkout['discount'],
                'final_total' => $checkout['final_total'],
                'payment_method' => $payment_method,
                'payment_url' => $paymentUrl,
                'redirect_url' => BASE_URL . 'Khachhang/camon/' . $ma_don_hang
            ]
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT/PATCH/POST']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_don_hang = trim((string)$id);
        if ($ma_don_hang === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thieu ma don hang']);
        }

        $orderResult = $this->dh->DonHang_getById($ma_don_hang);
        if (!$orderResult || mysqli_num_rows($orderResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay don hang']);
        }

        $order = mysqli_fetch_assoc($orderResult);
        if (($order['ma_user'] ?? '') !== $ma_user) {
            $this->sendResponse(403, ['success' => false, 'message' => 'Ban khong co quyen cap nhat don nay']);
        }

        $payload = $this->parseInputData();
        $status = trim((string)($payload['trang_thai_don_hang'] ?? $payload['status'] ?? ''));
        if ($status === '') {
            $this->sendResponse(422, ['success' => false, 'message' => 'Thieu trang thai can cap nhat']);
        }

        $allowed = ['cho_duyet', 'da_huy'];
        if (!in_array($status, $allowed, true)) {
            $this->sendResponse(422, ['success' => false, 'message' => 'Trang thai cap nhat khong hop le']);
        }

        if (($order['trang_thai_don_hang'] ?? '') !== 'cho_duyet' && $status === 'da_huy') {
            $this->sendResponse(409, ['success' => false, 'message' => 'Chi co the huy don o trang thai cho_duyet']);
        }

        if ($status === 'da_huy' && ($order['trang_thai_don_hang'] ?? '') !== 'da_huy') {
            $ok = $this->dh->DonHang_cancelWithRestock($ma_don_hang);
        } else {
            $ok = $this->dh->DonHang_updateStatus($ma_don_hang, $status);
        }
        if (!$ok) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Khong the cap nhat don hang']);
        }

        if ($status === 'da_huy') {
            $paymentResult = $this->tt->ThanhToan_getByOrder($ma_don_hang);
            if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
                $paymentRow = mysqli_fetch_assoc($paymentResult);
                $this->tt->ThanhToan_update(
                    $paymentRow['ma_giao_dich'],
                    $ma_don_hang,
                    $paymentRow['phuong_thuc'],
                    $paymentRow['so_tien_thanh_toan'],
                    'that_bai'
                );
            }
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cap nhat don checkout thanh cong',
            'data' => [
                'ma_don_hang' => $ma_don_hang,
                'trang_thai_don_hang' => $status
            ]
        ]);
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE/POST']);
        }

        $ma_user = $this->requireAuthUser();
        $ma_don_hang = trim((string)$id);
        if ($ma_don_hang === '') {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thieu ma don hang']);
        }

        $orderResult = $this->dh->DonHang_getById($ma_don_hang);
        if (!$orderResult || mysqli_num_rows($orderResult) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Khong tim thay don hang']);
        }

        $order = mysqli_fetch_assoc($orderResult);
        if (($order['ma_user'] ?? '') !== $ma_user) {
            $this->sendResponse(403, ['success' => false, 'message' => 'Ban khong co quyen huy don nay']);
        }

        if (($order['trang_thai_don_hang'] ?? '') !== 'cho_duyet') {
            $this->sendResponse(409, ['success' => false, 'message' => 'Chi huy duoc don o trang thai cho_duyet']);
        }

        $ok = $this->dh->DonHang_cancelWithRestock($ma_don_hang);
        if (!$ok) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Khong the huy don']);
        }

        $paymentResult = $this->tt->ThanhToan_getByOrder($ma_don_hang);
        if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
            $paymentRow = mysqli_fetch_assoc($paymentResult);
            $this->tt->ThanhToan_update(
                $paymentRow['ma_giao_dich'],
                $ma_don_hang,
                $paymentRow['phuong_thuc'],
                $paymentRow['so_tien_thanh_toan'],
                'that_bai'
            );
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Huy don checkout thanh cong',
            'data' => [
                'ma_don_hang' => $ma_don_hang,
                'trang_thai_don_hang' => 'da_huy'
            ]
        ]);
    }

    public function preview() {
        // Alias GET /Api/Checkout/preview?.... cho de tich hop view.
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $checkout = $this->buildCheckoutPayload($ma_user, $_GET, true);
        if (isset($checkout['error'])) {
            $code = isset($checkout['stock_errors']) ? 422 : 400;
            $this->sendResponse($code, ['success' => false, 'message' => $checkout['error'], 'errors' => $checkout['stock_errors'] ?? []]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Preview checkout thanh cong',
            'data' => $checkout
        ]);
    }
}
