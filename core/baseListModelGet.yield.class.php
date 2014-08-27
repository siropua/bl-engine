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

class baseTreeModelGet
{
	static function ret($className, &$list)
	{
		foreach ($list as $key => $item) {
			if (!empty($item['childNodes'])){
				$list[$key]['childNodes'] = self::ret($className, $item['childNodes']);
				foreach ($list[$key]['childNodes'] as $keyin => $rowin){
					yield $keyin => $rowin;
				}
			}
			$list[$key] = $className::hydrate($item);
			yield $key => $list[$key];
		}
	}
}
