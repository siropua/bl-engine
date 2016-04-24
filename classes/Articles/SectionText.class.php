<?php

namespace Articles;

/**
* Секция текста
*/
class SectionText extends \model_articlesSections
{
	use Section;
	static public function createSection(\rMyArticle $a)
	{
		return self::create(['article_id' => $a->id, 'type' => 'text', 'order_n' => self::getNewN($a->id)]);
	}
}