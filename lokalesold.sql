-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 30, 2024 at 12:03 PM
-- Server version: 10.6.18-MariaDB-cll-lve
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lokalesold`
--

-- --------------------------------------------------------

--
-- Table structure for table `actividad`
--

CREATE TABLE `actividad` (
  `id_actividad` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` mediumtext NOT NULL,
  `imagen` varchar(45) NOT NULL,
  `activa` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actividad_asistencia`
--

CREATE TABLE `actividad_asistencia` (
  `actividad` int(11) NOT NULL,
  `dni` int(8) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actividad_dias`
--

CREATE TABLE `actividad_dias` (
  `id_dia` int(11) NOT NULL,
  `nombre_dia` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actividad_horarios`
--

CREATE TABLE `actividad_horarios` (
  `id_horario` int(11) NOT NULL,
  `actividad_id_actividad` int(11) NOT NULL,
  `actividad_dias_id_dia` int(11) NOT NULL,
  `hora` time DEFAULT NULL,
  `costo` int(11) DEFAULT NULL,
  `cupo` int(11) DEFAULT NULL,
  `desc_especifica` mediumtext DEFAULT NULL,
  `activo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actividad_horarios_susp`
--

CREATE TABLE `actividad_horarios_susp` (
  `actividad_horarios_id_horario2` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actividad_reservas`
--

CREATE TABLE `actividad_reservas` (
  `id_reserva` int(11) NOT NULL,
  `registrados_dni` int(8) NOT NULL,
  `fecha` date DEFAULT NULL,
  `actividad_horarios_id_horario` int(11) NOT NULL,
  `dni_invitador` int(8) NOT NULL,
  `asiste` int(1) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actividad_reservas_asist`
--

CREATE TABLE `actividad_reservas_asist` (
  `registrados_dni` int(8) NOT NULL,
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `actividad_horarios_id_horario` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actividad_reserva_auto`
--

CREATE TABLE `actividad_reserva_auto` (
  `registrados_dni` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auditoria`
--

CREATE TABLE `auditoria` (
  `cod` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `usu` int(11) NOT NULL,
  `men` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comentario` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caja`
--

CREATE TABLE `caja` (
  `billetes` int(11) NOT NULL,
  `monedas` double DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `cerrada` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caja_aporte`
--

CREATE TABLE `caja_aporte` (
  `billetes` int(11) NOT NULL,
  `monedas` double NOT NULL,
  `concepto` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caja_cierre`
--

CREATE TABLE `caja_cierre` (
  `billetes` int(11) NOT NULL,
  `monedas` double DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_cierre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caja_extraccion`
--

CREATE TABLE `caja_extraccion` (
  `billetes` int(11) NOT NULL,
  `monedas` double NOT NULL,
  `concepto` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `comentarios` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caja_extraccion_concepto`
--

CREATE TABLE `caja_extraccion_concepto` (
  `id_concepto` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `cod` int(11) NOT NULL,
  `foto_generica` varchar(100) NOT NULL DEFAULT '',
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `cod_padre` int(45) NOT NULL,
  `margen` double DEFAULT NULL,
  `activa` varchar(1) NOT NULL,
  `calcula_stock` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `compra_detalle`
--

CREATE TABLE `compra_detalle` (
  `id_compra` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `compra_temporal`
--

CREATE TABLE `compra_temporal` (
  `cod_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultas`
--

CREATE TABLE `consultas` (
  `numero` int(11) NOT NULL,
  `consulta` longtext DEFAULT NULL,
  `fecha` date NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `respuesta` longtext NOT NULL,
  `id_mail` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacto`
--

CREATE TABLE `contacto` (
  `codigo` int(30) NOT NULL,
  `empresa` varchar(30) NOT NULL,
  `actividad` varchar(30) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `comentario` longtext DEFAULT NULL,
  `fecha` date NOT NULL,
  `respuesta` longtext NOT NULL,
  `id_mail` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foro`
--

CREATE TABLE `foro` (
  `comentario` varchar(100) NOT NULL DEFAULT '',
  `id_comentario` int(11) NOT NULL,
  `mail` varchar(40) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gymroom`
--

CREATE TABLE `gymroom` (
  `floor` int(10) DEFAULT NULL,
  `wall` int(10) DEFAULT NULL,
  `ramp` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indicadores`
--

CREATE TABLE `indicadores` (
  `fecha` date NOT NULL,
  `ventas_cantidad` int(11) NOT NULL,
  `ventas_efectivo` int(11) NOT NULL,
  `puntos_activos` int(11) NOT NULL,
  `clientes_activos` int(11) NOT NULL,
  `visits` int(11) NOT NULL,
  `l_act` int(11) NOT NULL,
  `l_mail` int(11) NOT NULL,
  `l_fb` int(11) NOT NULL,
  `l_ig` int(11) NOT NULL,
  `prospects` int(11) NOT NULL,
  `conversion` int(11) NOT NULL,
  `retencion` int(11) NOT NULL,
  `puntos_promedio` int(11) NOT NULL,
  `gasto_promedio` int(11) NOT NULL,
  `reservas` int(11) NOT NULL,
  `asistencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indicadores_clientes_temp`
--

CREATE TABLE `indicadores_clientes_temp` (
  `dni` int(11) NOT NULL,
  `nombre` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `compras` int(11) NOT NULL,
  `ingreso` double NOT NULL,
  `reservas` int(11) NOT NULL,
  `asistencias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indicador_botones`
--

CREATE TABLE `indicador_botones` (
  `cod` int(11) NOT NULL,
  `nombre` varchar(10) NOT NULL,
  `clicks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inscripcion_eventos`
--

CREATE TABLE `inscripcion_eventos` (
  `cod` int(11) NOT NULL,
  `nombre` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `apellido` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mail` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `dni` int(10) NOT NULL,
  `celular` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `edad` int(4) NOT NULL,
  `ciudad` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `provincia` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `categoria` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comentario` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inscripcion_eventos_ackitecontest`
--

CREATE TABLE `inscripcion_eventos_ackitecontest` (
  `cod` int(11) NOT NULL,
  `nombre` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `apellido` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_mail` int(11) NOT NULL,
  `celular` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `edad` int(4) NOT NULL,
  `ciudad` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `provincia` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `estilo` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `categoria` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comentario` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inscripcion_eventos_undiadewake`
--

CREATE TABLE `inscripcion_eventos_undiadewake` (
  `cod` int(11) NOT NULL,
  `nombre` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `apellido` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mail` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `celular` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `edad` int(4) NOT NULL,
  `ciudad` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `provincia` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `categoria` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `comentario` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mails`
--

CREATE TABLE `mails` (
  `mail` varchar(40) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marcas`
--

CREATE TABLE `marcas` (
  `id_marca` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `logo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `mensaje` longtext DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `confirmado` varchar(1) NOT NULL,
  `id_usuario_rem` int(11) NOT NULL,
  `id_dest` int(11) NOT NULL,
  `id_conversacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes_conversaciones`
--

CREATE TABLE `mensajes_conversaciones` (
  `id_conversacion` int(11) NOT NULL,
  `fecha_ultimo_msj` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes_internos`
--

CREATE TABLE `mensajes_internos` (
  `id_mensaje` int(11) NOT NULL,
  `mensaje` longtext DEFAULT NULL,
  `fecha` date NOT NULL,
  `confirmado` varchar(1) NOT NULL,
  `id_usuario_rem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes_internos_destinatarios`
--

CREATE TABLE `mensajes_internos_destinatarios` (
  `id_usuario` int(11) NOT NULL,
  `id_mensaje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `nro_ped` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `confirmado` varchar(1) NOT NULL,
  `enviado` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos_detalle`
--

CREATE TABLE `pedidos_detalle` (
  `nro_det` int(11) NOT NULL,
  `nro_ped` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `cod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `cod` int(11) NOT NULL,
  `descripcion` varchar(300) NOT NULL,
  `costo` double NOT NULL,
  `foto` varchar(100) NOT NULL DEFAULT '',
  `cod_cat` varchar(100) NOT NULL DEFAULT '',
  `id_marca` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `clicks` int(11) NOT NULL,
  `cantp` int(11) NOT NULL,
  `duracion` int(11) NOT NULL,
  `activo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productos_imagenes`
--

CREATE TABLE `productos_imagenes` (
  `cod` int(11) NOT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productos_usados`
--

CREATE TABLE `productos_usados` (
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `cod` int(11) NOT NULL,
  `descripcion` varchar(300) NOT NULL,
  `costo` double NOT NULL,
  `foto` varchar(100) NOT NULL DEFAULT '',
  `cod_cat` varchar(100) NOT NULL DEFAULT '',
  `id_marca` int(11) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `clicks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profesor`
--

CREATE TABLE `profesor` (
  `id_profesor` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cuit` int(11) DEFAULT NULL,
  `tipo` int(1) NOT NULL,
  `mail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrados`
--

CREATE TABLE `registrados` (
  `alias` varchar(20) NOT NULL,
  `nombre` varchar(20) NOT NULL DEFAULT '',
  `apellido` varchar(20) NOT NULL DEFAULT '',
  `dni` int(8) NOT NULL,
  `mail` varchar(40) NOT NULL,
  `nacimiento` date NOT NULL,
  `comentario` mediumtext NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `actividad` int(11) NOT NULL,
  `profesor` int(11) NOT NULL,
  `vencimiento` date NOT NULL,
  `mes_pagado` date NOT NULL,
  `fecha` date NOT NULL,
  `celular` varchar(50) NOT NULL,
  `autorizacion` varchar(1) NOT NULL,
  `certificado` varchar(1) NOT NULL,
  `clave` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `credito` int(11) DEFAULT 0,
  `mail_confirma` varchar(1) DEFAULT NULL,
  `dni_confirma` varchar(1) NOT NULL,
  `reserva_auto` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(10) NOT NULL,
  `clave` varchar(10) NOT NULL,
  `id_tipo_usuario` int(11) NOT NULL,
  `activo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_tipos`
--

CREATE TABLE `usuarios_tipos` (
  `id_tipo_usuario` int(11) NOT NULL,
  `nombre_tipo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_registrados` int(8) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_forma` int(11) NOT NULL,
  `efectivo` double DEFAULT NULL,
  `descuento` double NOT NULL,
  `recargo` double NOT NULL,
  `confirmada` varchar(1) NOT NULL,
  `pagado` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ventas_detalle`
--

CREATE TABLE `ventas_detalle` (
  `id_venta` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ventas_facturacion`
--

CREATE TABLE `ventas_facturacion` (
  `mes` int(11) NOT NULL,
  `ventapgim` double NOT NULL,
  `ventamesgim` double NOT NULL,
  `ventapbar` double NOT NULL,
  `ventamesbar` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ventas_forma_pago`
--

CREATE TABLE `ventas_forma_pago` (
  `id_forma` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ventas_temporal`
--

CREATE TABLE `ventas_temporal` (
  `cod_producto` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_venta_temporal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`id_actividad`);

--
-- Indexes for table `actividad_dias`
--
ALTER TABLE `actividad_dias`
  ADD PRIMARY KEY (`id_dia`);

--
-- Indexes for table `actividad_horarios`
--
ALTER TABLE `actividad_horarios`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `fk_actividad_horarios_actividad_dias1_idx` (`actividad_dias_id_dia`),
  ADD KEY `fk_actividad_horarios_actividad1_idx` (`actividad_id_actividad`);

--
-- Indexes for table `actividad_horarios_susp`
--
ALTER TABLE `actividad_horarios_susp`
  ADD PRIMARY KEY (`actividad_horarios_id_horario2`,`fecha`) USING BTREE;

--
-- Indexes for table `actividad_reservas`
--
ALTER TABLE `actividad_reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `no_repetir_invitacion` (`registrados_dni`,`fecha`,`actividad_horarios_id_horario`),
  ADD KEY `fk_reservas_registrados1_idx` (`registrados_dni`),
  ADD KEY `fk_actividad_reservas_actividad_horarios1_idx` (`actividad_horarios_id_horario`);

--
-- Indexes for table `actividad_reservas_asist`
--
ALTER TABLE `actividad_reservas_asist`
  ADD PRIMARY KEY (`registrados_dni`,`fecha`,`actividad_horarios_id_horario`),
  ADD KEY `fk_reservas_registrados1_idx` (`registrados_dni`),
  ADD KEY `fk_actividad_reservas_actividad_horarios1_idx` (`actividad_horarios_id_horario`),
  ADD KEY `indprofe` (`id_usuario`);

--
-- Indexes for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `ind_usuario` (`usu`);

--
-- Indexes for table `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_caja`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `caja_aporte`
--
ALTER TABLE `caja_aporte`
  ADD PRIMARY KEY (`fecha`,`hora`);

--
-- Indexes for table `caja_cierre`
--
ALTER TABLE `caja_cierre`
  ADD PRIMARY KEY (`id_cierre`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `caja_extraccion`
--
ALTER TABLE `caja_extraccion`
  ADD PRIMARY KEY (`fecha`,`hora`),
  ADD KEY `concepto` (`concepto`);

--
-- Indexes for table `caja_extraccion_concepto`
--
ALTER TABLE `caja_extraccion_concepto`
  ADD PRIMARY KEY (`id_concepto`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cod`);

--
-- Indexes for table `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `compra_detalle`
--
ALTER TABLE `compra_detalle`
  ADD PRIMARY KEY (`id_compra`,`cod_producto`);

--
-- Indexes for table `compra_temporal`
--
ALTER TABLE `compra_temporal`
  ADD PRIMARY KEY (`cod_producto`);

--
-- Indexes for table `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `id_mail` (`id_mail`);

--
-- Indexes for table `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `id_mail` (`id_mail`);

--
-- Indexes for table `foro`
--
ALTER TABLE `foro`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `mail` (`mail`);

--
-- Indexes for table `indicadores`
--
ALTER TABLE `indicadores`
  ADD PRIMARY KEY (`fecha`);

--
-- Indexes for table `indicadores_clientes_temp`
--
ALTER TABLE `indicadores_clientes_temp`
  ADD PRIMARY KEY (`dni`);

--
-- Indexes for table `inscripcion_eventos`
--
ALTER TABLE `inscripcion_eventos`
  ADD PRIMARY KEY (`cod`);

--
-- Indexes for table `inscripcion_eventos_ackitecontest`
--
ALTER TABLE `inscripcion_eventos_ackitecontest`
  ADD PRIMARY KEY (`cod`);

--
-- Indexes for table `inscripcion_eventos_undiadewake`
--
ALTER TABLE `inscripcion_eventos_undiadewake`
  ADD PRIMARY KEY (`cod`);

--
-- Indexes for table `mails`
--
ALTER TABLE `mails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- Indexes for table `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id_marca`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_usuario_rem` (`id_usuario_rem`);

--
-- Indexes for table `mensajes_conversaciones`
--
ALTER TABLE `mensajes_conversaciones`
  ADD PRIMARY KEY (`id_conversacion`);

--
-- Indexes for table `mensajes_internos`
--
ALTER TABLE `mensajes_internos`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_usuario_rem` (`id_usuario_rem`);

--
-- Indexes for table `mensajes_internos_destinatarios`
--
ALTER TABLE `mensajes_internos_destinatarios`
  ADD PRIMARY KEY (`id_usuario`,`id_mensaje`),
  ADD KEY `id_mensaje` (`id_mensaje`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`nro_ped`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indexes for table `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  ADD PRIMARY KEY (`nro_det`),
  ADD KEY `cod` (`cod`),
  ADD KEY `nro_ped` (`nro_ped`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `id_marca` (`id_marca`);

--
-- Indexes for table `productos_imagenes`
--
ALTER TABLE `productos_imagenes`
  ADD PRIMARY KEY (`cod`,`foto`),
  ADD KEY `cod` (`cod`);

--
-- Indexes for table `productos_usados`
--
ALTER TABLE `productos_usados`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `id_marca` (`id_marca`);

--
-- Indexes for table `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`id_profesor`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `registrados`
--
ALTER TABLE `registrados`
  ADD PRIMARY KEY (`dni`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_tipo_usuario` (`id_tipo_usuario`);

--
-- Indexes for table `usuarios_tipos`
--
ALTER TABLE `usuarios_tipos`
  ADD PRIMARY KEY (`id_tipo_usuario`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_forma` (`id_forma`);

--
-- Indexes for table `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD PRIMARY KEY (`id_venta`,`cod_producto`);

--
-- Indexes for table `ventas_facturacion`
--
ALTER TABLE `ventas_facturacion`
  ADD PRIMARY KEY (`mes`);

--
-- Indexes for table `ventas_forma_pago`
--
ALTER TABLE `ventas_forma_pago`
  ADD PRIMARY KEY (`id_forma`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `ventas_temporal`
--
ALTER TABLE `ventas_temporal`
  ADD PRIMARY KEY (`id_venta_temporal`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actividad`
--
ALTER TABLE `actividad`
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actividad_dias`
--
ALTER TABLE `actividad_dias`
  MODIFY `id_dia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actividad_horarios`
--
ALTER TABLE `actividad_horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actividad_horarios_susp`
--
ALTER TABLE `actividad_horarios_susp`
  MODIFY `actividad_horarios_id_horario2` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actividad_reservas`
--
ALTER TABLE `actividad_reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caja_cierre`
--
ALTER TABLE `caja_cierre`
  MODIFY `id_cierre` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultas`
--
ALTER TABLE `consultas`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacto`
--
ALTER TABLE `contacto`
  MODIFY `codigo` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foro`
--
ALTER TABLE `foro`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscripcion_eventos`
--
ALTER TABLE `inscripcion_eventos`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscripcion_eventos_ackitecontest`
--
ALTER TABLE `inscripcion_eventos_ackitecontest`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscripcion_eventos_undiadewake`
--
ALTER TABLE `inscripcion_eventos_undiadewake`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mails`
--
ALTER TABLE `mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mensajes_conversaciones`
--
ALTER TABLE `mensajes_conversaciones`
  MODIFY `id_conversacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mensajes_internos`
--
ALTER TABLE `mensajes_internos`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `nro_ped` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  MODIFY `nro_det` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productos_usados`
--
ALTER TABLE `productos_usados`
  MODIFY `cod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profesor`
--
ALTER TABLE `profesor`
  MODIFY `id_profesor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios_tipos`
--
ALTER TABLE `usuarios_tipos`
  MODIFY `id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ventas_forma_pago`
--
ALTER TABLE `ventas_forma_pago`
  MODIFY `id_forma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ventas_temporal`
--
ALTER TABLE `ventas_temporal`
  MODIFY `id_venta_temporal` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actividad_horarios`
--
ALTER TABLE `actividad_horarios`
  ADD CONSTRAINT `fk_actividad_horarios_actividad1` FOREIGN KEY (`actividad_id_actividad`) REFERENCES `actividad` (`id_actividad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_actividad_horarios_actividad_dias1` FOREIGN KEY (`actividad_dias_id_dia`) REFERENCES `actividad_dias` (`id_dia`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `actividad_reservas`
--
ALTER TABLE `actividad_reservas`
  ADD CONSTRAINT `fk_actividad_reservas_actividad_horarios1` FOREIGN KEY (`actividad_horarios_id_horario`) REFERENCES `actividad_horarios` (`id_horario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reservas_registrados1` FOREIGN KEY (`registrados_dni`) REFERENCES `registrados` (`dni`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `actividad_reservas_asist`
--
ALTER TABLE `actividad_reservas_asist`
  ADD CONSTRAINT `actividad_reservas_asist_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usu`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `caja_extraccion`
--
ALTER TABLE `caja_extraccion`
  ADD CONSTRAINT `caja_extraccion_ibfk_1` FOREIGN KEY (`concepto`) REFERENCES `caja_extraccion_concepto` (`id_concepto`);

--
-- Constraints for table `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `compra_detalle`
--
ALTER TABLE `compra_detalle`
  ADD CONSTRAINT `compra_detalle_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`) ON DELETE CASCADE;

--
-- Constraints for table `mensajes_internos`
--
ALTER TABLE `mensajes_internos`
  ADD CONSTRAINT `mensajes_internos_ibfk_1` FOREIGN KEY (`id_usuario_rem`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `mensajes_internos_destinatarios`
--
ALTER TABLE `mensajes_internos_destinatarios`
  ADD CONSTRAINT `mensajes_internos_destinatarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `mensajes_internos_destinatarios_ibfk_2` FOREIGN KEY (`id_mensaje`) REFERENCES `mensajes_internos` (`id_mensaje`);

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Constraints for table `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  ADD CONSTRAINT `pedidos_detalle_ibfk_1` FOREIGN KEY (`nro_ped`) REFERENCES `pedidos` (`nro_ped`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_detalle_ibfk_2` FOREIGN KEY (`cod`) REFERENCES `productos` (`cod`);

--
-- Constraints for table `productos_imagenes`
--
ALTER TABLE `productos_imagenes`
  ADD CONSTRAINT `productos_imagenes_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `productos` (`cod`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_tipo_usuario`) REFERENCES `usuarios_tipos` (`id_tipo_usuario`);

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_forma`) REFERENCES `ventas_forma_pago` (`id_forma`);

--
-- Constraints for table `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD CONSTRAINT `ventas_detalle_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
