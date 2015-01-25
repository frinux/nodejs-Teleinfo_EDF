CREATE DATABASE  IF NOT EXISTS `teleinfo` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `teleinfo`;
-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (x86_64)
--
-- Host: 192.168.0.101    Database: teleinfo
-- ------------------------------------------------------
-- Server version	5.5.40-0+wheezy1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary table structure for view `daily_consumption`
--

DROP TABLE IF EXISTS `daily_consumption`;
/*!50001 DROP VIEW IF EXISTS `daily_consumption`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `daily_consumption` (
  `YEAR` tinyint NOT NULL,
  `MONTH` tinyint NOT NULL,
  `DAY` tinyint NOT NULL,
  `SUM_HCHC_delta` tinyint NOT NULL,
  `SUM_HCHP_delta` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `hourly_consumption`
--

DROP TABLE IF EXISTS `hourly_consumption`;
/*!50001 DROP VIEW IF EXISTS `hourly_consumption`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `hourly_consumption` (
  `YEAR` tinyint NOT NULL,
  `MONTH` tinyint NOT NULL,
  `DAY` tinyint NOT NULL,
  `HOUR` tinyint NOT NULL,
  `SUM_HCHC_delta` tinyint NOT NULL,
  `SUM_HCHP_delta` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `monthly_consumption`
--

DROP TABLE IF EXISTS `monthly_consumption`;
/*!50001 DROP VIEW IF EXISTS `monthly_consumption`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `monthly_consumption` (
  `YEAR(timestamp)` tinyint NOT NULL,
  `MONTH(timestamp)` tinyint NOT NULL,
  `SUM(HCHC_delta)` tinyint NOT NULL,
  `SUM(HCHP_delta)` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `raw_data`
--

DROP TABLE IF EXISTS `raw_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `raw_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ADCO` varchar(45) NOT NULL,
  `OPTARIF` varchar(45) NOT NULL,
  `ISOUSC` varchar(45) NOT NULL,
  `HCHC` varchar(45) NOT NULL,
  `HCHP` varchar(45) NOT NULL,
  `PTEC` varchar(45) NOT NULL,
  `IINST` varchar(45) NOT NULL,
  `IMAX` varchar(45) NOT NULL,
  `HHPHC` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `HCHC_delta` varchar(45) NOT NULL,
  `HCHP_delta` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95758 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `yearly_consumption`
--

DROP TABLE IF EXISTS `yearly_consumption`;
/*!50001 DROP VIEW IF EXISTS `yearly_consumption`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `yearly_consumption` (
  `YEAR(timestamp)` tinyint NOT NULL,
  `SUM(HCHC_delta)` tinyint NOT NULL,
  `SUM(HCHP_delta)` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `daily_consumption`
--

/*!50001 DROP TABLE IF EXISTS `daily_consumption`*/;
/*!50001 DROP VIEW IF EXISTS `daily_consumption`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `daily_consumption` AS select year(`raw_data`.`timestamp`) AS `YEAR`,month(`raw_data`.`timestamp`) AS `MONTH`,dayofmonth(`raw_data`.`timestamp`) AS `DAY`,sum(`raw_data`.`HCHC_delta`) AS `SUM_HCHC_delta`,sum(`raw_data`.`HCHP_delta`) AS `SUM_HCHP_delta` from `raw_data` group by year(`raw_data`.`timestamp`),month(`raw_data`.`timestamp`),dayofmonth(`raw_data`.`timestamp`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `hourly_consumption`
--

/*!50001 DROP TABLE IF EXISTS `hourly_consumption`*/;
/*!50001 DROP VIEW IF EXISTS `hourly_consumption`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `hourly_consumption` AS select year(`raw_data`.`timestamp`) AS `YEAR`,month(`raw_data`.`timestamp`) AS `MONTH`,dayofmonth(`raw_data`.`timestamp`) AS `DAY`,hour(`raw_data`.`timestamp`) AS `HOUR`,sum(`raw_data`.`HCHC_delta`) AS `SUM_HCHC_delta`,sum(`raw_data`.`HCHP_delta`) AS `SUM_HCHP_delta` from `raw_data` group by year(`raw_data`.`timestamp`),month(`raw_data`.`timestamp`),dayofmonth(`raw_data`.`timestamp`),hour(`raw_data`.`timestamp`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `monthly_consumption`
--

/*!50001 DROP TABLE IF EXISTS `monthly_consumption`*/;
/*!50001 DROP VIEW IF EXISTS `monthly_consumption`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `monthly_consumption` AS select year(`raw_data`.`timestamp`) AS `YEAR(timestamp)`,month(`raw_data`.`timestamp`) AS `MONTH(timestamp)`,sum(`raw_data`.`HCHC_delta`) AS `SUM(HCHC_delta)`,sum(`raw_data`.`HCHP_delta`) AS `SUM(HCHP_delta)` from `raw_data` group by year(`raw_data`.`timestamp`),month(`raw_data`.`timestamp`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `yearly_consumption`
--

/*!50001 DROP TABLE IF EXISTS `yearly_consumption`*/;
/*!50001 DROP VIEW IF EXISTS `yearly_consumption`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `yearly_consumption` AS select year(`raw_data`.`timestamp`) AS `YEAR(timestamp)`,sum(`raw_data`.`HCHC_delta`) AS `SUM(HCHC_delta)`,sum(`raw_data`.`HCHP_delta`) AS `SUM(HCHP_delta)` from `raw_data` group by year(`raw_data`.`timestamp`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-25 20:30:37
