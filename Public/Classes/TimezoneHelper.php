<?php
class TimezoneHelper
{
    private static $timezone = 'Asia/Ho_Chi_Minh'; // Vietnam timezone

    /**
     * Set the default timezone for the application
     */
    public static function setDefaultTimezone()
    {
        date_default_timezone_set(self::$timezone);
    }

    /**
     * Convert database datetime to user's local timezone
     * @param string $db_datetime DateTime from database
     * @param string $target_timezone Target timezone (defaults to application timezone)
     * @return string Converted datetime
     */
    public static function convertFromDB($db_datetime, $target_timezone = null)
    {
        if (empty($db_datetime)) {
            return $db_datetime;
        }

        if ($target_timezone === null) {
            $target_timezone = self::$timezone;
        }

        // Tạo đối tượng DateTime từ thời gian cơ sở dữ liệu (giả định là ở UTC hoặc múi giờ máy chủ cục bộ)
        $date = new DateTime($db_datetime);

        // Đặt múi giờ đích
        $date->setTimezone(new DateTimeZone($target_timezone));

        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Convert user input datetime to database format
     * @param string $user_datetime DateTime from user input
     * @param string $source_timezone Source timezone (defaults to application timezone)
     * @return string Converted datetime for database
     */
    public static function convertToDB($user_datetime, $source_timezone = null)
    {
        if (empty($user_datetime)) {
            return $user_datetime;
        }

        if ($source_timezone === null) {
            $source_timezone = self::$timezone;
        }

        // Tạo đối tượng DateTime với múi giờ của người dùng
        $date = new DateTime($user_datetime, new DateTimeZone($source_timezone));

        // Chuyển đổi sang UTC hoặc bất kỳ gì cơ sở dữ liệu mong đợi
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Format datetime for display
     * @param string $datetime DateTime string
     * @param string $format Output format
     * @return string Formatted datetime
     */
    public static function formatForDisplay($datetime, $format = 'd/m/Y H:i:s')
    {
        if (empty($datetime)) {
            return $datetime;
        }

        $date = new DateTime($datetime);
        return $date->format($format);
    }

    /**
     * Get current time in application timezone
     * @return string Current datetime
     */
    public static function getCurrentTime()
    {
        return date('Y-m-d H:i:s');
    }
}
