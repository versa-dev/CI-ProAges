UPDATE settings SET `value` = '10001' WHERE (`key` = 'payment_import_max');

ALTER TABLE `proages`.`payments` 
ADD COLUMN `agent_clave` VARCHAR(45) NULL DEFAULT NULL AFTER `bonus_prime`,
ADD COLUMN `insured_name` VARCHAR(45) NULL DEFAULT NULL AFTER `agent_clave`,
ADD COLUMN `plan_type` VARCHAR(45) NULL DEFAULT NULL AFTER `insured_name`,
ADD COLUMN `deducible_type` VARCHAR(45) NULL DEFAULT NULL AFTER `plan_type`;

