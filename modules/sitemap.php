<?php

class module_sitemap extends rMyModule{

	protected $items = array();

	public function Run()
	{

		$this->loadExists(ROOT.'/sitemap.xml');
		
		$this->loadBlog();
		$this->loadCustom();

		$this->printXML();
	}

	public function printXML()
	{
		header('Content-Type: application/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			foreach($this->items as $i){
				echo "<url>\n";
				foreach($i as $key => $value){
					echo "\t<$key>$value</$key>\n";
				}
				echo "</url>\n";
			}
		echo '</urlset>';
			
		exit;
	}


	function addLink($url, $arr = array())
	{
		
		$url = $this->app->url->getAbsoluteURL(htmlspecialchars($url));
		$arr['loc'] = $url;
		if(isset($arr['lastmod']) && is_numeric($arr['lastmod'])) 
			$arr['lastmod'] = date('Y-m-d', $arr['lastmod']);
		$this->items[md5($url)] = $arr;
	}


	public function loadExists($path)
	{
		if(!file_exists($path)) 
			return false;

		require_once('rlib/xml2array.php');
		$orig = @xml2array(file_get_contents($path));

		if(!empty($orig['urlset']['url']) && is_array($orig['urlset']['url']))
		foreach($orig['urlset']['url'] as $i){
			$key = md5($i['loc']['value']);
			foreach($i as $n=>$v){
				$this->items[$key][$n] = $v['value'];
			}
		}

		return true;
	}

	public function loadBlog()
	{
		$blog = new rMyBlog($this->app);
		$posts = $blog->getSitemap();
		foreach($posts as $p){
			$this->addLink(rBlogPost::getPostURL($p), array(
				'lastmod' => max($p['lastmodified'], $p['last_comment']),
				'changefreq' => 'weekly'
			));
		}

		$tags = $blog->getTagsList();
		foreach($tags as $tag){
			$this->addLink(ROOT_URL.'tags/'.$tag['url']);
		}
	}

	public function loadCustom()
	{
		
	}

}