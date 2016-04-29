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
	protected $className;
	protected $db;
	
	public function __construct($baseModel)
	{
		$this->baseModel = $baseModel;
		$this->className = chr(92).$this->baseModel;
		$this->db = DB::getInstance();
	}

	public function getTableName()
	{
		$className = $this->className;
		return $className::getTableName();
	}

	public function getPKey()
	{
		$className = $this->className;
		return $className::getPKey();
	}

	public function getAsArray($conditions = false)
	{
		$list = $this->db->select(
			sprintf(
				'SELECT %s FROM ?#%s%s%s%s%s%s',
				!empty($conditions['fields']) ? ' '.$this->makeFieldsString($conditions['fields']) : '*',
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

	protected function makeFieldsString($param){
		if (is_array($param)){
			foreach ($param as $key => $val){
				if (is_string($key)){
					$param[$key] = sprintf('%s as %s', $val, $key);
				}
			}
			return implode(', ', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	protected function makeJoinString($param){
		if (is_array($param)){
			return implode(' ', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	protected function makeWhereString($param){
		if (is_array($param)){
			if(empty($param[0]))
			{
				$where = '';
				$param2 = [];
				foreach ($param as $pKey => $pValue) {
					$param2[] = '`'.$pKey.'` = "'.mysqli_escape_string(DB::getInstance()->link, $pValue).'"';
				}
				$param = $param2;
			}
			return implode(' AND ', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	protected function makeGroupString($param){
		if (is_array($param)){
			return implode(',', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	protected function makeHavingString($param){
		if (is_array($param)){
			return implode(' AND ', $param);
		}
		if (is_string($param)){
			return $param;
		}
		return false;
	}

	protected function makeOrderString($param){
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

	protected function makeLimitString($param){
		if (is_array($param)){
			return implode(',', $param);
		}
		if (is_string($param)){
			return $param;
		}
		if (is_numeric($param)){
			return $param;
		}
		return false;
	}

	public function get($conditions = false)
	{
		$list = $this->getAsArray($conditions);
		return baseListModelGet::ret($this->className, $list);
	}

	public function remove($id)
	{
		return $this->db->query('DELETE FROM ?# WHERE ?# = ?', $this->getTableName(), $this->getPKey(), $id);
	}
}
