<?php

namespace ble;

/**
* Работает со списками baseTableModel
*/
class baseListModel
{

	protected $baseModel;
	protected $db;
	
	public function __construct($baseModel)
	{
		$this->baseModel = $baseModel;
		$this->db = DB::getInstance();
	}

	public function getTableName()
	{
		$className = '\\'.$this->baseModel;
		return $className::getTableName();
	}

	public function getAsArray($where = false)
	{
		$list =  $this->db->select('SELECT * FROM ?#', $this->getTableName());
		return $list;
	}

	public function get($where = false)
	{
		$className = '\\'.$this->baseModel;
		$list = $this->getAsArray($where);
		echo PHP_VERSION;
/**		
// блять, 5.5 всетаки мало где установлен :( жаль... оч жаль!
foreach ($list as $key => $item) {
				yield $key => $className::hydrate($item);
		}

**/

		foreach ($list as $key => $value) {
			$list[$key] = $className::hydrate($item);
		}

		return $item;
	}
}
