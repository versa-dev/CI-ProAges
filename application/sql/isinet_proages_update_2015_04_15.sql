
ALTER TABLE `payments` DROP PRIMARY KEY;
ALTER TABLE `payments` ADD `pay_tbl_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
ALTER TABLE `payments` ADD INDEX `pay_key` (`agent_id`, `amount`, `payment_date`, `policy_number`) COMMENT '';
