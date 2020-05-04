ALTER TABLE payments 
ADD COLUMN `allocated_prime` FLOAT NULL DEFAULT NULL AFTER `agent_generation_gmm`,
ADD COLUMN `bonus_prime` FLOAT NULL DEFAULT NULL AFTER `allocated_prime`;
