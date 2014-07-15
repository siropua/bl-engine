<?php

class rMySiteBlog extends rMyModule{

	protected $blog;

	public function __construct(rApplication $app, $blog = null){
		parent::__construct($app);
		$this->blog = $blog ? $blog : new rMyBlog($app);
	}

	public function assignPost($post){
		$this->assign('post', $post);
		$this->app->setTitle($post['title']);
		$descr = rMyMetatagsWorker::getDescription($post['preview'] ? $post['preview'] : substr($post['text'], 0, 800));
		$this->app->setDescription($descr);
		
		if(!empty($post['tags']) && is_array($post['tags']))
			$this->app->setKeywords(implode(', ', $post['tags']));

		$this->blog->viewPost($post['id']);
		$this->app->setTemplate('blog/viewPost.tpl');
	}

	public function routeURL(){
		if(!$blogData = $this->blog->selectedBlog()) $this->notFound();

		$this->assign('blogData', $blogData);
		if($this->app->url->path(2)){
			// пробуем отобразить пост


			if($post = rBlogPost::getByURL($this->blog, $this->app->url->path(2))){
				$this->assignPost($post->getData());				
			}
		}else{
//			$this->app->setTitle($blogData['name']);
			$this->assign('posts', $this->blog->selectPosts($this->app->url->get('page', 1)));
		}
	}

	public function Run()
	{
		# code...
	}

}