
ALTER TABLE `simulator_new` CHANGE `simulatorprimasprimertrimestre` `simulatorPrimasPeriod_1` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `simulator_new` CHANGE `simulatorprimassegundotrimestre` `simulatorPrimasPeriod_2` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `simulator_new` CHANGE `simulatorprimastercertrimestre` `simulatorPrimasPeriod_3` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `simulator_new` CHANGE `simulatorprimascuartotrimestre` `simulatorPrimasPeriod_4` FLOAT NOT NULL DEFAULT '0';