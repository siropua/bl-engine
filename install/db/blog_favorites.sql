DROP TABLE IF EXISTS `blog_favorites`;

CREATE TABLE `blog_favorites` (
  `user_id` bigint(10) unsigned NOT NULL DEFAULT '0',
  `post_id` bigint(10) unsigned NOT NULL DEFAULT '0',
  `dateadd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`post_id`),
  KEY `FK_blog_favorites_p` (`post_id`),
  CONSTRAINT `FK_blog_favorites_p` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_blog_favorites_u` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;
