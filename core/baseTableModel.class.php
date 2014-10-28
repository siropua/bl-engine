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


	protected $lang_key = array('id' => null, 'lang_id' => null);
	protected $lang_data = array();
	protected $lang_table = '_str';
	protected $lang_field = array();

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

		$item = false;

		if($doGetAfterInsert){
			$item = static::get($newID);
		}else{
			$insData[static::$pKey] = $newID;
			$item = static::hydrate($insData);
		}

		if(!$item) return false;


		if (!empty($data['lang_id'])){
			$item->lang_key = array(
				'id' => $item->id,
				'lang_id' => $data['lang_id']
			);
			foreach ($item->lang_field as $key => $function){
				$item->lang_data[$key] = !empty($function['code']) ? $function['code'](@$data[$key]) : @$data[$key];
			}
			$item->db->query('INSERT INTO ?# (?#) VALUES (?a)',
				$item->get_lang_table(),
				array_keys($item->lang_key + $item->lang_data),
				array_values($item->lang_key + $item->lang_data)
			);
		}

		return $item;

	}

	public function getData()
	{
		return $this->data;
	}

	public function __get($key = NULL)
	{
		if ($key == 'data'){
			return isset($this->data['data']) ? $this->data['data'] : $this->data;
		}
		return $key !== NULL && isset($this->data[$key]) ? $this->data[$key] : NULL;
	}

	public function getID()
	{
		return $this->data[static::$pKey];
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
		if(isset(static::$fields[$key]))
			$this->data[$key] = $val;


		if (in_array($key, array_keys($this->lang_field))){
			$this->lang_data[$key] = !empty($this->lang_field[$key]['code']) ? $this->lang_field[$key]['code']($val) : $val;
		}elseif ($key == 'lang_id'){
			$this->lang_key = array(
				'id' => $this->data['id'],
				'lang_id' => $val
			);
		}

		if($instantSave) $this->save();

		return $this;
	}

	public function setFields($data, $instantSave = false)
	{
		if(!empty($data['lang_id'])){
			// чтобы lang_id заполнялся гарантированно первым
			$this->setFieldData('lang_id', $data['lang_id']);
			unset($data['lang_id']);
		}
		foreach ($data as $field => $value) {
			$this->setFieldData($field, $value);
		}

		if($instantSave) $this->save();
		
		return $this;
	}

	/**
	* Сохраняет данные массива в базу
	**/
	public function save()
	{
		$this->db->query('UPDATE ?# SET ?a WHERE ?# = ?', static::$tableName, $this->data, static::$pKey, $this->getID());


		if (!empty($this->lang_key['lang_id']) && !empty($this->lang_data)){
			$this->db->query('INSERT INTO ?# (?#) VALUES (?a) ON DUPLICATE KEY UPDATE ?a',
				$this->get_lang_table(),
				array_keys($this->lang_key + $this->lang_data),
				array_values($this->lang_key + $this->lang_data),
				$this->lang_data
			);
		}

		return $this;
	}

	public function getLangs($onlyLang = null)
	{
		$list = $this->db->select('SELECT ?#, ?# AS ARRAY_KEY FROM ?# WHERE ?# = ?d',
			array_keys($this->lang_field),
			array_keys($this->lang_key)[1],
			$this->get_lang_table(),
			array_keys($this->lang_key)[0],
			$this->data['id']
		);
		foreach ($list as $k => $row){
			foreach ($row as $key => $val){
				$list[$k][$key] = !empty($this->lang_field[$key]['decode']) ? $this->lang_field[$key]['decode']($val) : $val;
			}
		}

		if($onlyLang) return @$list[$onlyLang];
		return $list;
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

	protected function get_lang_table()
	{
		return static::getTableName().$this->lang_table;
	}


	public function remove()
	{

		$this->db->query('DELETE FROM ?# WHERE ?# = ?d', static::$tableName, static::$pKey, $this->id);

		return true;
	}
}


class modelException extends \Exception{}

