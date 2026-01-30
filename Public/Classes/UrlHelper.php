<?php
class UrlHelper
{
    /**
     * Get the base URL of the application
     * @return string The base URL
     */
    public static function baseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . $host;

        // Lấy tên script và xác định đường dẫn cơ sở
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);

        // Xử lý trường hợp ứng dụng nằm trong thư mục con
        if ($basePath !== '/') {
            $baseUrl .= $basePath;
        }

        // Đảm bảo URL cơ sở kết thúc bằng dấu gạch chéo
        if (substr($baseUrl, -1) !== '/') {
            $baseUrl .= '/';
        }

        return $baseUrl;
    }

    /**
     * Generate a URL relative to the application base
     * @param string $path The path to append to the base URL
     * @return string The complete URL
     */
    public static function url($path = '')
    {
        $baseUrl = self::baseUrl();

        // Loại bỏ dấu gạch chéo đầu tiên khỏi đường dẫn nếu có
        if (strlen($path) > 0 && $path[0] === '/') {
            $path = substr($path, 1);
        }

        // Nếu đường dẫn trống, trả về URL cơ sở
        if (empty($path)) {
            return $baseUrl;
        }

        return $baseUrl . $path;
    }

    /**
     * Get the current URL path without the base
     * @return string The current path
     */
    public static function getCurrentPath()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);

        // Loại bỏ thư mục script khỏi URI yêu cầu
        $relativePath = str_replace($scriptDir, '', $requestUri);

        // Loại bỏ các tham số truy vấn
        $relativePath = explode('?', $relativePath)[0];

        // Loại bỏ dấu gạch chéo đầu tiên
        if (strlen($relativePath) > 0 && $relativePath[0] === '/') {
            $relativePath = substr($relativePath, 1);
        }

        return $relativePath;
    }
}
