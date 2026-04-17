<?php
class Techbot extends api_controller {
    private $sanpham_model;
    private $bienthe_model;
    private $donhang_model;
    private $ctdh_model;
    private $thanhtoan_model;
    private $khuyenmai_model;
    private $users_model;
    private $giohang_model;
    private $ctgh_model;
    private $danhgia_model;
    private $diachi_model;
    private $danhmuc_model;
    private $thuonghieu_model;
    private $nhacungcap_model;

    public function __construct() {
        parent::__construct();
        $this->sanpham_model = $this->model('SanPham_m');
        $this->bienthe_model = $this->model('BienThe_m');
        $this->donhang_model = $this->model('DonHang_m');
        $this->ctdh_model = $this->model('ChiTietDonHang_m');
        $this->thanhtoan_model = $this->model('ThanhToan_m');
        $this->khuyenmai_model = $this->model('KhuyenMai_m');
        $this->users_model = $this->model('Users_m');
        $this->giohang_model = $this->model('GioHang_m');
        $this->ctgh_model = $this->model('ChiTietGioHang_m');
        $this->danhgia_model = $this->model('DanhGia_m');
        $this->diachi_model = $this->model('DiaChiGiaoHang_m');
        $this->danhmuc_model = $this->model('DanhMuc_m');
        $this->thuonghieu_model = $this->model('ThuongHieu_m');
        $this->nhacungcap_model = $this->model('NhaCungCap_m');
    }

    public function ask() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use POST'
            ]);
        }

        $data = $this->getJsonInput();
        if (empty($data) && !empty($_POST)) {
            $data = $_POST;
        }

        $message = trim((string)($data['message'] ?? $data['question'] ?? ''));
        if ($message === '') {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Vui lòng nhập nội dung câu hỏi'
            ]);
        }

        $priceFilter = $this->extractPriceFilter($message);

        $role = $this->resolveRole();
        $intentMeta = $this->detectIntent($message, $role);
        $intent = $intentMeta['intent'];
        $entities = $intentMeta['entities'];

        $messageLower = mb_strtolower($message, 'UTF-8');
        if (strpos($messageLower, 'doanh thu') !== false || strpos($messageLower, 'doanh số') !== false || strpos($messageLower, 'doanh so') !== false) {
            $intent = ($role === 'admin') ? 'admin_revenue_report' : 'restricted_info';
        }

        if (
            ($role === 'admin')
            && (strpos($messageLower, 'đơn hàng') !== false || strpos($messageLower, 'don hang') !== false)
            && $this->extractOrderCode($message) === ''
            && (
                strpos($messageLower, 'bao nhiêu') !== false
                || strpos($messageLower, 'bao nhieu') !== false
                || strpos($messageLower, 'thống kê') !== false
                || strpos($messageLower, 'thong ke') !== false
                || strpos($messageLower, 'đếm') !== false
                || strpos($messageLower, 'dem') !== false
                || strpos($messageLower, 'đã hủy') !== false
                || strpos($messageLower, 'da huy') !== false
                || strpos($messageLower, 'đang giao') !== false
                || strpos($messageLower, 'dang giao') !== false
                || strpos($messageLower, 'chờ duyệt') !== false
                || strpos($messageLower, 'cho duyet') !== false
                || strpos($messageLower, 'hoàn thành') !== false
                || strpos($messageLower, 'hoan thanh') !== false
            )
        ) {
            $intent = 'admin_order_stats';
        }

        if ($role !== 'admin' && in_array($intent, ['admin_pending_orders', 'admin_inventory_alert', 'admin_users_list', 'admin_api_help', 'admin_full_access', 'admin_revenue_report', 'admin_order_stats'], true)) {
            $intent = 'restricted_info';
        }

        $payload = [
            'question' => $message,
            'role' => $role,
            'intent' => $intent,
            'data' => []
        ];

        if ($intent === 'customer_my_orders' && $role !== 'admin') {
            $myOrders = $this->getCurrentCustomerOrders($message);
            $payload['data'] = $myOrders;
            $reply = $this->buildResponse($payload);

            $this->sendResponse(200, [
                'success' => true,
                'role' => $role,
                'intent' => $intent,
                'reply' => $reply,
                'data' => $myOrders
            ]);
        }

        if ($intent === 'order_status') {
            $orderCode = trim((string)($entities['order_code'] ?? $this->extractOrderCode($message)));
            if ($orderCode === '') {
                $reply = $this->normalizeReplyForChat($this->buildNeedOrderCodeReply());
                $this->sendResponse(200, [
                    'success' => true,
                    'role' => $role,
                    'intent' => $intent,
                    'reply' => $reply,
                    'data' => []
                ]);
            }

            $orderInfo = $this->getOrderStatus($orderCode);
            $payload['data'] = $orderInfo;
            $reply = $this->buildResponse($payload);

            $this->sendResponse(200, [
                'success' => true,
                'role' => $role,
                'intent' => $intent,
                'reply' => $reply,
                'data' => $orderInfo
            ]);
        }

        if ($role === 'admin') {
            if ($intent === 'admin_full_access') {
                $payload['data'] = $this->getAdminFullShopData();
            } else if ($intent === 'admin_order_stats') {
                $payload['data'] = $this->getAdminOrderStats($message);
            } else if ($intent === 'admin_revenue_report') {
                $range = $this->extractRevenueRange($message);
                $payload['data'] = $this->getRevenueReport($range);
            } else if ($intent === 'product_variant_lookup') {
                $keyword = trim((string)($entities['product_keyword'] ?? $this->extractProductKeyword($message)));
                $payload['data'] = $this->getProductVariants($keyword, true);
            } else if ($intent === 'admin_pending_orders') {
                $payload['data'] = $this->getPendingOrders();
            } else if ($intent === 'admin_inventory_alert') {
                $payload['data'] = $this->getLowStockProducts();
            } else if ($intent === 'admin_users_list') {
                $payload['data'] = $this->getUsersPreview();
            } else if ($intent === 'admin_api_help') {
                $payload['data'] = $this->getAdminApiHelp();
            } else if ($intent === 'promotion_lookup') {
                $payload['data'] = $this->getActivePromotions();
            } else {
                $keyword = $this->resolveProductKeyword($entities, $message, $priceFilter);
                $payload['data'] = $this->getProducts($keyword, true, $priceFilter);

                if (empty($payload['data']['items']) && $intent !== 'product_lookup') {
                    $payload['data'] = $this->getAdminOverview();
                    $payload['intent'] = 'admin_overview';
                }
            }

            $reply = $this->buildResponse($payload);
            $this->sendResponse(200, [
                'success' => true,
                'role' => $role,
                'intent' => $payload['intent'],
                'reply' => $reply,
                'data' => $payload['data']
            ]);
        }

        // Mặc định cho khách hàng
        if ($intent === 'promotion_lookup') {
            $payload['data'] = $this->getActivePromotions();
        } else if ($intent === 'product_variant_lookup') {
            $keyword = $this->resolveProductKeyword($entities, $message, $priceFilter);
            $payload['data'] = $this->getProductVariants($keyword, false);
        } else if ($intent === 'restricted_info') {
            $payload['data'] = [];
        } else if ($intent === 'greeting') {
            $payload['data'] = [];
        } else {
            $keyword = $this->resolveProductKeyword($entities, $message, $priceFilter);
            $payload['data'] = $this->getProducts($keyword, false, $priceFilter);

            if (empty($payload['data']['items']) && $intent !== 'product_lookup') {
                $payload['intent'] = 'unknown';
            }
        }

        $reply = $this->buildResponse($payload);
        $this->sendResponse(200, [
            'success' => true,
            'role' => $role,
            'intent' => $payload['intent'],
            'reply' => $reply,
            'data' => $payload['data']
        ]);
    }

    private function resolveRole() {
        $sessionRole = isset($_SESSION['user_role']) ? trim((string)$_SESSION['user_role']) : '';
        return ($sessionRole === 'admin') ? 'admin' : 'customer';
    }

    private function detectIntent($message, $role) {
        $fallback = $this->detectIntentByHeuristic($message, $role);

        // Ưu tiên intent cứng cho "đơn hàng của tôi" để không bị classifier kéo về order_status.
        if (($fallback['intent'] ?? '') === 'customer_my_orders') {
            return $fallback;
        }

        $groqIntent = $this->detectIntentByGroq($message, $role);

        if (!is_array($groqIntent) || empty($groqIntent['intent'])) {
            return $fallback;
        }

        return [
            'intent' => $groqIntent['intent'],
            'entities' => [
                'order_code' => trim((string)($groqIntent['entities']['order_code'] ?? '')),
                'product_keyword' => trim((string)($groqIntent['entities']['product_keyword'] ?? ''))
            ]
        ];
    }

    private function detectIntentByHeuristic($message, $role) {
        $text = mb_strtolower($message, 'UTF-8');

        if ($role !== 'admin') {
            $hasOrderWord = (
                strpos($text, 'đơn') !== false
                || strpos($text, 'don') !== false
                || strpos($text, 'order') !== false
            );
            $statusFilter = $this->extractOrderStatusFilter($message);

            if ($hasOrderWord && $statusFilter !== null && $this->extractOrderCode($message) === '') {
                return ['intent' => 'customer_my_orders', 'entities' => ['order_code' => '', 'product_keyword' => '']];
            }

            $hasMyOrderPhrase = (
                strpos($text, 'đơn hàng của tôi') !== false
                || strpos($text, 'don hang cua toi') !== false
                || strpos($text, 'đơn hàng tôi') !== false
                || strpos($text, 'don hang toi') !== false
                || strpos($text, 'tôi đã mua') !== false
                || strpos($text, 'toi da mua') !== false
                || strpos($text, 'lịch sử mua') !== false
                || strpos($text, 'lich su mua') !== false
                || strpos($text, 'đơn đã mua') !== false
                || strpos($text, 'don da mua') !== false
            );

            if ($hasMyOrderPhrase && $this->extractOrderCode($message) === '') {
                return ['intent' => 'customer_my_orders', 'entities' => ['order_code' => '', 'product_keyword' => '']];
            }
        }

        if ($role === 'admin') {
            $hasOrderPhrase = (strpos($text, 'đơn hàng') !== false || strpos($text, 'don hang') !== false);
            $hasCountPhrase = (
                strpos($text, 'bao nhiêu') !== false
                || strpos($text, 'bao nhieu') !== false
                || strpos($text, 'thống kê') !== false
                || strpos($text, 'thong ke') !== false
                || strpos($text, 'đếm') !== false
                || strpos($text, 'dem') !== false
                || strpos($text, 'đã hủy') !== false
                || strpos($text, 'da huy') !== false
                || strpos($text, 'đang giao') !== false
                || strpos($text, 'dang giao') !== false
                || strpos($text, 'chờ duyệt') !== false
                || strpos($text, 'cho duyet') !== false
                || strpos($text, 'hoàn thành') !== false
                || strpos($text, 'hoan thanh') !== false
            );

            if ($hasOrderPhrase && $hasCountPhrase && $this->extractOrderCode($message) === '') {
                return ['intent' => 'admin_order_stats', 'entities' => ['order_code' => '', 'product_keyword' => '']];
            }
        }

        if (strpos($text, 'đơn hàng') !== false || strpos($text, 'ma don hang') !== false || strpos($text, 'mã đơn hàng') !== false || strpos($text, 'trạng thái') !== false) {
            return ['intent' => 'order_status', 'entities' => ['order_code' => $this->extractOrderCode($message), 'product_keyword' => '']];
        }

        if (strpos($text, 'khuyến mãi') !== false || strpos($text, 'mã giảm') !== false || strpos($text, 'voucher') !== false) {
            return ['intent' => 'promotion_lookup', 'entities' => ['order_code' => '', 'product_keyword' => '']];
        }

        if (
            strpos($text, 'doanh thu') !== false
            || strpos($text, 'doanh số') !== false
            || strpos($text, 'doanh so') !== false
            || strpos($text, 'báo cáo doanh thu') !== false
            || strpos($text, 'bao cao doanh thu') !== false
        ) {
            if ($role === 'admin') {
                return ['intent' => 'admin_revenue_report', 'entities' => ['order_code' => '', 'product_keyword' => '']];
            }
            return ['intent' => 'restricted_info', 'entities' => ['order_code' => '', 'product_keyword' => '']];
        }

        if (
            strpos($text, 'biến thể') !== false
            || strpos($text, 'bien the') !== false
            || strpos($text, 'phiên bản') !== false
            || strpos($text, 'phien ban') !== false
        ) {
            return ['intent' => 'product_variant_lookup', 'entities' => ['order_code' => '', 'product_keyword' => $this->extractProductKeyword($message)]];
        }

        if (
            strpos($text, 'toàn bộ thông tin') !== false
            || strpos($text, 'toan bo thong tin') !== false
            || strpos($text, 'toàn bộ dữ liệu') !== false
            || strpos($text, 'toan bo du lieu') !== false
            || strpos($text, 'full dữ liệu') !== false
            || strpos($text, 'full du lieu') !== false
            || strpos($text, 'toàn shop') !== false
            || strpos($text, 'toan shop') !== false
            || strpos($text, 'tất cả đơn hàng') !== false
            || strpos($text, 'tat ca don hang') !== false
            || strpos($text, 'tất cả sản phẩm') !== false
            || strpos($text, 'tat ca san pham') !== false
            || strpos($text, 'tất cả người dùng') !== false
            || strpos($text, 'tat ca nguoi dung') !== false
        ) {
            return ['intent' => 'admin_full_access', 'entities' => ['order_code' => '', 'product_keyword' => '']];
        }

        if (
            strpos($text, 'lợi nhuận') !== false
            || strpos($text, 'loi nhuan') !== false
            || strpos($text, 'danh sách người dùng') !== false
            || strpos($text, 'danh sach nguoi dung') !== false
            || strpos($text, 'nhà cung cấp') !== false
            || strpos($text, 'nha cung cap') !== false
            || strpos($text, 'lỗi hệ thống') !== false
            || strpos($text, 'loi he thong') !== false
        ) {
            return ['intent' => 'restricted_info', 'entities' => ['order_code' => '', 'product_keyword' => '']];
        }

        if ($role === 'admin') {
            if (strpos($text, 'chờ duyệt') !== false || strpos($text, 'cho_duyet') !== false) {
                return ['intent' => 'admin_pending_orders', 'entities' => ['order_code' => '', 'product_keyword' => '']];
            }

            if (strpos($text, 'sắp hết') !== false || strpos($text, 'sap het') !== false || strpos($text, 'tồn kho thấp') !== false) {
                return ['intent' => 'admin_inventory_alert', 'entities' => ['order_code' => '', 'product_keyword' => '']];
            }

            if (strpos($text, 'người dùng') !== false || strpos($text, 'khách hàng') !== false || strpos($text, 'users') !== false) {
                return ['intent' => 'admin_users_list', 'entities' => ['order_code' => '', 'product_keyword' => '']];
            }

            if (strpos($text, 'endpoint') !== false || strpos($text, 'api') !== false) {
                return ['intent' => 'admin_api_help', 'entities' => ['order_code' => '', 'product_keyword' => '']];
            }
        }

        if (strpos($text, 'xin chào') !== false || strpos($text, 'chào') !== false || strpos($text, 'hello') !== false || strpos($text, 'hi') !== false) {
            return ['intent' => 'greeting', 'entities' => ['order_code' => '', 'product_keyword' => '']];
        }

        if (strpos($text, 'điện thoại') !== false || strpos($text, 'iphone') !== false || strpos($text, 'samsung') !== false || strpos($text, 'xiaomi') !== false || strpos($text, 'oppo') !== false || strpos($text, 'vivo') !== false || strpos($text, 'laptop') !== false || strpos($text, 'macbook') !== false || strpos($text, 'sản phẩm') !== false) {
            return ['intent' => 'product_lookup', 'entities' => ['order_code' => '', 'product_keyword' => $this->extractProductKeyword($message)]];
        }

        return ['intent' => 'unknown', 'entities' => ['order_code' => '', 'product_keyword' => $this->extractProductKeyword($message)]];
    }

    private function detectIntentByGroq($message, $role) {
        $apiKey = $this->getGroqApiKey();
        if ($apiKey === '') {
            return null;
        }

        $prompt = "Bạn là bộ phân loại intent cho TechZone.\n"
            . "Chỉ trả về JSON thuần theo schema: {\"intent\":\"...\",\"entities\":{\"order_code\":\"\",\"product_keyword\":\"\"}}.\n"
            . "Intent hợp lệ: greeting, product_lookup, product_variant_lookup, order_status, customer_my_orders, promotion_lookup, admin_revenue_report, admin_order_stats, admin_pending_orders, admin_inventory_alert, admin_users_list, admin_api_help, admin_full_access, restricted_info, unknown.\n"
            . "Vai trò hiện tại: " . $role . ".\n"
            . "Câu hỏi: " . $message;

        $response = $this->callGroq($apiKey, [
            ['role' => 'system', 'content' => 'Bạn là intent-classifier. Không thêm giải thích.'],
            ['role' => 'user', 'content' => $prompt]
        ]);

        if ($response === '') {
            return null;
        }

        $decoded = $this->decodeJsonFromText($response);
        if (!is_array($decoded) || empty($decoded['intent'])) {
            return null;
        }

        return $decoded;
    }

    private function getProducts($keyword, $includeOutOfStock, $priceFilter = null) {
        if (is_array($priceFilter)) {
            return $this->getProductsByVariantPriceFilter($keyword, $includeOutOfStock, $priceFilter);
        }

        // Luôn lấy full list và lọc local theo token để hiểu tốt câu tự nhiên của người dùng.
        $result = $this->sanpham_model->SanPham_getAll();

        $map = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = (string)($row['ma_san_pham'] ?? '');
                if ($id === '') {
                    continue;
                }

                if (!isset($map[$id])) {
                    $map[$id] = [
                        'ma_san_pham' => $id,
                        'ten_san_pham' => $row['ten_san_pham'] ?? '',
                        'ma_danh_muc' => $row['ma_danh_muc'] ?? '',
                        'ma_thuong_hieu' => $row['ma_thuong_hieu'] ?? '',
                        'ma_nha_cung_cap' => $row['ma_nha_cung_cap'] ?? '',
                        'ten_nha_cung_cap' => $row['ten_nha_cung_cap'] ?? '',
                        'ngay_tao' => $row['ngay_tao'] ?? '',
                        'gia' => isset($row['gia']) ? (float)$row['gia'] : 0,
                        'so_luong_kho' => isset($row['so_luong_kho']) ? (int)$row['so_luong_kho'] : 0,
                        'ten_danh_muc' => $row['ten_danh_muc'] ?? '',
                        'ten_thuong_hieu' => $row['ten_thuong_hieu'] ?? '',
                        'img_bien_the' => $row['img_bien_the'] ?? '',
                        'ten_bien_the' => $row['ten_bien_the'] ?? ''
                    ];
                } else {
                    $currentPrice = isset($row['gia']) ? (float)$row['gia'] : 0;
                    if ($currentPrice > 0 && ($map[$id]['gia'] <= 0 || $currentPrice < $map[$id]['gia'])) {
                        $map[$id]['gia'] = $currentPrice;
                    }
                    $map[$id]['so_luong_kho'] += isset($row['so_luong_kho']) ? (int)$row['so_luong_kho'] : 0;
                    if ($map[$id]['img_bien_the'] === '' && !empty($row['img_bien_the'])) {
                        $map[$id]['img_bien_the'] = $row['img_bien_the'];
                    }
                    if (($map[$id]['ten_nha_cung_cap'] ?? '') === '' && !empty($row['ten_nha_cung_cap'])) {
                        $map[$id]['ten_nha_cung_cap'] = $row['ten_nha_cung_cap'];
                    }
                    if (($map[$id]['ten_bien_the'] ?? '') === '' && !empty($row['ten_bien_the'])) {
                        $map[$id]['ten_bien_the'] = $row['ten_bien_the'];
                    }
                }
            }
        }

        $items = array_values($map);

        // Đồng bộ giá và tồn kho theo toàn bộ biến thể để lọc theo giá chính xác.
        foreach ($items as &$item) {
            $productId = trim((string)($item['ma_san_pham'] ?? ''));
            if ($productId === '' || !$this->bienthe_model) {
                continue;
            }

            $variantResult = $this->bienthe_model->BienThe_getByProduct($productId);
            if (!$variantResult) {
                continue;
            }

            $minPrice = null;
            $totalStock = 0;
            $firstImage = (string)($item['img_bien_the'] ?? '');
            $firstVariantName = (string)($item['ten_bien_the'] ?? '');
            $matchedMinPrice = null;
            $matchedStock = 0;
            $matchedVariantCount = 0;

            $filterMin = (is_array($priceFilter) && array_key_exists('min', $priceFilter) && $priceFilter['min'] !== null)
                ? (float)$priceFilter['min'] : null;
            $filterMax = (is_array($priceFilter) && array_key_exists('max', $priceFilter) && $priceFilter['max'] !== null)
                ? (float)$priceFilter['max'] : null;

            while ($variant = mysqli_fetch_assoc($variantResult)) {
                $price = isset($variant['gia']) ? (float)$variant['gia'] : 0;
                $stock = isset($variant['so_luong_kho']) ? (int)$variant['so_luong_kho'] : 0;

                if ($minPrice === null || ($price > 0 && $price < $minPrice)) {
                    $minPrice = $price;
                }
                $totalStock += $stock;

                if ($firstImage === '' && !empty($variant['img_bien_the'])) {
                    $firstImage = (string)$variant['img_bien_the'];
                }
                if ($firstVariantName === '' && !empty($variant['ten_bien_the'])) {
                    $firstVariantName = (string)$variant['ten_bien_the'];
                }

                if (is_array($priceFilter)) {
                    $isMatched = true;
                    if ($filterMin !== null && $price < $filterMin) {
                        $isMatched = false;
                    }
                    if ($filterMax !== null && $price > $filterMax) {
                        $isMatched = false;
                    }

                    if ($isMatched) {
                        $matchedVariantCount++;
                        $matchedStock += $stock;
                        if ($matchedMinPrice === null || ($price > 0 && $price < $matchedMinPrice)) {
                            $matchedMinPrice = $price;
                        }
                    }
                }
            }

            if (is_array($priceFilter)) {
                $item['_match_price'] = ($matchedVariantCount > 0);
                $item['matched_variant_count'] = $matchedVariantCount;
                $item['so_luong_kho'] = $matchedStock;
                if ($matchedMinPrice !== null) {
                    $item['gia'] = $matchedMinPrice;
                }
            } else {
                if ($minPrice !== null) {
                    $item['gia'] = $minPrice;
                }
                $item['so_luong_kho'] = $totalStock;
            }
            $item['img_bien_the'] = $firstImage;
            $item['ten_bien_the'] = $firstVariantName;
        }
        unset($item);

        if (!$includeOutOfStock) {
            $items = array_values(array_filter($items, function($item) {
                return (int)($item['so_luong_kho'] ?? 0) > 0;
            }));
        }

        if (trim((string)$keyword) !== '') {
            $items = array_values(array_filter($items, function($item) use ($keyword) {
                return $this->productMatchesKeyword($item, $keyword);
            }));
        }

        if (is_array($priceFilter)) {
            $items = array_values(array_filter($items, function($item) {
                return !empty($item['_match_price']);
            }));
        }

        usort($items, function($a, $b) {
            return (int)($b['so_luong_kho'] ?? 0) - (int)($a['so_luong_kho'] ?? 0);
        });

        return [
            'keyword' => $keyword,
            'price_filter' => $priceFilter,
            'total' => count($items),
            'items' => array_slice(array_map(function($item) {
                unset($item['_match_price']);
                return $item;
            }, $items), 0, 8)
        ];
    }

    private function isPriceMatched($price, $priceFilter) {
        $value = (float)$price;
        $minPrice = (is_array($priceFilter) && array_key_exists('min', $priceFilter) && $priceFilter['min'] !== null)
            ? (float)$priceFilter['min'] : null;
        $maxPrice = (is_array($priceFilter) && array_key_exists('max', $priceFilter) && $priceFilter['max'] !== null)
            ? (float)$priceFilter['max'] : null;

        if ($minPrice !== null && $value < $minPrice) {
            return false;
        }
        if ($maxPrice !== null && $value > $maxPrice) {
            return false;
        }

        return true;
    }

    private function getProductsByVariantPriceFilter($keyword, $includeOutOfStock, $priceFilter) {
        $productMeta = [];
        $productsResult = $this->sanpham_model->SanPham_getAll();
        if ($productsResult) {
            while ($row = mysqli_fetch_assoc($productsResult)) {
                $id = (string)($row['ma_san_pham'] ?? '');
                if ($id === '') {
                    continue;
                }

                $productMeta[$id] = [
                    'ma_san_pham' => $id,
                    'ten_san_pham' => $row['ten_san_pham'] ?? '',
                    'ma_danh_muc' => $row['ma_danh_muc'] ?? '',
                    'ma_thuong_hieu' => $row['ma_thuong_hieu'] ?? '',
                    'ma_nha_cung_cap' => $row['ma_nha_cung_cap'] ?? '',
                    'ten_nha_cung_cap' => $row['ten_nha_cung_cap'] ?? '',
                    'ngay_tao' => $row['ngay_tao'] ?? '',
                    'ten_danh_muc' => $row['ten_danh_muc'] ?? '',
                    'ten_thuong_hieu' => $row['ten_thuong_hieu'] ?? ''
                ];
            }
        }

        $variantResult = $this->bienthe_model ? $this->bienthe_model->BienThe_getAll() : false;
        $map = [];

        if ($variantResult) {
            while ($variant = mysqli_fetch_assoc($variantResult)) {
                $productId = (string)($variant['ma_san_pham'] ?? '');
                if ($productId === '' || !isset($productMeta[$productId])) {
                    continue;
                }

                $price = isset($variant['gia']) ? (float)$variant['gia'] : 0;
                $stock = isset($variant['so_luong_kho']) ? (int)$variant['so_luong_kho'] : 0;

                if (!$this->isPriceMatched($price, $priceFilter)) {
                    continue;
                }

                if (!$includeOutOfStock && $stock <= 0) {
                    continue;
                }

                if (!isset($map[$productId])) {
                    $meta = $productMeta[$productId];
                    $map[$productId] = [
                        'ma_san_pham' => $meta['ma_san_pham'],
                        'ten_san_pham' => $meta['ten_san_pham'],
                        'ma_danh_muc' => $meta['ma_danh_muc'],
                        'ma_thuong_hieu' => $meta['ma_thuong_hieu'],
                        'ma_nha_cung_cap' => $meta['ma_nha_cung_cap'],
                        'ten_nha_cung_cap' => $meta['ten_nha_cung_cap'],
                        'ngay_tao' => $meta['ngay_tao'],
                        'gia' => $price,
                        'so_luong_kho' => 0,
                        'ten_danh_muc' => $meta['ten_danh_muc'],
                        'ten_thuong_hieu' => $meta['ten_thuong_hieu'],
                        'img_bien_the' => $variant['img_bien_the'] ?? '',
                        'ten_bien_the' => $variant['ten_bien_the'] ?? '',
                        'matched_variant_count' => 0
                    ];
                }

                if ($price > 0 && $price < (float)$map[$productId]['gia']) {
                    $map[$productId]['gia'] = $price;
                }

                $map[$productId]['so_luong_kho'] += $stock;
                $map[$productId]['matched_variant_count']++;

                if (($map[$productId]['img_bien_the'] ?? '') === '' && !empty($variant['img_bien_the'])) {
                    $map[$productId]['img_bien_the'] = $variant['img_bien_the'];
                }
                if (($map[$productId]['ten_bien_the'] ?? '') === '' && !empty($variant['ten_bien_the'])) {
                    $map[$productId]['ten_bien_the'] = $variant['ten_bien_the'];
                }
            }
        }

        $items = array_values($map);
        $priceMatchedItems = $items;

        if (trim((string)$keyword) !== '') {
            $items = array_values(array_filter($items, function($item) use ($keyword) {
                return $this->productMatchesKeyword($item, $keyword);
            }));

            // Nếu keyword quá chung chung/lệch ngữ nghĩa làm rỗng kết quả,
            // ưu tiên trả lại danh sách đã khớp điều kiện giá để tránh "đứt".
            if (empty($items)) {
                $items = $priceMatchedItems;
            }
        }

        usort($items, function($a, $b) {
            return (int)($b['so_luong_kho'] ?? 0) - (int)($a['so_luong_kho'] ?? 0);
        });

        return [
            'keyword' => $keyword,
            'price_filter' => $priceFilter,
            'total' => count($items),
            'items' => array_slice($items, 0, 8)
        ];
    }

    private function extractKeywordTokens($keyword) {
        $text = mb_strtolower(trim((string)$keyword), 'UTF-8');
        if ($text === '') {
            return [];
        }

        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $parts = preg_split('/\s+/u', $text);
        if (!is_array($parts)) {
            return [];
        }

        $ignore = [
            'dua', 'ra', 'het', 'tat', 'ca', 'cac', 'nhung', 'san', 'pham', 'liet', 'ke', 'tim', 'kiem',
            'cho', 'toi', 'minh', 'em', 'xin', 'hay', 'vui', 'long', 'giup', 'ho', 'tro', 'show', 'tatca',
            'đưa', 'hết', 'tất', 'cả', 'các', 'những', 'liệt', 'kê', 'tìm', 'kiếm', 'giúp', 'hỗ', 'trợ'
        ];

        $tokens = [];
        foreach ($parts as $token) {
            $token = trim((string)$token);
            if ($token === '' || mb_strlen($token, 'UTF-8') < 2) {
                continue;
            }
            if (in_array($token, $ignore, true)) {
                continue;
            }
            $tokens[] = $token;
        }

        return array_values(array_unique($tokens));
    }

    private function productMatchesKeyword($item, $keyword) {
        $tokens = $this->extractKeywordTokens($keyword);
        if (empty($tokens)) {
            return true;
        }

        $haystack = mb_strtolower(
            trim(
                (string)($item['ten_san_pham'] ?? '') . ' '
                . (string)($item['ten_danh_muc'] ?? '') . ' '
                . (string)($item['ten_thuong_hieu'] ?? '') . ' '
                . (string)($item['ma_san_pham'] ?? '')
            ),
            'UTF-8'
        );

        foreach ($tokens as $token) {
            if (mb_strpos($haystack, $token) === false) {
                return false;
            }
        }

        return true;
    }

    private function getProductVariants($keyword, $includeOutOfStock) {
        $products = $this->getProducts($keyword, true);
        $productItems = isset($products['items']) && is_array($products['items']) ? $products['items'] : [];
        $resultItems = [];

        foreach ($productItems as $product) {
            $productId = trim((string)($product['ma_san_pham'] ?? ''));
            if ($productId === '') {
                continue;
            }

            $variantResult = $this->bienthe_model ? $this->bienthe_model->BienThe_getByProduct($productId) : false;
            $variants = [];
            if ($variantResult) {
                while ($variant = mysqli_fetch_assoc($variantResult)) {
                    $stock = isset($variant['so_luong_kho']) ? (int)$variant['so_luong_kho'] : 0;
                    if (!$includeOutOfStock && $stock <= 0) {
                        continue;
                    }

                    $variants[] = [
                        'ma_bien_the' => $variant['ma_bien_the'] ?? '',
                        'ten_bien_the' => $variant['ten_bien_the'] ?? '',
                        'mau_sac' => $variant['mau_sac'] ?? '',
                        'ram' => $variant['ram'] ?? '',
                        'dung_luong' => $variant['dung_luong'] ?? '',
                        'gia' => isset($variant['gia']) ? (float)$variant['gia'] : 0,
                        'so_luong_kho' => $stock,
                        'img_bien_the' => $variant['img_bien_the'] ?? ''
                    ];
                }
            }

            $resultItems[] = [
                'ma_san_pham' => $productId,
                'ten_san_pham' => $product['ten_san_pham'] ?? '',
                'ten_danh_muc' => $product['ten_danh_muc'] ?? '',
                'ten_thuong_hieu' => $product['ten_thuong_hieu'] ?? '',
                'variant_count' => count($variants),
                'variants' => $variants
            ];
        }

        return [
            'keyword' => $keyword,
            'total_products' => count($resultItems),
            'items' => $resultItems
        ];
    }

    private function extractRevenueRange($message) {
        $text = mb_strtolower((string)$message, 'UTF-8');
        $today = date('Y-m-d');

        $range = [
            'label' => 'hom_nay',
            'start_date' => $today,
            'end_date' => $today
        ];

        if (strpos($text, 'hôm nay') !== false || strpos($text, 'hom nay') !== false || strpos($text, 'ngày nay') !== false || strpos($text, 'ngay nay') !== false) {
            return $range;
        }

        if (strpos($text, 'tháng này') !== false || strpos($text, 'thang nay') !== false) {
            return [
                'label' => 'thang_nay',
                'start_date' => date('Y-m-01'),
                'end_date' => date('Y-m-t')
            ];
        }

        if (preg_match('/(\d{1,2})\s*tháng|(?:thang)\s*(\d{1,2})/u', $text, $m)) {
            $months = (int)($m[1] !== '' ? $m[1] : ($m[2] ?? 0));
            if ($months > 0) {
                return [
                    'label' => $months . '_thang',
                    'start_date' => date('Y-m-d', strtotime('-' . ($months - 1) . ' months', strtotime(date('Y-m-01')))),
                    'end_date' => date('Y-m-t')
                ];
            }
        }

        if (preg_match('/(\d{4}-\d{2}-\d{2})\s*(?:den|đến|to|-)\s*(\d{4}-\d{2}-\d{2})/u', $text, $m2)) {
            return [
                'label' => 'custom_range',
                'start_date' => $m2[1],
                'end_date' => $m2[2]
            ];
        }

        return $range;
    }

    private function getRevenueReport($range) {
        $startDate = $range['start_date'] ?? date('Y-m-d');
        $endDate = $range['end_date'] ?? date('Y-m-d');
        $label = $range['label'] ?? 'hom_nay';

        $result = $this->donhang_model->DonHang_getAll();
        $orders = [];
        $totalRevenue = 0.0;
        $dailyRevenue = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $createdAtRaw = (string)($row['ngay_tao'] ?? '');
                if ($createdAtRaw === '') {
                    continue;
                }

                $orderDate = date('Y-m-d', strtotime($createdAtRaw));
                if ($orderDate < $startDate || $orderDate > $endDate) {
                    continue;
                }

                $status = trim((string)($row['trang_thai_don_hang'] ?? ''));
                if ($status === 'da_huy') {
                    continue;
                }

                $amount = isset($row['tong_tien_hang']) ? (float)$row['tong_tien_hang'] : 0;
                $totalRevenue += $amount;

                if (!isset($dailyRevenue[$orderDate])) {
                    $dailyRevenue[$orderDate] = 0.0;
                }
                $dailyRevenue[$orderDate] += $amount;

                $orders[] = [
                    'ma_don_hang' => $row['ma_don_hang'] ?? '',
                    'ngay_tao' => $createdAtRaw,
                    'full_name' => $row['full_name'] ?? '',
                    'tong_tien_hang' => $amount,
                    'trang_thai_don_hang' => $status,
                    'thanh_toan' => $row['thanh_toan'] ?? ''
                ];
            }
        }

        ksort($dailyRevenue);
        $series = [];
        foreach ($dailyRevenue as $date => $amount) {
            $series[] = [
                'date' => $date,
                'revenue' => $amount
            ];
        }

        return [
            'range' => [
                'label' => $label,
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'total_revenue' => $totalRevenue,
            'order_count' => count($orders),
            'daily_series' => $series,
            'orders' => $orders
        ];
    }

    private function getOrderStatus($orderCode) {
        $result = $this->donhang_model->DonHang_getById($orderCode);
        if (!$result || mysqli_num_rows($result) === 0) {
            return [
                'found' => false,
                'ma_don_hang' => $orderCode
            ];
        }

        $row = mysqli_fetch_assoc($result);

        $paymentInfo = null;
        if ($this->thanhtoan_model) {
            $paymentResult = $this->thanhtoan_model->ThanhToan_getByOrder($orderCode);
            if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
                $paymentRow = mysqli_fetch_assoc($paymentResult);
                $paymentInfo = [
                    'ma_giao_dich' => $paymentRow['ma_giao_dich'] ?? '',
                    'ma_don_hang' => $paymentRow['ma_don_hang'] ?? '',
                    'phuong_thuc' => $paymentRow['phuong_thuc'] ?? '',
                    'so_tien_thanh_toan' => isset($paymentRow['so_tien_thanh_toan']) ? (float)$paymentRow['so_tien_thanh_toan'] : 0,
                    'trang_thai_thanh_toan' => $paymentRow['trang_thai_thanh_toan'] ?? '',
                    'ngay_thanh_toan' => $paymentRow['ngay_thanh_toan'] ?? ''
                ];
            }
        }

        $orderDetails = [];
        if ($this->ctdh_model) {
            $detailResult = $this->ctdh_model->ChiTietDonHang_getByOrderId($orderCode);
            if ($detailResult) {
                while ($detail = mysqli_fetch_assoc($detailResult)) {
                    $orderDetails[] = [
                        'ma_ctdh' => $detail['ma_ctdh'] ?? '',
                        'ma_don_hang' => $detail['ma_don_hang'] ?? '',
                        'ma_bien_the' => $detail['ma_bien_the'] ?? '',
                        'ten_san_pham' => $detail['ten_san_pham'] ?? '',
                        'ten_bien_the' => $detail['ten_bien_the'] ?? '',
                        'mau_sac' => $detail['mau_sac'] ?? '',
                        'ram' => $detail['ram'] ?? '',
                        'dung_luong' => $detail['dung_luong'] ?? '',
                        'img_hinh_anh' => $detail['img_hinh_anh'] ?? '',
                        'so_luong' => isset($detail['so_luong']) ? (int)$detail['so_luong'] : 0,
                        'gia_luc_mua' => isset($detail['gia_luc_mua']) ? (float)$detail['gia_luc_mua'] : 0
                    ];
                }
            }
        }

        return [
            'found' => true,
            'ma_don_hang' => $row['ma_don_hang'] ?? $orderCode,
            'ma_user' => $row['ma_user'] ?? '',
            'ma_dia_chi' => $row['ma_dia_chi'] ?? '',
            'ma_khuyen_mai' => $row['ma_khuyen_mai'] ?? '',
            'full_name' => $row['full_name'] ?? '',
            'ten_nguoi_nhan' => $row['ten_nguoi_nhan'] ?? '',
            'so_dien_thoai' => $row['so_dien_thoai'] ?? '',
            'dia_chi' => $row['dia_chi'] ?? '',
            'trang_thai_don_hang' => $row['trang_thai_don_hang'] ?? 'khong_xac_dinh',
            'tong_tien_hang' => isset($row['tong_tien_hang']) ? (float)$row['tong_tien_hang'] : 0,
            'thanh_toan' => isset($row['thanh_toan']) ? (float)$row['thanh_toan'] : 0,
            'ngay_tao' => $row['ngay_tao'] ?? '',
            'payment_info' => $paymentInfo,
            'order_details' => $orderDetails
        ];
    }

    private function getCurrentCustomerOrders($message = '') {
        $userId = isset($_SESSION['user_id']) ? trim((string)$_SESSION['user_id']) : '';
        $filterStatus = $this->extractOrderStatusFilter($message);
        if ($userId === '') {
            return [
                'requires_login' => true,
                'filter_status' => $filterStatus,
                'filter_status_label' => $filterStatus !== null ? $this->humanizeOrderStatus($filterStatus) : 'Tất cả trạng thái',
                'total' => 0,
                'matched_total' => 0,
                'items' => []
            ];
        }

        $result = $this->donhang_model->DonHang_getAll();
        $items = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (trim((string)($row['ma_user'] ?? '')) !== $userId) {
                    continue;
                }

                $status = trim((string)($row['trang_thai_don_hang'] ?? ''));
                if ($filterStatus !== null && $status !== $filterStatus) {
                    continue;
                }

                $orderCode = (string)($row['ma_don_hang'] ?? '');
                $paymentInfo = null;
                if ($this->thanhtoan_model && $orderCode !== '') {
                    $paymentResult = $this->thanhtoan_model->ThanhToan_getByOrder($orderCode);
                    if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
                        $paymentRow = mysqli_fetch_assoc($paymentResult);
                        $paymentInfo = [
                            'ma_giao_dich' => $paymentRow['ma_giao_dich'] ?? '',
                            'phuong_thuc' => $paymentRow['phuong_thuc'] ?? '',
                            'so_tien_thanh_toan' => isset($paymentRow['so_tien_thanh_toan']) ? (float)$paymentRow['so_tien_thanh_toan'] : 0,
                            'trang_thai_thanh_toan' => $paymentRow['trang_thai_thanh_toan'] ?? '',
                            'ngay_thanh_toan' => $paymentRow['ngay_thanh_toan'] ?? ''
                        ];
                    }
                }

                $details = [];
                if ($this->ctdh_model && $orderCode !== '') {
                    $detailResult = $this->ctdh_model->ChiTietDonHang_getByOrderId($orderCode);
                    if ($detailResult) {
                        while ($detail = mysqli_fetch_assoc($detailResult)) {
                            $details[] = [
                                'ma_ctdh' => $detail['ma_ctdh'] ?? '',
                                'ma_bien_the' => $detail['ma_bien_the'] ?? '',
                                'ten_san_pham' => $detail['ten_san_pham'] ?? '',
                                'ten_bien_the' => $detail['ten_bien_the'] ?? '',
                                'so_luong' => isset($detail['so_luong']) ? (int)$detail['so_luong'] : 0,
                                'gia_luc_mua' => isset($detail['gia_luc_mua']) ? (float)$detail['gia_luc_mua'] : 0
                            ];
                        }
                    }
                }

                $items[] = [
                    'ma_don_hang' => $orderCode,
                    'ma_user' => $row['ma_user'] ?? '',
                    'ma_dia_chi' => $row['ma_dia_chi'] ?? '',
                    'ma_khuyen_mai' => $row['ma_khuyen_mai'] ?? '',
                    'ten_nguoi_nhan' => $row['ten_nguoi_nhan'] ?? '',
                    'so_dien_thoai' => $row['so_dien_thoai'] ?? '',
                    'dia_chi' => $row['dia_chi'] ?? '',
                    'tong_tien_hang' => isset($row['tong_tien_hang']) ? (float)$row['tong_tien_hang'] : 0,
                    'thanh_toan' => isset($row['thanh_toan']) ? (float)$row['thanh_toan'] : 0,
                    'trang_thai_don_hang' => $status,
                    'ngay_tao' => $row['ngay_tao'] ?? '',
                    'payment_info' => $paymentInfo,
                    'order_details' => $details
                ];
            }
        }

        usort($items, function($a, $b) {
            return strcmp((string)($b['ngay_tao'] ?? ''), (string)($a['ngay_tao'] ?? ''));
        });

        return [
            'requires_login' => false,
            'ma_user' => $userId,
            'filter_status' => $filterStatus,
            'filter_status_label' => $filterStatus !== null ? $this->humanizeOrderStatus($filterStatus) : 'Tất cả trạng thái',
            'total' => count($items),
            'matched_total' => count($items),
            'items' => array_slice($items, 0, 20)
        ];
    }

    private function getActivePromotions() {
        $result = $this->khuyenmai_model->KhuyenMai_find('', '');
        $items = [];
        $today = date('Y-m-d');

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $status = trim((string)($row['trang_thai_khuyen_mai'] ?? ''));
                $endDateRaw = trim((string)($row['ngay_ket_thuc'] ?? ''));
                $endDate = ($endDateRaw !== '') ? date('Y-m-d', strtotime($endDateRaw)) : '';
                $isActive = ($status === 'con') || ($endDate !== '' && $endDate >= $today);

                if ($isActive) {
                    $items[] = [
                        'ma_khuyen_mai' => $row['ma_khuyen_mai'] ?? '',
                        'ten_khuyen_mai' => $row['ten_khuyen_mai'] ?? '',
                        'tien_khuyen_mai' => isset($row['tien_khuyen_mai']) ? (float)$row['tien_khuyen_mai'] : 0,
                        'ngay_bat_dau' => $row['ngay_bat_dau'] ?? '',
                        'ngay_ket_thuc' => $row['ngay_ket_thuc'] ?? ''
                    ];
                }
            }
        }

        return [
            'total' => count($items),
            'items' => array_slice($items, 0, 10)
        ];
    }

    private function getLowStockProducts() {
        $products = $this->getProducts('', true);
        $items = array_values(array_filter($products['items'], function($item) {
            $stock = (int)($item['so_luong_kho'] ?? 0);
            return $stock >= 0 && $stock <= 5;
        }));

        usort($items, function($a, $b) {
            return (int)($a['so_luong_kho'] ?? 0) - (int)($b['so_luong_kho'] ?? 0);
        });

        return [
            'threshold' => 5,
            'total' => count($items),
            'items' => $items
        ];
    }

    private function getPendingOrders() {
        $result = $this->donhang_model->DonHang_getAll();
        $items = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (($row['trang_thai_don_hang'] ?? '') === 'cho_duyet') {
                    $items[] = [
                        'ma_don_hang' => $row['ma_don_hang'] ?? '',
                        'full_name' => $row['full_name'] ?? '',
                        'tong_tien_hang' => isset($row['tong_tien_hang']) ? (float)$row['tong_tien_hang'] : 0,
                        'ngay_tao' => $row['ngay_tao'] ?? ''
                    ];
                }
            }
        }

        return [
            'total' => count($items),
            'items' => array_slice($items, 0, 20)
        ];
    }

    private function extractOrderStatusFilter($message) {
        $text = mb_strtolower((string)$message, 'UTF-8');

        if (strpos($text, 'đã hủy') !== false || strpos($text, 'da huy') !== false || strpos($text, 'hủy') !== false || strpos($text, 'huy') !== false) {
            return 'da_huy';
        }
        if (strpos($text, 'đang giao') !== false || strpos($text, 'dang giao') !== false) {
            return 'dang_giao';
        }
        if (strpos($text, 'chờ duyệt') !== false || strpos($text, 'cho duyet') !== false || strpos($text, 'cho_duyet') !== false) {
            return 'cho_duyet';
        }
        if (strpos($text, 'hoàn thành') !== false || strpos($text, 'hoan thanh') !== false) {
            return 'hoan_thanh';
        }

        return null;
    }

    private function humanizeOrderStatus($status) {
        $map = [
            'cho_duyet' => 'Chờ duyệt',
            'dang_giao' => 'Đang giao',
            'hoan_thanh' => 'Hoàn thành',
            'da_huy' => 'Đã hủy'
        ];

        return $map[$status] ?? $status;
    }

    private function getAdminOrderStats($message) {
        $filterStatus = $this->extractOrderStatusFilter($message);
        $result = $this->donhang_model->DonHang_getAll();

        $counts = [
            'cho_duyet' => 0,
            'dang_giao' => 0,
            'hoan_thanh' => 0,
            'da_huy' => 0
        ];
        $items = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $status = trim((string)($row['trang_thai_don_hang'] ?? ''));
                if (isset($counts[$status])) {
                    $counts[$status]++;
                }

                if ($filterStatus !== null && $status !== $filterStatus) {
                    continue;
                }

                $orderCode = $row['ma_don_hang'] ?? '';
                $paymentInfo = null;
                if ($this->thanhtoan_model && $orderCode !== '') {
                    $paymentResult = $this->thanhtoan_model->ThanhToan_getByOrder($orderCode);
                    if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
                        $paymentInfo = mysqli_fetch_assoc($paymentResult);
                    }
                }

                $orderDetails = [];
                if ($this->ctdh_model && $orderCode !== '') {
                    $detailResult = $this->ctdh_model->ChiTietDonHang_getByOrderId($orderCode);
                    if ($detailResult) {
                        while ($detail = mysqli_fetch_assoc($detailResult)) {
                            $orderDetails[] = [
                                'ma_ctdh' => $detail['ma_ctdh'] ?? '',
                                'ma_bien_the' => $detail['ma_bien_the'] ?? '',
                                'ten_san_pham' => $detail['ten_san_pham'] ?? '',
                                'ten_bien_the' => $detail['ten_bien_the'] ?? '',
                                'mau_sac' => $detail['mau_sac'] ?? '',
                                'ram' => $detail['ram'] ?? '',
                                'dung_luong' => $detail['dung_luong'] ?? '',
                                'img_hinh_anh' => $detail['img_hinh_anh'] ?? '',
                                'so_luong' => isset($detail['so_luong']) ? (int)$detail['so_luong'] : 0,
                                'gia_luc_mua' => isset($detail['gia_luc_mua']) ? (float)$detail['gia_luc_mua'] : 0
                            ];
                        }
                    }
                }

                $items[] = [
                    'ma_don_hang' => $row['ma_don_hang'] ?? '',
                    'ma_user' => $row['ma_user'] ?? '',
                    'ma_dia_chi' => $row['ma_dia_chi'] ?? '',
                    'ma_khuyen_mai' => $row['ma_khuyen_mai'] ?? '',
                    'full_name' => $row['full_name'] ?? '',
                    'ten_nguoi_nhan' => $row['ten_nguoi_nhan'] ?? '',
                    'so_dien_thoai' => $row['so_dien_thoai'] ?? '',
                    'dia_chi' => $row['dia_chi'] ?? '',
                    'tong_tien_hang' => isset($row['tong_tien_hang']) ? (float)$row['tong_tien_hang'] : 0,
                    'thanh_toan' => isset($row['thanh_toan']) ? (float)$row['thanh_toan'] : 0,
                    'trang_thai_don_hang' => $status,
                    'ngay_tao' => $row['ngay_tao'] ?? '',
                    'ten_khuyen_mai' => $row['ten_khuyen_mai'] ?? '',
                    'tien_khuyen_mai' => isset($row['tien_khuyen_mai']) ? (float)$row['tien_khuyen_mai'] : 0,
                    'payment_info' => $paymentInfo ? [
                        'ma_giao_dich' => $paymentInfo['ma_giao_dich'] ?? '',
                        'phuong_thuc' => $paymentInfo['phuong_thuc'] ?? '',
                        'so_tien_thanh_toan' => isset($paymentInfo['so_tien_thanh_toan']) ? (float)$paymentInfo['so_tien_thanh_toan'] : 0,
                        'trang_thai_thanh_toan' => $paymentInfo['trang_thai_thanh_toan'] ?? '',
                        'ngay_thanh_toan' => $paymentInfo['ngay_thanh_toan'] ?? ''
                    ] : null,
                    'order_details' => $orderDetails
                ];
            }
        }

        return [
            'filter_status' => $filterStatus,
            'filter_status_label' => $filterStatus !== null ? $this->humanizeOrderStatus($filterStatus) : 'Tất cả trạng thái',
            'counts' => $counts,
            'total_orders' => array_sum($counts),
            'matched_total' => ($filterStatus !== null && isset($counts[$filterStatus])) ? $counts[$filterStatus] : array_sum($counts),
            'items' => array_slice($items, 0, 20)
        ];
    }

    private function getUsersPreview() {
        $result = $this->users_model->Users_find('', '');
        $users = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = [
                    'ma_user' => $row['ma_user'] ?? '',
                    'ten_user' => $row['ten_user'] ?? '',
                    'password' => $row['password'] ?? '',
                    'full_name' => $row['full_name'] ?? '',
                    'avatar' => $row['avatar'] ?? '',
                    'email' => $row['email'] ?? '',
                    'so_dien_thoai' => $row['so_dien_thoai'] ?? '',
                    'phan_quyen' => $row['phan_quyen'] ?? '',
                    'ngay_tao' => $row['ngay_tao'] ?? ''
                ];
            }
        }

        return [
            'total' => count($users),
            'items' => array_slice($users, 0, 20),
            'hint' => 'Xem đầy đủ qua GET /Api/Users'
        ];
    }

    private function getAdminApiHelp() {
        return [
            'endpoints' => [
                ['method' => 'GET', 'path' => '/Api/Products', 'description' => 'Danh sách sản phẩm'],
                ['method' => 'GET', 'path' => '/Api/Donhang', 'description' => 'Danh sách đơn hàng'],
                ['method' => 'GET', 'path' => '/Api/Users', 'description' => 'Danh sách người dùng'],
                ['method' => 'GET', 'path' => '/Api/Khuyenmai', 'description' => 'Danh sách khuyến mãi'],
                ['method' => 'POST', 'path' => '/Api/Techbot/ask', 'description' => 'Hỏi đáp chatbot TechZone']
            ]
        ];
    }

    private function getAdminOverview() {
        $products = $this->getProducts('', true);
        $pending = $this->getPendingOrders();
        $users = $this->getUsersPreview();

        return [
            'total_products' => (int)($products['total'] ?? 0),
            'pending_orders' => (int)($pending['total'] ?? 0),
            'total_users' => (int)($users['total'] ?? 0)
        ];
    }

    private function rowsFromResult($result) {
        $rows = [];
        if (!$result) {
            return $rows;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    private function getSchemaCatalog() {
        return [
            'bien_the' => ['ma_bien_the', 'ma_san_pham', 'ten_bien_the', 'img_bien_the', 'mau_sac', 'ram', 'dung_luong', 'gia', 'so_luong_kho'],
            'chi_tiet_don_hang' => ['ma_ctdh', 'ma_don_hang', 'ma_bien_the', 'so_luong', 'gia_luc_mua'],
            'chi_tiet_gio_hang' => ['ma_gio_hang', 'ma_bien_the', 'so_luong'],
            'danh_gia' => ['ma_danh_gia', 'ma_user', 'ma_san_pham', 'so_sao', 'noi_dung', 'phan_hoi', 'ngay_danh_gia'],
            'danh_muc' => ['ma_danh_muc', 'ten_danh_muc', 'ngay_tao'],
            'dia_chi_giao_hang' => ['ma_dia_chi', 'ma_user', 'ho_ten', 'so_dien_thoai', 'dia_chi', 'mac_dinh'],
            'don_hang' => ['ma_don_hang', 'ma_user', 'ma_dia_chi', 'ma_khuyen_mai', 'tong_tien_hang', 'thanh_toan', 'trang_thai_don_hang', 'ngay_tao'],
            'gio_hang' => ['ma_gio_hang', 'ma_user', 'trang_thai', 'ngay_tao'],
            'khuyen_mai' => ['ma_khuyen_mai', 'ten_khuyen_mai', 'tien_khuyen_mai', 'ngay_bat_dau', 'ngay_ket_thuc', 'trang_thai_khuyen_mai'],
            'nha_cung_cap' => ['ma_nha_cung_cap', 'ten_nha_cung_cap', 'dia_chi', 'dien_thoai'],
            'san_pham' => ['ma_san_pham', 'ten_san_pham', 'ma_danh_muc', 'ma_thuong_hieu', 'ma_nha_cung_cap', 'ngay_tao'],
            'thanh_toan' => ['ma_giao_dich', 'ma_don_hang', 'phuong_thuc', 'so_tien_thanh_toan', 'trang_thai_thanh_toan', 'ngay_thanh_toan'],
            'thuong_hieu' => ['ma_thuong_hieu', 'ten_thuong_hieu', 'ngay_tao'],
            'users' => ['ma_user', 'ten_user', 'password', 'full_name', 'avatar', 'email', 'phan_quyen', 'so_dien_thoai', 'ngay_tao']
        ];
    }

    private function getAdminFullShopData() {
        $products = $this->rowsFromResult($this->sanpham_model->SanPham_getAll());
        $variants = $this->rowsFromResult($this->bienthe_model->BienThe_getAll());
        $orders = $this->rowsFromResult($this->donhang_model->DonHang_getAll());
        $orderDetails = $this->rowsFromResult($this->ctdh_model->ChiTietDonHang_getAll());
        $payments = $this->rowsFromResult($this->thanhtoan_model->ThanhToan_getAll());
        $carts = $this->rowsFromResult($this->giohang_model->GioHang_getAll());
        $cartDetails = $this->rowsFromResult($this->ctgh_model->ChiTietGioHang_getAll());
        $reviews = $this->rowsFromResult($this->danhgia_model->DanhGia_getAll());
        $addresses = $this->rowsFromResult($this->diachi_model->DiaChiGiaoHang_getAll());
        $categories = $this->rowsFromResult($this->danhmuc_model->DanhMuc_getAll());
        $brands = $this->rowsFromResult($this->thuonghieu_model->ThuongHieu_getAll());
        $suppliers = $this->rowsFromResult($this->nhacungcap_model->NhaCungCap_getAll());
        $users = $this->rowsFromResult($this->users_model->Users_find('', ''));
        $promotions = $this->rowsFromResult($this->khuyenmai_model->KhuyenMai_find('', ''));

        $pendingOrders = [];
        foreach ($orders as $order) {
            if (($order['trang_thai_don_hang'] ?? '') === 'cho_duyet') {
                $pendingOrders[] = $order;
            }
        }

        $lowStockProducts = [];
        foreach ($products as $product) {
            $stock = isset($product['so_luong_kho']) ? (int)$product['so_luong_kho'] : null;
            if ($stock !== null && $stock >= 0 && $stock <= 5) {
                $lowStockProducts[] = $product;
            }
        }

        return [
            'meta' => [
                'generated_at' => date('Y-m-d H:i:s'),
                'scope' => 'admin_full_access',
                'source_endpoints' => ['/Api/Products', '/Api/Bienthe', '/Api/Donhang', '/Api/Users', '/Api/Khuyenmai', '/Api/Thanhtoan', '/Api/Giohang', '/Api/Danhgia', '/Api/Danhmuc', '/Api/Thuonghieu', '/Api/Nhacungcap'],
                'schema_catalog' => $this->getSchemaCatalog()
            ],
            'totals' => [
                'products' => count($products),
                'variants' => count($variants),
                'orders' => count($orders),
                'order_details' => count($orderDetails),
                'payments' => count($payments),
                'carts' => count($carts),
                'cart_details' => count($cartDetails),
                'reviews' => count($reviews),
                'addresses' => count($addresses),
                'categories' => count($categories),
                'brands' => count($brands),
                'suppliers' => count($suppliers),
                'users' => count($users),
                'promotions' => count($promotions),
                'pending_orders' => count($pendingOrders),
                'low_stock_products' => count($lowStockProducts)
            ],
            'products' => $products,
            'variants' => $variants,
            'orders' => $orders,
            'order_details' => $orderDetails,
            'payments' => $payments,
            'carts' => $carts,
            'cart_details' => $cartDetails,
            'reviews' => $reviews,
            'addresses' => $addresses,
            'categories' => $categories,
            'brands' => $brands,
            'suppliers' => $suppliers,
            'users' => $users,
            'promotions' => $promotions,
            'reports' => [
                'pending_orders' => $pendingOrders,
                'low_stock_products' => $lowStockProducts
            ]
        ];
    }

    private function buildNeedOrderCodeReply() {
        return "Dạ, TechZone xin hỗ trợ kiểm tra đơn hàng cho Quý khách.\n"
            . "- 📦 Quý khách vui lòng cung cấp đúng mã đơn hàng (ví dụ: DH00123).\n"
            . "- ✅ Em sẽ phản hồi ngay trạng thái và tổng tiền của đơn.\n"
            . "Cảm ơn Quý khách, TechZone luôn sẵn sàng hỗ trợ ạ.";
    }

    private function buildResponse($payload) {
        $intent = (string)($payload['intent'] ?? 'unknown');
        $role = (string)($payload['role'] ?? 'customer');

        // Các intent khách hàng nên trả lời local để tránh LLM trả sai dữ liệu thực tế.
        if (
            $role !== 'admin'
            && in_array($intent, ['product_lookup', 'product_variant_lookup', 'promotion_lookup', 'order_status', 'customer_my_orders', 'restricted_info', 'greeting', 'unknown'], true)
        ) {
            return $this->normalizeReplyForChat($this->buildResponseLocal($payload));
        }

        $apiKey = $this->getGroqApiKey();
        if ($apiKey !== '') {
            $groqReply = $this->buildResponseByGroq($apiKey, $payload);
            if ($groqReply !== '') {
                return $this->normalizeReplyForChat($groqReply);
            }
        }

        return $this->normalizeReplyForChat($this->buildResponseLocal($payload));
    }

    private function buildResponseByGroq($apiKey, $payload) {
        $systemPrompt = "Bạn là TechZone Assistant. Bắt buộc trả lời tiếng Việt lịch sự, chuyên nghiệp theo cấu trúc: chào thương hiệu, nội dung chính, lời mời bước tiếp theo."
            . " Dùng icon phù hợp như 📱💰📦✅."
            . " Định dạng bắt buộc: văn bản thuần dễ đọc, không dùng markdown (không **, không ###, không bảng)."
            . " Mỗi ý chính là 1 dòng bắt đầu bằng '- '."
            . " Nếu vai trò customer: chỉ hiển thị sản phẩm (tên, giá, danh mục, thương hiệu, tồn kho), trạng thái+tổng tiền đơn hàng theo mã đúng, khuyến mãi đang hoạt động."
            . " Nếu vai trò admin: được phép truy cập và trình bày đầy đủ dữ liệu quản trị theo payload được cung cấp."
            . " Tuyệt đối không lộ doanh thu, danh sách toàn bộ users, thông tin nhà cung cấp, lỗi hệ thống chi tiết."
            . " Nếu không có dữ liệu: xin lỗi lịch sự và đề xuất kiểm tra lại.";

        $userPrompt = "Dữ liệu đầu vào JSON:\n" . json_encode($payload, JSON_UNESCAPED_UNICODE)
            . "\nHãy viết câu trả lời hoàn chỉnh để gửi cho người dùng cuối.";

        $response = $this->callGroq($apiKey, [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt]
        ]);

        return trim($response);
    }

    private function normalizeReplyForChat($reply) {
        $text = trim((string)$reply);
        if ($text === '') {
            return $text;
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Loại markdown gây rối khi hiển thị trong bubble chat.
        $text = preg_replace('/\*\*(.*?)\*\*/u', '$1', $text);
        $text = preg_replace('/^\s{0,3}#{1,6}\s*/mu', '', $text);
        $text = preg_replace('/\[(.*?)\]\((.*?)\)/u', '$1', $text);
        $text = preg_replace('/`([^`]*)`/u', '$1', $text);

        // Chuẩn hóa bullet và khoảng trắng.
        $text = preg_replace('/^[ \t]*[-•]\s*/mu', '- ', $text);
        $text = preg_replace('/^[ \t]*\d+[\.)]\s*/mu', '- ', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        $lines = explode("\n", $text);
        $normalizedLines = [];
        foreach ($lines as $line) {
            $clean = trim($line);
            if ($clean === '') {
                $normalizedLines[] = '';
                continue;
            }

            // Nếu là dòng thông tin key:value dài, giữ nguyên để dễ đọc.
            $clean = preg_replace('/\s{2,}/u', ' ', $clean);
            $normalizedLines[] = $clean;
        }

        $text = trim(implode("\n", $normalizedLines));
        return $text;
    }

    private function buildResponseLocal($payload) {
        $intent = $payload['intent'] ?? 'unknown';
        $role = $payload['role'] ?? 'customer';
        $data = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : [];

        if ($intent === 'greeting') {
            return "Dạ, TechZone xin kính chào Quý khách.\n"
                . "- 📱 Em có thể hỗ trợ tư vấn sản phẩm, kiểm tra mã giảm giá và trạng thái đơn hàng.\n"
                . "- ✅ Quý khách chỉ cần nhắn tên sản phẩm hoặc mã đơn hàng để em hỗ trợ ngay ạ.\n"
                . "Quý khách muốn tham khảo dòng máy nào trước ạ?";
        }

        if ($intent === 'order_status') {
            if (empty($data['found'])) {
                return "Dạ, TechZone đã kiểm tra nhưng chưa tìm thấy đơn hàng tương ứng.\n"
                    . "- 📦 Quý khách vui lòng kiểm tra lại mã đơn hàng (ví dụ: DH00123).\n"
                    . "- ✅ Em sẽ hỗ trợ tra cứu lại ngay khi Quý khách gửi đúng mã ạ.\n"
                    . "Cảm ơn Quý khách đã liên hệ TechZone.";
            }

            return "Dạ, TechZone đã tra cứu đơn hàng cho Quý khách:\n"
                . "- 📦 Mã đơn hàng: " . ($data['ma_don_hang'] ?? '') . "\n"
                . "- ✅ Trạng thái: " . ($data['trang_thai_don_hang'] ?? '') . "\n"
                . "- 💰 Tổng tiền: " . $this->formatCurrency($data['tong_tien_hang'] ?? 0) . "\n"
                . "- 👤 Người nhận: " . ($data['ten_nguoi_nhan'] ?? $data['full_name'] ?? '') . " | SĐT: " . ($data['so_dien_thoai'] ?? '') . "\n"
                . "- 📍 Địa chỉ: " . ($data['dia_chi'] ?? '') . "\n"
                . "Quý khách có muốn em hỗ trợ thêm về phương thức thanh toán hoặc giao hàng không ạ?";
        }

        if ($intent === 'customer_my_orders') {
            if (!empty($data['requires_login'])) {
                return "Dạ, để xem đơn hàng của tài khoản mình, Quý khách vui lòng đăng nhập trước ạ.\n"
                    . "- ✅ Sau khi đăng nhập, em sẽ hiển thị toàn bộ đơn hàng đã mua ngay trong khung chat.\n"
                    . "TechZone luôn sẵn sàng hỗ trợ Quý khách.";
            }

            $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
            if (empty($items)) {
                return "Dạ, hiện tài khoản của Quý khách chưa có đơn hàng nào.\n"
                    . "- 📱 Quý khách có thể tham khảo sản phẩm và đặt mua ngay trên TechZone.\n"
                    . "Em có thể gợi ý một số sản phẩm phù hợp để mình bắt đầu không ạ?";
            }

            $lines = [
                "Dạ, TechZone đã lấy danh sách đơn hàng của tài khoản hiện tại:",
                "- 👤 Mã tài khoản: " . ($data['ma_user'] ?? ''),
                "- 📦 Trạng thái đang xem: " . ($data['filter_status_label'] ?? 'Tất cả trạng thái'),
                "- ✅ Số đơn khớp: " . (int)($data['matched_total'] ?? count($items)),
                "- 📄 Đang hiển thị: " . count($items) . " đơn gần nhất"
            ];

            foreach ($items as $order) {
                $lines[] = "- " . ($order['ma_don_hang'] ?? '')
                    . " | " . $this->humanizeOrderStatus($order['trang_thai_don_hang'] ?? '')
                    . " | " . $this->formatCurrency($order['tong_tien_hang'] ?? 0)
                    . " | " . ($order['ngay_tao'] ?? '');
            }

            $lines[] = "Quý khách muốn em mở chi tiết đơn nào (ví dụ: DH27) để xem sản phẩm và thanh toán không ạ?";
            return implode("\n", $lines);
        }

        if ($intent === 'promotion_lookup') {
            $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
            if (empty($items)) {
                return "Dạ, hiện tại TechZone chưa có mã giảm giá đang hoạt động.\n"
                    . "- ✅ Quý khách có thể theo dõi thêm trong khung giờ ưu đãi sắp tới.\n"
                    . "TechZone cảm ơn Quý khách và luôn sẵn sàng hỗ trợ ạ.";
            }

            $lines = ["Dạ, TechZone đang có các ưu đãi nổi bật dành cho Quý khách:"];
            foreach (array_slice($items, 0, 5) as $promo) {
                $lines[] = "- 💰 " . ($promo['ma_khuyen_mai'] ?? '') . " - " . ($promo['ten_khuyen_mai'] ?? '')
                    . " (" . $this->formatCurrency($promo['tien_khuyen_mai'] ?? 0) . ", đến " . ($promo['ngay_ket_thuc'] ?? '') . ")";
            }
            $lines[] = "Quý khách có muốn em gợi ý sản phẩm phù hợp để áp mã ngay không ạ?";

            return implode("\n", $lines);
        }

        if ($intent === 'product_variant_lookup') {
            $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
            if (empty($items)) {
                return "Dạ, TechZone chưa tìm thấy sản phẩm phù hợp để kiểm tra biến thể.\n"
                    . "- 📱 Quý khách vui lòng nhập rõ tên sản phẩm (ví dụ: iPhone 17 Pro Max).\n"
                    . "- ✅ Em sẽ liệt kê chi tiết từng biến thể ngay khi có kết quả.\n"
                    . "Quý khách muốn em kiểm tra lại sản phẩm nào ạ?";
            }

            $product = $items[0];
            $variants = isset($product['variants']) && is_array($product['variants']) ? $product['variants'] : [];
            $lines = [
                "Dạ, TechZone đã kiểm tra biến thể cho sản phẩm " . ($product['ten_san_pham'] ?? 'N/A') . ":",
                "- ✅ Tổng số biến thể: " . (int)($product['variant_count'] ?? count($variants))
            ];

            if (empty($variants)) {
                $lines[] = "- 📦 Hiện chưa có biến thể còn hàng để hiển thị.";
            } else {
                foreach ($variants as $index => $variant) {
                    $line = "- " . ($index + 1) . ". "
                        . ($variant['ten_bien_the'] ?? 'Biến thể')
                        . " | Màu: " . (($variant['mau_sac'] ?? '') !== '' ? $variant['mau_sac'] : 'N/A')
                        . " | RAM: " . (($variant['ram'] ?? '') !== '' ? $variant['ram'] : 'N/A')
                        . " | Dung lượng: " . (($variant['dung_luong'] ?? '') !== '' ? $variant['dung_luong'] : 'N/A')
                        . " | Giá: " . $this->formatCurrency($variant['gia'] ?? 0)
                        . " | Kho: " . (int)($variant['so_luong_kho'] ?? 0);
                    $lines[] = $line;
                }
            }

            $lines[] = "Quản trị viên có muốn em lọc thêm theo màu sắc hoặc dung lượng không ạ?";
            return implode("\n", $lines);
        }

        if ($intent === 'restricted_info') {
            return "Dạ, TechZone xin phép chưa thể cung cấp thông tin này cho tài khoản hiện tại của Quý khách.\n"
                . "- ✅ Theo chính sách bảo mật, các dữ liệu như doanh thu/doanh số, danh sách người dùng hoặc thông tin nhà cung cấp chỉ dành cho quản trị viên đã xác thực.\n"
                . "- 📱 Em có thể hỗ trợ ngay các nội dung công khai như sản phẩm, khuyến mãi và trạng thái đơn hàng theo mã đơn.\n"
                . "Quý khách muốn em tư vấn mẫu điện thoại nào tiếp theo ạ?";
        }

        if ($role === 'admin' && $intent === 'admin_revenue_report') {
            $range = isset($data['range']) && is_array($data['range']) ? $data['range'] : [];
            $series = isset($data['daily_series']) && is_array($data['daily_series']) ? $data['daily_series'] : [];

            $lines = [
                "Dạ, TechZone đã tổng hợp doanh thu cho quản trị viên:",
                "- 💰 Tổng doanh thu: " . $this->formatCurrency($data['total_revenue'] ?? 0),
                "- 📦 Số đơn hợp lệ: " . (int)($data['order_count'] ?? 0),
                "- ✅ Khoảng thời gian: " . ($range['start_date'] ?? '') . " đến " . ($range['end_date'] ?? '')
            ];

            if (!empty($series)) {
                $lines[] = "- 📊 Doanh thu theo ngày:";
                foreach (array_slice($series, 0, 10) as $point) {
                    $lines[] = "  • " . ($point['date'] ?? '') . ": " . $this->formatCurrency($point['revenue'] ?? 0);
                }
            }

            $lines[] = "Quản trị viên có muốn em xuất danh sách chi tiết đơn hàng trong kỳ này không ạ?";
            return implode("\n", $lines);
        }

        if ($role === 'admin' && $intent === 'admin_full_access') {
            $totals = isset($data['totals']) && is_array($data['totals']) ? $data['totals'] : [];
            return "Dạ, TechZone đã cấp dữ liệu toàn bộ cửa hàng cho tài khoản quản lý.\n"
                . "- ✅ Sản phẩm: " . (int)($totals['products'] ?? 0) . "\n"
                . "- 🎯 Biến thể: " . (int)($totals['variants'] ?? 0) . "\n"
                . "- 📦 Đơn hàng: " . (int)($totals['orders'] ?? 0) . "\n"
                . "- 🧾 Chi tiết đơn: " . (int)($totals['order_details'] ?? 0) . " | Thanh toán: " . (int)($totals['payments'] ?? 0) . "\n"
                . "- 🛒 Giỏ hàng: " . (int)($totals['carts'] ?? 0) . " | Chi tiết giỏ: " . (int)($totals['cart_details'] ?? 0) . "\n"
                . "- ⭐ Đánh giá: " . (int)($totals['reviews'] ?? 0) . " | Địa chỉ giao hàng: " . (int)($totals['addresses'] ?? 0) . "\n"
                . "- 🗂 Danh mục: " . (int)($totals['categories'] ?? 0) . " | Thương hiệu: " . (int)($totals['brands'] ?? 0) . " | Nhà cung cấp: " . (int)($totals['suppliers'] ?? 0) . "\n"
                . "- 👤 Người dùng: " . (int)($totals['users'] ?? 0) . "\n"
                . "- 💰 Khuyến mãi: " . (int)($totals['promotions'] ?? 0) . "\n"
                . "- ⚠️ Đơn chờ duyệt: " . (int)($totals['pending_orders'] ?? 0) . " | Sản phẩm tồn kho thấp: " . (int)($totals['low_stock_products'] ?? 0) . "\n"
                . "Quản lý có muốn em lọc sâu theo đúng bảng và trường trong schema catalog không ạ?";
        }

        if ($role === 'admin' && $intent === 'admin_order_stats') {
            $counts = isset($data['counts']) && is_array($data['counts']) ? $data['counts'] : [];
            $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];

            $lines = [
                "Dạ, TechZone đã thống kê đơn hàng theo yêu cầu quản trị viên:",
                "- 📦 Trạng thái đang xem: " . ($data['filter_status_label'] ?? 'Tất cả trạng thái'),
                "- ✅ Số đơn khớp: " . (int)($data['matched_total'] ?? 0),
                "- Tổng quan: Chờ duyệt " . (int)($counts['cho_duyet'] ?? 0)
                    . " | Đang giao " . (int)($counts['dang_giao'] ?? 0)
                    . " | Hoàn thành " . (int)($counts['hoan_thanh'] ?? 0)
                    . " | Đã hủy " . (int)($counts['da_huy'] ?? 0)
            ];

            if (!empty($items)) {
                $lines[] = "- Mẫu đơn hàng:";
                foreach (array_slice($items, 0, 5) as $order) {
                    $lines[] = "- " . ($order['ma_don_hang'] ?? '')
                        . " | " . ($order['full_name'] ?? $order['ten_nguoi_nhan'] ?? 'Khách hàng')
                        . " | " . $this->humanizeOrderStatus($order['trang_thai_don_hang'] ?? '')
                        . " | " . $this->formatCurrency($order['tong_tien_hang'] ?? 0);
                }
            }

            $lines[] = "Quản trị viên có muốn em xuất chi tiết 20 đơn đầu tiên của trạng thái này không ạ?";
            return implode("\n", $lines);
        }

        if ($role === 'admin' && $intent === 'admin_pending_orders') {
            $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
            $lines = ["Dạ, TechZone xin gửi nhanh danh sách đơn hàng chờ duyệt:"];
            if (empty($items)) {
                $lines[] = "- ✅ Hiện không có đơn hàng ở trạng thái cho_duyet.";
            } else {
                foreach (array_slice($items, 0, 8) as $order) {
                    $lines[] = "- 📦 " . ($order['ma_don_hang'] ?? '') . " | " . ($order['full_name'] ?? 'Khách hàng')
                        . " | " . $this->formatCurrency($order['tong_tien_hang'] ?? 0);
                }
            }
            $lines[] = "Quản trị viên có muốn em lọc thêm theo ngày tạo đơn không ạ?";
            return implode("\n", $lines);
        }

        if ($role === 'admin' && $intent === 'admin_inventory_alert') {
            $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
            $lines = ["Dạ, TechZone xin gửi danh sách sản phẩm sắp hết hàng:"];
            if (empty($items)) {
                $lines[] = "- ✅ Không có sản phẩm dưới ngưỡng tồn kho cảnh báo.";
            } else {
                foreach (array_slice($items, 0, 8) as $product) {
                    $lines[] = "- 📱 " . ($product['ten_san_pham'] ?? '') . " (" . ($product['ma_san_pham'] ?? '') . ")"
                        . " | Kho: " . (int)($product['so_luong_kho'] ?? 0)
                        . " | Giá: " . $this->formatCurrency($product['gia'] ?? 0);
                }
            }
            $lines[] = "Quản trị viên có muốn em xuất nhanh danh sách này sang endpoint quản trị không ạ?";
            return implode("\n", $lines);
        }

        if ($role === 'admin' && $intent === 'admin_users_list') {
            $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
            $lines = ["Dạ, TechZone xin gửi dữ liệu người dùng (bản xem nhanh):"];
            if (empty($items)) {
                $lines[] = "- ✅ Chưa có dữ liệu người dùng.";
            } else {
                foreach (array_slice($items, 0, 8) as $user) {
                    $lines[] = "- 👤 " . ($user['ma_user'] ?? '') . " | " . ($user['full_name'] ?? $user['ten_user'] ?? '')
                        . " | " . ($user['email'] ?? '') . " | " . ($user['phan_quyen'] ?? '');
                }
            }
            $lines[] = "Để lấy toàn bộ dữ liệu, Quản trị viên có thể dùng GET /Api/Users.";
            return implode("\n", $lines);
        }

        if ($role === 'admin' && $intent === 'admin_api_help') {
            $endpoints = isset($data['endpoints']) && is_array($data['endpoints']) ? $data['endpoints'] : [];
            $lines = ["Dạ, TechZone xin gửi các endpoint chính cho quản trị:"];
            foreach ($endpoints as $item) {
                $lines[] = "- ✅ [" . ($item['method'] ?? '') . "] " . ($item['path'] ?? '') . " - " . ($item['description'] ?? '');
            }
            $lines[] = "Quản trị viên muốn em hướng dẫn mẫu request cụ thể cho endpoint nào ạ?";
            return implode("\n", $lines);
        }

        if ($role === 'admin' && $intent === 'admin_overview') {
            return "Dạ, TechZone xin gửi nhanh tổng quan quản trị:\n"
                . "- 📱 Tổng sản phẩm: " . (int)($data['total_products'] ?? 0) . "\n"
                . "- 📦 Đơn chờ duyệt: " . (int)($data['pending_orders'] ?? 0) . "\n"
                . "- 👤 Tổng người dùng: " . (int)($data['total_users'] ?? 0) . "\n"
                . "Quản trị viên muốn xem chi tiết mục nào tiếp theo ạ?";
        }

        $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
        if (empty($items)) {
            $priceFilter = isset($data['price_filter']) && is_array($data['price_filter']) ? $data['price_filter'] : null;
            $priceHint = '';
            if ($priceFilter !== null) {
                if (($priceFilter['min'] ?? null) !== null && ($priceFilter['max'] ?? null) !== null) {
                    $priceHint = ' trong khoảng giá ' . $this->formatCurrency($priceFilter['min']) . ' - ' . $this->formatCurrency($priceFilter['max']);
                } else if (($priceFilter['max'] ?? null) !== null) {
                    $priceHint = ' dưới ' . $this->formatCurrency($priceFilter['max']);
                } else if (($priceFilter['min'] ?? null) !== null) {
                    $priceHint = ' từ ' . $this->formatCurrency($priceFilter['min']) . ' trở lên';
                }
            }

            return "Dạ, TechZone xin lỗi vì hiện chưa tìm thấy dữ liệu phù hợp với yêu cầu của Quý khách" . $priceHint . ".\n"
                . "- 📱 Quý khách vui lòng thử lại bằng tên sản phẩm cụ thể hơn (ví dụ: iPhone 15, Samsung S24).\n"
                . "- ✅ Em sẽ gợi ý thêm các mẫu tương tự đang sẵn hàng ngay ạ.\n"
                . "Quý khách muốn em tư vấn theo tầm giá nào ạ?";
        }

        $lines = ["Dạ, TechZone đã tìm thấy sản phẩm phù hợp cho Quý khách:"];
        foreach (array_slice($items, 0, 5) as $product) {
            $lines[] = "- 📱 " . ($product['ten_san_pham'] ?? '')
                . " | 💰 " . $this->formatCurrency($product['gia'] ?? 0)
                . " | Danh mục: " . ($product['ten_danh_muc'] ?? 'Đang cập nhật')
                . " | Thương hiệu: " . ($product['ten_thuong_hieu'] ?? 'Đang cập nhật')
                . " | ✅ Kho: " . (int)($product['so_luong_kho'] ?? 0);
        }
        $lines[] = "Quý khách có muốn em hỗ trợ thêm sản phẩm này vào giỏ hàng không ạ?";

        return implode("\n", $lines);
    }

    private function extractOrderCode($message) {
        if (preg_match('/\bDH[0-9A-Z]+\b/i', strtoupper($message), $matches)) {
            return strtoupper(trim($matches[0]));
        }

        return '';
    }

    private function resolveProductKeyword($entities, $message, $priceFilter = null) {
        $entityKeyword = trim((string)($entities['product_keyword'] ?? ''));
        // Ưu tiên tách từ khóa từ chính câu user để tránh entity AI bị lệch dấu/ngữ nghĩa.
        $keyword = $this->extractProductKeyword((string)$message);
        if ($keyword === '' && $entityKeyword !== '') {
            $keyword = $this->extractProductKeyword($entityKeyword);
        }

        if ($keyword === '') {
            return '';
        }

        if ($priceFilter !== null) {
            // Nếu còn lại toàn từ chung chung thì coi như không có từ khóa.
            if (preg_match('/^(cac|các|nhung|những|tat ca|tất cả|mau|mẫu|hang|hàng|san pham|sản phẩm)$/u', $keyword)) {
                return '';
            }

            // Tránh trường hợp LLM trả nguyên câu chứa mệnh đề giá vào product_keyword.
            $keyword = preg_replace('/\b(duoi|dưới|tren|trên|tu|từ|den|đến|khoang|khoảng|tam|tầm)\b.*/u', '', $keyword);
            $keyword = trim(preg_replace('/\s+/', ' ', (string)$keyword));
        }

        return $keyword;
    }

    private function extractProductKeyword($message) {
        $keyword = trim((string)$message);
        if ($keyword === '') {
            return '';
        }

        $stopPhrases = [
            'có', 'không', 'shop', 'cửa hàng', 'techzone', 'giúp', 'tư vấn', 'cho', 'mình', 'em',
            'co', 'khong', 'cua hang', 'giup', 'tu van', 'cho minh',
            'xin', 'chào', 'giá', 'bao nhiêu', 'còn hàng', 'khuyến mãi', 'mã giảm giá', 'đơn hàng',
            'xin chao', 'gia', 'bao nhieu', 'con hang', 'khuyen mai', 'ma giam gia', 'don hang',
            'kiểm tra', 'trạng thái', 'sản phẩm', 'biến thể', 'bien the', 'phiên bản', 'phien ban',
            'kiem tra', 'trang thai', 'san pham',
            'bao nhiêu biến thể', 'bao nhieu bien the', 'là', 'với', 'và', 'ạ', '?',
            'la', 'voi', 'va',
            'dưới', 'duoi', 'trên', 'tren', 'từ', 'tu', 'đến', 'den', 'khoảng', 'khoang', 'tầm', 'tam',
            'triệu', 'trieu', 'nghìn', 'nghin', 'ngàn', 'ngan', 'vnd', 'vnđ',
            'các', 'cac', 'những', 'nhung', 'tất cả', 'tat ca', 'mẫu', 'mau', 'hàng', 'hang',
            'đưa ra', 'dua ra', 'đưa ra hết', 'dua ra het', 'liệt kê', 'liet ke', 'show', 'toàn bộ', 'toan bo'
        ];

        $lower = mb_strtolower($keyword, 'UTF-8');
        foreach ($stopPhrases as $phrase) {
            $lower = str_replace($phrase, ' ', $lower);
        }

        // Xóa mệnh đề khoảng giá để tránh biến thành từ khóa tìm kiếm sai.
        $lower = preg_replace('/\b(duoi|tren|tu|den|khoang|tam)\b\s*[0-9]+(?:[\.,][0-9]+)?\s*(trieu|nghin|ngan|k|vnd|vnđ)?/u', ' ', $lower);
        $lower = preg_replace('/\b[0-9]+(?:[\.,][0-9]+)?\s*(trieu|nghin|ngan|k|vnd|vnđ)\b/u', ' ', $lower);
        $lower = preg_replace('/\b[0-9]+(?:[\.,][0-9]+)?\b/u', ' ', $lower);

        $lower = preg_replace('/\s+/', ' ', $lower);
        return trim($lower);
    }

    private function parseMoneyValue($rawNumber, $rawUnit = '') {
        $number = str_replace(',', '.', trim((string)$rawNumber));
        $value = (float)$number;
        $unit = mb_strtolower(trim((string)$rawUnit), 'UTF-8');

        if ($unit === 'tỷ' || $unit === 'ty') {
            return $value * 1000000000;
        }
        if ($unit === 'triệu' || $unit === 'trieu') {
            return $value * 1000000;
        }
        if ($unit === 'nghìn' || $unit === 'nghin' || $unit === 'ngàn' || $unit === 'ngan' || $unit === 'k') {
            return $value * 1000;
        }

        return $value;
    }

    private function extractPriceFilter($message) {
        $text = mb_strtolower(trim((string)$message), 'UTF-8');
        if ($text === '') {
            return null;
        }

        $hasPriceHint = (
            strpos($text, 'giá') !== false
            || strpos($text, 'gia') !== false
            || strpos($text, 'dưới') !== false
            || strpos($text, 'duoi') !== false
            || strpos($text, 'trên') !== false
            || strpos($text, 'tren') !== false
            || strpos($text, 'từ') !== false
            || strpos($text, 'tu') !== false
            || strpos($text, 'đến') !== false
            || strpos($text, 'den') !== false
            || strpos($text, 'khoảng') !== false
            || strpos($text, 'khoang') !== false
            || strpos($text, 'tầm') !== false
            || strpos($text, 'tam') !== false
            || strpos($text, 'triệu') !== false
            || strpos($text, 'trieu') !== false
            || strpos($text, 'nghìn') !== false
            || strpos($text, 'nghin') !== false
            || strpos($text, 'k') !== false
        );

        if (!$hasPriceHint) {
            return null;
        }

        if (preg_match('/(?:tu|từ)\s*([0-9]+(?:[\.,][0-9]+)?)\s*(ty|tỷ|trieu|triệu|nghin|nghìn|ngan|ngàn|k)?\s*(?:den|đến)\s*([0-9]+(?:[\.,][0-9]+)?)\s*(ty|tỷ|trieu|triệu|nghin|nghìn|ngan|ngàn|k)?/u', $text, $m)) {
            $min = $this->parseMoneyValue($m[1], $m[2] ?? '');
            $max = $this->parseMoneyValue($m[3], $m[4] ?? '');
            if ($max < $min) {
                $tmp = $min;
                $min = $max;
                $max = $tmp;
            }
            return ['min' => $min, 'max' => $max];
        }

        if (preg_match('/([0-9]+(?:[\.,][0-9]+)?)\s*(ty|tỷ|trieu|triệu|nghin|nghìn|ngan|ngàn|k)?\s*(?:-|~)\s*([0-9]+(?:[\.,][0-9]+)?)\s*(ty|tỷ|trieu|triệu|nghin|nghìn|ngan|ngàn|k)?/u', $text, $m2)) {
            $min = $this->parseMoneyValue($m2[1], $m2[2] ?? '');
            $max = $this->parseMoneyValue($m2[3], $m2[4] ?? '');
            if ($max < $min) {
                $tmp = $min;
                $min = $max;
                $max = $tmp;
            }
            return ['min' => $min, 'max' => $max];
        }

        if (preg_match('/(?:duoi|dưới)\s*([0-9]+(?:[\.,][0-9]+)?)\s*(ty|tỷ|trieu|triệu|nghin|nghìn|ngan|ngàn|k)?/u', $text, $m3)) {
            return ['min' => null, 'max' => $this->parseMoneyValue($m3[1], $m3[2] ?? '')];
        }

        if (preg_match('/(?:tren|trên)\s*([0-9]+(?:[\.,][0-9]+)?)\s*(ty|tỷ|trieu|triệu|nghin|nghìn|ngan|ngàn|k)?/u', $text, $m4)) {
            return ['min' => $this->parseMoneyValue($m4[1], $m4[2] ?? ''), 'max' => null];
        }

        return null;
    }

    private function formatCurrency($value) {
        return number_format((float)$value, 0, ',', '.') . 'đ';
    }

    private function loadEnvFromRoot() {
        static $loaded = false;
        if ($loaded) {
            return;
        }
        $loaded = true;

        $envPath = __DIR__ . '/../../../.env';
        if (!is_file($envPath) || !is_readable($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!is_array($lines)) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim((string)$line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                continue;
            }

            $parts = explode('=', $line, 2);
            $name = trim((string)($parts[0] ?? ''));
            $value = trim((string)($parts[1] ?? ''));

            if ($name === '') {
                continue;
            }

            $len = strlen($value);
            if ($len >= 2) {
                $first = $value[0];
                $last = $value[$len - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            $current = getenv($name);
            if ($current === false || trim((string)$current) === '') {
                putenv($name . '=' . $value);
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    private function getGroqApiKey() {
        $this->loadEnvFromRoot();

        $envKey = getenv('GROQ_API_KEY');
        if (is_string($envKey) && trim($envKey) !== '') {
            return trim($envKey);
        }

        if (defined('GROQ_API_KEY') && trim((string)GROQ_API_KEY) !== '') {
            return trim((string)GROQ_API_KEY);
        }

        return '';
    }

    private function callGroq($apiKey, $messages) {
        if (!function_exists('curl_init')) {
            return '';
        }

        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        if ($ch === false) {
            return '';
        }

        $payload = [
            'model' => 'llama-3.1-8b-instant',
            'temperature' => 0.2,
            'messages' => $messages
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $raw = curl_exec($ch);
        if ($raw === false) {
            curl_close($ch);
            return '';
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status < 200 || $status >= 300) {
            return '';
        }

        $json = json_decode($raw, true);
        if (!is_array($json)) {
            return '';
        }

        return trim((string)($json['choices'][0]['message']['content'] ?? ''));
    }

    private function decodeJsonFromText($text) {
        $text = trim((string)$text);
        if ($text === '') {
            return null;
        }

        $direct = json_decode($text, true);
        if (is_array($direct)) {
            return $direct;
        }

        if (preg_match('/\{.*\}/s', $text, $matches)) {
            $candidate = $matches[0];
            $decoded = json_decode($candidate, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }
}
?>