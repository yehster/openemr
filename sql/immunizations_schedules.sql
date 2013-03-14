-- MySQL dump 10.13  Distrib 5.5.27, for Win64 (x86)
--
-- Host: localhost    Database: openemr
-- ------------------------------------------------------
-- Server version	5.5.27

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
-- Table structure for table `immunizations_schedules`
--

DROP TABLE IF EXISTS `immunizations_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immunizations_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `age_max` int(11) DEFAULT NULL,
  `frequency` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `immunizations_schedules`
--

LOCK TABLES `immunizations_schedules` WRITE;
/*!40000 ALTER TABLE `immunizations_schedules` DISABLE KEYS */;
INSERT INTO `immunizations_schedules` VALUES (1,'Newborn',0,NULL,NULL),(2,'2 Months',2,NULL,NULL),(3,'4 Months',4,NULL,NULL),(4,'6 Months',6,NULL,NULL),(5,'9 Months',9,NULL,NULL),(6,'12 Months',12,NULL,NULL),(7,'15 Months',15,NULL,NULL),(8,'18 Months',18,NULL,NULL),(9,'4 Years',48,NULL,NULL),(10,'11 Years',132,NULL,NULL),(11,'15 Years',160,NULL,NULL),(12,'Influenza',36,216,'annual');
/*!40000 ALTER TABLE `immunizations_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `immunizations_schedules_codes`
--

DROP TABLE IF EXISTS `immunizations_schedules_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immunizations_schedules_codes` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(45) DEFAULT NULL,
  `cvx_code` varchar(45) DEFAULT NULL,
  `proc_codes` varchar(45) DEFAULT NULL,
  `justify_codes` varchar(45) DEFAULT NULL,
  `default_site` varchar(45) DEFAULT NULL,
  `comments` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `immunizations_schedules_codes`
--

LOCK TABLES `immunizations_schedules_codes` WRITE;
/*!40000 ALTER TABLE `immunizations_schedules_codes` DISABLE KEYS */;
INSERT INTO `immunizations_schedules_codes` VALUES (1,'Hepatitis B',NULL,'8','CPT4:90744','ICD9:V05.9','RT','Newborn'),(2,'Pentacel','PMC','120','CPT4:90698','ICD9:V06.3;ICD9:V03.81','RT',NULL),(3,'Hepatitis B',NULL,'8','CPT4:90744','ICD9:V05.3','RT',NULL),(4,'Prevnar 13','WAL','133','CPT4:90670','ICD9:V03.82','LT',NULL),(5,'Rotateq','MSD','116','CPT4:90680','ICD9:V04.89','PO',NULL),(6,'DTaP',NULL,'20','CPT4:90700','ICD9:V06.1','RT',NULL),(7,'IPV','PMC','10','CPT4:90713','ICD9:V04.0','RT','(IPOL?)'),(8,'Influenza','PMC','140','CPT4:90655','ICD9:V04.81','LT','(Fluzone?)(6 months)'),(9,'HIB',NULL,'47','CPT4:90645','ICD9:V03.81','LT','(HibTiter?/Wyeth)(LT 9 Mo)'),(10,'MMR',NULL,'03','CPT4:90707','ICD9:V06.4','RA','(Merck?)'),(11,'Varicella',NULL,'21','CPT4:90716','ICD9:V05.4','LA','(Merck?/Varivax?)'),(12,'Hepatitis A',NULL,'83','CPT4:90633','ICD9:V05.9','RT','RT 12 Months'),(13,'HIB',NULL,'47','CPT4:90645','ICD9:V03.81','RT','(RT 15 months)'),(14,'Hepatitis A',NULL,'83','CPT4:90633','ICD9:V05.9','RD','RD 18 Months'),(15,'Kinrix','SKB','130','CPT4:90696','ICD9:V06.3','RT',NULL),(16,'Menactra','PMC','114','CPT4:90734','ICD9:V03.89','RD',NULL),(17,'Adacel-TDaP','PMC','115','CPT4:90715','ICD9:V06.1','LD',NULL),(18,'Gardasil','MSD','62','CPT4:90649','ICD9:V05.8','RD',NULL),(19,'Influenza',NULL,'141','CPT4:90658','ICD9:V04.81','RD','Annual Influenza Age 3-18');
/*!40000 ALTER TABLE `immunizations_schedules_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `immunizations_schedules_options`
--

DROP TABLE IF EXISTS `immunizations_schedules_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immunizations_schedules_options` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `code_id` int(11) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `immunizations_schedules_options`
--

LOCK TABLES `immunizations_schedules_options` WRITE;
/*!40000 ALTER TABLE `immunizations_schedules_options` DISABLE KEYS */;
INSERT INTO `immunizations_schedules_options` VALUES (1,1,1,10),(2,2,2,10),(3,2,3,20),(4,2,4,30),(5,2,5,40),(6,3,2,10),(7,3,3,20),(8,3,4,30),(9,3,5,40),(10,4,6,10),(11,4,7,20),(12,4,4,30),(13,4,8,40),(14,4,5,50),(15,4,2,60),(16,5,3,10),(17,5,9,20),(18,5,8,30),(19,6,10,10),(20,6,11,20),(21,6,12,30),(22,6,8,40),(23,7,6,10),(24,7,13,20),(25,7,4,30),(26,8,14,10),(27,9,6,10),(28,9,7,20),(29,9,15,30),(30,9,11,40),(31,9,10,50),(32,10,16,10),(33,10,17,20),(34,10,18,30),(35,11,16,10),(36,12,19,10);
/*!40000 ALTER TABLE `immunizations_schedules_options` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-03-16 10:29:03
