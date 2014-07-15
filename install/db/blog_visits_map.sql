CREATE TABLE IF NOT EXISTS `blog_visits_map` (
  `visit_id` int(10) unsigned NOT NULL default '0',
  `item_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`visit_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;