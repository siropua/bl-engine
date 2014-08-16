<?php

namespace ble;

/**
* Работает со списками baseTableModel через yield
*/
class baseListModelGet
{
	static function ret($className, $list)
	{
		foreach ($list as $key => $item) {
			yield $key => $className::hydrate($item);
		}
	}
}
