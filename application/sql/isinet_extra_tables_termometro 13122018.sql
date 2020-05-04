CREATE TABLE `tex_congreso` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT(5) NULL,
  `gen` VARCHAR(45) NULL,
  `congreso_name` VARCHAR(45) NULL,
  `min_amount` FLOAT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_congreso` (`id`, `year`, `gen`, `congreso_name`, `min_amount`) VALUES ('1', 2018, 'G1,G2,G3,Consolidado', 'No', '0');
INSERT INTO `tex_congreso` (`id`, `year`, `gen`, `congreso_name`, `min_amount`) VALUES ('2', 2018, 'G3,Consolidado', 'Oro', '1100000');
INSERT INTO `tex_congreso` (`id`, `year`, `gen`, `congreso_name`, `min_amount`) VALUES ('3', 2018, 'G1,G2,G3,Consolidado', 'Platino', '1700000');
INSERT INTO `tex_congreso` (`id`, `year`, `gen`, `congreso_name`, `min_amount`) VALUES ('4', 2018, 'G1,G2,G3,Consolidado', 'Diamante', '2700000');
INSERT INTO `tex_congreso` (`id`, `year`, `gen`, `congreso_name`, `min_amount`) VALUES ('5', 2018, 'G1,G2,G3,Consolidado', 'Consejo', '4900000');
INSERT INTO `tex_congreso` (`id`, `year`, `gen`, `congreso_name`, `min_amount`) VALUES ('6', 2018, 'G1,G2', 'Oro', '900000');
  
  

  CREATE TABLE `tex_puntos_integralidad` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT(5) NULL,
  `amount` FLOAT NULL,
  `points` INT(5) NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_puntos_integralidad` (`id`, `year`, `amount`, `points`) VALUES ('1', 2018, '120000', '1');
INSERT INTO `tex_puntos_integralidad` (`id`, `year`, `amount`, `points`) VALUES ('2', 2018, '180000', '1');
INSERT INTO `tex_puntos_integralidad` (`id`, `year`, `amount`, `points`) VALUES ('3', 2018, '240000', '2');
INSERT INTO `tex_puntos_integralidad` (`id`, `year`, `amount`, `points`) VALUES ('4', 2018, '300000', '2');
INSERT INTO `tex_puntos_integralidad` (`id`, `year`, `amount`, `points`) VALUES ('5', 2018, '410000', '3');
INSERT INTO `tex_puntos_integralidad` (`id`, `year`, `amount`, `points`) VALUES ('6', 2018, '550000', '3');
INSERT INTO `tex_puntos_integralidad` (`id`, `year`, `amount`, `points`) VALUES ('7', 2018, '740000', '3');

  
  CREATE TABLE `tex_requisitos_conservacion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT(5) NULL,
  `generacion` VARCHAR(45) NULL,
  `min_perc` FLOAT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_requisitos_conservacion` (`id`, `year`, `generacion`, `min_perc`) VALUES ('1', 2018, 'G1', '0');
INSERT INTO `tex_requisitos_conservacion` (`id`, `year`, `generacion`, `min_perc`) VALUES ('2', 2018, 'G2', '0.8');
INSERT INTO `tex_requisitos_conservacion` (`id`, `year`, `generacion`, `min_perc`) VALUES ('3', 2018, 'G3', '0.9');
INSERT INTO `tex_requisitos_conservacion` (`id`, `year`, `generacion`, `min_perc`) VALUES ('4', 2018, 'G4', '0.9');
INSERT INTO `tex_requisitos_conservacion` (`id`, `year`, `generacion`, `min_perc`) VALUES ('5', 2018, 'Consolidado', '0.9');

  
  CREATE TABLE `tex_cartera_value` (
  `id` INT NOT NULL,
  `year` INT(5) NULL,
  `perc` FLOAT NULL,
  `result` INT(3) NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_cartera_value` (`id`, `year`, `perc`, `result`) VALUES ('1', 2018, '0.9', '2');
INSERT INTO `tex_cartera_value` (`id`, `year`, `perc`, `result`) VALUES ('2', 2018, '0.93', '3');
INSERT INTO `tex_cartera_value` (`id`, `year`, `perc`, `result`) VALUES ('3', 2018, '0.95', '4');


  CREATE TABLE `tex_cartera_bonus` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT(5) NULL,
  `perc` FLOAT NULL,
  `amount` FLOAT NULL,
  `bonus_perc` FLOAT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('1', 2018, '0.9', '0', '0');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('2', 2018, '0.9', '120000', '0.02');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('3', 2018, '0.9', '155000', '0.03');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('4', 2018, '0.9', '210000', '0.04');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('5', 2018, '0.9', '270000', '0.05');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('6', 2018, '0.9', '355000', '0.07');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('7', 2018, '0.9', '490000', '0.08');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('8', 2018, '0.9', '640000', '0.09');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('9', 2018, '0.93', '0', '0');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('10', 2018, '0.93', '120000', '0.03');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('11', 2018, '0.93', '155000', '0.04');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('12', 2018, '0.93', '210000', '0.05');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('13', 2018, '0.93', '270000', '0.06');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('14', 2018, '0.93', '355000', '0.09');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('15', 2018, '0.93', '490000', '0.1');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('16', 2018, '0.93', '640000', '0.11');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('17', 2018, '0.95', '0', '0');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('18', 2018, '0.95', '120000', '0.04');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('19', 2018, '0.95', '155000', '0.05');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('20', 2018, '0.95', '210000', '0.06');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('21', 2018, '0.95', '270000', '0.07');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('22', 2018, '0.95', '355000', '0.1');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('23', 2018, '0.95', '490000', '0.11');
INSERT INTO `tex_cartera_bonus` (`id`, `year`, `perc`, `amount`, `bonus_perc`) VALUES ('24', 2018, '0.95', '640000', '0.12');

  
  CREATE TABLE `tex_bono_nuevos_negocios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT(5) NULL,
  `negocios` INT(3) NULL,
  `type` VARCHAR(45) NULL,
  `amount` FLOAT NULL,
  `bonus_perc` FLOAT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('1', 2018, '3', 'Consolidado', '120000', '0.05');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('2', 2018, '3', 'Consolidado', '1280000', '0.06');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('3', 2018, '3', 'Consolidado', '240000', '0.07');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('4', 2018, '3', 'Consolidado', '300000', '0.08');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('5', 2018, '3', 'Consolidado', '410000', '0.11');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('6', 2018, '3', 'Consolidado', '550000', '0.13');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('7', 2018, '3', 'Consolidado', '740000', '0.15');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('8', 2018, '5', 'Noveles,Consolidado', '120000', '0.1');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('9', 2018, '5', 'Noveles,Consolidado', '1280000', '0.13');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('10', 2018, '5', 'Noveles,Consolidado', '240000', '0.16');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('11', 2018, '5', 'Noveles,Consolidado', '300000', '0.19');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('12', 2018, '5', 'Noveles,Consolidado', '410000', '0.26');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('13', 2018, '5', 'Noveles,Consolidado', '550000', '0.28');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('14', 2018, '5', 'Noveles,Consolidado', '740000', '0.3');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('15', 2018, '7', 'Noveles,Consolidado', '120000', '0.15');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('16', 2018, '7', 'Noveles,Consolidado', '1280000', '175');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('17', 2018, '7', 'Noveles,Consolidado', '240000', '0.2');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('18', 2018, '7', 'Noveles,Consolidado', '300000', '225');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('19', 2018, '7', 'Noveles,Consolidado', '410000', '0.3');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('20', 2018, '7', 'Noveles,Consolidado', '550000', '325');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('21', 2018, '7', 'Noveles,Consolidado', '740000', '0.35');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('22', 2018, '9', 'Noveles,Consolidado', '120000', '175');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('23', 2018, '9', 'Noveles,Consolidado', '1280000', '0.2');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('24', 2018, '9', 'Noveles,Consolidado', '240000', '225');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('25', 2018, '9', 'Noveles,Consolidado', '300000', '0.25');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('26', 2018, '9', 'Noveles,Consolidado', '410000', '325');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('27', 2018, '9', 'Noveles,Consolidado', '550000', '0.36');
INSERT INTO `tex_bono_nuevos_negocios` (`id`, `year`, `negocios`, `type`, `amount`, `bonus_perc`) VALUES ('28', 2018, '9', 'Noveles,Consolidado', '740000', '0.4');


  CREATE TABLE `tex_bono_integral` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT(5) NULL,
  `neg_amount` INT(4) NULL,
  `perc` FLOAT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_bono_integral` (`id`, `year`, `neg_amount`, `perc`) VALUES ('1', 2018, '0', '0');
INSERT INTO `tex_bono_integral` (`id`, `year`, `neg_amount`, `perc`) VALUES ('2', 2018, '6', '0.1');
INSERT INTO `tex_bono_integral` (`id`, `year`, `neg_amount`, `perc`) VALUES ('3', 2018, '11', '0.13');
INSERT INTO `tex_bono_integral` (`id`, `year`, `neg_amount`, `perc`) VALUES ('4', 2018, '16', '0.15');
INSERT INTO `tex_bono_integral` (`id`, `year`, `neg_amount`, `perc`) VALUES ('5', 2018, '21', '0.18');
INSERT INTO `tex_bono_integral` (`id`, `year`, `neg_amount`, `perc`) VALUES ('6', 2018, '26', '0.2');
INSERT INTO `tex_bono_integral` (`id`, `year`, `neg_amount`, `perc`) VALUES ('7', 2018, '31', '0.25');


  CREATE TABLE `tex_noveles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT(5) NULL,
  `amount` INT NULL,
  `res` INT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_noveles` (`id`, `year`, `amount`, `res`) VALUES ('1', 2018, '5', '2');
INSERT INTO `tex_noveles` (`id`, `year`, `amount`, `res`) VALUES ('2', 2018, '7', '3');
INSERT INTO `tex_noveles` (`id`, `year`, `amount`, `res`) VALUES ('3', 2018, '9', '4');


  CREATE TABLE `tex_consolidados` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT(5) NULL,
  `amount` INT NULL,
  `res` INT NULL,
  PRIMARY KEY (`id`));

INSERT INTO `tex_consolidados` (`id`, `year`, `amount`, `res`) VALUES ('1', 2018, '3', '2');
INSERT INTO `tex_consolidados` (`id`, `year`, `amount`, `res`) VALUES ('2', 2018, '5', '3');
INSERT INTO `tex_consolidados` (`id`, `year`, `amount`, `res`) VALUES ('3', 2018, '7', '4');
INSERT INTO `tex_consolidados` (`id`, `year`, `amount`, `res`) VALUES ('4', 2018, '9', '5');

CREATE TABLE `tex_bono_negocios_nuevos` (
  `id` INT NOT NULL,
  `year` INT(5) NULL,
  `type` VARCHAR(45) NULL,
  `neg_amount` INT NULL,
  `amount` FLOAT NULL,
  `bonus_perc` FLOAT NULL,
  PRIMARY KEY (`id`));
  
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (1, 2018, 'Consolidado', 3, 120000, 0.05);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (2, 2018, 'Consolidado', 3, 180000, 0.06);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (3, 2018, 'Consolidado', 3, 240000, 0.07);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (4, 2018, 'Consolidado', 3, 300000, 0.08);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (5, 2018, 'Consolidado', 3, 410000, 0.11);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (6, 2018, 'Consolidado', 3, 550000, 0.13);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (7, 2018, 'Consolidado', 3, 740000, 0.15);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (8, 2018, 'Consolidado, Noveles', 5, 120000, 0.1);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (9, 2018, 'Consolidado, Noveles', 5, 180000, 0.13);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (10, 2018, 'Consolidado, Noveles', 5, 240000, 0.16);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (11, 2018, 'Consolidado, Noveles', 5, 300000, 0.19);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (12, 2018, 'Consolidado, Noveles', 5, 410000, 0.26);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (13, 2018, 'Consolidado, Noveles', 5, 550000, 0.28);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (14, 2018, 'Consolidado, Noveles', 5, 740000, 0.3);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (15, 2018, 'Consolidado, Noveles', 7, 120000, 0.15);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (16, 2018, 'Consolidado, Noveles', 7, 180000, 0.175);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (17, 2018, 'Consolidado, Noveles', 7, 240000, 0.2);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (18, 2018, 'Consolidado, Noveles', 7, 300000, 0.225);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (19, 2018, 'Consolidado, Noveles', 7, 410000, 0.3);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (20, 2018, 'Consolidado, Noveles', 7, 550000, 0.325);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (21, 2018, 'Consolidado, Noveles', 7, 740000, 0.35);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (22, 2018, 'Consolidado, Noveles', 9, 120000, 0.175);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (23, 2018, 'Consolidado, Noveles', 9, 180000, 0.2);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (24, 2018, 'Consolidado, Noveles', 9, 240000, 0.225);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (25, 2018, 'Consolidado, Noveles', 9, 300000, 0.25);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (26, 2018, 'Consolidado, Noveles', 9, 410000, 0.325);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (27, 2018, 'Consolidado, Noveles', 9, 550000, 0.36);
INSERT INTO `tex_bono_negocios_nuevos` (`id`, `year`, `type`, `neg_amount`, `amount`, `bonus_perc`) VALUES (28, 2018, 'Consolidado, Noveles', 9, 740000, 0.4);

CREATE TABLE `tex_puntos_standing` (
  `id` INT NOT NULL,
  `year` INT NULL,
  `gen` VARCHAR(45) NULL,
  `min_amount` FLOAT NULL,
  `points` FLOAT NULL,
  PRIMARY KEY (`id`));

INSERT INTO `tex_puntos_standing` (`id`, `year`, `gen`, `min_amount`, `points`) VALUES ('1', '2018', 'all', '400000', '1');
INSERT INTO `tex_puntos_standing` (`id`, `year`, `gen`, `min_amount`, `points`) VALUES ('2', '2018', 'all', '1050000', '1.5');
INSERT INTO `tex_puntos_standing` (`id`, `year`, `gen`, `min_amount`, `points`) VALUES ('3', '2018', 'all', '1800000', '2');
INSERT INTO `tex_puntos_standing` (`id`, `year`, `gen`, `min_amount`, `points`) VALUES ('4', '2018', 'all', '4700000', '3');
INSERT INTO `tex_puntos_standing` (`id`, `year`, `gen`, `min_amount`, `points`) VALUES ('5', '2018', 'G1', '900000', '1');
INSERT INTO `tex_puntos_standing` (`id`, `year`, `gen`, `min_amount`, `points`) VALUES ('6', '2018', 'G1', '1700000', '1.5');
INSERT INTO `tex_puntos_standing` (`id`, `year`, `gen`, `min_amount`, `points`) VALUES ('7', '2018', 'G1', '2700000', '2');
INSERT INTO `tex_puntos_standing` (`id`, `year`, `gen`, `min_amount`, `points`) VALUES ('8', '2018', 'G1', '4900000', '3');



