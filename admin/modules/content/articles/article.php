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

	public function Run_attach(){
		
		if(empty($_POST['postID'])){
			throw new JSONException('post-id not specified');
		}

		if(empty($_FILES['secpic']['tmp_name'])){
			throw new JSONException('file not specified');
			
		}

		$b = new rMyBlog($this->app);
		$post = new rBlogPost($b, $_POST['postID']);

		$fn = $post->attachPic($_FILES['secpic']);

		if(!$fn) throw new JSONException('Cant attach picture!');

		$result = array('ok' => 1, 'pic' => $fn);
		

		return $result;
	}

	public function Run_attachweb()
	{
		if(empty($_POST['postID'])){
			throw new JSONException('post-id not specified');
		}

		if(empty($_POST['pic'])){
			return array();
		}

		$picName = basename($_POST['pic']);


		$b = new rMyBlog($this->app);
		$post = new rBlogPost($b, $_POST['postID']);

		$tmpPic = TMP_PATH.'/'.uniqid('webpic');
		require 'rlib/vibrowser.inc.php';
		$vb = new ViBrowser;
		$vb->setURL($_POST['pic']);
		$vb->getURLToFile($_POST['pic'], $tmpPic);

		if(!file_exists($tmpPic)) throw new Exception('Cant download a pic');

		$fn = $post->attachPic(array(
			'tmp_name' => $tmpPic,
			'name' => $picName,
		)); 

		if(!$fn) throw new JSONException('Cant attach picture!');

		$result = array('ok' => 1, 'pic' => $fn);
		

		return $result;
	}

	public function Run_delete()
	{

		if(empty($_POST['id'])) throw new JSONException('Specify ID!');

		$b = new rMyBlog($this->app);
		$post = new rBlogPost($b, $_POST['id']);
		if(!$post->delete()){
			throw new JSONException('Cant delete post!');
		}

		return array('deleted_id' => $post->id);
		
	}
	

}