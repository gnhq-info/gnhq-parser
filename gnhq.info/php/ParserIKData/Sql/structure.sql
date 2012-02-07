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
/*Table structure for table `okrug` */

DROP TABLE IF EXISTS `okrug`;

CREATE TABLE `okrug` (
  `Abbr` varchar(10) NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `TikDataLink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Abbr`)
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
  KEY `ik` (`IkFullName`,`IkType`),
  KEY `ResultType` (`ResultType`)
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

/*Table structure for table `watch_412` */

DROP TABLE IF EXISTS `watch_412`;

CREATE TABLE `watch_412` (
  `uik` int(11) DEFAULT NULL,
  `WatchType` char(2) DEFAULT NULL,
  `code` int(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
