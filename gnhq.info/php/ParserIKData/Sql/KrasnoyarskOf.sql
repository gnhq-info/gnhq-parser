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

/*Table structure for table `krasnoyarsk_result_of` */

DROP TABLE IF EXISTS `krasnoyarsk_result_of`;

CREATE TABLE `krasnoyarsk_result_of` (
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
  KEY `ResultType` (`ResultType`),
  KEY `ik` (`IkType`,`IkFullName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `krasnoyarsk_result_of` */

/*Table structure for table `krasnoyarsk_result_of_copy` */

DROP TABLE IF EXISTS `krasnoyarsk_result_of_copy`;

CREATE TABLE `krasnoyarsk_result_of_copy` (
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
  `Line19` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `krasnoyarsk_result_of_copy` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
