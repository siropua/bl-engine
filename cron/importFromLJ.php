<?php
/**Проверяем  наличие новых постов в LiveJournal блог  /cron/importFromLJ.php**/
if(!@include('../config/config.main.php')) exit;
require_once(LIB_PATH.'/init.php');
if(@include_once(ROOT.'/config/livejournal.blog.php')) {
	
	$lj_posts = $rBlog->getPostsLJ();

foreach($lj_posts['events'] as $item){

	$res = $db->selectCell("SELECT id FROM blog_posts_ext WHERE  type_id = 2 AND url_id = ?d", $item['ditemid']);
	
	if(empty($res) && !isset($item['security'])){

		$post_data = array(
			'title' => htmlspecialchars($item['subject']),
			'url' => $rBlog->getURL($item['subject']),
			'blog_id' => '1',
			'owner_id' => LJ_IMPORT_USER_ID,
			'datepost' => strtotime($item['eventtime']),
			'original_text' => $item['event'],
			'text' => $item['event']
		);

		$blogItem = $rBlog->post($post_data);
		
		$lj_post_arr = array('type_id'=> 2, 'ext_id'=> $item['itemid'], 'post_id' => $blogItem['id'], 'url_id' => $item['ditemid']);
		$db->query("INSERT INTO blog_posts_ext SET ?a", $lj_post_arr);
		
	}
}

}else{
	exit("Нет файла настроек для LiveJournal");
}
