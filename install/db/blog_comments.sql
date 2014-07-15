
DROP TABLE IF EXISTS `blog_comments`;

CREATE TABLE `blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) unsigned DEFAULT '0',
  `entry_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `datepost` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `parent_id` bigint(20) unsigned DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `blocked` tinyint(4) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `approved` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `entry_id` (`entry_id`),
  KEY `sort` (`sort`),
  KEY `level` (`level`),
  KEY `FK_blog_comments_owners` (`owner_id`),
  CONSTRAINT `FK_blog_comments_owners` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_blog_comments_posts` FOREIGN KEY (`entry_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;