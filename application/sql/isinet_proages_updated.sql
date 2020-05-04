ALTER TABLE `policies` ADD `prima` FLOAT(10,2) AFTER `payment_method_id`;

/**
 *	Table for temporal data
 **/
CREATE TABLE `payments_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Add field product_group_id table policies*/
ALTER TABLE `policies` ADD product_group_id INT NULL AFTER product_id;

/*Add field year_prime table payments*/
ALTER TABLE `payments` ADD `year_prime` INT NOT NULL AFTER `policy_id`;

/*Activity module*/
CREATE TABLE `agents_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `begin` date DEFAULT  NULL,
  `end` date DEFAULT  NULL,
  `cita` int DEFAULT 0 NULL,
  `prospectus` int DEFAULT 0 NULL,
  `interview` int DEFAULT 0 NULL,  
  `comments` text character set utf8 collate utf8_spanish_ci DEFAULT '' NULL,  
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


/*SIMULATOR TABLES*/
CREATE TABLE `simulator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period` int not null,
  `agent_id` int not null,
  `product_group_id` int not null,
  `data` text not null,
  `last_updated` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `simulator_default_estacionalidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(20) character set utf8 collate utf8_spanish_ci not null,
  `vida` int not null,
  `gmm` int not null,
  `autos` int not null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO simulator_default_estacionalidad values
( null, 'Enero', '5', '3', '3' ),
( null, 'Febrero', '7', '9', '9' ),
( null, 'Marzo', '14', '14', '14' ),
( null, 'Abril', '3', '3', '3' ),
( null, 'Mayo', '7', '7', '7' ),
( null, 'Junio', '13', '13', '13' ),
( null, 'Julio', '4', '4', '4' ),
( null, 'Agosto', '7', '7', '7' ),
( null, 'Septiembre', '2', '12', '12' ),
( null, 'Octubre', '3', '3', '3' ),
( null, 'Noviembre', '15', '5', '5' ),
( null, 'Diciembre', '20', '20', '20' );


ALTER TABLE policies CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `agents_activity`  ADD `vida_requests` INT NULL AFTER `interview`,  ADD `vida_businesses` INT NULL AFTER `vida_requests`,  ADD `gmm_requests` INT NULL AFTER `vida_businesses`,  ADD `gmm_businesses` INT NULL AFTER `gmm_requests`,  ADD `autos_businesses` INT NULL AFTER `gmm_businesses`;
