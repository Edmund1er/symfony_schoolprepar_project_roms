-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: schoolprepar_db
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Dumping data for table doctrine_migration_versions
--


/*!40000 ALTER TABLE doctrine_migration_versions DISABLE KEYS */;
INSERT INTO doctrine_migration_versions VALUES ('DoctrineMigrations\\Version20260408203924','2026-04-08 20:40:08',360),('DoctrineMigrations\\Version20260414114753','2026-04-14 11:50:34',103),('DoctrineMigrations\\Version20260414115631','2026-04-14 11:59:18',47),('DoctrineMigrations\\Version20260414120244','2026-04-14 12:03:21',52);
/*!40000 ALTER TABLE doctrine_migration_versions ENABLE KEYS */;
UN

--
-- Dumping data for table etablissement
--


/*!40000 ALTER TABLE etablissement DISABLE KEYS */;
INSERT INTO etablissement VALUES (21,'Universit+® de Lom+®','Boulevard du 13 Janvier, Lom+®, Togo','contact@univ-lome.tg','univ-lome.png'),(22,'Universit+® de Kara','Kara, Togo','rectorat@univ-kara.tg','univ-kara.png'),(23,'ISDI','21 BKK, rue Djav+®m+®, derri+¿re FUCEC Atikoum+®, Togo','isditogo@gmail.com','isdi.png'),(24,'IPNET Institute of Technology','Avenue Sarakawa, Lom+®, Togo','info@ipnet.tg','ipnet.png'),(25,'IAI','46G6+54 Lom+®, Togo','Iaitogo@iai-togo.tg','iai.png'),(26,'ESIBA','Ago+¿nyiv+® Assiy+®y+® dans la banlieue Nord de Lom+® 01BP: 3518, ESIBA-IUA, Rue de la gare routi+¿re d, Rue El Hadj Abbas Bonfoh, Lom+®','Esiba@gmail.com','esiba-69f9d90ee629f.jpg');
/*!40000 ALTER TABLE etablissement ENABLE KEYS */;
UN

--
-- Dumping data for table evenement
--


/*!40000 ALTER TABLE evenement DISABLE KEYS */;
INSERT INTO evenement VALUES (21,'Portes Ouvertes IPNET 2025','2025-05-15 09:00:00','IPNET Lom+®'),(22,'Webinaire : R+®ussir son orientation','2025-04-20 14:00:00','En ligne'),(23,'Hackathon SchoolPrepar','2025-06-10 08:00:00','Universit+® de Lom+®'),(24,'Rencontre Mentor-+ël+¿ve','2025-05-05 10:00:00','IPNET Lom+®'),(25,'Forum des M+®tiers et Formations','2025-07-01 09:00:00','Palais des Congr+¿s de Lom+®'),(26,'bootcamp','2026-07-10 15:30:00','agence Tecox Agoe-2lion');
/*!40000 ALTER TABLE evenement ENABLE KEYS */;
UN

--
-- Dumping data for table evenement_user
--


/*!40000 ALTER TABLE evenement_user DISABLE KEYS */;
INSERT INTO evenement_user VALUES (21,17),(21,18),(22,17),(23,19);
/*!40000 ALTER TABLE evenement_user ENABLE KEYS */;
UN

--
-- Dumping data for table filiere
--


/*!40000 ALTER TABLE filiere DISABLE KEYS */;
INSERT INTO filiere VALUES (21,'G+®nie Logiciel','D+®veloppement de logiciels, conception, programmation avanc+®e, gestion de projets informatiques','genie-logiciel.png'),(22,'R+®seaux et Syst+¿mes','Administration des r+®seaux, s+®curit+® informatique, virtualisation, cloud computing','reseaux-systemes.png'),(23,'Comptabilit+®','Comptabilit+® g+®n+®rale, comptabilit+® analytique, audit financier, fiscalit+®','comptabilite.png'),(24,'Droit Audit et Contr+¦le','Audit interne, contr+¦le de gestion, gestion des risques, conformit+®','audit-controle.png'),(26,'sciences Agronomiques','Les sciences agronomiques regroupent des disciplines scientifiques et techniques appliqu+®es +á l''agriculture pour une production alimentaire durable, la gestion des ressources et la protection de l''environnement','sciences-Agronomiques-69f9db9069c66.jpg');
/*!40000 ALTER TABLE filiere ENABLE KEYS */;
UN

--
-- Dumping data for table messenger_messages
--


/*!40000 ALTER TABLE messenger_messages DISABLE KEYS */;
/*!40000 ALTER TABLE messenger_messages ENABLE KEYS */;
UN

--
-- Dumping data for table user
--


/*!40000 ALTER TABLE user DISABLE KEYS */;
INSERT INTO user VALUES (16,'ADJA','Kokou','kokou.adja@schoolprepar.com','$2y$13$0V.iRcXQGihY77Rk/VIomuyG.2VF1xl5gE6Hmhw8qGMABgRWWCrQq','[\"ROLE_ADMIN\"]',NULL),(17,'ALI','Romaric','romaric.ali@ipnet.tg','$2y$13$2Udn.0PALsag.gaxqUh8wOYj8kSnZ4H8Opkv0AE.x1fm2yI7PzJ6C','[\"ROLE_ELEVE\"]',21),(18,'DJANGBEDJA','Essotina','essotina.djangbedja@univ-lome.tg','$2y$13$.bFAMp5HK9JllhPZHBrTUuq1kch/W1NC9ILIkRyQ4yutIFW322jCW','[\"ROLE_MENTOR\"]',22),(19,'KOMLAN','Ayao','ayao.komlan@schoolprepar.com','$2y$13$AkkVzdVMu2AL/E0fyCzqEuXGy9FEbHfFFKLnKajxFy0Kj62ONvKfe','[\"ROLE_ELEVE\"]',23),(21,'NASSIFATOU','lumtia','nafissatou.lumtia@ipnet.tg','$2y$13$a0.iqxUelMBH/HAGbfzv8OXqnD7ynGDXKaU9YQqYRdZ0uUEcJPlem','[\"ROLE_ELEVE\"]',23),(22,'FATOU','illumina','fatou.lumina@gmail.tg','$2y$13$vOr/J02JpokCSqWEprvPr.iXIt1xQxaKIH94KiCHY6Suk./cndWn2','[\"ROLE_MENTOR\"]',21),(23,'ESSE','Clarisse','esseclarisse28@gmail.com','$2y$13$6igMs.71MIVdSRc3FRLHnetTC7NOSYjbXgpB2h3ztUX5MTBExiG7.','[\"ROLE_ADMIN\"]',NULL);
/*!40000 ALTER TABLE user ENABLE KEYS */;
UN
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-05 15:31:18
