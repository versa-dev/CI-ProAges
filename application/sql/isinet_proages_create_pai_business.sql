CREATE TABLE `pai_business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ramo` int(11) DEFAULT NULL,
  `policy_number` varchar(45) DEFAULT NULL,
  `pai` int(11) DEFAULT NULL,
  `date_pai` datetime DEFAULT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)