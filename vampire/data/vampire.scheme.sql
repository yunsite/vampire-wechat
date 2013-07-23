/*
SQLyog 企业版 - MySQL GUI v7.14 
MySQL - 5.0.45-community-nt : Database - wechatvampire
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_wechat` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `db_wechat`;

/*Table structure for table `t_vampire` */

DROP TABLE IF EXISTS `t_vampire`;

CREATE TABLE `t_vampire` (
  `id` bigint unsigned NOT NULL auto_increment COMMENT '具体游戏编号',
  `judge` varchar(40) NOT NULL default '' COMMENT '裁判微信名称',
  `totalNum` smallint unsigned  NOT NULL default '0' COMMENT '本局游戏的总人数',
  `finished` smallint unsigned  NOT NULL default '0' COMMENT '当前已经获得角色的人数',
  `keyWords` varchar(40) NOT NULL default '' COMMENT '人和鬼的关键词',
  `roleSquence` varchar(40) NOT NULL default '' COMMENT '人鬼序列的01代码',
  `roleInfo` varchar(4048) NOT NULL default '' COMMENT '当前玩家的关键词序列',
  `gameState` smallint unsigned NOT NULL default '0' COMMENT '本局游戏当前的状态,0',
  PRIMARY KEY  (`id`),
  key(`judge`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Table structure for table `t_vampireUser` */

DROP TABLE IF EXISTS `t_vampireUser`;

CREATE TABLE `t_vampireUser` (
  `id` bigint(40) unsigned NOT NULL auto_increment,
  `weChatName` varchar(40) default '' NOT NULL COMMENT '微信用户名',
  `nickName` varchar(40) default '' NOT NULL  COMMENT '游戏中的昵称',
  PRIMARY KEY  (`id`),
  UNIQUE key(`weChatName`),
  UNIQUE key(`nickName`)
) ENGINE=InnoDB AUTO_INCREMENT=1  DEFAULT CHARSET=utf8;

/*Table structure for table `t_vampireWords` */

DROP TABLE IF EXISTS `t_vampireWords`;

CREATE TABLE `t_vampireWords` (
  `id` bigint unsigned NOT NULL auto_increment COMMENT '关键词编号',
  `word` varchar(40) NOT NULL default '' COMMENT '关键词内容',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*DATA SQL */
