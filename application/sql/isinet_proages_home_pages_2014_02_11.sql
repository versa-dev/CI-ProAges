--
-- Add priority field to table `user_roles`
--
ALTER TABLE `user_roles` ADD `home_page_priority` int(11) NOT NULL DEFAULT '5' AFTER `x_home_page`;
UPDATE `user_roles` SET `home_page_priority` = '1' WHERE `user_roles`.`id` =4;
UPDATE `user_roles` SET `home_page_priority` = '2' WHERE `user_roles`.`id` =3;
UPDATE `user_roles` SET `home_page_priority` = '3' WHERE `user_roles`.`id` =1;
UPDATE `user_roles` SET `home_page_priority` = '4' WHERE `user_roles`.`id` =2;


