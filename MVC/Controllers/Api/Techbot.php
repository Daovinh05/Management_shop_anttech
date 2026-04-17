<?php
class Techbot extends api_controller {
    private $sanpham_model;
    private $bienthe_model;
    private $donhang_model;
    private $khuyenmai_model;
    private $users_model;

    public function __construct() {
        parent::__construct();
        $this->sanpham_model = $this->model('SanPham_m');
        $this->bienthe_model = $this->model('BienThe_m');
        $this->donhang_model = $this->model('DonHang_m');
        $this->khuyenmai_model = $this->model('KhuyenMai_m');
        $this->users_model = $this->model('Users_m');
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

        $role = $this->resolveRole();
        $intentMeta = $this->detectIntent($message, $role);
        $intent = $intentMeta['intent'];
        $entities = $intentMeta['entities'];

        $messageLower = mb_strtolower($message, 'UTF-8');
        if (strpos($messageLower, 'doanh thu') !== false || strpos($messageLower, 'doanh số') !== false || strpos($messageLower, 'doanh so') !== false) {
            $intent = ($role === 'admin') ? 'admin_revenue_report' : 'restricted_info';
        }

        if ($role !== 'admin' && in_array($intent, ['admin_pending_orders', 'admin_inventory_alert', 'admin_users_list', 'admin_api_help', 'admin_full_access', 'admin_revenue_report'], true)) {
            $intent = 'restricted_info';
        }

        $payload = [
            'question' => $message,
            'role' => $role,
            'intent' => $intent,
            'data' => []
        ];

        if ($intent === 'order_status') {
            $orderCode = trim((string)($entities['order_code'] ?? $this->extractOrderCode($message)));
            if ($orderCode === '') {
                $reply = $this->buildNeedOrderCodeReply();
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
                $keyword = trim((string)($entities['product_keyword'] ?? $this->extractProductKeyword($message)));
                $payload['data'] = $this->getProducts($keyword, true);

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
            $keyword = trim((string)($entities['product_keyword'] ?? $this->extractProductKeyword($message)));
            $payload['data'] = $this->getProductVariants($keyword, false);
        } else if ($intent === 'restricted_info') {
            $payload['data'] = [];
        } else if ($intent === 'greeting') {
            $payload['data'] = [];
        } else {
            $keyword = trim((string)($entities['product_keyword'] ?? $this->extractProductKeyword($message)));
            $payload['data'] = $this->getProducts($keyword, false);

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
            . "Intent hợp lệ: greeting, product_lookup, product_variant_lookup, order_status, promotion_lookup, admin_revenue_report, admin_pending_orders, admin_inventory_alert, admin_users_list, admin_api_help, admin_full_access, restricted_info, unknown.\n"
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

    private function getProducts($keyword, $includeOutOfStock) {
        $result = ($keyword !== '')
            ? $this->sanpham_model->SanPham_find('', $keyword)
            : $this->sanpham_model->SanPham_getAll();

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
                        'gia' => isset($row['gia']) ? (float)$row['gia'] : 0,
                        'so_luong_kho' => isset($row['so_luong_kho']) ? (int)$row['so_luong_kho'] : 0,
                        'ten_danh_muc' => $row['ten_danh_muc'] ?? '',
                        'ten_thuong_hieu' => $row['ten_thuong_hieu'] ?? '',
                        'img_bien_the' => $row['img_bien_the'] ?? ''
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
                }
            }
        }

        $items = array_values($map);
        if (!$includeOutOfStock) {
            $items = array_values(array_filter($items, function($item) {
                return (int)($item['so_luong_kho'] ?? 0) > 0;
            }));
        }

        usort($items, function($a, $b) {
            return (int)($b['so_luong_kho'] ?? 0) - (int)($a['so_luong_kho'] ?? 0);
        });

        return [
            'keyword' => $keyword,
            'total' => count($items),
            'items' => array_slice($items, 0, 8)
        ];
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
        return [
            'found' => true,
            'ma_don_hang' => $row['ma_don_hang'] ?? $orderCode,
            'full_name' => $row['full_name'] ?? '',
            'trang_thai_don_hang' => $row['trang_thai_don_hang'] ?? 'khong_xac_dinh',
            'tong_tien_hang' => isset($row['tong_tien_hang']) ? (float)$row['tong_tien_hang'] : 0
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

    private function getUsersPreview() {
        $result = $this->users_model->Users_find('', '');
        $users = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = [
                    'ma_user' => $row['ma_user'] ?? '',
                    'ten_user' => $row['ten_user'] ?? '',
                    'full_name' => $row['full_name'] ?? '',
                    'email' => $row['email'] ?? '',
                    'so_dien_thoai' => $row['so_dien_thoai'] ?? '',
                    'phan_quyen' => $row['phan_quyen'] ?? ''
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

    private function getAdminFullShopData() {
        $products = $this->rowsFromResult($this->sanpham_model->SanPham_getAll());
        $orders = $this->rowsFromResult($this->donhang_model->DonHang_getAll());
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
                'source_endpoints' => ['/Api/Products', '/Api/Donhang', '/Api/Users', '/Api/Khuyenmai']
            ],
            'totals' => [
                'products' => count($products),
                'orders' => count($orders),
                'users' => count($users),
                'promotions' => count($promotions),
                'pending_orders' => count($pendingOrders),
                'low_stock_products' => count($lowStockProducts)
            ],
            'products' => $products,
            'orders' => $orders,
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
        $apiKey = $this->getGroqApiKey();
        if ($apiKey !== '') {
            $groqReply = $this->buildResponseByGroq($apiKey, $payload);
            if ($groqReply !== '') {
                return $groqReply;
            }
        }

        return $this->buildResponseLocal($payload);
    }

    private function buildResponseByGroq($apiKey, $payload) {
        $systemPrompt = "Bạn là TechZone Assistant. Bắt buộc trả lời tiếng Việt lịch sự, chuyên nghiệp theo cấu trúc: chào thương hiệu, nội dung chính, lời mời bước tiếp theo."
            . " Dùng icon phù hợp như 📱💰📦✅."
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
                . "Quý khách có muốn em hỗ trợ thêm về phương thức thanh toán hoặc giao hàng không ạ?";
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
                . "- 📦 Đơn hàng: " . (int)($totals['orders'] ?? 0) . "\n"
                . "- 👤 Người dùng: " . (int)($totals['users'] ?? 0) . "\n"
                . "- 💰 Khuyến mãi: " . (int)($totals['promotions'] ?? 0) . "\n"
                . "- ⚠️ Đơn chờ duyệt: " . (int)($totals['pending_orders'] ?? 0) . " | Sản phẩm tồn kho thấp: " . (int)($totals['low_stock_products'] ?? 0) . "\n"
                . "Quản lý có muốn em lọc sâu theo nhóm dữ liệu cụ thể (sản phẩm/đơn hàng/người dùng/khuyến mãi) không ạ?";
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
            return "Dạ, TechZone xin lỗi vì hiện chưa tìm thấy dữ liệu phù hợp với yêu cầu của Quý khách.\n"
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

    private function extractProductKeyword($message) {
        $keyword = trim((string)$message);
        if ($keyword === '') {
            return '';
        }

        $stopPhrases = [
            'có', 'không', 'shop', 'cửa hàng', 'techzone', 'giúp', 'tư vấn', 'cho', 'mình', 'em',
            'xin', 'chào', 'giá', 'bao nhiêu', 'còn hàng', 'khuyến mãi', 'mã giảm giá', 'đơn hàng',
            'kiểm tra', 'trạng thái', 'sản phẩm', 'biến thể', 'bien the', 'phiên bản', 'phien ban',
            'bao nhiêu biến thể', 'bao nhieu bien the', 'là', 'với', 'và', 'ạ', '?'
        ];

        $lower = mb_strtolower($keyword, 'UTF-8');
        foreach ($stopPhrases as $phrase) {
            $lower = str_replace($phrase, ' ', $lower);
        }

        $lower = preg_replace('/\s+/', ' ', $lower);
        return trim($lower);
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