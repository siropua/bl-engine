CREATE TABLE IF NOT EXISTS `ref_sources` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hash` varchar(32) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `search` varchar(255) NOT NULL default '',
  `search_engine` varchar(20) NOT NULL default '',
  `total_visits` int(10) unsigned NOT NULL default '0',
  `first_visit` int(10) unsigned NOT NULL default '0',
  `last_visit` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;