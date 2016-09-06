<?php

namespace ble;

/**
* Nested Sets
*/
class nestedSetsModel
{
	protected
		$tableName = '',
		$className = '',
		$db = NULL;

	public function __construct($table)
	{
		$this->tableName = trim($table);
		$this->className = 'model_'.baseTableModel::translateSQLNameToPHP($this->tableName);
		$this->db = DB::getInstance();
	}

	public function create($parentID, array $data)
	{
		$level = 0;
		$right_key = 0;
		$parent_id = NULL;
		$class = $this->className;
		if($parentID && ($parent = $class::get($parentID)))
		{
			$right_key = $parent->right_key;
			$level = $parent->level;
			$parent_id = $parent->id;
		}else
		{
			$right_key = $this->db->selectCell('SELECT MAX(right_key) FROM ?#', $this->tableName) + 1;
		}

		$this->db->query('UPDATE ?# SET right_key = right_key + 2, left_key = IF(left_key > ?d, left_key + 2, left_key) WHERE right_key >= ?d', $this->tableName, $right_key, $right_key);

		$data['left_key'] = $right_key;
		$data['right_key'] = $right_key + 1;
		$data['level'] = $level + 1;
		$data['parent_id'] = $parent_id;

		return $class::create($data);
	}

	public function delete($id)
	{	
		if(!$item = $this->getObject($id)) return false;

		$r = $item->right_key;
		$l = $item->left_key;
		$this->db->query('DELETE FROM ?# WHERE left_key >= ?d AND right_key <= ?d', $this->tableName, $l, $r);

		$this->db->query('UPDATE ?# SET left_key = IF(left_key > ?d, left_key - ?d, left_key), right_key = right_key - ?d WHERE right_key > ?d', $this->tableName, $l, ($r-$l+1), ($r-$l+1), $r);

		return true;
	}

	public function getObject($id)
	{
		$class = $this->className;
		return $item = $class::get($id);
	}

	public function getAssoc($startID = 0)
	{
		
	}

	public function getParents($idOrModel)
	{
		if(!is_object($idOrModel))
		{
			if(!$idOrModel = $this->getObject($idOrModel)) return [];
		}

		$parents = $this->db->select('SELECT * FROM ?# WHERE left_key <= ?d AND right_key >= ?d ORDER BY left_key', $this->tableName, $idOrModel->left_key, $idOrModel->right_key);

		return $parents;
	}
}