-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: scis_requisition_db
-- ------------------------------------------------------
-- Server version	5.7.11

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
-- Table structure for table `itemsnotpo`
--

DROP TABLE IF EXISTS `itemsnotpo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemsnotpo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_accomplished` date DEFAULT NULL,
  `request_slip_no` int(11) NOT NULL COMMENT 'request_slip.id',
  `amount` double DEFAULT '0',
  `itemStatus` enum('Pending','Canceled','Delivered') NOT NULL DEFAULT 'Pending',
  `remarks` varchar(255) DEFAULT 'None',
  `supplier` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_slip_no` (`request_slip_no`),
  CONSTRAINT `request_slipFK` FOREIGN KEY (`request_slip_no`) REFERENCES `request_slip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemsnotpo`
--

LOCK TABLES `itemsnotpo` WRITE;
/*!40000 ALTER TABLE `itemsnotpo` DISABLE KEYS */;
INSERT INTO `itemsnotpo` VALUES (1,44,'projector',NULL,1,0,'Pending','None','randall as_');
/*!40000 ALTER TABLE `itemsnotpo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemspo`
--

DROP TABLE IF EXISTS `itemspo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemspo` (
  `iditemspo` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `unitprice` double DEFAULT '0',
  `amount` double DEFAULT '0',
  `poid` int(11) NOT NULL,
  `itemspostatus` enum('Pending','Canceled','Delivered') NOT NULL DEFAULT 'Pending',
  `date_complete` date DEFAULT NULL,
  `supplier_po` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`iditemspo`),
  KEY `POIDFK_idx` (`poid`),
  KEY `iditemspo` (`iditemspo`,`quantity`,`description`,`remarks`,`Location`,`unitprice`,`amount`,`poid`,`itemspostatus`),
  KEY `iditemspo_2` (`iditemspo`,`quantity`,`description`,`remarks`,`Location`,`unitprice`,`amount`,`poid`,`itemspostatus`),
  CONSTRAINT `PoFKID` FOREIGN KEY (`poid`) REFERENCES `purchase_order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Items for PO';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemspo`
--

LOCK TABLES `itemspo` WRITE;
/*!40000 ALTER TABLE `itemspo` DISABLE KEYS */;
INSERT INTO `itemspo` VALUES (1,35,'projector','nice','jan sa gilid',20,700,1,'Delivered','2017-05-31','randall dogg_;');
/*!40000 ALTER TABLE `itemspo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order`
--

DROP TABLE IF EXISTS `purchase_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_no` varchar(11) DEFAULT NULL,
  `date_of_po` date DEFAULT NULL,
  `supplier` varchar(45) DEFAULT NULL,
  `totalamt` double DEFAULT NULL,
  `request_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  CONSTRAINT `fk_reqid_reqslip` FOREIGN KEY (`request_id`) REFERENCES `request_slip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order`
--

LOCK TABLES `purchase_order` WRITE;
/*!40000 ALTER TABLE `purchase_order` DISABLE KEYS */;
INSERT INTO `purchase_order` VALUES (1,'99','2017-05-26',NULL,700,2);
/*!40000 ALTER TABLE `purchase_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_slip`
--

DROP TABLE IF EXISTS `request_slip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_slip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rs_no` int(111) NOT NULL,
  `requested_by` varchar(255) NOT NULL COMMENT 'users.id',
  `date_needed` date DEFAULT NULL,
  `time_needed` varchar(10) DEFAULT '12:00 AM',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purpose` text NOT NULL COMMENT 'another word for reason',
  `status` varchar(255) NOT NULL COMMENT 'pending/cancelled/forPO/delivered/in-progrees/completed',
  `type` enum('ItemsNoPO','PO','Service') NOT NULL COMMENT 'The category',
  `ConcernedOffice` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_slip`
--

LOCK TABLES `request_slip` WRITE;
/*!40000 ALTER TABLE `request_slip` DISABLE KEYS */;
INSERT INTO `request_slip` VALUES (1,0,'Adam  Levine','2017-05-24','12:00 AM','2017-05-23 03:51:03','2017-05-23 03:51:03','for me to enable to pass this subject! ','Pending','ItemsNoPO','BS'),(2,1213,'Adam  Levine','2017-05-31','05:50 AM','2017-05-23 04:00:48','2017-05-23 04:00:48','para makapasaaaaaaa!!!','Pending','PO',NULL),(3,35,'Adam  Levine','2017-05-25','12:55 PM','2017-05-23 03:56:42','2017-05-23 03:56:42','para sa kinabukasan!','Pending','Service','bs');
/*!40000 ALTER TABLE `request_slip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `idServices` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) DEFAULT NULL,
  `status` enum('Pending','Canceled','Completed') NOT NULL,
  `remarks` text,
  `requestID` int(11) NOT NULL,
  `date_completed` date DEFAULT NULL,
  `service_provider` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idServices`),
  KEY `ServicesFKRequest_idx` (`requestID`),
  CONSTRAINT `ServicesFKRequest` FOREIGN KEY (`requestID`) REFERENCES `request_slip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (6,'hilot paa','Pending','nice one',3,NULL,'poodle'),(7,'hilot tuhod','Canceled','yow mama',3,'2017-05-31','3 months');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_details`
--

DROP TABLE IF EXISTS `user_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'users.id',
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_details_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_details`
--

LOCK TABLES `user_details` WRITE;
/*!40000 ALTER TABLE `user_details` DISABLE KEYS */;
INSERT INTO `user_details` VALUES (1,1,'Adam ','Levine','2017-05-11 01:34:10','2017-05-06 08:40:23'),(2,2,'JL','Black','2017-05-11 01:34:34','2017-05-07 09:58:34');
/*!40000 ALTER TABLE `user_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$wHcRpicZaO5kwPlxNAWuyO6XCdCu/DQOxAOMwxZOyPhLPeZtX8Rj2','2017-05-11 03:29:32','2017-05-06 08:40:23'),(2,'jl','$2y$10$NWfnEz.GRJgvqSbjqbMiFeqOESUtuXMwKtuiRF9slber5fegNnwGO','2017-05-07 09:58:34','2017-05-07 09:58:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'scis_requisition_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-05-23 12:05:19
