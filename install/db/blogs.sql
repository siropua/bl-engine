
DROP TABLE IF EXISTS `blogs`;

CREATE TABLE `blogs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `url` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `custom_css` varchar(30) NOT NULL DEFAULT '',
  `feedburner` varchar(255) NOT NULL DEFAULT '',
  `list_id` int(10) unsigned NOT NULL DEFAULT '0',
  `only_domain` varchar(255) NOT NULL DEFAULT '',
  `posts` int(10) unsigned NOT NULL DEFAULT '0',
  `ordr` int(11) NOT NULL DEFAULT '0',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(30) NOT NULL DEFAULT '',
  `thumb` varchar(50) NOT NULL DEFAULT '',
  `main_tag_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_default` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`,`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
