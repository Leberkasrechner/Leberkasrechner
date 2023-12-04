-- Leberkasrechner Database Setup
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
DROP DATABASE IF EXISTS `leberkasrechner`;
CREATE DATABASE IF NOT EXISTS `leberkasrechner` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `leberkasrechner`;
DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `weblink` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `header` text NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `butchers`;
CREATE TABLE `butchers` (
  `id` bigint NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
DROP TRIGGER IF EXISTS `butchers_after_delete`;
DELIMITER $$
CREATE TRIGGER `butchers_after_delete` AFTER DELETE ON `butchers` FOR EACH ROW BEGIN
  INSERT INTO butchers_log (table_name, change_type, butcher_id, old_value)
  VALUES ('butchers', 'DELETE', OLD.id, CONCAT('Lat: ', OLD.lat, ', Lon: ', OLD.lon, ', Tags: ', OLD.tags));
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `butchers_after_insert`;
DELIMITER $$
CREATE TRIGGER `butchers_after_insert` AFTER INSERT ON `butchers` FOR EACH ROW BEGIN
  INSERT INTO butchers_log (table_name, change_type, butcher_id, new_value)
  VALUES ('butchers', 'INSERT', NEW.id, CONCAT('Lat: ', NEW.lat, ', Lon: ', NEW.lon, ', Tags: ', NEW.tags));
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `butchers_after_update`;
DELIMITER $$
CREATE TRIGGER `butchers_after_update` AFTER UPDATE ON `butchers` FOR EACH ROW BEGIN
  IF NOT (NEW.lat <=> OLD.lat AND NEW.lon <=> OLD.lon AND NEW.tags <=> OLD.tags) THEN
    INSERT INTO butchers_log (table_name, change_type, butcher_id, old_value, new_value)
    VALUES ('butchers', 'UPDATE', NEW.id, CONCAT('Lat: ', OLD.lat, ', Lon: ', OLD.lon, ', Tags: ', OLD.tags), CONCAT('Lat: ', NEW.lat, ', Lon: ', NEW.lon, ', Tags: ', NEW.tags));
  END IF;
END
$$
DELIMITER ;
DROP TABLE IF EXISTS `butchers_log`;
CREATE TABLE `butchers_log` (
  `id` int NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `change_type` varchar(50) NOT NULL,
  `change_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `butcher_id` bigint DEFAULT NULL,
  `old_value` text,
  `new_value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `date` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `date_sort` date DEFAULT NULL,
  `author` int DEFAULT NULL,
  `license` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
DROP TABLE IF EXISTS `image_butcher`;
CREATE TABLE `image_butcher` (
  `id` int NOT NULL,
  `image` int DEFAULT NULL,
  `butcher` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
DROP TABLE IF EXISTS `license`;
CREATE TABLE `license` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `weblink` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edit` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TRIGGER IF EXISTS `users_after_delete`;
DELIMITER $$
CREATE TRIGGER `users_after_delete` AFTER DELETE ON `users` FOR EACH ROW BEGIN
  INSERT INTO users_log (table_name, change_type, user_id, old_value)
  VALUES ('users', 'DELETE', OLD.id, CONCAT('Username: ', OLD.username, ', Email: ', OLD.email, ', Password: ', OLD.password, ', Edit: ', OLD.edit, ', Admin: ', OLD.admin));
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `users_after_insert`;
DELIMITER $$
CREATE TRIGGER `users_after_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
  INSERT INTO users_log (table_name, change_type, user_id, new_value)
  VALUES ('users', 'INSERT', NEW.id, CONCAT('Username: ', NEW.username, ', Email: ', NEW.email, ', Password: ', NEW.password, ', Edit: ', NEW.edit, ', Admin: ', NEW.admin));
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `users_after_update`;
DELIMITER $$
CREATE TRIGGER `users_after_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
  INSERT INTO users_log (table_name, change_type, user_id, old_value, new_value)
  VALUES ('users', 'UPDATE', NEW.id, CONCAT('Username: ', OLD.username, ', Email: ', OLD.email, ', Password: ', OLD.password, ', Edit: ', OLD.edit, ', Admin: ', OLD.admin), CONCAT('Username: ', NEW.username, ', Email: ', NEW.email, ', Password: ', NEW.password, ', Edit: ', NEW.edit, ', Admin: ', NEW.admin));
END
$$
DELIMITER ;
DROP TABLE IF EXISTS `users_log`;
CREATE TABLE `users_log` (
  `id` int NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `blog_posts`
  ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `butchers`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `butchers` ADD FULLTEXT KEY `fulltext_tags` (`tags`);
ALTER TABLE `butchers_log`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bild_FK_1` (`license`),
  ADD KEY `bild_FK_2` (`author`);
ALTER TABLE `image_butcher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_butcher_FK_1` (`image`),
  ADD KEY `image_butcher_FK_2` (`butcher`);
ALTER TABLE `license`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users_log`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `butchers`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
ALTER TABLE `butchers_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `image_butcher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `license`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `users_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`author`) REFERENCES `author` (`id`),
  ADD CONSTRAINT `image_ibfk_2` FOREIGN KEY (`license`) REFERENCES `license` (`id`);
ALTER TABLE `image_butcher`
  ADD CONSTRAINT `image_butcher_ibfk_1` FOREIGN KEY (`image`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `image_butcher_ibfk_2` FOREIGN KEY (`butcher`) REFERENCES `butchers` (`id`);
COMMIT;
