<?php

namespace Articles;

/**
* Секция текста
*/
class SectionImage extends \model_articlesSections
{
	use Section;

	protected 	$maxWidth = 2000,
				$maxHeight = 1000;

	protected static $myType = 'image';

	static public function createSection(\rMyArticle $a)
	{
		return self::create(['article_id' => $a->id, 'type' => static::$myType, 'order_n' => self::getNewN($a->id)]);
	}

	public function uploadPic($file)
	{
		
		$resized = $this->_uploadPic($file);
		
		$this->setFields([
			'string_data' => $resized->getBasename(),
			'int_data' => $resized->filesize(),
			'int_data1' => $resized->w(),
			'int_data2' => $resized->h(),
			], true);

		return $resized;
	}

	protected function _uploadPic($file)
	{
		require_once 'rlib/rImage.class.php';
		if(!$rImage = \rImage::getFromFile($file)) return false;
		$rImage->setDestination($this->getArticle()->getPath());

		$resized = $rImage->saveResized($this->maxWidth, $this->maxHeight);
		$thumb = $rImage->setFile($resized)->setResizeMode('thumbnail')->saveResized(200, 200, 'thumb_'.$resized->getBasename());

		return $resized;
	}

	public function remove()
	{
		$a = $this->getArticle();
		$a->removePic($this->string_data);
		parent::remove();
		$a->updateCached();
	}
}