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

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $ma_user = $this->requireAuthUser();
        $checkout = $this->buildCheckoutPayload($ma_user, $_GET, true);
        if (isset($checkout['error'])) {
            $code = isset($checkout['stock_errors']) ? 422 : 400;
            $this->sendResponse($code, ['success' => false, 'message' => $checkout['error'], 'errors' => $checkout['stock_errors'] ?? []]);
        }

        $addresses = [];
        $addrResult = $this->dc->DiaChiGiaoHang_getByUser($ma_user);
        if ($addrResult) {
            while ($row = mysqli_fetch_assoc($addrResult)) {
                $addresses[] = $row;
            }
        }

        $promotions = [];
        $kmResult = $this->km->KhuyenMai_getAvailable();
        if ($kmResult) {
            while ($row = mysqli_fetch_assoc($kmResult)) {
                $promotions[] = $row;
            }
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lay du lieu checkout thanh cong',
            'data' => [
                'checkout' => $checkout,
                'addresses' => $addresses,
                'promotions' => $promotions
            ]
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

        $ok = $this->dh->DonHang_updateStatus($ma_don_hang, $status);
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

        $ok = $this->dh->DonHang_updateStatus($ma_don_hang, 'da_huy');
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
