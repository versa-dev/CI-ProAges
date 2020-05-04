ALTER TABLE `policy_adjusted_primas` 
ADD COLUMN `adjusted_allocated_prime` DECIMAL(20,2) NULL DEFAULT '0.00' AFTER `adjusted_prima`,
ADD COLUMN `adjusted_bonus_prime` DECIMAL(20,2) NULL DEFAULT '0.00' AFTER `adjusted_allocated_prime`;
