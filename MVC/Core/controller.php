<?php
class controller
{
    public function model($model)
    {
        include_once __DIR__ . '/../Models/' . $model . '.php';
        return new $model;
    }
    public function view($view, $data = [])
    {
        include_once __DIR__ . '/../Views/' . $view . '.php';
    }

    /**
     * Định dạng ngày giờ để hiển thị theo múi giờ chính xác
     * @param string $datetime Chuỗi ngày giờ từ cơ sở dữ liệu
     * @param string $format Định dạng để hiển thị (tùy chọn)
     * @return string Ngày giờ đã định dạng
     */
    public function formatDate($datetime, $format = 'd/m/Y H:i:s')
    {
        if (empty($datetime)) {
            return $datetime;
        }

        // Use the timezone helper to format the date
        return TimezoneHelper::formatForDisplay($datetime, $format);
    }

    /**
     * Tạo URL tương đối với cơ sở ứng dụng
     * @param string $path Đường dẫn để thêm vào URL cơ sở
     * @return string URL hoàn chỉnh
     */
    public function url($path = '')
    {
        return UrlHelper::url($path);
    }
}
