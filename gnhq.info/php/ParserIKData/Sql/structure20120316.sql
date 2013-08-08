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
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gnhq` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `gnhq`;

/*Table structure for table `moscow_municipal_cand` */

DROP TABLE IF EXISTS `moscow_municipal_cand`;

CREATE TABLE `moscow_municipal_cand` (
  `OkrId` int(11) NOT NULL,
  `Num` int(11) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `IsBlacklist` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `moscow_municipal_oiks` */

DROP TABLE IF EXISTS `moscow_municipal_oiks`;

CREATE TABLE `moscow_municipal_oiks` (
  `Id` int(11) NOT NULL,
  `FullName` varchar(50) NOT NULL,
  `TikNum` int(11) NOT NULL,
  `Link` text,
  `Magnitude` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `okrug` */

DROP TABLE IF EXISTS `okrug`;

CREATE TABLE `okrug` (
  `Abbr` varchar(10) NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `TikDataLink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Abbr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `region` */

DROP TABLE IF EXISTS `region`;

CREATE TABLE `region` (
  `RegionNum` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `Population` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`RegionNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `report_412` */

DROP TABLE IF EXISTS `report_412`;

CREATE TABLE `report_412` (
  `uik` int(11) NOT NULL,
  `ocenka` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `shortDescr` text,
  `fullReport` longtext,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `result_403` */

DROP TABLE IF EXISTS `result_403`;

CREATE TABLE `result_403` (
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
  `Line2` int(11) NOT NULL,
  `Line3` int(11) NOT NULL,
  `Line4` int(11) NOT NULL,
  `Line5` int(11) NOT NULL,
  `Line6` int(11) NOT NULL,
  `Line7` int(11) NOT NULL,
  `Line8` int(11) NOT NULL,
  `Line9` int(11) NOT NULL,
  `Line10` int(11) NOT NULL,
  `Line11` int(11) NOT NULL,
  `Line12` int(11) NOT NULL,
  `Line13` int(11) NOT NULL,
  `Line14` int(11) NOT NULL,
  `Line15` int(11) NOT NULL,
  `Line16` int(11) NOT NULL,
  `Line17` int(11) NOT NULL,
  `Line18` int(11) NOT NULL,
  `Line19` int(11) NOT NULL,
  `Line20` int(11) NOT NULL,
  `Line21` int(11) NOT NULL,
  `Line22` int(11) NOT NULL,
  `Line23` int(11) NOT NULL,
  KEY `ResultType` (`ResultType`),
  KEY `ik` (`IkType`,`IkFullName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `result_403_copy` */

DROP TABLE IF EXISTS `result_403_copy`;

CREATE TABLE `result_403_copy` (
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
  `Line2` int(11) NOT NULL,
  `Line3` int(11) NOT NULL,
  `Line4` int(11) NOT NULL,
  `Line5` int(11) NOT NULL,
  `Line6` int(11) NOT NULL,
  `Line7` int(11) NOT NULL,
  `Line8` int(11) NOT NULL,
  `Line9` int(11) NOT NULL,
  `Line10` int(11) NOT NULL,
  `Line11` int(11) NOT NULL,
  `Line12` int(11) NOT NULL,
  `Line13` int(11) NOT NULL,
  `Line14` int(11) NOT NULL,
  `Line15` int(11) NOT NULL,
  `Line16` int(11) NOT NULL,
  `Line17` int(11) NOT NULL,
  `Line18` int(11) NOT NULL,
  `Line19` int(11) NOT NULL,
  `Line20` int(11) NOT NULL,
  `Line21` int(11) NOT NULL,
  `Line22` int(11) NOT NULL,
  `Line23` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `result_403_of` */

DROP TABLE IF EXISTS `result_403_of`;

CREATE TABLE `result_403_of` (
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
  `Line2` int(11) NOT NULL,
  `Line3` int(11) NOT NULL,
  `Line4` int(11) NOT NULL,
  `Line5` int(11) NOT NULL,
  `Line6` int(11) NOT NULL,
  `Line7` int(11) NOT NULL,
  `Line8` int(11) NOT NULL,
  `Line9` int(11) NOT NULL,
  `Line10` int(11) NOT NULL,
  `Line11` int(11) NOT NULL,
  `Line12` int(11) NOT NULL,
  `Line13` int(11) NOT NULL,
  `Line14` int(11) NOT NULL,
  `Line15` int(11) NOT NULL,
  `Line16` int(11) NOT NULL,
  `Line17` int(11) NOT NULL,
  `Line18` int(11) NOT NULL,
  `Line19` int(11) NOT NULL,
  `Line20` int(11) NOT NULL,
  `Line21` int(11) NOT NULL,
  `Line22` int(11) NOT NULL,
  `Line23` int(11) NOT NULL,
  KEY `ResultType` (`ResultType`),
  KEY `ik` (`IkType`,`IkFullName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `result_403_of_copy` */

DROP TABLE IF EXISTS `result_403_of_copy`;

CREATE TABLE `result_403_of_copy` (
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
  `Line2` int(11) NOT NULL,
  `Line3` int(11) NOT NULL,
  `Line4` int(11) NOT NULL,
  `Line5` int(11) NOT NULL,
  `Line6` int(11) NOT NULL,
  `Line7` int(11) NOT NULL,
  `Line8` int(11) NOT NULL,
  `Line9` int(11) NOT NULL,
  `Line10` int(11) NOT NULL,
  `Line11` int(11) NOT NULL,
  `Line12` int(11) NOT NULL,
  `Line13` int(11) NOT NULL,
  `Line14` int(11) NOT NULL,
  `Line15` int(11) NOT NULL,
  `Line16` int(11) NOT NULL,
  `Line17` int(11) NOT NULL,
  `Line18` int(11) NOT NULL,
  `Line19` int(11) NOT NULL,
  `Line20` int(11) NOT NULL,
  `Line21` int(11) NOT NULL,
  `Line22` int(11) NOT NULL,
  `Line23` int(11) NOT NULL,
  KEY `ResultType` (`ResultType`),
  KEY `ik` (`IkType`,`IkFullName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `result_403_offile` */

DROP TABLE IF EXISTS `result_403_offile`;

CREATE TABLE `result_403_offile` (
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
  `Line2` int(11) NOT NULL,
  `Line3` int(11) NOT NULL,
  `Line4` int(11) NOT NULL,
  `Line5` int(11) NOT NULL,
  `Line6` int(11) NOT NULL,
  `Line7` int(11) NOT NULL,
  `Line8` int(11) NOT NULL,
  `Line9` int(11) NOT NULL,
  `Line10` int(11) NOT NULL,
  `Line11` int(11) NOT NULL,
  `Line12` int(11) NOT NULL,
  `Line13` int(11) NOT NULL,
  `Line14` int(11) NOT NULL,
  `Line15` int(11) NOT NULL,
  `Line16` int(11) NOT NULL,
  `Line17` int(11) NOT NULL,
  `Line18` int(11) NOT NULL,
  `Line19` int(11) NOT NULL,
  `Line20` int(11) NOT NULL,
  `Line21` int(11) NOT NULL,
  `Line22` int(11) NOT NULL,
  `Line23` int(11) NOT NULL,
  KEY `ResultType` (`ResultType`),
  KEY `ik` (`IkType`,`IkFullName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `result_412` */

DROP TABLE IF EXISTS `result_412`;

CREATE TABLE `result_412` (
  `IkFullName` varchar(100) DEFAULT NULL,
  `IkType` varchar(5) NOT NULL,
  `ResultType` varchar(5) NOT NULL,
  `ClaimCount` int(11) DEFAULT '0',
  `Line1` int(11) NOT NULL,
  `Line2` int(11) NOT NULL,
  `Line3` int(11) NOT NULL,
  `Line4` int(11) NOT NULL,
  `Line5` int(11) NOT NULL,
  `Line6` int(11) NOT NULL,
  `Line7` int(11) NOT NULL,
  `Line8` int(11) NOT NULL,
  `Line9` int(11) NOT NULL,
  `Line10` int(11) NOT NULL,
  `Line11` int(11) NOT NULL,
  `Line12` int(11) NOT NULL,
  `Line13` int(11) NOT NULL,
  `Line14` int(11) NOT NULL,
  `Line15` int(11) NOT NULL,
  `Line16` int(11) NOT NULL,
  `Line17` int(11) NOT NULL,
  `Line18` int(11) NOT NULL,
  `Line19` int(11) NOT NULL,
  `Line20` int(11) NOT NULL,
  `Line21` int(11) NOT NULL,
  `Line22` int(11) NOT NULL,
  `Line23` int(11) NOT NULL,
  `Line24` int(11) NOT NULL,
  `Line25` int(11) NOT NULL,
  KEY `ResultType` (`ResultType`),
  KEY `ik` (`IkType`,`IkFullName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tik` */

DROP TABLE IF EXISTS `tik`;

CREATE TABLE `tik` (
  `OkrugAbbr` varchar(10) DEFAULT NULL,
  `FullName` varchar(100) NOT NULL,
  `Address` text,
  `Phone` varchar(255) DEFAULT NULL,
  `Chief` varchar(100) DEFAULT NULL,
  `Deputy` varchar(100) DEFAULT NULL,
  `Secretary` varchar(100) DEFAULT NULL,
  `Members` text,
  `SelfInfoLink` varchar(255) DEFAULT NULL,
  `AddressLink` varchar(255) DEFAULT NULL,
  `SostavLink` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `Uid` char(20) DEFAULT NULL,
  PRIMARY KEY (`FullName`),
  KEY `OkrugAbbr` (`OkrugAbbr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tik_russia` */

DROP TABLE IF EXISTS `tik_russia`;

CREATE TABLE `tik_russia` (
  `RegionNum` int(11) NOT NULL,
  `TikNum` int(11) NOT NULL,
  `FullName` varchar(200) NOT NULL,
  `OkrugName` varchar(200) DEFAULT NULL,
  `Link` text,
  PRIMARY KEY (`RegionNum`,`TikNum`),
  KEY `OkrugName` (`OkrugName`),
  KEY `RegionNum` (`RegionNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `twit` */

DROP TABLE IF EXISTS `twit`;

CREATE TABLE `twit` (
  `Guid` varchar(30) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Time` datetime NOT NULL,
  `Link` varchar(255) NOT NULL,
  `Source` varchar(255) DEFAULT NULL,
  `Place` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Guid`),
  KEY `Time` (`Time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `uik` */

DROP TABLE IF EXISTS `uik`;

CREATE TABLE `uik` (
  `TikUniqueId` varchar(30) DEFAULT NULL,
  `FullName` int(11) NOT NULL,
  `BorderDescription` text,
  `Place` text,
  `VotingPlace` text,
  `Link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`FullName`),
  KEY `TikUniqueId` (`TikUniqueId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `uik_russia` */

DROP TABLE IF EXISTS `uik_russia`;

CREATE TABLE `uik_russia` (
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
  `ComplaintStatus` char(1) DEFAULT NULL COMMENT 'n-нет данных;0-не подана, 1-подана, 2-отклонена, 3-есть реакция',
  `UIKNum` int(11) DEFAULT NULL,
  `TIKNum` int(11) DEFAULT NULL,
  `Media` longtext,
  `Obsrole` char(1) DEFAULT 'n' COMMENT 'n-нет данных;0-избиратель;1-ПРГ;2-ПСГ;3-Наблюдатель;4-Корр',
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
  `Description` text NOT NULL COMMENT 'описание',
  `Place` varchar(100) DEFAULT NULL COMMENT 'место',
  `ComplaintStatus` char(1) DEFAULT NULL COMMENT 'n-нет данных;0-не подана, 1-подана, 2-отклонена, 3-есть реакция',
  `UIKNum` int(11) DEFAULT NULL,
  `TIKNum` int(11) DEFAULT NULL,
  `Media` longtext,
  `Obsrole` char(1) DEFAULT 'n' COMMENT 'n-нет данных;0-избиратель;1-ПРГ;2-ПСГ;3-Наблюдатель;4-Корр',
  `Impact` smallint(6) DEFAULT NULL COMMENT 'влияние на результат',
  `Obstime` datetime DEFAULT NULL COMMENT 'время нарушения',
  `Loadtime` datetime DEFAULT NULL COMMENT 'время загрузки в эту таблицу',
  `Recchanel` smallint(6) DEFAULT NULL COMMENT 'канал поступления',
  `Hqcomment` longtext COMMENT 'комментарий штаба',
  `Obsid` varchar(50) DEFAULT NULL COMMENT 'id наблюдателя (0 - не из системы)',
  `Obstrusted` smallint(6) DEFAULT NULL COMMENT 'уровень доверия',
  `PoliceReaction` smallint(6) DEFAULT NULL COMMENT '0-нет инфы, 1-был вызов, 2-прибыла',
  `Rectified` tinyint(1) DEFAULT NULL COMMENT 'удалось предотвратить',
  `Rectime` datetime DEFAULT NULL COMMENT 'время получения проектом наблюдателем'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `violation_type` */

DROP TABLE IF EXISTS `violation_type`;

CREATE TABLE `violation_type` (
  `MergedType` int(11) NOT NULL,
  `ProjectType` varchar(255) NOT NULL,
  `ProjectCode` char(2) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupType` smallint(6) DEFAULT '0',
  `Severity` smallint(6) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `watch_403` */

DROP TABLE IF EXISTS `watch_403`;

CREATE TABLE `watch_403` (
  `uik` int(11) DEFAULT NULL,
  `WatchType` char(2) DEFAULT NULL,
  `code` int(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `watch_412` */

DROP TABLE IF EXISTS `watch_412`;

CREATE TABLE `watch_412` (
  `uik` int(11) DEFAULT NULL,
  `WatchType` char(2) DEFAULT NULL,
  `code` int(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `uik_okrug` */

DROP TABLE IF EXISTS `uik_okrug`;

/*!50001 DROP VIEW IF EXISTS `uik_okrug` */;
/*!50001 DROP TABLE IF EXISTS `uik_okrug` */;

/*!50001 CREATE TABLE  `uik_okrug`(
 `uik` int(11) ,
 `okrug` varchar(10) 
)*/;

/*View structure for view uik_okrug */

/*!50001 DROP TABLE IF EXISTS `uik_okrug` */;
/*!50001 DROP VIEW IF EXISTS `uik_okrug` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `uik_okrug` AS select `uik`.`FullName` AS `uik`,`okrug`.`Abbr` AS `okrug` from ((`uik` join `tik` on((`uik`.`TikUniqueId` = `tik`.`Uid`))) join `okrug` on((`tik`.`OkrugAbbr` = `okrug`.`Abbr`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
