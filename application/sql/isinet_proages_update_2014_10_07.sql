ALTER TABLE `work_order` CHANGE `last_updated` `last_updated` DATETIME NOT NULL;
ALTER TABLE `work_order` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `policies` CHANGE `last_updated` `last_updated` DATETIME NOT NULL;
ALTER TABLE `policies` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

