CREATE TABLE IF NOT EXISTS `blog_posts_votes` (
  `post_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `vote` int(10) NOT NULL default '0',
  `dateadd` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`post_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;