
INSERT INTO `modules` (`name`, `label`, `last_updated`, `date`) VALUES
('Settings', 'Configuración', '2014-11-11', 1388663270);

CREATE TABLE `settings` (
  `key` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `value` text collate utf8_bin NOT NULL,
  `name` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `rank` tinyint NOT NULL,
  `form_type`  varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `form_validation`  varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `tooltip` text collate utf8_bin NOT NULL,
  PRIMARY KEY  (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`key`, `value`, `name`, `rank`, `form_type`,  `form_validation`, `tooltip`) VALUES
('logo', 'logo20.png', 'Logo', 1, 'image', 'gif|jpg|png,50,100,150', 'Tamaño max: 50k, anchura max: 100px, altura max: 150px'),
('email_sender', 'info+proages@isinet.mx', 'Correo electrónico del remitente', 2, 'email', 'trim|valid_email|required', ''),
('company_name', 'Proages', 'Nombre de la empresa', 3, 'text', 'trim|xss_clean', ''),
('days_yellow', '5', 'Número de días para marcar un OT amarillo', 4, 'number', 'trim|is_natural', ''),
('days_red', '10', 'Número de días para marcar un OT rojo', 5, 'number', 'trim|is_natural', '');


