-- MySQL dump 10.13  Distrib 8.0.40, for Linux (x86_64)
--
-- Host: localhost    Database: Mateando_Juntos
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_elimina_post`
--

DROP TABLE IF EXISTS `admin_elimina_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_elimina_post` (
  `ID_post` int NOT NULL,
  `ID_admin` int NOT NULL,
  `Fecha_eliminacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_post`,`ID_admin`),
  KEY `ID_admin` (`ID_admin`),
  CONSTRAINT `admin_elimina_post_ibfk_1` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID_post`),
  CONSTRAINT `admin_elimina_post_ibfk_2` FOREIGN KEY (`ID_admin`) REFERENCES `administrador` (`ID_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_elimina_post`
--

LOCK TABLES `admin_elimina_post` WRITE;
/*!40000 ALTER TABLE `admin_elimina_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_elimina_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `administrador`
--

DROP TABLE IF EXISTS `administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administrador` (
  `ID_admin` int NOT NULL AUTO_INCREMENT,
  `Contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_admin`),
  UNIQUE KEY `Contrasena` (`Contrasena`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrador`
--

LOCK TABLES `administrador` WRITE;
/*!40000 ALTER TABLE `administrador` DISABLE KEYS */;
/*!40000 ALTER TABLE `administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asiste_Evento`
--

DROP TABLE IF EXISTS `asiste_Evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asiste_Evento` (
  `ID_usuario` int NOT NULL,
  `ID_evento` int NOT NULL,
  `Fecha_Eliminacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_usuario`,`ID_evento`),
  KEY `ID_evento` (`ID_evento`),
  CONSTRAINT `asiste_Evento_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  CONSTRAINT `asiste_Evento_ibfk_2` FOREIGN KEY (`ID_evento`) REFERENCES `evento` (`ID_evento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asiste_Evento`
--

LOCK TABLES `asiste_Evento` WRITE;
/*!40000 ALTER TABLE `asiste_Evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `asiste_Evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentarios`
--

DROP TABLE IF EXISTS `comentarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentarios` (
  `ID_comentario` int NOT NULL AUTO_INCREMENT,
  `ID_usuario` int NOT NULL,
  `ID_post` int NOT NULL,
  `Contenido` text NOT NULL,
  `Fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_comentario`),
  KEY `ID_usuario` (`ID_usuario`),
  KEY `ID_post` (`ID_post`),
  CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID_post`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentarios`
--

LOCK TABLES `comentarios` WRITE;
/*!40000 ALTER TABLE `comentarios` DISABLE KEYS */;
INSERT INTO `comentarios` VALUES (1,3,1,'que lindos ','2024-11-15 02:47:35');
/*!40000 ALTER TABLE `comentarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunidad`
--

DROP TABLE IF EXISTS `comunidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comunidad` (
  `ID_comunidad` int NOT NULL AUTO_INCREMENT,
  `Nombre_comunidad` varchar(100) NOT NULL,
  `Descripcion` text,
  `Fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_usuario_creador` int NOT NULL,
  `Url_fotocomunidad` text,
  PRIMARY KEY (`ID_comunidad`),
  KEY `ID_usuario_creador` (`ID_usuario_creador`),
  CONSTRAINT `comunidad_ibfk_1` FOREIGN KEY (`ID_usuario_creador`) REFERENCES `usuario` (`ID_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunidad`
--

LOCK TABLES `comunidad` WRITE;
/*!40000 ALTER TABLE `comunidad` DISABLE KEYS */;
INSERT INTO `comunidad` VALUES (1,'Mates Rambla','Mates en la Rambla.','2024-11-15 02:44:34',1,'6736b59257c64.jpg');
/*!40000 ALTER TABLE `comunidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunidad_evento`
--

DROP TABLE IF EXISTS `comunidad_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comunidad_evento` (
  `ID_comunidad` int NOT NULL,
  `ID_evento` int NOT NULL,
  PRIMARY KEY (`ID_comunidad`,`ID_evento`),
  KEY `ID_evento` (`ID_evento`),
  CONSTRAINT `comunidad_evento_ibfk_1` FOREIGN KEY (`ID_comunidad`) REFERENCES `comunidad` (`ID_comunidad`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comunidad_evento_ibfk_2` FOREIGN KEY (`ID_evento`) REFERENCES `evento` (`ID_evento`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunidad_evento`
--

LOCK TABLES `comunidad_evento` WRITE;
/*!40000 ALTER TABLE `comunidad_evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `comunidad_evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunidad_post`
--

DROP TABLE IF EXISTS `comunidad_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comunidad_post` (
  `ID_comunidad` int NOT NULL,
  `ID_post` int NOT NULL,
  PRIMARY KEY (`ID_comunidad`,`ID_post`),
  KEY `ID_post` (`ID_post`),
  CONSTRAINT `comunidad_post_ibfk_1` FOREIGN KEY (`ID_comunidad`) REFERENCES `comunidad` (`ID_comunidad`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comunidad_post_ibfk_2` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunidad_post`
--

LOCK TABLES `comunidad_post` WRITE;
/*!40000 ALTER TABLE `comunidad_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `comunidad_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crea_admin`
--

DROP TABLE IF EXISTS `crea_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `crea_admin` (
  `Administrador_admin_creador` int NOT NULL,
  `Administrador_admin_creado` int NOT NULL,
  PRIMARY KEY (`Administrador_admin_creador`,`Administrador_admin_creado`),
  KEY `Administrador_admin_creado` (`Administrador_admin_creado`),
  CONSTRAINT `crea_admin_ibfk_1` FOREIGN KEY (`Administrador_admin_creador`) REFERENCES `administrador` (`ID_admin`),
  CONSTRAINT `crea_admin_ibfk_2` FOREIGN KEY (`Administrador_admin_creado`) REFERENCES `administrador` (`ID_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crea_admin`
--

LOCK TABLES `crea_admin` WRITE;
/*!40000 ALTER TABLE `crea_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `crea_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dar_megusta`
--

DROP TABLE IF EXISTS `dar_megusta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dar_megusta` (
  `ID_usuario` int NOT NULL,
  `ID_post` int NOT NULL,
  PRIMARY KEY (`ID_usuario`,`ID_post`),
  KEY `ID_post` (`ID_post`),
  CONSTRAINT `dar_megusta_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  CONSTRAINT `dar_megusta_ibfk_2` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dar_megusta`
--

LOCK TABLES `dar_megusta` WRITE;
/*!40000 ALTER TABLE `dar_megusta` DISABLE KEYS */;
INSERT INTO `dar_megusta` VALUES (1,1),(3,1),(2,2);
/*!40000 ALTER TABLE `dar_megusta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `elimina_usuario`
--

DROP TABLE IF EXISTS `elimina_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `elimina_usuario` (
  `ID_usuario` int NOT NULL,
  `ID_admin` int NOT NULL,
  `Fecha_Eliminacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_usuario`,`ID_admin`),
  KEY `ID_admin` (`ID_admin`),
  CONSTRAINT `elimina_usuario_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  CONSTRAINT `elimina_usuario_ibfk_2` FOREIGN KEY (`ID_admin`) REFERENCES `administrador` (`ID_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `elimina_usuario`
--

LOCK TABLES `elimina_usuario` WRITE;
/*!40000 ALTER TABLE `elimina_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `elimina_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ev_contiene`
--

DROP TABLE IF EXISTS `ev_contiene`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ev_contiene` (
  `ID_evento` int NOT NULL,
  `ID_post` int NOT NULL,
  PRIMARY KEY (`ID_evento`,`ID_post`),
  KEY `ID_post` (`ID_post`),
  CONSTRAINT `ev_contiene_ibfk_1` FOREIGN KEY (`ID_evento`) REFERENCES `evento` (`ID_evento`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ev_contiene_ibfk_2` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ev_contiene`
--

LOCK TABLES `ev_contiene` WRITE;
/*!40000 ALTER TABLE `ev_contiene` DISABLE KEYS */;
/*!40000 ALTER TABLE `ev_contiene` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento`
--

DROP TABLE IF EXISTS `evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evento` (
  `ID_evento` int NOT NULL AUTO_INCREMENT,
  `ID_usuario` int DEFAULT NULL,
  `Titulo` varchar(255) DEFAULT NULL,
  `Descripcion` text,
  `Fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Fecha_encuentro` date DEFAULT NULL,
  `Hora_inicio` time DEFAULT NULL,
  `Hora_fin` time DEFAULT NULL,
  `Latitud` decimal(10,8) DEFAULT NULL,
  `Longitud` decimal(11,8) DEFAULT NULL,
  `Lugar` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_evento`),
  KEY `ID_usuario` (`ID_usuario`),
  CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento`
--

LOCK TABLES `evento` WRITE;
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
INSERT INTO `evento` VALUES (1,1,'MATES ','Vamos a matear el sabado!!','2024-11-15 02:43:45','2024-11-16','16:00:00','19:45:00',-34.92024058,-56.17216712,'Puesta del Sol, Ciclovia de la Rambla, Parque Rodó, Montevideo, 11303, Uruguay');
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `idioma`
--

DROP TABLE IF EXISTS `idioma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `idioma` (
  `ID_idioma` int NOT NULL AUTO_INCREMENT,
  `Nombre_idioma` varchar(70) NOT NULL,
  PRIMARY KEY (`ID_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `idioma`
--

LOCK TABLES `idioma` WRITE;
/*!40000 ALTER TABLE `idioma` DISABLE KEYS */;
/*!40000 ALTER TABLE `idioma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensaje`
--

DROP TABLE IF EXISTS `mensaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensaje` (
  `ID_mensaje` int NOT NULL AUTO_INCREMENT,
  `Contenido` text,
  `Fecha_envio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_usuario_envia` int DEFAULT NULL,
  `ID_usuario_recibe` int DEFAULT NULL,
  `leeido` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID_mensaje`),
  KEY `ID_usuario_envia` (`ID_usuario_envia`),
  KEY `ID_usuario_recibe` (`ID_usuario_recibe`),
  CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`ID_usuario_envia`) REFERENCES `usuario` (`ID_usuario`),
  CONSTRAINT `mensaje_ibfk_2` FOREIGN KEY (`ID_usuario_recibe`) REFERENCES `usuario` (`ID_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensaje`
--

LOCK TABLES `mensaje` WRITE;
/*!40000 ALTER TABLE `mensaje` DISABLE KEYS */;
INSERT INTO `mensaje` VALUES (1,'Hola!!','2024-11-15 02:47:44',3,1,0);
/*!40000 ALTER TABLE `mensaje` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modifica`
--

DROP TABLE IF EXISTS `modifica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modifica` (
  `ID_perfil` int NOT NULL,
  `ID_idioma` int NOT NULL,
  PRIMARY KEY (`ID_perfil`,`ID_idioma`),
  KEY `ID_idioma` (`ID_idioma`),
  CONSTRAINT `modifica_ibfk_1` FOREIGN KEY (`ID_perfil`) REFERENCES `perfil_usuario` (`ID_perfil`),
  CONSTRAINT `modifica_ibfk_2` FOREIGN KEY (`ID_idioma`) REFERENCES `idioma` (`ID_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modifica`
--

LOCK TABLES `modifica` WRITE;
/*!40000 ALTER TABLE `modifica` DISABLE KEYS */;
/*!40000 ALTER TABLE `modifica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil_usuario`
--

DROP TABLE IF EXISTS `perfil_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil_usuario` (
  `ID_perfil` int NOT NULL AUTO_INCREMENT,
  `Tema` tinyint(1) DEFAULT '1',
  `Foto_perfil` text,
  `Biografia` text,
  `Privado` tinyint(1) DEFAULT '0',
  `ID_usuario` int DEFAULT NULL,
  PRIMARY KEY (`ID_perfil`),
  KEY `ID_usuario` (`ID_usuario`),
  CONSTRAINT `perfil_usuario_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_usuario`
--

LOCK TABLES `perfil_usuario` WRITE;
/*!40000 ALTER TABLE `perfil_usuario` DISABLE KEYS */;
INSERT INTO `perfil_usuario` VALUES (1,1,'6736b4ee5d021.jpg','HOLA!, como estan?',0,1),(2,1,'6736b5db6f15a.jpg','La mejor yerba ',0,2),(3,1,'6736b63218c19.jpg','Me gusta el mate ',0,3);
/*!40000 ALTER TABLE `perfil_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pertenece`
--

DROP TABLE IF EXISTS `pertenece`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pertenece` (
  `ID_usuario` int NOT NULL,
  `ID_comunidad` int NOT NULL,
  PRIMARY KEY (`ID_usuario`,`ID_comunidad`),
  KEY `ID_comunidad` (`ID_comunidad`),
  CONSTRAINT `pertenece_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  CONSTRAINT `pertenece_ibfk_2` FOREIGN KEY (`ID_comunidad`) REFERENCES `comunidad` (`ID_comunidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pertenece`
--

LOCK TABLES `pertenece` WRITE;
/*!40000 ALTER TABLE `pertenece` DISABLE KEYS */;
INSERT INTO `pertenece` VALUES (1,1);
/*!40000 ALTER TABLE `pertenece` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post` (
  `ID_post` int NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(255) DEFAULT NULL,
  `Descripcion` text,
  `Fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_usuario` int NOT NULL,
  PRIMARY KEY (`ID_post`),
  KEY `ID_usuario` (`ID_usuario`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,'s','                        mis  mates ','2024-11-15 02:42:25',1),(2,'s','                        Cual es tu preferida ?','2024-11-15 02:46:05',2);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_multimedia`
--

DROP TABLE IF EXISTS `post_multimedia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_multimedia` (
  `Numero_mul` int NOT NULL AUTO_INCREMENT,
  `Src_mul` text,
  `ID_post` int NOT NULL,
  PRIMARY KEY (`Numero_mul`,`ID_post`),
  KEY `ID_post` (`ID_post`),
  CONSTRAINT `post_multimedia_ibfk_1` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID_post`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_multimedia`
--

LOCK TABLES `post_multimedia` WRITE;
/*!40000 ALTER TABLE `post_multimedia` DISABLE KEYS */;
INSERT INTO `post_multimedia` VALUES (1,'6736b5125faaf.jpg',1),(2,'6736b5ef20d43.jpg',2);
/*!40000 ALTER TABLE `post_multimedia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguir`
--

DROP TABLE IF EXISTS `seguir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguir` (
  `Perfil_usuario_Seguido` int NOT NULL,
  `Perfil_usuario_Seguidor` int NOT NULL,
  PRIMARY KEY (`Perfil_usuario_Seguido`,`Perfil_usuario_Seguidor`),
  KEY `Perfil_usuario_Seguidor` (`Perfil_usuario_Seguidor`),
  CONSTRAINT `seguir_ibfk_1` FOREIGN KEY (`Perfil_usuario_Seguido`) REFERENCES `perfil_usuario` (`ID_perfil`),
  CONSTRAINT `seguir_ibfk_2` FOREIGN KEY (`Perfil_usuario_Seguidor`) REFERENCES `perfil_usuario` (`ID_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguir`
--

LOCK TABLES `seguir` WRITE;
/*!40000 ALTER TABLE `seguir` DISABLE KEYS */;
INSERT INTO `seguir` VALUES (1,3);
/*!40000 ALTER TABLE `seguir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `ID_usuario` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Nombre_usuario` varchar(50) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_usuario`),
  UNIQUE KEY `Nombre_usuario` (`Nombre_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'eze','eze','$2y$10$ba0G2l5oQl/dOW9s7Y8T3.jJNt.AVjHupMPFcr9KNn25u5sMWqmou','dfg@gmail.com','2024-11-15 02:35:40'),(2,'Canarias ','Canarias ','$2y$10$OKEjjZVMXC4N3zKbRee4n.83/9t6r5FAExFpxOctVgkcdUVSAMUea','dcanafg@gmail.com','2024-11-15 02:45:15'),(3,'martita ','Marta ','$2y$10$eGQPDTvvwLp5POqXUZbos.Sqcccm8nl2FxwsFccPmjoDtAGXT5mja','dMafg@gmail.com','2024-11-15 02:46:36');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-15  4:10:00
