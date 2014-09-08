-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-09-2014 a las 02:12:51
-- Versión del servidor: 5.5.37-35.1
-- Versión de PHP: 5.4.23

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `energycl_maquinacpquito`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fbuser`
--

CREATE TABLE IF NOT EXISTS `fbuser` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `fbuser` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `link` varchar(250) NOT NULL,
  `username` varchar(100) NOT NULL,
  `birthday` date DEFAULT NULL,
  `hometown_id` varchar(50) DEFAULT NULL,
  `hometown_name` varchar(100) DEFAULT NULL,
  `bio` varchar(250) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `timezone` varchar(20) DEFAULT NULL,
  `locale` varchar(20) DEFAULT NULL,
  `token_comun` mediumtext NOT NULL,
  `token_largo` mediumtext NOT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fbuser` (`fbuser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_codigo`
--

CREATE TABLE IF NOT EXISTS `usuario_codigo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fbuser_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `codigobarras` int(4) unsigned zerofill NOT NULL,
  `activado` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
