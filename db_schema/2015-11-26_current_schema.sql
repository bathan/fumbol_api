# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: photolocal.pixable.com (MySQL 5.5.41-0ubuntu0.14.04.1)
# Database: fumbol
# Generation Time: 2015-11-26 14:37:31 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

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
  `match_player_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `match_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `match_team_id` int(11) unsigned DEFAULT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`match_player_id`),
  UNIQUE KEY `match_id` (`match_id`,`user_id`),
  KEY `created_date` (`created_date`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

LOCK TABLES `match_players` WRITE;
/*!40000 ALTER TABLE `match_players` DISABLE KEYS */;

INSERT INTO `match_players` (`match_player_id`, `match_id`, `user_id`, `match_team_id`, `confirmed`, `created_date`)
VALUES
	(17,5,1,39,0,'2015-11-25 17:02:32'),
	(26,5,2,38,0,'0000-00-00 00:00:00'),
	(27,5,3,39,0,'0000-00-00 00:00:00'),
	(29,5,4,38,0,'0000-00-00 00:00:00'),
	(30,5,5,38,0,'0000-00-00 00:00:00'),
	(31,5,6,38,0,'0000-00-00 00:00:00'),
	(32,5,7,39,0,'0000-00-00 00:00:00'),
	(33,5,8,39,0,'0000-00-00 00:00:00'),
	(34,5,9,39,0,'0000-00-00 00:00:00'),
	(35,5,10,38,0,'0000-00-00 00:00:00');

/*!40000 ALTER TABLE `match_players` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table match_teams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `match_teams`;

CREATE TABLE `match_teams` (
  `match_team_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `team_name` varchar(250) DEFAULT NULL,
  `match_id` int(11) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`match_team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

LOCK TABLES `match_teams` WRITE;
/*!40000 ALTER TABLE `match_teams` DISABLE KEYS */;

INSERT INTO `match_teams` (`match_team_id`, `team_name`, `match_id`, `created_date`)
VALUES
	(18,'',4,'2015-11-25 17:58:04'),
	(19,'',4,'2015-11-25 17:58:04'),
	(38,'',5,'2015-11-26 11:27:31'),
	(39,'',5,'2015-11-26 11:27:31');

/*!40000 ALTER TABLE `match_teams` ENABLE KEYS */;
UNLOCK TABLES;


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
  `status` enum('NO_MATCH','LFM','FULL') NOT NULL DEFAULT 'NO_MATCH',
  PRIMARY KEY (`match_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;

INSERT INTO `matches` (`match_id`, `match_date_time`, `venue_id`, `player_count`, `winning_team_id`, `tied`, `created_date`, `status`)
VALUES
	(3,'2015-11-24 22:15:00',1,0,0,NULL,'2015-11-24 12:49:21','LFM'),
	(4,'2015-11-25 22:15:00',1,0,0,NULL,'2015-11-25 12:33:43','LFM'),
	(5,'2015-11-26 23:00:00',2,0,0,NULL,'2015-11-26 10:12:40','LFM');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

LOCK TABLES `user_auth` WRITE;
/*!40000 ALTER TABLE `user_auth` DISABLE KEYS */;

INSERT INTO `user_auth` (`user_id`, `user_name`, `password`, `salt`, `last_successful_login`, `created_date`)
VALUES
	(1,'email@toti.com.ar','77a828910929c836fc33576ef9813856ce059367','MTQ0ODM4MDczMw==','2015-11-25 12:32:14','2015-11-24 12:58:53'),
	(2,'elbolsa@fumbol.com.ar','9d2dce93490d3b344e8eb70a66eaf946b434c268','MTQ0ODQ4MjkyOQ==',NULL,'2015-11-25 17:22:09'),
	(3,'maicena@fumbol.com.ar','83142fb9a6d52f32ed36e2fc41b87280e918b972','MTQ0ODQ4MjkzNg==',NULL,'2015-11-25 17:22:16'),
	(4,'frydel@fumbol.com.ar','137c278b35a85da14c52de7b1e90e2efea1df038','MTQ0ODQ4Mjk0Mw==',NULL,'2015-11-25 17:22:23'),
	(5,'chimi@fumbol.com.ar','d5e2e0d7628fbdcd60c138aafb1f34c60f13af84','MTQ0ODQ4Mjk0OA==',NULL,'2015-11-25 17:22:28'),
	(6,'fer@fumbol.com.ar','2fdaf992a999dcfb5744edc83ee0b983e887a5e4','MTQ0ODQ4Mjk2Mw==',NULL,'2015-11-25 17:22:43'),
	(7,'Javi@fumbol.com.ar','93d1a9736d91ea66bb894677d7a4bb5cd9b17745','MTQ0ODQ4Mjk2OA==',NULL,'2015-11-25 17:22:48'),
	(8,'Nico@fumbol.com.ar','c89c36f204df5fbc3a22925a97c2942096974059','MTQ0ODQ4Mjk5NA==',NULL,'2015-11-25 17:23:14'),
	(9,'Juanma@fumbol.com.ar','c67748b6e6cd012c7691e8bbbc591b1ad0322dc8','MTQ0ODQ4MzAwMA==',NULL,'2015-11-25 17:23:20'),
	(10,'chapas@fumbol.com.ar','428e40df99e7a3d7fa5a36e7aacbe0d0014f15a6','MTQ0ODQ4MzAwOA==','2015-11-25 17:25:14','2015-11-25 17:23:28');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`row_id`, `user_id`, `first_name`, `last_name`, `nickname`, `email`, `created_date`)
VALUES
	(1,1,'','','toti','email@toti.com.ar','2015-11-24 12:58:53'),
	(2,2,'','','elBolsa','elbolsa@fumbol.com.ar','2015-11-25 17:22:09'),
	(3,3,'','','maicena','maicena@fumbol.com.ar','2015-11-25 17:22:16'),
	(4,4,'','','frydel','frydel@fumbol.com.ar','2015-11-25 17:22:23'),
	(5,5,'','','chimi','chimi@fumbol.com.ar','2015-11-25 17:22:28'),
	(6,6,'','','fer','fer@fumbol.com.ar','2015-11-25 17:22:43'),
	(7,7,'','','Javi','Javi@fumbol.com.ar','2015-11-25 17:22:48'),
	(8,8,'','','Nico','Nico@fumbol.com.ar','2015-11-25 17:23:14'),
	(9,9,'','','Juanma','Juanma@fumbol.com.ar','2015-11-25 17:23:20'),
	(10,10,'','','chapas','chapas@fumbol.com.ar','2015-11-25 17:23:28');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
