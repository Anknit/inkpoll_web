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
  `anonymousvote` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pollitem`
--

LOCK TABLES `pollitem` WRITE;
/*!40000 ALTER TABLE `pollitem` DISABLE KEYS */;
INSERT INTO `pollitem` VALUES (1,'akdjhfkj',1,8,0),(2,'akdjhfkj',1,8,1),(3,'akdjhfkj',1,8,0),(4,'test yor code. is it working',1,8,1),(5,'What is category of this poll',1,4,0),(6,'hjhjdghj',1,5,1),(7,'dsdsd khk',1,5,0),(8,'sjhdgfhghd',1,3,1),(9,'sjhdgfhghd',1,4,1),(10,'Is editor functionality working',1,8,0),(11,'',1,1,0),(12,'',1,1,1),(13,'',1,1,0),(14,'jsdjghjhs',1,1,1),(15,'Test anonymous',4,1,0),(16,'kksjkfjkks',4,1,0),(17,'jkdfjk',4,1,0),(18,'jkdfjk',4,1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polloptions`
--

LOCK TABLES `polloptions` WRITE;
/*!40000 ALTER TABLE `polloptions` DISABLE KEYS */;
INSERT INTO `polloptions` VALUES (1,1,'jksf jdsj'),(2,1,'jfj'),(3,1,'jb s fjsd'),(4,2,'jksf jdsj'),(5,2,'jfj'),(6,2,'jb s fjsd'),(7,3,'jksf jdsj'),(8,3,'jfj'),(9,3,'jb s fjsd'),(10,4,'yes'),(11,4,'no'),(12,4,'dont know'),(13,5,'Sports'),(14,5,'Politics'),(15,5,'Education'),(16,6,'jhg'),(17,6,'kl'),(18,6,'klk'),(19,7,'sdf'),(20,7,'sdfd'),(21,7,'fvssdv'),(22,8,'dfd'),(23,8,'adfas'),(24,8,'sdvsdf'),(25,9,'dfd'),(26,9,'adfas'),(27,9,'sdvsdf'),(28,10,'Array'),(29,10,'Array'),(30,10,'Array'),(31,10,'Sure'),(32,11,'Array'),(33,11,'Array'),(34,11,'789'),(35,11,'doiois'),(36,12,'789'),(37,12,'pol'),(38,12,'hjnn'),(39,13,''),(40,13,''),(41,13,''),(42,14,'shhdhkjs'),(43,15,'shdjh'),(44,15,'hjgjhg'),(45,15,'jhghjhghj'),(46,16,'45'),(47,16,'879'),(48,16,'jh'),(49,17,'45'),(50,17,'scs'),(51,17,'kl'),(52,18,'45'),(53,18,'scs'),(54,18,'kl');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pollresponses`
--

LOCK TABLES `pollresponses` WRITE;
/*!40000 ALTER TABLE `pollresponses` DISABLE KEYS */;
INSERT INTO `pollresponses` VALUES (1,1,2,1),(3,2,6,1),(4,4,10,1),(5,3,8,1),(6,12,37,1),(7,10,31,1),(8,11,34,1),(9,13,39,1),(10,14,42,1),(11,5,14,1),(12,17,50,1),(13,17,50,4),(14,18,54,4),(15,18,53,0);
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
  `userid` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `usertype` int(11) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `authvendor` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userinfo`
--

LOCK TABLES `userinfo` WRITE;
/*!40000 ALTER TABLE `userinfo` DISABLE KEYS */;
INSERT INTO `userinfo` VALUES (1,'Ankit Agarwal','admin','ankitakkii24@gmail.com',1,'0192023a7bbd73250516f069df18b500',NULL),(4,'AnKit Agarwal','1586737744731743','ankitfbnot@gmail.com',1,'',NULL),(5,'Ankit Agarwal','116953657481149615588','ankitakkii24@gmail.com',1,'','GOOGLE'),(6,'AnKit Agarwal','1586737744731743','ankitfbnot@gmail.com',1,'','FACEBOOK');
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

-- Dump completed on 2017-06-14 18:00:37
