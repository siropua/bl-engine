CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `datepost` int(10) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `firm` varchar(255) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `answer` text NOT NULL,
  `answered` int(11) NOT NULL default '0',
  `ip` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;