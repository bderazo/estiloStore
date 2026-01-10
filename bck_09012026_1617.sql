-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: estiuhzp_tienda
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `articulo_color`
--

DROP TABLE IF EXISTS `articulo_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_color` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `color_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `articulo_color_articulo_id_color_id_unique` (`articulo_id`,`color_id`) USING BTREE,
  KEY `articulo_color_color_id_foreign` (`color_id`) USING BTREE,
  KEY `articulo_color_articulo_id_index` (`articulo_id`) USING BTREE,
  CONSTRAINT `articulo_color_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_color_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_color`
--

LOCK TABLES `articulo_color` WRITE;
/*!40000 ALTER TABLE `articulo_color` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo_imagenes`
--

DROP TABLE IF EXISTS `articulo_imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_imagenes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `ruta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta del archivo de imagen (UUID)',
  `orden` int NOT NULL DEFAULT '0' COMMENT 'Orden de presentación de la imagen',
  `metadatos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Metadatos: width, height, size, alt text, etc',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `articulo_imagenes_articulo_id_index` (`articulo_id`) USING BTREE,
  KEY `articulo_imagenes_orden_index` (`orden`) USING BTREE,
  CONSTRAINT `articulo_imagenes_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_imagenes_chk_1` CHECK (json_valid(`metadatos`))
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_imagenes`
--

LOCK TABLES `articulo_imagenes` WRITE;
/*!40000 ALTER TABLE `articulo_imagenes` DISABLE KEYS */;
INSERT INTO `articulo_imagenes` VALUES (1,1,'default.png',1,NULL,'2026-01-04 00:49:13','2026-01-04 00:49:13'),(2,2,'default.png',1,NULL,'2026-01-04 00:49:13','2026-01-04 00:49:13'),(3,3,'default.png',1,NULL,'2026-01-04 00:49:13','2026-01-04 00:49:13'),(4,4,'default.png',1,NULL,'2026-01-04 00:49:13','2026-01-04 00:49:13'),(5,5,'default.png',1,NULL,'2026-01-04 00:49:13','2026-01-04 00:49:13'),(6,6,'default.png',1,NULL,'2026-01-04 00:49:13','2026-01-04 00:49:13'),(7,7,'default.png',1,NULL,'2026-01-04 00:49:13','2026-01-04 00:49:13'),(8,8,'default.png',1,NULL,'2026-01-04 00:49:13','2026-01-04 00:49:13');
/*!40000 ALTER TABLE `articulo_imagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo_medida`
--

DROP TABLE IF EXISTS `articulo_medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_medida` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `medida_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `articulo_medida_articulo_id_medida_id_unique` (`articulo_id`,`medida_id`) USING BTREE,
  KEY `articulo_medida_medida_id_foreign` (`medida_id`) USING BTREE,
  KEY `articulo_medida_articulo_id_index` (`articulo_id`) USING BTREE,
  CONSTRAINT `articulo_medida_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_medida_medida_id_foreign` FOREIGN KEY (`medida_id`) REFERENCES `medidas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_medida`
--

LOCK TABLES `articulo_medida` WRITE;
/*!40000 ALTER TABLE `articulo_medida` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo_medida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo_modelo`
--

DROP TABLE IF EXISTS `articulo_modelo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_modelo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `modelo_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `articulo_modelo_articulo_id_modelo_id_unique` (`articulo_id`,`modelo_id`) USING BTREE,
  KEY `articulo_modelo_modelo_id_foreign` (`modelo_id`) USING BTREE,
  KEY `articulo_modelo_articulo_id_index` (`articulo_id`) USING BTREE,
  CONSTRAINT `articulo_modelo_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_modelo_modelo_id_foreign` FOREIGN KEY (`modelo_id`) REFERENCES `modelos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_modelo`
--

LOCK TABLES `articulo_modelo` WRITE;
/*!40000 ALTER TABLE `articulo_modelo` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo_modelo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo_plaza`
--

DROP TABLE IF EXISTS `articulo_plaza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_plaza` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `plaza_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `articulo_plaza_articulo_id_plaza_id_unique` (`articulo_id`,`plaza_id`) USING BTREE,
  KEY `articulo_plaza_plaza_id_foreign` (`plaza_id`) USING BTREE,
  KEY `articulo_plaza_articulo_id_index` (`articulo_id`) USING BTREE,
  CONSTRAINT `articulo_plaza_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_plaza_plaza_id_foreign` FOREIGN KEY (`plaza_id`) REFERENCES `plazas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_plaza`
--

LOCK TABLES `articulo_plaza` WRITE;
/*!40000 ALTER TABLE `articulo_plaza` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo_plaza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo_sabor`
--

DROP TABLE IF EXISTS `articulo_sabor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_sabor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `sabor_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `articulo_sabor_articulo_id_sabor_id_unique` (`articulo_id`,`sabor_id`) USING BTREE,
  KEY `articulo_sabor_sabor_id_foreign` (`sabor_id`) USING BTREE,
  KEY `articulo_sabor_articulo_id_index` (`articulo_id`) USING BTREE,
  CONSTRAINT `articulo_sabor_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_sabor_sabor_id_foreign` FOREIGN KEY (`sabor_id`) REFERENCES `sabores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_sabor`
--

LOCK TABLES `articulo_sabor` WRITE;
/*!40000 ALTER TABLE `articulo_sabor` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo_sabor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo_talla`
--

DROP TABLE IF EXISTS `articulo_talla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_talla` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `talla_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `articulo_talla_articulo_id_talla_id_unique` (`articulo_id`,`talla_id`) USING BTREE,
  KEY `articulo_talla_talla_id_foreign` (`talla_id`) USING BTREE,
  KEY `articulo_talla_articulo_id_index` (`articulo_id`) USING BTREE,
  CONSTRAINT `articulo_talla_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_talla_talla_id_foreign` FOREIGN KEY (`talla_id`) REFERENCES `tallas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_talla`
--

LOCK TABLES `articulo_talla` WRITE;
/*!40000 ALTER TABLE `articulo_talla` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo_talla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo_tono`
--

DROP TABLE IF EXISTS `articulo_tono`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_tono` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `tono_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `articulo_tono_articulo_id_tono_id_unique` (`articulo_id`,`tono_id`) USING BTREE,
  KEY `articulo_tono_tono_id_foreign` (`tono_id`) USING BTREE,
  KEY `articulo_tono_articulo_id_index` (`articulo_id`) USING BTREE,
  CONSTRAINT `articulo_tono_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_tono_tono_id_foreign` FOREIGN KEY (`tono_id`) REFERENCES `tonos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_tono`
--

LOCK TABLES `articulo_tono` WRITE;
/*!40000 ALTER TABLE `articulo_tono` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo_tono` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo_variantes`
--

DROP TABLE IF EXISTS `articulo_variantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_variantes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint unsigned NOT NULL,
  `atributos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Atributos de la variante (talla, color, plaza, etc)',
  `stock` int NOT NULL DEFAULT '0' COMMENT 'Stock total de la variante',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Variante activa/inactiva',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `articulo_variantes_articulo_id_index` (`articulo_id`) USING BTREE,
  KEY `articulo_variantes_articulo_id_activo_index` (`articulo_id`,`activo`) USING BTREE,
  CONSTRAINT `articulo_variantes_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulo_variantes_chk_1` CHECK (json_valid(`atributos`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo_variantes`
--

LOCK TABLES `articulo_variantes` WRITE;
/*!40000 ALTER TABLE `articulo_variantes` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo_variantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulos`
--

DROP TABLE IF EXISTS `articulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del artículo',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug único para URLs',
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Descripción del artículo',
  `especificaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Especificaciones técnicas',
  `precio` decimal(10,2) NOT NULL COMMENT 'Precio base del artículo',
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `porcentaje_descuento` int DEFAULT NULL,
  `en_oferta` tinyint(1) DEFAULT '0',
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SKU del artículo base',
  `categoria_id` bigint unsigned DEFAULT NULL,
  `marca_id` bigint unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Artículo activo/inactivo',
  `destacado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Artículo destacado',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `articulos_slug_unique` (`slug`) USING BTREE,
  UNIQUE KEY `articulos_sku_unique` (`sku`) USING BTREE,
  KEY `articulos_categoria_id_index` (`categoria_id`) USING BTREE,
  KEY `articulos_marca_id_index` (`marca_id`) USING BTREE,
  KEY `articulos_slug_activo_index` (`slug`,`activo`) USING BTREE,
  KEY `articulos_activo_index` (`activo`) USING BTREE,
  KEY `articulos_destacado_index` (`destacado`) USING BTREE,
  CONSTRAINT `articulos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulos_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulos`
--

LOCK TABLES `articulos` WRITE;
/*!40000 ALTER TABLE `articulos` DISABLE KEYS */;
INSERT INTO `articulos` VALUES (1,'Audífonos Bluetooth Pro','audifonos-bluetooth-pro','Excelente calidad de sonido',NULL,59.90,NULL,NULL,0,NULL,1,1,1,0,NULL,'2026-01-04 00:47:39','2026-01-04 00:47:39'),(2,'Laptop Gamer X1','laptop-gamer-x1','Procesador de última generación',NULL,1200.00,NULL,NULL,0,NULL,2,1,1,0,NULL,'2026-01-04 00:47:53','2026-01-04 00:47:53'),(3,'Teclado Mecánico RGB','teclado-mecanico-rgb','Switches red para gaming',NULL,45.00,NULL,NULL,0,NULL,2,1,1,0,NULL,'2026-01-04 00:48:03','2026-01-04 00:48:03'),(4,'Smartphone S24 Ultra','smartphone-s24-ultra','Cámara de 200MP',NULL,999.00,NULL,NULL,0,NULL,3,1,1,0,NULL,'2026-01-04 00:48:36','2026-01-04 00:48:36'),(5,'Casaca Cortavientos','casaca-cortavientos','Ideal para climas fríos',NULL,35.00,NULL,NULL,0,NULL,5,1,1,0,NULL,'2026-01-04 00:48:44','2026-01-04 00:48:44'),(6,'Pantalón Jean Slim','pantalon-jean-slim','Color azul clásico',NULL,25.00,NULL,NULL,0,NULL,9,1,1,0,NULL,'2026-01-04 00:48:51','2026-01-04 00:48:51'),(7,'Zapatillas Running A1','zapatillas-running-a1','Amortiguación superior',NULL,85.00,76.50,10,1,NULL,11,1,0,0,NULL,'2026-01-04 00:48:59','2026-01-04 00:48:59'),(8,'Parlante Inteligente','parlante-inteligente','Compatible con Alexa y Google',NULL,49.00,44.10,10,1,NULL,20,1,1,0,NULL,'2026-01-04 00:49:05','2026-01-04 00:49:05');
/*!40000 ALTER TABLE `articulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `seccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagen_ruta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_destino` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seccion` (`seccion`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'initial_header','¿Cómo pagar tus pedidos?','¡Forma parte de nuestra comunidad ahora!','banners/banner.png','https://tu-sitio.com/guia-pagos',1,'2026-01-03 20:59:11','2026-01-03 20:59:11'),(2,'nosotros_header','Nosotros','Quienes Somos?','banners/nosotros.png',NULL,1,'2026-01-03 20:59:11','2026-01-03 20:59:11');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carrusel`
--

DROP TABLE IF EXISTS `carrusel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrusel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activar_subtitulo` tinyint(1) NOT NULL DEFAULT '0',
  `activar_boton` tinyint(1) NOT NULL DEFAULT '0',
  `url_boton` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirigir_misma_pagina` tinyint(1) NOT NULL DEFAULT '0',
  `posicion_contenido` enum('Izquierda','Derecha') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Izquierda',
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrusel`
--

LOCK TABLES `carrusel` WRITE;
/*!40000 ALTER TABLE `carrusel` DISABLE KEYS */;
INSERT INTO `carrusel` VALUES (11,'Conoce nuestros productos',NULL,1,0,NULL,0,'Izquierda','carrusel/1766629227_1Is2abmrA7.jpg',1,'2025-12-25 07:17:21','2025-12-25 07:21:05');
/*!40000 ALTER TABLE `carrusel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `categorias_slug_unique` (`slug`) USING BTREE,
  KEY `categorias_parent_id_foreign` (`parent_id`) USING BTREE,
  CONSTRAINT `categorias_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Electronica','test','categorias/ex1.jpg','TEST',NULL,1,1,'2025-11-25 18:15:12','2025-11-25 18:15:12'),(2,'Computadoras','computadoras','categorias/ex1.jpg',NULL,1,1,1,'2026-01-03 22:43:38','2026-01-03 22:43:38'),(3,'Celulares','celulares','categorias/ex1.jpg',NULL,1,1,2,'2026-01-03 22:43:38','2026-01-03 22:43:38'),(4,'Tablets','tablets','categorias/ex1.jpg',NULL,1,1,3,'2026-01-03 22:43:38','2026-01-03 22:43:38'),(5,'Ropa','ropa','categorias/ropa.jpg',NULL,NULL,1,4,'2026-01-03 22:43:38','2026-01-03 22:43:38'),(7,'Cocina','cocina','categorias/ex1.jpg',NULL,6,1,2,'2026-01-03 22:43:41','2026-01-03 22:43:41'),(8,'Iluminación','iluminacion','categorias/ex1.jpg',NULL,6,1,3,'2026-01-03 22:43:41','2026-01-03 22:43:41'),(9,'Hombre','moda-hombre','categorias/ex1.jpg',NULL,5,1,1,'2026-01-03 22:43:43','2026-01-03 22:43:43'),(10,'Mujer','moda-mujer','categorias/ex1.jpg',NULL,5,1,2,'2026-01-03 22:43:43','2026-01-03 22:43:43'),(11,'Calzado','calzado','categorias/ex1.jpg',NULL,5,1,3,'2026-01-03 22:43:43','2026-01-03 22:43:43'),(12,'Fitness','fitness','categorias/ex1.jpg',NULL,5,1,1,'2026-01-03 22:43:46','2026-01-03 22:43:46'),(13,'Ciclismo','ciclismo','categorias/ex1.jpg',NULL,4,1,2,'2026-01-03 22:43:46','2026-01-03 22:43:46'),(14,'Fútbol','futbol','categorias/ex1.jpg',NULL,4,1,3,'2026-01-03 22:43:46','2026-01-03 22:43:46'),(15,'Maquillaje','maquillaje','categorias/ex1.jpg',NULL,5,1,1,'2026-01-03 22:44:35','2026-01-03 22:44:35'),(16,'Cuidado de la Piel','skincare','categorias/ex1.jpg',NULL,5,1,2,'2026-01-03 22:44:35','2026-01-03 22:44:35'),(17,'Perfumes','perfumes','categorias/ex1.jpg',NULL,5,1,3,'2026-01-03 22:44:35','2026-01-03 22:44:35'),(18,'Cuidado Capilar','cabello','categorias/ex1.jpg',NULL,5,1,4,'2026-01-03 22:44:35','2026-01-03 22:44:35'),(19,'Cámaras','camaras-fotograficas','categorias/ex1.jpg',NULL,1,1,5,'2026-01-03 22:44:37','2026-01-03 22:44:37'),(20,'Audio y Video','audio-video','categorias/ex1.jpg',NULL,1,1,6,'2026-01-03 22:44:37','2026-01-03 22:44:37'),(22,'Jardinería','jardineria','categorias/jardineria.jpg',NULL,NULL,1,5,'2026-01-03 22:44:40','2026-01-03 22:44:40'),(23,'Natación','natacion','categorias/natacion.jpg',NULL,NULL,1,4,'2026-01-03 22:44:42','2026-01-03 22:44:42'),(24,'Camping','camping-aventura','categorias/camping.jpg',NULL,NULL,1,5,'2026-01-03 22:44:42','2026-01-03 22:44:42'),(25,'Categoría Invisible','invisible','categorias/ex1.jpg',NULL,NULL,0,99,'2026-01-03 22:44:44','2026-01-03 22:44:44');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colores`
--

DROP TABLE IF EXISTS `colores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_hex` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colores`
--

LOCK TABLES `colores` WRITE;
/*!40000 ALTER TABLE `colores` DISABLE KEYS */;
/*!40000 ALTER TABLE `colores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa_datos`
--

DROP TABLE IF EXISTS `empresa_datos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresa_datos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `contenido` text,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa_datos`
--

LOCK TABLES `empresa_datos` WRITE;
/*!40000 ALTER TABLE `empresa_datos` DISABLE KEYS */;
INSERT INTO `empresa_datos` VALUES (1,'quienes_somos','Nuestra Historia','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','nosotros/1.png',1,1,'2026-01-04 16:51:15','2026-01-05 01:54:46'),(3,'vision','Nuestra Visión','There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.','nosotros/3.png',1,3,'2026-01-04 16:51:15','2026-01-05 01:54:46'),(4,'mision','Nuestra Misión','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.','nosotros/2.png',1,0,'2026-01-05 01:55:53','2026-01-05 01:56:19');
/*!40000 ALTER TABLE `empresa_datos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `folletos`
--

DROP TABLE IF EXISTS `folletos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `folletos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `archivo_pdf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descargas` int NOT NULL DEFAULT '0',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `folletos`
--

LOCK TABLES `folletos` WRITE;
/*!40000 ALTER TABLE `folletos` DISABLE KEYS */;
INSERT INTO `folletos` VALUES (1,'Catálogo General 2024','Descarga nuestro folleto con todos los productos y promociones vigentes.','folletos/prueba.pdf',3,1,'2026-01-03 20:00:13','2026-01-04 01:20:49');
/*!40000 ALTER TABLE `folletos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legal_documents`
--

DROP TABLE IF EXISTS `legal_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `legal_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `archivo_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1.0',
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legal_documents`
--

LOCK TABLES `legal_documents` WRITE;
/*!40000 ALTER TABLE `legal_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `legal_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marcas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `marcas_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marcas`
--

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
INSERT INTO `marcas` VALUES (1,'Marca Genérica','marca-generica',NULL,1,'2026-01-04 00:47:15','2026-01-04 00:47:15');
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medidas`
--

DROP TABLE IF EXISTS `medidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medidas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medidas`
--

LOCK TABLES `medidas` WRITE;
/*!40000 ALTER TABLE `medidas` DISABLE KEYS */;
/*!40000 ALTER TABLE `medidas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_01_01_000001_create_modules_table',1),(6,'2024_01_01_000002_create_roles_table',1),(7,'2024_01_01_000003_create_permissions_table',1),(8,'2024_01_01_000004_modify_users_table',1),(9,'2024_01_01_000005_create_permission_role_table',1),(10,'2024_01_01_000006_create_role_user_table',1),(11,'2025_09_29_020031_update_users_roles_relationship',1),(12,'2025_10_19_220229_create_carrusel_table',1),(13,'2025_10_20_013544_create_categorias_table',1),(14,'2025_10_20_055040_create_marcas_table',1),(15,'2025_10_20_062335_create_colores_table',1),(16,'2025_10_20_064844_create_tallas_table',1),(17,'2025_10_22_220014_create_plazas_table',1),(18,'2025_10_23_010737_create_medidas_table',1),(19,'2025_10_23_020801_create_sabores_table',1),(20,'2025_10_23_063454_create_modelos_table',1),(21,'2025_10_23_063457_create_tonos_table',1),(22,'2025_10_27_080000_create_articulos_table',1),(23,'2025_10_27_080001_create_articulo_imagenes_table',1),(24,'2025_10_27_080002_create_articulo_variantes_table',1),(25,'2025_10_27_080003_create_articulo_color_table',1),(26,'2025_10_27_080004_create_articulo_talla_table',1),(27,'2025_10_27_080005_create_articulo_plaza_table',1),(28,'2025_10_27_080006_create_articulo_medida_table',1),(29,'2025_10_27_080008_create_articulo_sabor_table',1),(30,'2025_10_27_080009_create_articulo_modelo_table',1),(31,'2025_10_27_080010_create_articulo_tono_table',1),(32,'2025_10_28_064517_remove_reserved_from_articulo_variantes_table',1),(33,'2025_10_29_072023_remove_sku_precio_from_articulo_variantes_table',1),(34,'2025_11_23_000000_create_nosotros_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modelos`
--

DROP TABLE IF EXISTS `modelos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modelos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modelos`
--

LOCK TABLES `modelos` WRITE;
/*!40000 ALTER TABLE `modelos` DISABLE KEYS */;
/*!40000 ALTER TABLE `modelos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `modules_nombre_unique` (`nombre`) USING BTREE,
  UNIQUE KEY `modules_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'Usuarios','usuarios','Gestión de usuarios del sistema','2025-11-25 03:35:31','2025-11-25 03:35:31'),(2,'Roles','roles','Gestión de roles y permisos','2025-11-25 03:35:31','2025-11-25 03:35:31'),(3,'Sistema','sistema','Configuraciones del sistema','2025-11-25 03:35:31','2025-11-25 03:35:31'),(4,'Carrusel','carrusel','Gestión del carrusel de imágenes','2025-11-25 03:35:31','2025-11-25 03:35:31'),(5,'Categorías','categoria','Gestión de categorías','2025-11-25 03:35:31','2025-11-25 03:35:31'),(6,'Marcas','marcas','Gestión de marcas','2025-11-25 03:35:32','2025-11-25 03:35:32'),(7,'Colores','colores','Gestión de colores','2025-11-25 03:35:32','2025-11-25 03:35:32'),(8,'Tallas','tallas','Gestión de tallas','2025-11-25 03:35:32','2025-11-25 03:35:32'),(9,'Medidas','medidas','Gestión de medidas','2025-11-25 03:35:32','2025-11-25 03:35:32'),(10,'Sabores','sabores','Gestión de sabores','2025-11-25 03:35:32','2025-11-25 03:35:32'),(11,'Modelos','modelos','Gestión de modelos','2025-11-25 03:35:32','2025-11-25 03:35:32'),(12,'Tonos','tonos','Gestión de tonos','2025-11-25 03:35:32','2025-11-25 03:35:32'),(13,'Artículos','articulos','Gestión de artículos, variantes e inventario','2025-11-25 03:35:32','2025-11-25 03:35:32'),(14,'Plazas','plazas','Gestión de plazas','2025-11-25 03:35:32','2025-11-25 03:35:32'),(15,'Nosotros','nosotros','Gestión de la sección Nosotros','2025-11-25 03:35:32','2025-11-25 03:35:32');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nosotros`
--

DROP TABLE IF EXISTS `nosotros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nosotros` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuerpo_principal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cuerpo_secundario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `imagen_portada_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `imagen_cuerpo_1_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `imagen_cuerpo_2_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nosotros`
--

LOCK TABLES `nosotros` WRITE;
/*!40000 ALTER TABLE `nosotros` DISABLE KEYS */;
/*!40000 ALTER TABLE `nosotros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `articulo_id` int NOT NULL,
  `articulo_variante_id` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `fk_variante` (`articulo_variante_id`),
  CONSTRAINT `fk_variante` FOREIGN KEY (`articulo_variante_id`) REFERENCES `articulo_variantes` (`id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_payments`
--

DROP TABLE IF EXISTS `order_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `comprobante_url` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `observaciones` text,
  `tecnico_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_payments`
--

LOCK TABLES `order_payments` WRITE;
/*!40000 ALTER TABLE `order_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `puntos_generados` int DEFAULT '0',
  `estado` enum('pendiente_abono','pendiente_pago_total','validando_pago','completado','cancelado') DEFAULT 'pendiente_abono',
  `tipo` enum('compra_directa','reserva') DEFAULT 'reserva',
  `fecha_limite` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `permission_role_role_id_permission_id_unique` (`role_id`,`permission_id`) USING BTREE,
  KEY `permission_role_role_id_index` (`role_id`) USING BTREE,
  KEY `permission_role_permission_id_index` (`permission_id`) USING BTREE,
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,1,1,NULL,NULL),(2,1,2,NULL,NULL),(3,1,3,NULL,NULL),(4,1,4,NULL,NULL),(5,1,5,NULL,NULL),(6,1,6,NULL,NULL),(7,1,7,NULL,NULL),(8,1,8,NULL,NULL),(9,1,9,NULL,NULL),(10,1,10,NULL,NULL),(11,1,11,NULL,NULL),(12,1,12,NULL,NULL),(13,1,13,NULL,NULL),(14,1,14,NULL,NULL),(15,1,15,NULL,NULL),(16,1,16,NULL,NULL),(17,1,17,NULL,NULL),(18,1,18,NULL,NULL),(19,1,19,NULL,NULL),(20,1,20,NULL,NULL),(21,1,21,NULL,NULL),(22,1,22,NULL,NULL),(23,1,23,NULL,NULL),(24,1,24,NULL,NULL),(25,1,25,NULL,NULL),(26,1,26,NULL,NULL),(27,1,27,NULL,NULL),(28,1,28,NULL,NULL),(29,1,29,NULL,NULL),(30,1,30,NULL,NULL),(31,1,31,NULL,NULL),(32,1,32,NULL,NULL),(33,1,33,NULL,NULL),(34,1,34,NULL,NULL),(35,1,35,NULL,NULL),(36,1,36,NULL,NULL),(37,1,37,NULL,NULL),(38,1,38,NULL,NULL),(39,1,39,NULL,NULL),(40,1,40,NULL,NULL),(41,1,41,NULL,NULL),(42,1,42,NULL,NULL),(43,1,43,NULL,NULL),(44,1,44,NULL,NULL),(45,1,45,NULL,NULL),(46,1,46,NULL,NULL),(47,1,47,NULL,NULL),(48,1,48,NULL,NULL),(49,1,49,NULL,NULL),(50,1,50,NULL,NULL),(51,1,51,NULL,NULL),(52,1,52,NULL,NULL),(53,1,53,NULL,NULL),(54,1,54,NULL,NULL),(55,1,55,NULL,NULL),(56,1,56,NULL,NULL),(57,1,57,NULL,NULL),(58,1,58,NULL,NULL),(59,1,59,NULL,NULL),(60,1,60,NULL,NULL),(61,1,61,NULL,NULL),(62,1,62,NULL,NULL),(63,1,63,NULL,NULL),(64,1,64,NULL,NULL),(65,1,65,NULL,NULL),(66,1,66,NULL,NULL),(67,1,67,NULL,NULL),(68,1,68,NULL,NULL),(69,1,69,NULL,NULL),(70,1,70,NULL,NULL),(71,1,71,NULL,NULL),(72,1,72,NULL,NULL),(73,1,73,NULL,NULL),(74,1,74,NULL,NULL),(75,1,75,NULL,NULL),(76,1,76,NULL,NULL),(77,1,77,NULL,NULL),(78,1,78,NULL,NULL),(79,1,79,NULL,NULL),(80,1,80,NULL,NULL),(81,1,81,NULL,NULL),(82,1,82,NULL,NULL),(83,1,83,NULL,NULL),(84,1,84,NULL,NULL),(85,1,85,NULL,NULL),(86,1,86,NULL,NULL),(87,1,87,NULL,NULL),(88,1,88,NULL,NULL),(89,1,90,NULL,NULL),(90,1,89,NULL,NULL);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `module_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `permissions_slug_unique` (`slug`) USING BTREE,
  KEY `permissions_module_id_index` (`module_id`) USING BTREE,
  CONSTRAINT `permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Ver listado de usuarios','usuarios.index','Permite ver listado de usuarios',1,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(2,'Ver detalles de usuario','usuarios.show','Permite ver detalles de usuario',1,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(3,'Crear usuarios','usuarios.create','Permite crear usuarios',1,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(4,'Editar usuarios','usuarios.edit','Permite editar usuarios',1,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(5,'Eliminar usuarios','usuarios.delete','Permite eliminar usuarios',1,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(6,'Ver listado de roles','roles.index','Permite ver listado de roles',2,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(7,'Ver detalles de rol','roles.show','Permite ver detalles de rol',2,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(8,'Crear roles','roles.create','Permite crear roles',2,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(9,'Editar roles','roles.edit','Permite editar roles',2,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(10,'Eliminar roles','roles.delete','Permite eliminar roles',2,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(11,'Ver dashboard','inicio.view','Permite ver dashboard',3,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(12,'Ver módulos','modulos.index','Permite ver módulos',3,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(13,'Configurar sistema','sistema.configuracion','Permite configurar sistema',3,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(14,'Ver logs del sistema','sistema.logs','Permite ver logs del sistema',3,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(15,'Realizar backups','sistema.backup','Permite realizar backups',3,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(16,'Ver listado de carrusel','carrusel.index','Permite ver listado de carrusel',4,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(17,'Ver detalles del carrusel','carrusel.show','Permite ver detalles del carrusel',4,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(18,'Crear elementos del carrusel','carrusel.create','Permite crear elementos del carrusel',4,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(19,'Editar elementos del carrusel','carrusel.edit','Permite editar elementos del carrusel',4,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(20,'Eliminar elementos del carrusel','carrusel.delete','Permite eliminar elementos del carrusel',4,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(21,'Cambiar estado del carrusel','carrusel.estado','Permite cambiar estado del carrusel',4,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(22,'Ver listado de categorías','categorias.index','Permite ver listado de categorías',5,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(23,'Ver detalles de categoría','categorias.show','Permite ver detalles de categoría',5,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(24,'Crear categorías','categorias.create','Permite crear categorías',5,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(25,'Editar categorías','categorias.edit','Permite editar categorías',5,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(26,'Eliminar categorías','categorias.delete','Permite eliminar categorías',5,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(27,'Cambiar estado de categorías','categorias.estado','Permite cambiar estado de categorías',5,'2025-11-25 03:35:31','2025-11-25 03:35:31'),(28,'Ver listado de marcas','marcas.index','Permite ver listado de marcas',6,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(29,'Ver detalles de marca','marcas.show','Permite ver detalles de marca',6,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(30,'Crear marcas','marcas.create','Permite crear marcas',6,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(31,'Editar marcas','marcas.edit','Permite editar marcas',6,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(32,'Eliminar marcas','marcas.delete','Permite eliminar marcas',6,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(33,'Cambiar estado de marcas','marcas.estado','Permite cambiar estado de marcas',6,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(34,'Ver listado de colores','colores.index','Permite ver listado de colores',7,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(35,'Ver detalles de color','colores.show','Permite ver detalles de color',7,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(36,'Crear colores','colores.create','Permite crear colores',7,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(37,'Editar colores','colores.edit','Permite editar colores',7,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(38,'Eliminar colores','colores.delete','Permite eliminar colores',7,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(39,'Cambiar estado de colores','colores.estado','Permite cambiar estado de colores',7,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(40,'Ver listado de tallas','tallas.index','Permite ver listado de tallas',8,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(41,'Ver detalles de talla','tallas.show','Permite ver detalles de talla',8,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(42,'Crear tallas','tallas.create','Permite crear tallas',8,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(43,'Editar tallas','tallas.edit','Permite editar tallas',8,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(44,'Eliminar tallas','tallas.delete','Permite eliminar tallas',8,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(45,'Cambiar estado de tallas','tallas.estado','Permite cambiar estado de tallas',8,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(46,'Ver listado de medidas','medidas.index','Permite ver listado de medidas',9,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(47,'Ver detalles de medida','medidas.show','Permite ver detalles de medida',9,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(48,'Crear medidas','medidas.create','Permite crear medidas',9,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(49,'Editar medidas','medidas.edit','Permite editar medidas',9,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(50,'Eliminar medidas','medidas.delete','Permite eliminar medidas',9,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(51,'Cambiar estado de medidas','medidas.estado','Permite cambiar estado de medidas',9,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(52,'Ver listado de sabores','sabores.index','Permite ver listado de sabores',10,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(53,'Ver detalles de sabor','sabores.show','Permite ver detalles de sabor',10,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(54,'Crear sabores','sabores.create','Permite crear sabores',10,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(55,'Editar sabores','sabores.edit','Permite editar sabores',10,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(56,'Eliminar sabores','sabores.delete','Permite eliminar sabores',10,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(57,'Cambiar estado de sabores','sabores.estado','Permite cambiar estado de sabores',10,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(58,'Ver listado de modelos','modelos.index','Permite ver listado de modelos',11,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(59,'Ver detalles de modelo','modelos.show','Permite ver detalles de modelo',11,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(60,'Crear modelos','modelos.create','Permite crear modelos',11,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(61,'Editar modelos','modelos.edit','Permite editar modelos',11,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(62,'Eliminar modelos','modelos.delete','Permite eliminar modelos',11,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(63,'Cambiar estado de modelos','modelos.estado','Permite cambiar estado de modelos',11,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(64,'Ver listado de tonos','tonos.index','Permite ver listado de tonos',12,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(65,'Ver detalles de tono','tonos.show','Permite ver detalles de tono',12,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(66,'Crear tonos','tonos.create','Permite crear tonos',12,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(67,'Editar tonos','tonos.edit','Permite editar tonos',12,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(68,'Eliminar tonos','tonos.delete','Permite eliminar tonos',12,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(69,'Cambiar estado de tonos','tonos.estado','Permite cambiar estado de tonos',12,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(70,'Ver listado de artículos','articulos.index','Permite ver listado de artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(71,'Ver detalles de artículo','articulos.show','Permite ver detalles de artículo',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(72,'Crear artículos','articulos.create','Permite crear artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(73,'Editar artículos','articulos.edit','Permite editar artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(74,'Eliminar artículos','articulos.delete','Permite eliminar artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(75,'Cambiar estado de artículos','articulos.estado','Permite cambiar estado de artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(76,'Crear variantes de artículos','articulos.crear_variantes','Permite crear variantes de artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(77,'Editar variantes de artículos','articulos.editar_variantes','Permite editar variantes de artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(78,'Eliminar variantes de artículos','articulos.eliminar_variantes','Permite eliminar variantes de artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(79,'Ver información de stock','articulos.ver_stock','Permite ver información de stock',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(80,'Reservar stock de artículos','articulos.reservar_stock','Permite reservar stock de artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(81,'Liberar stock reservado','articulos.liberar_stock','Permite liberar stock reservado',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(82,'Decrementar stock de artículos','articulos.decrementar_stock','Permite decrementar stock de artículos',13,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(83,'Ver listado de plazas','plazas.index','Permite ver listado de plazas',14,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(84,'Ver detalles de plaza','plazas.show','Permite ver detalles de plaza',14,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(85,'Crear plazas','plazas.create','Permite crear plazas',14,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(86,'Editar plazas','plazas.edit','Permite editar plazas',14,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(87,'Eliminar plazas','plazas.delete','Permite eliminar plazas',14,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(88,'Cambiar estado de plazas','plazas.estado','Permite cambiar estado de plazas',14,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(89,'Ver sección Nosotros','view nosotros','Permite ver la información de la sección Nosotros',15,'2025-11-25 03:35:32','2025-11-25 03:35:32'),(90,'Administrar sección Nosotros','manage nosotros','Permite crear o actualizar la información de la sección Nosotros',15,'2025-11-25 03:35:32','2025-11-25 03:35:32');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`) USING BTREE,
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plazas`
--

DROP TABLE IF EXISTS `plazas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plazas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plazas`
--

LOCK TABLES `plazas` WRITE;
/*!40000 ALTER TABLE `plazas` DISABLE KEYS */;
/*!40000 ALTER TABLE `plazas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_settings`
--

DROP TABLE IF EXISTS `point_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `evento` varchar(50) DEFAULT NULL,
  `valor_puntos` int DEFAULT NULL,
  `es_porcentaje` tinyint(1) DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_settings`
--

LOCK TABLES `point_settings` WRITE;
/*!40000 ALTER TABLE `point_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `point_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_transactions`
--

DROP TABLE IF EXISTS `point_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `point_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_transactions`
--

LOCK TABLES `point_transactions` WRITE;
/*!40000 ALTER TABLE `point_transactions` DISABLE KEYS */;
INSERT INTO `point_transactions` VALUES (2,3,10,'Bono de Bienvenida',NULL,NULL,'2026-01-08 02:32:28','2026-01-08 02:32:28');
/*!40000 ALTER TABLE `point_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `roles_nombre_unique` (`nombre`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Administrador','Acceso completo al sistema','2025-11-25 03:35:31','2025-11-25 03:35:31');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sabores`
--

DROP TABLE IF EXISTS `sabores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sabores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sabores`
--

LOCK TABLES `sabores` WRITE;
/*!40000 ALTER TABLE `sabores` DISABLE KEYS */;
/*!40000 ALTER TABLE `sabores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tallas`
--

DROP TABLE IF EXISTS `tallas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tallas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tallas`
--

LOCK TABLES `tallas` WRITE;
/*!40000 ALTER TABLE `tallas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tallas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tonos`
--

DROP TABLE IF EXISTS `tonos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tonos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tonos`
--

LOCK TABLES `tonos` WRITE;
/*!40000 ALTER TABLE `tonos` DISABLE KEYS */;
INSERT INTO `tonos` VALUES (1,'test',1,'2025-12-23 20:45:56','2025-12-23 20:45:56'),(2,'hola',1,'2025-12-23 20:46:16','2025-12-23 20:46:16');
/*!40000 ALTER TABLE `tonos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_addresses`
--

DROP TABLE IF EXISTS `user_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provincia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion_exacta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `principal` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_address` (`user_id`),
  CONSTRAINT `fk_user_address` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_addresses`
--

LOCK TABLES `user_addresses` WRITE;
/*!40000 ALTER TABLE `user_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_documento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombres` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  `codigo_referido` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referido_por_codigo` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `puntos_acumulados` int DEFAULT '0',
  `fallos_reserva` int DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `users_numero_documento_unique` (`numero_documento`) USING BTREE,
  UNIQUE KEY `users_email_unique` (`email`) USING BTREE,
  UNIQUE KEY `codigo_referido` (`codigo_referido`),
  KEY `users_role_id_foreign` (`role_id`) USING BTREE,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'99999999',NULL,'Estilo','Store',NULL,'Estilo Store','super_admin@admin.com','2025-11-25 03:35:32','$2y$10$vmpmGe.nD66VwDn635JnBe3tz8ounPHVTTCJj1wh41F/hn.0Yw.QW',NULL,'2025-11-25 03:35:32','2025-11-25 03:35:32',1,NULL,NULL,0,0,1),(3,'1721543526','CEDULA','Diego NZ','asdasd',NULL,'Diego NZ asdasd','correo@correo.com',NULL,'$2y$10$7bqTqDGjSCFsw5d.k7aSw..oJCb95I3Ov9CZozh3UOlwQaFN7BN/u',NULL,'2026-01-08 02:32:28','2026-01-08 02:32:28',NULL,'925DWUDU',NULL,10,0,1);
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

-- Dump completed on 2026-01-09 21:11:55
