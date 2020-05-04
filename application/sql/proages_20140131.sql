-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 31-01-2014 a las 10:04:30
-- Versión del servidor: 5.5.31
-- Versión de PHP: 5.3.10-1ubuntu3.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pro-ages_2001`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `label` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Volcado de datos para la tabla `actions`
--

INSERT INTO `actions` (`id`, `name`, `label`, `last_updated`, `date`) VALUES
(1, 'Ver', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(2, 'Crear', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(3, 'Editar', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(4, 'Activar/Desactivar', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(5, 'Export xls', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(6, 'Import xls', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(7, 'Export pdf', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(8, 'Cambiar estatus', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(9, 'Importar payments', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(10, 'Ver reporte', '', '2013-07-15 14:27:28', '2013-07-15 14:27:28'),
(11, 'Petición nuevo usuario', '', '2013-07-15 14:39:53', '2013-07-15 14:39:53'),
(12, 'Eliminar', '', '2013-07-20 13:53:58', '2013-07-20 13:53:58'),
(13, 'Enviar correo', '', '2013-07-20 13:53:58', '2013-07-20 13:53:58'),
(14, 'Ver todos los registros', '', '2013-07-28 16:07:01', '2013-07-28 16:07:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agencies`
--

CREATE TABLE IF NOT EXISTS `agencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `insurance` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `joined_since` date DEFAULT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agents`
--

CREATE TABLE IF NOT EXISTS `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `connection_date` date DEFAULT NULL,
  `license_expired_date` date DEFAULT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

--
-- Volcado de datos para la tabla `agents`
--

INSERT INTO `agents` (`id`, `user_id`, `connection_date`, `license_expired_date`, `last_updated`, `date`) VALUES
(54, 61, NULL, NULL, '2013-08-08 23:56:01', '2013-08-09 04:56:01'),
(59, 64, '0000-00-00', '0000-00-00', '2013-08-20 01:17:12', '2013-08-20 06:17:12'),
(60, 65, '0000-00-00', '0000-00-00', '2013-09-02 13:09:07', '2013-09-02 18:09:07'),
(61, 59, '0000-00-00', '0000-00-00', '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(63, 1, '0000-00-00', '0000-00-00', '2013-10-05 09:37:08', '2013-10-05 14:37:08'),
(65, 66, '2002-07-02', '2015-12-12', '2013-12-23 18:21:17', '2013-12-24 00:21:17'),
(68, 62, '2014-01-01', '0000-00-00', '2014-01-15 16:21:30', '2014-01-15 22:21:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agents_activity`
--

CREATE TABLE IF NOT EXISTS `agents_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `begin` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `cita` int(11) DEFAULT '0',
  `prospectus` int(11) DEFAULT '0',
  `interview` int(11) DEFAULT '0',
  `vida_requests` int(11) DEFAULT NULL,
  `vida_businesses` int(11) DEFAULT NULL,
  `gmm_requests` int(11) DEFAULT NULL,
  `gmm_businesses` int(11) DEFAULT NULL,
  `autos_businesses` int(11) DEFAULT NULL,
  `comments` text CHARACTER SET utf8 COLLATE utf8_spanish_ci,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `agents_activity`
--

INSERT INTO `agents_activity` (`id`, `agent_id`, `begin`, `end`, `cita`, `prospectus`, `interview`, `vida_requests`, `vida_businesses`, `gmm_requests`, `gmm_businesses`, `autos_businesses`, `comments`, `last_updated`, `date`) VALUES
(1, 63, '2013-09-30', '2013-10-06', 10, 3, 8, NULL, NULL, NULL, NULL, NULL, NULL, '2013-10-05 09:38:04', '2013-10-05 14:38:04'),
(2, 63, '2013-12-09', '2013-12-15', 10, 25, 8, NULL, NULL, NULL, NULL, NULL, NULL, '2013-12-22 16:52:11', '2013-12-22 22:52:11'),
(3, 59, '2013-12-16', '2013-12-22', 10, 25, 8, NULL, NULL, NULL, NULL, NULL, NULL, '2013-12-22 17:31:41', '2013-12-22 23:31:41'),
(4, 63, '2013-12-16', '2013-12-22', 10, 10, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2013-12-22 20:30:27', '2013-12-23 02:30:27'),
(5, 63, '2013-11-25', '2013-12-01', 10, 10, 5, NULL, NULL, NULL, NULL, NULL, NULL, '2013-12-22 20:31:06', '2013-12-23 02:31:06'),
(6, 65, '2013-12-16', '2013-12-22', 8, 21, 6, NULL, NULL, NULL, NULL, NULL, NULL, '2013-12-23 19:08:11', '2013-12-24 01:08:11'),
(7, 65, '2013-12-09', '2013-12-15', 7, 10, 5, NULL, NULL, NULL, NULL, NULL, NULL, '2013-12-23 19:08:42', '2013-12-24 01:08:42'),
(8, 65, '2013-12-02', '2013-12-08', 8, 4, 6, NULL, NULL, NULL, NULL, NULL, NULL, '2013-12-23 19:11:07', '2013-12-24 01:11:07'),
(9, 63, '2013-12-02', '2013-12-08', 12, 15, 10, NULL, NULL, NULL, NULL, NULL, NULL, '2013-12-24 17:21:00', '2013-12-24 23:21:00'),
(10, 63, '2013-12-30', '2014-01-05', 10, 15, 8, NULL, NULL, NULL, NULL, NULL, NULL, '2014-01-01 19:57:13', '2014-01-02 01:57:13'),
(11, 63, '2014-01-06', '2014-01-12', 8, 25, 6, NULL, NULL, NULL, NULL, NULL, 'Test', '2014-01-12 12:46:20', '2014-01-12 18:46:20'),
(12, 64, '2014-01-06', '2014-01-12', 10, 15, 8, NULL, NULL, NULL, NULL, NULL, 'Para agente cancelado', '2014-01-12 13:14:17', '2014-01-12 19:14:17'),
(13, 63, '2014-01-13', '2014-01-19', 10, 15, 8, 3, 1, 2, 2, 3, 'No comments', '2014-01-19 18:41:16', '2014-01-20 00:41:16'),
(14, 63, '2014-01-20', '2014-01-26', 10, 20, 10, 3, 5, 3, 4, 6, '', '2014-01-29 11:59:26', '2014-01-29 17:59:26'),
(15, 59, '2014-01-20', '2014-01-26', 8, 15, 6, 0, 0, 3, 2, 5, NULL, '2014-01-29 12:01:10', '2014-01-29 18:01:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agent_uids`
--

CREATE TABLE IF NOT EXISTS `agent_uids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `type` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `uid` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=341 ;

--
-- Volcado de datos para la tabla `agent_uids`
--

INSERT INTO `agent_uids` (`id`, `agent_id`, `type`, `uid`, `last_updated`, `date`) VALUES
(170, 54, 'clave', '767676767', '2013-08-08 23:56:01', '2013-08-09 04:56:01'),
(171, 54, 'national', 'N54567', '2013-08-08 23:56:01', '2013-08-09 04:56:01'),
(172, 54, 'provincial', 'P343343', '2013-08-08 23:56:01', '2013-08-09 04:56:01'),
(183, 59, 'clave', '87987987', '2013-08-20 01:17:12', '2013-08-20 06:17:12'),
(184, 59, 'national', '987897', '2013-08-20 01:17:12', '2013-08-20 06:17:12'),
(185, 59, 'provincial', '987987', '2013-08-20 01:17:12', '2013-08-20 06:17:12'),
(186, 60, 'clave', '', '2013-09-02 13:09:07', '2013-09-02 18:09:07'),
(187, 60, 'national', '', '2013-09-02 13:09:07', '2013-09-02 18:09:07'),
(188, 60, 'provincial', '', '2013-09-02 13:09:07', '2013-09-02 18:09:07'),
(189, 60, 'national', 'N0014011', '2013-09-02 18:13:10', '2013-09-02 23:13:10'),
(190, 60, 'provincial', 'P0026024', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(192, 60, 'provincial', 'P0016324', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(195, 54, 'provincial', 'P0012953', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(196, 59, 'provincial', 'P0020399', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(197, 60, 'provincial', 'P0021808', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(198, 59, 'provincial', 'P0023164', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(200, 59, 'provincial', 'P0035187', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(201, 59, 'provincial', 'P0046397', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(204, 59, 'provincial', 'P0022679', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(205, 60, 'provincial', 'P0023083', '2013-09-05 14:59:29', '2013-09-05 19:59:29'),
(206, 61, 'clave', '897897987', '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(207, 61, 'national', 'N89897', '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(208, 61, 'provincial', 'P987897', '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(209, 61, 'provincial', 'P0007802', '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(210, 61, 'provincial', 'P0005789', '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(211, 61, 'provincial', 'P0004234', '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(212, 61, 'provincial', 'P0022519', '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(217, 63, 'clave', '', '2013-10-05 09:37:08', '2013-10-05 14:37:08'),
(218, 63, 'national', '', '2013-10-05 09:37:08', '2013-10-05 14:37:08'),
(219, 63, 'provincial', '', '2013-10-05 09:37:08', '2013-10-05 14:37:08'),
(252, 65, 'clave', '70090', '2013-12-23 18:21:17', '2013-12-24 00:21:17'),
(253, 65, 'national', '23164', '2013-12-23 18:21:17', '2013-12-24 00:21:17'),
(254, 65, 'provincial', '23164', '2013-12-23 18:21:17', '2013-12-24 00:21:17'),
(315, 68, 'clave', 'CLAVE12345', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(316, 68, 'national', 'N0001525', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(317, 68, 'national', 'N0012953', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(318, 68, 'national', 'N0038863', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(319, 68, 'national', 'N0044105', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(320, 68, 'national', 'N0030070', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(321, 68, 'national', 'N0043009', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(322, 68, 'national', 'N0046182', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(323, 68, 'national', 'N0047743', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(324, 68, 'national', 'N0016324', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(325, 68, 'provincial', 'P0009855', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(326, 68, 'provincial', 'P0023642', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(327, 68, 'provincial', 'P0022428', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(328, 68, 'provincial', 'P0022995', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(329, 68, 'provincial', 'P0046182', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(330, 68, 'provincial', 'P0003011', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(331, 68, 'provincial', 'P0009651', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(332, 68, 'provincial', 'P0021806', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(333, 68, 'provincial', 'P0023943', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(334, 68, 'provincial', 'P0042817', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(335, 68, 'provincial', 'P0025586', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(336, 68, 'provincial', 'P0026920', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(337, 68, 'provincial', 'P0036217', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(338, 68, 'provincial', 'P0044105', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(339, 68, 'provincial', 'P0008740', '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(340, 68, 'provincial', 'P0020244', '2014-01-15 16:21:30', '2014-01-15 22:21:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `label` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `label`, `last_updated`, `date`) VALUES
(1, 'Moneda Nacional', '', '2013-07-24 16:01:43', '2013-07-24 16:01:43'),
(2, 'Dolares Americanos', '', '2013-07-24 16:01:43', '2013-07-24 16:01:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `folder`
--

CREATE TABLE IF NOT EXISTS `folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_type_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `user_agent` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `referer` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_types`
--

CREATE TABLE IF NOT EXISTS `log_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `label` varchar(45) NOT NULL DEFAULT '',
  `last_updated` date DEFAULT '0000-00-00',
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id`, `name`, `label`, `last_updated`, `date`) VALUES
(2, 'Usuarios', '', '0000-00-00', 1372286547),
(3, 'Modulos', '', '2013-07-15', 1373913971),
(4, 'Rol', '', '2013-07-15', 1373913981),
(5, 'Orden de trabajo', '', '2013-07-15', 1373914004),
(6, 'Actividades', 'Actividades', '0000-00-00', 1380580833),
(7, 'Simulador', 'Simulador', '0000-00-00', 1381536644),
(8, 'Agent Profile', 'Agentes', '0000-00-00', 1387751082);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_type_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `subject` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `send` tinyint(4) NOT NULL,
  `unread` tinyint(4) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification_types`
--

CREATE TABLE IF NOT EXISTS `notification_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `offices`
--

CREATE TABLE IF NOT EXISTS `offices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) NOT NULL,
  `label` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) NOT NULL,
  `YEAR_PRIME` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `business` int(11) DEFAULT NULL,
  `policy_number` varchar(250) DEFAULT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

--
-- Volcado de datos para la tabla `payments`
--

INSERT INTO `payments` (`id`, `policy_id`, `YEAR_PRIME`, `currency_id`, `amount`, `payment_date`, `business`, `policy_number`, `last_updated`, `date`) VALUES
(1, 53, 0, 1, 339, '2013-08-28', NULL, NULL, '2013-09-02 13:12:24', '2013-09-02 18:12:24'),
(2, 54, 0, 1, 1935, '2013-08-28', NULL, NULL, '2013-09-02 13:12:24', '2013-09-02 18:12:24'),
(3, 53, 0, 1, 339, '2013-08-28', NULL, NULL, '2013-09-02 13:20:21', '2013-09-02 18:20:21'),
(4, 54, 0, 1, 1935, '2013-08-28', NULL, NULL, '2013-09-02 13:20:21', '2013-09-02 18:20:21'),
(5, 55, 0, 1, 339, '2013-08-28', NULL, NULL, '2013-09-02 18:13:21', '2013-09-02 23:13:21'),
(6, 56, 0, 1, 1935, '2013-08-28', NULL, NULL, '2013-09-02 18:13:21', '2013-09-02 23:13:21'),
(8, 58, 0, 1, 10145, '2013-09-18', NULL, NULL, '2013-09-19 15:19:54', '2013-09-19 20:19:54'),
(9, 60, 0, 1, 4999, '2013-09-18', NULL, NULL, '2013-09-19 15:19:54', '2013-09-20 04:18:50'),
(10, 62, 0, 1, 35000, '2013-12-22', NULL, NULL, '2013-12-22 00:00:00', '2013-12-23 22:21:57'),
(11, 64, 0, 1, 13224, '2014-01-01', 0, '112594734', '2014-01-01 21:06:47', '2014-01-02 03:08:48'),
(12, 65, 0, 1, 2053, '2014-01-01', 0, '120184734', '2014-01-01 21:06:48', '2014-01-02 03:08:48'),
(13, 66, 0, 1, 1518, '2014-01-01', 0, '118945609', '2014-01-01 21:06:48', '2014-01-02 03:08:48'),
(14, 67, 0, 1, -13732, '2014-01-01', -1, '120639539', '2014-01-01 21:06:48', '2014-01-02 03:08:48'),
(15, 68, 0, 1, 1225, '2014-01-01', 0, '118292622', '2014-01-01 21:06:48', '2014-01-02 03:08:48'),
(16, 69, 0, 1, 4540, '2014-01-01', 1, '121035588', '2014-01-01 21:06:48', '2014-01-02 03:08:48'),
(17, 70, 0, 1, 2519, '2014-01-01', 0, '117047837', '2014-01-01 21:06:48', '2014-01-02 03:08:48'),
(18, 71, 0, 1, 3389, '2014-01-01', 1, '121255343', '2014-01-01 21:06:48', '2014-01-02 03:08:48'),
(19, 72, 0, 1, 2222, '2014-01-01', 1, '121138192', '2014-01-01 21:06:48', '2014-01-02 03:08:48'),
(20, 64, 0, 1, 13224, '1969-12-31', 0, '112594734', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(21, 65, 0, 1, 2053, '1969-12-31', 0, '120184734', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(22, 66, 0, 1, 1518, '1969-12-31', 0, '118945609', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(23, 67, 0, 1, -13732, '1969-12-31', -1, '120639539', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(24, 68, 0, 1, 1225, '1969-12-31', 0, '118292622', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(25, 69, 0, 1, 4540, '1969-12-31', 1, '121035588', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(26, 70, 0, 1, 2519, '1969-12-31', 0, '117047837', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(27, 71, 0, 1, 3389, '1969-12-31', 1, '121255343', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(28, 72, 0, 1, 2222, '1969-12-31', 1, '121138192', '2014-01-04 19:20:52', '2014-01-05 01:20:52'),
(29, 64, 0, 1, 13224, '1969-12-31', 0, '112594734', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(30, 65, 0, 1, 2053, '1969-12-31', 0, '120184734', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(31, 66, 0, 1, 1518, '1969-12-31', 0, '118945609', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(32, 67, 0, 1, -13732, '1969-12-31', -1, '120639539', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(33, 68, 0, 1, 1225, '1969-12-31', 0, '118292622', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(34, 69, 0, 1, 4540, '1969-12-31', 1, '121035588', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(35, 70, 0, 1, 2519, '1969-12-31', 0, '117047837', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(36, 71, 0, 1, 3389, '1969-12-31', 1, '121255343', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(37, 72, 0, 1, 2222, '1969-12-31', 1, '121138192', '2014-01-04 19:26:38', '2014-01-05 01:26:38'),
(38, 64, 0, 1, 13224, '2013-10-30', 0, '112594734', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(39, 65, 0, 1, 2053, '2013-10-30', 0, '120184734', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(40, 66, 0, 1, 1518, '1969-12-31', 0, '118945609', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(41, 67, 0, 1, -13732, '1969-12-31', -1, '120639539', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(42, 68, 0, 1, 1225, '1969-12-31', 0, '118292622', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(43, 69, 0, 1, 4540, '1969-12-31', 1, '121035588', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(44, 70, 0, 1, 2519, '1969-12-31', 0, '117047837', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(45, 71, 0, 1, 3389, '1969-12-31', 1, '121255343', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(46, 72, 0, 1, 2222, '1969-12-31', 1, '121138192', '2014-01-04 19:29:16', '2014-01-05 01:29:16'),
(47, 64, 0, 1, 13224, '2013-10-30', 0, '112594734', '2014-01-04 19:30:24', '2014-01-05 01:30:24'),
(48, 65, 0, 1, 2053, '2013-10-30', 0, '120184734', '2014-01-04 19:30:24', '2014-01-05 01:30:24'),
(49, 66, 0, 1, 1518, '1969-12-31', 0, '118945609', '2014-01-04 19:30:24', '2014-01-05 01:30:24'),
(50, 67, 0, 1, -13732, '1969-12-31', -1, '120639539', '2014-01-04 19:30:24', '2014-01-05 01:30:24'),
(51, 68, 0, 1, 1225, '1969-12-31', 0, '118292622', '2014-01-04 19:30:24', '2014-01-05 01:30:24'),
(52, 69, 0, 1, 4540, '1969-12-31', 1, '121035588', '2014-01-04 19:30:24', '2014-01-05 01:30:24'),
(53, 70, 0, 1, 2519, '1969-12-31', 0, '117047837', '2014-01-04 19:30:24', '2014-01-05 01:30:24'),
(54, 71, 0, 1, 3389, '1969-12-31', 1, '121255343', '2014-01-04 19:30:24', '2014-01-05 01:30:24'),
(55, 72, 0, 1, 2222, '1969-12-31', 1, '121138192', '2014-01-04 19:30:24', '2014-01-05 01:30:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments_tmp`
--

CREATE TABLE IF NOT EXISTS `payments_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_intervals`
--

CREATE TABLE IF NOT EXISTS `payment_intervals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `label` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `payment_intervals`
--

INSERT INTO `payment_intervals` (`id`, `name`, `label`, `last_updated`, `date`) VALUES
(1, 'Mensual', '', '2013-07-24 16:03:12', '2013-07-24 16:03:12'),
(2, 'Trimestral', '', '2013-07-24 16:03:12', '2013-07-24 16:03:12'),
(3, 'Semestral', '', '2013-07-24 16:03:12', '2013-07-24 16:03:12'),
(4, 'Anual', '', '2013-07-24 16:03:12', '2013-07-24 16:03:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_methods`
--

CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `label` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `label`, `last_updated`, `date`) VALUES
(1, 'Oficina de servicio', '', '2013-07-24 16:06:18', '2013-07-24 16:06:18'),
(2, 'Cargo automático a tarjeta de credito', '', '2013-07-24 16:06:36', '2013-07-24 16:06:36'),
(3, 'Cargo unico a tarjeta de Credito', '', '2013-07-24 16:06:36', '2013-07-24 16:06:36'),
(4, 'Cargo a chequeras o tarjeta de debito', '', '2013-07-24 16:06:36', '2013-07-24 16:06:36'),
(5, 'Pago en sucursal bancaria o por Internet', '', '2013-07-24 16:06:36', '2013-07-24 16:06:36'),
(6, 'Agente', 'Agente', '2013-08-07 08:08:46', '2013-08-07 13:08:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platforms`
--

CREATE TABLE IF NOT EXISTS `platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `label` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `platforms`
--

INSERT INTO `platforms` (`id`, `name`, `label`, `last_updated`, `date`) VALUES
(1, 'tradicional', 'traditional', NULL, NULL),
(2, 'universal', 'universal', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `policies`
--

CREATE TABLE IF NOT EXISTS `policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_group_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `payment_interval_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `prima` decimal(20,2) DEFAULT NULL,
  `uid` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `lastname_father` varchar(45) NOT NULL,
  `lastname_mother` varchar(45) NOT NULL,
  `year_premium` decimal(10,0) DEFAULT NULL,
  `period` varchar(20) NOT NULL,
  `expired_date` datetime DEFAULT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

--
-- Volcado de datos para la tabla `policies`
--

INSERT INTO `policies` (`id`, `product_id`, `product_group_id`, `currency_id`, `payment_interval_id`, `payment_method_id`, `prima`, `uid`, `name`, `lastname_father`, `lastname_mother`, `year_premium`, `period`, `expired_date`, `last_updated`, `date`) VALUES
(1, 17, 0, 1, 1, 1, 0.00, '9', 'prueba', 'prueba', 'prueba', 0, '0', '0000-00-00 00:00:00', '2013-08-02 01:10:42', '2013-08-02 01:10:42'),
(2, 1, 0, 1, 2, 1, 0.00, '5', 'sdfsdf', 'kmlkjlkj', 'lkjlkjklj', 0, '10', '0000-00-00 00:00:00', '2013-08-02 14:39:51', '2013-08-02 14:39:51'),
(3, 19, 0, 1, 3, 4, 0.00, '9', 'wewe', 'werwerwer', 'werwerwer', 0, '0', '0000-00-00 00:00:00', '2013-08-02 23:48:19', '2013-08-02 23:48:19'),
(4, 6, 0, 1, 2, 2, 0.00, '5', 'a', 'sdasdasdasdasd', 'asdasd', 0, 'EA65', '0000-00-00 00:00:00', '2013-08-06 12:15:00', '2013-08-06 17:15:00'),
(5, 1, 0, 1, 1, 4, 0.00, '5', 'lkjlkj', 'kljlkjlkj', 'lkjlkjlkj', 0, '8', '0000-00-00 00:00:00', '2013-08-06 21:09:01', '2013-08-07 02:09:01'),
(6, 0, 0, 0, 0, 0, 0.00, '2013080701', '', '', '', NULL, '', NULL, '2013-08-07 22:56:14', '2013-08-08 03:56:14'),
(7, 0, 0, 0, 0, 0, 0.00, '234345356', '', '', '', NULL, '', NULL, '2013-08-08 08:17:21', '2013-08-08 13:17:21'),
(8, 0, 0, 0, 0, 0, 0.00, 'ddf34534534', '', '', '', NULL, '', NULL, '2013-08-08 13:18:32', '2013-08-08 18:18:32'),
(9, 0, 0, 0, 0, 0, 0.00, '', '', '', '', NULL, '', NULL, '2013-08-09 17:02:40', '2013-08-09 22:02:40'),
(10, 0, 0, 0, 0, 0, 0.00, '123456', '', '', '', NULL, '', NULL, '2013-08-16 17:12:05', '2013-08-16 22:12:05'),
(11, 0, 0, 0, 0, 0, 0.00, '34234234234234', '', '', '', NULL, '', NULL, '2013-08-19 19:18:55', '2013-08-20 00:18:55'),
(12, 1, 0, 1, 3, 1, 0.00, '8978798798', 'Asegurado contratante', '', '', 0, '18', '0000-00-00 00:00:00', '2013-08-19 23:20:57', '2013-09-02 23:04:34'),
(13, 1, 0, 1, 3, 3, 130000.00, '', 'Asegurado contratante', '', '', 0, '14', '0000-00-00 00:00:00', '2013-08-19 23:57:24', '2013-08-20 04:57:24'),
(14, 0, 0, 0, 0, 0, NULL, '123456789', '', '', '', NULL, '', NULL, '2013-08-20 00:45:34', '2013-08-20 05:45:34'),
(15, 0, 0, 0, 0, 0, NULL, '442352534', '', '', '', NULL, '', NULL, '2013-08-20 00:47:49', '2013-08-20 05:47:49'),
(16, 0, 0, 0, 0, 0, NULL, '4545345', '', '', '', NULL, '', NULL, '2013-08-20 00:50:54', '2013-08-20 05:50:54'),
(17, 0, 0, 0, 0, 0, NULL, '34324234', '', '', '', NULL, '', NULL, '2013-08-20 00:52:11', '2013-08-20 05:52:11'),
(18, 0, 0, 0, 0, 0, NULL, '', '', '', '', NULL, '', NULL, '2013-08-20 00:54:20', '2013-08-20 05:54:20'),
(19, 0, 0, 0, 0, 0, NULL, '', '', '', '', NULL, '', NULL, '2013-08-20 00:58:25', '2013-08-20 05:58:25'),
(20, 0, 0, 0, 0, 0, NULL, '', '', '', '', NULL, '', NULL, '2013-08-20 01:00:29', '2013-08-20 06:00:29'),
(21, 0, 0, 0, 0, 0, NULL, '123123123', '', '', '', NULL, '', NULL, '2013-08-20 01:12:47', '2013-08-20 06:12:47'),
(22, 1, 0, 1, 3, 3, 43534544.00, '', 'ggergergerg', '', '', 0, '12', '0000-00-00 00:00:00', '2013-08-20 01:19:22', '2013-08-20 06:19:22'),
(23, 3, 0, 1, 4, 3, 100000000.00, '', 'eggdfggdf', '', '', 0, '15', '0000-00-00 00:00:00', '2013-08-20 01:22:39', '2013-08-20 06:22:39'),
(24, 6, 0, 1, 2, 2, 43534544.00, '123456789', 'fgdgdfgfdg', '', '', 0, 'EA 65', '0000-00-00 00:00:00', '2013-08-20 01:34:06', '2013-08-24 20:26:51'),
(25, 0, 0, 0, 0, 0, NULL, '213123213', '', '', '', NULL, '', NULL, '2013-08-20 01:39:00', '2013-08-20 06:39:00'),
(26, 0, 0, 0, 0, 0, NULL, '565465446', '', '', '', NULL, '', NULL, '2013-08-20 01:45:47', '2013-08-20 06:45:47'),
(27, 0, 0, 0, 0, 0, NULL, '', '', '', '', NULL, '', NULL, '2013-08-20 01:54:11', '2013-08-20 06:54:11'),
(28, 2, 0, 1, 2, 3, 100000000.00, '7686786876', 'gergergerg', '', '', 0, '8', '0000-00-00 00:00:00', '2013-08-20 01:56:32', '2013-08-26 13:36:58'),
(29, 3, 0, 1, 2, 2, 100000000.00, '', 'fdfwefweef', '', '', 0, '15', '0000-00-00 00:00:00', '2013-08-20 02:00:18', '2013-08-20 07:00:18'),
(30, 2, 0, 1, 1, 2, 100000000.00, '', 'fsdfsdsdf', '', '', 0, '8', '0000-00-00 00:00:00', '2013-08-20 02:01:24', '2013-08-20 07:01:24'),
(31, 0, 0, 0, 0, 0, NULL, '31234324', '', '', '', NULL, '', NULL, '2013-08-20 02:07:54', '2013-08-20 07:07:54'),
(32, 0, 0, 0, 0, 0, NULL, '324234235', '', '', '', NULL, '', NULL, '2013-08-20 02:09:58', '2013-08-20 07:09:58'),
(33, 0, 0, 0, 0, 0, NULL, '3123123123', 'erwerwerwer', '', '', NULL, '', NULL, '2013-08-20 02:21:48', '2013-08-20 07:21:48'),
(34, 0, 0, 0, 0, 0, NULL, '23423423234', 'rewefwefweff', '', '', NULL, '', NULL, '2013-08-20 02:41:29', '2013-08-20 07:41:29'),
(35, 0, 0, 0, 0, 0, NULL, '23423423234', 'rewefwefweff', '', '', NULL, '', NULL, '2013-08-20 02:47:11', '2013-08-20 07:47:11'),
(36, 0, 0, 0, 0, 0, NULL, '', 'fggdfgdfgdf', '', '', NULL, '', NULL, '2013-08-20 02:48:22', '2013-08-20 07:48:22'),
(37, 0, 0, 0, 0, 0, NULL, '324234234234', 'fdgdfgdfgdfg', '', '', NULL, '', NULL, '2013-08-20 12:34:23', '2013-08-20 17:34:23'),
(38, 10, 0, 1, 2, 1, 100000000.00, '', 'gfdfgdfgdfgfd', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 12:35:26', '2013-08-20 17:35:26'),
(39, 10, 0, 1, 2, 1, 100000000.00, '', 'Prueba Contratante', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 12:40:26', '2013-08-20 17:40:26'),
(40, 10, 0, 1, 2, 1, 1234567936.00, '', 'Prueba asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 12:42:33', '2013-08-20 17:42:33'),
(41, 10, 0, 1, 2, 1, 1234567936.00, '', 'Prueba asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 12:43:40', '2013-08-20 17:43:40'),
(42, 10, 0, 1, 2, 1, 1234567936.00, '', 'Prueba asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 13:06:35', '2013-08-20 18:06:35'),
(43, 10, 0, 1, 2, 1, 1234567890.00, '', 'Prueba asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 13:18:22', '2013-08-20 18:18:22'),
(44, 10, 0, 1, 2, 1, 1234567890.00, '', 'Prueba asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 13:23:23', '2013-08-20 18:23:23'),
(45, 10, 0, 1, 2, 1, 1234567890.00, '', 'Prueba asegurados', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 13:27:45', '2013-08-20 18:27:45'),
(46, 10, 0, 1, 2, 1, 1234567890.00, '', 'Asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 13:41:35', '2013-08-20 18:41:35'),
(47, 10, 0, 1, 2, 1, 1234567890.00, '', 'Asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 13:53:20', '2013-08-20 18:53:20'),
(48, 10, 0, 1, 2, 1, 1234567890.00, '', 'Asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 13:54:15', '2013-08-20 18:54:15'),
(49, 10, 0, 1, 2, 1, 1234567890.00, '', 'Asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-08-20 14:08:54', '2013-08-20 19:08:54'),
(50, 0, 0, 0, 0, 0, NULL, '12345', 'Prueba asegurados', '', '', NULL, '', NULL, '2013-08-21 16:34:40', '2013-08-21 21:34:40'),
(51, 0, 0, 0, 0, 0, NULL, '12345', 'Prueba', '', '', NULL, '', NULL, '2013-08-21 16:35:06', '2013-08-21 21:35:06'),
(52, 0, 0, 0, 0, 0, NULL, '23423234', 'gffdgd', '', '', NULL, '', NULL, '2013-08-29 19:36:48', '2013-08-30 00:36:48'),
(53, 0, 0, 0, 0, 0, NULL, '00000105932388', '', '', '', NULL, '', NULL, '2013-09-02 13:12:24', '2013-09-02 18:12:24'),
(54, 0, 0, 0, 0, 0, NULL, '00000088771407', '', '', '', NULL, '', NULL, '2013-09-02 13:12:24', '2013-09-02 18:12:24'),
(55, 0, 0, 0, 0, 0, NULL, '105932388', '', '', '', NULL, '', NULL, '2013-09-02 18:13:21', '2013-09-02 23:13:21'),
(56, 0, 0, 0, 0, 0, NULL, '88771407', '', '', '', NULL, '', NULL, '2013-09-02 18:13:21', '2013-09-02 23:13:21'),
(57, 0, 3, 0, 0, 0, 273.94, '61309100', 'ELVIA MENDOZA BAUTISTA', '', '', NULL, '', NULL, '2013-09-05 14:59:53', '2013-09-05 19:59:53'),
(58, 1, 0, 1, 1, 4, 100000.00, '28230027', 'Prueba de asegurado', '', '', 0, '7', '0000-00-00 00:00:00', '2013-09-19 14:53:46', '2013-09-19 22:57:45'),
(59, 2, 0, 1, 2, 1, 200000.00, '123456789', 'Asegurado de prueba', '', '', 0, '7', '0000-00-00 00:00:00', '2013-09-19 20:43:22', '2014-01-02 02:11:09'),
(60, 10, 0, 1, 4, 3, 50000.00, '31212213', 'Prueba de asegurado', '', '', 0, 'EA 95', '0000-00-00 00:00:00', '2013-09-19 22:39:22', '2013-09-20 03:41:52'),
(61, 4, 0, 1, 2, 3, 300000.00, '', 'Prueba de asegurado', '', '', 0, '20', '0000-00-00 00:00:00', '2013-09-19 23:22:08', '2013-09-20 04:22:08'),
(62, 5, 0, 1, 3, 1, 35000.00, '', 'Test User', '', '', 0, 'EA 65', '0000-00-00 00:00:00', '2013-12-21 16:44:12', '2013-12-21 22:44:12'),
(63, 3, 0, 1, 2, 1, 24000.00, '123456', 'Juan Pérez González', '', '', 0, '10', '0000-00-00 00:00:00', '2013-12-23 19:47:47', '2014-01-02 02:13:05'),
(64, 0, 1, 0, 0, 0, 13224.35, '112594734', '', '', '', NULL, '', NULL, '2014-01-01 21:06:47', '2014-01-02 03:06:47'),
(65, 0, 1, 0, 0, 0, 2052.50, '120184734', '', '', '', NULL, '', NULL, '2014-01-01 21:06:48', '2014-01-02 03:06:48'),
(66, 0, 1, 0, 0, 0, 1518.14, '118945609', '', '', '', NULL, '', NULL, '2014-01-01 21:06:48', '2014-01-02 03:06:48'),
(67, 0, 1, 0, 0, 0, -13732.00, '120639539', '', '', '', NULL, '', NULL, '2014-01-01 21:06:48', '2014-01-02 03:06:48'),
(68, 0, 1, 0, 0, 0, 1225.00, '118292622', '', '', '', NULL, '', NULL, '2014-01-01 21:06:48', '2014-01-02 03:06:48'),
(69, 0, 1, 0, 0, 0, 4540.00, '121035588', '', '', '', NULL, '', NULL, '2014-01-01 21:06:48', '2014-01-02 03:06:48'),
(70, 0, 1, 0, 0, 0, 2519.19, '117047837', '', '', '', NULL, '', NULL, '2014-01-01 21:06:48', '2014-01-02 03:06:48'),
(71, 0, 1, 0, 0, 0, 3388.87, '121255343', '', '', '', NULL, '', NULL, '2014-01-01 21:06:48', '2014-01-02 03:06:48'),
(72, 0, 1, 0, 0, 0, 2222.36, '121138192', '', '', '', NULL, '', NULL, '2014-01-01 21:06:48', '2014-01-02 03:06:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `policies_vs_users`
--

CREATE TABLE IF NOT EXISTS `policies_vs_users` (
  `user_id` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `percentage` int(11) NOT NULL,
  `since` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `policies_vs_users`
--

INSERT INTO `policies_vs_users` (`user_id`, `policy_id`, `percentage`, `since`) VALUES
(2, 1, 100, '2013-08-02 01:10:42'),
(1, 2, 100, '2013-08-02 14:39:51'),
(1, 3, 100, '2013-08-02 23:48:19'),
(3, 4, 50, '2013-08-06 12:15:00'),
(7, 5, 10, '2013-08-06 21:09:01'),
(16, 6, 100, '2013-08-07 22:56:14'),
(34, 7, 100, '2013-08-08 08:17:21'),
(45, 8, 100, '2013-08-08 13:18:32'),
(57, 9, 100, '2013-08-09 17:02:40'),
(57, 10, 100, '2013-08-16 17:12:05'),
(55, 11, 100, '2013-08-19 19:18:55'),
(56, 12, 100, '2013-08-19 23:20:57'),
(54, 13, 100, '2013-08-19 23:57:24'),
(58, 14, 100, '2013-08-20 00:45:34'),
(58, 15, 100, '2013-08-20 00:47:49'),
(58, 16, 100, '2013-08-20 00:50:54'),
(58, 17, 100, '2013-08-20 00:52:11'),
(58, 18, 100, '2013-08-20 00:54:20'),
(58, 19, 100, '2013-08-20 00:58:25'),
(58, 20, 100, '2013-08-20 01:00:29'),
(58, 21, 100, '2013-08-20 01:12:47'),
(59, 22, 100, '2013-08-20 01:19:22'),
(59, 23, 100, '2013-08-20 01:22:39'),
(59, 24, 100, '2013-08-20 01:34:06'),
(59, 25, 100, '2013-08-20 01:39:00'),
(59, 26, 100, '2013-08-20 01:45:47'),
(59, 27, 100, '2013-08-20 01:54:11'),
(59, 28, 100, '2013-08-20 01:56:32'),
(59, 29, 100, '2013-08-20 02:00:18'),
(59, 30, 100, '2013-08-20 02:01:24'),
(59, 31, 100, '2013-08-20 02:07:54'),
(59, 32, 100, '2013-08-20 02:09:58'),
(59, 33, 100, '2013-08-20 02:21:48'),
(59, 34, 100, '2013-08-20 02:41:29'),
(59, 35, 100, '2013-08-20 02:47:11'),
(59, 36, 100, '2013-08-20 02:48:22'),
(59, 37, 100, '2013-08-20 12:34:23'),
(59, 38, 100, '2013-08-20 12:35:26'),
(59, 39, 100, '2013-08-20 12:40:26'),
(59, 40, 100, '2013-08-20 12:42:33'),
(59, 41, 100, '2013-08-20 12:43:40'),
(59, 42, 100, '2013-08-20 13:06:35'),
(59, 43, 100, '2013-08-20 13:18:22'),
(59, 44, 100, '2013-08-20 13:23:23'),
(59, 45, 100, '2013-08-20 13:27:45'),
(59, 46, 100, '2013-08-20 13:41:35'),
(59, 47, 100, '2013-08-20 13:53:20'),
(59, 48, 100, '2013-08-20 13:54:15'),
(59, 49, 100, '2013-08-20 14:08:54'),
(59, 50, 100, '2013-08-21 16:34:40'),
(59, 51, 100, '2013-08-21 16:35:06'),
(59, 52, 100, '2013-08-29 19:36:48'),
(65, 53, 100, '2013-09-02 13:12:24'),
(64, 54, 100, '2013-09-02 13:12:24'),
(65, 55, 100, '2013-09-02 18:13:21'),
(64, 56, 100, '2013-09-02 18:13:21'),
(55, 58, 100, '2013-09-19 14:53:46'),
(55, 59, 100, '2013-09-19 20:43:22'),
(55, 60, 100, '2013-09-19 22:39:22'),
(55, 61, 100, '2013-09-19 23:22:08'),
(59, 62, 100, '2013-12-21 16:44:12'),
(65, 63, 100, '2013-12-23 19:47:47'),
(62, 64, 100, '2014-01-01 21:06:47'),
(62, 65, 100, '2014-01-01 21:06:48'),
(62, 66, 100, '2014-01-01 21:06:48'),
(62, 67, 100, '2014-01-01 21:06:48'),
(62, 68, 100, '2014-01-01 21:06:48'),
(62, 69, 100, '2014-01-01 21:06:48'),
(62, 70, 100, '2014-01-01 21:06:48'),
(62, 71, 100, '2014-01-01 21:06:48'),
(62, 72, 100, '2014-01-01 21:06:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform_id` int(11) DEFAULT NULL,
  `product_group_id` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `period` varchar(20) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `platform_id`, `product_group_id`, `name`, `period`, `active`, `last_updated`, `date`) VALUES
(1, 1, 1, 'PROFESIONAL', '7-18', 1, '2013-07-23 12:54:01', '2013-07-23 12:54:01'),
(2, 1, 1, 'VIDA A TUS SUEÑOS', '7-9', 1, '2013-07-23 12:54:01', '2013-07-23 12:54:01'),
(3, 1, 1, 'TRASCIENDE', '5,10,15,20,EA 65', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(4, 1, 1, 'DOTAL', '10,15,20', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(5, 2, 1, 'VIDA INVERSIÓN', 'EA 65,VITALICIO', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(6, 2, 1, 'CONSOLIDA', 'EA 65', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(7, 2, 1, 'CONSOLIDA TOTAL', 'EA 65', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(8, 1, 1, 'PROYECTA AFECTO', '10,EA 55,EA 60,EA 65', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:00'),
(9, 1, 1, 'PROYECTA', 'EA 55,EA 60,EA 65', 1, '2013-07-23 12:54:02', '2013-08-07 13:10:41'),
(10, 2, 1, 'PRIVILEGIO UNIVERSAL', 'EA 95', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(11, 1, 1, 'PRIVILEGIO TEMPORAL', '10,20', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(12, 1, 1, 'PLATINO TEMPORAL', '10,20,EA 65', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(13, 1, 1, 'PLATINO UNIVERSAL', 'EA 95', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(14, 1, 1, 'ORDINARIO DE VIDA', 'EA100', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(15, 2, 1, 'VISION PLUS', '20,EA 65', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(16, 2, 1, 'ELIGE', '20,EA 65', 1, '2013-07-23 12:54:02', '2013-08-07 13:11:52'),
(17, 1, 2, 'Internacional', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(18, 1, 2, 'Vínculo Mundial', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(19, 1, 2, 'Premier 100', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(20, 1, 2, 'Premier 200', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(21, 1, 2, 'Premier 300', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(22, 1, 2, 'Premier 400', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(23, 1, 2, 'Conexión (cien mil)', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(24, 1, 2, 'VIP', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(25, 1, 2, 'Conexión (sin limite)', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(26, 1, 3, 'Seguro de Auto', '', 1, '2013-07-23 12:54:02', '2013-07-23 12:54:02'),
(27, 1, 2, 'Premium', NULL, 1, '2013-08-09 12:15:25', '2013-08-09 17:15:25'),
(28, 1, 2, 'Platino', NULL, 1, '2013-08-09 12:15:25', '2013-08-09 17:15:25'),
(29, 1, 2, 'Flexible Índigo', NULL, 1, '2013-08-09 12:16:32', '2013-08-09 17:16:32'),
(30, 1, 2, 'Flexible Ámbar', NULL, 1, '2013-08-09 12:16:32', '2013-08-09 17:16:32'),
(31, 1, 2, 'Flexible Cuarzo', NULL, 1, '2013-08-09 12:17:31', '2013-08-09 17:17:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products_vs_currencies`
--

CREATE TABLE IF NOT EXISTS `products_vs_currencies` (
  `product_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_group`
--

CREATE TABLE IF NOT EXISTS `product_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `product_group`
--

INSERT INTO `product_group` (`id`, `name`, `last_updated`, `date`) VALUES
(1, 'Vida', '2013-07-22 01:20:34', '2013-07-22 01:20:34'),
(2, 'GMM', '2013-07-22 01:20:35', '2013-07-28 01:29:58'),
(3, 'Auto', '2013-07-22 01:20:35', '2013-07-22 01:20:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representatives`
--

CREATE TABLE IF NOT EXISTS `representatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `lastnames` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `office_phone` varchar(13) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `office_ext` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `simulator`
--

CREATE TABLE IF NOT EXISTS `simulator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `product_group_id` int(11) NOT NULL,
  `data` text NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `simulator`
--

INSERT INTO `simulator` (`id`, `agent_id`, `product_group_id`, `data`, `last_updated`, `date`) VALUES
(9, 64, 2, '{"ramo":"2","userid":"62","agent_id":"64","saves":"1","ingresoBonoRenovacion":{"1":"0","2":"0","3":"0"},"periodo":"12","primasnetasiniciales":"630000","porAcotamiento":"80","primaspromedio":"23000","nonegocios":"28","simulatorPrimasPeriod":{"1":"182700","2":"195300","3":"252000"},"simulatorIngresosPeriod":{"1":"39505","2":"49554","3":"69820"},"noNegocios":{"1":"8","2":"9","3":"11"},"primasAfectasInicialesPagar":{"1":"146160","2":"156240","3":"201600"},"primasRenovacion":{"1":"110000","2":"120000","3":"130000"},"XAcotamiento":{"1":"80","2":"80","3":"80"},"primasRenovacionPagar":{"1":"88000","2":"96000","3":"104000"},"comisionVentaInicial":{"1":"11","2":"12","3":"13"},"ingresoComisionesVentaInicial":{"1":"20097","2":"23436","3":"32760"},"comisionVentaRenovacion":{"1":"11","2":"12","3":"13"},"ingresoComisionRenovacion":{"1":"12100","2":"14400","3":"16900"},"bonoAplicado":{"1":"5%","2":"7.5%","3":"10%"},"ingresoBonoProductividad":{"1":"7308","2":"11718","3":"20160"},"porsiniestridad":{"1":"68","2":"64","3":"60"},"porbonoganado":{"1":"0%","2":"1%","3":"2%"},"efectividad":"75","mes-1":"3","primas-solicitud-meta-1":"1","primas-negocios-meta-1":"1","primas-meta-1":"18900","mes-2":"9","primas-solicitud-meta-2":"3","primas-negocios-meta-2":"2","primas-meta-2":"56700","mes-3":"14","primas-solicitud-meta-3":"5","primas-negocios-meta-3":"4","primas-meta-3":"88200","mes-4":"3","primas-solicitud-meta-4":"1","primas-negocios-meta-4":"1","primas-meta-4":"18900","primas-solicitud-meta-primer":"10.666666666666666","primas-negocio-meta-primer":"8","primas-meta-primer":"182700","mes-5":"7","primas-solicitud-meta-5":"3","primas-negocios-meta-5":"2","primas-meta-5":"44100","mes-6":"13","primas-solicitud-meta-6":"5","primas-negocios-meta-6":"4","primas-meta-6":"81900","mes-7":"4","primas-solicitud-meta-7":"1","primas-negocios-meta-7":"1","primas-meta-7":"25200","mes-8":"7","primas-solicitud-meta-8":"3","primas-negocios-meta-8":"2","primas-meta-8":"44100","primas-solicitud-meta-second":"12","primas-negocio-meta-second":"9","primas-meta-second":"195300","mes-9":"12","primas-solicitud-meta-9":"4","primas-negocios-meta-9":"3","primas-meta-9":"75600","mes-10":"3","primas-solicitud-meta-10":"1","primas-negocios-meta-10":"1","primas-meta-10":"18900","mes-11":"5","primas-solicitud-meta-11":"1","primas-negocios-meta-11":"1","primas-meta-11":"31500","mes-12":"20","primas-solicitud-meta-12":"7","primas-negocios-meta-12":"5","primas-meta-12":"126000","primas-solicitud-meta-tercer":"13.333333333333332","primas-negocio-meta-tercer":"10","primas-meta-tercer":"252000","primas-solicitud-meta-total":"87","primas-negocios-meta-total":"65","primas-meta-total":"630000"}', '2014-01-12 18:09:17', '2014-01-13 07:40:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `simulator_default_estacionalidad`
--

CREATE TABLE IF NOT EXISTS `simulator_default_estacionalidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `vida` int(11) NOT NULL,
  `gmm` int(11) NOT NULL,
  `autos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `simulator_default_estacionalidad`
--

INSERT INTO `simulator_default_estacionalidad` (`id`, `month`, `vida`, `gmm`, `autos`) VALUES
(1, 'Enero', 5, 3, 3),
(2, 'Febrero', 7, 9, 9),
(3, 'Marzo', 14, 14, 14),
(4, 'Abril', 3, 3, 3),
(5, 'Mayo', 7, 7, 7),
(6, 'Junio', 13, 13, 13),
(7, 'Julio', 4, 4, 4),
(8, 'Agosto', 7, 7, 7),
(9, 'Septiembre', 2, 12, 12),
(10, 'Octubre', 3, 3, 3),
(11, 'Noviembre', 15, 5, 5),
(12, 'Diciembre', 20, 20, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sources`
--

CREATE TABLE IF NOT EXISTS `sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `label` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `office_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `company_name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `username` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `lastnames` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `disabled` tinyint(4) NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `office_id`, `manager_id`, `company_name`, `username`, `password`, `name`, `lastnames`, `birthdate`, `email`, `picture`, `disabled`, `last_updated`, `date`) VALUES
(1, 0, 0, '', 'administrador', 'dc0a37940f89f27fc31cd0c22f637dc9', 'Admin', 'ProAges', '0000-00-00', 'email@correo.com', 'photo_1.jpg', 1, '2013-12-23 19:11:55', '2013-12-24 01:11:55'),
(59, 0, 0, '', 'juanagente', 'dc0a37940f89f27fc31cd0c22f637dc9', 'Juan Agente', 'Campa', '0000-00-00', 'juan+agente@gmail.com', 'default.png', 1, '2013-09-16 20:52:56', '2013-09-17 01:52:56'),
(60, 0, 0, '', 'pepecoordinador', 'dc0a37940f89f27fc31cd0c22f637dc9', 'Pepe Coordinador', 'Lora', NULL, 'pepe+coordinador@gmail.com', 'default.png', 0, '2013-08-08 23:56:01', '2013-08-09 04:56:01'),
(61, 0, 0, 'Agentes Modernos SC', 'agentesmodernos', 'dc0a37940f89f27fc31cd0c22f637dc9', '', '', NULL, 'agentes@gmail.com', 'default.png', 0, '2013-08-08 23:56:01', '2013-08-09 04:56:01'),
(62, 0, 0, '', 'agentecancelado', '5f4dcc3b5aa765d61d8327deb882cf99', 'Agente', 'Cancelado', '0000-00-00', 'agente.cancelado@gmail.com', 'default.png', 1, '2014-01-15 16:21:30', '2014-01-15 22:21:30'),
(63, 0, 0, '', 'gerente', '5f4dcc3b5aa765d61d8327deb882cf99', 'Gerente', 'General', '0000-00-00', 'gerente@email.com', 'default.png', 1, '2013-08-08 23:58:00', '2013-08-09 04:58:00'),
(64, 0, 0, '', 'rbalassa', '5f4dcc3b5aa765d61d8327deb882cf99', 'Rodrigo', 'Balassa', '0000-00-00', 'rbalassa@gmail.com', 'default.png', 0, '2013-08-20 01:17:12', '2013-08-20 06:17:12'),
(65, 0, 63, '', 'A20130902', 'dc0a37940f89f27fc31cd0c22f637dc9', 'Nombre Agente 20130902', 'Apellido Agente 20130902', '0000-00-00', 'rbalassa+A20130902@gmail.com', 'default.png', 0, '2013-09-02 13:09:07', '2013-09-02 18:09:07'),
(66, 0, 0, '', 'gabino', '5f4dcc3b5aa765d61d8327deb882cf99', 'José´Gabino', 'Navarro Rivas', '1974-05-08', 'gabinon@hotmail.com', 'default.png', 1, '2013-12-23 18:21:17', '2013-12-24 00:21:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_vs_user_roles`
--

CREATE TABLE IF NOT EXISTS `users_vs_user_roles` (
  `user_id` int(11) NOT NULL,
  `user_role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users_vs_user_roles`
--

INSERT INTO `users_vs_user_roles` (`user_id`, `user_role_id`) VALUES
(60, 2),
(61, 1),
(63, 3),
(64, 1),
(65, 1),
(59, 1),
(66, 1),
(1, 5),
(62, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `label` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `user_roles`
--

INSERT INTO `user_roles` (`id`, `name`, `label`, `last_updated`, `date`) VALUES
(1, 'Agente', '', 1372810673, 1372810673),
(2, 'Coordinador', '', 1372810735, 1372810735),
(3, 'Gerente', '', 1372810751, 1372810751),
(4, 'Director', '', 1372810758, 1372810758),
(5, 'Administrador', '', 1391020503, 1372810768);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles_vs_access`
--

CREATE TABLE IF NOT EXISTS `user_roles_vs_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1266 ;

--
-- Volcado de datos para la tabla `user_roles_vs_access`
--

INSERT INTO `user_roles_vs_access` (`id`, `user_role_id`, `module_id`, `action_id`, `last_updated`, `date`) VALUES
(8, 4, 2, 1, '2013-07-15 22:44:39', '2013-07-15 22:44:39'),
(9, 4, 2, 5, '2013-07-15 22:44:39', '2013-07-15 22:44:39'),
(10, 4, 2, 11, '2013-07-15 22:44:39', '2013-07-15 22:44:39'),
(11, 3, 2, 1, '2013-07-15 22:44:39', '2013-07-15 22:44:39'),
(12, 3, 2, 5, '2013-07-15 22:44:39', '2013-07-15 22:44:39'),
(13, 3, 2, 11, '2013-07-15 22:44:39', '2013-07-15 22:44:39'),
(552, 1, 6, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(553, 1, 6, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(554, 1, 7, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1186, 5, 2, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1187, 5, 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1188, 5, 2, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1189, 5, 2, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1190, 5, 2, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1191, 5, 2, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1192, 5, 2, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1193, 5, 2, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1194, 5, 2, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1195, 5, 2, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1196, 5, 2, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1197, 5, 2, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1198, 5, 2, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1199, 5, 3, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1200, 5, 3, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1201, 5, 3, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1202, 5, 3, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1203, 5, 3, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1204, 5, 3, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1205, 5, 3, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1206, 5, 3, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1207, 5, 4, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1208, 5, 4, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1209, 5, 4, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1210, 5, 5, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1211, 5, 5, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1212, 5, 5, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1213, 5, 5, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1214, 5, 5, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1215, 5, 5, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1216, 5, 5, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1217, 5, 5, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1218, 5, 5, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1219, 5, 5, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1220, 5, 5, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1221, 5, 5, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1222, 5, 5, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1223, 5, 5, 14, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1224, 5, 6, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1225, 5, 6, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1226, 5, 6, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1227, 5, 6, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1228, 5, 6, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1229, 5, 6, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1230, 5, 6, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1231, 5, 6, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1232, 5, 6, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1233, 5, 6, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1234, 5, 6, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1235, 5, 6, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1236, 5, 6, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1237, 5, 6, 14, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1238, 5, 7, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1239, 5, 7, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1240, 5, 7, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1241, 5, 7, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1242, 5, 7, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1243, 5, 7, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1244, 5, 7, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1245, 5, 7, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1246, 5, 7, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1247, 5, 7, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1248, 5, 7, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1249, 5, 7, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1250, 5, 7, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1251, 5, 7, 14, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1252, 5, 8, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1253, 5, 8, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1254, 5, 8, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1255, 5, 8, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1256, 5, 8, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1257, 5, 8, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1258, 5, 8, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1259, 5, 8, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1260, 5, 8, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1261, 5, 8, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1262, 5, 8, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1263, 5, 8, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1264, 5, 8, 13, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1265, 5, 8, 14, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work_order`
--

CREATE TABLE IF NOT EXISTS `work_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `product_group_id` int(11) NOT NULL,
  `work_order_type_id` int(11) NOT NULL,
  `work_order_status_id` int(11) NOT NULL,
  `work_order_reason_id` int(11) NOT NULL,
  `work_order_responsible_id` int(11) NOT NULL,
  `uid` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `comments` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

--
-- Volcado de datos para la tabla `work_order`
--

INSERT INTO `work_order` (`id`, `user`, `policy_id`, `product_group_id`, `work_order_type_id`, `work_order_status_id`, `work_order_reason_id`, `work_order_responsible_id`, `uid`, `creation_date`, `comments`, `duration`, `last_updated`, `date`) VALUES
(1, 0, 1, 2, 91, 4, 6, 2, '987654321', '2013-08-06 21:19:04', 'asasdasd', 0, '0000-00-00 00:00:00', '2014-01-02 02:09:13'),
(2, 0, 2, 1, 49, 2, 3, 1, '2312423423', '2013-08-02 09:45:46', 'sdsdsdf', 0, '0000-00-00 00:00:00', '2013-08-21 22:04:54'),
(3, 0, 0, 1, 4, 2, 4, 2, '13423234', '2013-05-01 09:48:08', 'dfsdfsdfsdf', 0, '0000-00-00 00:00:00', '2013-08-07 02:20:08'),
(4, 0, 0, 1, 5, 2, 5, 2, '2342344', '2013-08-19 19:13:02', 'asdasdasd', 0, '0000-00-00 00:00:00', '2013-08-20 00:22:31'),
(5, 0, 3, 2, 92, 8, 0, 0, '', '2013-08-02 18:54:16', 'dsfsdfsd', 0, '0000-00-00 00:00:00', '2013-08-16 22:03:05'),
(6, 0, 4, 1, 48, 2, 3, 1, '324234234', '2013-08-23 08:03:37', 'dasdasd', 0, '0000-00-00 00:00:00', '2013-08-23 13:03:45'),
(7, 0, 5, 1, 49, 2, 4, 1, '', '2013-08-19 19:14:18', 'sdasdasd', 0, '0000-00-00 00:00:00', '2013-08-24 20:22:12'),
(8, 0, 0, 1, 34, 7, 2, 2, '0807V', '2013-08-19 19:14:38', 'sdafsdf', 0, '0000-00-00 00:00:00', '2014-01-02 02:09:33'),
(9, 0, 0, 1, 3, 2, 5, 2, '0807V', '0000-00-00 00:00:00', 'fsdfsdf', 0, '0000-00-00 00:00:00', '2013-08-24 20:23:01'),
(10, 0, 0, 1, 2, 5, 0, 0, '0807V103', '2013-08-07 08:09:01', 'no comments', 0, '2013-08-07 08:44:02', '2013-08-07 13:44:02'),
(11, 0, 0, 3, 110, 5, 0, 0, '0807A101', '2013-08-07 08:20:35', 'dfssdf', 0, '2013-08-07 08:17:14', '2013-08-07 13:17:14'),
(12, 0, 6, 1, 2, 5, 0, 0, '0807V001', '2013-08-01 23:02:36', 'Ningún comentario', 0, '2013-08-07 22:14:56', '2013-08-08 03:14:56'),
(13, 0, 7, 1, 2, 5, 0, 0, '0801V', '2013-08-01 08:23:45', 'dfsdsdfsdf', 0, '2013-08-08 08:21:17', '2013-08-08 13:21:17'),
(14, 0, 8, 3, 101, 5, 0, 0, '0808A', '2013-08-08 13:24:57', '', 0, '2013-08-08 13:32:18', '2013-08-08 18:32:18'),
(15, 0, 9, 3, 110, 5, 0, 0, '0809A', '2013-08-09 17:09:11', 'dfdsfsdfsdf', 0, '2013-08-09 17:40:02', '2013-08-09 22:40:02'),
(16, 0, 10, 1, 35, 9, 2, 3, '0816V12345', '2013-08-19 19:14:50', 'asdasdasdasdasdasd', 0, '2013-08-19 19:14:50', '2013-08-20 00:14:50'),
(17, 0, 11, 1, 3, 9, 2, 3, '0814V123456', '2013-08-19 23:18:19', 'sdfsdfsdf', 0, '2013-08-19 23:18:19', '2013-08-20 04:18:19'),
(18, 0, 12, 1, 48, 7, 1, 3, '0819V123456', '2013-08-19 23:21:37', 'NEGOCIO CON PAGO EN OT.\nAUTORIZACION 234\nNo DE COBRO 5434\n\nSE SOLICITA INFORME MEDICO POR INFORMACION EN LA OI POR ANTECEDENTES DE GASTRITIS', 0, '0000-00-00 00:00:00', '2013-09-02 23:04:34'),
(19, 0, 13, 1, 51, 7, 1, 3, '0820V5645456', '2013-08-20 00:04:00', 'asdasdasdasd', 0, '0000-00-00 00:00:00', '2013-12-24 23:38:47'),
(20, 0, 14, 1, 5, 9, 2, 2, '0820V123456', '2013-08-20 00:46:52', 'sdsdsd', 0, '2013-08-20 00:46:52', '2013-08-20 05:46:52'),
(21, 0, 15, 3, 104, 5, 0, 0, '0820A54645756', '2013-08-20 00:55:08', '', 0, '2013-08-20 00:49:47', '2013-08-20 05:49:47'),
(22, 0, 16, 2, 69, 5, 0, 0, '0820G785785674', '2013-08-20 00:58:13', '', 0, '2013-08-20 00:54:50', '2013-08-20 05:54:50'),
(23, 0, 17, 2, 66, 5, 0, 0, '0820G689789789', '2013-08-20 00:59:30', '', 0, '2013-08-20 00:11:52', '2013-08-20 05:11:52'),
(24, 0, 18, 3, 111, 5, 0, 0, '0820A5646456', '2013-08-20 01:01:40', 'fsdf', 0, '2013-08-20 00:20:54', '2013-08-20 05:20:54'),
(25, 0, 19, 3, 111, 5, 0, 0, '0820A5646456', '2013-08-20 01:01:40', 'fsdf', 0, '2013-08-20 00:25:58', '2013-08-20 05:25:58'),
(26, 0, 20, 3, 112, 5, 0, 0, '0820A435345345', '2013-08-20 01:07:49', '', 0, '2013-08-20 01:29:00', '2013-08-20 06:29:00'),
(27, 0, 21, 1, 5, 5, 0, 0, '0820V45345345435', '2013-08-20 01:20:06', '', 0, '2013-08-20 01:47:12', '2013-08-20 06:47:12'),
(28, 0, 22, 1, 48, 8, 2, 1, '0820V45435365456', '2013-08-21 16:50:11', 'esto falta: xyz', 0, '0000-00-00 00:00:00', '2013-08-21 21:58:20'),
(29, 0, 23, 1, 50, 2, 4, 1, '0820V23432423432', '2013-08-20 01:29:59', 'fgdfgdfg', 0, '0000-00-00 00:00:00', '2013-08-21 22:05:30'),
(30, 0, 24, 1, 50, 7, 1, 2, '0820V234234234234', '2013-08-24 15:25:10', 'asdasdasd', 0, '0000-00-00 00:00:00', '2013-08-24 20:26:51'),
(31, 0, 25, 1, 5, 8, 0, 0, '0820V234234234', '2013-08-20 01:46:20', '', 0, '0000-00-00 00:00:00', '2013-08-24 20:28:31'),
(32, 0, 26, 1, 36, 7, 1, 1, '0820V56456456', '2013-08-26 08:34:52', 'rgfdgdfg', 0, '0000-00-00 00:00:00', '2013-08-26 13:36:15'),
(33, 0, 27, 3, 112, 8, 0, 0, '0820A453345', '2013-08-20 02:01:31', '', 0, '0000-00-00 00:00:00', '2013-08-26 13:37:37'),
(34, 0, 28, 1, 49, 7, 0, 0, '0820V4543534534534', '2013-08-20 02:03:52', '', 0, '0000-00-00 00:00:00', '2013-08-26 13:36:58'),
(35, 0, 29, 1, 49, 2, 3, 2, '0820V234234234234', '2013-08-26 11:39:09', 'asdasdasddas', 0, '0000-00-00 00:00:00', '2013-09-20 04:58:22'),
(36, 0, 30, 1, 49, 8, 0, 0, '0820V234234234', '2013-08-20 02:08:43', '', 0, '0000-00-00 00:00:00', '2013-08-26 13:38:23'),
(37, 0, 31, 1, 37, 5, 0, 0, '0820V324234234234', '2013-08-20 02:15:14', '', 0, '2013-08-20 02:54:07', '2013-08-20 07:54:07'),
(38, 0, 32, 2, 89, 5, 0, 0, '0820G23423423423', '2013-08-20 02:17:18', 'dsfsdfsdfdsf', 0, '2013-08-20 02:58:09', '2013-08-20 07:58:09'),
(39, 0, 33, 1, 39, 9, 2, 2, '0820V234234234', '2013-08-20 02:30:22', 'qweqweqwe', 0, '2013-08-20 02:30:22', '2013-08-20 07:30:22'),
(40, 0, 34, 2, 89, 5, 0, 0, '0820G23423423', '2013-08-20 02:48:49', 'dfsdfsdfsdf', 0, '2013-08-20 02:29:41', '2013-08-20 07:29:41'),
(41, 0, 35, 2, 66, 5, 0, 0, '0820G5434534534453', '2013-08-20 02:54:31', 'dfsdfsdfsdf', 0, '2013-08-20 02:11:47', '2013-08-20 07:11:47'),
(42, 0, 36, 3, 110, 5, 0, 0, '0820A43543534576', '2013-08-20 02:55:42', 'dfgdfgdfg', 0, '2013-08-20 02:22:48', '2013-08-20 07:22:48'),
(43, 0, 37, 2, 88, 5, 0, 0, '0820G254435345', '2013-08-20 12:41:44', '', 0, '2013-08-20 12:23:34', '2013-08-20 17:23:34'),
(44, 0, 38, 1, 50, 8, 0, 0, '0820V546546567567', '2013-08-20 12:42:48', 'fgdfgdfg', 0, '0000-00-00 00:00:00', '2013-09-20 04:59:30'),
(45, 0, 39, 1, 50, 8, 0, 0, '0820V9876543210', '2013-08-20 12:47:48', 'Prueba comentarios', 0, '0000-00-00 00:00:00', '2013-08-24 20:29:16'),
(46, 0, 40, 1, 50, 8, 0, 0, '0820V98765432101', '2013-08-20 12:49:54', 'Prueba comentarios', 0, '0000-00-00 00:00:00', '2013-09-20 04:59:15'),
(47, 0, 41, 1, 50, 8, 0, 0, '0820V98765432102', '2013-08-20 12:51:01', 'Prueba comentarios', 0, '0000-00-00 00:00:00', '2013-09-20 04:59:41'),
(48, 0, 42, 1, 50, 8, 0, 0, '0820V98765432103', '2013-08-20 13:13:56', 'Prueba comentario', 0, '0000-00-00 00:00:00', '2013-09-20 04:59:51'),
(49, 0, 43, 1, 50, 8, 0, 0, '0820V98765432104', '2013-08-20 13:25:44', 'Prueba comentario', 0, '0000-00-00 00:00:00', '2013-09-20 05:00:34'),
(50, 0, 44, 1, 50, 8, 0, 0, '0820V98765432104', '2013-08-20 13:25:44', 'Prueba comentario', 0, '0000-00-00 00:00:00', '2013-09-20 05:02:32'),
(51, 0, 45, 1, 50, 8, 0, 0, '0820V98765432104', '2013-08-20 13:35:07', 'Prueba comentarios', 0, '0000-00-00 00:00:00', '2013-09-20 05:01:10'),
(52, 0, 46, 1, 50, 8, 0, 0, '0820V09890890', '2013-08-20 13:48:57', 'Comentario', 0, '0000-00-00 00:00:00', '2013-09-20 05:01:04'),
(53, 0, 47, 1, 50, 8, 0, 0, '0820V09890890', '2013-08-20 13:48:57', 'Comentario', 0, '0000-00-00 00:00:00', '2013-09-20 05:00:57'),
(54, 0, 48, 1, 50, 8, 0, 0, '0820V7868798909', '2013-08-20 14:01:37', 'Comentario', 0, '0000-00-00 00:00:00', '2013-09-20 05:00:24'),
(55, 0, 49, 1, 50, 8, 0, 0, '0820V7868798909', '2013-08-20 14:01:37', 'Comentario', 0, '0000-00-00 00:00:00', '2013-09-20 05:00:12'),
(56, 0, 50, 1, 3, 8, 0, 0, '0821V0001', '2013-08-21 16:42:07', 'asdasdasd', 0, '0000-00-00 00:00:00', '2013-08-24 20:29:44'),
(57, 0, 51, 3, 100, 5, 0, 0, '0821A0002', '2013-08-21 16:42:34', '', 0, '2013-08-21 16:06:35', '2013-08-21 21:06:35'),
(58, 1, 52, 1, 5, 5, 0, 0, '0829V35234', '2013-08-29 19:44:54', 'xczxczxc', 0, '2013-08-29 19:48:36', '2013-08-30 00:48:36'),
(59, 1, 58, 1, 48, 4, 0, 0, '0915V9875', '2013-09-15 15:03:31', '', 0, '0000-00-00 00:00:00', '2013-09-19 20:24:30'),
(60, 1, 59, 1, 48, 7, 0, 0, '0919V123456', '2013-09-19 20:53:07', '', 0, '0000-00-00 00:00:00', '2014-01-02 02:11:09'),
(61, 1, 60, 1, 49, 7, 0, 0, '0919V54353', '2013-09-19 22:49:08', '', 0, '0000-00-00 00:00:00', '2013-09-20 03:41:53'),
(62, 1, 61, 1, 50, 8, 0, 0, '0919V89798', '2013-09-19 23:31:55', '', 0, '0000-00-00 00:00:00', '2013-09-20 04:22:35'),
(63, 1, 62, 1, 48, 4, 0, 0, '1221V12345', '2013-12-20 17:01:18', '', 0, '2013-12-21 16:12:44', '2013-12-23 03:50:20'),
(64, 1, 63, 1, 48, 7, 0, 0, '1223V1235', '2013-12-22 20:05:03', '', 0, '0000-00-00 00:00:00', '2014-01-02 02:51:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work_order_history`
--

CREATE TABLE IF NOT EXISTS `work_order_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `field` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `original` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `new` text CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work_order_reason`
--

CREATE TABLE IF NOT EXISTS `work_order_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_group_id` int(11) NOT NULL,
  `work_order_status_id` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `work_order_reason`
--

INSERT INTO `work_order_reason` (`id`, `product_group_id`, `work_order_status_id`, `name`, `last_updated`, `date`) VALUES
(1, 1, 6, 'INFORMACION MEDICA ADICIONAL Y/O LABORAL', '2013-07-30 15:31:05', '2013-07-30 15:31:05'),
(2, 1, 6, 'SOLICITUD INCOMPLETA', '2013-07-30 15:31:05', '2013-07-30 15:31:05'),
(3, 1, 2, 'PETICION', '2013-07-30 15:52:59', '2013-07-30 15:52:59'),
(4, 1, 2, 'EXCEDE TIEMPO DE ESPERA ', '2013-07-30 15:52:59', '2013-07-30 15:52:59'),
(5, 1, 2, 'SUSTITUCION X OTRA OT ', '2013-07-30 15:52:59', '2013-07-30 15:52:59'),
(6, 2, 6, 'INFORMACION MEDICA ADICIONAL Y/O LABORAL', '2013-07-30 16:09:50', '2013-07-30 16:09:50'),
(7, 2, 6, 'SOLICITUD INCOMPLETA ', '2013-07-30 16:09:50', '2013-07-30 16:09:50'),
(8, 2, 2, 'A PETICION', '2013-07-30 16:10:33', '2013-07-30 16:10:33'),
(9, 2, 2, 'SE EXCEDE TIEMPO DE ESPERA ', '2013-07-30 16:10:33', '2013-07-30 16:10:33'),
(10, 2, 2, 'SUSTITUCION X OTRA OT ', '2013-07-30 16:10:33', '2013-07-30 16:10:33'),
(11, 3, 6, 'NO ACEPTA EL RIESGO', '2013-07-30 16:11:05', '2013-07-30 16:11:05'),
(12, 3, 6, 'SOLICITUD INCOMPLETA ', '2013-07-30 16:11:05', '2013-07-30 16:11:05'),
(13, 3, 2, 'A PETICION', '2013-07-30 16:11:27', '2013-07-30 16:11:27'),
(14, 3, 2, 'SOLICITUD INCOMPLETA ', '2013-07-30 16:11:27', '2013-07-30 16:11:27'),
(15, 3, 2, 'SUSTITUCION X OTRA OT ', '2013-07-30 16:11:27', '2013-07-30 16:11:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work_order_responsibles`
--

CREATE TABLE IF NOT EXISTS `work_order_responsibles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `work_order_responsibles`
--

INSERT INTO `work_order_responsibles` (`id`, `name`, `last_updated`, `date`) VALUES
(1, 'Agente', '2013-07-25 11:52:07', '2013-07-25 11:52:07'),
(2, 'DA', '2013-07-25 11:52:07', '2013-07-30 15:33:21'),
(3, 'GNP', '2013-07-25 11:52:07', '2013-07-25 11:52:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work_order_status`
--

CREATE TABLE IF NOT EXISTS `work_order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `work_order_status`
--

INSERT INTO `work_order_status` (`id`, `name`, `last_updated`, `date`) VALUES
(2, 'cancelada', '2013-07-25 10:44:12', '2013-07-25 10:44:12'),
(3, 'excedido', '2013-07-25 10:44:12', '2013-07-25 10:44:12'),
(4, 'pagada', '2013-07-25 10:44:12', '2013-07-25 10:44:12'),
(5, 'en trámite', '2013-07-25 10:44:12', '2013-08-20 06:46:54'),
(6, 'activada', '2013-07-28 01:15:34', '2013-07-28 01:15:34'),
(7, 'aceptado', '2013-07-28 12:24:42', '2013-07-28 12:24:42'),
(8, 'rechazado', '2013-07-28 12:24:42', '2013-07-28 12:24:42'),
(9, 'en trámite', '2013-08-16 17:01:49', '2013-08-26 13:34:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `work_order_types`
--

CREATE TABLE IF NOT EXISTS `work_order_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patent_id` int(11) NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `description` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

--
-- Volcado de datos para la tabla `work_order_types`
--

INSERT INTO `work_order_types` (`id`, `patent_id`, `name`, `description`, `duration`, `last_updated`, `date`) VALUES
(1, 1, 'MOVIMIENTO   A POLIZA', '', 0, '2013-07-22 01:43:00', '2013-07-22 02:14:47'),
(2, 1, 'MOVIMIENTO   A POLIZA CORRECCION FECHA NACIMI', '', 7, '2013-07-22 01:52:22', '2013-07-22 02:06:26'),
(3, 1, 'ALTA, RECONSIDERACION Y CANCELACION DE EXTRAP', '', 3, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(4, 1, 'CORRECCION FECHA NACIMIENTO ALTERANDO EDAD', '', 3, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(5, 1, 'CAMBIO DE COBERTURA', '', 3, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(6, 1, 'CAMBIO CONDUCTO DE COBRO', '', 3, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(7, 1, 'CAMBIO CONTRATANTE', '', 9, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(8, 1, 'CAMBIO DIRECCION', '', 9, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(9, 1, 'CAMBIO FORMA DE PAGO', '', 3, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(10, 1, 'CAMBIO OPCION DE LIQUIDACION SUMA ASEGURADA', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(11, 1, 'CAMBIO TELEFONO, FAX,CORREO ELECTRONICO', '', 3, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(12, 1, 'CAMBIO Y CORRECCION BENEFICARIOS', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(13, 1, 'CAMBIO FIDEICOMISO', '', 2, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(14, 1, 'CANCELACION DE BENEFICIOS ADICIONALES', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(15, 1, 'CONSTANCIA RETENCIO  DE IMPUESTOS', '', 9, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(16, 1, 'CORRECCION DE RFC', '', 3, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(17, 1, 'CORRECCION DEL NOMBRE DEL ASEGURADO', '', 2, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(18, 1, 'DEVOLUCION DE PRIMAS', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(19, 1, 'DISMINUCION DE S.A.', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(20, 1, 'INCLUSION DE BENEFICOS ADICIONALES', '', 9, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(21, 1, 'PAGO DE PRIMA COMPLEMENTANDO CON FONDO DE INV', '', 9, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(22, 1, 'PAGO PRIMA OTRO RAMO', '', 2, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(23, 1, 'PAGO PRIMANUEVO NEGOCIO CON FONDO DE INVERSIO', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(24, 1, 'PAGO PRIMA RENOVACION CON FONDO INVERSION', '', 12, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(25, 1, 'REEXPEDICIONES BASICAS', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(26, 1, 'REEXPEDICION CON MANTENIMIENTO', '', 9, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(27, 1, 'REHABILITACION CON CAMBIOS', '', 12, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(28, 1, 'REHABILITACION SIN CAMBIOS', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(29, 1, 'REVALORACION APLICACION EXTRAPRIMA', '', 12, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(30, 1, 'DES-PRORROGAR POLIZA', '', 12, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(31, 1, 'PRORROGAR POLIZA', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(32, 1, 'CESION DE COMISION', '', 7, '2013-07-22 01:52:22', '2013-07-22 01:52:22'),
(33, 1, 'MOVIMIENTO FINANCIERO', '', 0, '2013-07-22 01:55:01', '2013-07-22 02:07:31'),
(34, 33, 'MOVIMIENTO FINANCIERO	ACTUALIZACION DATOS BAN', '', 9, '2013-07-22 01:58:07', '2013-07-22 02:01:26'),
(35, 33, 'AHORRO PROGRAMADO EN EL CONDUCTO DE COBRO CAT', '', 7, '2013-07-22 01:58:07', '2013-08-20 19:08:40'),
(36, 33, 'AHORRO PROGRAMADO EN EL CONDUCTO DE COBRO DOM', '', 7, '2013-07-22 01:58:07', '2013-08-20 19:08:40'),
(37, 33, 'ALTA CONDUCTO DE COBRO ELECTRONICO', '', 7, '2013-07-22 01:58:07', '2013-07-22 02:01:34'),
(38, 33, 'CAMBIO DE CLABE', '', 7, '2013-07-22 01:58:07', '2013-07-22 02:01:37'),
(39, 33, 'CAMBIO TARJETA', '', 3, '2013-07-22 01:58:07', '2013-07-22 02:01:41'),
(40, 33, 'REACTIVACION COBRO ELECTRONICO', '', 7, '2013-07-22 01:58:07', '2013-07-22 02:01:44'),
(41, 33, 'RESCATE DE VALORES GARANTIZADOS', '', 7, '2013-07-22 01:58:07', '2013-07-22 02:01:47'),
(42, 33, 'RETIRO', '', 7, '2013-07-22 01:58:07', '2013-07-22 02:01:50'),
(43, 33, 'RETIRODE FONDO DE INVERSION', '', 7, '2013-07-22 01:58:07', '2013-07-22 02:01:53'),
(44, 33, 'PRESTAMO PARA PAGO DE PRIMA', '', 9, '2013-07-22 01:58:07', '2013-07-22 02:01:56'),
(45, 33, 'PRESTAMO PARA CHEQUE', '', 9, '2013-07-22 01:58:07', '2013-07-22 02:02:02'),
(46, 33, 'PRESTAMO POR MEDIO DE TRASFERENCIA BANCARIA', '', 9, '2013-07-22 01:58:07', '2013-07-22 02:02:04'),
(47, 1, 'NUEVO NEGOCIO', '', 0, '2013-07-22 01:58:24', '2013-07-22 12:11:47'),
(48, 47, 'RANGO   A', '', 5, '2013-07-22 01:59:34', '2013-07-22 02:02:13'),
(49, 47, 'RANGO   C', '', 8, '2013-07-22 01:59:34', '2013-07-22 02:02:15'),
(50, 47, 'RANGO   E', '', 10, '2013-07-22 01:59:34', '2013-07-22 02:02:16'),
(51, 47, 'RANGO   F', '', 10, '2013-07-22 01:59:34', '2013-07-22 02:02:18'),
(60, 2, 'MOVIMIENTO   A POLIZA  ', '', 0, '2013-07-22 02:09:00', '2013-07-22 02:09:00'),
(61, 60, 'MOVIMIENTO   A POLIZA	ALTA ASEGURADO', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(62, 60, 'ALTA RECIEN NACIDO', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(63, 60, 'ALTA RECONSIDERACION  DE EXTRAPRIMA', '', 7, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(64, 60, 'BAJA ASEGURADO', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(65, 60, 'BAJA CLAUSULA', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(66, 60, 'CAMBIO COBERTURA', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(67, 60, 'CAMBIO CONDUCTO COBRO', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(68, 60, 'CAMBIO CONTRATANTE PERSONA MORAL', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(69, 60, 'CAMBIO DIRECCION', '', 3, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(70, 60, 'CAMBIO FORMA PAGO MAYOR A MENOR', '', 7, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(71, 60, 'CAMBIO DE SUSTITUCION DE RIESGO ASEGURADO', '', 7, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(72, 60, 'CAMBIO TELEFONO, FAX,CORREO ELECTRONICO', '', 3, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(73, 60, 'CANCELACION DE POLIZA A PETICION', '', 1, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(74, 60, 'CORRECCION FECHA NACIMIENTO', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(75, 60, 'CORRECCION FECHA NACIMIENTO ALTERANDO EDAD', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(76, 60, 'CORRECCION DE RFC', '', 3, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(77, 60, 'CORRECCION DEL NOMBRE DEL ASEGURADO', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(78, 60, 'INCLUSION DE INTERMEDIARIO ADICIONAL', '', 7, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(79, 60, 'INCLUSION, CAMBIO CORRECCION DE BENEFICIARIOS', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(80, 60, 'MODIFICACION DE  CLAVE DE SEXO ASEGURADOS', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(81, 60, 'MODIFICAICON DE CONDICIONES', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(82, 60, 'MODIFICACION FECHA DE ANTIGÜEDAD DE GNP', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(83, 60, 'MODIFICACION PERIODOS DE ESPERA', '', 7, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(84, 60, 'REHABILITACION -A PETICION ASEGURADO', '', 10, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(85, 60, 'CON CAMBIO GRUPO A INDIVIDUAL', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(86, 60, 'CONVERSION DE CONEXIÓN A INDIVUDUAL', '', 9, '2013-07-22 02:14:12', '2013-07-22 02:14:12'),
(87, 2, 'MOVIMIENTO FINANCIERO', '', 0, '2013-07-22 02:14:38', '2013-07-22 02:14:38'),
(88, 87, 'CAMBIO CUENTA BANCARIA', '', 3, '2013-07-22 02:15:45', '2013-07-22 02:15:45'),
(89, 87, 'MODIFICACION DATOS BANCARIOS', '', 3, '2013-07-22 02:15:45', '2013-07-22 02:15:45'),
(90, 2, 'NUEVO NEGOCIO', '', 0, '2013-07-22 02:16:01', '2013-07-22 02:16:01'),
(91, 90, 'SIN CONSIDERAR ESPECIALES', '', 7, '2013-07-22 02:17:17', '2013-07-22 02:17:17'),
(92, 90, 'CON ELIMINACION PERIODOS ESPERA', '', 9, '2013-07-22 02:17:17', '2013-07-22 02:17:17'),
(93, 90, 'INFORMACION MEDICA ADICIONAL Y/O LABORAL', '', 0, '2013-07-22 02:17:17', '2013-07-22 02:17:17'),
(94, 90, 'SOLICITUD INCOMPLETA', '', 0, '2013-07-22 02:17:17', '2013-07-22 02:17:17'),
(99, 3, 'MOVIMIENTO A LA POLIZA', '', 0, '2013-07-22 02:19:31', '2013-07-22 02:19:31'),
(100, 99, 'Cambios de Forma de Pago (con Recibos Pagados', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(101, 99, 'Cambios de Coberturas (Disminución de Paquete', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(102, 99, 'Modificación de Clave de Marca y/o Modelo', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(103, 99, 'Cambio de Contratante  y modificación de dato', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(104, 99, 'Cambio de Contratante y Modicación de datos d', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(105, 99, 'Modificación del Número de Serie', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(106, 99, 'Cancelación de Póliza a Petición', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(107, 99, 'Cancelaciones por Pérdida Total', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(108, 99, 'Disminución de Sumas Aseguradas', '', 3, '2013-07-22 02:21:14', '2013-07-22 02:21:14'),
(109, 3, 'COTIZACION  Y EMISION  DE  VEHICULOS', '', 0, '2013-07-22 02:21:31', '2013-07-22 02:21:31'),
(110, 109, 'Vehículos Residentes con valor comercial mayo', '', 5, '2013-07-22 02:22:45', '2013-07-22 02:22:45'),
(111, 109, 'Vehículos Residentes último modelo a valor fa', '', 5, '2013-07-22 02:22:45', '2013-07-22 02:22:45'),
(112, 109, 'Vehículos Residentes para vehículos en Jalisc', '', 5, '2013-07-22 02:22:45', '2013-07-22 02:22:45'),
(113, 109, 'Vehículos Importados, Antiguos,  Clásicos y B', '', 5, '2013-07-22 02:22:45', '2013-07-22 02:22:45'),
(114, 109, 'Vehículos Nuevos sin tarifa', '', 5, '2013-07-22 02:22:45', '2013-07-22 02:22:45'),
(115, 3, 'FINANCIEROS', '', 0, '2013-07-22 02:23:07', '2013-07-22 02:23:07'),
(116, 115, 'CAMBIO TC  O DOMICILIACION', '', 3, '2013-07-22 02:23:30', '2013-07-22 02:23:30');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
