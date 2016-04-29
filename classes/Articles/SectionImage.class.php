<?php

namespace Articles;

/**
* Секция текста
*/
class SectionImage extends \model_articlesSections
{
	use Section;

	static public function createSection(\rMyArticle $a)
	{
		return self::create(['article_id' => $a->id, 'type' => 'image', 'order_n' => self::getNewN($a->id)]);
	}

	public function uploadPic($file)
	{
		require_once 'rlib/rImage.class.php';
		if(!$rImage = \rImage::getFromFile($file)) return false;
		$rImage->setDestination($this->getArticle()->getPath());

		$resized = $rImage->saveResized(2000, 1600);
		$thumb = $rImage->setFile($resized)->setResizeMode('thumbnail')->saveResized(200, 200, 'thumb_'.$resized->getBasename());

		$this->setFields([
			'string_data' => $resized->getBasename(),
			'int_data' => $resized->filesize(),
			'int_data1' => $resized->w(),
			'int_data2' => $resized->h(),
			], true);

		return $resized;
	}
}