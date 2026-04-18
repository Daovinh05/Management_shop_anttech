<?php
class Settings extends api_controller
{
    private $settingsModel;

    public function __construct()
    {
        parent::__construct();
        $this->settingsModel = $this->model('Settings_m');
    }

    private function requireAdmin()
    {
        $role = isset($_SESSION['user_role']) ? (string)$_SESSION['user_role'] : '';
        if ($role !== 'admin') {
            $this->sendResponse(403, [
                'success' => false,
                'message' => 'Bạn không có quyền thao tác cấu hình hệ thống'
            ]);
        }
    }

    public function order_timeout()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Uu tien key moi theo phut; fallback tu key gio de tuong thich du lieu cu.
            $rawMinutes = $this->settingsModel->Settings_get('order_timeout_minutes', null);
            $minutes = (int)$rawMinutes;

            if ($minutes <= 0) {
                $rawHours = $this->settingsModel->Settings_get('order_timeout_hours', '24');
                $hours = (int)$rawHours;
                if ($hours <= 0) {
                    $hours = 24;
                }
                $minutes = $hours * 60;
            }

            $this->sendResponse(200, [
                'success' => true,
                'data' => [
                    'config_key' => 'order_timeout_minutes',
                    'minutes' => $minutes
                ]
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            $this->sendResponse(405, [
                'success' => false,
                'message' => 'Method Not Allowed. Must use GET/POST/PUT/PATCH'
            ]);
        }

        $data = $this->getJsonInput();
        if (empty($data)) {
            $data = $_POST;
        }

        $minutes = isset($data['minutes']) ? (int)$data['minutes'] : 0;
        if ($minutes <= 0 && isset($data['hours'])) {
            $minutes = (int)$data['hours'] * 60;
        }
        if ($minutes < 1 || $minutes > 10080) {
            $this->sendResponse(422, [
                'success' => false,
                'message' => 'Số phút phải nằm trong khoảng từ 1 đến 10080'
            ]);
        }

        $ma_user = isset($_SESSION['user_id']) ? (string)$_SESSION['user_id'] : null;
        $saved = $this->settingsModel->Settings_upsert('order_timeout_minutes', (string)$minutes, $ma_user);

        if (!$saved) {
            $this->sendResponse(500, [
                'success' => false,
                'message' => 'Không thể lưu cấu hình thời gian tự động hủy đơn',
                'error' => mysqli_error($this->settingsModel->con)
            ]);
        }

        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Lưu cấu hình thành công',
            'data' => [
                'config_key' => 'order_timeout_minutes',
                'minutes' => $minutes
            ]
        ]);
    }
}
