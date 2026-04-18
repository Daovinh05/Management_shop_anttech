-- Chatbot history persistence for TechZone
-- Run this script on banhang database

CREATE TABLE IF NOT EXISTS `chat_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_code` varchar(64) NOT NULL,
  `ma_user` varchar(20) DEFAULT NULL,
  `guest_token` varchar(64) DEFAULT NULL,
  `status` enum('active','closed') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_conversation_code` (`conversation_code`),
  KEY `idx_chat_conv_user` (`ma_user`),
  KEY `idx_chat_conv_guest` (`guest_token`),
  KEY `idx_chat_conv_status` (`status`),
  CONSTRAINT `fk_chat_conv_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `ma_user` varchar(20) DEFAULT NULL,
  `sender` enum('user','bot') NOT NULL,
  `message` text NOT NULL,
  `intent` varchar(64) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_chat_msg_conv` (`conversation_id`),
  KEY `idx_chat_msg_user` (`ma_user`),
  KEY `idx_chat_msg_created` (`created_at`),
  CONSTRAINT `fk_chat_msg_conv` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_chat_msg_user` FOREIGN KEY (`ma_user`) REFERENCES `users` (`ma_user`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
