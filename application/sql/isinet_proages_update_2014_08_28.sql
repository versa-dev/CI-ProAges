INSERT INTO `user_role_home_pages` (`page_id`, `page_name`, `uri_segments`, `last_updated`, `date`) VALUES
(10, 'Perfil de agente', 'agent/index', '2014-08-28 10:07:01', '2014-08-28 10:07:01');

UPDATE `new_pro-ages_2001`.`user_roles` SET `x_home_page` = '10' WHERE `user_roles`.`id` = 1;