<?php
class Settings_m extends connectDB
{
    // Lấy giá trị cấu hình theo khóa, có fallback mặc định.
    public function Settings_get($config_key, $default_value = null)
    {
        $config_key = mysqli_real_escape_string($this->con, (string)$config_key);
        $sql = "SELECT config_value FROM settings WHERE config_key = '$config_key' LIMIT 1";
        $result = mysqli_query($this->con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return isset($row['config_value']) ? $row['config_value'] : $default_value;
        }

        return $default_value;
    }

    // Thêm mới hoặc cập nhật cấu hình.
    public function Settings_upsert($config_key, $config_value, $ma_user = null)
    {
        $config_key = mysqli_real_escape_string($this->con, (string)$config_key);
        $config_value = mysqli_real_escape_string($this->con, (string)$config_value);
        $safe_user = $ma_user !== null ? mysqli_real_escape_string($this->con, (string)$ma_user) : null;
        $safe_user_value = $safe_user !== null ? "'$safe_user'" : 'NULL';

        $sql = "INSERT INTO settings (config_key, config_value, description, updated_by, created_at, updated_at)
                VALUES ('$config_key', '$config_value', NULL, $safe_user_value, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    config_value = VALUES(config_value),
                    updated_by = VALUES(updated_by),
                    updated_at = NOW()";

        return mysqli_query($this->con, $sql);
    }
}
