<?php

class module_posts_pics extends rMyAdminModule{

	public $blog;

	public function Init()
	{
		$this->blog = new rMyBlog($this->app);
	}

	public function Run()
	{
		# code...
	}


	public function Run_get()
	{
		$id = $this->app->path(7);
		$data = $this->getPostPics($id);

		$this->assign('postPicsData', $data);
		//print_r($data);
		echo $this->app->fetch('', 'postPics.tpl');
		exit;
	}

	public function Run_remove()
	{
		if(!empty($_POST['id']) && !empty($_POST['postID'])){
			$post = new rBlogPost($this->blog, $_POST['postID']);
			$post->deAttachPic($_POST['id']);
		}

		return 'OK';
	}

	public function getPostPics($id)
	{
		$post = new rBlogPost($this->blog, $id);
		$data['res_url'] = $post->res_url;
		$data['mainpic'] = $post->mainpic_id;
		$data['pics'] = $post->getPics();

		return $data;
	}

	public function Run_order()
	{
		if(!empty($_POST['postID']) && !empty($_POST['pics'])){
			$pics = explode(',', $_POST['pics']);
			foreach($pics as $ordr => $id){
				$this->app->db->query('UPDATE blog_images SET ordr = ?d WHERE id = ?d AND post_id = ?d',
					$ordr+1, $id, $_POST['postID']);
			}
		}

		return 'ok';
	}

	public function Run_asmain()
	{
		if(empty($_POST['postID']) || empty($_POST['id'])) throw new JSONException('Empty request!');
		
		$post = new rBlogPost($this->blog, $_POST['postID']);
		$post->setField('mainpic_id', $_POST['id']);

		return 'OK';
	}

	public function Run_gettext()
	{
		if(empty($_GET['id'])) throw new JSONException('No id specified');

		$pic = $this->app->db->selectRow('SELECT * FROM blog_images WHERE id = ?d', $_GET['id']);
		if(!$pic) throw new JSONException('Pic not exists');

		$post = new rBlogPost($this->blog, $pic['post_id']);

		$pic['text'] = trim($pic['text']);
		
		return array(
			'res_url' => $post->res_url,
			'pic' => $pic
		);
		
	}
	
	public function Run_settext()
	{
		if(empty($_GET['id'])) throw new JSONException('No id specified');
		if(!isset($_POST['text'])) throw new JSONException('No text specified');
		

		$this->app->db->query('UPDATE blog_images SET `text` = ? WHERE id = ?d', 
			trim($_POST['text']), $_GET['id']);

		
		return array('ok' => $_GET['id']);
		
	}
	

}