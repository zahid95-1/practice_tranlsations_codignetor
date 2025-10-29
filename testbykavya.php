



CREATE VIEW MDV_Registered_Account AS select `u`.`user_id` AS `user_id`,`u`.`unique_id` AS `unique_id`,`u`.`parent_id` AS `parent_id`,`u`.`wallet_balance` AS `wallet_balance`,`u`.`manager_name` AS `manager_name`,`u`.`username` AS `username`,`u`.`first_name` AS `first_name`,`u`.`last_name` AS `last_name`,`u`.`email` AS `email`,`u`.`role` AS `role`,`u`.`mobile` AS `mobile`,`u`.`gender` AS `gender`,`c`.`name` AS `name`,`c`.`nicename` AS `nicename`,`u`.`created_datetime` AS `created_datetime` from (`forex`.`users` `u` join `forex`.`country` `c` on(`u`.`country_id` = `c`.`id`)) where `u`.`role` = 1 ORDER BY created_datetime DESC