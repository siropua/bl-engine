CREATE TABLE IF NOT EXISTS `users_info` (
  `id` int(10) unsigned NOT NULL default '0',
  `birthday` date NOT NULL default '0000-00-00',
  `about` text NOT NULL,
  `email` varchar(50) NOT NULL default '',
  `icq` int(10) unsigned NOT NULL default '0',
  `phone` varchar(255) NOT NULL default '',
  `skype` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;