
DROP TABLE IF EXISTS `blog_tags_map`;

CREATE TABLE `blog_tags_map` (
  `entry_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tag_id` int(11) unsigned NOT NULL DEFAULT '0',
  `datepost` int(10) unsigned NOT NULL DEFAULT '0',
  `filter_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`entry_id`,`tag_id`),
  KEY `article` (`entry_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `FK_blog_tags` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_blog_tags_map_posts` FOREIGN KEY (`entry_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;
