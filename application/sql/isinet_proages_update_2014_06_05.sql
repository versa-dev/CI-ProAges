ALTER TABLE `payments` ADD `imported_folio` varchar( 45 ) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL Default '' AFTER `date` ,
ADD `imported_agent_name` varchar( 310 ) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL Default '' AFTER `date` ,
ADD `import_date` DATE NOT NULL Default '0000-00-00' AFTER `date` ;
