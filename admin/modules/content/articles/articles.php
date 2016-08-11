<?php

/**
* Список статей
*/
class module_articles extends rMyAdminModule
{
	
	function Run()
	{

		if(!empty($_POST['newitem']))
			$this->createArticle($_POST['newitem']);

		$catalog = $this->app->db->select('SELECT a.*, u.url, REPEAT("—", a.level) as padding 
			FROM articles_catalog a
			LEFT JOIN urls u ON u.id = a.url_id
			ORDER BY a.left_key');

		$this->assign('catalogItems', $catalog);

		if(!empty($_GET['id']))
			$this->editArticle($_GET['id']);

		$list = $this->app->db->select('SELECT a.*, c.title as catalog_title, u.url
			FROM articles a
			LEFT JOIN articles_catalog c ON c.id = a.catalog_id
			LEFT JOIN urls u ON u.id = a.url_id

			ORDER BY a.id DESC');
		$this->assign('articles', $list);
	}

	public function createArticle($p)
	{
		if($article = rMyArticle::create($p))
			$this->app->url->redirect('?id='.$article->id);

		$this->app->addMessage('Ошибка создания статьи!', APPMSG_ERROR);
	}

	public function editArticle($id)
	{
		$article = Articles\Article::get($id);

		if(!empty($_POST)) $this->publishArticle($article, $_POST);

		$this->app->initExternalFW('rMyFWS_Gallery');
		$this->app->addFWJS('date_ru_utf8.js');
		$this->app->addFWJS('jquery.pickmeup.min.js');
		$this->app->addFWCSS('pickmeup.min.css');

		$this->app->addFWJS('tinymce/tinymce.min.js');
		$this->app->addFWJS('tinymce/jquery.tinymce.min.js');

		$this->app->addFWJS('autocomplete/jquery.autoSuggest.js');
		$this->app->addFWCSS('autocomplete/jquery.autoSuggest.css');

		$this->addJS('article-form.js');

		$this->assign('article', $article->getData());

		$this->app->render('article-form.tpl');
	}

	public function publishArticle(Articles\Article $a, $data)
	{
		// if(!empty($_POST)) $this->app->dump($_POST, true, false);

		$postData = $data['post'];
		$postData['last_update'] = time();
		$postData['edits_count'] = $a->edits_count+1;
		$postData['status'] = 'published';

		foreach ($data['sections'] as $id => $sec_data) {
			$a->setSectionData($id, $sec_data);
		}

		$postData['text'] = $a->renderSectionsAsText();

		$a->setFields($postData, true);

		$this->app->url->redirect('?saved='.$a->id);
	}


}