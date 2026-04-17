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

        if ($message !== '') {
            // Trả lời mẫu cho admin: hỏi về doanh số, sản phẩm, biến thể, danh mục...
            if (stripos($message, 'doanh số') !== false) {
                $reply = 'Doanh số hôm nay là 12.000.000 VNĐ.';
            } elseif (stripos($message, 'sản phẩm') !== false) {
                $reply = 'Hiện có 120 sản phẩm đang kinh doanh.';
            } elseif (stripos($message, 'biến thể') !== false) {
                $reply = 'Có 35 biến thể sản phẩm còn hàng.';
            } elseif (stripos($message, 'danh mục') !== false) {
                $reply = 'Có 8 danh mục sản phẩm.';
            } elseif (stripos($message, 'thiếu') !== false) {
                $reply = 'Một số sản phẩm đang thiếu hàng: iPhone 15, Samsung S24.';
            } elseif (stripos($message, 'còn') !== false) {
                $reply = 'Tất cả các danh mục đều còn hàng.';
            } else {
                $reply = 'Bạn có thể hỏi về doanh số, sản phẩm, biến thể, danh mục, tồn kho...';
            }
        }
        echo json_encode(['reply' => $reply]);
        exit;
    }
}
