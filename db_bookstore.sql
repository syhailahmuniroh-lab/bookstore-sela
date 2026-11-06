-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_bookstore
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
-- Current Database: `db_bookstore`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `db_bookstore` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db_bookstore`;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id_buku` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) DEFAULT NULL,
  `judul` varchar(200) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 25,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_buku`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `books_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `categories` (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (1,1,'The Midnight Library','Matt Haig',85000,25,'A dazzling novel about all the choices that go into a life well lived.','https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&h=420&fit=crop&auto=format'),(2,2,'Atomic Habits','James Clear',95000,25,'Tiny changes, remarkable results. A proven framework for building good habits.','https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=300&h=420&fit=crop&auto=format'),(3,3,'Sapiens','Yuval Noah Harari',110000,25,'A brief history of humankind from the Stone Age to the present.','https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=300&h=420&fit=crop&auto=format'),(4,4,'Dune','Frank Herbert',120000,25,'The epic tale of a desert planet, its people, and a young hero.','https://images.unsplash.com/photo-1614544048536-0d28caf77f41?w=300&h=420&fit=crop&auto=format'),(5,5,'The Psychology of Money','Morgan Housel',88000,25,'Timeless lessons on wealth, greed, and happiness.','https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=300&h=420&fit=crop&auto=format'),(6,1,'The Alchemist','Paulo Coelho',75000,25,'A magical story about following your dreams.','https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=300&h=420&fit=crop&auto=format'),(7,6,'Thinking, Fast and Slow','Daniel Kahneman',105000,25,'How two systems drive the way we think and make choices.','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=420&fit=crop&auto=format'),(8,7,'Pride and Prejudice','Jane Austen',65000,25,'A beloved novel of manners, marriage, and social class in 19th century England.','https://images.unsplash.com/photo-1461360228754-6e81c478b882?w=300&h=420&fit=crop&auto=format');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Fiction'),(2,'Self-Help'),(3,'History'),(4,'Science Fiction'),(5,'Finance'),(6,'Psychology'),(7,'Classic');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id_pesan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `isi_pesan` text DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pesan`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,6,'P min\n','2026-07-26 20:58:10'),(3,2,'Halo admin, apakah stok buku Atomic Habits masih tersedia untuk pengiriman ke Bandung? Terima kasih.','2026-11-03 20:30:12');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `id_order` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  PRIMARY KEY (`id_item`),
  KEY `id_order` (`id_order`),
  KEY `id_buku` (`id_buku`),
  CONSTRAINT `fk_buku` FOREIGN KEY (`id_buku`) REFERENCES `books` (`id_buku`) ON DELETE CASCADE,
  CONSTRAINT `fk_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,8,1,65000),(2,1,5,1,88000),(3,2,6,1,75000),(4,2,3,1,110000),(5,3,7,1,105000),(6,4,8,1,65000),(7,4,7,1,105000),(9,6,2,2,95000);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `tanggal_order` datetime DEFAULT current_timestamp(),
  `status` enum('pending','dikirim','selesai') DEFAULT 'pending',
  `metode_pembayaran` varchar(50) DEFAULT 'COD',
  `status_pembayaran` varchar(50) DEFAULT 'Pending (COD)',
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_order`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,4,'Jl. Kebon Jeruk No. 8, Jakarta Barat',153000,'2026-07-26 20:05:48','selesai','COD','Pending (COD)',NULL),(2,2,'Jl. Diponegoro No. 22, Bandung',185000,'2026-07-26 20:29:03','selesai','COD','Pending (COD)',NULL),(3,6,'Jl. Pahlawan No. 10, Surabaya',105000,'2026-07-26 20:57:59','selesai','COD','Pending (COD)',NULL),(4,2,'Jl. Malioboro No. 50, Yogyakarta',170000,'2026-07-26 21:25:07','selesai','QRIS Instant','Lunas',NULL),(6,2,'Jl. Melati No. 15, RT 02/RW 04, Menteng, Jakarta Pusat',190000,'2026-11-03 20:22:00','pending','Transfer Bank BCA','Menunggu Verifikasi Admin','bukti_2_1793712120_655.png');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `no_telp` varchar(20) DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Fateh','Fateh1@gmail.com','$2y$10$dfXBV1Y58vOMLl9wx.uQ8uAmOBe70H71c6AolFD/5f.51xak.HGNa','user',NULL,'2026-07-26 20:02:10'),(2,'Budi Santoso','budi@email.com','$2y$10$CCajG/.tzhLkohZV0bStt.OYceNkMGIIIKrwlu84II4U91f7YwBo.','user','081234567890','2026-07-26 20:02:10'),(3,'Administrator','admin@bookstore.com','$2y$10$I17WEYQR0mTNVqZGu/k9tuA5ZR3dN2bukit4yVEBLUdJM12ffr3rm','admin','081298765432','2026-07-26 20:02:10'),(4,'Athaillah Nazhif Firdaus ( Alumni )','najib@gmail.com','$2y$10$hNFWtUBjt0IOZUbCj.uBLOoEhB0X5HiNy4j/5F8j/xgoBTOk2uYJe','user','08123456789','2026-07-26 20:03:20'),(5,'Shella','syhailahmuniroh@gmail.com','$2y$10$1GPrQHVFSoQmUPRISXl63ewUZRaIMpmRsALUFp6VyDwmJwcycTt7S','user','089668867666','2026-07-26 20:49:17'),(6,'sella','sella1@gmail.com','$2y$10$YD8iw/PCC93duDHnstxtkuBj.fPCpNQ/.HKMVduINLN3T4Z4l7QAe','user','0811111111','2026-07-26 20:57:03');
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

-- Dump completed on 2026-11-03 20:45:05
