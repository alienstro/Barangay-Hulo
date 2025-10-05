-- Add OTP verification table
CREATE TABLE IF NOT EXISTS `otp_verification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add is_verified column to users table
ALTER TABLE `users` 
ADD COLUMN `is_verified` tinyint(1) DEFAULT 0 AFTER `email`,
ADD COLUMN `verification_token` varchar(255) DEFAULT NULL AFTER `is_verified`;

-- Add index for faster queries
ALTER TABLE `users` ADD INDEX `idx_verification` (`is_verified`);
