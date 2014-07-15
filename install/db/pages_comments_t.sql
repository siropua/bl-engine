CREATE TABLE IF NOT EXISTS `pages_comments_t` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `entry_id` int(10) unsigned NOT NULL default '0',
  `viewed` int(10) unsigned NOT NULL default '0',
  `viewed_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;