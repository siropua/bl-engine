CREATE TABLE IF NOT EXISTS `blog_posts_ext` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `ext_id` varchar(32) NOT NULL,
  `post_id` int(11) unsigned NOT NULL,
  `url_id` varchar(254) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;