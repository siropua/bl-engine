CREATE TABLE IF NOT EXISTS `blog_visits` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `source_id` int(10) unsigned NOT NULL default '0',
  `visitor_id` int(10) unsigned NOT NULL default '0',
  `dateadd` int(10) unsigned NOT NULL default '0',
  `post_id` int(10) unsigned NOT NULL default '0',
  `blog_id` int(10) unsigned NOT NULL default '0',
  `datevisit` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`),
  KEY `source_id` (`source_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;