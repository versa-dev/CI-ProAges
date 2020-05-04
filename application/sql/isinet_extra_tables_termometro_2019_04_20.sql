CREATE TABLE `t_pai_data` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `array_data` VARCHAR(2048) NULL DEFAULT NULL,
  `data_type` VARCHAR(45) NULL DEFAULT NULL,
  `data_product` VARCHAR(45) NULL DEFAULT NULL,
  `year` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`));

INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('1', '{\"oro_g1\":700000,\"oro_g2\":900000,\"oro_g3-4\":1100000,\"plat\":1700000,\"diam\":2700000,\"con\":4900000}', 'congreso', 'vida', '2018');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('2', '{\"oro_g1\":750000,\"oro_g2\":950000,\"oro_g3-4\":1150000,\"plat\":1800000,\"diam\":2850000,\"con\":5100000}', 'congreso', 'vida', '2019');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('3', '{\"oro_g1\":700000,\"oro_g2\":900000,\"oro_g3-4\":1100000,\"plat\":1700000,\"diam\":2700000,\"con\":4900000}', 'congreso', 'gmm', '2018');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('4', '{\"oro_g1\":750000,\"oro_g2\":950000,\"oro_g3-4\":1150000,\"plat\":1800000,\"diam\":2850000,\"con\":5100000}', 'congreso', 'gmm', '2019');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('5', '[120000,155000,210000,270000,355000,490000,640000]', 'perce_bono_cartera', 'vida', '2018');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('6', '[130000,160000,220000,290000,380000,530000,690000]', 'perce_bono_cartera', 'vida', '2019');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('7', '[1900000]', 'prima_club_elite', 'vida', '2018');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('8', '[2000000]', 'prima_club_elite', 'vida', '2019');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('9', '[120000,180000,240000,300000,410000,550000,740000]', 'bono_1st_year_perc', 'vida', '2018');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('10', '[130000,190000,250000,320000,440000,590000,790000]', 'bono_1st_year_perc', 'vida', '2019');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('11', '[97500,157500,232500,322500,435000]', 'bono_1st_year_perc', 'gmm', '2018');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('12', '[65000,105000,155000,215000,290000]', 'bono_1st_year_perc', 'gmm', '2019');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('13', '{\"individual\":[400000,1050000,1800000,4700000],\"agrupados\":[500000,1100000,1900000,4900000]}', 'ptos_standing', 'vida', '2018');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('14', '{\"individual\":[440000,1100000,1900000,4900000],\"agrupados\":[540000,1150000,2000000,5100000]}', 'ptos_standing', 'vida', '2019');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('15', '[0,160000,245000,345000,465000,610000] ', 'bono_rentabilidad', 'gmm', '2018');
INSERT INTO `t_pai_data` (`id`, `array_data`, `data_type`, `data_product`, `year`) VALUES ('16', '[0,170000,260000,370000,500000,660000]', 'bono_rentabilidad', 'gmm', '2019');


