-- MySQL dump 10.19  Distrib 10.3.39-MariaDB, for Linux (x86_64)
--
-- Host: studdb.csc.liv.ac.uk    Database: sgefojci
-- ------------------------------------------------------
-- Server version	10.5.27-MariaDB-log
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */
;
/*!40103 SET TIME_ZONE='+00:00' */
;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */
;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */
;
--
-- Table structure for table `bookings`
--
DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `bookings` (
    `booking_id` int(11) NOT NULL AUTO_INCREMENT,
    `session_id` int(11) DEFAULT NULL,
    `student_name` varchar(255) NOT NULL,
    `student_email` varchar(255) NOT NULL,
    PRIMARY KEY (`booking_id`),
    KEY `session_id` (`session_id`),
    CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`session_id`) ON DELETE
    SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `bookings`
--
LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */
;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */
;
UNLOCK TABLES;
--
-- Table structure for table `sessions`
--
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */
;
/*!40101 SET character_set_client = utf8 */
;
CREATE TABLE `sessions` (
    `session_id` int(11) NOT NULL AUTO_INCREMENT,
    `topic` varchar(100) NOT NULL,
    `session_time` time NOT NULL,
    `capacity` int(11) DEFAULT NULL,
    `available_places` int(11) NOT NULL,
    `day_of_week` varchar(20) DEFAULT NULL,
    PRIMARY KEY (`session_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 11 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */
;
--
-- Dumping data for table `sessions`
--
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */
;
INSERT INTO `sessions`
VALUES (1, 'Word Processing', '10:00:00', 4, 4, 'Monday'),
(2, 'Word Processing', '11:00:00', 4, 4, 'Wednesday'),
(3, 'Word Processing', '12:00:00', 4, 4, 'Friday'),
(4, 'Spreadsheets', '11:00:00', 3, 3, 'Tuesday'),
(5, 'Spreadsheets', '12:00:00', 3, 3, 'Friday'),
(6, 'Email', '12:00:00', 3, 3, 'Tuesday'),
(7, 'Email', '10:00:00', 3, 3, 'Wednesday'),
(
        8,
        'Presentation Software',
        '10:00:00',
        2,
        2,
        'Monday'
    ),
(
        9,
        'Presentation Software',
        '12:00:00',
        2,
        2,
        'Thursday'
    ),
(10, 'Library Use', '11:00:00', 2, 2, 'Wednesday');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */
;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */
;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */
;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */
;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */
;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */
;
-- Dump completed on 2025-04-04 14:36:06