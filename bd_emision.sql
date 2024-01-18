/*
 Navicat Premium Data Transfer

 Source Server         : 250
 Source Server Type    : MySQL
 Source Server Version : 50626 (5.6.26)
 Source Host           : 192.168.1.250:3306
 Source Schema         : bd_emision

 Target Server Type    : MySQL
 Target Server Version : 50626 (5.6.26)
 File Encoding         : 65001

 Date: 26/10/2023 14:24:58
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bajas
-- ----------------------------
DROP TABLE IF EXISTS `bajas`;
CREATE TABLE `bajas`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idrelacion` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fecha_ingreso_baja` date NOT NULL,
  `filial_solicitud` int(6) NOT NULL,
  `nombre_funcionario` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `observaciones` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `nombre_socio` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cedula_socio` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `filial_socio` int(6) NOT NULL,
  `servicio_contratado` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `horas_contratadas` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `importe` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `motivo_baja` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nombre_contacto` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apellido_contacto` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `telefono_contacto` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `celular_contacto` varchar(9) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `fecha_inicio_gestion` date NULL DEFAULT NULL,
  `estado` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `nombre_funcionario_final` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `motivo_no_otorgada` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `observacion_final` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `area_fin_gestion` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `fecha_fin_gestion` date NULL DEFAULT NULL,
  `activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 823 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for historial_carga_bajas
-- ----------------------------
DROP TABLE IF EXISTS `historial_carga_bajas`;
CREATE TABLE `historial_carga_bajas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_subida` datetime NULL DEFAULT NULL,
  `cantidad_registros` int(11) NULL DEFAULT NULL,
  `nombre_archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for historial_logs
-- ----------------------------
DROP TABLE IF EXISTS `historial_logs`;
CREATE TABLE `historial_logs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for logs_correctos
-- ----------------------------
DROP TABLE IF EXISTS `logs_correctos`;
CREATE TABLE `logs_correctos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `consulta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_historial_logs` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 791 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for logs_errores
-- ----------------------------
DROP TABLE IF EXISTS `logs_errores`;
CREATE TABLE `logs_errores`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `consulta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `id_historial_logs` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for padron_datos_socio
-- ----------------------------
DROP TABLE IF EXISTS `padron_datos_socio`;
CREATE TABLE `padron_datos_socio`  (
  `id` int(11) NOT NULL DEFAULT 0,
  `nombre` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tel` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `cedula` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `direccion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sucursal` int(6) NULL DEFAULT NULL,
  `ruta` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `radio` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `activo` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_nacimiento` date NULL DEFAULT NULL,
  `edad` int(11) NULL DEFAULT NULL,
  `tarjeta` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tipo_tarjeta` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero_tarjeta` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nombre_titular` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cedula_titular` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono_titular` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `anio_e` int(4) NULL DEFAULT NULL,
  `mes_e` int(2) NULL DEFAULT NULL,
  `sucursal_cobranzas` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sucursal_cobranza_num` int(6) NULL DEFAULT NULL,
  `empresa_marca` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag` int(1) NULL DEFAULT NULL,
  `count` int(11) NULL DEFAULT NULL,
  `observaciones` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `grupo` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `idrelacion` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `empresa_rut` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_importe` int(11) NULL DEFAULT NULL,
  `nactual` int(11) NULL DEFAULT NULL,
  `version` int(11) NULL DEFAULT NULL,
  `flagchange` int(11) NULL DEFAULT NULL,
  `rutcentralizado` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `PRINT` int(11) NULL DEFAULT NULL,
  `EMITIDO` int(11) NULL DEFAULT NULL,
  `movimientoabm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `abm` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `abmactual` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `check` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `usuario` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `usuariod` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `fechafil` date NULL DEFAULT NULL,
  `radioViejo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `extra` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nomodifica` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `origenVta` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `imp_desc` int(11) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for padron_datos_socio1123
-- ----------------------------
DROP TABLE IF EXISTS `padron_datos_socio1123`;
CREATE TABLE `padron_datos_socio1123`  (
  `id` int(11) NOT NULL DEFAULT 0,
  `nombre` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tel` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `cedula` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `direccion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sucursal` int(6) NULL DEFAULT NULL,
  `ruta` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `radio` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `activo` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_nacimiento` date NULL DEFAULT NULL,
  `edad` int(11) NULL DEFAULT NULL,
  `tarjeta` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tipo_tarjeta` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `numero_tarjeta` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nombre_titular` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cedula_titular` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono_titular` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `anio_e` int(4) NULL DEFAULT NULL,
  `mes_e` int(2) NULL DEFAULT NULL,
  `sucursal_cobranzas` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sucursal_cobranza_num` int(6) NULL DEFAULT NULL,
  `empresa_marca` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag` int(1) NULL DEFAULT NULL,
  `count` int(11) NULL DEFAULT NULL,
  `observaciones` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `grupo` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `idrelacion` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `empresa_rut` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `total_importe` int(11) NULL DEFAULT NULL,
  `nactual` int(11) NULL DEFAULT NULL,
  `version` int(11) NULL DEFAULT NULL,
  `flagchange` int(11) NULL DEFAULT NULL,
  `rutcentralizado` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `PRINT` int(11) NULL DEFAULT NULL,
  `EMITIDO` int(11) NULL DEFAULT NULL,
  `movimientoabm` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `abm` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `abmactual` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `check` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `usuario` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `usuariod` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `fechafil` date NULL DEFAULT NULL,
  `radioViejo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `extra` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nomodifica` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `origenVta` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `imp_desc` int(11) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for padron_producto_socio
-- ----------------------------
DROP TABLE IF EXISTS `padron_producto_socio`;
CREATE TABLE `padron_producto_socio`  (
  `id` int(11) NOT NULL DEFAULT 0,
  `cedula` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `servicio` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `importe` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cod_promo` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_registro` date NULL DEFAULT NULL,
  `numero_contrato` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_afiliacion` date NULL DEFAULT NULL,
  `nombre_vendedor` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `observaciones` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lugar_venta` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vendedor_independiente` int(11) NULL DEFAULT NULL,
  `activo` int(11) NULL DEFAULT NULL,
  `movimiento` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `fecha_inicio_derechos` date NULL DEFAULT NULL,
  `numero_vendedor` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `keepprice1` int(11) NULL DEFAULT NULL,
  `promoactivo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `tipo_de_cobro` tinyint(11) NULL DEFAULT NULL,
  `tipo_iva` tinyint(1) UNSIGNED NULL DEFAULT 0,
  `idrelacion` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `codigo_precio` float(11, 0) NULL DEFAULT NULL,
  `aumento` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `empresa` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nactual` int(11) NULL DEFAULT NULL,
  `servdecod` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `count` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `version` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `abm` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `abmactual` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `usuario` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `usuarioid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `extra` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nomodifica` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `precioOriginal` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `abitab` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cedula_titular_gf` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for padron_producto_socio1123
-- ----------------------------
DROP TABLE IF EXISTS `padron_producto_socio1123`;
CREATE TABLE `padron_producto_socio1123`  (
  `id` int(11) NOT NULL DEFAULT 0,
  `cedula` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `servicio` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hora` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `importe` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cod_promo` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_registro` date NULL DEFAULT NULL,
  `numero_contrato` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_afiliacion` date NULL DEFAULT NULL,
  `nombre_vendedor` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `observaciones` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lugar_venta` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vendedor_independiente` int(11) NULL DEFAULT NULL,
  `activo` int(11) NULL DEFAULT NULL,
  `movimiento` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `fecha_inicio_derechos` date NULL DEFAULT NULL,
  `numero_vendedor` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `keepprice1` int(11) NULL DEFAULT NULL,
  `promoactivo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `tipo_de_cobro` tinyint(11) NULL DEFAULT NULL,
  `tipo_iva` tinyint(1) UNSIGNED NULL DEFAULT 0,
  `idrelacion` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `codigo_precio` float(11, 0) NULL DEFAULT NULL,
  `aumento` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `empresa` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nactual` int(11) NULL DEFAULT NULL,
  `servdecod` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `count` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `version` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `abm` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `abmactual` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `usuario` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `usuarioid` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `extra` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nomodifica` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `precioOriginal` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `abitab` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cedula_titular_gf` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for registrar_bajas
-- ----------------------------
DROP TABLE IF EXISTS `registrar_bajas`;
CREATE TABLE `registrar_bajas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` int(11) NULL DEFAULT NULL,
  `id_historial_carga_bajas` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 823 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `hash` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `activo` int(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
