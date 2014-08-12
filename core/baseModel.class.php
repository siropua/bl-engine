<?php

namespace blEngine;

/**
* Базовая модель, от неё будем наследовать все остальные модели в системе
*/
abstract class baseModel
{

	static protected $pKey = 'id'; // primary key
	static protected $tableName = '';
	protected $db = NULL; // database link
	
	protected $fields = array();
	protected $data = array();

	protected function __construct($data)
	{
		$this->data = $data;
		$this->db = DB::getInstance();
	}

	/**
	*	Заполняет данные из массива в класс, контролируя, чтобы в данных был primary key
	**/
	public function hydrate($data)
	{
		if(!self::$pKey) throw new modelException('pKey not defined');
		if(!isset($data[self::$pKey])) throw new modelException('pKey '.self::$pKey.' not found');
		
		
		foreach (self::$fields as $key => $value) 
		{
			if(isset($data[$key]))
			{
				$this->data[$key] = $value;
			}
		}

		return new self($data);
	}

	static public function get($id)
	{
		$data = DB::getInstance()->selectRow('SELECT * FROM ?# WHERE ?# = ?', self::$table, self::$pKey, $id);
		if(!$data) return false;

		return new self($data);
	}
}


class modelException extends Exception{}

