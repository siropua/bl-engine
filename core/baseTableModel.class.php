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
	static protected $systemFields = []; // fields with no access to public


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

	/**
	* Конструирует объект по primary-key (хотя можно и по составному)

	* @var $id int|string|array - если int или string то значение единичного ключа. Если массив - набор field => value
	* @var $key string|null если задано, то имя ключа, по которому высылается
	* @return baseTableModel|false либо созданный объект, либо false, если такой строки не найдено
	**/
	static public function get($id, $key = null)
	{
		$db = DB::getInstance();

		if(is_array($id))
		{
			// ключ составной, выбираем по нескольким полям!
			
			$where = array();
			foreach($id as $field => $value) $where[] = sprintf('%s=%s', $db->escape($field, true), $db->escape($value));

			$data = $db->selectRow('SELECT * FROM ?# WHERE '.implode(' AND ', $where).' LIMIT 1', static::$tableName);

		}else
		{
			if(!$key) $key = static::$pKey;
			$data = $db->selectRow('SELECT * FROM ?# WHERE ?# = ? LIMIT 1', static::$tableName, $key, $id);
		}

		
		if(!$data) return false;

		return new static($data);
	}

	/**
	* Создает строчку в базе и создает из неё объект

	* @var $data array массив с данными. автоматически фильтруется
	* @var $doGetAfterInsert bool надо ли принудительно сделать SELECT из таблицы после вставки, либо заполнить массив «предполагаемыми» данными
	* @var $updateIfExists bool делать ли ON DUPLICATE KEY UPDATE
	* @return baseTableModel|false либо созданный объект, либо false, если добавить строку не удалось
	*/
	static public function create($data, $doGetAfterInsert = false, $updateIfExists = false)
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

		if($updateIfExists){
			$newID = DB::getInstance()->query('INSERT INTO ?# SET ?a ON DUPLICATE KEY UPDATE ?a', static::$tableName, $insData, $insData);
		}else{
			$newID = DB::getInstance()->query('INSERT INTO ?# SET ?a', static::$tableName, $insData);
		}

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

	/**
	 * Alias for setFieldData method.
	 * @param string  $key         Field name
	 * @param mixed  $val         Field value
	 * @param boolean $instantSave Instant save to database (or you must call save() method at and instead)
	 */
	public function setField($key, $val, $instantSave = false)
	{
		return $this->setFieldData($key, $val, $instantSave);
	}

	/**
	 * Сразу же сохранить данные, но отфильтровав системные поля
	 * @param  array $data Массив с новыми данными
	 * @return baseTableModel       
	 */
	public function safeSetFields($data, $instantSave = false)
	{
		if(is_array($this->systemFields))
			foreach($this->systemFields as $f)
				if(isset($data[$f]))
					unset($data[$f]);
		return $this->setFields($data, $instantSave);
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
		$this->db->query('UPDATE ?# SET ?a WHERE ?# = ?',
			static::$tableName,
			array_intersect_key($this->data, static::$fields),
			static::$pKey,
			$this->getID()
		);

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
	
	public function inc($field, $cnt = 1)
	{
	    $this->db->query('UPDATE ?# SET ?# = ?# + ? WHERE ?# = ?d',
		static::$tableName, $field, $field, $cnt, static::$pKey, $this->id
	    );
	    $this->data[$field] = $this->db->selectCell('SELECT ?# FROM ?# WHERE ?# = ?d',
		$field, static::$tableName, static::$pKey, $this->id
	    );
	    return $this;
	}
	
	public function dec($field, $cnt = 1)
	{
	    return $this->inc($field, -$cnt);
	}


	public function remove()
	{

		$this->db->query('DELETE FROM ?# WHERE ?# = ?d', static::$tableName, static::$pKey, $this->id);

		return true;
	}
}


class modelException extends \Exception{}

