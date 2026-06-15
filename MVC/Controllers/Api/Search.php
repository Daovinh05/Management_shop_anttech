<?php
class Search extends api_controller {
    private $sp;

    public function __construct() {
        parent::__construct();
        $this->sp = $this->model('SanPham_m');
    }

    private function parseInputData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contentType = isset($_SERVER['CONTENT_TYPE']) ? strtolower(trim((string)$_SERVER['CONTENT_TYPE'])) : '';

            if (strpos($contentType, 'application/json') !== false) {
                $json = $this->getJsonInput();
                if (!empty($json)) {
                    return $json;
                }
            }

            if (!empty($_POST)) {
                return $_POST;
            }

            // Fallback: co the request gui JSON nhung khong co content-type day du
            $json = $this->getJsonInput();
            if (!empty($json)) {
                return $json;
            }

            return [];
        }
        return $this->getJsonInput();
    }

    private function getHistoryOwnerKey() {
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            return 'user_' . trim((string)$_SESSION['user_id']);
        }

        return 'guest';
    }

    private function getSearchHistory() {
        if (!isset($_SESSION['search_history']) || !is_array($_SESSION['search_history'])) {
            $_SESSION['search_history'] = [];
        }

        $ownerKey = $this->getHistoryOwnerKey();
        if (!isset($_SESSION['search_history'][$ownerKey]) || !is_array($_SESSION['search_history'][$ownerKey])) {
            $_SESSION['search_history'][$ownerKey] = [];
        }

        return $_SESSION['search_history'][$ownerKey];
    }

    private function saveSearchHistory($history) {
        $ownerKey = $this->getHistoryOwnerKey();
        $_SESSION['search_history'][$ownerKey] = array_values($history);
    }

    private function upsertKeywordToHistory($keyword) {
        $keyword = trim((string)$keyword);
        if ($keyword === '') {
            return;
        }

        $history = $this->getSearchHistory();
        $normalized = mb_strtolower($keyword, 'UTF-8');

        $filtered = [];
        foreach ($history as $item) {
            $existingKeyword = trim((string)($item['keyword'] ?? ''));
            if ($existingKeyword === '') {
                continue;
            }

            if (mb_strtolower($existingKeyword, 'UTF-8') === $normalized) {
                continue;
            }

            $filtered[] = $item;
        }

        array_unshift($filtered, [
            'id' => 'sr_' . substr(md5(uniqid((string)mt_rand(), true)), 0, 10),
            'keyword' => $keyword,
            'updated_at' => date('c')
        ]);

        $this->saveSearchHistory(array_slice($filtered, 0, 10));
    }

    public function get_all() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;

        $page = max(1, $page);
        $limit = max(1, min(48, $limit));

        $items = [];
        $total = 0;

        if ($q !== '') {
            $result = $this->sp->SanPham_searchStorefront($q, $page, $limit);
            if ($result === false) {
                $this->sendResponse(500, ['success' => false, 'message' => 'Không thể tìm kiếm sản phẩm']);
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row;
            }

            $total = $this->sp->SanPham_countSearchStorefront($q);
            $this->upsertKeywordToHistory($q);
        }

        $history = $this->getSearchHistory();

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Tìm kiếm thành công',
            'data' => [
                'query' => $q,
                'items' => $items,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'total_pages' => $total > 0 ? (int)ceil($total / $limit) : 0
                ],
                'history' => $history
            ]
        ]);
    }

    public function get_detail($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã sản phẩm']);
        }

        $result = $this->sp->SanPham_getStorefrontDetail($id);
        if (!$result || mysqli_num_rows($result) === 0) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
        }

        $product = mysqli_fetch_assoc($result);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy chi tiết tìm kiếm thành công',
            'data' => $product
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use POST']);
        }

        $data = $this->parseInputData();
        $keyword = trim((string)($data['keyword'] ?? ''));

        if ($keyword === '') {
            $this->sendResponse(422, ['success' => false, 'message' => 'keyword khong duoc de trong']);
        }

        $this->upsertKeywordToHistory($keyword);
        $history = $this->getSearchHistory();

        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Lưu từ khóa tìm kiếm thành công',
            'data' => [
                'keyword' => $keyword,
                'history' => $history
            ]
        ]);
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use PUT/PATCH/POST']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã lịch sử tìm kiếm']);
        }

        $data = $this->parseInputData();
        $keyword = trim((string)($data['keyword'] ?? ''));

        if ($keyword === '') {
            $this->sendResponse(422, ['success' => false, 'message' => 'keyword khong duoc de trong']);
        }

        $history = $this->getSearchHistory();
        $found = false;

        foreach ($history as $index => $item) {
            if (($item['id'] ?? '') === $id) {
                $history[$index]['keyword'] = $keyword;
                $history[$index]['updated_at'] = date('c');
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy lịch sử tìm kiếm']);
        }

        usort($history, function ($a, $b) {
            return strcmp((string)($b['updated_at'] ?? ''), (string)($a['updated_at'] ?? ''));
        });

        $this->saveSearchHistory($history);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Cập nhật lịch sử tìm kiếm thành công',
            'data' => [
                'history' => $this->getSearchHistory()
            ]
        ]);
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use DELETE']);
        }

        if (!$id) {
            $this->sendResponse(400, ['success' => false, 'message' => 'Thiếu mã lịch sử tìm kiếm']);
        }

        $history = $this->getSearchHistory();
        $before = count($history);

        $history = array_values(array_filter($history, function ($item) use ($id) {
            return ($item['id'] ?? '') !== $id;
        }));

        if ($before === count($history)) {
            $this->sendResponse(404, ['success' => false, 'message' => 'Không tìm thấy lịch sử tìm kiếm']);
        }

        $this->saveSearchHistory($history);

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Xóa lịch sử tìm kiếm thành công',
            'data' => [
                'history' => $history
            ]
        ]);
    }

    public function suggestions() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
        $limit = max(1, min(20, $limit));

        if ($q === '') {
            $this->sendResponse(200, [
                'success' => true,
                'message' => 'Không có từ khóa gợi ý',
                'data' => []
            ]);
        }

        $result = $this->sp->SanPham_getSearchSuggestions($q, $limit);
        if ($result === false) {
            $this->sendResponse(500, ['success' => false, 'message' => 'Không thể lấy gợi ý tìm kiếm']);
        }

        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy gợi ý tìm kiếm thành công',
            'data' => $items
        ]);
    }

    public function history() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['success' => false, 'message' => 'Method Not Allowed. Must use GET']);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lấy lịch sử tìm kiếm thành công',
            'data' => $this->getSearchHistory()
        ]);
    }
}
