<?php

require_once('rlib/simpleRSS.class.php');

class module_rss extends rMyModule{

	protected $rss = null;
	protected $host;

	public function Run()
	{

		$this->host = 'http://'.$_SERVER['HTTP_HOST'];

		$this->rss = new simpleRSS($this->app->getSetting('default_title'), $this->host, 'Посты', array('lastBuildDate' => date('r', time())));

		$this->generateRSS();
		$this->printRSS();
	}


	public function printRSS()
	{
		header('Content-Type: text/xml;charset=utf-8');
		$this->rss->printXML('utf-8');

		exit;

	}

	public function generateRSS($value='')
	{
		$blog = new rMyBlog($this->app);
		$posts = $blog->selectPosts(1);
		$lastModified = 0;

		foreach ($posts['posts'] as $key => $p) {
			$lastModified = max($lastModified, $p['lastmodified']);
			$p['url'] = $this->host.$p['post_url'];

			$text = empty($p['preview']) ? $p['text'] : $p['preview'];
			if(!empty($p['mainpic_filename']))
				$text = '<img src="'.$this->host.$p['res_url'].$p['mainpic_filename'].'"><br>'.$text;

			$pics = $this->app->db->select('SELECT * FROM blog_images WHERE post_id = ?d{ AND id <> ?d} ORDER BY ordr',
				$p['id'], $p['mainpic_id'] ? $p['mainpic_id'] : DBSIMPLE_SKIP);
			if($pics){
				$text .= "<br><br>";
				foreach ($pics as $pic) {
					if($pic['text']) $text .= $pic['text'];
					$text .= '<img src="'.$this->host.$p['res_url'].$pic['filename'].'"><br><br>';
				}
			}

			if(!empty($p['preview']))
				$text .= $p['text'];

			$this->rss->addItem(
				$p['title'], 
				$p['url'], 
				$text, 
				array('guid'=>$p['url'], 'author' => $p['author_name'])
			);
		}

		$this->rss->setOptions(array('lastBuildDate' => date('r', $lastModified)));

	} // generate
}