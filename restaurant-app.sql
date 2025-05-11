-- Adminer 4.8.1 MySQL 10.11.11-MariaDB-0+deb12u1-log dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_gallery` tinyint(4) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `menu_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `id_menu_category` int(11) DEFAULT NULL,
  `menu_type` enum('daily_menu','menu','drinks') DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_upper_category` (`id_menu_category`),
  CONSTRAINT `menu_category_ibfk_1` FOREIGN KEY (`id_menu_category`) REFERENCES `menu_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `menu_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu_category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `id_image` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_menu_category` (`id_menu_category`),
  KEY `id_image` (`id_image`),
  CONSTRAINT `menu_item_ibfk_8` FOREIGN KEY (`id_menu_category`) REFERENCES `menu_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `menu_item_ibfk_9` FOREIGN KEY (`id_image`) REFERENCES `image` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `menu_item_variant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu_item` int(11) NOT NULL,
  `size` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_menu_item` (`id_menu_item`),
  CONSTRAINT `menu_item_variant_ibfk_2` FOREIGN KEY (`id_menu_item`) REFERENCES `menu_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `is_shown` tinyint(4) DEFAULT NULL,
  `id_image` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_image` (`id_image`),
  CONSTRAINT `news_ibfk_5` FOREIGN KEY (`id_image`) REFERENCES `image` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `opening_hours` (
  `id` int(11) NOT NULL,
  `day` varchar(255) NOT NULL,
  `opening_hour` varchar(20) DEFAULT NULL,
  `closing_hour` varchar(20) DEFAULT NULL,
  `is_closed` tinyint(4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `password_reset_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `reservation_date` datetime NOT NULL,
  `guest_count` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `is_new` tinyint(4) DEFAULT 1,
  `email_send` tinyint(4) DEFAULT NULL,
  `last_user` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `restaurant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `sentence` varchar(255) DEFAULT NULL,
  `about_us` text DEFAULT NULL,
  `ico` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_send` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `tripadvisor` varchar(255) DEFAULT NULL,
  `id_image` int(11) DEFAULT NULL,
  `id_image_events` int(11) DEFAULT NULL,
  `id_image_about_us` int(11) DEFAULT NULL,
  `id_image_about_us_in` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_image` (`id_image`),
  KEY `id_image_events` (`id_image_events`),
  KEY `id_image_about_us` (`id_image_about_us`),
  KEY `id_image_about_us_in` (`id_image_about_us_in`),
  CONSTRAINT `restaurant_ibfk_2` FOREIGN KEY (`id_image`) REFERENCES `image` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `restaurant_ibfk_6` FOREIGN KEY (`id_image_events`) REFERENCES `image` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `restaurant_ibfk_7` FOREIGN KEY (`id_image_about_us_in`) REFERENCES `image` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `restaurant_ibfk_8` FOREIGN KEY (`id_image_about_us`) REFERENCES `image` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


CREATE TABLE `web_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_shown` tinyint(4) DEFAULT NULL,
  `href` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


-- 2025-05-11 14:12:12
