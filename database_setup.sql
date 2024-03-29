SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `leberkasrechner`;

CREATE TABLE `author` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `weblink` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `blog_posts` (
  `id` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `header` text NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `butchers` (
  `id` bigint NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DELIMITER $$
CREATE TRIGGER `butchers_after_delete` AFTER DELETE ON `butchers` FOR EACH ROW BEGIN
  INSERT INTO butchers_log (table_name, change_type, butcher_id, old_value)
  VALUES ('butchers', 'DELETE', OLD.id, CONCAT('Lat: ', OLD.lat, ', Lon: ', OLD.lon, ', Tags: ', OLD.tags));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `butchers_after_insert` AFTER INSERT ON `butchers` FOR EACH ROW BEGIN
  INSERT INTO butchers_log (table_name, change_type, butcher_id, new_value)
  VALUES ('butchers', 'INSERT', NEW.id, CONCAT('Lat: ', NEW.lat, ', Lon: ', NEW.lon, ', Tags: ', NEW.tags));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `butchers_after_update` AFTER UPDATE ON `butchers` FOR EACH ROW BEGIN
  IF NOT (NEW.lat <=> OLD.lat AND NEW.lon <=> OLD.lon AND NEW.tags <=> OLD.tags) THEN
    INSERT INTO butchers_log (table_name, change_type, butcher_id, old_value, new_value)
    VALUES ('butchers', 'UPDATE', NEW.id, CONCAT('Lat: ', OLD.lat, ', Lon: ', OLD.lon, ', Tags: ', OLD.tags), CONCAT('Lat: ', NEW.lat, ', Lon: ', NEW.lon, ', Tags: ', NEW.tags));
  END IF;
END
$$
DELIMITER ;

CREATE TABLE `butchers_log` (
  `id` int NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `change_type` varchar(50) NOT NULL,
  `change_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `butcher_id` bigint DEFAULT NULL,
  `old_value` text,
  `new_value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
DELIMITER $$
CREATE TRIGGER `image_after_delete` AFTER DELETE ON `image` FOR EACH ROW BEGIN
  INSERT INTO `image_log` (id, created_at, modified_at, filename, name, description, date, date_sort, author, license, action)
  VALUES (OLD.id, OLD.created_at, NOW(), OLD.filename, OLD.name, OLD.description, OLD.date, OLD.date_sort, OLD.author, OLD.license, 'DELETE');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `image_after_insert` AFTER INSERT ON `image` FOR EACH ROW BEGIN
  INSERT INTO `image_log` (id, created_at, modified_at, filename, name, description, date, date_sort, author, license, action)
  VALUES (NEW.id, NEW.created_at, NEW.modified, NEW.filename, NEW.name, NEW.description, NEW.date, NEW.date_sort, NEW.author, NEW.license, 'INSERT');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `image_after_update` AFTER UPDATE ON `image` FOR EACH ROW BEGIN
  INSERT INTO `image_log` (id, created_at, modified_at, filename, name, description, date, date_sort, author, license, action)
  VALUES (OLD.id, OLD.created_at, NOW(), OLD.filename, OLD.name, OLD.description, OLD.date, OLD.date_sort, OLD.author, OLD.license, 'UPDATE');
END
$$
DELIMITER ;

CREATE TABLE `image_butcher` (
  `id` int NOT NULL,
  `image` int NOT NULL,
  `butcher` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `image_log` (
  `id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `date` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `date_sort` date DEFAULT NULL,
  `author` int DEFAULT NULL,
  `license` int DEFAULT NULL,
  `action` varchar(10) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `license` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `weblink` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edit` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DELIMITER $$
CREATE TRIGGER `users_after_delete` AFTER DELETE ON `users` FOR EACH ROW BEGIN
  INSERT INTO users_log (table_name, change_type, user_id, old_value)
  VALUES ('users', 'DELETE', OLD.id, CONCAT('Username: ', OLD.username, ', Email: ', OLD.email, ', Password: ', OLD.password, ', Edit: ', OLD.edit, ', Admin: ', OLD.admin));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_after_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
  INSERT INTO users_log (table_name, change_type, user_id, new_value)
  VALUES ('users', 'INSERT', NEW.id, CONCAT('Username: ', NEW.username, ', Email: ', NEW.email, ', Password: ', NEW.password, ', Edit: ', NEW.edit, ', Admin: ', NEW.admin));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_after_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
  INSERT INTO users_log (table_name, change_type, user_id, old_value, new_value)
  VALUES ('users', 'UPDATE', NEW.id, CONCAT('Username: ', OLD.username, ', Email: ', OLD.email, ', Password: ', OLD.password, ', Edit: ', OLD.edit, ', Admin: ', OLD.admin), CONCAT('Username: ', NEW.username, ', Email: ', NEW.email, ', Password: ', NEW.password, ', Edit: ', NEW.edit, ', Admin: ', NEW.admin));
END
$$
DELIMITER ;

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

CREATE USER 'lview'@'localhost' IDENTIFIED BY 'JbMyvjuaJgMYyVhK5RcEuaGGKDof65CB8atFkLW2Euwu3PnLzPv2GhCBrBnDXU6W';
GRANT USAGE ON *.* TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.author TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.license TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.image TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.image_butcher TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.butchers TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.blog_posts TO 'lview'@'localhost';
GRANT SELECT (`id`, `edit`, `admin`) ON `leberkasrechner`.`users` TO 'lview'@'localhost'; 
ALTER USER 'lview'@'localhost' ;

CREATE USER 'lusercreate'@'localhost' IDENTIFIED BY 'Cj4xtm8SaZe5sNnnE2DbXRay92i47TTum5UtJKHFLDbipDAfpMH9tFL2bRuYS2Kg';
GRANT INSERT ON leberkasrechner.users TO 'lusercreate'@'localhost';
GRANT CREATE USER ON *.* TO 'lusercreate'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.butchers TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image_butcher TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image_butcher TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.license TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.blog_posts TO 'lview'@'localhost' WITH GRANT OPTION;
GRANT SELECT (id, username, email, edit, admin) ON leberkasrechner.users TO lusercreate@localhost WITH GRANT OPTION; 
ALTER USER lusercreate@localhost ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
