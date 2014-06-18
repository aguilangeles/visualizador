-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-11-2011 a las 00:29:23
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `visualizador`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carat_meta`
--

DROP TABLE IF EXISTS `carat_meta`;
CREATE TABLE IF NOT EXISTS `carat_meta` (
  `carat_meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `carat_meta_desc` varchar(255) NOT NULL,
  `carat_meta_label` varchar(255) NOT NULL,
  `doc_type_id` int(11) NOT NULL,
  PRIMARY KEY (`carat_meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `carat_meta`
--

INSERT INTO `carat_meta` (`carat_meta_id`, `carat_meta_desc`, `carat_meta_label`, `doc_type_id`) VALUES
(1, 'CAJA', 'Caja Boletas', 1),
(2, 'BANCO', 'Banco', 1),
(3, 'SUCURSAL', 'Sucursal', 1),
(4, 'FECHAPRESENTACION', 'Fecha de Presentación', 1),
(5, 'CAJA', 'Caja', 2),
(6, 'ANIO', 'Año', 2),
(7, 'MES', 'Mes', 2),
(8, 'LIQUIDACION', 'Liquidación', 2),
(9, 'UNIDAD', 'Unidad', 2),
(10, 'CAJA', 'Caja', 3),
(11, 'ANIO', 'Año', 3),
(12, 'MES', 'Mes', 3),
(13, 'LIQUIDACION', 'Liquidación', 3),
(14, 'UNIDAD', 'Unidad', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctypegroups`
--

DROP TABLE IF EXISTS `doctypegroups`;
CREATE TABLE IF NOT EXISTS `doctypegroups` (
  `doctypegroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `doctype_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`doctypegroup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Volcar la base de datos para la tabla `doctypegroups`
--

INSERT INTO `doctypegroups` (`doctypegroup_id`, `doctype_id`, `group_id`) VALUES
(14, 1, 1),
(15, 2, 1),
(16, 3, 1),
(17, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doc_types`
--

DROP TABLE IF EXISTS `doc_types`;
CREATE TABLE IF NOT EXISTS `doc_types` (
  `doc_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_type_desc` varchar(255) NOT NULL,
  `doc_type_label` varchar(255) NOT NULL,
  `doc_type_level` int(11) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`doc_type_id`),
  UNIQUE KEY `doc_type_desc` (`doc_type_desc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `doc_types`
--

INSERT INTO `doc_types` (`doc_type_id`, `doc_type_desc`, `doc_type_label`, `doc_type_level`, `enabled`) VALUES
(1, 'BOLETAS', 'Boletas OSN', 1, 1),
(2, 'REC', 'Recibos', 1, 1),
(3, 'EJER', 'Ejercicios', 1, 1),
(4, 'Boletas n2', 'Boletas N2', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Volcar la base de datos para la tabla `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`) VALUES
(1, 'Administradores');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ocr_meta`
--

DROP TABLE IF EXISTS `ocr_meta`;
CREATE TABLE IF NOT EXISTS `ocr_meta` (
  `ocr_meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `ocr_meta_desc` varchar(255) NOT NULL,
  `ocr_meta_label` varchar(255) NOT NULL,
  `doc_type_id` int(11) NOT NULL,
  PRIMARY KEY (`ocr_meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcar la base de datos para la tabla `ocr_meta`
--

INSERT INTO `ocr_meta` (`ocr_meta_id`, `ocr_meta_desc`, `ocr_meta_label`, `doc_type_id`) VALUES
(1, 'PARTIDA', 'Partida', 1),
(2, 'DISTRITO', 'Distrito', 1),
(3, 'SUBCUENTA', 'Subcuenta', 1),
(4, 'DIGITO', 'Digito', 1),
(5, 'ANIO', 'Año', 1),
(6, 'BIMESTRE', 'Bimestre', 1),
(7, 'OCR1', 'OCR 1', 2),
(8, 'OCR2', 'OCR 2', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usergroups`
--

DROP TABLE IF EXISTS `usergroups`;
CREATE TABLE IF NOT EXISTS `usergroups` (
  `usergroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`usergroup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `usergroups`
--

INSERT INTO `usergroups` (`usergroup_id`, `user_id`, `group_id`) VALUES
(11, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL,
  `userPass` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `users`
--

INSERT INTO `users` (`userId`, `userName`, `userPass`, `is_admin`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);
