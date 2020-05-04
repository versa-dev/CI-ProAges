ALTER TABLE `policy_negocio_pai` DROP `id`;
ALTER TABLE policy_negocio_pai DROP INDEX ramo;
ALTER TABLE `policy_negocio_pai` ADD UNIQUE(`policy_number`);