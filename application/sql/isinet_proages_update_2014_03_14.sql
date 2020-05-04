ALTER TABLE `policies` CHANGE `payment_method_id` `payment_int_bck_id` int(11) NOT NULL;
ALTER TABLE `policies` CHANGE `payment_interval_id` `payment_method_id` int(11) NOT NULL;
ALTER TABLE `policies` CHANGE `payment_int_bck_id` `payment_interval_id` int(11) NOT NULL;

