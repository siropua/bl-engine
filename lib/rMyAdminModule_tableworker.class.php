<?php

require_once 'rlib/rTable.class.php';

/**
* Стандартная работа с таблицами
* 
* Главное, таблица должна иметь поле ID
* Если переменная $hasOrdering стоит в true также необходимо поле ordr

*/
class rMyAdminModule_tableworker extends rMyAdminModule
{
	
	protected $tableName = 'table';
	protected $table = NULL;
	protected $ordr = false;
	protected $sortField = 'id desc';
	protected $template = '';

	public function Init()
	{
		$this->table = new rTableClass($this->app->db, $this->tableName);
		if($this->ordr){
			$this->sortField = $this->ordr;
		}

		$this->template = ENGINE_PATH.'/admin/components/tableworker/index.tpl';

		$this->structure = $this->app->db->select('SHOW FULL COLUMNS FROM '.$this->tableName);
		$this->assign('tableStruct', $this->structure);

	}

	public function Run()
	{
		
		if (!empty($_POST['item'])) {
			$item = $_POST['item'];
			if(empty($item['id'])){
				$this->addItem($item);
			}else{
				$id = $item['id'];
				unset($item['id']);
				$this->saveItem($id, $item);
			}
		}


		$items = $this->table->getList('', $this->sortField);

		$this->assign('items', $items);

		if($this->template)
			$this->app->render($this->template);
	}

	public function saveItem($id, $item)
	{
		$this->table->put($id, $item);
		$this->go2index();
	}

	public function addItem($i)
	{
		$this->table->add($i);
		$this->go2index();
	}

	public function RunAJAX_delete()
	{
		if(empty($_POST['id'])) return 'OK';

		$this->table->remove($_POST['id']);

		return 'OK';
	}

	public function RunAJAX_get()
	{
		if(!empty($_GET['id'])) return $this->table->get($_GET['id']);

		return false;
	}
}