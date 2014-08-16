<?php

namespace ble;

if ((float)PHP_VERSION >= 5.5){
	require_once('baseListModelGet.yield.class.php');
}else{
	require_once('baseListModelGet.foreach.class.php');
}

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

	public function getAsArray($conditions = false)
	{
		$list = $this->db->select(
			sprintf(
				'SELECT * FROM ?#%s%s%s%s%s%s',
				!empty($conditions['join']) ? ' '.$this->makeJoinString($conditions['join']) : '',
				!empty($conditions['where']) ? ' WHERE '.$this->makeWhereString($conditions['where']) : '',
				!empty($conditions['group']) ? ' GROUP BY '.$this->makeGroupString($conditions['group']) : '',
				!empty($conditions['having']) ? ' HAVING '.$this->makeHavingString($conditions['having']) : '',
				!empty($conditions['order']) ? ' ORDER BY '.$this->makeOrderString($conditions['order']) : '',
				!empty($conditions['limit']) ? ' LIMIT '.$this->makeLimitString($conditions['limit']) : ''
			),
			$this->getTableName()
		);
		return $list;
	}

	private function makeJoinString($param){
		if (is_array($param)){
			return implode(' ', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	private function makeWhereString($param){
		if (is_array($param)){
			return implode(' AND ', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	private function makeGroupString($param){
		if (is_array($param)){
			return implode(',', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	private function makeHavingString($param){
		if (is_array($param)){
			return implode(' AND ', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	private function makeOrderString($param){
		if (is_array($param)){
			$ret = array();
			foreach ($param as $key => $val){
				$ret[] = $key.(!empty($val) ? ' '.$val : '');
			}
			return implode(',', $ret);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	private function makeLimitString($param){
		if (is_array($param)){
			return implode(',', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	public function get($conditions = false)
	{
		$className = '\\'.$this->baseModel;
		$list = $this->getAsArray($conditions);
		return baseListModelGet::ret($className, $list);
	}
}
