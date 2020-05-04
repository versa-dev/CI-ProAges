
CREATE TABLE IF NOT EXISTS `policy_negocio_pai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ramo` int(11) NOT NULL,
  `policy_number` varchar(45) NOT NULL,
  `negocio_pai` mediumint(9) NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY (`ramo`, `policy_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
