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
  KEY `MergedTypeId` (`MergedTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
