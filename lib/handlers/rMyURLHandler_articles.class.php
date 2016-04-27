<?php

/**
* Работа со статьями
*/
class rMyURLHandler_articles extends rMyURLHandler
{

	protected 
		$article = NULL;

	public function __construct(ble\rURL $url)
	{
		parent::__construct($url);
		if(!$this->article = Articles\Article::get($url->handled_id))
			throw new rNotFoundException('Article not found');
	}

	public function Run()
	{	
		if($this->article->catalog_id &&
			($catalogItem = model_articlesCatalog::get($this->article->catalog_id)))
		{
			$this->assign('catalogItem', $catalogItem->getData());

			$parents = $this->app->db->select('SELECT ac.*, u.url FROM articles_catalog ac
				LEFT JOIN urls u ON u.id = ac.url_id
				WHERE ac.left_key <= ?d AND ac.right_key >= ?d ORDER BY ac.left_key', $catalogItem->left_key, $catalogItem->right_key);
			// $this->app->dump($parents);
			$this->assign('catalogParents', $parents);

		}
		$this->assign('article', $this->article->getData());

		$this->renderPage();
		
	}

	public function renderPage()
	{
		$this->app->addJS('~DESIGN/articles/articles.js');
		$this->app->addCSS('articles/articles.css');
		$this->app->render('articles/view.tpl');
	}
}