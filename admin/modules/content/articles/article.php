<?php

class module_articles_article extends rMyAdminModule{

	public function Run()
	{
		# code...
	}

	/**
	* Сохраняем пост
	**/
	public function Run_save()
	{
		$_POST['tags'] = trim($_POST['as_values_tags'], ' ,');
		$p = $_POST;
		

		if(empty($p['postID']) || (!$postID = intval($p['postID']))){
			// создание поста (точнее его черновика)
			$r = rMyArticle::create($p);

			$post = $r->getData();
			$post['action'] = 'created';
		}else{
			if(!$r = rMyArticle::get($p['postID']))
				throw new rNotFoundException('Article not found');
				
			$r->edit($p);

			$post = $r->getData();
			$post['action'] = 'saved';
		}

		@session_start();
		$_SESSION['admin-temp-post-id'] = $post['id'];

		$result = array('ok' => 1, 'post' => $post);


		return $result;
	}

	public function Run_delete()
	{

		if(empty($_POST['id'])) throw new JSONException('Specify ID!');

		if(!$a = Articles\Article::get($_POST['id'])) throw new rNotFoundException("");
		
		$a->remove();

		return 'OK';
		
	}
	

}