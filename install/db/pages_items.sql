CREATE TABLE IF NOT EXISTS `pages_items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `section` varchar(30) NOT NULL default '',
  `item_id` varchar(30) NOT NULL default '',
  `value` text NOT NULL,
  `last_changed` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `section` (`section`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;