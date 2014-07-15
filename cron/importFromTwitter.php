<?php
/** /cron/importFromTwitter.php**/
if(!@include('../config/config.main.php')) exit;
require_once(LIB_PATH.'/init.php');
if(include_once(ROOT.'/config/twitter.php')) {
	
$res = file_get_contents('http://twitter.com/statuses/user_timeline/'.TWITTER_ACCOUNT.'.json?count=10');
$res = json_decode($res);

	foreach($res as $k=>$v){
		
		$db_res = $db->selectCell("SELECT id FROM blog_posts_ext WHERE type_id = '1' AND ext_id = ?", $res[$k]->id_str);
		//print_r($db_res);
		if(empty($db_res)){
			
			$date_post = date('Y-m-d h:i:s', strtotime($res[$k]->created_at));
			
			$post_data = array(
				'title' => htmlspecialchars($date_post),
				'url' => $rBlog->getURL($date_post),
				'blog_id' => '1',
				'owner_id' => TWIT_IMPORT_USER_ID,
				'datepost' => strtotime($res[$k]->created_at),
				'original_text' => $res[$k]->text,
				'text' => $res[$k]->text
			);
	
			$blogItem = $rBlog->post($post_data);
			
			$lj_post_arr = array('type_id'=> 1, 'ext_id'=> $res[$k]->id_str, 'post_id' => $blogItem['id']);
			
			$db->query("INSERT INTO blog_posts_ext SET ?a", $lj_post_arr);
			
						/**Постинг в LJ*/
			/**Проверка наличия файла с логином и паролем для LiveJournal*/
			if((include_once(ROOT.'/config/livejournal.blog.php')) && defined('TWITTER_POST2LJ') && TWITTER_POST2LJ) {
			
				$post_data['ljPost'] = '1';
				$post_data['todo'] = 'publish';
				$rBlog->pos2LJ($post_data, $blogItem['id']);
			}
			/**КОНЕЦ - Постинг в LJ*/
			
		}
			
	}

}else{
	exit("Нет файла настроек для Twitter");
}
