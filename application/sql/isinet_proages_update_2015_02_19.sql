DROP TABLE simulator_new;
CREATE TABLE IF NOT EXISTS `simulator_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` int(11) NOT NULL DEFAULT '0',
  `agent_id` int(11) NOT NULL,
  `ramo` int(11) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `year` year(4) NOT NULL,

  `comisionVentaInicial_1` float NOT NULL DEFAULT '0',
  `comisionVentaInicial_2` float NOT NULL DEFAULT '0',
  `comisionVentaInicial_3` float NOT NULL DEFAULT '0',
  `comisionVentaInicial_4` float NOT NULL DEFAULT '0',

  `comisionVentaRenovacion_1` float NOT NULL DEFAULT '0',
  `comisionVentaRenovacion_2` float NOT NULL DEFAULT '0',
  `comisionVentaRenovacion_3` float NOT NULL DEFAULT '0',
  `comisionVentaRenovacion_4` float NOT NULL DEFAULT '0',

  `noNegocios_1` smallint(5) unsigned NOT NULL DEFAULT '0',
  `noNegocios_2` smallint(5) unsigned NOT NULL DEFAULT '0',
  `noNegocios_3` smallint(5) unsigned NOT NULL DEFAULT '0',
  `noNegocios_4` smallint(5) unsigned NOT NULL DEFAULT '0',

  `periodo` smallint(5) unsigned NOT NULL DEFAULT '0',

  `porcentajeConservacion_1` varchar(6) NOT NULL DEFAULT '0',
  `porcentajeConservacion_2` varchar(6) NOT NULL DEFAULT '0',
  `porcentajeConservacion_3` varchar(6) NOT NULL DEFAULT '0',
  `porcentajeConservacion_4` varchar(6) NOT NULL DEFAULT '0',

  `porsiniestridad_1` float NOT NULL DEFAULT '0', -- not for vida
  `porsiniestridad_2` float NOT NULL DEFAULT '0', -- not for vida
  `porsiniestridad_3` float NOT NULL DEFAULT '0', -- not for vida
  `porsiniestridad_4` float NOT NULL DEFAULT '0', -- not for vida

  `primasRenovacion_1` float NOT NULL DEFAULT '0',
  `primasRenovacion_2` float NOT NULL DEFAULT '0',
  `primasRenovacion_3` float NOT NULL DEFAULT '0',
  `primasRenovacion_4` float NOT NULL DEFAULT '0',

  `simulatorprimascuartotrimestre` float NOT NULL DEFAULT '0',
  `simulatorprimasprimertrimestre` float NOT NULL DEFAULT '0',
  `simulatorprimassegundotrimestre` float NOT NULL DEFAULT '0',
  `simulatorprimastercertrimestre` float NOT NULL DEFAULT '0',

  `XAcotamiento_1` float NOT NULL DEFAULT '0',
  `XAcotamiento_2` float NOT NULL DEFAULT '0',
  `XAcotamiento_3` float NOT NULL DEFAULT '0',
  `XAcotamiento_4` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ramo` (`ramo`),
  KEY `agent_id` (`agent_id`),
  KEY `year` (`year`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
