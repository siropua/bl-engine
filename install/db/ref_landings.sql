CREATE TABLE IF NOT EXISTS `ref_landings` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uri` varchar(255) NOT NULL default '',
  `lands_count` int(10) unsigned NOT NULL default '0',
  `first_land` int(10) unsigned NOT NULL default '0',
  `last_land` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;