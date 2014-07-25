SET FOREIGN_KEY_CHECKS=0;


CREATE TABLE `amnesia` (
  `id` int(11) DEFAULT NULL,
  `code` varchar(33) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `beer_drinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `beer_id` int(10) unsigned NOT NULL,
  `dateadd` date NOT NULL,
  `l` float unsigned NOT NULL,
  `descr` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beerday` (`beer_id`,`dateadd`),
  CONSTRAINT `FK_beer_drinks_beer` FOREIGN KEY (`beer_id`) REFERENCES `beers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `beer_factories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(50) NOT NULL,
  `city_id` int(10) unsigned NOT NULL,
  `beers` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_beer_factories_city` (`city_id`),
  CONSTRAINT `FK_beer_factories_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `beer_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `beers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateadd` int(10) unsigned NOT NULL,
  `factory_id` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `is_nf` tinyint(3) unsigned NOT NULL,
  `is_alive` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `basename` varchar(255) NOT NULL,
  `url` varchar(50) NOT NULL,
  `total_uses` int(10) unsigned NOT NULL,
  `total_litres` float unsigned NOT NULL,
  `last_used` date DEFAULT NULL,
  `file_bottle` varchar(50) NOT NULL,
  `file_label` varchar(50) NOT NULL,
  `file_backlabel` varchar(50) NOT NULL,
  `file_collar` varchar(50) NOT NULL,
  `file_cap` varchar(50) NOT NULL,
  `comments` int(10) unsigned NOT NULL,
  `last_comment` int(10) unsigned NOT NULL,
  `last_comment_uid` bigint(20) unsigned NOT NULL,
  `rating` int(11) NOT NULL,
  `description` text NOT NULL,
  `gravity` float unsigned NOT NULL,
  `abv` float unsigned NOT NULL,
  `barcode` varchar(20) NOT NULL,
  `is_full` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `factory` (`factory_id`),
  KEY `FK_beers_type` (`type_id`),
  CONSTRAINT `FK_beers_type` FOREIGN KEY (`type_id`) REFERENCES `beer_types` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_beers_factory` FOREIGN KEY (`factory_id`) REFERENCES `beer_factories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
  CONSTRAINT `FK_blog_comments_posts` FOREIGN KEY (`entry_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_blog_comments_owners` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `blog_comments_t` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_id` int(10) unsigned NOT NULL DEFAULT '0',
  `viewed` int(10) unsigned NOT NULL DEFAULT '0',
  `viewed_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `blog_favorites` (
  `user_id` bigint(10) unsigned NOT NULL DEFAULT '0',
  `post_id` bigint(10) unsigned NOT NULL DEFAULT '0',
  `dateadd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`post_id`),
  KEY `FK_blog_favorites_p` (`post_id`),
  CONSTRAINT `FK_blog_favorites_u` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_blog_favorites_p` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;
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
  CONSTRAINT `FK_blog_images_post` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `blog_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `owner_id` bigint(20) unsigned DEFAULT '0',
  `blog_id` int(11) unsigned DEFAULT '0',
  `dateadd` int(10) unsigned NOT NULL DEFAULT '0',
  `lastmodified` int(10) unsigned NOT NULL DEFAULT '0',
  `datepost` int(10) unsigned NOT NULL DEFAULT '0',
  `visible` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Определяет видимость. 1 - видим всем, 0 - видим только по ссылке, -1 не виден никому',
  `allow_comments` tinyint(1) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `last_comment` int(10) unsigned NOT NULL DEFAULT '0',
  `last_comment_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `preview` text NOT NULL,
  `text` longtext NOT NULL,
  `resume` text NOT NULL,
  `tags_cache` text NOT NULL,
  `have_cut` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `author_rating` int(11) DEFAULT NULL,
  `rating` float NOT NULL DEFAULT '0',
  `ref_clicks` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_total` int(11) NOT NULL DEFAULT '0',
  `rating_count` int(11) NOT NULL DEFAULT '0',
  `copyright_str` varchar(255) NOT NULL DEFAULT '',
  `source_url` varchar(255) NOT NULL DEFAULT '',
  `status` enum('posted','deferred','in_moderation','cancelled','deleted','day','draft') NOT NULL DEFAULT 'posted',
  `post_mode` enum('text','photo','video','import') NOT NULL DEFAULT 'text',
  `when_post` int(10) unsigned NOT NULL DEFAULT '0',
  `mainpic_id` int(10) unsigned DEFAULT NULL,
  `attached_pics` int(11) NOT NULL,
  `geo_lat` double NOT NULL DEFAULT '0',
  `geo_lng` double NOT NULL DEFAULT '0',
  `geo_address` varchar(255) NOT NULL DEFAULT '',
  `video_type` varchar(20) NOT NULL DEFAULT '',
  `video_id` varchar(50) NOT NULL DEFAULT '',
  `video_link` varchar(255) NOT NULL DEFAULT '',
  `pin_n` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `visible` (`visible`),
  KEY `status` (`status`),
  KEY `last_comment` (`last_comment`),
  KEY `blog_id` (`blog_id`),
  KEY `comments` (`comments`,`status`),
  KEY `list_post` (`status`,`blog_id`,`datepost`),
  KEY `datepost` (`datepost`,`status`),
  KEY `pin_n` (`pin_n`),
  KEY `FK_blog_posts_users` (`owner_id`),
  KEY `mainpic_id` (`mainpic_id`),
  KEY `random_pic` (`status`,`id`),
  CONSTRAINT `FK_blogs_posts` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_blog_posts_pic` FOREIGN KEY (`mainpic_id`) REFERENCES `blog_images` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_blog_posts_users` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `blog_posts_ext` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `ext_id` varchar(32) NOT NULL,
  `post_id` int(11) unsigned NOT NULL,
  `url_id` varchar(254) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `blog_posts_visits_map` (
  `visit_id` int(10) unsigned NOT NULL DEFAULT '0',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`visit_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `blog_posts_votes` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `vote` int(10) NOT NULL DEFAULT '0',
  `dateadd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;
CREATE TABLE `blog_sources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(32) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `search` varchar(255) NOT NULL DEFAULT '',
  `search_engine` varchar(20) NOT NULL DEFAULT '',
  `total_visits` int(10) unsigned NOT NULL DEFAULT '0',
  `first_visit` int(10) unsigned NOT NULL DEFAULT '0',
  `last_visit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `blog_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `datepost` int(10) unsigned NOT NULL DEFAULT '0',
  `creator_id` int(10) unsigned NOT NULL DEFAULT '0',
  `used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
CREATE TABLE `blog_visits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` int(10) unsigned NOT NULL DEFAULT '0',
  `visitor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dateadd` int(10) unsigned NOT NULL DEFAULT '0',
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `blog_id` int(10) unsigned NOT NULL DEFAULT '0',
  `datevisit` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `source_id` (`source_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;
CREATE TABLE `blog_visits_map` (
  `visit_id` int(10) unsigned NOT NULL DEFAULT '0',
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`visit_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
CREATE TABLE `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cities_country` (`country_id`),
  CONSTRAINT `FK_cities_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `feedbacks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datepost` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `firm` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `answer` text NOT NULL,
  `answered` int(11) NOT NULL DEFAULT '0',
  `ip` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `htl1m` (
  `dateadd` int(10) unsigned NOT NULL,
  `h` double unsigned NOT NULL,
  `t` double NOT NULL,
  `l` int(10) unsigned NOT NULL,
  `t2` double NOT NULL,
  `t3` double NOT NULL,
  `t4` double DEFAULT NULL,
  `p` double unsigned DEFAULT NULL,
  `gas` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`dateadd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `htl1s` (
  `dateadd` int(10) unsigned NOT NULL,
  `h` double unsigned NOT NULL,
  `t` double NOT NULL,
  `l` int(10) unsigned NOT NULL,
  `t2` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `ingress_map_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `map_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `dateadd` int(10) unsigned NOT NULL,
  `ordr` int(11) NOT NULL,
  `shape` enum('line','circle','marker','comment','user') NOT NULL,
  `shape_data` text NOT NULL,
  `geo_lat` double DEFAULT NULL,
  `geo_lng` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `ingress_maps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(15) NOT NULL,
  `dateadd` int(10) unsigned NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL,
  `views` int(10) unsigned NOT NULL,
  `comments` int(10) unsigned NOT NULL,
  `last_comment` int(10) unsigned NOT NULL,
  `last_comment_uid` int(10) unsigned NOT NULL,
  `visible` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `ingress_total_score` (
  `dateadd` int(10) unsigned NOT NULL,
  `resistance` bigint(20) NOT NULL,
  `alien` bigint(20) NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`dateadd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `menu_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `ordr` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(50) NOT NULL DEFAULT '',
  `dateadd` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `kws` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `text` longtext NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `last_comment` int(11) NOT NULL DEFAULT '0',
  `last_comment_uid` int(11) NOT NULL DEFAULT '0',
  `allow_comment` int(11) NOT NULL DEFAULT '0',
  `ordr` int(11) NOT NULL DEFAULT '0',
  `show_in_menu` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `pages_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_id` int(10) unsigned NOT NULL DEFAULT '0',
  `datepost` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
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
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `pages_comments_t` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `entry_id` int(10) unsigned NOT NULL DEFAULT '0',
  `viewed` int(10) unsigned NOT NULL DEFAULT '0',
  `viewed_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `pages_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(30) NOT NULL DEFAULT '',
  `item_id` varchar(30) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `last_changed` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `section` (`section`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `ref_landings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL DEFAULT '',
  `lands_count` int(10) unsigned NOT NULL DEFAULT '0',
  `first_land` int(10) unsigned NOT NULL DEFAULT '0',
  `last_land` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `ref_sources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(32) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `search` varchar(255) NOT NULL DEFAULT '',
  `search_engine` varchar(20) NOT NULL DEFAULT '',
  `total_visits` int(10) unsigned NOT NULL DEFAULT '0',
  `first_visit` int(10) unsigned NOT NULL DEFAULT '0',
  `last_visit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `ref_visits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` int(10) unsigned NOT NULL DEFAULT '0',
  `visitor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dateadd` int(10) unsigned NOT NULL DEFAULT '0',
  `datevisit` date NOT NULL DEFAULT '0000-00-00',
  `ip` int(11) unsigned NOT NULL DEFAULT '0',
  `visits` int(10) unsigned NOT NULL DEFAULT '1',
  `land_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `visitor_ip` (`source_id`,`datevisit`,`ip`,`land_id`),
  KEY `source_id` (`source_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `siprem_checkins_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `venue_id` varchar(50) NOT NULL,
  `dateadd` int(10) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `texts` text NOT NULL,
  `hours` tinyint(3) unsigned NOT NULL,
  `autocheckins` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `last_checkin` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_venue` (`user_id`,`venue_id`),
  KEY `active_in_hour` (`hours`,`status`),
  KEY `byuser` (`user_id`),
  CONSTRAINT `FK_siprem_checkins_cron_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `site_settings` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `int_value` int(11) NOT NULL DEFAULT '0',
  `type` enum('text','string','int','bool') NOT NULL DEFAULT 'text',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `social_networks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(10) NOT NULL,
  `domain` varchar(50) NOT NULL,
  `client_id` varchar(255) DEFAULT NULL,
  `client_secret` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Социальные сети';
CREATE TABLE `stat_agents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;
CREATE TABLE `static_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(50) NOT NULL DEFAULT '',
  `dateadd` int(10) unsigned NOT NULL DEFAULT '0',
  `last_modified` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `kws` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `text` longtext NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `last_comment` int(11) NOT NULL DEFAULT '0',
  `last_comment_uid` int(11) NOT NULL DEFAULT '0',
  `allow_comment` int(11) NOT NULL DEFAULT '0',
  `ordr` int(11) NOT NULL DEFAULT '0',
  `show_in_menu` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `datepost` int(10) unsigned NOT NULL DEFAULT '0',
  `creator_id` int(10) unsigned NOT NULL DEFAULT '0',
  `used` int(11) NOT NULL DEFAULT '0',
  `is_main` int(11) NOT NULL DEFAULT '0',
  `ordr` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `ordr` (`ordr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `acc_type_id` int(10) unsigned DEFAULT NULL,
  `nick` varchar(50) NOT NULL DEFAULT '',
  `full_name` varchar(150) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  `login` varchar(200) NOT NULL DEFAULT '',
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
  `premium_till` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `users_email_changes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `dateadd` int(10) unsigned NOT NULL,
  `old_email` varchar(50) NOT NULL,
  `new_email` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `changed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_changed` (`user_id`,`changed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `users_external` (
  `user_id` bigint(10) unsigned NOT NULL,
  `network_id` int(10) unsigned NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `client_secret` varchar(255) NOT NULL,
  `last_update` int(10) unsigned NOT NULL,
  `last_login` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `userpic` varchar(255) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`network_id`),
  UNIQUE KEY `auth_as_client` (`network_id`,`client_id`),
  KEY `FK_users_external_ntws` (`network_id`),
  CONSTRAINT `FK_users_external_ntws` FOREIGN KEY (`network_id`) REFERENCES `social_networks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_users_external_uid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ключи к внешним сетям';
CREATE TABLE `users_info` (
  `id` bigint(10) unsigned NOT NULL DEFAULT '0',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `about` text NOT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `icq` int(10) unsigned NOT NULL DEFAULT '0',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `skype` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_users_info` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `users_stats` (
  `id` bigint(20) unsigned NOT NULL,
  `blog_posts` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_users_stats` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



SET FOREIGN_KEY_CHECKS=1;
