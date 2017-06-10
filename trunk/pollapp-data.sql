-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: pollapp
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.04.1

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
-- Dumping data for table `pollcategories`
--

LOCK TABLES `pollcategories` WRITE;
/*!40000 ALTER TABLE `pollcategories` DISABLE KEYS */;
INSERT INTO `pollcategories` VALUES (1,'Entertainment','For Entertainment',0,'1','2017-06-10 19:44:10'),(2,'Sports','For sports',0,'1','2017-06-10 19:44:10'),(3,'Food','For Food',0,'1','2017-06-10 19:44:10'),(4,'Politics','For politics',0,'1','2017-06-10 19:44:10'),(5,'Education','For education',0,'1','2017-06-10 19:44:10'),(6,'Business','For Business',0,'1','2017-06-10 19:44:10'),(7,'Health','For Health',0,'1','2017-06-10 19:44:10'),(8,'Miscellaneous','Misc Purpose',0,'1','2017-06-10 19:44:10');
/*!40000 ALTER TABLE `pollcategories` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pollitem`
--

LOCK TABLES `pollitem` WRITE;
/*!40000 ALTER TABLE `pollitem` DISABLE KEYS */;
INSERT INTO `pollitem` VALUES (1,'akdjhfkj',1,8),(2,'akdjhfkj',1,8),(3,'akdjhfkj',1,8),(4,'test yor code. is it working',1,8),(5,'What is category of this poll',1,4),(6,'hjhjdghj',1,5),(7,'dsdsd khk',1,5),(8,'sjhdgfhghd',1,3),(9,'sjhdgfhghd',1,4);
/*!40000 ALTER TABLE `pollitem` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polloptions`
--

LOCK TABLES `polloptions` WRITE;
/*!40000 ALTER TABLE `polloptions` DISABLE KEYS */;
INSERT INTO `polloptions` VALUES (1,1,'jksf jdsj'),(2,1,'jfj'),(3,1,'jb s fjsd'),(4,2,'jksf jdsj'),(5,2,'jfj'),(6,2,'jb s fjsd'),(7,3,'jksf jdsj'),(8,3,'jfj'),(9,3,'jb s fjsd'),(10,4,'yes'),(11,4,'no'),(12,4,'dont know'),(13,5,'Sports'),(14,5,'Politics'),(15,5,'Education'),(16,6,'jhg'),(17,6,'kl'),(18,6,'klk'),(19,7,'sdf'),(20,7,'sdfd'),(21,7,'fvssdv'),(22,8,'dfd'),(23,8,'adfas'),(24,8,'sdvsdf'),(25,9,'dfd'),(26,9,'adfas'),(27,9,'sdvsdf');
/*!40000 ALTER TABLE `polloptions` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`resId`),
  UNIQUE KEY `resId_UNIQUE` (`resId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pollresponses`
--

LOCK TABLES `pollresponses` WRITE;
/*!40000 ALTER TABLE `pollresponses` DISABLE KEYS */;
INSERT INTO `pollresponses` VALUES (1,1,2,1),(3,2,6,1),(4,4,10,1),(5,3,8,1);
/*!40000 ALTER TABLE `pollresponses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `usertype` int(11) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userinfo`
--

LOCK TABLES `userinfo` WRITE;
/*!40000 ALTER TABLE `userinfo` DISABLE KEYS */;
INSERT INTO `userinfo` VALUES (1,'Ankit Agarwal','admin','ankitakkii24@gmail.com',1,'0192023a7bbd73250516f069df18b500');
/*!40000 ALTER TABLE `userinfo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-10 20:24:09
