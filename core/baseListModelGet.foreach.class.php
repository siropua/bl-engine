<?php

namespace ble;

/**
* Работает со списками baseTableModel через foreach
*/
class baseListModelGet
{
	static function ret($className, $list)
	{
		foreach ($list as $key => $item) {
			$list[$key] = $className::hydrate($item);
		}
		return $list;
	}
}
