<?php
// File: MVC/Controllers/Api/Chatbot.php
class Chatbot
{
    // POST /Api/Chatbot/ask
    public function ask()
    {
        header('Content-Type: application/json; charset=utf-8');
        $input = json_decode(file_get_contents('php://input'), true);
        $message = isset($input['message']) ? trim($input['message']) : '';
        $reply = 'Xin lỗi, tôi chưa hiểu ý bạn.';

        // =============================================
        // PATTERNS nhận diện câu hỏi đặc biệt
        // =============================================
        $patterns = [
                        'product_price' => [
                            '/giá\s*(sản\s*phẩm)?\s*(.+)/i',
                            '/số\s*tiền\s*(sản\s*phẩm)?\s*(.+)/i',
                            '/bao\s*nhiêu\s*tiền\s*(sản\s*phẩm)?\s*(.+)/i',
                            '/giá\s*bán\s*(.+)/i',
                        ],
            'product_count' => [
                '/bao\s*nhiêu\s*sản\s*phẩm/i',
                '/có\s*bao\s*nhiêu\s*sản\s*phẩm/i',
                '/số\s*lượng\s*sản\s*phẩm/i',
                '/tổng\s*sản\s*phẩm/i',
                '/tổng\s*cộng\s*sản\s*phẩm/i',
                '/sản\s*phẩm\s*đang\s*kinh\s*doanh/i',
                '/how\s*many\s*products/i',
                '/number\s*of\s*products/i',
            ],
            'order_buyer' => [
                '/đơn\s*hàng\s*(\d+)\s*ai\s*mua/i',
                '/ai\s*mua\s*đơn\s*hàng\s*(\d+)/i',
            ],
            'product_stock' => [
                '/sản\s*phẩm\s*(.+)\s*còn\s*bao\s*nhiêu/i',
                '/tồn\s*kho\s*sản\s*phẩm\s*(.+)/i',
            ],
            'product_variant_count' => [
                '/sản\s*phẩm\s*(.+)\s*có\s*bao\s*nhiêu\s*biến\s*thể/i',
            ],
            'supplier_count' => [
                '/bao\s*nhiêu\s*nhà\s*cung\s*cấp/i',
                '/có\s*bao\s*nhiêu\s*nhà\s*cung\s*cấp/i',
            ],
            'variant_out_of_stock' => [
                '/biến\s*thể\s*nào\s*hết\s*hàng/i',
            ],
            'orders_5star' => [
                '/đơn\s*(hàng)?\s*(nào)?\s*đánh\s*giá\s*5\s*sao/i',
            ],
            'today_revenue' => [
                '/doanh\s*(số|thu)\s*(hôm\s*nay)?/i',
            ],
            'orders_cod' => [
                '/đơn\s*(nào)?\s*thanh\s*toán\s*cod/i',
            ],
            'orders_vnpay' => [
                '/đơn\s*(nào)?\s*thanh\s*toán\s*vnpay/i',
            ],
            // ✅ Các pattern này phải nằm NGOÀI orders_vnpay, không lồng bên trong
            'top_selling' => [
                '/sản\s*phẩm\s*bán\s*chạy/i',
                '/bán\s*chạy\s*nhất/i',
                '/top\s*\d*\s*bán\s*chạy/i',
            ],
            'top_customers' => [
                '/ai\s*mua\s*nhiều\s*nhất/i',
                '/khách\s*hàng\s*thân\s*thiết/i',
                '/top\s*khách\s*hàng/i',
            ],
            'pending_orders' => [
                '/đơn\s*hàng\s*mới/i',
                '/chưa\s*xử\s*lý/i',
                '/bao\s*nhiêu\s*đơn\s*đang\s*chờ/i',
            ],
            'system_status' => [
                '/tình\s*hình\s*hệ\s*thống/i',
                '/website\s*sao\s*rồi/i',
                '/sức\s*khỏe\s*hệ\s*thống/i',
            ],
        ];

        // =============================================
        // Kết nối DB - CHỈ 1 LẦN DUY NHẤT
        // =============================================
        $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Helper: trả JSON và thoát
        $respond = function (string $msg) use ($db) {
            echo json_encode(['reply' => $msg]);
            if ($db) mysqli_close($db);
            exit;
        };

        // =============================================
        // XỬ LÝ TỪNG KỊCH BẢN ĐẶC BIỆT
        // =============================================

        // 1. Số lượng sản phẩm
        foreach ($patterns['product_count'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db, "SELECT COUNT(*) as total FROM san_pham") : false;
                $total = ($rs && $row = mysqli_fetch_assoc($rs)) ? $row['total'] : 'Không xác định';
                $respond("Hiện tại hệ thống đang kinh doanh $total sản phẩm.");
            }
        }

        // 2. Đơn hàng XX do ai mua
        foreach ($patterns['order_buyer'] as $pattern) {
            if (preg_match($pattern, $message, $m)) {
                $order_id = mysqli_real_escape_string($db, $m[1]);
                $rs = $db ? mysqli_query($db,
                    "SELECT u.full_name, u.ten_user
                     FROM don_hang d
                     JOIN users u ON d.ma_user = u.ma_user
                     WHERE d.ma_don_hang = '$order_id'"
                ) : false;
                if ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $ten = $row['full_name'] ?: $row['ten_user'];
                    $respond("Đơn hàng #$order_id do khách hàng **$ten** mua.");
                }
                $respond("Không tìm thấy thông tin đơn hàng #$order_id.");
            }
        }

        // 3. Tồn kho sản phẩm X
        foreach ($patterns['product_stock'] as $pattern) {
            if (preg_match($pattern, $message, $m)) {
                $ten = mysqli_real_escape_string($db, trim($m[1]));
                $rs = $db ? mysqli_query($db,
                    "SELECT bt.so_luong_kho
                     FROM san_pham sp
                     JOIN bien_the bt ON sp.ma_san_pham = bt.ma_san_pham
                     WHERE sp.ten_san_pham LIKE '%$ten%'
                     LIMIT 1"
                ) : false;
                if ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $respond("Sản phẩm '$ten' còn tồn kho: {$row['so_luong_kho']}.");
                }
                $respond("Không tìm thấy sản phẩm '$ten'.");
            }
        }

        // 4. Số biến thể của sản phẩm X
        foreach ($patterns['product_variant_count'] as $pattern) {
            if (preg_match($pattern, $message, $m)) {
                $ten = mysqli_real_escape_string($db, trim($m[1]));
                $rs = $db ? mysqli_query($db,
                    "SELECT ma_san_pham FROM san_pham WHERE ten_san_pham LIKE '%$ten%' LIMIT 1"
                ) : false;
                if ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $ma = $row['ma_san_pham'];
                    $rs2 = mysqli_query($db, "SELECT COUNT(*) as total FROM bien_the WHERE ma_san_pham = '$ma'");
                    $so = ($rs2 && $row2 = mysqli_fetch_assoc($rs2)) ? $row2['total'] : 0;
                    $respond("Sản phẩm '$ten' có $so biến thể.");
                }
                $respond("Không tìm thấy sản phẩm '$ten'.");
            }
        }

        // 5. Số nhà cung cấp
        foreach ($patterns['supplier_count'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db, "SELECT COUNT(*) as total FROM nha_cung_cap") : false;
                $so = ($rs && $row = mysqli_fetch_assoc($rs)) ? $row['total'] : 0;
                $respond("Hệ thống hiện có $so nhà cung cấp.");
            }
        }

        // 6. Biến thể hết hàng
        foreach ($patterns['variant_out_of_stock'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db, "SELECT ten_bien_the FROM bien_the WHERE so_luong_kho <= 0") : false;
                $arr = [];
                while ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $arr[] = $row['ten_bien_the'];
                }
                $respond($arr
                    ? "Các biến thể đang hết hàng: " . implode(', ', $arr)
                    : "Tất cả biến thể đều còn hàng."
                );
            }
        }

        // 7. Sản phẩm được đánh giá 5 sao
        foreach ($patterns['orders_5star'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db,
                    "SELECT sp.ten_san_pham
                     FROM danh_gia dg
                     JOIN san_pham sp ON dg.ma_san_pham = sp.ma_san_pham
                     WHERE dg.so_sao = 5
                     GROUP BY dg.ma_san_pham"
                ) : false;
                $arr = [];
                while ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $arr[] = $row['ten_san_pham'];
                }
                $respond($arr
                    ? "Các sản phẩm được đánh giá 5 sao: " . implode(', ', $arr)
                    : "Chưa có sản phẩm nào được đánh giá 5 sao."
                );
            }
        }

        // 8. Doanh thu hôm nay
        foreach ($patterns['today_revenue'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db,
                    "SELECT SUM(thanh_toan) as total
                     FROM don_hang
                     WHERE DATE(ngay_tao) = CURDATE()
                     AND trang_thai_don_hang = 'hoan_thanh'"
                ) : false;
                $so = ($rs && $row = mysqli_fetch_assoc($rs))
                    ? number_format($row['total'] ?? 0, 0, ',', '.') . ' VNĐ'
                    : '0 VNĐ';
                $respond("💰 Doanh thu hôm nay: $so");
            }
        }

        // 9. Đơn thanh toán COD
        foreach ($patterns['orders_cod'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db,
                    "SELECT ma_don_hang FROM thanh_toan WHERE phuong_thuc = 'cod'"
                ) : false;
                $arr = [];
                while ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $arr[] = '#' . $row['ma_don_hang'];
                }
                $respond($arr
                    ? "Các đơn thanh toán COD: " . implode(', ', $arr)
                    : "Không có đơn hàng nào thanh toán COD."
                );
            }
        }

        // 10. Đơn thanh toán VNPAY
        foreach ($patterns['orders_vnpay'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db,
                    "SELECT ma_don_hang FROM thanh_toan WHERE phuong_thuc = 'vnpay'"
                ) : false;
                $arr = [];
                while ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $arr[] = '#' . $row['ma_don_hang'];
                }
                $respond($arr
                    ? "Các đơn thanh toán VNPAY: " . implode(', ', $arr)
                    : "Không có đơn hàng nào thanh toán VNPAY."
                );
            }
        }

        // 11. Top sản phẩm bán chạy
        foreach ($patterns['top_selling'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db,
                    "SELECT sp.ten_san_pham, SUM(ct.so_luong) as total_sold
                     FROM chi_tiet_don_hang ct
                     JOIN san_pham sp ON ct.ma_san_pham = sp.ma_san_pham
                     GROUP BY ct.ma_san_pham
                     ORDER BY total_sold DESC
                     LIMIT 5"
                ) : false;
                $arr = [];
                while ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $arr[] = "{$row['ten_san_pham']} ({$row['total_sold']} sp)";
                }
                $respond($arr
                    ? "🔥 Top 5 sản phẩm bán chạy: " . implode(', ', $arr)
                    : "Chưa có dữ liệu bán hàng."
                );
            }
        }

        // 12. Top khách hàng mua nhiều nhất ✅ ĐÃ SỬA - lấy đúng data từ DB
        foreach ($patterns['top_customers'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db,
                    "SELECT u.full_name, u.ten_user,
                            SUM(ct.gia_luc_mua * ct.so_luong) as total_spent
                     FROM chi_tiet_don_hang ct
                     JOIN don_hang d ON ct.ma_don_hang = d.ma_don_hang
                     JOIN users u ON d.ma_user = u.ma_user
                     WHERE d.trang_thai_don_hang = 'hoan_thanh'
                     GROUP BY u.ma_user
                     ORDER BY total_spent DESC
                     LIMIT 5"
                ) : false;
                $arr = [];
                while ($rs && $row = mysqli_fetch_assoc($rs)) {
                    $ten = $row['full_name'] ?: $row['ten_user'];
                    $arr[] = "$ten (" . number_format($row['total_spent'], 0, ',', '.') . "đ)";
                }
                $respond($arr
                    ? "🏆 Top khách hàng mua nhiều nhất: " . implode(', ', $arr)
                    : "Chưa có dữ liệu khách hàng."
                );
            }
        }

        // 13. Đơn hàng chờ xử lý
        foreach ($patterns['pending_orders'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $rs = $db ? mysqli_query($db,
                    "SELECT COUNT(*) as total FROM don_hang WHERE trang_thai_don_hang = 'cho_duyet'"
                ) : false;
                $count = ($rs && $row = mysqli_fetch_assoc($rs)) ? $row['total'] : 0;
                $respond($count > 0
                    ? "🔔 Có $count đơn hàng đang chờ duyệt!"
                    : "✅ Không có đơn hàng nào tồn đọng."
                );
            }
        }

        // 14. Trạng thái hệ thống
        foreach ($patterns['system_status'] as $pattern) {
            if (preg_match($pattern, $message)) {
                $respond("✅ Hệ thống đang hoạt động ổn định. Nếu có vấn đề, hãy kiểm tra log server nhé!");
            }
        }

        // 15. Giá sản phẩm cụ thể
        foreach ($patterns['product_price'] as $pattern) {
            if (preg_match($pattern, $message, $m)) {
                // Lấy tên sản phẩm từ group cuối cùng
                $ten = isset($m[2]) ? trim($m[2]) : (isset($m[1]) ? trim($m[1]) : '');
                if ($ten) {
                    $ten = mysqli_real_escape_string($db, $ten);
                    // Lấy giá các biến thể của sản phẩm
                    $rs = $db ? mysqli_query($db,
                        "SELECT sp.ten_san_pham, bt.gia, bt.ten_bien_the FROM san_pham sp
                         JOIN bien_the bt ON sp.ma_san_pham = bt.ma_san_pham
                         WHERE sp.ten_san_pham LIKE '%$ten%'") : false;
                    $arr = [];
                    while ($rs && $row = mysqli_fetch_assoc($rs)) {
                        $gia = number_format($row['gia'], 0, ',', '.');
                        $ten_bien_the = $row['ten_bien_the'] ? ' - ' . $row['ten_bien_the'] : '';
                        $arr[] = $row['ten_san_pham'] . $ten_bien_the . ': ' . $gia . 'đ';
                    }
                    if ($arr) {
                        $respond('Giá sản phẩm: ' . implode('; ', $arr));
                    } else {
                        $respond("Không tìm thấy thông tin giá cho sản phẩm '$ten'.");
                    }
                }
            }
        }

        // =============================================
        // KHÔNG KHỚP KỊCH BẢN ĐẶC BIỆT
        // → Lấy dữ liệu tổng hợp rồi gọi Groq API
        // =============================================
        $doanh_so     = 'Không xác định';
        $so_san_pham  = 'Không xác định';
        $so_bien_the  = 'Không xác định';
        $so_danh_muc  = 'Không xác định';
        $san_pham_thieu = 'Không có sản phẩm nào thiếu hàng.';

        if ($db) {
            $rs = mysqli_query($db,
                "SELECT SUM(thanh_toan) as total FROM don_hang
                 WHERE DATE(ngay_tao) = CURDATE() AND trang_thai_don_hang = 'hoan_thanh'"
            );
            if ($rs && $row = mysqli_fetch_assoc($rs)) {
                $doanh_so = number_format($row['total'] ?? 0, 0, ',', '.') . ' VNĐ';
            }

            $rs = mysqli_query($db, "SELECT COUNT(*) as total FROM san_pham");
            if ($rs && $row = mysqli_fetch_assoc($rs)) $so_san_pham = $row['total'];

            $rs = mysqli_query($db, "SELECT COUNT(*) as total FROM bien_the");
            if ($rs && $row = mysqli_fetch_assoc($rs)) $so_bien_the = $row['total'];

            $rs = mysqli_query($db, "SELECT COUNT(*) as total FROM danh_muc");
            if ($rs && $row = mysqli_fetch_assoc($rs)) $so_danh_muc = $row['total'];

            $rs = mysqli_query($db, "SELECT ten_san_pham FROM san_pham WHERE so_luong_kho < 5 LIMIT 5");
            $thieu_arr = [];
            while ($rs && $row = mysqli_fetch_assoc($rs)) $thieu_arr[] = $row['ten_san_pham'];
            if ($thieu_arr) $san_pham_thieu = implode(', ', $thieu_arr);
        }

        // Context cho Groq
        $context  = "Bạn là trợ lý AI thân thiện cho quản trị viên hệ thống bán hàng.\n";
        $context .= "Trả lời ngắn gọn, rõ ràng bằng tiếng Việt.\n";
        $context .= "\n=== DỮ LIỆU TỔNG HỢP HỆ THỐNG ===\n";
        $context .= "- Doanh thu hôm nay: $doanh_so\n";
        $context .= "- Số sản phẩm đang kinh doanh: $so_san_pham\n";
        $context .= "- Số biến thể sản phẩm: $so_bien_the\n";
        $context .= "- Số danh mục: $so_danh_muc\n";
        $context .= "- Sản phẩm sắp hết hàng (tồn kho < 5): $san_pham_thieu\n";
        $context .= "\nNếu câu hỏi liên quan đến dữ liệu không có trong phần trên, hãy trả lời thành thật rằng bạn không có dữ liệu đó.\n";

        // Gọi Groq API
        $api_key  = defined('GROQ_API_KEY') ? GROQ_API_KEY : getenv('GROQ_API_KEY');
        $groq_url = 'https://api.groq.com/openai/v1/chat/completions';
        $groq_response = null;

        if ($api_key && $message !== '') {
            $payload = [
                'model'    => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $context],
                    ['role' => 'user',   'content' => $message],
                ],
                'max_tokens'  => 512,
                'temperature' => 0.5,
            ];

            $ch = curl_init($groq_url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $api_key,
                ],
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_TIMEOUT        => 15,
            ]);
            $result   = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpcode === 200 && $result) {
                $json = json_decode($result, true);
                $groq_response = $json['choices'][0]['message']['content'] ?? null;
            }
        }

        $reply = $groq_response
            ?: ($message !== ''
                ? "Dữ liệu tổng hợp: Doanh thu hôm nay $doanh_so | Sản phẩm: $so_san_pham | Biến thể: $so_bien_the | Danh mục: $so_danh_muc."
                : "Vui lòng nhập câu hỏi để tôi hỗ trợ bạn.");

        $respond($reply);
    }
}