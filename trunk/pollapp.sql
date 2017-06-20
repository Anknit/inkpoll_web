-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: pollapp
-- ------------------------------------------------------
-- Server version	5.7.17-0ubuntu0.16.04.2

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
-- Table structure for table `pollcategories`
--

DROP TABLE IF EXISTS `pollcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollcategories` (
  `catId` int(11) NOT NULL AUTO_INCREMENT,
  `catName` varchar(200) DEFAULT NULL,
  `catDescription` text,
  `parentCat` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `createdOn` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`catId`),
  UNIQUE KEY `catId_UNIQUE` (`catId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pollitem`
--

DROP TABLE IF EXISTS `pollitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pollQuestion` text,
  `userId` int(11) DEFAULT NULL,
  `catId` int(11) DEFAULT NULL,
  `anonymousvote` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `createdon` varchar(45) DEFAULT NULL,
  `updatedon` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `polllikes`
--

DROP TABLE IF EXISTS `polllikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polllikes` (
  `pollid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `likescore` int(11) DEFAULT NULL,
  `likedon` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `polloptions`
--

DROP TABLE IF EXISTS `polloptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polloptions` (
  `optionId` int(11) NOT NULL AUTO_INCREMENT,
  `pollId` int(11) DEFAULT NULL,
  `optionText` text,
  PRIMARY KEY (`optionId`),
  UNIQUE KEY `optionid_UNIQUE` (`optionId`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pollresponses`
--

DROP TABLE IF EXISTS `pollresponses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollresponses` (
  `resId` int(11) NOT NULL AUTO_INCREMENT,
  `pollId` int(11) DEFAULT NULL,
  `optionId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `createdon` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`resId`),
  UNIQUE KEY `resId_UNIQUE` (`resId`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `userid` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `usertype` int(11) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `authvendor` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-21  0:47:43
