/*
SQLyog Community v9.30 
MySQL - 5.5.17 : Database - gnhq
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
USE `gnhq`;

/*Table structure for table `election-preffix_region` */

DROP TABLE IF EXISTS `election-preffix_region`;

CREATE TABLE `election-preffix_region` (
  `RegionNum` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `Population` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`RegionNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_result` */

DROP TABLE IF EXISTS `election-preffix_result`;

CREATE TABLE `election-preffix_result` (
  `IkFullName` varchar(100) NOT NULL,
  `IkType` varchar(5) NOT NULL,
  `ResultType` varchar(5) NOT NULL,
  `ClaimCount` int(11) DEFAULT '0',
  `ProjectId` varchar(100) DEFAULT NULL,
  `UpdateTime` datetime DEFAULT NULL,
  `SignTime` datetime DEFAULT NULL,
  `LoadTime` datetime DEFAULT NULL,
  `Dirt` smallint(6) DEFAULT NULL,
  `HasCopy` smallint(6) DEFAULT NULL,
  `Revised` smallint(6) DEFAULT NULL,
  `Line1` int(11) NOT NULL,
  KEY `ResultType` (`ResultType`),
  KEY `ik` (`IkType`,`IkFullName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_result_copy` */

DROP TABLE IF EXISTS `election-preffix_result_copy`;

CREATE TABLE `election-preffix_result_copy` (
  `IkFullName` varchar(100) NOT NULL,
  `IkType` varchar(5) NOT NULL,
  `ResultType` varchar(5) NOT NULL,
  `ClaimCount` int(11) DEFAULT '0',
  `ProjectId` varchar(100) DEFAULT NULL,
  `UpdateTime` datetime DEFAULT NULL,
  `SignTime` datetime DEFAULT NULL,
  `LoadTime` datetime DEFAULT NULL,
  `Dirt` smallint(6) DEFAULT NULL,
  `HasCopy` smallint(6) DEFAULT NULL,
  `Revised` smallint(6) DEFAULT NULL,
  `Line1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_result_of` */

DROP TABLE IF EXISTS `election-preffix_result_of`;

CREATE TABLE `election-preffix_result_of` (
  `IkFullName` varchar(100) NOT NULL,
  `IkType` varchar(5) NOT NULL,
  `ResultType` varchar(5) NOT NULL,
  `ClaimCount` int(11) DEFAULT '0',
  `ProjectId` varchar(100) DEFAULT NULL,
  `UpdateTime` datetime DEFAULT NULL,
  `SignTime` datetime DEFAULT NULL,
  `LoadTime` datetime DEFAULT NULL,
  `Dirt` smallint(6) DEFAULT NULL,
  `HasCopy` smallint(6) DEFAULT NULL,
  `Revised` smallint(6) DEFAULT NULL,
  `Line1` int(11) NOT NULL,
  KEY `ResultType` (`ResultType`),
  KEY `ik` (`IkType`,`IkFullName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_result_of_copy` */

DROP TABLE IF EXISTS `election-preffix_result_of_copy`;

CREATE TABLE `election-preffix_result_of_copy` (
  `IkFullName` varchar(100) NOT NULL,
  `IkType` varchar(5) NOT NULL,
  `ResultType` varchar(5) NOT NULL,
  `ClaimCount` int(11) DEFAULT '0',
  `ProjectId` varchar(100) DEFAULT NULL,
  `UpdateTime` datetime DEFAULT NULL,
  `SignTime` datetime DEFAULT NULL,
  `LoadTime` datetime DEFAULT NULL,
  `Dirt` smallint(6) DEFAULT NULL,
  `HasCopy` smallint(6) DEFAULT NULL,
  `Revised` smallint(6) DEFAULT NULL,
  `Line1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_tik` */

DROP TABLE IF EXISTS `election-preffix_tik`;

CREATE TABLE `election-preffix_tik` (
  `RegionNum` int(11) NOT NULL,
  `TikNum` int(11) NOT NULL,
  `FullName` varchar(200) NOT NULL,
  `OkrugName` varchar(200) DEFAULT NULL,
  `Link` text,
  PRIMARY KEY (`RegionNum`,`TikNum`),
  KEY `OkrugName` (`OkrugName`),
  KEY `RegionNum` (`RegionNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_uik` */

DROP TABLE IF EXISTS `election-preffix_uik`;

CREATE TABLE `election-preffix_uik` (
  `RegionNum` int(11) NOT NULL,
  `TikNum` int(11) NOT NULL,
  `UikNum` int(11) NOT NULL,
  `FullName` int(11) NOT NULL,
  `Link` text,
  `Place` text,
  `VotingPlace` text,
  `BorderDescription` text,
  PRIMARY KEY (`FullName`),
  KEY `RegionNum` (`RegionNum`),
  KEY `TikNum` (`TikNum`),
  KEY `Uik` (`RegionNum`,`UikNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_violation` */

DROP TABLE IF EXISTS `election-preffix_violation`;

CREATE TABLE `election-preffix_violation` (
  `ProjectId` varchar(50) NOT NULL COMMENT 'id в проекте наблюдателе',
  `ProjectCode` char(2) NOT NULL COMMENT 'код проекта наблюдателя',
  `ProjectUptime` datetime NOT NULL COMMENT 'время последнего обновления в проекте наблюдателе',
  `ProjectVersion` smallint(6) NOT NULL COMMENT 'версия записи',
  `RegionNum` int(11) NOT NULL COMMENT 'номер региона',
  `MergedTypeId` int(11) NOT NULL COMMENT 'тип нарушения',
  `Description` longtext NOT NULL COMMENT 'описание',
  `Place` varchar(100) DEFAULT NULL COMMENT 'место',
  `ComplaintStatus` char(1) DEFAULT NULL COMMENT 'n-нет данных, 0-не подана, 1-подана, 2-отклонена, 3-есть реакция',
  `UIKNum` int(11) DEFAULT NULL,
  `TIKNum` int(11) DEFAULT NULL,
  `Media` longtext,
  `Obsrole` char(1) DEFAULT 'n' COMMENT 'n-нет данных, 0-избиратель, 1-ПРГ, 2-ПСГ, 3-Наблюдатель, 4-Корр',
  `Impact` smallint(6) DEFAULT NULL COMMENT 'влияние на результат',
  `Obstime` datetime DEFAULT NULL COMMENT 'время нарушения',
  `Loadtime` datetime DEFAULT NULL COMMENT 'время загрузки в эту таблицу',
  `Recchanel` smallint(6) DEFAULT NULL COMMENT 'канал поступления',
  `Hqcomment` longtext COMMENT 'комментарий штаба',
  `Obsid` varchar(50) DEFAULT NULL COMMENT 'id наблюдателя (0 - не из системы)',
  `Obstrusted` smallint(6) DEFAULT NULL COMMENT 'уровень доверия',
  `PoliceReaction` smallint(4) DEFAULT NULL COMMENT '0-нет инфы, 1-был вызов, 2-прибыла',
  `Rectified` tinyint(1) DEFAULT NULL COMMENT 'удалось предотвратить',
  `Rectime` datetime DEFAULT NULL COMMENT 'время получения проектом наблюдателем',
  PRIMARY KEY (`ProjectId`,`ProjectCode`,`ProjectUptime`,`ProjectVersion`),
  KEY `RegionNum` (`RegionNum`),
  KEY `UIKNum` (`RegionNum`,`UIKNum`),
  KEY `TikNum` (`RegionNum`,`TIKNum`),
  KEY `Loadtime` (`Loadtime`),
  KEY `MergedTypeId` (`MergedTypeId`),
  KEY `uptime` (`ProjectUptime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_violation_copy` */

DROP TABLE IF EXISTS `election-preffix_violation_copy`;

CREATE TABLE `election-preffix_violation_copy` (
  `ProjectId` varchar(50) NOT NULL COMMENT 'id в проекте наблюдателе',
  `ProjectCode` char(2) NOT NULL COMMENT 'код проекта наблюдателя',
  `ProjectUptime` datetime NOT NULL COMMENT 'время последнего обновления в проекте наблюдателе',
  `ProjectVersion` smallint(6) NOT NULL COMMENT 'версия записи',
  `RegionNum` int(11) NOT NULL COMMENT 'номер региона',
  `MergedTypeId` int(11) NOT NULL COMMENT 'тип нарушения',
  `Description` longtext NOT NULL COMMENT 'описание',
  `Place` varchar(100) DEFAULT NULL COMMENT 'место',
  `ComplaintStatus` char(1) DEFAULT NULL COMMENT 'n-нет данных, 0-не подана, 1-подана, 2-отклонена, 3-есть реакция',
  `UIKNum` int(11) DEFAULT NULL,
  `TIKNum` int(11) DEFAULT NULL,
  `Media` longtext,
  `Obsrole` char(1) DEFAULT 'n' COMMENT 'n-нет данных, 0-избиратель, 1-ПРГ, 2-ПСГ, 3-Наблюдатель, 4-Корр',
  `Impact` smallint(6) DEFAULT NULL COMMENT 'влияние на результат',
  `Obstime` datetime DEFAULT NULL COMMENT 'время нарушения',
  `Loadtime` datetime DEFAULT NULL COMMENT 'время загрузки в эту таблицу',
  `Recchanel` smallint(6) DEFAULT NULL COMMENT 'канал поступления',
  `Hqcomment` longtext COMMENT 'комментарий штаба',
  `Obsid` varchar(50) DEFAULT NULL COMMENT 'id наблюдателя (0 - не из системы)',
  `Obstrusted` smallint(6) DEFAULT NULL COMMENT 'уровень доверия',
  `PoliceReaction` smallint(4) DEFAULT NULL COMMENT '0-нет инфы, 1-был вызов, 2-прибыла',
  `Rectified` tinyint(1) DEFAULT NULL COMMENT 'удалось предотвратить',
  `Rectime` datetime DEFAULT NULL COMMENT 'время получения проектом наблюдателем',
  PRIMARY KEY (`ProjectId`,`ProjectCode`,`ProjectUptime`,`ProjectVersion`),
  KEY `RegionNum` (`RegionNum`),
  KEY `UIKNum` (`RegionNum`,`UIKNum`),
  KEY `TikNum` (`RegionNum`,`TIKNum`),
  KEY `Loadtime` (`Loadtime`),
  KEY `MergedTypeId` (`MergedTypeId`),
  KEY `uptime` (`ProjectUptime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `election-preffix_watch` */

DROP TABLE IF EXISTS `election-preffix_watch`;

CREATE TABLE `election-preffix_watch` (
  `uik` int(11) DEFAULT NULL,
  `WatchType` char(2) DEFAULT NULL,
  `code` int(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
