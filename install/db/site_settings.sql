CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` varchar(50) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  `int_value` int(11) NOT NULL default '0',
  `type` enum('text','string','int','bool') NOT NULL default 'text',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;