<?php

namespace ble;

/**
* Базовая модель, от неё будем наследовать все остальные модели в системе
*/
abstract class baseTableModel
{

	static protected $pKey = 'id'; // primary key
	static protected $tableName = '';
	static protected $fields = array();

	protected $db = NULL; // database link
	
	
	protected $data = array();

	protected function __construct($data)
	{
		$this->data = $data;
		$this->db = \rMyApp::getInstance()->db; // TODO: baseModel should not be know about Application
	}

	/**
	*	Заполняет данные из массива в класс, контролируя, чтобы в данных был primary key
	**/
	static public function hydrate($data)
	{
		if(!static::$pKey) throw new modelException('pKey not defined');
		if(!isset($data[static::$pKey])) throw new modelException('pKey '.static::$pKey.' not found');
		
		
		// foreach (static::$fields as $key => $value) 
		// {
		// 	if(isset($data[$key]))
		// 	{
		// 		$stat->data[$key] = $value;
		// 	}
		// }

		return new static($data);
	}

	static public function get($id)
	{
		$data = DB::getInstance()->selectRow('SELECT * FROM ?# WHERE ?# = ?', static::$tableName, static::$pKey, $id);
		if(!$data) return false;

		return new static($data);
	}



	static public function create($data, $doGetAfterInsert = false)
	{
		$insData = array();

		foreach (static::$fields as $key => $value) {
			if(isset($data[$key])){
				$insData[$key] = $data[$key];
			}else{
				if($key != static::$pKey && $value['default'] != ''){
					$insData[$key] = $value['default'];
				}
			}
		}

		$newID = DB::getInstance()->query('INSERT INTO ?# SET ?a', static::$tableName, $insData);

		if($doGetAfterInsert){
			return static::get($newID);
		}else{
			$insData[static::$pKey] = $newID;
			return static::hydrate($insData);
		}

	}

	public function __get($key)
	{
		return isset($this->data[$key]) ? $this->data[$key] : NULL;
	}

	public function getID()
	{
		return $this->data[$this->pKey];
	}

	public function __set($key, $val)
	{
		$this->setFieldData($key, $val);
	}

	/**
	* Устанавливает поле. 
	* Если параметр instantSave = true то немедленно записывает в базу
	**/
	public function setFieldData($key, $val, $instantSave = false)
	{
		if(isset($this->fields[$key]))
			$this->data[$key] = $val;

		if($instantSave) $this->save();

		return $this;
	}

	/**
	* Сохраняет данные массива в базу
	**/
	public function save()
	{
		$this->db->query('UPDATE ?# SET ?a WHERE ?# = ?', $this->tableName, $this->data, $this->pKey, $this->getID());
		return $this;
	}

	static public function getTableName()
	{
		return static::$tableName;
	}

	static public function getPKey()
	{
		return static::$pKey;
	}

	static public function getFieldsList()
	{
		return array_keys(static::$fields);
	}


	static public function me()
	{
		return get_called_class();
	}
}


class modelException extends \Exception{}

