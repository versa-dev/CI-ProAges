ALTER TABLE `payments` DROP `id`;
ALTER TABLE  `payments` CHANGE  `policy_id`  `agent_id` INT( 11 ) NOT NULL;
ALTER TABLE `payments`
  DROP PRIMARY KEY,
   ADD PRIMARY KEY(
     `agent_id`,
     `amount`,
     `payment_date`,
     `policy_number`);
ALTER TABLE  `payments` ADD  `product_group` INT NOT NULL FIRST;
ALTER TABLE  `payments` CHANGE  `amount`  `amount` FLOAT( 11 ) NOT NULL;
ALTER TABLE  `payments` CHANGE  `product_group`  `product_group` INT( 2 ) NOT NULL;
