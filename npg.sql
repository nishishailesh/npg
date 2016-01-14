-- MySQL dump 10.13  Distrib 5.5.39, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: npg
-- ------------------------------------------------------
-- Server version	5.5.39-1

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
-- Table structure for table `catagory`
--

DROP TABLE IF EXISTS `catagory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catagory` (
  `catagory` varchar(20) NOT NULL,
  PRIMARY KEY (`catagory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catagory`
--

LOCK TABLES `catagory` WRITE;
/*!40000 ALTER TABLE `catagory` DISABLE KEYS */;
INSERT INTO `catagory` VALUES ('Open'),('Open-MO'),('Open-PH'),('SC'),('SC-MO'),('SC-PH'),('SEBC'),('SEBC-MO'),('SEBC-PH'),('ST'),('ST-MO'),('ST-PH');
/*!40000 ALTER TABLE `catagory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `choice`
--

DROP TABLE IF EXISTS `choice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `choice` (
  `student_id` int(11) NOT NULL,
  `subloc_id` int(11) NOT NULL,
  `choice` int(11) NOT NULL,
  PRIMARY KEY (`student_id`,`subloc_id`),
  UNIQUE KEY `student_id` (`student_id`,`choice`),
  KEY `choice_ibfk_2` (`subloc_id`),
  CONSTRAINT `choice_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `choice_ibfk_2` FOREIGN KEY (`subloc_id`) REFERENCES `subloc` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `choice`
--

LOCK TABLES `choice` WRITE;
/*!40000 ALTER TABLE `choice` DISABLE KEYS */;
INSERT INTO `choice` VALUES (1,6,3),(1,1,4),(1,10,7),(1,13,9),(1,11,15),(2,2,1),(2,1,2),(3,1,1);
/*!40000 ALTER TABLE `choice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seat`
--

DROP TABLE IF EXISTS `seat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seat` (
  `subloc_id` int(11) NOT NULL,
  `catagory` varchar(10) NOT NULL,
  `number` int(11) NOT NULL,
  `max_number` int(11) NOT NULL,
  PRIMARY KEY (`subloc_id`,`catagory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seat`
--

LOCK TABLES `seat` WRITE;
/*!40000 ALTER TABLE `seat` DISABLE KEYS */;
INSERT INTO `seat` VALUES (1,'Open',1,1),(2,'Open',1,1),(5,'Open',1,1);
/*!40000 ALTER TABLE `seat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `catagory` varchar(10) NOT NULL,
  `origin` varchar(1) NOT NULL,
  `all_india` double NOT NULL,
  `tie_breaker` int(11) NOT NULL,
  `alloc_subloc_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catagory` (`catagory`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`catagory`) REFERENCES `catagory` (`catagory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (1,'shailesh','Open','A',550,0,11),(2,'nimesh','Open','B',700,0,0),(3,'Mahesh','Open','A',550,1,0);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subloc`
--

DROP TABLE IF EXISTS `subloc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subloc` (
  `id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `location` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subloc`
--

LOCK TABLES `subloc` WRITE;
/*!40000 ALTER TABLE `subloc` DISABLE KEYS */;
INSERT INTO `subloc` VALUES (1,'SUR','G'),(2,'MED','G'),(3,'SUR','S'),(4,'MED','S'),(5,'PED','G'),(6,'PED','S'),(7,'DCH','G'),(8,'DCH','S'),(9,'OG','G'),(10,'OG','S'),(11,'DGO','G'),(12,'DGO','S'),(13,'OPTH','G'),(14,'OPTH','S'),(15,'DOPTH','G'),(16,'DOPTH','S'),(17,'ENT','G'),(18,'ENT','S'),(19,'DLO','G'),(20,'DLO','S'),(21,'ORTHO','G'),(22,'ORTHO','S'),(23,'SKINVD','G'),(24,'SKINVD','S'),(25,'DVD','G'),(26,'DVD','S'),(27,'PSYC','G'),(28,'PSYC','S'),(29,'DPM','G'),(30,'DPM','S'),(31,'PATH','G'),(32,'PATH','S'),(33,'DCP','G'),(34,'DCP','S'),(35,'MICRO','G'),(36,'MICRO','S'),(37,'PHARM','G'),(38,'PHARM','S'),(39,'FM','G'),(40,'FM','S'),(41,'ANAT','G'),(42,'ANAT','S'),(43,'PHYSIO','G'),(44,'PHYSIO','S'),(45,'BIOCHE','G'),(46,'BIOCHE','S');
/*!40000 ALTER TABLE `subloc` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-14 20:23:06
