-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.18-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for nezka
CREATE DATABASE IF NOT EXISTS `nezka` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `nezka`;

-- Dumping structure for table nezka.auth_object
CREATE TABLE IF NOT EXISTS `auth_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menu_active` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `father_id` int(11) DEFAULT NULL,
  `location` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `icon` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- Dumping data for table nezka.auth_object: ~14 rows (approximately)
DELETE FROM `auth_object`;
/*!40000 ALTER TABLE `auth_object` DISABLE KEYS */;
INSERT INTO `auth_object` (`id`, `name`, `menu_active`, `description`, `father_id`, `location`, `position`, `icon`, `status`) VALUES
	(1, 'Usuarios del Sistema', 'auth_users', 'auth_users', 0, 'auth_users', 1, NULL, 1),
	(2, 'Marcas', 'brands', 'brands', 0, 'brands', 3, NULL, 1),
	(3, 'Opciones', 'options', 'options', 0, 'options', 4, NULL, 1),
	(4, 'Incidentes', 'incidents', 'incidents', 0, 'incidents', 5, NULL, 1),
	(5, 'Adicionales', 'extras', 'extras', 0, 'extras', 6, NULL, 1),
	(6, 'Reportes', 'reports', 'reports', 0, 'reports', 7, NULL, 1),
	(7, 'Tiendas', 'stores', 'stores', 0, 'stores', 9, NULL, 1),
	(8, 'Categorías', 'product_categories', 'product_categories', 0, 'product_categories', 10, NULL, 1),
	(9, 'Mapa', 'map', 'map', 0, 'map', 11, NULL, 1),
	(10, 'Productos', 'products', 'products', 0, 'products', 12, NULL, 1),
	(11, 'Cadenas', 'chain', 'chain', 0, 'chain', 8, NULL, 1),
	(12, 'Roles', 'auth_role', 'auth_role', 0, 'auth_role', 2, NULL, 1),
	(13, 'Mercaderistas', 'merchant', 'merchant', 0, 'merchant', 14, NULL, 1),
	(14, 'Supervisores', 'supervisor', 'supervisor', 0, 'supervisor', 13, NULL, 1);
/*!40000 ALTER TABLE `auth_object` ENABLE KEYS */;

-- Dumping structure for table nezka.auth_role
CREATE TABLE IF NOT EXISTS `auth_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `app` tinyint(3) NOT NULL DEFAULT '0',
  `cms` tinyint(3) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.auth_role: ~4 rows (approximately)
DELETE FROM `auth_role`;
/*!40000 ALTER TABLE `auth_role` DISABLE KEYS */;
INSERT INTO `auth_role` (`id`, `name`, `status`, `app`, `cms`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
	(1, 'Administrador', 1, 0, 1, '2018-03-19 10:57:59', '2018-03-19 19:50:35', 0, 6),
	(2, 'Mercaderista', 1, 1, 1, '2018-03-19 10:58:18', '2018-03-19 19:50:45', 0, 6),
	(3, 'Supervisor', 1, 1, 1, '2018-03-19 10:58:09', '2018-03-19 19:50:50', 0, 6),
	(4, 'Operaciones', 1, 1, 1, '2018-03-19 19:41:57', '2018-03-19 19:50:55', 6, 6);
/*!40000 ALTER TABLE `auth_role` ENABLE KEYS */;

-- Dumping structure for table nezka.auth_role_object
CREATE TABLE IF NOT EXISTS `auth_role_object` (
  `role_id` int(11) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `permission` enum('A','W','R') COLLATE utf8_unicode_ci DEFAULT 'A',
  KEY `FK_auth_user_object_auth_user` (`role_id`),
  KEY `FK_auth_user_object_auth_object` (`object_id`),
  CONSTRAINT `FK_auth_role_object_auth_role` FOREIGN KEY (`role_id`) REFERENCES `auth_role` (`id`),
  CONSTRAINT `FK_auth_user_object_auth_object` FOREIGN KEY (`object_id`) REFERENCES `auth_object` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- Dumping data for table nezka.auth_role_object: ~14 rows (approximately)
DELETE FROM `auth_role_object`;
/*!40000 ALTER TABLE `auth_role_object` DISABLE KEYS */;
INSERT INTO `auth_role_object` (`role_id`, `object_id`, `permission`) VALUES
	(1, 1, 'A'),
	(1, 12, 'A'),
	(1, 2, 'A'),
	(1, 3, 'A'),
	(1, 4, 'A'),
	(1, 5, 'A'),
	(1, 6, 'A'),
	(1, 11, 'A'),
	(1, 7, 'A'),
	(1, 8, 'A'),
	(1, 9, 'A'),
	(1, 10, 'A'),
	(1, 13, 'A'),
	(1, 14, 'A');
/*!40000 ALTER TABLE `auth_role_object` ENABLE KEYS */;

-- Dumping structure for table nezka.auth_user
CREATE TABLE IF NOT EXISTS `auth_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `user` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- Dumping data for table nezka.auth_user: ~9 rows (approximately)
DELETE FROM `auth_user`;
/*!40000 ALTER TABLE `auth_user` DISABLE KEYS */;
INSERT INTO `auth_user` (`id`, `role_id`, `user`, `password`, `first_name`, `last_name`, `email`, `status`, `created_at`, `created_by`, `updated_by`, `updated_at`, `last_at`) VALUES
	(6, 1, 'admin', 'admin', 'admin', 'admin', 'admin@ideamultumedia.net', 1, '2018-03-06 15:26:42', 1, 6, '2018-04-19 21:26:28', '2018-04-19 21:26:28'),
	(11, 2, '45722618', '3fxi', 'Luis', 'Valdivia', 'luis+00891@ideamultimedia.net', 1, '2018-03-21 22:59:07', 6, NULL, '2018-04-01 23:52:58', NULL),
	(12, 3, '07460057', 'abc123def', 'Luis', 'Valdivia', 'valdivialuis1989@gmail.com', 1, '2018-03-23 19:46:43', 6, NULL, '2018-03-23 19:46:57', NULL),
	(13, 3, '12345678', 'abc123def', 'Otro supervisor', 'Otro supervisor', 'luis@ideamultimedia.net', 1, '2018-03-23 21:50:43', 6, NULL, '2018-03-23 21:52:09', NULL),
	(16, 2, '00899888', 'abc123def', 'Eduardo', 'Aspillaga', 'eduardo@ideamultimedia.net', 1, '2018-04-01 17:15:47', 6, NULL, '2018-04-01 17:15:26', NULL),
	(17, 2, '34465566', 'abc123def', 'Richard', 'Rondan', 'richard@ideamultimedia.net', 1, '2018-04-01 17:16:51', 6, NULL, '2018-04-01 17:16:30', NULL),
	(18, 2, '09900099', 'pum2018', 'Abel', 'Balbuena', 'abelb@pum.pe', 1, '2018-04-01 22:41:32', 6, NULL, '2018-04-01 22:43:00', NULL),
	(20, 2, '10299194', 'admin', 'Eduardo2', 'aspillaga2', 'eduardo@orbemnetworks.com', 1, '2018-04-02 21:21:22', 6, NULL, '2018-04-04 23:12:42', NULL),
	(21, 2, '87654321', 'admin', 'Eduardo3', 'aspillaga3', 'ejaa@hotmail.com', 1, '2018-04-02 21:56:05', 6, NULL, '2018-04-02 21:59:39', NULL);
/*!40000 ALTER TABLE `auth_user` ENABLE KEYS */;

-- Dumping structure for table nezka.blog
CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `subtitle` varchar(150) NOT NULL,
  `image` varchar(150) NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.blog: ~0 rows (approximately)
DELETE FROM `blog`;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;

-- Dumping structure for table nezka.client
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `image` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.client: ~0 rows (approximately)
DELETE FROM `client`;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
/*!40000 ALTER TABLE `client` ENABLE KEYS */;

-- Dumping structure for table nezka.contact_us
CREATE TABLE IF NOT EXISTS `contact_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `mail` varchar(150) NOT NULL,
  `bussiness_name` varchar(150) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `client_type` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.contact_us: ~0 rows (approximately)
DELETE FROM `contact_us`;
/*!40000 ALTER TABLE `contact_us` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_us` ENABLE KEYS */;

-- Dumping structure for table nezka.customer_type
CREATE TABLE IF NOT EXISTS `customer_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `slug` varchar(50) DEFAULT NULL,
  `image` longtext,
  `description` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.customer_type: ~0 rows (approximately)
DELETE FROM `customer_type`;
/*!40000 ALTER TABLE `customer_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_type` ENABLE KEYS */;

-- Dumping structure for table nezka.project
CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `client_id` int(11) NOT NULL,
  `subtitle` varchar(200) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `video` varchar(100) NOT NULL,
  `has_video` tinyint(4) NOT NULL DEFAULT '0',
  `description` longtext NOT NULL,
  `type_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.project: ~0 rows (approximately)
DELETE FROM `project`;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;

-- Dumping structure for table nezka.project_images
CREATE TABLE IF NOT EXISTS `project_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `type` enum('small','long','gallery') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.project_images: ~0 rows (approximately)
DELETE FROM `project_images`;
/*!40000 ALTER TABLE `project_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_images` ENABLE KEYS */;

-- Dumping structure for table nezka.project_type
CREATE TABLE IF NOT EXISTS `project_type` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.project_type: ~0 rows (approximately)
DELETE FROM `project_type`;
/*!40000 ALTER TABLE `project_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_type` ENABLE KEYS */;

-- Dumping structure for table nezka.services
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` longtext,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.services: ~0 rows (approximately)
DELETE FROM `services`;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;

-- Dumping structure for table nezka.social_media
CREATE TABLE IF NOT EXISTS `social_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(150) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table nezka.social_media: ~0 rows (approximately)
DELETE FROM `social_media`;
/*!40000 ALTER TABLE `social_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_media` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
