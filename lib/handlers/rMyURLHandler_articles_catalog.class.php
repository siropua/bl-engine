<?php

/**
* Работа со статьями
*/
class rMyURLHandler_articles_catalog extends rMyURLHandler
{

	protected 
		$articleCatalog = NULL;

	public function __construct(ble\rURL $url)
	{
		parent::__construct($url);
		if(!$this->articleCatalog = model_articlesCatalog::get($url->handled_id))
			throw new rNotFoundException('Article not found');
		$this->assign('catalogItem', $this->articleCatalog->getData());
	}

	public function Run()
	{
		$parents = $this->app->db->select('SELECT ac.*, u.url FROM articles_catalog ac
				LEFT JOIN urls u ON u.id = ac.url_id
				WHERE ac.left_key <= ?d AND ac.right_key >= ?d ORDER BY ac.left_key', $this->articleCatalog->left_key, $this->articleCatalog->right_key);
			// $this->app->dump($parents);
		$this->assign('catalogParents', $parents);

		$articles = $this->app->db->select('SELECT a.id, a.title, u.url
			FROM articles a
			LEFT JOIN urls u ON u.id = a.url_id
			WHERE catalog_id = ?d', $this->articleCatalog->id);
		$this->assign('articles', $articles);

		$childs = $this->app->db->select('SELECT ac.*, u.url FROM articles_catalog ac
				LEFT JOIN urls u ON u.id = ac.url_id
				WHERE ac.left_key > ?d AND ac.right_key < ?d ORDER BY ac.left_key', $this->articleCatalog->left_key, $this->articleCatalog->right_key);
			
		$this->assign('catalogChilds', $childs);

		$this->renderPage();
		
	}

	public function renderPage()
	{
		$this->app->addCSS('articles/articles.css');
		$this->app->render('articles/list.tpl');
	}
}