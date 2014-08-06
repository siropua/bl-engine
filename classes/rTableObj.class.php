<?php


/**
* Объект завязанный на таблицу.
* Умеет работать с БД, гидрироваться, и всякое такое. 
* Базовый класс для всяких простых штук
* По сути, пришел на смену rTable
*/
class rTableObj
{
	protected $id;
	protected $data = NULL;
	protected $tableName = '';
	
	public function __construct($id)
	{
		$this->id = $id;
	}

	public function getData()
	{
		$this->checkData();
		return $this->data;
	}

	public function __get($key)
	{
		$this->checkData();
		if(!isset($this->data[$key])) return NULL;

		return $this->data[$key];
	}

	public function checkData()
	{
		if($data == NULL){
			$this->reloadData();
		}
	}

	public function reloadData()
	{
		$this->data = rMyApp::getInstance()->db->selectRow('SELECT * FROM ?# WHERE id = ?d', $this->tableName, $this->id);
	}

	// превращает массив в объект
	static public function hydrate($idORdata, $data = NULL)
	{
		if($data === NULL){
			$this->data = $idORdata;
			$this->id = $this->data['id'];
		}else{
			$this->id = $idORdata;
			$this->data = $idORdata;
			$this->data['id'] = $this->id;
		}

		return new self($this->id);
	}
}