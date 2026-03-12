CREATE DATABASE  IF NOT EXISTS `gestionproyectos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `gestionproyectos`;
-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: gestionproyectos
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

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
-- Table structure for table `actividades_obra`
--

DROP TABLE IF EXISTS `actividades_obra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actividades_obra` (
  `id` int NOT NULL AUTO_INCREMENT,
  `proyecto_id` int NOT NULL,
  `item` int NOT NULL,
  `actividad` text NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `dias` int NOT NULL,
  `porcentajeAvance` int NOT NULL DEFAULT '0',
  `comentarios` text,
  PRIMARY KEY (`id`),
  KEY `proyecto_id` (`proyecto_id`),
  CONSTRAINT `actividades_obra_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividades_obra`
--

LOCK TABLES `actividades_obra` WRITE;
/*!40000 ALTER TABLE `actividades_obra` DISABLE KEYS */;
/*!40000 ALTER TABLE `actividades_obra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoras`
--

DROP TABLE IF EXISTS `bitacoras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacoras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `proyecto_id` int NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `comentarios` text NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `nivelImportancia` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `proyecto_id` (`proyecto_id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `bitacoras_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bitacoras_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoras`
--

LOCK TABLES `bitacoras` WRITE;
/*!40000 ALTER TABLE `bitacoras` DISABLE KEYS */;
INSERT INTO `bitacoras` VALUES (4,'2026-03-05',4,15,'Revision de sistemas existentes para validar y coordinar desmantelamiento de sistemas de video vigilancia y control de acceso existentes.','Edgar',1);
/*!40000 ALTER TABLE `bitacoras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Administrativo'),(2,'Auditoria'),(3,'Control documental'),(4,'Calidad'),(5,'Ambiental'),(6,'Ingenieria'),(7,'Diseno'),(8,'Seguridad'),(9,'Operaciones'),(10,'Supervision'),(11,'Compras'),(12,'Herramientas y equipos'),(13,'Eq Especial'),(14,'Otro'),(15,'Instalaciones');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disciplinas`
--

DROP TABLE IF EXISTS `disciplinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disciplinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciplinas`
--

LOCK TABLES `disciplinas` WRITE;
/*!40000 ALTER TABLE `disciplinas` DISABLE KEYS */;
INSERT INTO `disciplinas` VALUES (1,'Electrico');
/*!40000 ALTER TABLE `disciplinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `nombreCliente` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `rfc` varchar(50) DEFAULT NULL,
  `direccion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'Alta Tecnologia en Sistemas Inteligentes','Salvador Leon','alta@alta-tec.com.mx','1234567890','','hdsjkfhdkjfs','sdfsdfds');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `minutas`
--

DROP TABLE IF EXISTS `minutas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `minutas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `proyecto_id` int NOT NULL,
  `fecha` date NOT NULL,
  `tituloJunta` text,
  `personalJunta` text,
  `personalCliente` text,
  `categoriaJunta` text,
  `informacionJunta` text,
  `compromisos` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `minutas`
--

LOCK TABLES `minutas` WRITE;
/*!40000 ALTER TABLE `minutas` DISABLE KEYS */;
INSERT INTO `minutas` VALUES (4,4,'2026-03-05','DESMANTELAMIENTO DE SISTEMAS EXISTENTES - INICIO DE INSTALACION','Israel, Edgar, Segio, Ana, Erick, Martin','N/A','Instalaciones','Se realizo recorrido con Erick y Marin\r\nSe reviso que se va desmantelar (Tubería, cajas nema, cable utp) Dispositivos de puertas se retiraron\r\nLuis Aparicio indico que no se manipulen sistemas que estén empalmados o sobre mismo sistema FAS\r\n\r\nDesmantelar tubería de control de acceso.\r\nExisten muros y áreas que están aun en servicio. Solo se puede trabajar en fin de semana.\r\nSe planea iniciar la instalación de anillos para los sistemas de vídeo vigilancia y comunicaciones\r\n\r\nDejar encargado arnés a Ramón o a Hector Muniz.\r\nConsiderar llevar material para realizar la instalación. \r\n\r\nYa se entregaron Docks, se requiere permiso para ingresar a esa zona.\r\n\r\nManana se ingresa plataforma de elevación a las 8:00 am\r\n\r\n\r\nFalta EPP para personal operativo\r\nGuantes, Cascos, Chalecos\r\n\r\nAntes de instalar se debe entregar los anillos y mordazas, hacer recepción de materiales para el día Lunes\r\n\r\nRevisar la información enviada por Carlos Medina para ver los cambios e impacto en el proyecto\r\nRevisar submittals recibidos y verificar cuales se enviarían el lunes.','Tiempo para desmantelar: 2 fin de semana\r\nIngresar el Domingo para hacer desmantelamiento.\r\nComenzar con prioridad la primer fase del proyecto que corresponde: (Revisar cuales son las fases de acuerdo a lo que indica walbridge)\r\n\r\nHacer Vplan de desmantelamiento de la instalación existente\r\nCambiar fecha de instalación inicio para el 9 de marzo 2026\r\n\r\nEdgar entrega planos de taller submittados no aprobados por el cliente final (Ghafary)\r\n\r\nTema banos: 2 dias despues de haber realizado el pago de los mismos, no se pueden usar los banos de GM.\r\n\r\nConsiderar compra de carrito para mezanine para identificar los cables'),(5,4,'2026-03-06','Cambio de fechas para instalacion y desmantelamiento','Israel, Edgar','N/A','Instalaciones','5 domingos para desmantelamiento (Retiro de tubería, cableado y equipos)\r\nInicia instalación anillos el lunes (comunicaciones y vídeo vigilancia) cambiar en programa e info semanal Lunes a viernes.\r\nFase 1: 9 marzo a 20 anillos.\r\nFase 2:  23 marzo 3 abril (zonas con restricción)\r\nDemas fechas pendientes conforme vaya llegando material.\r\nFase 3: 13 abril al 19 abril.\r\n\r\nEn control de acceso va ir todo en canalización: la tubería llega en 6 semanas a partir del primer pago, aun no se realiza el pago. \r\n\r\nSe cambia día descanso de Domingo por sábado por fecha de trabajo desmantelamiento.','Area R,S,T pendiente por revisar en campo (Erick y Martín)\r\nMartín va revisar donde están tapiales o muros provisionales \r\nDiseno de plano maestro para los 3 sistemas. - Edgar\r\nHacer layout de plano de desmantelamiento. (para vplan) Edgar');
/*!40000 ALTER TABLE `minutas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `proyecto_id` int NOT NULL,
  `empresa_id` int NOT NULL,
  `permiso` enum('leer','editar') NOT NULL DEFAULT 'leer',
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `proyecto_id` (`proyecto_id`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`),
  CONSTRAINT `permisos_ibfk_3` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (7,4,4,1,'editar');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyectos`
--

DROP TABLE IF EXISTS `proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proyectos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `disciplina_id` int NOT NULL,
  `subdisciplina_id` int NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disciplina_id` (`disciplina_id`),
  KEY `subdisciplina_id` (`subdisciplina_id`),
  CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`),
  CONSTRAINT `proyectos_ibfk_2` FOREIGN KEY (`subdisciplina_id`) REFERENCES `subdisciplinas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyectos`
--

LOCK TABLES `proyectos` WRITE;
/*!40000 ALTER TABLE `proyectos` DISABLE KEYS */;
INSERT INTO `proyectos` VALUES (4,'GRX','Silao',1,1,'2026-01-27','2026-06-30');
/*!40000 ALTER TABLE `proyectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes`
--

DROP TABLE IF EXISTS `reportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reportes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `proyecto_id` int NOT NULL,
  `subdisciplina_id` int DEFAULT NULL,
  `actividad` text NOT NULL,
  `area_zonal` text,
  `nivel` text,
  `permisoTrabajo` text,
  `horastrabajadas` int DEFAULT NULL,
  `imagenes` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `proyecto_id` (`proyecto_id`),
  KEY `subdisciplina_id` (`subdisciplina_id`),
  CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`),
  CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`subdisciplina_id`) REFERENCES `subdisciplinas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes`
--

LOCK TABLES `reportes` WRITE;
/*!40000 ALTER TABLE `reportes` DISABLE KEYS */;
INSERT INTO `reportes` VALUES (15,4,1,'Visita a planta para ver alcances de desmantelamiento ','Area M','0.00','N/A',9,'[]','2026-03-05 17:27:02');
/*!40000 ALTER TABLE `reportes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subdisciplinas`
--

DROP TABLE IF EXISTS `subdisciplinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subdisciplinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `disciplina_id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `disciplina_id` (`disciplina_id`),
  CONSTRAINT `subdisciplinas_ibfk_1` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subdisciplinas`
--

LOCK TABLES `subdisciplinas` WRITE;
/*!40000 ALTER TABLE `subdisciplinas` DISABLE KEYS */;
INSERT INTO `subdisciplinas` VALUES (1,1,'Video Vigilancia'),(2,1,'Control de acceso'),(3,1,'Comunicaciones');
/*!40000 ALTER TABLE `subdisciplinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL DEFAULT 'cliente',
  `creado` datetime DEFAULT CURRENT_TIMESTAMP,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Admin','e.gm27@outlook.com','$2y$10$JH7HVxG2pMVHiAqH/7PGReIB32EN8A8mWjuv/lYUjz5Z5Yh0rXNem\n','admin','2026-02-27 07:31:13','activo'),(4,'Israel','israel@alta-tec.com.mx','$2y$10$0pwiog6JTdnJOh.Si6.Pb.v5c7rRs0mIEwxFiyf.6ZExbs/G3cBKO','cliente','2026-03-05 17:16:08','activo');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'gestionproyectos'
--

--
-- Dumping routines for database 'gestionproyectos'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-08 17:43:19
