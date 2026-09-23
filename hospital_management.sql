-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: hospital_management
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled','no_show') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doctor_slot_unique` (`doctor_id`,`appointment_date`,`appointment_time`),
  KEY `appointments_department_id_foreign` (`department_id`),
  KEY `appointments_created_by_foreign` (`created_by`),
  KEY `appointments_patient_id_appointment_date_index` (`patient_id`,`appointment_date`),
  CONSTRAINT `appointments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,2,1,1,'2026-08-26','10:00:00','Routine checkup','completed',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,2,1,1,'2026-08-31','11:00:00','Follow-up','completed',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:41:51'),(3,3,2,2,'2026-08-26','10:00:00','Routine checkup','completed',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(4,3,2,2,'2026-09-01','11:00:00','Follow-up','pending',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(5,4,3,3,'2026-08-26','10:00:00','Routine checkup','completed',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(6,4,3,3,'2026-09-02','11:00:00','Follow-up','confirmed',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(7,5,4,4,'2026-08-26','10:00:00','Routine checkup','completed',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(8,5,4,4,'2026-09-03','11:00:00','Follow-up','pending',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(9,1,5,5,'2026-08-26','10:00:00','Routine checkup','completed',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(10,1,5,5,'2026-09-04','11:00:00','Follow-up','confirmed',NULL,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(11,3,4,4,'2026-08-31','09:00:00','Leg Injury','cancelled',NULL,1,'2026-08-28 23:38:21','2026-08-28 23:39:55'),(12,7,2,2,'2026-09-04','09:30:00','asdfg','completed',NULL,10,'2026-09-02 02:44:45','2026-09-02 02:47:47'),(13,6,1,1,'2026-09-03','11:00:00','Chest pain','pending',NULL,9,'2026-09-02 03:56:15','2026-09-02 03:56:15');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Cardiology','Building A, 2nd Floor','Heart and cardiovascular care.',1,'2026-08-28 23:27:05','2026-08-28 23:27:05'),(2,'Neurology','Building A, 3rd Floor','Brain and nervous system care.',1,'2026-08-28 23:27:05','2026-08-28 23:27:05'),(3,'Pediatrics','Building B, 1st Floor','Child healthcare.',1,'2026-08-28 23:27:05','2026-08-28 23:27:05'),(4,'Orthopedics','Building B, 2nd Floor','Bone and joint care.',1,'2026-08-28 23:27:05','2026-08-28 23:27:05'),(5,'General Medicine','Building A, 1st Floor','General checkups and referrals.',1,'2026-08-28 23:27:05','2026-08-28 23:27:05');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diagnoses`
--

DROP TABLE IF EXISTS `diagnoses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diagnoses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `medical_record_id` bigint(20) unsigned NOT NULL,
  `diagnosis_name` varchar(255) NOT NULL,
  `icd_code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `severity` enum('mild','moderate','severe','critical') NOT NULL DEFAULT 'mild',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diagnoses_medical_record_id_foreign` (`medical_record_id`),
  CONSTRAINT `diagnoses_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnoses`
--

LOCK TABLES `diagnoses` WRITE;
/*!40000 ALTER TABLE `diagnoses` DISABLE KEYS */;
INSERT INTO `diagnoses` VALUES (1,1,'Viral Fever','B34.9','Self-limiting viral infection.','mild','2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,2,'Viral Fever','B34.9','Self-limiting viral infection.','mild','2026-08-28 23:27:07','2026-08-28 23:27:07'),(3,3,'Viral Fever','B34.9','Self-limiting viral infection.','mild','2026-08-28 23:27:07','2026-08-28 23:27:07'),(4,4,'Viral Fever','B34.9','Self-limiting viral infection.','mild','2026-08-28 23:27:07','2026-08-28 23:27:07'),(5,5,'Viral Fever','B34.9','Self-limiting viral infection.','mild','2026-08-28 23:27:07','2026-08-28 23:27:07');
/*!40000 ALTER TABLE `diagnoses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_schedules`
--

DROP TABLE IF EXISTS `doctor_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `day_of_week` tinyint(3) unsigned NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `slot_duration_minutes` smallint(5) unsigned NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_schedules_doctor_id_day_of_week_index` (`doctor_id`,`day_of_week`),
  CONSTRAINT `doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_schedules`
--

LOCK TABLES `doctor_schedules` WRITE;
/*!40000 ALTER TABLE `doctor_schedules` DISABLE KEYS */;
INSERT INTO `doctor_schedules` VALUES (1,1,1,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(2,1,1,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(3,1,2,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(4,1,2,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(5,1,3,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(6,1,3,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(7,1,4,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(8,1,4,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(9,1,5,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(10,1,5,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(11,2,1,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(12,2,1,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(13,2,2,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(14,2,2,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(15,2,3,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(16,2,3,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(17,2,4,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(18,2,4,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(19,2,5,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(20,2,5,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(21,3,1,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(22,3,1,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(23,3,2,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(24,3,2,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(25,3,3,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(26,3,3,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(27,3,4,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(28,3,4,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(29,3,5,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(30,3,5,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(31,4,1,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(32,4,1,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(33,4,2,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(34,4,2,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(35,4,3,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(36,4,3,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(37,4,4,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(38,4,4,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(39,4,5,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(40,4,5,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(41,5,1,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(42,5,1,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(43,5,2,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(44,5,2,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(45,5,3,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(46,5,3,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(47,5,4,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(48,5,4,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(49,5,5,'09:00:00','13:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(50,5,5,'14:00:00','17:00:00',30,1,'2026-08-28 23:27:07','2026-08-28 23:27:07');
/*!40000 ALTER TABLE `doctor_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `specialization` varchar(255) NOT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `experience_years` smallint(5) unsigned NOT NULL DEFAULT 0,
  `license_number` varchar(255) DEFAULT NULL,
  `consultation_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctors_user_id_foreign` (`user_id`),
  KEY `doctors_department_id_foreign` (`department_id`),
  CONSTRAINT `doctors_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` VALUES (1,3,1,'Cardiologist','MBBS, MD (Cardiology)',12,'LIC-EPC6XX',50.00,'Cardiologist with 12 years of experience.','2026-08-28 23:27:06','2026-08-28 23:27:06'),(2,4,2,'Neurologist','MBBS, FCPS (Neurology)',9,'LIC-THW5VF',60.00,'Neurologist with 9 years of experience.','2026-08-28 23:27:06','2026-08-28 23:27:06'),(3,5,3,'Pediatrician','MBBS, DCH',7,'LIC-J0NH5H',40.00,'Pediatrician with 7 years of experience.','2026-08-28 23:27:06','2026-08-28 23:27:06'),(4,6,4,'Orthopedic Surgeon','MBBS, MS (Ortho)',15,'LIC-3FQXTC',55.00,'Orthopedic Surgeon with 15 years of experience.','2026-08-28 23:27:06','2026-08-28 23:27:06'),(5,7,5,'General Physician','MBBS',5,'LIC-IDMYCP',30.00,'General Physician with 5 years of experience.','2026-08-28 23:27:07','2026-08-28 23:27:07');
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (1,1,'Consultation Fee',1,50.00,50.00,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,1,'Medicine Dispensing',1,8.50,8.50,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(3,2,'Consultation Fee',1,60.00,60.00,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(4,2,'Medicine Dispensing',1,8.50,8.50,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(5,3,'Consultation Fee',1,40.00,40.00,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(6,3,'Medicine Dispensing',1,8.50,8.50,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(7,4,'Consultation Fee',1,55.00,55.00,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(8,4,'Medicine Dispensing',1,8.50,8.50,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(9,5,'Consultation Fee',1,30.00,30.00,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(10,5,'Medicine Dispensing',1,8.50,8.50,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(11,6,'Consultation fee',1,50.00,50.00,'2026-08-29 06:27:47','2026-08-29 06:27:47'),(12,7,'Consultation fee',1,50.00,50.00,'2026-08-29 06:29:18','2026-08-29 06:29:18');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `appointment_id` bigint(20) unsigned DEFAULT NULL,
  `issue_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('unpaid','partially_paid','paid','cancelled') NOT NULL DEFAULT 'unpaid',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_patient_id_foreign` (`patient_id`),
  KEY `invoices_appointment_id_foreign` (`appointment_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  CONSTRAINT `invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,'INV-20260829-LFRWQ',2,1,'2026-08-26','2026-09-02',58.50,58.50,'paid',1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,'INV-20260829-FTNYG',3,3,'2026-08-26','2026-09-02',68.50,0.00,'unpaid',1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(3,'INV-20260829-ZJHAH',4,5,'2026-08-26','2026-09-02',48.50,48.50,'paid',1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(4,'INV-20260829-XMMDB',5,7,'2026-08-26','2026-09-02',63.50,63.50,'paid',1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(5,'INV-20260829-KODIF',1,9,'2026-08-26','2026-09-02',38.50,0.00,'unpaid',1,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(6,'INV-20260829-OU7TI',6,NULL,'2026-08-31','2026-08-31',50.00,0.00,'unpaid',2,'2026-08-29 06:27:47','2026-08-29 06:27:47'),(7,'INV-20260829-PHKKF',2,NULL,'2026-08-31','2026-08-31',50.00,0.00,'unpaid',2,'2026-08-29 06:29:18','2026-08-29 06:29:18');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_records`
--

DROP TABLE IF EXISTS `medical_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medical_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) unsigned NOT NULL,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `appointment_id` bigint(20) unsigned DEFAULT NULL,
  `visit_date` date NOT NULL,
  `chief_complaint` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `blood_pressure` varchar(255) DEFAULT NULL,
  `temperature_celsius` decimal(4,1) DEFAULT NULL,
  `pulse_bpm` smallint(5) unsigned DEFAULT NULL,
  `weight_kg` decimal(5,1) DEFAULT NULL,
  `height_cm` decimal(5,1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_records_patient_id_foreign` (`patient_id`),
  KEY `medical_records_doctor_id_foreign` (`doctor_id`),
  KEY `medical_records_appointment_id_foreign` (`appointment_id`),
  CONSTRAINT `medical_records_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_records_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `medical_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_records`
--

LOCK TABLES `medical_records` WRITE;
/*!40000 ALTER TABLE `medical_records` DISABLE KEYS */;
INSERT INTO `medical_records` VALUES (1,2,1,1,'2026-08-26','Mild fever and fatigue for 2 days.','Patient advised rest and hydration. Follow up if symptoms persist.','120/80',37.8,82,68.5,170.0,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,3,2,3,'2026-08-26','Mild fever and fatigue for 2 days.','Patient advised rest and hydration. Follow up if symptoms persist.','120/80',37.8,82,68.5,170.0,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(3,4,3,5,'2026-08-26','Mild fever and fatigue for 2 days.','Patient advised rest and hydration. Follow up if symptoms persist.','120/80',37.8,82,68.5,170.0,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(4,5,4,7,'2026-08-26','Mild fever and fatigue for 2 days.','Patient advised rest and hydration. Follow up if symptoms persist.','120/80',37.8,82,68.5,170.0,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(5,1,5,9,'2026-08-26','Mild fever and fatigue for 2 days.','Patient advised rest and hydration. Follow up if symptoms persist.','120/80',37.8,82,68.5,170.0,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(6,2,1,2,'2026-08-29','Testing','testing',NULL,37.0,100,60.0,160.0,'2026-08-28 23:41:51','2026-08-28 23:41:51'),(7,7,2,12,'2026-09-02','testing','testing','120/80',37.0,100,60.0,176.0,'2026-09-02 02:47:47','2026-09-02 02:47:47');
/*!40000 ALTER TABLE `medical_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000010_create_departments_table',1),(5,'2024_01_01_000020_create_doctors_table',1),(6,'2024_01_01_000030_create_patients_table',1),(7,'2024_01_01_000040_create_doctor_schedules_table',1),(8,'2024_01_01_000050_create_appointments_table',1),(9,'2024_01_01_000060_create_medical_records_table',1),(10,'2024_01_01_000070_create_diagnoses_table',1),(11,'2024_01_01_000080_create_prescriptions_table',1),(12,'2024_01_01_000090_create_invoices_table',1),(13,'2024_01_01_000100_create_payments_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patients_user_id_foreign` (`user_id`),
  CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,8,'Rahim Uddin','1990-05-14','male','B+','01700000099','patient@hms.test','House 12, Road 5, Dhaka','Karim Uddin','01700000098',NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,NULL,'Anika Chowdhury','1988-08-29','female','A+','01854725158',NULL,NULL,NULL,NULL,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(3,NULL,'Tanvir Hasan','2000-08-29','male','O+','01819598101',NULL,NULL,NULL,NULL,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(4,NULL,'Sumaiya Akter','1968-08-29','female','AB+','01859323289',NULL,NULL,NULL,NULL,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(5,NULL,'Jahid Hasan','1981-08-29','male','O-','01867822587',NULL,NULL,NULL,NULL,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(6,9,'Rahmat Ali','1980-01-10',NULL,NULL,NULL,'rahmat@email.com',NULL,NULL,NULL,NULL,'2026-08-29 00:03:35','2026-08-29 00:03:35'),(7,10,'Arif','2016-02-09','male',NULL,'01434567898','arif@gmail.com',NULL,NULL,NULL,NULL,'2026-09-02 02:43:59','2026-09-02 02:43:59');
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','mobile_banking','insurance') NOT NULL DEFAULT 'cash',
  `transaction_reference` varchar(255) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `received_by` bigint(20) unsigned DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  KEY `payments_received_by_foreign` (`received_by`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,58.50,'cash',NULL,'2026-08-26',2,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,3,48.50,'cash',NULL,'2026-08-26',2,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(3,4,63.50,'cash',NULL,'2026-08-26',2,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescription_items`
--

DROP TABLE IF EXISTS `prescription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescription_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint(20) unsigned NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescription_items_prescription_id_foreign` (`prescription_id`),
  CONSTRAINT `prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescription_items`
--

LOCK TABLES `prescription_items` WRITE;
/*!40000 ALTER TABLE `prescription_items` DISABLE KEYS */;
INSERT INTO `prescription_items` VALUES (1,1,'Paracetamol 500mg','1 tablet','3x daily','5 days','After meals','2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,1,'Vitamin C 500mg','1 tablet','1x daily','7 days','Morning','2026-08-28 23:27:07','2026-08-28 23:27:07'),(3,2,'Paracetamol 500mg','1 tablet','3x daily','5 days','After meals','2026-08-28 23:27:07','2026-08-28 23:27:07'),(4,2,'Vitamin C 500mg','1 tablet','1x daily','7 days','Morning','2026-08-28 23:27:07','2026-08-28 23:27:07'),(5,3,'Paracetamol 500mg','1 tablet','3x daily','5 days','After meals','2026-08-28 23:27:07','2026-08-28 23:27:07'),(6,3,'Vitamin C 500mg','1 tablet','1x daily','7 days','Morning','2026-08-28 23:27:07','2026-08-28 23:27:07'),(7,4,'Paracetamol 500mg','1 tablet','3x daily','5 days','After meals','2026-08-28 23:27:07','2026-08-28 23:27:07'),(8,4,'Vitamin C 500mg','1 tablet','1x daily','7 days','Morning','2026-08-28 23:27:07','2026-08-28 23:27:07'),(9,5,'Paracetamol 500mg','1 tablet','3x daily','5 days','After meals','2026-08-28 23:27:07','2026-08-28 23:27:07'),(10,5,'Vitamin C 500mg','1 tablet','1x daily','7 days','Morning','2026-08-28 23:27:07','2026-08-28 23:27:07'),(11,6,'Vitamin C',NULL,NULL,NULL,NULL,'2026-08-28 23:42:57','2026-08-28 23:42:57'),(12,7,'Paracetamol','500','1','7 days','With meal','2026-09-02 02:48:46','2026-09-02 02:48:46');
/*!40000 ALTER TABLE `prescription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `medical_record_id` bigint(20) unsigned NOT NULL,
  `doctor_id` bigint(20) unsigned NOT NULL,
  `patient_id` bigint(20) unsigned NOT NULL,
  `issue_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescriptions_medical_record_id_foreign` (`medical_record_id`),
  KEY `prescriptions_doctor_id_foreign` (`doctor_id`),
  KEY `prescriptions_patient_id_foreign` (`patient_id`),
  CONSTRAINT `prescriptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prescriptions_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescriptions`
--

LOCK TABLES `prescriptions` WRITE;
/*!40000 ALTER TABLE `prescriptions` DISABLE KEYS */;
INSERT INTO `prescriptions` VALUES (1,1,1,2,'2026-08-26','Take with food. Return if fever persists beyond 3 days.','2026-08-28 23:27:07','2026-08-28 23:27:07'),(2,2,2,3,'2026-08-26','Take with food. Return if fever persists beyond 3 days.','2026-08-28 23:27:07','2026-08-28 23:27:07'),(3,3,3,4,'2026-08-26','Take with food. Return if fever persists beyond 3 days.','2026-08-28 23:27:07','2026-08-28 23:27:07'),(4,4,4,5,'2026-08-26','Take with food. Return if fever persists beyond 3 days.','2026-08-28 23:27:07','2026-08-28 23:27:07'),(5,5,5,1,'2026-08-26','Take with food. Return if fever persists beyond 3 days.','2026-08-28 23:27:07','2026-08-28 23:27:07'),(6,6,1,2,'2026-08-29',NULL,'2026-08-28 23:42:57','2026-08-28 23:42:57'),(7,7,2,7,'2026-09-02','Visit again 7 days later','2026-09-02 02:48:46','2026-09-02 02:48:46');
/*!40000 ALTER TABLE `prescriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5jazHlwOqlx9nJUgHbKy0gfGkE1kyTpVsVvJAdQD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJiMWNUcmg1blBUaTB5RmR5dTVFQzBIUDJqbUJDaUVwV0w1c21hT3o3IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIn19',1788343311);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'patient',
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Administrator','admin@hms.test',NULL,'$2y$12$mbe7sdCuJaRGrnv2di6mQOg09jh7k8J6HGNQ4M9zorVnFPP5sw0/q','admin','01700000001',1,NULL,'2026-08-28 23:27:05','2026-08-28 23:27:05'),(2,'Front Desk','reception@hms.test',NULL,'$2y$12$6DCXxlCTwnD8G154P2A.c.c60WLndF3yZH00ztj5XaCSdVwlK.0YK','receptionist','01700000002',1,'L9zmoWDJR5mrUEVgGue4l8VcKsB5OCrcHWQVrLXhGYJmh5sTUpgofru7KEGG','2026-08-28 23:27:05','2026-08-28 23:27:05'),(3,'Dr. Sarah Islam','doctor@hms.test',NULL,'$2y$12$oxNAaknHhWyPJ9txebYPmusoMxwRhYkSE6RaxR1i2MQpvbEgDn2Mm','doctor','01766204209',1,NULL,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(4,'Dr. Farhan Ahmed','farhan.ahmed@hms.test',NULL,'$2y$12$NEYF7YEdsiRyl9Kc6skAf.ICvEzWlCFyfPFtwEKNwyGSGpKwKJSfq','doctor','01730562979',1,NULL,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(5,'Dr. Nusrat Jahan','nusrat.jahan@hms.test',NULL,'$2y$12$zXPkQ/nb4ir50OQvpEIj4.lXh1kyjw.duTL5U1BS66V0VnSYte8NO','doctor','01764419036',1,NULL,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(6,'Dr. Kamal Hossain','kamal.hossain@hms.test',NULL,'$2y$12$M1DJdlmHcK7WBckdsCS4KOq/G1zKcYBXzImF5dNbknzFNRAMPeWUy','doctor','01786620234',1,NULL,'2026-08-28 23:27:06','2026-08-28 23:27:06'),(7,'Dr. Ayesha Rahman','ayesha.rahman@hms.test',NULL,'$2y$12$Mku7UI/KpZ0HcwK7L9S.XefeoNoWQRULvp1k4GeD/DFG..Eicq47u','doctor','01740993274',1,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(8,'Rahim Uddin','patient@hms.test',NULL,'$2y$12$GE159Iu8tDp.eKJ2ef1m/u2n5uPbuHegs1ZyG3uthwYl6qnkZeuBm','patient','01700000099',1,NULL,'2026-08-28 23:27:07','2026-08-28 23:27:07'),(9,'Rahmat Ali','rahmat@email.com',NULL,'$2y$12$p2Ipl/VbzpdDu7DxSuVA5uT3SvZHtBjxphTNjiZUyH4EE.YI7OL.q','patient',NULL,1,NULL,'2026-08-29 00:03:35','2026-08-29 00:03:35'),(10,'Arif','arif@gmail.com',NULL,'$2y$12$5Fmqwp3X3ibtjDv7rGZpt.w7mC8fRVpJshlQZF9pGjrNRzmDuQr/G','patient','01434567898',1,NULL,'2026-09-02 02:43:59','2026-09-02 02:43:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-02 23:01:01
