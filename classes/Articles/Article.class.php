<?php

namespace Articles;

/**
* Работа со статьями
*/
class Article extends \model_articles
{

	static protected $systemFields = 
		['date_add', 'id', 'owner_id', 'views', 'comments'];
	
	static public function create($data, $p1=false, $p2=false)
	{
		$data['date_add'] = $data['last_update'] = time();
		if(!empty($data['id'])) unset($data['id']);
		if(!$data['owner_id'] = rMyApp::getInstance()->user->getID())
			$data['owner_id'] = NULL;

		if(!$data['catalog_id']) $data['catalog_id'] = NULL;

		if(!isset($data['title'])) $data['title'] = '';
		$data['title'] = trim($data['title']);
		$baseURL = '';
		if(!empty($data['catalog_id']) && ($catalogURL = \ble\rURL::getHandled('articles_catalog', $data['catalog_id']))){
			$baseURL = $catalogURL->url;
		}

		if(empty($data['url']))
			$data['url'] = rURLParser::fineURLPart($data['title'] ? $data['title'] : date("d-m-Y-H-i-s"));
		else 
			$data['url'] = rURLParser::fineURLPart($data['url']);

		$article = parent::create($data);

		if($url = ble\rURL::createURL($data['url'], $baseURL, 'articles', $article->id))
			$article->setField('url_id', $url->id, true);

		return $article;
	}


	public function edit($data)
	{
		$this->safeSetFields($data)
			->setFields(['last_update' => time()])
			->inc('edits_count')
			->save();
	}

	static public function getArticleFolder($id, $date_add)
	{
		return 'articles/'.date('Y/m', $date_add).'/'.$id;
	}

	static public function getArticleURL($id, $date_add)
	{
		return USERS_URL.self::getArticleFolder($id, $date_add).'/';
	}

	static public function getArticlePath($id, $date_add, $autoCreate = false)
	{
		$path = USERS_PATH.'/'.self::getArticleFolder($id, $date_add);
		if($autoCreate && !is_dir($path))
			@mkdir($path, 0777, true);

		return $path;
	}

	public function getURL()
	{
		return self::getArticleURL($this->id, $this->date_add);
	}

	public function getPath()
	{
		return self::getArticlePath($this->id, $this->date_add);
	}

	public function getSections()
	{
		return $this->db->select('SELECT * FROM articles_sections WHERE article_id = ?d ORDER BY order_n', $this->id);
	}

	public function getData()
	{
		$data = parent::getData();
		$data['res_url'] = $this->getURL();
		$data['sections'] = $this->getSections();

		return $data;
	}

	public function setSectionData($id, $data)
	{
		if(!$model = \model_articlesSections::get($id)) return false;

		return $model->setFields($data, true);
	}

	public function renderSectionsAsText()
	{
		$sections = $this->getSections();
		$text = '';
		foreach ($sections as $sec) {
			if(!$sec['is_visible']) continue;
			if($sec['type'] == 'text')
			{
				$text .= '<div id="section'.$sec['id'].'" class="article-section article-section-text">';
				$text .= $sec['text_data'];
				$text .= '</div>';
			}else if($sec['type'] == 'image')
			{
				if(!$sec['string_data']) continue;
				$text .= '<div id="section'.$sec['id'].'" class="article-section article-section-image">';
				$text .= '<div class="pre-image">'.$sec['text_data'].'</div>';
				$text .= '<div class="image"><img src="'.$this->getURL().$sec['string_data'].'"></div>';
				$text .= '<div class="pre-image">'.$sec['text_data'].'</div>';
				$text .= '</div>';
			}
			$text .= "\n";			
		}

		return $text;
	}
}