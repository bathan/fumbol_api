# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: photolocal.pixable.com (MySQL 5.5.41-0ubuntu0.14.04.1)
# Database: fumbol
# Generation Time: 2015-10-01 18:32:21 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table book
# ------------------------------------------------------------

DROP TABLE IF EXISTS `book`;

CREATE TABLE `book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int(11) unsigned DEFAULT NULL,
  `price` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;

INSERT INTO `book` (`id`, `title`, `rating`, `price`)
VALUES
	(1,'Learn to Program',10,29.99);

/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`group_id`, `name`, `description`, `created_date`)
VALUES
	(1,'fumbol','fumbol','2015-09-29 12:12:12'),
	(7,'Cebollitas2','asasasa2323','2015-09-29 15:30:11'),
	(9,'Cebollitas22','asasasa2323','2015-09-29 15:37:56');

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table match_players
# ------------------------------------------------------------

DROP TABLE IF EXISTS `match_players`;

CREATE TABLE `match_players` (
  `match_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table match_teams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `match_teams`;

CREATE TABLE `match_teams` (
  `match_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `match_team_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table matches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `matches`;

CREATE TABLE `matches` (
  `match_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `match_date_time` datetime NOT NULL,
  `venue_id` int(11) NOT NULL,
  `player_count` int(11) NOT NULL,
  `winning_team_id` int(11) NOT NULL,
  `tied` bit(1) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;

INSERT INTO `matches` (`match_id`, `match_date_time`, `venue_id`, `player_count`, `winning_team_id`, `tied`, `created_date`)
VALUES
	(1,'2015-10-01 23:00:00',2,0,0,NULL,'2015-10-01 15:30:43');

/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_auth
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_auth`;

CREATE TABLE `user_auth` (
  `user_id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(320) NOT NULL DEFAULT '',
  `password` varchar(320) NOT NULL DEFAULT '',
  `salt` varchar(50) NOT NULL DEFAULT '',
  `last_successful_login` datetime DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user_auth` WRITE;
/*!40000 ALTER TABLE `user_auth` DISABLE KEYS */;

INSERT INTO `user_auth` (`user_id`, `user_name`, `password`, `salt`, `last_successful_login`, `created_date`)
VALUES
	(1,'bathan@gmail.com','ccfe1701798f663620a55005fabdbd4b6a8c6113','MTQ0MzYzNDU4OA==','2015-09-30 15:00:35','2015-09-30 14:36:28');

/*!40000 ALTER TABLE `user_auth` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_groups`;

CREATE TABLE `user_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `row_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`row_id`, `user_id`, `first_name`, `last_name`, `nickname`, `email`, `created_date`)
VALUES
	(1,1,'','','','bathan@gmail.com','2015-09-30 14:36:28');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
