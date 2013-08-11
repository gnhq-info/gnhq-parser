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

CREATE DATABASE /*!32312 IF NOT EXISTS*/`gnhq_election-preffix` /*!40100 DEFAULT CHARACTER SET utf8 */;


USE `gnhq_election-preffix`;

/*Table structure for table `region` */

DROP TABLE IF EXISTS `region`;

CREATE TABLE `region` (
  `RegionNum` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `Population` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`RegionNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `result` */

DROP TABLE IF EXISTS `result`;

CREATE TABLE `result` (
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

/*Table structure for table `result_copy` */

DROP TABLE IF EXISTS `result_copy`;

CREATE TABLE `result_copy` (
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

/*Table structure for table `result_of` */

DROP TABLE IF EXISTS `result_of`;

CREATE TABLE `result_of` (
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

/*Table structure for table `result_of_copy` */

DROP TABLE IF EXISTS `result_of_copy`;

CREATE TABLE `result_of_copy` (
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

/*Table structure for table `tik` */

DROP TABLE IF EXISTS `tik`;

CREATE TABLE `tik` (
  `RegionNum` int(11) NOT NULL,
  `TikNum` int(11) NOT NULL,
  `FullName` varchar(200) NOT NULL,
  `OkrugName` varchar(200) DEFAULT NULL,
  `Link` text,
  PRIMARY KEY (`RegionNum`,`TikNum`),
  KEY `OkrugName` (`OkrugName`),
  KEY `RegionNum` (`RegionNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `uik` */

DROP TABLE IF EXISTS `uik`;

CREATE TABLE `uik` (
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

/*Table structure for table `violation` */

DROP TABLE IF EXISTS `violation`;

CREATE TABLE `violation` (
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

/*Table structure for table `violation_copy` */

DROP TABLE IF EXISTS `violation_copy`;

CREATE TABLE `violation_copy` (
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

/*Table structure for table `watch` */

DROP TABLE IF EXISTS `watch`;

CREATE TABLE `watch` (
  `uik` int(11) DEFAULT NULL,
  `WatchType` char(2) DEFAULT NULL,
  `code` int(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
