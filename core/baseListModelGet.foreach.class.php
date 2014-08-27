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

class baseTreeModelGet
{
	static function ret($className, &$list)
	{
		foreach ($list as $key => $item) {
			if (!empty($item['childNodes'])){
				$list[$key]['childNodes'] = self::ret($className, $item['childNodes']);
			}
			$list[$key] = $className::hydrate($item);
		}
		return $list;
	}
}
