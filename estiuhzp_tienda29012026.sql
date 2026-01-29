-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-01-2026 a las 23:31:30
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `estiuhzp_tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL COMMENT 'Nombre del artículo',
  `slug` varchar(255) NOT NULL COMMENT 'Slug único para URLs',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción del artículo',
  `especificaciones` text DEFAULT NULL COMMENT 'Especificaciones técnicas',
  `precio` decimal(10,2) NOT NULL COMMENT 'Precio base del artículo',
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `porcentaje_descuento` int(11) DEFAULT NULL,
  `en_oferta` tinyint(1) DEFAULT 0,
  `sku` varchar(255) DEFAULT NULL COMMENT 'SKU del artículo base',
  `categoria_id` bigint(20) UNSIGNED DEFAULT NULL,
  `marca_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Artículo activo/inactivo',
  `destacado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Artículo destacado',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `nombre`, `slug`, `descripcion`, `especificaciones`, `precio`, `precio_oferta`, `porcentaje_descuento`, `en_oferta`, `sku`, `categoria_id`, `marca_id`, `activo`, `destacado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Audífonos Bluetooth Pro', 'audifonos-bluetooth-pro', 'Excelente calidad de sonido', NULL, 59.90, NULL, NULL, 0, 'AAAAA0001', 1, 1, 1, 0, NULL, '2026-01-04 00:47:39', '2026-01-04 00:47:39'),
(4, 'Smartphone S24 Ultra', 'smartphone-s24-ultra', 'Cámara de 200MP', NULL, 999.00, NULL, NULL, 0, 'AAAAA0004', 3, 1, 1, 0, NULL, '2026-01-04 00:48:36', '2026-01-04 00:48:36'),
(5, 'Casaca Cortavientos', 'casaca-cortavientos', 'Ideal para climas fríos', NULL, 35.00, NULL, NULL, 0, 'AAAAA0005', 5, 1, 1, 0, NULL, '2026-01-04 00:48:44', '2026-01-04 00:48:44'),
(6, 'Pantalón Jean Slim', 'pantalon-jean-slim', 'Color azul clásico', NULL, 25.00, NULL, NULL, 0, 'AAAAA0006', 9, 1, 1, 0, NULL, '2026-01-04 00:48:51', '2026-01-04 00:48:51'),
(7, 'Zapatillas Running A1', 'zapatillas-running-a1', 'Amortiguación superior', NULL, 85.00, 76.50, 10, 1, 'AAAAA0007', 11, 1, 1, 0, NULL, '2026-01-04 00:48:59', '2026-01-04 00:48:59'),
(8, 'Parlante Inteligente', 'parlante-inteligente', 'Compatible con Alexa y Google', NULL, 49.00, 44.10, 10, 1, 'AAAAA0008', 20, 1, 1, 0, NULL, '2026-01-04 00:49:05', '2026-01-04 00:49:05'),
(9, 'articulo completo', 'articulo-completo-A', 'articulo-completo-Aarticulo-completo-Aarticulo-completo-Aarticulo-completo-Aarticulo-completo-Aarticulo-completo-A', NULL, 100.00, NULL, NULL, 0, 'ACA100', 5, 1, 1, 0, NULL, '2026-01-29 05:12:37', '2026-01-29 05:12:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_color`
--

CREATE TABLE `articulo_color` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `color_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_imagenes`
--

CREATE TABLE `articulo_imagenes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `ruta` varchar(255) NOT NULL COMMENT 'Ruta del archivo de imagen (UUID)',
  `orden` int(11) NOT NULL DEFAULT 0 COMMENT 'Orden de presentación de la imagen',
  `metadatos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Metadatos: width, height, size, alt text, etc',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulo_imagenes`
--

INSERT INTO `articulo_imagenes` (`id`, `articulo_id`, `ruta`, `orden`, `metadatos`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'default.png', 1, NULL, 0, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(4, 4, 'default.png', 1, NULL, 1, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(5, 5, 'default.png', 1, NULL, 1, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(6, 6, 'default.png', 1, NULL, 1, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(7, 7, '1.png', 1, NULL, 1, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(8, 8, 'default.png', 1, NULL, 1, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(16, 7, '1.png', 2, NULL, 1, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(17, 7, '2.png', 3, NULL, 1, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(18, 8, 'default.png', 2, NULL, 1, '2026-01-04 00:49:13', '2026-01-04 00:49:13'),
(19, 9, 'articulos/9/ea6041ad-6b8a-4b03-8f99-d0438a34fbe4.png', 1, '{\"size\":11013,\"mime\":\"image\\/png\",\"width\":322,\"height\":181}', 1, '2026-01-29 05:12:38', '2026-01-29 05:14:38'),
(20, 9, 'articulos/9/5897b23c-e418-4769-9de7-5db3eb1390cc.png', 2, '{\"size\":165071,\"mime\":\"image\\/png\",\"width\":361,\"height\":275}', 1, '2026-01-29 05:12:38', '2026-01-29 05:14:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_medida`
--

CREATE TABLE `articulo_medida` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `medida_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_modelo`
--

CREATE TABLE `articulo_modelo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `modelo_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_plaza`
--

CREATE TABLE `articulo_plaza` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `plaza_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_sabor`
--

CREATE TABLE `articulo_sabor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `sabor_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_talla`
--

CREATE TABLE `articulo_talla` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `talla_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_tono`
--

CREATE TABLE `articulo_tono` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `tono_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_variantes`
--

CREATE TABLE `articulo_variantes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `color_id` bigint(20) UNSIGNED DEFAULT NULL,
  `talla_id` bigint(20) UNSIGNED DEFAULT NULL,
  `atributos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Atributos de la variante (talla, color, plaza, etc)',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Stock total de la variante',
  `activo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Variante activa/inactiva',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulo_variantes`
--

INSERT INTO `articulo_variantes` (`id`, `articulo_id`, `color_id`, `talla_id`, `atributos`, `stock`, `activo`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 1, '1', 0, 1, '2026-01-08 16:36:10', '2026-01-08 16:36:10'),
(2, 7, 1, 2, '1', 1, 1, '2026-01-08 16:36:10', '2026-01-08 16:36:10'),
(3, 7, 2, 2, '1', 1, 1, '2026-01-08 16:36:10', '2026-01-08 16:36:10'),
(6, 9, NULL, NULL, '{\"colores\":\"Azul Noche\",\"tallas\":\"L\",\"sabores\":\"Chocolate\",\"modelos\":\"2022\",\"tonos\":\"hola\",\"medidas\":\"500ml\",\"plazas\":\"Plaza Mayor\"}', 99, 1, NULL, NULL),
(7, 9, NULL, NULL, '{\"colores\":\"Fucsia ser dama\",\"modelos\":\"Pro\",\"plazas\":\"Plaza Este\"}', 36, 1, NULL, NULL),
(8, 9, NULL, NULL, '{\"sabores\":\"Lim\\u00f3n\"}', 9, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `seccion` varchar(255) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `imagen_ruta` varchar(255) NOT NULL,
  `url_destino` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `banners`
--

INSERT INTO `banners` (`id`, `seccion`, `titulo`, `subtitulo`, `imagen_ruta`, `url_destino`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'initial_header', '¿Cómo pagar tus pedidos?', '¡Forma parte de nuestra comunidad ahora!', 'banners/1769122164_3ExL2wJ84d.jpg', 'https://tu-sitio.com/guia-pagos', 1, '2026-01-03 20:59:11', '2026-01-23 03:49:24'),
(2, 'nosotros_header', 'Nosotros', 'Quienes Somos?', 'banners/nosotros.png', NULL, 1, '2026-01-03 20:59:11', '2026-01-03 20:59:11'),
(3, 'publicidad_uno', 'Publicidad Uno editable', 'Lorem ipsum dolor sit amet, consectetur', 'banners/banner.png', NULL, 1, NULL, NULL),
(4, 'publicidad_dos', 'Publicidad Uno editable', 'Lorem ipsum dolor sit amet, consectetur', 'banners/banner.png', NULL, 1, NULL, NULL),
(5, 'panel_cuenta_uno', 'Publicidad Uno editable', 'Lorem ipsum dolor sit amet, consectetur', 'banners/banner.png', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrusel`
--

CREATE TABLE `carrusel` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `activar_subtitulo` tinyint(1) NOT NULL DEFAULT 0,
  `activar_boton` tinyint(1) NOT NULL DEFAULT 0,
  `url_boton` varchar(255) DEFAULT NULL,
  `texto_boton` varchar(255) DEFAULT NULL,
  `color_boton` varchar(7) DEFAULT '#3B82F6',
  `redirigir_misma_pagina` tinyint(1) NOT NULL DEFAULT 0,
  `posicion_contenido` enum('Izquierda','Derecha') NOT NULL DEFAULT 'Izquierda',
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `carrusel`
--

INSERT INTO `carrusel` (`id`, `titulo`, `subtitulo`, `activar_subtitulo`, `activar_boton`, `url_boton`, `texto_boton`, `color_boton`, `redirigir_misma_pagina`, `posicion_contenido`, `imagen`, `estado`, `created_at`, `updated_at`) VALUES
(11, 'Conoce nuestros productos', 'opiconal akjncibd', 1, 1, 'https://www.fb.com', 'Botón', '#E23CC3', 0, 'Derecha', 'carrusel/1769208788_Ef9Z8EKhmb.png', 1, '2025-12-25 07:17:21', '2026-01-25 22:40:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `slug`, `imagen`, `descripcion`, `parent_id`, `activo`, `orden`, `created_at`, `updated_at`) VALUES
(1, 'Accesorios', 'accesorios', 'categorias/1.png', 'accesorios', NULL, 1, 1, '2025-11-25 18:15:12', '2025-11-25 18:15:12'),
(3, 'Cuidado Corporal', 'cuidado-corporal', 'categorias/3.png', NULL, NULL, 1, 2, '2026-01-03 22:43:38', '2026-01-03 22:43:38'),
(4, 'Cuidado Facial', 'cuidado-facial', 'categorias/4.png', NULL, NULL, 1, 3, '2026-01-03 22:43:38', '2026-01-03 22:43:38'),
(5, 'Hogar', 'hogar', 'categorias/5.png', NULL, NULL, 1, 4, '2026-01-03 22:43:38', '2026-01-03 22:43:38'),
(7, 'Maquilaje', 'maquilaje', 'categorias/6.png', NULL, NULL, 1, 5, '2026-01-03 22:43:41', '2026-01-03 22:43:41'),
(8, 'Iluminación', 'iluminacion', 'categorias/ex1.jpg', NULL, 6, 1, 3, '2026-01-03 22:43:41', '2026-01-03 22:43:41'),
(9, 'Hombre', 'moda-hombre', 'categorias/ex1.jpg', NULL, 5, 1, 1, '2026-01-03 22:43:43', '2026-01-03 22:43:43'),
(10, 'Mujer', 'moda-mujer', 'categorias/ex1.jpg', NULL, 5, 1, 2, '2026-01-03 22:43:43', '2026-01-03 22:43:43'),
(11, 'Calzado', 'calzado', 'categorias/ex1.jpg', NULL, 5, 1, 3, '2026-01-03 22:43:43', '2026-01-03 22:43:43'),
(12, 'Fitness', 'fitness', 'categorias/ex1.jpg', NULL, 5, 1, 1, '2026-01-03 22:43:46', '2026-01-03 22:43:46'),
(13, 'Ciclismo', 'ciclismo', 'categorias/ex1.jpg', NULL, 4, 1, 2, '2026-01-03 22:43:46', '2026-01-03 22:43:46'),
(14, 'Fútbol', 'futbol', 'categorias/ex1.jpg', NULL, 4, 1, 3, '2026-01-03 22:43:46', '2026-01-03 22:43:46'),
(15, 'Maquillaje', 'maquillaje', 'categorias/ex1.jpg', NULL, 5, 1, 1, '2026-01-03 22:44:35', '2026-01-03 22:44:35'),
(16, 'Cuidado de la Piel', 'skincare', 'categorias/ex1.jpg', NULL, 5, 1, 2, '2026-01-03 22:44:35', '2026-01-03 22:44:35'),
(17, 'Perfumes', 'perfumes', 'categorias/ex1.jpg', NULL, 5, 1, 3, '2026-01-03 22:44:35', '2026-01-03 22:44:35'),
(18, 'Cuidado Capilar', 'cabello', 'categorias/ex1.jpg', NULL, 5, 1, 4, '2026-01-03 22:44:35', '2026-01-03 22:44:35'),
(19, 'Cámaras', 'camaras-fotograficas', 'categorias/ex1.jpg', NULL, 1, 1, 5, '2026-01-03 22:44:37', '2026-01-03 22:44:37'),
(20, 'Audio y Video', 'audio-video', 'categorias/ex1.jpg', NULL, 1, 1, 6, '2026-01-03 22:44:37', '2026-01-03 22:44:37'),
(22, 'Jardinería', 'jardineria', 'categorias/jardineria.jpg', NULL, NULL, 1, 5, '2026-01-03 22:44:40', '2026-01-03 22:44:40'),
(23, 'Natación', 'natacion', 'categorias/natacion.jpg', NULL, NULL, 1, 7, '2026-01-03 22:44:42', '2026-01-03 22:44:42'),
(24, 'Camping', 'camping-aventura', 'categorias/camping.jpg', NULL, NULL, 1, 5, '2026-01-03 22:44:42', '2026-01-03 22:44:42'),
(25, 'Categoría Invisible', 'invisible', 'categorias/ex1.jpg', NULL, NULL, 0, 99, '2026-01-03 22:44:44', '2026-01-03 22:44:44'),
(26, 'nueva categoria', 'nueva-categoria', 'categorias/LPOENQRttiAk4RLBO8CRY20BenKJkWnsaBxA1il0.png', 'nuea oanscjbaive aby hrmosa mi amor chula', NULL, 1, 0, '2026-01-29 09:02:49', '2026-01-30 02:08:33'),
(27, 'imagen-logo', 'imagen-logo', 'categorias/QRQ9gPz2GHTWTKaQJ0ugEJIEfpt4Y7aUGas8XDsO.png', 'imagen-logoimagen-logoimagen-logoimagen-logo', NULL, 1, 0, '2026-01-29 18:42:09', '2026-01-29 19:17:41'),
(28, 'nueva 2', 'nueva-2', 'categorias/Jz8QnlgorfqwQPPcY35OVDSZ7ByFjTdxydy88QLx.png', 'nueva-2nueva-2nueva-2nueva-2nueva-2nueva-2', 26, 1, 1, '2026-01-29 19:19:05', '2026-01-29 19:19:05'),
(29, 'iosndcbuv', 'iosndcbuv', 'categorias/vo9vuKzY3Rp67OfxWCOLziwfodZMymAHtpftwr5N.png', 'sdkvjbhidshbv', NULL, 1, 1, '2026-01-30 02:02:08', '2026-01-30 02:02:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE `colores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `codigo_hex` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `colores`
--

INSERT INTO `colores` (`id`, `nombre`, `codigo_hex`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Fucsia ser dama', '#ff007f', 1, NULL, NULL),
(2, 'Azul Noche', '#2b3d51', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa_datos`
--

CREATE TABLE `empresa_datos` (
  `id` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `orden` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `empresa_datos`
--

INSERT INTO `empresa_datos` (`id`, `clave`, `titulo`, `contenido`, `imagen`, `activo`, `orden`, `created_at`, `updated_at`) VALUES
(1, 'quienes_somos', 'Nuestra Historia', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'nosotros/3.png', 1, 1, '2026-01-04 16:51:15', '2026-01-11 12:55:54'),
(3, 'vision', 'Nuestra Visión', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 'nosotros/2.png', 1, 3, '2026-01-04 16:51:15', '2026-01-11 12:57:35'),
(4, 'mision', 'Nuestra Misión', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', 'nosotros/2.png', 1, 0, '2026-01-05 01:55:53', '2026-01-05 01:56:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `folletos`
--

CREATE TABLE `folletos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `archivo_pdf` varchar(255) NOT NULL,
  `descargas` int(11) NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `folletos`
--

INSERT INTO `folletos` (`id`, `nombre`, `descripcion`, `archivo_pdf`, `descargas`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Catálogo General 2024', 'Descarga nuestro folleto con todos los productos y promociones vigentes.', 'folletos/prueba.pdf', 5, 1, '2026-01-03 20:00:13', '2026-01-14 01:13:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `header_principal_items`
--

CREATE TABLE `header_principal_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo_uno` varchar(255) DEFAULT NULL,
  `descripcion_uno` varchar(255) DEFAULT NULL,
  `boton_uno_texto` varchar(255) DEFAULT NULL,
  `boton_uno_url` varchar(2048) DEFAULT NULL,
  `boton_uno_nueva_pestana` tinyint(1) NOT NULL DEFAULT 0,
  `alt_uno` varchar(255) DEFAULT NULL,
  `imagen_uno` varchar(255) DEFAULT NULL,
  `titulo_dos` varchar(255) DEFAULT NULL,
  `descripcion_dos` varchar(255) DEFAULT NULL,
  `boton_dos_texto` varchar(255) DEFAULT NULL,
  `boton_dos_url` varchar(2048) DEFAULT NULL,
  `boton_dos_nueva_pestana` tinyint(1) NOT NULL DEFAULT 0,
  `alt_dos` varchar(255) DEFAULT NULL,
  `imagen_dos` varchar(255) DEFAULT NULL,
  `imagen_mobile` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `legal_documents`
--

CREATE TABLE `legal_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `archivo_url` varchar(255) NOT NULL,
  `version` varchar(10) DEFAULT '1.0',
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `slug`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Marca Genérica', 'marca-generica', NULL, 1, '2026-01-04 00:47:15', '2026-01-04 00:47:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medidas`
--

CREATE TABLE `medidas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `medidas`
--

INSERT INTO `medidas` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(1, '500g', 1, '2026-01-28 22:15:41', '2026-01-28 22:15:41'),
(2, '1kg', 1, '2026-01-28 22:15:41', '2026-01-28 22:15:41'),
(3, '1.5kg', 1, '2026-01-28 22:15:41', '2026-01-28 22:15:41'),
(4, '2kg', 1, '2026-01-28 22:15:41', '2026-01-28 22:15:41'),
(5, '5kg', 1, '2026-01-28 22:15:41', '2026-01-28 22:15:41'),
(7, '250ml', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(8, '500ml', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(9, '1L', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(10, '2L', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(11, '500g', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(12, '1kg', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(13, '1.5kg', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(14, '2kg', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(15, '5kg', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50'),
(16, '100ml', 1, '2026-01-28 22:20:50', '2026-01-28 22:20:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL,
  `nombre_banco` varchar(100) NOT NULL,
  `tipo_pago` enum('Transferencia','QR','Efectivo','Otro') DEFAULT 'Transferencia',
  `nombre_titular` varchar(150) DEFAULT NULL,
  `numero_cuenta` varchar(50) DEFAULT NULL,
  `tipo_cuenta` varchar(50) DEFAULT NULL,
  `identificacion` varchar(20) DEFAULT NULL,
  `instrucciones` text DEFAULT NULL,
  `imagen_qr` varchar(255) DEFAULT NULL,
  `logo_banco` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre_banco`, `tipo_pago`, `nombre_titular`, `numero_cuenta`, `tipo_cuenta`, `identificacion`, `instrucciones`, `imagen_qr`, `logo_banco`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Banco Guayaquil', 'Transferencia', 'Nathaly Zambrano', '39212129', 'Corriente', '1712345678', 'Transferencias interbancarias pueden tardar hasta 24 horas.', NULL, 'metodos_pago/logos/q1Cd84jyeZYUHCYt0uaAcw2GKHrwEPLWlHh5zxo5.png', 1, '2026-01-10 22:52:14', '2026-01-29 05:44:36'),
(2, 'De Una!', 'QR', NULL, NULL, NULL, NULL, 'Escanea el código QR y realiza el pago. Es inmediato y sin costo.', 'metodos_pago/qr/zpmsZvxBekC5RmP6a3SpOL3NOxt6p2znEt3c1xbW.png', 'metodos_pago/logos/6NlUuipPsoC5znZWDyvGz39SHIPEdzgMhos3GZTJ.png', 1, '2026-01-10 22:52:24', '2026-01-29 05:44:15'),
(3, 'Banco Pichincha', 'Transferencia', 'Nathaly Zambrano', '2208050416', 'Ahorros', '1790001234001', 'Por favor, enviar el comprobante indicando tu número de cédula en el concepto.', NULL, 'metodos_pago/logos/xyZHCW41vVCROx2PWVxQvgcZk0dZHv94TwK9AR7T.png', 1, '2026-01-10 22:52:26', '2026-01-29 05:43:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_01_000001_create_modules_table', 1),
(6, '2024_01_01_000002_create_roles_table', 1),
(7, '2024_01_01_000003_create_permissions_table', 1),
(8, '2024_01_01_000004_modify_users_table', 1),
(9, '2024_01_01_000005_create_permission_role_table', 1),
(10, '2024_01_01_000006_create_role_user_table', 1),
(11, '2025_09_29_020031_update_users_roles_relationship', 1),
(12, '2025_10_19_220229_create_carrusel_table', 1),
(13, '2025_10_20_013544_create_categorias_table', 1),
(14, '2025_10_20_055040_create_marcas_table', 1),
(15, '2025_10_20_062335_create_colores_table', 1),
(16, '2025_10_20_064844_create_tallas_table', 1),
(17, '2025_10_22_220014_create_plazas_table', 1),
(18, '2025_10_23_010737_create_medidas_table', 1),
(19, '2025_10_23_020801_create_sabores_table', 1),
(20, '2025_10_23_063454_create_modelos_table', 1),
(21, '2025_10_23_063457_create_tonos_table', 1),
(22, '2025_10_27_080000_create_articulos_table', 1),
(23, '2025_10_27_080001_create_articulo_imagenes_table', 1),
(24, '2025_10_27_080002_create_articulo_variantes_table', 1),
(25, '2025_10_27_080003_create_articulo_color_table', 1),
(26, '2025_10_27_080004_create_articulo_talla_table', 1),
(27, '2025_10_27_080005_create_articulo_plaza_table', 1),
(28, '2025_10_27_080006_create_articulo_medida_table', 1),
(29, '2025_10_27_080008_create_articulo_sabor_table', 1),
(30, '2025_10_27_080009_create_articulo_modelo_table', 1),
(31, '2025_10_27_080010_create_articulo_tono_table', 1),
(32, '2025_10_28_064517_remove_reserved_from_articulo_variantes_table', 1),
(33, '2025_10_29_072023_remove_sku_precio_from_articulo_variantes_table', 1),
(34, '2025_11_23_000000_create_nosotros_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelos`
--

CREATE TABLE `modelos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `modelos`
--

INSERT INTO `modelos` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(1, '2024', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(2, '2023', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(3, '2022', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(4, 'Pro', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(5, 'Lite', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(6, 'Max', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(7, 'Standard', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(8, 'Deluxe', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(9, 'Premium', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11'),
(10, 'Económico', 1, '2026-01-28 22:19:11', '2026-01-28 22:19:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id`, `nombre`, `slug`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Usuarios', 'usuarios', 'Gestión de usuarios del sistema', '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(2, 'Roles', 'roles', 'Gestión de roles y permisos', '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(3, 'Sistema', 'sistema', 'Configuraciones del sistema', '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(4, 'Carrusel', 'carrusel', 'Gestión del carrusel de imágenes', '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(5, 'Categorías', 'categoria', 'Gestión de categorías', '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(6, 'Marcas', 'marcas', 'Gestión de marcas', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(7, 'Colores', 'colores', 'Gestión de colores', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(8, 'Tallas', 'tallas', 'Gestión de tallas', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(9, 'Medidas', 'medidas', 'Gestión de medidas', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(10, 'Sabores', 'sabores', 'Gestión de sabores', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(11, 'Modelos', 'modelos', 'Gestión de modelos', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(12, 'Tonos', 'tonos', 'Gestión de tonos', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(13, 'Artículos', 'articulos', 'Gestión de artículos, variantes e inventario', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(14, 'Plazas', 'plazas', 'Gestión de plazas', '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(15, 'Nosotros', 'nosotros', 'Gestión de la sección Nosotros', '2025-11-25 03:35:32', '2025-11-25 03:35:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `puntos_minimos` int(11) NOT NULL,
  `premio_valor` decimal(10,2) DEFAULT NULL,
  `premio_descripcion` text DEFAULT NULL,
  `color_hex` varchar(7) DEFAULT '#6c757d',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id`, `nombre`, `puntos_minimos`, `premio_valor`, `premio_descripcion`, `color_hex`, `created_at`, `updated_at`) VALUES
(1, 'Bronce', 1000, 40.00, 'Premio Modular', '#CD7F32', '2026-01-14 14:08:25', '2026-01-14 14:08:25'),
(2, 'Plata', 2000, 80.00, 'Premio de Mesa (Table)', '#C0C0C0', '2026-01-14 14:08:25', '2026-01-14 14:08:25'),
(3, 'Oro', 3000, 120.00, 'Teléfono Celular', '#FFD700', '2026-01-14 14:08:25', '2026-01-14 14:08:25'),
(4, 'Golden', 9000, 360.00, 'Computadora / Muebles', '#FF8C00', '2026-01-14 14:08:25', '2026-01-14 14:08:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nosotros`
--

CREATE TABLE `nosotros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `cuerpo_principal` text DEFAULT NULL,
  `cuerpo_secundario` text DEFAULT NULL,
  `imagen_portada_url` text DEFAULT NULL,
  `imagen_cuerpo_1_url` text DEFAULT NULL,
  `imagen_cuerpo_2_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `puntos_generados` int(11) DEFAULT 0,
  `estado` enum('pendiente_abono','pendiente_pago_total','validando_pago','completado','cancelado') DEFAULT 'pendiente_abono',
  `tipo` enum('compra_directa','reserva') DEFAULT 'reserva',
  `fecha_limite` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` int(11) NOT NULL,
  `articulo_variante_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_payments`
--

CREATE TABLE `order_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `comprobante_url` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `tecnico_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `transporte_id` bigint(20) UNSIGNED DEFAULT NULL,
  `codigo_reserva` varchar(20) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `costo_envio` decimal(10,2) DEFAULT 0.00,
  `estado` enum('pendiente','abonado','completado','rechazado','cancelado') DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `user_id`, `transporte_id`, `codigo_reserva`, `subtotal`, `total`, `costo_envio`, `estado`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 'RES-DMLN15JT', 459.00, 459.00, 0.00, 'pendiente', '2026-01-14 02:08:51', '2026-01-14 02:08:51'),
(2, 3, NULL, 'RES-M6DBY2CN', 382.50, 382.50, 0.00, 'abonado', '2026-01-14 02:23:51', '2026-01-28 03:13:04'),
(3, 3, 5, 'RES-DZVOZN3T', 306.00, 306.00, 6.00, 'abonado', '2026-01-14 03:37:37', '2026-01-28 03:12:22'),
(4, 3, 19, 'RES-YEGHP0D3', 306.00, 306.00, 3.00, 'completado', '2026-01-26 04:57:30', '2026-01-28 03:09:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_id` bigint(20) UNSIGNED NOT NULL,
  `variante_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalles`
--

INSERT INTO `pedido_detalles` (`id`, `pedido_id`, `variante_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 2, 76.50),
(2, 1, 2, 4, 76.50),
(3, 2, 3, 5, 76.50),
(4, 3, 3, 3, 76.50),
(5, 3, 2, 1, 76.50),
(6, 4, 1, 1, 76.50),
(7, 4, 2, 1, 76.50),
(8, 4, 3, 2, 76.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_pagos`
--

CREATE TABLE `pedido_pagos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_id` bigint(20) UNSIGNED NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `comprobante_ruta` varchar(255) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_pagos`
--

INSERT INTO `pedido_pagos` (`id`, `pedido_id`, `monto`, `comprobante_ruta`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 2, 95.99, 'vauchers/CFiodMsacOE07BmXYEyjZF0VlpkbXkwWfHYit9Mb.png', 'aprobado', NULL, '2026-01-14 02:33:24', '2026-01-28 03:13:04'),
(2, 2, 400.00, 'vauchers/c721FpmOgzAMLDyhpaLAeiQTzq9qsoumJXo2UCjc.png', 'rechazado', NULL, '2026-01-14 02:38:35', '2026-01-28 03:12:57'),
(3, 3, 98.50, 'vauchers/baNh7QREa6AQhgm8YMYRhjUi5gBjYUWO7BdutERn.png', 'aprobado', NULL, '2026-01-14 03:38:47', '2026-01-28 03:12:22'),
(8, 4, 300.00, 'vauchers/w9WhErHxvyMD4xlpiPbODBqZb3eZ0wBvd3hLdWwF.png', 'aprobado', NULL, '2026-01-28 03:08:14', '2026-01-28 03:08:28'),
(9, 4, 9.00, 'vauchers/kWajwWRgaAfX5Pg1Sccl6G9oNBwpCM6gLJtsysfR.png', 'aprobado', NULL, '2026-01-28 03:09:11', '2026-01-28 03:09:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `nombre`, `slug`, `descripcion`, `module_id`, `created_at`, `updated_at`) VALUES
(1, 'Ver listado de usuarios', 'usuarios.index', 'Permite ver listado de usuarios', 1, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(2, 'Ver detalles de usuario', 'usuarios.show', 'Permite ver detalles de usuario', 1, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(3, 'Crear usuarios', 'usuarios.create', 'Permite crear usuarios', 1, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(4, 'Editar usuarios', 'usuarios.edit', 'Permite editar usuarios', 1, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(5, 'Eliminar usuarios', 'usuarios.delete', 'Permite eliminar usuarios', 1, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(6, 'Ver listado de roles', 'roles.index', 'Permite ver listado de roles', 2, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(7, 'Ver detalles de rol', 'roles.show', 'Permite ver detalles de rol', 2, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(8, 'Crear roles', 'roles.create', 'Permite crear roles', 2, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(9, 'Editar roles', 'roles.edit', 'Permite editar roles', 2, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(10, 'Eliminar roles', 'roles.delete', 'Permite eliminar roles', 2, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(11, 'Ver dashboard', 'inicio.view', 'Permite ver dashboard', 3, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(12, 'Ver módulos', 'modulos.index', 'Permite ver módulos', 3, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(13, 'Configurar sistema', 'sistema.configuracion', 'Permite configurar sistema', 3, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(14, 'Ver logs del sistema', 'sistema.logs', 'Permite ver logs del sistema', 3, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(15, 'Realizar backups', 'sistema.backup', 'Permite realizar backups', 3, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(16, 'Ver listado de carrusel', 'carrusel.index', 'Permite ver listado de carrusel', 4, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(17, 'Ver detalles del carrusel', 'carrusel.show', 'Permite ver detalles del carrusel', 4, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(18, 'Crear elementos del carrusel', 'carrusel.create', 'Permite crear elementos del carrusel', 4, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(19, 'Editar elementos del carrusel', 'carrusel.edit', 'Permite editar elementos del carrusel', 4, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(20, 'Eliminar elementos del carrusel', 'carrusel.delete', 'Permite eliminar elementos del carrusel', 4, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(21, 'Cambiar estado del carrusel', 'carrusel.estado', 'Permite cambiar estado del carrusel', 4, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(22, 'Ver listado de categorías', 'categorias.index', 'Permite ver listado de categorías', 5, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(23, 'Ver detalles de categoría', 'categorias.show', 'Permite ver detalles de categoría', 5, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(24, 'Crear categorías', 'categorias.create', 'Permite crear categorías', 5, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(25, 'Editar categorías', 'categorias.edit', 'Permite editar categorías', 5, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(26, 'Eliminar categorías', 'categorias.delete', 'Permite eliminar categorías', 5, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(27, 'Cambiar estado de categorías', 'categorias.estado', 'Permite cambiar estado de categorías', 5, '2025-11-25 03:35:31', '2025-11-25 03:35:31'),
(28, 'Ver listado de marcas', 'marcas.index', 'Permite ver listado de marcas', 6, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(29, 'Ver detalles de marca', 'marcas.show', 'Permite ver detalles de marca', 6, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(30, 'Crear marcas', 'marcas.create', 'Permite crear marcas', 6, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(31, 'Editar marcas', 'marcas.edit', 'Permite editar marcas', 6, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(32, 'Eliminar marcas', 'marcas.delete', 'Permite eliminar marcas', 6, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(33, 'Cambiar estado de marcas', 'marcas.estado', 'Permite cambiar estado de marcas', 6, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(34, 'Ver listado de colores', 'colores.index', 'Permite ver listado de colores', 7, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(35, 'Ver detalles de color', 'colores.show', 'Permite ver detalles de color', 7, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(36, 'Crear colores', 'colores.create', 'Permite crear colores', 7, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(37, 'Editar colores', 'colores.edit', 'Permite editar colores', 7, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(38, 'Eliminar colores', 'colores.delete', 'Permite eliminar colores', 7, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(39, 'Cambiar estado de colores', 'colores.estado', 'Permite cambiar estado de colores', 7, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(40, 'Ver listado de tallas', 'tallas.index', 'Permite ver listado de tallas', 8, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(41, 'Ver detalles de talla', 'tallas.show', 'Permite ver detalles de talla', 8, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(42, 'Crear tallas', 'tallas.create', 'Permite crear tallas', 8, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(43, 'Editar tallas', 'tallas.edit', 'Permite editar tallas', 8, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(44, 'Eliminar tallas', 'tallas.delete', 'Permite eliminar tallas', 8, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(45, 'Cambiar estado de tallas', 'tallas.estado', 'Permite cambiar estado de tallas', 8, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(46, 'Ver listado de medidas', 'medidas.index', 'Permite ver listado de medidas', 9, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(47, 'Ver detalles de medida', 'medidas.show', 'Permite ver detalles de medida', 9, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(48, 'Crear medidas', 'medidas.create', 'Permite crear medidas', 9, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(49, 'Editar medidas', 'medidas.edit', 'Permite editar medidas', 9, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(50, 'Eliminar medidas', 'medidas.delete', 'Permite eliminar medidas', 9, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(51, 'Cambiar estado de medidas', 'medidas.estado', 'Permite cambiar estado de medidas', 9, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(52, 'Ver listado de sabores', 'sabores.index', 'Permite ver listado de sabores', 10, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(53, 'Ver detalles de sabor', 'sabores.show', 'Permite ver detalles de sabor', 10, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(54, 'Crear sabores', 'sabores.create', 'Permite crear sabores', 10, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(55, 'Editar sabores', 'sabores.edit', 'Permite editar sabores', 10, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(56, 'Eliminar sabores', 'sabores.delete', 'Permite eliminar sabores', 10, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(57, 'Cambiar estado de sabores', 'sabores.estado', 'Permite cambiar estado de sabores', 10, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(58, 'Ver listado de modelos', 'modelos.index', 'Permite ver listado de modelos', 11, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(59, 'Ver detalles de modelo', 'modelos.show', 'Permite ver detalles de modelo', 11, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(60, 'Crear modelos', 'modelos.create', 'Permite crear modelos', 11, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(61, 'Editar modelos', 'modelos.edit', 'Permite editar modelos', 11, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(62, 'Eliminar modelos', 'modelos.delete', 'Permite eliminar modelos', 11, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(63, 'Cambiar estado de modelos', 'modelos.estado', 'Permite cambiar estado de modelos', 11, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(64, 'Ver listado de tonos', 'tonos.index', 'Permite ver listado de tonos', 12, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(65, 'Ver detalles de tono', 'tonos.show', 'Permite ver detalles de tono', 12, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(66, 'Crear tonos', 'tonos.create', 'Permite crear tonos', 12, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(67, 'Editar tonos', 'tonos.edit', 'Permite editar tonos', 12, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(68, 'Eliminar tonos', 'tonos.delete', 'Permite eliminar tonos', 12, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(69, 'Cambiar estado de tonos', 'tonos.estado', 'Permite cambiar estado de tonos', 12, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(70, 'Ver listado de artículos', 'articulos.index', 'Permite ver listado de artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(71, 'Ver detalles de artículo', 'articulos.show', 'Permite ver detalles de artículo', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(72, 'Crear artículos', 'articulos.create', 'Permite crear artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(73, 'Editar artículos', 'articulos.edit', 'Permite editar artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(74, 'Eliminar artículos', 'articulos.delete', 'Permite eliminar artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(75, 'Cambiar estado de artículos', 'articulos.estado', 'Permite cambiar estado de artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(76, 'Crear variantes de artículos', 'articulos.crear_variantes', 'Permite crear variantes de artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(77, 'Editar variantes de artículos', 'articulos.editar_variantes', 'Permite editar variantes de artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(78, 'Eliminar variantes de artículos', 'articulos.eliminar_variantes', 'Permite eliminar variantes de artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(79, 'Ver información de stock', 'articulos.ver_stock', 'Permite ver información de stock', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(80, 'Reservar stock de artículos', 'articulos.reservar_stock', 'Permite reservar stock de artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(81, 'Liberar stock reservado', 'articulos.liberar_stock', 'Permite liberar stock reservado', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(82, 'Decrementar stock de artículos', 'articulos.decrementar_stock', 'Permite decrementar stock de artículos', 13, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(83, 'Ver listado de plazas', 'plazas.index', 'Permite ver listado de plazas', 14, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(84, 'Ver detalles de plaza', 'plazas.show', 'Permite ver detalles de plaza', 14, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(85, 'Crear plazas', 'plazas.create', 'Permite crear plazas', 14, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(86, 'Editar plazas', 'plazas.edit', 'Permite editar plazas', 14, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(87, 'Eliminar plazas', 'plazas.delete', 'Permite eliminar plazas', 14, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(88, 'Cambiar estado de plazas', 'plazas.estado', 'Permite cambiar estado de plazas', 14, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(89, 'Ver sección Nosotros', 'view nosotros', 'Permite ver la información de la sección Nosotros', 15, '2025-11-25 03:35:32', '2025-11-25 03:35:32'),
(90, 'Administrar sección Nosotros', 'manage nosotros', 'Permite crear o actualizar la información de la sección Nosotros', 15, '2025-11-25 03:35:32', '2025-11-25 03:35:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `permission_role`
--

INSERT INTO `permission_role` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 5, NULL, NULL),
(6, 1, 6, NULL, NULL),
(7, 1, 7, NULL, NULL),
(8, 1, 8, NULL, NULL),
(9, 1, 9, NULL, NULL),
(10, 1, 10, NULL, NULL),
(11, 1, 11, NULL, NULL),
(12, 1, 12, NULL, NULL),
(13, 1, 13, NULL, NULL),
(14, 1, 14, NULL, NULL),
(15, 1, 15, NULL, NULL),
(16, 1, 16, NULL, NULL),
(17, 1, 17, NULL, NULL),
(18, 1, 18, NULL, NULL),
(19, 1, 19, NULL, NULL),
(20, 1, 20, NULL, NULL),
(21, 1, 21, NULL, NULL),
(22, 1, 22, NULL, NULL),
(23, 1, 23, NULL, NULL),
(24, 1, 24, NULL, NULL),
(25, 1, 25, NULL, NULL),
(26, 1, 26, NULL, NULL),
(27, 1, 27, NULL, NULL),
(28, 1, 28, NULL, NULL),
(29, 1, 29, NULL, NULL),
(30, 1, 30, NULL, NULL),
(31, 1, 31, NULL, NULL),
(32, 1, 32, NULL, NULL),
(33, 1, 33, NULL, NULL),
(34, 1, 34, NULL, NULL),
(35, 1, 35, NULL, NULL),
(36, 1, 36, NULL, NULL),
(37, 1, 37, NULL, NULL),
(38, 1, 38, NULL, NULL),
(39, 1, 39, NULL, NULL),
(40, 1, 40, NULL, NULL),
(41, 1, 41, NULL, NULL),
(42, 1, 42, NULL, NULL),
(43, 1, 43, NULL, NULL),
(44, 1, 44, NULL, NULL),
(45, 1, 45, NULL, NULL),
(46, 1, 46, NULL, NULL),
(47, 1, 47, NULL, NULL),
(48, 1, 48, NULL, NULL),
(49, 1, 49, NULL, NULL),
(50, 1, 50, NULL, NULL),
(51, 1, 51, NULL, NULL),
(52, 1, 52, NULL, NULL),
(53, 1, 53, NULL, NULL),
(54, 1, 54, NULL, NULL),
(55, 1, 55, NULL, NULL),
(56, 1, 56, NULL, NULL),
(57, 1, 57, NULL, NULL),
(58, 1, 58, NULL, NULL),
(59, 1, 59, NULL, NULL),
(60, 1, 60, NULL, NULL),
(61, 1, 61, NULL, NULL),
(62, 1, 62, NULL, NULL),
(63, 1, 63, NULL, NULL),
(64, 1, 64, NULL, NULL),
(65, 1, 65, NULL, NULL),
(66, 1, 66, NULL, NULL),
(67, 1, 67, NULL, NULL),
(68, 1, 68, NULL, NULL),
(69, 1, 69, NULL, NULL),
(70, 1, 70, NULL, NULL),
(71, 1, 71, NULL, NULL),
(72, 1, 72, NULL, NULL),
(73, 1, 73, NULL, NULL),
(74, 1, 74, NULL, NULL),
(75, 1, 75, NULL, NULL),
(76, 1, 76, NULL, NULL),
(77, 1, 77, NULL, NULL),
(78, 1, 78, NULL, NULL),
(79, 1, 79, NULL, NULL),
(80, 1, 80, NULL, NULL),
(81, 1, 81, NULL, NULL),
(82, 1, 82, NULL, NULL),
(83, 1, 83, NULL, NULL),
(84, 1, 84, NULL, NULL),
(85, 1, 85, NULL, NULL),
(86, 1, 86, NULL, NULL),
(87, 1, 87, NULL, NULL),
(88, 1, 88, NULL, NULL),
(89, 1, 90, NULL, NULL),
(90, 1, 89, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plazas`
--

CREATE TABLE `plazas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `plazas`
--

INSERT INTO `plazas` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Plaza Central', 1, '2026-01-28 22:15:49', '2026-01-28 22:15:49'),
(2, 'Plaza Norte', 1, '2026-01-28 22:15:49', '2026-01-28 22:15:49'),
(3, 'Plaza Sur', 1, '2026-01-28 22:15:49', '2026-01-28 22:15:49'),
(4, 'Plaza Este', 1, '2026-01-28 22:15:49', '2026-01-28 22:15:49'),
(5, 'Plaza Oeste', 1, '2026-01-28 22:15:49', '2026-01-28 22:15:49'),
(7, 'Zona Comercial', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(8, 'Distrito Financiero', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(9, 'Area Residencial', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(10, 'Centro Histórico', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(11, 'Centro', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(12, 'Norte', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(13, 'Sur', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(14, 'Este', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(15, 'Oeste', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54'),
(16, 'Plaza Mayor', 1, '2026-01-28 22:19:54', '2026-01-28 22:19:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `point_settings`
--

CREATE TABLE `point_settings` (
  `id` int(11) NOT NULL,
  `evento` varchar(50) DEFAULT NULL,
  `valor_puntos` int(11) DEFAULT NULL,
  `es_porcentaje` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `point_transactions`
--

CREATE TABLE `point_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `point_transactions`
--

INSERT INTO `point_transactions` (`id`, `user_id`, `cantidad`, `motivo`, `reference_type`, `reference_id`, `created_at`, `updated_at`) VALUES
(2, 3, 10, 'Bono de Bienvenida', NULL, NULL, '2026-01-08 02:32:28', '2026-01-08 02:32:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrador', 'Acceso completo al sistema', '2025-11-25 03:35:31', '2025-11-25 03:35:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sabores`
--

CREATE TABLE `sabores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `sabores`
--

INSERT INTO `sabores` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Chocolate', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(2, 'Vainilla', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(3, 'Fresa', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(4, 'Limón', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(5, 'Menta', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(6, 'Café', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(7, 'Caramelo', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(8, 'Naranja', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(9, 'Mango', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59'),
(10, 'Piña', 1, '2026-01-28 22:18:59', '2026-01-28 22:18:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'S', NULL, 1, NULL, NULL),
(2, 'M', NULL, 1, NULL, NULL),
(3, 'L', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tonos`
--

CREATE TABLE `tonos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tonos`
--

INSERT INTO `tonos` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'test', 1, '2025-12-23 20:45:56', '2025-12-23 20:45:56'),
(2, 'hola', 1, '2025-12-23 20:46:16', '2025-12-23 20:46:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transportes`
--

CREATE TABLE `transportes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ruta` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cooperativa` varchar(100) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
  `tiempo_estimado` decimal(5,2) DEFAULT NULL COMMENT 'Tiempo estimado en horas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transportes`
--

INSERT INTO `transportes` (`id`, `ruta`, `precio`, `cooperativa`, `estado`, `tiempo_estimado`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SAN CARLOS', 2.50, 'ESPEJO', 1, NULL, '2026-01-25 21:51:37', '2026-01-25 21:51:37', NULL),
(2, 'VENTANAS', 3.00, 'ESPEJO', 1, NULL, '2026-01-25 21:51:37', '2026-01-25 21:51:37', NULL),
(3, 'BABAHOYO', 4.00, 'QUEVEDO', 1, NULL, '2026-01-25 21:51:37', '2026-01-25 21:51:37', NULL),
(4, 'GUAYAQUIL', 5.00, 'TIA', 1, NULL, '2026-01-25 21:51:37', '2026-01-25 21:51:37', NULL),
(5, 'MANTA', 6.00, 'BOLIVAR', 1, NULL, '2026-01-25 21:51:37', '2026-01-25 21:51:37', NULL),
(6, 'QUITO', 1.50, 'ESPEJO', 1, 0.50, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(7, 'CUENCA', 6.50, 'TIA', 1, 8.00, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(8, 'MACHALA', 7.00, 'BOLIVAR', 1, 10.50, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(9, 'IBARRA', 3.50, 'ESPEJO', 1, 3.00, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(10, 'LOJA', 8.00, 'TIA', 1, 12.00, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(11, 'AMBATO', 2.00, 'ESPEJO', 1, 2.00, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(12, 'RIOBAMBA', 2.50, 'ESPEJO', 1, 2.50, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(13, 'LATACUNGA', 1.80, 'QUEVEDO', 1, 1.50, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(14, 'TULCÁN', 4.50, 'ESPEJO', 1, 4.00, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(15, 'ESMERALDAS', 5.50, 'BOLIVAR', 1, 7.00, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(16, 'SALINAS', 8.50, 'TIA', 1, 12.50, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(17, 'MANTA - PORTOVIEJO', 1.20, 'QUEVEDO', 1, 1.00, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(18, 'GUAYAQUIL - DAULE', 0.80, 'TIA', 1, 0.75, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(19, 'QUITO - SANTO DOMINGO', 3.00, 'ESPEJO', 1, 3.50, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(20, 'CUENCA - AZOGUES', 0.50, 'QUEVEDO', 1, 0.50, '2026-01-25 21:52:06', '2026-01-25 21:52:06', NULL),
(21, 'ruta de prueba .com', 5.00, 'Latacunga', 1, 10.00, '2026-01-26 04:32:04', '2026-01-26 04:32:04', NULL),
(22, 'ruta de prueba .', 5.00, 'Latacunga', 1, 10.00, '2026-01-26 04:32:37', '2026-01-26 04:32:37', NULL),
(23, 'ruta de prueba', 5.00, 'Latacunga', 1, 10.00, '2026-01-26 04:34:23', '2026-01-26 04:34:23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero_documento` varchar(255) NOT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `codigo_referido` varchar(8) DEFAULT NULL,
  `referido_por_codigo` varchar(8) DEFAULT NULL,
  `puntos_acumulados` int(11) DEFAULT 0,
  `fallos_reserva` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `numero_documento`, `tipo_documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `codigo_referido`, `referido_por_codigo`, `puntos_acumulados`, `fallos_reserva`, `active`) VALUES
(1, '99999999', NULL, 'Estilo', 'Store', NULL, 'Estilo Store', 'super_admin@admin.com', '2025-11-25 03:35:32', '$2y$10$vmpmGe.nD66VwDn635JnBe3tz8ounPHVTTCJj1wh41F/hn.0Yw.QW', NULL, '2025-11-25 03:35:32', '2025-11-25 03:35:32', 1, NULL, NULL, 0, 0, 1),
(3, '1721543526', 'CEDULA', 'Diego NZ', 'asdasd', NULL, 'Diego NZ asdasd', 'correo@correo.com', NULL, '$2y$10$7bqTqDGjSCFsw5d.k7aSw..oJCb95I3Ov9CZozh3UOlwQaFN7BN/u', NULL, '2026-01-08 02:32:28', '2026-01-08 02:32:28', NULL, '925DWUDU', NULL, 10, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provincia` varchar(255) NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `direccion_exacta` text NOT NULL,
  `referencia` text DEFAULT NULL,
  `principal` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `articulos_slug_unique` (`slug`) USING BTREE,
  ADD UNIQUE KEY `articulos_sku_unique` (`sku`) USING BTREE,
  ADD KEY `articulos_categoria_id_index` (`categoria_id`) USING BTREE,
  ADD KEY `articulos_marca_id_index` (`marca_id`) USING BTREE,
  ADD KEY `articulos_slug_activo_index` (`slug`,`activo`) USING BTREE,
  ADD KEY `articulos_activo_index` (`activo`) USING BTREE,
  ADD KEY `articulos_destacado_index` (`destacado`) USING BTREE;

--
-- Indices de la tabla `articulo_color`
--
ALTER TABLE `articulo_color`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `articulo_color_articulo_id_color_id_unique` (`articulo_id`,`color_id`) USING BTREE,
  ADD KEY `articulo_color_color_id_foreign` (`color_id`) USING BTREE,
  ADD KEY `articulo_color_articulo_id_index` (`articulo_id`) USING BTREE;

--
-- Indices de la tabla `articulo_imagenes`
--
ALTER TABLE `articulo_imagenes`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `articulo_imagenes_articulo_id_index` (`articulo_id`) USING BTREE,
  ADD KEY `articulo_imagenes_orden_index` (`orden`) USING BTREE;

--
-- Indices de la tabla `articulo_medida`
--
ALTER TABLE `articulo_medida`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `articulo_medida_articulo_id_medida_id_unique` (`articulo_id`,`medida_id`) USING BTREE,
  ADD KEY `articulo_medida_medida_id_foreign` (`medida_id`) USING BTREE,
  ADD KEY `articulo_medida_articulo_id_index` (`articulo_id`) USING BTREE;

--
-- Indices de la tabla `articulo_modelo`
--
ALTER TABLE `articulo_modelo`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `articulo_modelo_articulo_id_modelo_id_unique` (`articulo_id`,`modelo_id`) USING BTREE,
  ADD KEY `articulo_modelo_modelo_id_foreign` (`modelo_id`) USING BTREE,
  ADD KEY `articulo_modelo_articulo_id_index` (`articulo_id`) USING BTREE;

--
-- Indices de la tabla `articulo_plaza`
--
ALTER TABLE `articulo_plaza`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `articulo_plaza_articulo_id_plaza_id_unique` (`articulo_id`,`plaza_id`) USING BTREE,
  ADD KEY `articulo_plaza_plaza_id_foreign` (`plaza_id`) USING BTREE,
  ADD KEY `articulo_plaza_articulo_id_index` (`articulo_id`) USING BTREE;

--
-- Indices de la tabla `articulo_sabor`
--
ALTER TABLE `articulo_sabor`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `articulo_sabor_articulo_id_sabor_id_unique` (`articulo_id`,`sabor_id`) USING BTREE,
  ADD KEY `articulo_sabor_sabor_id_foreign` (`sabor_id`) USING BTREE,
  ADD KEY `articulo_sabor_articulo_id_index` (`articulo_id`) USING BTREE;

--
-- Indices de la tabla `articulo_talla`
--
ALTER TABLE `articulo_talla`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `articulo_talla_articulo_id_talla_id_unique` (`articulo_id`,`talla_id`) USING BTREE,
  ADD KEY `articulo_talla_talla_id_foreign` (`talla_id`) USING BTREE,
  ADD KEY `articulo_talla_articulo_id_index` (`articulo_id`) USING BTREE;

--
-- Indices de la tabla `articulo_tono`
--
ALTER TABLE `articulo_tono`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `articulo_tono_articulo_id_tono_id_unique` (`articulo_id`,`tono_id`) USING BTREE,
  ADD KEY `articulo_tono_tono_id_foreign` (`tono_id`) USING BTREE,
  ADD KEY `articulo_tono_articulo_id_index` (`articulo_id`) USING BTREE;

--
-- Indices de la tabla `articulo_variantes`
--
ALTER TABLE `articulo_variantes`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `articulo_variantes_articulo_id_index` (`articulo_id`) USING BTREE,
  ADD KEY `articulo_variantes_articulo_id_activo_index` (`articulo_id`,`activo`) USING BTREE,
  ADD KEY `idx_variante_color` (`color_id`),
  ADD KEY `idx_variante_talla` (`talla_id`);

--
-- Indices de la tabla `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `seccion` (`seccion`);

--
-- Indices de la tabla `carrusel`
--
ALTER TABLE `carrusel`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `categorias_slug_unique` (`slug`) USING BTREE,
  ADD KEY `categorias_parent_id_foreign` (`parent_id`) USING BTREE;

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `empresa_datos`
--
ALTER TABLE `empresa_datos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE;

--
-- Indices de la tabla `folletos`
--
ALTER TABLE `folletos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `header_principal_items`
--
ALTER TABLE `header_principal_items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `legal_documents`
--
ALTER TABLE `legal_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `marcas_slug_unique` (`slug`) USING BTREE;

--
-- Indices de la tabla `medidas`
--
ALTER TABLE `medidas`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `modules_nombre_unique` (`nombre`) USING BTREE,
  ADD UNIQUE KEY `modules_slug_unique` (`slug`) USING BTREE;

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nosotros`
--
ALTER TABLE `nosotros`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_variante` (`articulo_variante_id`);

--
-- Indices de la tabla `order_payments`
--
ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`) USING BTREE;

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_reserva` (`codigo_reserva`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_pedidos_transporte` (`transporte_id`);

--
-- Indices de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `variante_id` (`variante_id`);

--
-- Indices de la tabla `pedido_pagos`
--
ALTER TABLE `pedido_pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`) USING BTREE,
  ADD KEY `permissions_module_id_index` (`module_id`) USING BTREE;

--
-- Indices de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `permission_role_role_id_permission_id_unique` (`role_id`,`permission_id`) USING BTREE,
  ADD KEY `permission_role_role_id_index` (`role_id`) USING BTREE,
  ADD KEY `permission_role_permission_id_index` (`permission_id`) USING BTREE;

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`) USING BTREE,
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`) USING BTREE;

--
-- Indices de la tabla `plazas`
--
ALTER TABLE `plazas`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `point_settings`
--
ALTER TABLE `point_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `point_transactions`
--
ALTER TABLE `point_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `roles_nombre_unique` (`nombre`) USING BTREE;

--
-- Indices de la tabla `sabores`
--
ALTER TABLE `sabores`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tonos`
--
ALTER TABLE `tonos`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `transportes`
--
ALTER TABLE `transportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ruta` (`ruta`),
  ADD KEY `idx_cooperativa` (`cooperativa`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_precio` (`precio`),
  ADD KEY `idx_ruta_cooperativa` (`ruta`,`cooperativa`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `users_numero_documento_unique` (`numero_documento`) USING BTREE,
  ADD UNIQUE KEY `users_email_unique` (`email`) USING BTREE,
  ADD UNIQUE KEY `codigo_referido` (`codigo_referido`),
  ADD KEY `users_role_id_foreign` (`role_id`) USING BTREE;

--
-- Indices de la tabla `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_address` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `articulo_color`
--
ALTER TABLE `articulo_color`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articulo_imagenes`
--
ALTER TABLE `articulo_imagenes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `articulo_medida`
--
ALTER TABLE `articulo_medida`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articulo_modelo`
--
ALTER TABLE `articulo_modelo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articulo_plaza`
--
ALTER TABLE `articulo_plaza`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articulo_sabor`
--
ALTER TABLE `articulo_sabor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articulo_talla`
--
ALTER TABLE `articulo_talla`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articulo_tono`
--
ALTER TABLE `articulo_tono`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articulo_variantes`
--
ALTER TABLE `articulo_variantes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `carrusel`
--
ALTER TABLE `carrusel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `empresa_datos`
--
ALTER TABLE `empresa_datos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `folletos`
--
ALTER TABLE `folletos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `header_principal_items`
--
ALTER TABLE `header_principal_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `legal_documents`
--
ALTER TABLE `legal_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `medidas`
--
ALTER TABLE `medidas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `modelos`
--
ALTER TABLE `modelos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `nosotros`
--
ALTER TABLE `nosotros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order_payments`
--
ALTER TABLE `order_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedido_pagos`
--
ALTER TABLE `pedido_pagos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plazas`
--
ALTER TABLE `plazas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `point_settings`
--
ALTER TABLE `point_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `point_transactions`
--
ALTER TABLE `point_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sabores`
--
ALTER TABLE `sabores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tonos`
--
ALTER TABLE `tonos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `transportes`
--
ALTER TABLE `transportes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD CONSTRAINT `articulos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulos_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_color`
--
ALTER TABLE `articulo_color`
  ADD CONSTRAINT `articulo_color_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulo_color_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_imagenes`
--
ALTER TABLE `articulo_imagenes`
  ADD CONSTRAINT `articulo_imagenes_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_medida`
--
ALTER TABLE `articulo_medida`
  ADD CONSTRAINT `articulo_medida_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulo_medida_medida_id_foreign` FOREIGN KEY (`medida_id`) REFERENCES `medidas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_modelo`
--
ALTER TABLE `articulo_modelo`
  ADD CONSTRAINT `articulo_modelo_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulo_modelo_modelo_id_foreign` FOREIGN KEY (`modelo_id`) REFERENCES `modelos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_plaza`
--
ALTER TABLE `articulo_plaza`
  ADD CONSTRAINT `articulo_plaza_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulo_plaza_plaza_id_foreign` FOREIGN KEY (`plaza_id`) REFERENCES `plazas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_sabor`
--
ALTER TABLE `articulo_sabor`
  ADD CONSTRAINT `articulo_sabor_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulo_sabor_sabor_id_foreign` FOREIGN KEY (`sabor_id`) REFERENCES `sabores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_talla`
--
ALTER TABLE `articulo_talla`
  ADD CONSTRAINT `articulo_talla_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulo_talla_talla_id_foreign` FOREIGN KEY (`talla_id`) REFERENCES `tallas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_tono`
--
ALTER TABLE `articulo_tono`
  ADD CONSTRAINT `articulo_tono_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articulo_tono_tono_id_foreign` FOREIGN KEY (`tono_id`) REFERENCES `tonos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `articulo_variantes`
--
ALTER TABLE `articulo_variantes`
  ADD CONSTRAINT `articulo_variantes_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_variantes_color` FOREIGN KEY (`color_id`) REFERENCES `colores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_variantes_talla` FOREIGN KEY (`talla_id`) REFERENCES `tallas` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
