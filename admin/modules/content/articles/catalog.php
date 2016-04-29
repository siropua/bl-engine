<?php

/**
* Управление каталогом
*/
class module_articles_catalog extends rMyAdminModule
{
	
	protected
		$catalog = NULL;


	public function __construct($app, $info)
	{
		parent::__construct($app, $info);
		require_once 'core/nestedSetsModel.class.php';
		$this->catalog = new ble\nestedSetsModel('articles_catalog');
	}

	function Run()
	{
		if(!empty($_POST) && !empty($_POST['title']))
		{
			if(empty($_POST['id']))
				$this->create($_POST);
			else
				$this->save($_POST['id'], $_POST);
		}elseif(!empty($_POST['delete_id']))
		{
			$this->delete($_POST['delete_id']);
		}

		$this->addJS('catalog.js');
		// $return = $this->catalog->create(3, ['title' => 'Вложенное создания']);

		$items = $this->app->db->select('SELECT a.id AS ARRAY_KEY, a.parent_id AS PARENT_KEY, a.*, u.url 
			FROM articles_catalog a
			LEFT JOIN urls u ON u.id = a.url_id
			ORDER BY a.left_key');
		// $this->app->dump($items, true, false); 
		

		$this->assign('catalogItems', $items);


		$this->app->render('catalog.tpl');
	}

	public function create(array $p)
	{
		if(empty($p['parent_id'])) $p['parent_id'] = NULL;

		$p['title'] = trim($p['title']);

		if(empty($p['url'])) $p['url'] = $p['title'];

		$baseURL = '';
		if($p['parent_id']){
			$baseURL = $this->app->db->selectCell('SELECT u.url FROM articles_catalog a LEFT JOIN urls u ON u.id = a.url_id WHERE a.id = ?d', $p['parent_id']);
		}

		if(!$url = ble\rURL::createURL($p['url'], $baseURL))
			$this->app->reload('Ошибка создания URL', APPMSG_ERROR);

		$p['url_id'] = $url->id;
		$p['date_add'] = $p['last_modified'] = time();

		if(!$item = $this->catalog->create($p['parent_id'], $p))
		{
			$this->app->reload('Ошибка создания!', APPMSG_ERROR);
		}

		$url->setHandler('articles_catalog', $item->id);

		$this->app->reload('Раздел создан', APPMSG_OK);

	}

	public function save($id, $data)
	{
		$data['last_modified'] = time();
		if($item = model_articlesCatalog::get($id))
		{
			if(isset($data['parent_id'])) unset($data['parent_id']);
			$item->setFields($data, true);
			if($item->url_id && ($url = ble\rURL::get($item->url_id)))
			{
				if($url->url != $data['url'])
					$url->updateURL($data['url']);
			}
		}
	}

	public function delete($id)
	{
		$deleted = $this->catalog->delete($id);
		if($deleted) 
			ble\rURL::deleteHandled('articles_catalog', $id);
		$this->app->reload('Раздел удалён!', APPMSG_OK);
	}
}