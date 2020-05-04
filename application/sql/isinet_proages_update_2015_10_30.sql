
CREATE TABLE IF NOT EXISTS `policy_adjusted_primas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) NOT NULL,
  `adjusted_prima` decimal(20,2) DEFAULT 0,
  `due_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`policy_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
