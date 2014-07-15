
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) NOT NULL DEFAULT '',
  `full_name` varchar(150) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  `login` varchar(200) NOT NULL default '',
  `email_confirmed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `datereg` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` int(11) unsigned NOT NULL DEFAULT '0',
  `userpic` varchar(255) NOT NULL DEFAULT '',
  `rights` text NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `lastpage` varchar(250) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `last_online` int(10) unsigned NOT NULL DEFAULT '0',
  `salt` varchar(10) NOT NULL DEFAULT '',
  `invite_id` int(10) unsigned NOT NULL DEFAULT '0',
  `invite_from` int(10) unsigned NOT NULL DEFAULT '0',
  `agent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gender` enum('u','m','f') NOT NULL DEFAULT 'u',
  `has_mainpic` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;