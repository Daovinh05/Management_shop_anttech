-- =========================================================
-- Cấu hình tự động hủy đơn quá hạn
-- File chạy riêng trên MySQL/MariaDB
-- =========================================================

START TRANSACTION;

CREATE TABLE IF NOT EXISTS `settings` (
  `config_key` varchar(100) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_by` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`config_key`),
  KEY `idx_settings_updated_by` (`updated_by`),
  CONSTRAINT `fk_settings_updated_by_users`
    FOREIGN KEY (`updated_by`) REFERENCES `users` (`ma_user`)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Neu da tung dung key theo gio, quy doi sang phut de test nhanh.
SET @old_hours := (
  SELECT CAST(config_value AS UNSIGNED)
  FROM settings
  WHERE config_key = 'order_timeout_hours'
  LIMIT 1
);

-- Mac dinh de test la 15 phut.
SET @default_minutes := 15;
SET @resolved_minutes := IF(@old_hours IS NULL OR @old_hours <= 0, @default_minutes, @old_hours * 60);

INSERT INTO `settings` (`config_key`, `config_value`, `description`, `updated_by`)
VALUES ('order_timeout_minutes', CAST(@resolved_minutes AS CHAR), 'So phut toi da cho don hang o trang thai cho_duyet', NULL)
ON DUPLICATE KEY UPDATE
  `config_value` = VALUES(`config_value`),
  `description` = VALUES(`description`),
  `updated_by` = VALUES(`updated_by`),
  `updated_at` = NOW();

COMMIT;
