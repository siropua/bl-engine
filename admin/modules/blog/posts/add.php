<?php

class module_posts_add extends rMyAdminModule{

	public $blog;

	public function Init()
	{
		$this->blog = new rMyBlog($this->app);
	}

	public function Run()
	{

		if(!empty($_POST)){
			$_POST['tags'] = trim($_POST['as_values_tags'], ' ,');
			if(empty($_POST['postID'])){
				$this->addPost($_POST);
			}else{
				$this->savePost($_POST['postID'], $_POST);
			}
		}

		$this->app->addFWJS('tinymce/tinymce.min.js');
		$this->app->addFWJS('tinymce/jquery.tinymce.min.js');

		$this->app->addFWJS('autocomplete/jquery.autoSuggest.js');
		$this->app->addFWCSS('autocomplete/jquery.autoSuggest.css');


		$this->app->addFWJS('multi_uploader/js/vendor/jquery.ui.widget.js');
		$this->app->addFWJS('multi_uploader/js/load-image.min.js');
		$this->app->addFWJS('multi_uploader/js/canvas-to-blob.min.js');
		//$this->app->addFWJS('multi_uploader/js/jquery.fileupload-image.js');
		$this->app->addFWJS('multi_uploader/js/jquery.fileupload.js');
		$this->app->addFWJS('multi_uploader/js/jquery.fileupload-process.js');
		$this->app->addFWJS('multi_uploader/js/jquery.iframe-transport.js');

		$this->app->addFWJS('date_ru_utf8.js');
		$this->app->addFWJS('jquery.pickmeup.min.js');
		$this->app->addFWCSS('pickmeup.min.css');

		$this->app->addFWJS('jquery.sortable.js');
		$this->app->addFWJS('jquery.ui.touch-punch.js');

		$this->addJS('post-form.js');

		
		$this->assign('blogs', $this->blog->getBlogsList());

		@session_start();
		if(!empty($_SESSION['admin-temp-post-id'])){
			$this->loadPost($_SESSION['admin-temp-post-id']);
		}

		$this->app->setTemplate('add.tpl');
	}

	/**
		Создаёт новый пост.
		Что странно, т.к. он должен создасться в другом месте
	**/
	public function addPost($post)
	{
		
	}

	/**
		Сохраняем пост
	**/
	public function savePost($id, $post)
	{

		@session_start();
		unset($_SESSION['admin-temp-post-id']);

		if(!empty($post['todo']) && ($post['todo'] == 'publish')){
			$post['status'] = 'posted';
		}

		if(!empty($post['is_datepost']) && !empty($post['dp_date'])){
			$post['datepost'] = $post['dp_date'];
			if(!empty($post['dp_time']))
				$post['datepost'] .= ' '. $post['dp_time'] . (strlen($post['dp_time']) < 6 ? ':00' : '');
		}

		
		$this->blog->editPost($id, $post);

		$this->app->url->redirect($this->me['url']);
	}

	/**
		Загружаем пост, который мы не доредактировали ранее
	**/
	public function loadPost($id)
	{
		$post = new rBlogPost($this->blog, $id);
		$this->assign('blogItem', $post->getData());

		$this->assign('postPicsData', array(
			'pics' => $post->getPics(),
			'res_url' => $post->res_url,
			'mainpic' => $post->mainpic_id,
		));
	}

}