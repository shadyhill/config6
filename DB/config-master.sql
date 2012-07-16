# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.14)
# Database: config_db
# Generation Time: 2012-07-16 20:17:18 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_logins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_logins`;

CREATE TABLE `admin_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `manager_id` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `user` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `pass` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `permission` int(2) DEFAULT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `admin_logins` WRITE;
/*!40000 ALTER TABLE `admin_logins` DISABLE KEYS */;

INSERT INTO `admin_logins` (`id`, `manager_id`, `user`, `pass`, `active`, `permission`, `stamp`)
VALUES
	(8,'shadyhill','shadyhill','3d4f29891a489c9eff2a4407253d44dd',1,1,'2012-05-29 09:29:17');

/*!40000 ALTER TABLE `admin_logins` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table form_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `form_fields`;

CREATE TABLE `form_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(11) DEFAULT NULL,
  `f_order` int(11) DEFAULT NULL,
  `type` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `name_id` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `placeholder` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `width` int(4) DEFAULT NULL,
  `class_override` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `style_override` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `dd_displays` text CHARACTER SET utf8 COMMENT 'select menu displays',
  `dd_values` text CHARACTER SET utf8 COMMENT 'select menu values',
  `has_hint` int(2) DEFAULT NULL,
  `hint_txt` text CHARACTER SET utf8,
  `has_error` int(2) DEFAULT NULL,
  `error_txt` text CHARACTER SET utf8,
  `stamp` date DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `form_fields` WRITE;
/*!40000 ALTER TABLE `form_fields` DISABLE KEYS */;

INSERT INTO `form_fields` (`id`, `form_id`, `f_order`, `type`, `label`, `name_id`, `placeholder`, `width`, `class_override`, `style_override`, `dd_displays`, `dd_values`, `has_hint`, `hint_txt`, `has_error`, `error_txt`, `stamp`, `updated`)
VALUES
	(1,1,1,'text','USERNAME','f_user','Manager Login',300,'',NULL,NULL,NULL,0,NULL,0,NULL,'2012-03-27','2012-03-27 21:46:56'),
	(2,1,2,'password','PASSWORD','f_pass','Password',300,NULL,NULL,NULL,NULL,0,NULL,0,NULL,'2012-03-27','2012-03-27 21:46:58');

/*!40000 ALTER TABLE `form_fields` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table forms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `forms`;

CREATE TABLE `forms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `method` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `onsubmit` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `class` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `button_txt` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `encoding` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `has_error_field` int(2) DEFAULT NULL,
  `stamp` date DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `forms` WRITE;
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;

INSERT INTO `forms` (`id`, `form_name`, `type`, `action`, `method`, `onsubmit`, `class`, `button_txt`, `encoding`, `has_error_field`, `stamp`, `updated`)
VALUES
	(1,'manager-login','AJAX','#manager-login','post','validateMLogin','managerForm','LOGIN',NULL,1,'2012-03-27','2012-03-29 10:34:26');

/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table manager_logins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `manager_logins`;

CREATE TABLE `manager_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `manager_id` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `user` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `pass` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `permission` int(2) DEFAULT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `manager_logins` WRITE;
/*!40000 ALTER TABLE `manager_logins` DISABLE KEYS */;

INSERT INTO `manager_logins` (`id`, `manager_id`, `user`, `pass`, `active`, `permission`, `stamp`)
VALUES
	(8,'shadyhill','shadyhill','9206ce98bef7160c4a5479032fac31df',1,1,'2012-06-15 14:46:55');

/*!40000 ALTER TABLE `manager_logins` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table page_css
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page_css`;

CREATE TABLE `page_css` (
  `page_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `css_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `css_order` int(3) DEFAULT NULL,
  `active` int(2) DEFAULT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `page_url` (`page_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table page_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page_data`;

CREATE TABLE `page_data` (
  `page_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `include_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `meta_keywords` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `meta_title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `active` int(2) NOT NULL DEFAULT '1',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `page_url` (`page_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `page_data` WRITE;
/*!40000 ALTER TABLE `page_data` DISABLE KEYS */;

INSERT INTO `page_data` (`page_name`, `page_url`, `include_file`, `type`, `template`, `meta_description`, `meta_keywords`, `meta_title`, `active`, `stamp`)
VALUES
	('Home Page','','PUBLIC/home-index.php','PUBLIC',NULL,'Charley and Emily','Charley and Emily','Charley and Emily',1,'2012-02-14 19:21:09'),
	('Manager Login','manager/login/','MANAGER/login.php','MANAGER',NULL,'','','Manager Login Page',1,'2012-03-22 12:49:41');

/*!40000 ALTER TABLE `page_data` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table page_js
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page_js`;

CREATE TABLE `page_js` (
  `page_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `js_file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `js_order` int(3) DEFAULT NULL,
  `active` int(2) DEFAULT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `page_url` (`page_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `page_js` WRITE;
/*!40000 ALTER TABLE `page_js` DISABLE KEYS */;

INSERT INTO `page_js` (`page_url`, `js_file`, `js_order`, `active`, `stamp`)
VALUES
	('manager/login/','MANAGER/jMLogin.js',1,1,'2012-05-28 15:11:22');

/*!40000 ALTER TABLE `page_js` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
