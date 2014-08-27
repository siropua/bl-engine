<?php

namespace ble;

/**
* Работает с деревом baseTableModel
*/
class baseTreeModel extends baseListModel
{

	protected $Key = 'id'; // Key
	protected $parentKey = 'parent_id'; // Parent key

	public function __construct($baseModel)
	{
		parent::__construct($baseModel);
	}

	public function setKey($key = 'id')
	{
		$this->Key = $key;
	}

	public function setParentKey($key = 'parent_id')
	{
		$this->parentKey = $key;
	}

	public function setKeys($keys = array('id', 'parent_id'))
	{
		$this->setKey($keys[0]);
		$this->setParentKey($keys[1]);
	}

	public function getAsArray($conditions = false)
	{
		$list = $this->db->select(
			sprintf(
				'SELECT *, ?# AS ARRAY_KEY, ?# AS PARENT_KEY FROM ?#%s%s%s%s%s%s',
				!empty($conditions['join']) ? ' '.$this->makeJoinString($conditions['join']) : '',
				!empty($conditions['where']) ? ' WHERE '.$this->makeWhereString($conditions['where']) : '',
				!empty($conditions['group']) ? ' GROUP BY '.$this->makeGroupString($conditions['group']) : '',
				!empty($conditions['having']) ? ' HAVING '.$this->makeHavingString($conditions['having']) : '',
				!empty($conditions['order']) ? ' ORDER BY '.$this->makeOrderString($conditions['order']) : '',
				!empty($conditions['limit']) ? ' LIMIT '.$this->makeLimitString($conditions['limit']) : ''
			),
			$this->Key,
			$this->parentKey,
			$this->getTableName()
		);
		return $list;
	}

	public function get($conditions = false)
	{
		$className = '\\'.$this->baseModel;
		$list = $this->getAsArray($conditions);
		return baseTreeModelGet::ret($className, $list);
	}
}
