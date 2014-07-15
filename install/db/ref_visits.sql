CREATE TABLE IF NOT EXISTS `ref_visits` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `source_id` int(10) unsigned NOT NULL default '0',
  `visitor_id` int(10) unsigned NOT NULL default '0',
  `dateadd` int(10) unsigned NOT NULL default '0',
  `datevisit` date NOT NULL default '0000-00-00',
  `ip` int(11) unsigned NOT NULL default '0',
  `visits` int(10) unsigned NOT NULL default '1',
  `land_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `visitor_ip` (`source_id`,`datevisit`,`ip`,`land_id`),
  KEY `source_id` (`source_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;