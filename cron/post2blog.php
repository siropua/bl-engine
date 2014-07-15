#!/usr/bin/php

<?php

if(!@include('../../configs/main.php')) exit;

require_once(LIB_PATH.'/init.php');

if($ids = $rBlog->publicDeferred()){
	$blogs = $rBlog->getBlogsList();

	$twitter = NULL;
	if (defined('TWITTER_USER_ID') && 
			$db->selectCell('SHOW COLUMNS FROM ?# LIKE ?s', $rBlog->getSetting('posts_table'), 'make_twit')){
		require_once('rlib/rTwitter.class.php');
		$twitter = new rTwitter($db, TWITTER_USER_ID);
		
	}
	
	foreach($blogs as $b)
		$rBlog->saveRSS(ROOT.'/xml/'.$b['url'].'.xml', $b['name'], $b['description'], SERVER_URL);
		
	foreach($ids as $id){
	/**Постинг в LJ*/
	/**Проверка наличия файла с логином и паролем для LiveJournal*/
		if(@include_once(ROOT.'/config/livejournal.blog.php')) {
			$photo_p = $rBlog->getById($id);
			$photo_p['ljPost'] = '1';
			$photo_p['todo'] = 'publish';
			if($db->selectCell("SELECT id FROM blog_posts_ext WHERE post_id = ?d", $id)) $photo_p['postID'] = $id;
			$rBlog->pos2LJ($photo_p, $id);

			if($twitter && !$photo_p['make_twit']){
				if ($twitter->twit($photo_p['title'].' '.SERVER_URL.ltrim($photo_p['post_url'], '/'), $p))
					$rBlog->updatePost($p, array('make_twit' => 1));
			}
		}
	/**КОНЕЦ - Постинг в LJ*/
	}
		
		
	echo 'posted!';
}

///нужно постить те посты у которых пришло время 