<?php

namespace blEngine;

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
		$data = DB::getInstance()->selectRow('SELECT * FROM ?# WHERE ?# = ?', self::$table, self::$pKey, $id);
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
}


class modelException extends \Exception{}

