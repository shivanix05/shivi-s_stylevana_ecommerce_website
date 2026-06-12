-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: shivi-stylevana
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adminpanel`
--

DROP TABLE IF EXISTS `adminpanel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adminpanel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adminname` varchar(45) DEFAULT NULL,
  `pass` varchar(45) DEFAULT NULL,
  `role` enum('superadmin','manager','staff') DEFAULT 'staff',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adminpanel`
--

LOCK TABLES `adminpanel` WRITE;
/*!40000 ALTER TABLE `adminpanel` DISABLE KEYS */;
INSERT INTO `adminpanel` VALUES (1,'shivani','1234','staff','2026-03-26 07:12:49'),(2,'raj','1234','staff','2026-03-26 07:12:49'),(3,'times','1234','staff','2026-03-26 07:12:49');
/*!40000 ALTER TABLE `adminpanel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `pid` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cart_item` (`user_email`,`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (1,'raj@gmail.com',2,2,'2026-03-18 11:07:16'),(2,'raj@gmail.com',8,3,'2026-03-18 11:07:35'),(3,'raj@gmail.com',4,3,'2026-03-18 11:07:50'),(4,'raj@gmail.com',5,1,'2026-03-18 11:07:53'),(5,'raj@gmail.com',3,1,'2026-03-18 11:07:55'),(6,'raj@gmail.com',10,3,'2026-03-18 11:14:02'),(14,'rashi@gmail.com',8,10,'2026-03-24 08:44:56'),(27,'shivam@gmail',5,1,'2026-03-25 09:45:55'),(28,'shivam@gmail',8,1,'2026-03-25 11:19:40'),(29,'Monisha@gmail.com',8,1,'2026-03-31 08:05:03'),(34,'teju@gmail.com',3,1,'2026-04-04 06:43:39'),(36,'tejup@gmail.com',3,1,'2026-04-04 07:13:54'),(39,'tejup@gmail.com',6,1,'2026-04-04 07:43:20'),(46,'sanu@gmail.com',14,1,'2026-05-09 08:40:49'),(47,'sanu@gmail.com',6,1,'2026-05-09 08:40:58'),(48,'sanu@gmail.com',7,1,'2026-05-09 08:41:04'),(49,'sanu@gmail.com',8,1,'2026-05-09 09:46:46'),(59,'harshita@gmail.com',4,1,'2026-05-11 07:26:00'),(60,'modi@gmail.com',7,1,'2026-05-11 07:26:50'),(61,'p@gmail.com',4,1,'2026-05-11 07:31:09'),(62,'p@gmail.com',3,1,'2026-05-11 07:31:15'),(63,'p@gmail.com',6,1,'2026-05-11 08:37:32'),(64,'shivi.mishra1905@gmail.com',17,1,'2026-05-13 07:11:21'),(65,'shivi.mishra1905@gmail.com',6,2,'2026-05-13 07:11:23'),(66,'shivi.mishra1905@gmail.com',7,2,'2026-05-13 07:11:26'),(67,'shivi.mishra1905@gmail.com',8,2,'2026-05-13 08:15:59'),(68,'dubeyraj6760263@gmail.com',6,2,'2026-05-13 08:19:32'),(69,'dubeyraj6760263@gmail.com',15,2,'2026-05-13 08:19:43'),(70,'dubeyraj6760263@gmail.com',2,1,'2026-05-13 08:19:52'),(71,'dubeyraj6760263@gmail.com',5,1,'2026-05-13 08:36:03'),(72,'dubeyraj6760263@gmail.com',29,1,'2026-05-13 11:21:29'),(73,'shivanimishra1924@gmail.com',26,1,'2026-05-14 08:28:04'),(74,'shivanimishra1924@gmail.com',6,1,'2026-05-14 08:28:43'),(75,'a40991446@gmail.com',5,2,'2026-05-14 09:38:21'),(77,'a40991446@gmail.com',3,2,'2026-05-14 09:38:27'),(78,'a40991446@gmail.com',4,1,'2026-05-14 09:46:42'),(79,'a40991446@gmail.com',22,2,'2026-05-14 09:46:45'),(80,'a40991446@gmail.com',21,1,'2026-05-14 09:46:46'),(81,'a40991446@gmail.com',29,4,'2026-05-14 10:47:44'),(82,'shivanimishra1924@gmail.com',29,1,'2026-05-15 06:04:03'),(83,'shivanimishra1924@gmail.com',5,1,'2026-05-15 06:04:18'),(85,'shivanimishra1924@gmail.com',32,1,'2026-05-22 06:58:33');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) DEFAULT NULL,
  `pid` int DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `adddress` varchar(45) DEFAULT NULL,
  `productprice` varchar(45) DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `productphoto` varchar(500) DEFAULT NULL,
  `mobilenumber` varchar(20) DEFAULT NULL,
  `payment_method` varchar(45) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'Unpaid',
  `tracking_id` varchar(100) DEFAULT NULL,
  `shipping_charge` int DEFAULT '0',
  `call_status` varchar(20) DEFAULT 'pending',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'rashi@gmail.com',NULL,'raj dubey','kumeriya sagar road damoh mp','56',NULL,NULL,'9399227341','COD','2026-03-23 08:47:02','Pending','Unpaid',NULL,0,'pending'),(2,'rashi@gmail.com',NULL,'raj dubey','kumeriya sagar road damoh mp','550',NULL,NULL,'09399227341','','2026-03-23 09:08:17','Pending','Unpaid',NULL,0,'pending'),(3,'rashi@gmail.com',NULL,'raj dubey','kumeriya sagar road damoh mp','56',NULL,NULL,'9399227341','','2026-03-23 09:12:28','Pending','Unpaid',NULL,0,'pending'),(4,'rashi@gmail.com',8,'raj dubey','kumeriya sagar road damoh mp','23106',3,NULL,'9399227341','COD','2026-03-23 09:36:12','Pending','Unpaid',NULL,0,'pending'),(5,'rashi@gmail.com',8,'raj dubey','kumeriya sagar road damoh mp','23106',3,NULL,'9399227341','COD','2026-03-23 09:36:21','Pending','Unpaid',NULL,0,'pending'),(6,'rashi@gmail.com',7,'raj dubey','kumeriya sagar road damoh mp','1600',2,NULL,'9399227341','COD','2026-03-23 09:37:00','Pending','Unpaid',NULL,0,'pending'),(7,'rashi@gmail.com',4,'raj dubey','kumeriya sagar road damoh mp','666',1,NULL,'9399227341','Online (TXN: 123456789789)','2026-03-23 09:44:07','Pending','Unpaid',NULL,0,'pending'),(8,'rashi@gmail.com',2,'shivani mishra','kumeriya sagar road damoh mp','550',1,NULL,'6264204873','COD','2026-03-23 10:52:31','Pending','Unpaid',NULL,0,'pending'),(9,'rashi@gmail.com',8,'raj dubey','kumeriya sagar road damoh mp','1',1,NULL,'9399227341','COD','2026-03-24 06:07:04','Pending','Unpaid',NULL,0,'pending'),(10,'rashi@gmail.com',8,'raj dubey','kumeriya sagar road damoh mp','1',1,NULL,'9399227341','Online (ID: pay_SUxWmghV9wdg7w)','2026-03-24 06:11:19','Pending','Unpaid',NULL,0,'pending'),(11,'rashi@gmail.com',10,'raj dubey','kumeriya sagar road damoh mp','56',1,NULL,'9399227341','COD','2026-03-24 06:12:52','Pending','Unpaid',NULL,0,'pending'),(12,'rashi@gmail.com',7,'raj dubey','kumeriya sagar road damoh mp','2400',1,NULL,'9399227341','COD','2026-03-24 09:11:43','Pending','Unpaid',NULL,0,'pending'),(13,'shivam@gmail',8,'raj dubey','hinotA BANSA','3',1,NULL,'9399227341','COD','2026-03-25 07:19:04','Pending','Unpaid',NULL,0,'pending'),(14,'shivam@gmail',8,'raj dubey','kumeriya sagar road damoh mp','1',1,'product_images/php3C0B.tmp','9399227341','COD','2026-03-25 09:10:07','Pending','Unpaid',NULL,0,'pending'),(15,'shivam@gmail',5,'raj dubey','kumeriya sagar road damoh mp','4068',1,'product_images/php3AA2.tmp','9399227341','COD','2026-03-25 09:10:48','Pending','Unpaid',NULL,0,'pending'),(16,'shivam@gmail',2,'raj dubey','kumeriya sagar road damoh mp','1650',1,'product_images/php4D8C.tmp','9399227341','COD','2026-03-25 09:13:56','Pending','Unpaid',NULL,0,'pending'),(17,'shivam@gmail',10,'raj dubey','kumeriya sagar road damoh mp','56',1,'product_images/jewllary2.png','9399227341','COD','2026-03-25 09:17:44','Pending','Unpaid',NULL,0,'pending'),(18,'shivam@gmail',7,'sanmati soni','jabapur','800',1,'product_images/phpD0DF.tmp','9098701533','COD','2026-03-25 09:33:47','Pending','Unpaid',NULL,0,'pending'),(19,'shivam@gmail',10,'rashi gupta','3 gulli','56',1,'product_images/jewllary2.png','9424623829','COD','2026-03-25 09:36:12','Pending','Unpaid',NULL,0,'pending'),(20,'shivam@gmail',10,'anuragi rai','aam chopra','56',1,'product_images/jewllary2.png','6263772063','COD','2026-03-25 09:39:26','Pending','Unpaid',NULL,0,'pending'),(21,'shivam@gmail',2,'raj dubey','kumeriya sagar road damoh mp','550',1,'product_images/php4D8C.tmp','9399227341','COD','2026-03-25 09:57:36','Pending','Unpaid',NULL,0,'pending'),(22,'shivam@gmail',4,'raj dubey','kumeriya sagar road damoh mp','666',1,'product_images/php3576.tmp','9399227341','COD','2026-03-25 09:57:36','Pending','Unpaid',NULL,0,'pending'),(23,'shivam@gmail',8,'raj dubey','kumeriya sagar road damoh mp','1',4,'product_images/php3C0B.tmp','9399227341','COD','2026-03-25 09:57:36','Delivered','Unpaid','',0,'pending'),(24,'Monisha@gmail.com',8,'Mohisha Patel','Vijay nagar civil lies','1',1,'product_images/php3C0B.tmp','6648456749','COD','2026-03-31 08:12:04','Pending','Unpaid',NULL,0,'pending'),(25,'teju@gmail.com',5,'tejashwini khare','professor colony','1356',1,'product_images/php3AA2.tmp','9658754557','COD','2026-04-01 06:23:43','Placed','Pending',NULL,0,'pending'),(26,'teju@gmail.com',4,'tejashwini khare','professor colony','666',1,'product_images/php3576.tmp','9658754557','COD','2026-04-01 06:25:23','Placed','Pending',NULL,0,'pending'),(27,'teju@gmail.com',5,'tejashwini khare','professor colony','678',1,'product_images/php3AA2.tmp','9658754557','COD','2026-04-01 06:39:32','Placed','Pending',NULL,0,'pending'),(28,'teju@gmail.com',10,'tejashwini khare','professor colony','56',1,'product_images/jewllary2.png','9658754557','COD','2026-04-01 06:43:41','Cancelled','Pending','',0,'pending'),(29,'teju@gmail.com',4,'tejashwini khare','professor colony','666',1,'product_images/php3576.tmp','9658754557','COD','2026-04-01 09:20:14','Placed','Pending',NULL,0,'pending'),(30,'teju@gmail.com',5,'tejashwini khare','professor colony','678',1,'product_images/php3AA2.tmp','9658754557','COD','2026-04-01 09:37:55','Placed','Pending',NULL,0,'pending'),(31,'teju@gmail.com',6,'tejashwini khare','professor colony','100',1,'product_images/php79BB.tmp','9658754557','COD','2026-04-01 09:37:55','Placed','Pending',NULL,0,'pending'),(32,'teju@gmail.com',14,'tejashwini khare','professor colony','100',1,'product_images/1775033549_sweater','9658754557','COD','2026-04-01 09:37:55','Placed','Pending',NULL,0,'pending'),(33,'teju@gmail.com',4,'tejashwini khare','professor colony','666',1,'product_images/php3576.tmp','9658754557','COD','2026-04-01 09:47:57','Placed','Pending',NULL,0,'pending'),(34,'teju@gmail.com',4,'tejashwini khare','professor colony','666',1,'product_images/php3576.tmp','9658754557','Online','2026-04-01 09:48:04','Placed','Completed',NULL,0,'pending'),(35,'teju@gmail.com',4,'tejashwini khare','professor colony','666',1,'product_images/php3576.tmp','9658754557','Online','2026-04-01 09:48:10','Placed','Completed',NULL,0,'pending'),(36,'teju@gmail.com',10,'tejashwini khare','professor colony','56',1,'product_images/jewllary2.png','9658754557','COD','2026-04-01 09:58:06','Placed','Pending',NULL,0,'pending'),(37,'teju@gmail.com',10,'tejashwini khare','professor colony','56',1,'product_images/jewllary2.png','9658754557','Online','2026-04-01 09:58:11','Placed','Completed',NULL,0,'pending'),(38,'teju@gmail.com',10,'tejashwini khare','professor colony','56',1,'product_images/jewllary2.png','9658754557','Online','2026-04-01 09:58:21','Placed','Completed',NULL,0,'pending'),(39,'teju@gmail.com',8,'tejashwini khare','professor colony','1',1,NULL,'9658754557','Online (ID: pay_SYBmNGV36CaSUZ)','2026-04-01 10:52:30','Cancelled','Unpaid','',0,'pending'),(40,'teju@gmail.com',8,'tejashwini khare','professor colony','1',1,'product_images/php3C0B.tmp','9658754557','COD','2026-04-01 10:53:25','Placed','Pending',NULL,0,'pending'),(41,'teju@gmail.com',7,'tejashwini khare','professor colony','800',1,'product_images/phpD0DF.tmp','9658754557','COD','2026-04-01 11:06:59','Placed','Pending',NULL,0,'pending'),(42,'teju@gmail.com',8,'tejashwini khare','professor colony','1',1,'product_images/php3C0B.tmp','9658754557','Online (ID: pay_SZJNAQDtMumfDm)','2026-04-04 06:09:27','Cancelled','Unpaid','',0,'pending'),(43,'teju@gmail.com',7,'tejashwini khare','professor colony','800',1,'product_images/phpD0DF.tmp','9658754557','Online (ID: pay_SZJOUpoiYWaZVF)','2026-04-04 06:10:43','Pending','Unpaid','',0,'pending'),(44,'teju@gmail.com',8,'tejashwini khare','professor colony','1',1,'product_images/php3C0B.tmp','9658754557','COD','2026-04-04 06:19:30','Cancelled','Pending','',0,'pending'),(45,'teju@gmail.com',6,'tejashwini khare','professor colony','100',1,'product_images/php79BB.tmp','9658754557','Online (ID: pay_SZJdcmhdktUJgn)','2026-04-04 06:25:02','placed','Unpaid',NULL,0,'pending'),(46,'tejup@gmail.com',4,'tejashwini khare','professor colony','666',1,'product_images/php3576.tmp','9658754557','COD','2026-04-04 08:17:03','Placed','Pending',NULL,0,'pending'),(47,'tejup@gmail.com',4,'tejashwini khare','professor colony','666',1,'product_images/php3576.tmp','9658754557','Online (ID: pay_SZLYsdvvVuNfB3)','2026-04-04 08:17:56','placed','Unpaid',NULL,0,'pending'),(48,'sanu@gmail.com',8,'shivam parihar','hinotA BANSA','1',1,'product_images/php3C0B.tmp','9860419889','COD','2026-04-29 09:45:19','Placed','Pending',NULL,0,'pending'),(49,'sanu@gmail.com',0,'shivam parihar','hinotA BANSA','100',1,'','9860419889','Online (ID: pay_SmN4fbjPgyfDA4)','2026-05-07 06:13:50','Cancelled','Unpaid','',0,'pending'),(50,'sanu@gmail.com',6,'shivam parihar','hinotA BANSA','100',1,'product_images/php79BB.tmp','9860419889','Online (ID: pay_SmN8YZ2ICKrMI6)','2026-05-07 06:17:25','placed','Unpaid',NULL,0,'pending'),(51,'sanu@gmail.com',8,'shivam parihar','hinotA BANSA','1',1,'product_images/php3C0B.tmp','9860419889','Online (ID: pay_SmN9HNUlaCFF7r)','2026-05-07 06:18:05','placed','Unpaid',NULL,0,'pending'),(52,'sanu@gmail.com',3,'shivam parihar','hinotA BANSA','1700',1,'product_images/php30E8.tmp','9860419889','Online (ID: pay_SmNBcJ7tLgXrui)','2026-05-07 06:20:17','Processing','Unpaid','12365',0,'pending'),(53,'sanu@gmail.com',8,'shivam parihar','hinotA BANSA','1',1,'product_images/php3C0B.tmp','9860419889','COD','2026-05-08 07:52:15','Placed','Pending',NULL,0,'pending'),(54,'sanu@gmail.com',8,'shivam parihar','hinotA BANSA','1',1,'product_images/php3C0B.tmp','9860419889','COD','2026-05-08 07:53:41','Placed','Pending',NULL,0,'pending'),(55,'sanu@gmail.com',8,'shivam parihar','hinotA BANSA','1',1,'product_images/php3C0B.tmp','9860419889','Online (ID: pay_SmnLlDUfqaipGq)','2026-05-08 07:56:00','placed','Unpaid',NULL,0,'pending'),(56,'sanu@gmail.com',5,'shivam parihar','hinotA BANSA','678',1,'product_images/php3AA2.tmp','9860419889','COD','2026-05-08 10:06:52','Placed','Pending',NULL,0,'pending'),(57,'sanu@gmail.com',7,'shivam parihar','hinotA BANSA','800',1,'product_images/phpD0DF.tmp','6264204873','COD','2026-05-08 10:08:41','Placed','Pending',NULL,0,'pending'),(58,'sanu@gmail.com',7,'shivam parihar','hinotA BANSA','800',1,'product_images/phpD0DF.tmp','6264204873','COD','2026-05-08 10:09:16','Placed','Pending',NULL,0,'pending'),(59,'sanu@gmail.com',4,'shivam parihar','hinotA BANSA','666',1,'product_images/php3576.tmp','916264204873','COD','2026-05-08 10:36:44','Placed','Pending',NULL,0,'pending'),(60,'sanu@gmail.com',14,'shivam parihar','hinotA BANSA','200',1,'product_images/1775033549_sweater','6264204873','COD','2026-05-08 10:37:29','Placed','Pending',NULL,0,'pending'),(61,'sanu@gmail.com',14,'shivam parihar','hinotA BANSA','200',1,'product_images/1775033549_sweater','6264204873','Online (ID: pay_Smq7jQjsX3ZCCX)','2026-05-08 10:38:45','placed','Unpaid',NULL,0,'pending'),(62,'sanu@gmail.com',14,'shivam parihar','hinotA BANSA','200',1,'product_images/1775033549_sweater','+916264204873','COD','2026-05-08 10:42:51','Placed','Pending',NULL,0,'pending'),(63,'sanu@gmail.com',7,'shivam parihar','hinotA BANSA','800',1,'product_images/phpD0DF.tmp','+916264204873','COD','2026-05-08 11:05:43','Placed','Pending',NULL,0,'pending'),(64,'sanu@gmail.com',4,'shivam parihar','hinotA BANSA','666',1,'product_images/php3576.tmp','+916264204873','COD','2026-05-08 11:09:04','Placed','Pending',NULL,0,'pending'),(65,'sanu@gmail.com',8,'shivam parihar','hinotA BANSA','1',1,'product_images/php3C0B.tmp','+916264204873','COD','2026-05-08 11:11:00','Placed','Pending',NULL,0,'pending'),(66,'sanu@gmail.com',6,'shivam parihar','hinotA BANSA','100',1,'product_images/php79BB.tmp','+916264204873','COD','2026-05-09 06:39:22','Placed','Pending',NULL,0,'pending'),(67,'p@gmail.com',14,'peratiska jaim','nagpur','200',1,'product_images/1775033549_sweater','+919545675454','Online (ID: pay_Snzd8KLwZ89i6y)','2026-05-11 08:35:53','Cancelled','Unpaid','',0,'pending'),(68,'shivanimishra1924@gmail.com',15,'peratiska sharma','nagpur','900',1,'product_images/1778487938_sdxfg.webp','+919545675454','Online (ID: pay_Sokro4dWGUX7gS)','2026-05-13 06:48:18','placed','Unpaid',NULL,0,'pending'),(69,'dubeyraj6760263@gmail.com',5,'peratiska jaim','parliament','678',1,'product_images/php3AA2.tmp','+919999900000','COD','2026-05-13 09:43:14','Placed','Pending',NULL,0,'pending'),(70,'dubeyraj6760263@gmail.com',4,'peratiska jaim','parliament','666',1,'product_images/php3576.tmp','+919999900000','COD','2026-05-13 09:58:20','Placed','Pending',NULL,0,'pending'),(71,'dubeyraj6760263@gmail.com',16,'peratiska jaim','parliament','100',1,'product_images/1778492913_24272_S2-8901030979545_0352b976-bf4b-4b4c-93cd-23f8204ccdd4.webp','+919999900000','COD','2026-05-13 10:39:24','Placed','Pending',NULL,0,'pending'),(72,'dubeyraj6760263@gmail.com',15,'peratiska jaim','parliament','900',1,'product_images/1778487938_sdxfg.webp','+919999900000','COD','2026-05-13 10:39:52','Placed','Pending',NULL,0,'pending'),(73,'shivanimishra1924@gmail.com',32,'peratiska sharma','nagpur','149',1,'product_images/1778745655_1_e2b57832-8f88-43e7-9a47-8a547cada069.webp','+919545675454','COD','2026-05-14 08:03:46','Placed','Pending',NULL,0,'pending'),(74,'a40991446@gmail.com',4,'Anuragi rai','aam chopra','666',1,'product_images/php3576.tmp','+919245465456','Online (ID: pay_SpCQH1zy76T6i1)','2026-05-14 09:45:45','Cancelled','Unpaid','',0,'pending'),(75,'shivanimishra1924@gmail.com',31,'peratiska sharma','nagpur','2',1,'product_images/1778743773_1bf5f8921506f5d35cfeaadcca58f202.jpg','+919545675454','COD','2026-05-15 09:36:37','Placed','Pending',NULL,0,'pending'),(76,'shivanimishra1924@gmail.com',32,'peratiska sharma','nagpur','149',1,'product_images/1778745655_1_e2b57832-8f88-43e7-9a47-8a547cada069.webp','+919545675454','Online (ID: pay_SsICjkQDVtbVi8)','2026-05-21 09:16:58','placed','Unpaid',NULL,0,'pending');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queries`
--

DROP TABLE IF EXISTS `queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queries`
--

LOCK TABLES `queries` WRITE;
/*!40000 ALTER TABLE `queries` DISABLE KEYS */;
/*!40000 ALTER TABLE `queries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `rid` int NOT NULL AUTO_INCREMENT,
  `pid` int NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `rating` int NOT NULL,
  `comment` text NOT NULL,
  `rev_photo` text,
  `admin_reply` text,
  `reply_date` timestamp NULL DEFAULT NULL,
  `review_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`rid`),
  KEY `pid` (`pid`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `shop` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (2,4,'shivam@gmail',2,'bad',NULL,NULL,NULL,'2026-03-24 11:00:14',0),(6,14,'p@gmail.com',4,'tf76f',NULL,NULL,NULL,'2026-05-11 08:34:28',0),(7,8,'shivi.mishra1905@gmail.com',5,'nice',NULL,NULL,NULL,'2026-05-13 07:13:38',0),(8,16,'dubeyraj6760263@gmail.com',5,'great product',NULL,NULL,NULL,'2026-05-13 08:52:27',0),(9,5,'dubeyraj6760263@gmail.com',3,'it was great',NULL,NULL,NULL,'2026-05-13 09:55:25',0),(10,4,'dubeyraj6760263@gmail.com',4,'supereb','uploads/reviews/1778666424_4.png',NULL,NULL,'2026-05-13 10:00:25',0),(11,15,'dubeyraj6760263@gmail.com',5,'great product','uploads/reviews/1778668819_15.webp',NULL,NULL,'2026-05-13 10:40:19',0),(12,32,'shivanimishra1924@gmail.com',4,'it was really great','uploads/reviews/1778745915_32.jfif',NULL,NULL,'2026-05-14 08:05:15',0),(13,4,'a40991446@gmail.com',5,'awesome','',NULL,NULL,'2026-05-14 09:46:24',0),(14,15,'shivanimishra1924@gmail.com',3,'glo','uploads/reviews/1778837068_15_0.webp',NULL,NULL,'2026-05-15 09:24:28',1);
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop`
--

DROP TABLE IF EXISTS `shop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shop` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `productname` varchar(255) DEFAULT NULL,
  `brand_name` varchar(100) DEFAULT 'Fenty Beauty',
  `productphoto` varchar(500) DEFAULT NULL,
  `productdescription` text,
  `category` varchar(45) DEFAULT NULL,
  `productprice` int DEFAULT NULL,
  `delivery_type` varchar(50) DEFAULT 'Free Delivery',
  `original_price` decimal(10,2) DEFAULT NULL,
  `offer_text` varchar(50) DEFAULT NULL,
  `stock_qty` int DEFAULT '10',
  `is_featured` tinyint(1) DEFAULT '0',
  `photo2` varchar(300) DEFAULT NULL,
  `photo3` varchar(300) DEFAULT NULL,
  `photo4` varchar(300) DEFAULT NULL,
  `photo5` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop`
--

LOCK TABLES `shop` WRITE;
/*!40000 ALTER TABLE `shop` DISABLE KEYS */;
INSERT INTO `shop` VALUES (2,'Rosy Blush','Fenty Beauty','product_images/php4D8C.tmp','this is a rosy blush','Makeup',550,'Free Shipping',500.00,'B1G!',10,0,NULL,NULL,NULL,NULL),(3,'Blue baggy jeans','Fenty Beauty','product_images/php30E8.tmp','ITs a blue baggy jeans comfertable','clothing',1700,'Free Shipping',1700.00,'700',10,0,NULL,NULL,NULL,NULL),(4,'H&M JEANS','Fenty Beauty','product_images/php3576.tmp','jeans','clothing',666,'Free Shipping',666.00,'600',2,0,NULL,NULL,NULL,NULL),(5,'Khadii dark Denim','Fenty Beauty','product_images/php3AA2.tmp','Super comfertable jeans ','clothing',678,'Free Shipping',1200.00,'500',6,0,NULL,NULL,NULL,NULL),(6,'Gold ring stack','Fenty Beauty','product_images/php79BB.tmp','5 prenium stack rings\r\n','jewellery',100,'Free Delivery',1000.00,'900',8,0,NULL,NULL,NULL,NULL),(7,'Simply skincare kit','Fenty Beauty','product_images/phpD0DF.tmp','For oily combination skin \r\n','skincare',800,'Free Delivery',NULL,NULL,6,0,NULL,NULL,NULL,NULL),(8,'Channel','Fenty Beauty','product_images/php3C0B.tmp','channel foundation','Makeup',1,'Free Shipping',0.00,'B1G2',4,0,NULL,NULL,NULL,NULL),(14,'guciiii sweater','Guchi','product_images/1775033549_sweater','A beutiful swaeter pinkl','clothing',200,'Free Shipping',5000.00,'B1G2',8,0,NULL,NULL,NULL,NULL),(15,'Buy Face Makeup Products Online In India - ','LakméIndia – Lakmē','product_images/1778487938_sdxfg.webp','Please enter PIN code to check delivery time & Pay on Delivery Availability\r\n\r\n100% Original Products\r\nPay on delivery might be available\r\nEasy 14 days exchanges\r\nThis item is only exchangeable for the same or a different size, if available, and cannot be returned','Makeup',900,'50',1000.00,'B1G2',9,0,NULL,NULL,NULL,NULL),(16,'Midnight Kohl Kajal Pencil - 1.5g - Black','LakméIndia – Lakmē','product_images/1778492913_24272_S2-8901030979545_0352b976-bf4b-4b4c-93cd-23f8204ccdd4.webp','Colour Shade Name\r\nBlack\r\nFeatures\r\nSmudge Proof\r\nFinish\r\nMatte\r\nFormulation\r\nPencil\r\nNet Quantity Unit\r\ng\r\nPreferences\r\nCruelty-Free','Makeup',100,'Free Shipping',500.00,'B1G2',9,0,NULL,NULL,NULL,NULL),(17,'Brown Smudge Wont Budge Lip Crayon','MARS ','product_images/1778493270_12916_H-8901030978029.webp','100% Original Products\r\nPay on delivery might be available\r\nEasy 7 days exchanges\r\nThis item is only exchangeable for the same or a different size, if available, and cannot be returned','Makeup',100,'100',199.00,'B1G2',5,0,NULL,NULL,NULL,NULL),(19,'Eternal Grace Gold-Plated Bridal Bangles','Rose jewllers','product_images/1778669573_9dacf6c26efd6b0293285937a566ccd1.jpg','Elevate your ethnic ensemble with these beautifully crafted bangles. Featuring intricate traditional patterns and a premium gold finish, they are designed to add a touch of royal elegance to your festive wardrobe.\r\n\r\nMaterial: High-grade alloy with long-lasting gold plating.\r\n\r\nCraftsmanship: Detailed Indian traditional motifs.\r\n\r\nStyle Tip: Pair them with a silk saree or a heavy lehenga for a complete wedding seasons.','jewellery',999,'Standard Delivery (₹50)',2999.00,'Flat 80% off',10,0,NULL,NULL,NULL,NULL),(20,'Sleek Everyday Rose hand chain','Rokde jewllers','product_images/1778669687_1bf5f8921506f5d35cfeaadcca58f202.jpg','Simplicity meets sophistication. These minimalist bangles are designed for the woman who loves \"less is more.\" Lightweight and comfortable, they are perfect for transitioning from a professional office look to a casual evening out.\r\n\r\nFinish: High-polish rose gold.\r\n\r\nFit: Ergonomic design for all-day comfort.\r\n\r\nVibe: Modern, professional, and chic.','jewellery',1999,'Express Delivery (₹100)',5999.00,'Flat 80% off',100,0,NULL,NULL,NULL,NULL),(21,'Sleek Everyday Hamd kada','Rokde jewllers','product_images/1778669751_jewllery4.png','Catch the light and everyone’s attention! These crystal-studded bangles offer a stunning sparkle, making them the perfect accessory for parties, dinners, or special occasions.\r\n\r\nDesign: Dainty crystals with a secure, easy-to-wear lock.\r\n\r\nStyling: Layer them together for a trendy \"stacked\" look or wear them solo for a subtle shimmer.\r\n\r\nDurability: Skin-friendly and tarnish-resistant for long-term wear.','jewellery',1999,'Free Shipping',5999.00,'Flat 80% off',100,0,NULL,NULL,NULL,NULL),(22,'Stylevana Velvet Touch neclace Set','Tanisk jewllers','product_images/1778669842_jewllary2.png','Experience the timeless charm of our velvet-finish bangles. These sets are curated to provide a rich, soft texture that feels as good as it looks. Available in a wide range of vibrant shades to match every outfit in your closet.\r\n\r\nTexture: Soft velvet coating on a durable glass base.\r\n\r\nQuantity: Available in sets of 12, 24, or 48.\r\n\r\nSpecialty: Lightweight and rash-free on the skin.','jewellery',2999,'Free Shipping',4999.00,'Exclusive',100,0,NULL,NULL,NULL,NULL),(23,'Radiance Boost Vitamin C & Hyaluronic Acid Serum','Pilgrim','product_images/1778669941_skincare4.png','Reveal your natural glow with our high-potency Vitamin C serum. This lightweight, non-greasy formula penetrates deep into the skin to fade dark spots, even out skin tone, and provide intense hydration.\r\n\r\nKey Benefits: Brightens complexion, reduces pigmentation, and plumps the skin.\r\n\r\nSkin Type: Suitable for all skin types (Oily, Dry, and Combination).\r\n\r\nHow to Use: Apply 3-4 drops on a clean face before moisturizing. Use daily for a \"lit-from-within\" glow.','skincare',299,'Free Shipping',399.00,'Flat 10% off',99,0,NULL,NULL,NULL,NULL),(24,'Radiance Boost Vitamin  A & Ritinol Acid Serum','Pilgrim','product_images/1778669963_skincare3.png','Reveal your natural glow with our high-potency Vitamin C serum. This lightweight, non-greasy formula penetrates deep into the skin to fade dark spots, even out skin tone, and provide intense hydration.\r\n\r\nKey Benefits: Brightens complexion, reduces pigmentation, and plumps the skin.\r\n\r\nSkin Type: Suitable for all skin types (Oily, Dry, and Combination).\r\n\r\nHow to Use: Apply 3-4 drops on a clean face before moisturizing. Use daily for a \"lit-from-within\" glow.','skincare',399,'Free Shipping',399.00,'Flat 10% off',99,0,NULL,NULL,NULL,NULL),(25,'Mcaffin 24-Hour Intense Hydration Cream','Mcaffin','product_images/1778670373_skincare2.png','Say goodbye to dry, dull skin. Our Aqua-Luxe moisturizer is enriched with Ceramide complexes that lock in moisture and strengthen your skin\'s natural barrier. It leaves your face feeling velvety soft without any sticky residue.\r\n\r\nKey Benefits: Long-lasting moisture, prevents water loss, and improves skin texture.\r\n\r\nFeatures: Paraben-free, lightweight, and fast-absorbing.\r\n\r\nPro Tip: Best applied on slightly damp skin for maximum absorption.','skincare',499,'Standard Delivery (₹50)',699.00,'B1G2',19,0,NULL,NULL,NULL,NULL),(26,'Velvet Matte Liquid Lipstick – Intense Pigment','LakméIndia – Lakmē','product_images/1778670544_makeup4.png','Define your pout with our high-definition Velvet Matte Liquid Lipstick. This weightless formula glides on like silk and dries down to a gorgeous, transfer-proof matte finish that stays flawless from your morning coffee to your evening dinner.\r\n\r\nKey Benefits: Smudge-proof, non-drying formula, and 12-hour long wear.\r\n\r\nFinish: Luxurious velvet matte.\r\n\r\nPro Tip: For a clean look, line your lips with a matching liner before filling in with the liquid color.','Makeup',100,'Free Shipping',159.00,'Flat 5% off',99,0,NULL,NULL,NULL,NULL),(27,'Midnight Bold Waterproof Mascara','Maybelin','product_images/1778670684_makeup2.png','Make your eyes do the talking! Our Midnight Bold eyeliner delivers an intense, jet-black finish in just one stroke. Whether you’re creating a sharp wing or a smokey smudge, this smudge-proof formula won’t budge all day.\r\n\r\nKey Benefits: 24-hour wear, waterproof, and safe for sensitive eyes.\r\n\r\nFinish: Deep Carbon Black.\r\n\r\nVibe: Bold, dramatic, and precise.','Makeup',200,'Free Shipping',499.00,'Flat 40% off',100,0,NULL,NULL,NULL,NULL),(28,'Silk-Finish Translucent Setting Powder','LakméIndia – Lakmē','product_images/1778670802_lakme-moisturizer-and-color-transform-cream.webp','Lock your look in place with our ultra-lightweight setting powder. Designed to blur pores and control shine, it gives your makeup a smooth, airbrushed finish without looking \"cakey\" or heavy.\r\n\r\nKey Benefits: Absorbs excess oil, minimizes the appearance of pores, and prevents makeup from creasing.\r\n\r\nFeature: Flashback-safe (looks perfect in photos!).\r\n\r\nBest For: Setting concealer and foundation for an all-day matte look.','Makeup',499,'Free Shipping',699.00,'Flat 30% off',200,0,NULL,NULL,NULL,NULL),(29,'Shimmering Crystal Stacking Rings','Rokde jewllers','product_images/1778670930_21ace41cad98809824fb1d4be4413cc7.0000000.jpg','Add a touch of sparkle to your night out! Ye crystal-studded bangles light mein reflect hokar aapko ek stunning look dete hain. Perfect for parties, dinners, and special dates.\r\n\r\nDesign: Dainty crystals with a secure lock.\r\n\r\nStyle: Layer them up for a \"Pinterest-inspired\" stacked look.\r\n\r\nDurability: Skin-friendly and tarnish-resistant.','jewellery',2999,'Free Shipping',15999.00,'Flat 90% off',20,0,NULL,NULL,NULL,NULL),(30,'Stardust Lumi-Glow Highlighter Palette','LakméIndia – Lakmē','product_images/1778671057_23657_H-8901030983429.webp','Get that \"lit-from-within\" radiance with our Stardust Highlighter Palette. Featuring ultra-fine shimmer particles, these shades blend seamlessly into the skin to catch the light at every angle. Perfect for adding a soft glow or a blinding strobe effect.\r\n\r\nKey Benefits: Buildability from sheer to intense, suits all skin tones, and zero chunky glitter.\r\n\r\nShades: Includes Champagne, Rose Gold, and Bronze tones.\r\n\r\nApplication Area: Apply to the high points of your face—cheekbones, brow bones, and the bridge of your nose.','Makeup',300,'Free Shipping',300.00,'',20,0,NULL,NULL,NULL,NULL),(31,'Buy Face Makeup Products Online In India -','LakméIndia – Lakmē','product_images/1778743773_1bf5f8921506f5d35cfeaadcca58f202.jpg','','jewellery',2,'Free Shipping',100.00,'B1G!',19,1,'product_images/1778743773_2_9dacf6c26efd6b0293285937a566ccd1.jpg','product_images/1778743773_3_12916_H-8901030978029.webp','product_images/1778743773_4_21ace41cad98809824fb1d4be4413cc7.0000000.jpg','product_images/1778743773_5_12916_H-8901030978029.webp'),(32,'Dot & Key Barrier Repair Hydrating Lip Balm SPF 50 | Cherry Crimson 4.5 gm','Dont &key','product_images/1778745655_1_e2b57832-8f88-43e7-9a47-8a547cada069.webp','Meltie SPF50+ PA+++ Lip Balm with Shea & Mango Butter 4 g - Berry Crumble\r\nSpecifications\r\nColour Shade Name\r\nBerry Crumble\r\nConcerns\r\nChapped Lips, Sun Protection, Dryness\r\nFinish\r\nGlossy\r\nFormulation\r\nBalm\r\nKey Ingredients\r\nShea Butter, Vitamin C, Ceramides\r\nNet Quantity\r\n4\r\nNet Quantity Unit\r\ng\r\nPreferences\r\nAlcohol-Free\r\nSPF\r\nAbove 50\r\nSustainable\r\nRegular','Makeup',149,'Free Shipping',399.00,'saves ₹250 (63% OFF)',9,1,'product_images/1778745655_2_4_f28ce8f1-d7c1-4c74-9dbd-22e84d86f764.webp','product_images/1778745655_3_3_82a9bb3f-e847-4421-8188-39e37b3715a3.webp','product_images/1778745655_4_dot-and-key-barrier-repair-hydrating-lip-balm-spf-50-cherry-crimson-4-5-gm_3_display_1767435285_0dbfeb89.webp','');
/*!40000 ALTER TABLE `shop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_update_history`
--

DROP TABLE IF EXISTS `user_update_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_update_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_sno` int DEFAULT NULL,
  `field_name` varchar(50) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_update_history`
--

LOCK TABLES `user_update_history` WRITE;
/*!40000 ALTER TABLE `user_update_history` DISABLE KEYS */;
INSERT INTO `user_update_history` VALUES (1,7,'userphoto','1774942508_profile.jfif','1774943368_user.png','2026-03-31 07:49:28'),(2,7,'userphoto','1774943368_user.png','1774943370_user.png','2026-03-31 07:49:30'),(3,7,'name','Monisha dagore','Mohisha Patel','2026-03-31 08:00:14'),(4,7,'mobilenumber','6648456745','6648456749','2026-03-31 08:00:14'),(5,7,'address','Vijay nagar','Vijay nagar civil lies','2026-03-31 08:00:14'),(6,9,'userphoto','1775022687_jeans3.png','1775023002_user.png','2026-04-01 05:56:42'),(7,9,'gmail','teju@gmail.com','tejup@gmail.com','2026-04-04 07:02:43'),(8,10,'userphoto','','1778134532_user.png','2026-05-07 06:15:32'),(9,10,'mobilenumber','9860419889','6264204873','2026-05-08 10:08:23'),(10,14,'name','peratiska jaim','peratiska sharma','2026-05-13 06:45:27'),(11,14,'userphoto','','1778747570_user.png','2026-05-14 08:32:50'),(12,17,'userphoto','','1778751459_user.png','2026-05-14 09:37:39'),(13,14,'name','peratiska sharma','peratiska jaIN','2026-05-21 09:18:45');
/*!40000 ALTER TABLE `user_update_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userdetail`
--

DROP TABLE IF EXISTS `userdetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userdetail` (
  `sno` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `address` varchar(45) NOT NULL,
  `mobilenumber` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `pincode` varchar(45) NOT NULL,
  `age` varchar(45) NOT NULL,
  `gmail` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `confirmpassword` varchar(45) DEFAULT NULL,
  `userphoto` varchar(45) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sno`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userdetail`
--

LOCK TABLES `userdetail` WRITE;
/*!40000 ALTER TABLE `userdetail` DISABLE KEYS */;
INSERT INTO `userdetail` VALUES (1,'raj dubey','kumeriya','09399227341','mp','damoh','447341','26','raj@gmail.com','raj','raj','uploads/1773828725_jeans1.png',NULL,0),(2,'vshukla','shyam nagar','925639978145785','wqdewvf ','damoh','470661','15','vshukla@gmail.com','000000','000000',NULL,NULL,0),(3,'rashi','imlai','9632587415','mp','damoh','447341','26','rashi@gmail.com','123456','123456','uploads/1774250326_jeans2.png',NULL,0),(4,'shivam parihar','Hinout Bansa tarkheda','9860419889','MP','damoh','447341','23','shivam@gmail','SHIVAM1234','SHIVAM1234','uploads/1774344179_jeans1.png',NULL,0),(5,'Pradunya','nagpur','9545675454','mp','ornage city','447702','21','pradunya@gmail.com','123456','123456','1774940777_profile.jfif',NULL,0),(6,'Neha dubey ','buildi','9684575648','MAHRATSRA ','NAGPUR','440012','21','Aarya@gmail.com','1234','1234','1774941786_profile.jfif',NULL,0),(7,'Mohisha Patel','Vijay nagar civil lies','6648456749','MP','Damoh','442056','23','Monisha@gmail.com','0000','0000','1774943370_user.png',NULL,0),(8,'Monisha dagore','Vijay nagar','6648456745','MP','Damoh','442056','23','Monishaa@gmail.com','1234','1234','',NULL,0),(9,'tejashwini khare','professor colony','9658754557','madhyapradesh','damoh','440062','23','tejup@gmail.com','teju1234','teju1234','1775023002_user.png',NULL,0),(10,'shivam parihar','hinotA BANSA','6264204873','MP','damoh','447341','21','sanu@gmail.com','1234','1234','1778134532_user.png',NULL,0),(11,'Harshita Asati ','bakoli chouraha damoh madhya pradesh ','9171906379','mp','damoh','447702','21','harshita@gmail.com','h1234','h1234','',NULL,0),(12,'narendra modi','parliament','9999900000','DELHI','delhi','447702','21','modi@gmail.com','1234','1234','',NULL,0),(13,'peratiska jaim','nagpur','9545675454','mp','ornage city','447702','21','p@gmail.com','p1234','p1234','',NULL,0),(14,'peratiska jaIN','nagpur','9545675454','mp','ornage city','447702','21','shivanimishra1924@gmail.com','12345678','12345678','1778747570_user.png',NULL,1),(15,'peratiska jaim','nagpur','9545675454','mp','ornage city','447702','21','shivi.mishra1905@gmail.com','12345678','12345678','',NULL,1),(16,'peratiska jaim','parliament','9999900000','DELHI','delhi','447702','21','dubeyraj6760263@gmail.com','12345678','12345678','',NULL,1),(17,'Anuragi rai','aam chopra','9245465456','MP','damoh','447341','22','a40991446@gmail.com','123456','123456','1778751459_user.png',NULL,1);
/*!40000 ALTER TABLE `userdetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userfeedback`
--

DROP TABLE IF EXISTS `userfeedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userfeedback` (
  `fid` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `gmail` varchar(150) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `feedback` text,
  `admin_reply` text,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userfeedback`
--

LOCK TABLES `userfeedback` WRITE;
/*!40000 ALTER TABLE `userfeedback` DISABLE KEYS */;
INSERT INTO `userfeedback` VALUES (1,'monsiha','Monisha@gmail.com','payment','payment is failing','','we are working on that  \r\nThankyou for reaching us \r\n','2026-03-31 09:14:27'),(2,'teju','teju@gmail.com','payment','my paymnet is not been sucessfull gateway is giving problem','need to inprove login page ','oh i ams orry for the inconvience we are working on that','2026-04-01 05:53:03'),(3,'teju','teju@gmail.com','order','order not delivere yet','','so sorry its shipped will update in your account','2026-04-01 08:30:03'),(4,'teju','teju@gmail.com','order','abcd','','ok','2026-04-01 09:20:30'),(5,'pradunya','tejup@gmail.com','payment','account info','','','2026-04-04 08:19:04'),(6,'NISHITA RAJ','sanu@gmail.com','ORDER','I HAVENT RECIVED MY ORDER YET ','SLOWW','yes','2026-05-07 06:05:42'),(7,'anuragi','a40991446@gmail.com','order','i havnt recived my order yet','','sorry','2026-05-14 09:48:03');
/*!40000 ALTER TABLE `userfeedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist` (
  `wid` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `pid` int NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`wid`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
INSERT INTO `wishlist` VALUES (2,'shivam@gmail',8,'2026-03-25 11:02:31'),(3,'shivam@gmail',2,'2026-03-25 11:21:58'),(4,'shivam@gmail',6,'2026-03-26 06:42:18'),(5,'shivam@gmail',10,'2026-03-26 06:42:28'),(6,'shivam@gmail',7,'2026-03-26 06:42:48'),(7,'shivam@gmail',3,'2026-03-26 06:42:56'),(8,'shivam@gmail',4,'2026-03-26 06:43:01'),(9,'shivam@gmail',5,'2026-03-26 06:43:08'),(10,'teju@gmail.com',5,'2026-04-01 06:13:58'),(11,'teju@gmail.com',14,'2026-04-01 09:36:24'),(15,'tejup@gmail.com',10,'2026-04-04 08:15:37'),(16,'tejup@gmail.com',4,'2026-04-04 08:16:55'),(18,'sanu@gmail.com',6,'2026-05-07 06:16:19'),(19,'sanu@gmail.com',10,'2026-05-07 06:19:24'),(20,'sanu@gmail.com',7,'2026-05-09 08:41:18'),(21,'sanu@gmail.com',8,'2026-05-09 08:41:25'),(26,'modi@gmail.com',8,'2026-05-11 07:04:54'),(27,'harshita@gmail.com',5,'2026-05-11 07:25:51'),(28,'harshita@gmail.com',3,'2026-05-11 07:26:07'),(29,'modi@gmail.com',7,'2026-05-11 07:26:48'),(30,'p@gmail.com',5,'2026-05-11 07:30:55'),(31,'shivi.mishra1905@gmail.com',7,'2026-05-13 07:11:30'),(32,'dubeyraj6760263@gmail.com',6,'2026-05-13 08:19:30'),(33,'dubeyraj6760263@gmail.com',15,'2026-05-13 08:19:41'),(34,'dubeyraj6760263@gmail.com',2,'2026-05-13 08:19:51'),(35,'dubeyraj6760263@gmail.com',16,'2026-05-13 08:48:33'),(36,'shivanimishra1924@gmail.com',14,'2026-05-14 08:55:34'),(37,'shivanimishra1924@gmail.com',5,'2026-05-14 08:55:36'),(38,'shivanimishra1924@gmail.com',6,'2026-05-14 08:55:45'),(39,'shivanimishra1924@gmail.com',29,'2026-05-14 09:02:09'),(40,'shivanimishra1924@gmail.com',27,'2026-05-14 09:03:04'),(41,'a40991446@gmail.com',14,'2026-05-14 09:38:31'),(42,'a40991446@gmail.com',4,'2026-05-14 09:38:34'),(43,'a40991446@gmail.com',31,'2026-05-14 10:48:06'),(44,'a40991446@gmail.com',32,'2026-05-14 10:48:09'),(45,'a40991446@gmail.com',5,'2026-05-14 10:48:11'),(46,'shivanimishra1924@gmail.com',16,'2026-05-15 06:27:14'),(47,'shivanimishra1924@gmail.com',15,'2026-05-15 06:27:15'),(48,'shivanimishra1924@gmail.com',32,'2026-05-21 09:14:47'),(49,'shivanimishra1924@gmail.com',3,'2026-06-12 06:48:49');
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-12 12:22:17
