CREATE TABLE `users_email_changes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `dateadd` INT(10) UNSIGNED NOT NULL,
  `old_email` VARCHAR(50) NOT NULL,
  `new_email` VARCHAR(50) NOT NULL,
  `code` VARCHAR(50) NOT NULL,
  `changed` TINYINT(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id_changed` (`user_id`,`changed`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;