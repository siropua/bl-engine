
DROP TABLE IF EXISTS `blog_images`;

CREATE TABLE `blog_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(10) unsigned NOT NULL,
  `dateadd` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `ordr` int(11) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `visible` tinyint(4) NOT NULL,
  `text` text,
  PRIMARY KEY (`id`),
  KEY `FK_blog_images_bp` (`post_id`),
  CONSTRAINT `FK_blog_images_bp` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
