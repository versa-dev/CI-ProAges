--
-- Table structure for table `user_role_home_pages`
--

CREATE TABLE IF NOT EXISTS `user_role_home_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `uri_segments` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `user_role_home_pages`
--

INSERT INTO `user_role_home_pages` (`page_id`, `page_name`, `uri_segments`, `last_updated`, `date`) VALUES
(1, 'Predeterminada', '', '2014-02-06 16:07:01', '2014-02-06 16:07:01'),
(2, 'Usuarios', 'usuarios', '2014-02-06 16:07:01', '2014-02-06 16:07:01'),
(3, 'Módulos', 'modulos', '2014-02-06 16:07:01', '2014-02-06 16:07:01'),
(4, 'Rol', 'roles', '2014-02-06 16:07:01', '2014-02-06 16:07:01'),
(5, 'Orden trabajo', 'ot', '2014-02-06 16:07:01', '2014-02-06 16:07:01'),
(6, 'Importar Pagos', 'ot/import_payments', '2014-02-06 16:07:01', '2014-02-06 16:07:01'),
(7, 'Reporte resultados', 'ot/reporte', '2014-02-06 16:07:01', '2014-02-06 16:07:01'),
(8, 'Mis actividades', 'activities', '2014-02-06 16:07:01', '2014-02-06 16:07:01'),
(9, 'Reporte actividades', 'activities/report', '2014-02-06 16:07:01', '2014-02-06 16:07:01');

--
-- Add home page field to table `user_roles`
--
ALTER TABLE `user_roles` ADD `x_home_page` int(11) NOT NULL DEFAULT '1' AFTER `label`;
