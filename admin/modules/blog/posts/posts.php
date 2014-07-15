<?php

class module_posts extends rMyAdminModule{

	public function Run()
	{

		@session_start();
		unset($_SESSION['admin-temp-post-id']);
		
		if(!empty($_GET['edit'])){
			$this->edit($_GET['edit']);
		}elseif (!empty($_GET['ashtml'])) {
			$this->showAsHTML($_GET['ashtml']);
		}

		$rBlog = new rMyBlog($this->app);

		require_once('rlib/simplePager.class.php');
		$pager = new simplePager(20, @$_GET['page']);
		$total = 0;

		$posts = $this->app->db->selectPage($total, 'SELECT 
				b.name as blog_name, b.url as blog_url,
				p.id, p.url, p.title, p.datepost, p.status, p.dateadd, p.views, p.allow_comments, p.source_url,
				p.comments, p.last_comment, p.tags_cache, p.ref_clicks, p.video_type, p.geo_lat,
				u.nick as owner_nick,
				img.filename as mainpic
			FROM ?# p
			LEFT JOIN ?# b ON b.id = p.blog_id
			LEFT JOIN users u ON u.id = p.owner_id
			LEFT JOIN blog_images img ON img.id = p.mainpic_id
			WHERE 1{ AND p.status = ?}
			{ AND p.blog_id = ?d}
			{ AND p.title LIKE ?}
			ORDER BY p.id DESC
			LIMIT '.$pager->getMySQLLimit(),
			$rBlog->getSetting('posts_table'),
			$rBlog->getSetting('blogs_table'),
			empty($_GET['status']) ? DBSIMPLE_SKIP : $_GET['status'],
			empty($_GET['blog_id']) ? DBSIMPLE_SKIP : $_GET['blog_id'],
			empty($_GET['q']) ? DBSIMPLE_SKIP : '%'.$_GET['q'].'%'
			);

		foreach ($posts as $key => $post) {
			$posts[$key] = rBlogPost::proceedPost($post);
		}

		$this->assign('posts', $posts);
		$this->assign('pages', $pager->getPagesStr($total));
		$this->assign('total', $total);
		$this->assign('blogs', $this->app->db->selectCol('SELECT id AS ARRAY_KEY, name FROM ?# ORDER BY name', $rBlog->getSetting('blogs_table')));

	}

	public function edit($id)
	{
		require_once 'add.php';
		$m = new module_posts_add($this->app, $this->me);
		$m->Init();
		$m->Run();

		$m->loadPost($id);

	}

	public function showAsHTML($id)
	{
		$b = new rMyBlog($this->app);
		$post = new rBlogPost($b, $id);
		$this->assign('post', $post->getData());
		$this->assign('pics', $post->getPics());

		$this->app->render('asHTML.tpl');
	}

}