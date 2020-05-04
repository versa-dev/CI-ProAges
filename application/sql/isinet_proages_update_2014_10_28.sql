ALTER TABLE `agents` ADD INDEX (`user_id`);
ALTER TABLE `policies_vs_users` ADD INDEX (`policy_id`);
ALTER TABLE `agent_uids` ADD INDEX (`agent_id`);
ALTER TABLE `extra_payment` ADD INDEX (`x_payment_interval`);
