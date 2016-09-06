<?php

namespace Articles;

/**
* Секция текста
*/
class SectionGallery extends SectionImage
{

	protected static $myType = 'gallery';

	public function uploadPic($file)
	{
		$this->maxHeight = $this->int_data2;

		$resized = $this->_uploadPic($file);
		$images = $this->getImages();

		$images[] = ['file' => $resized->getBasename()];

		$this->setFields([
			'text_data2' => json_encode($images),
			'type' => 'gallery'
			], true);

		return $images;
	}

	public function getImages()
	{
		if($this->type == 'image')
		{
			$images = [['file' => $this->string_data]];
		}else{
			$images = @json_decode($this->text_data2, true);
		}
		if(!$images) $images = [];

		return $images;
	}

	public function remove()
	{
		$a = $this->getArticle();
		foreach ($this->getImages() as $f) {
			$a->removePic($f['file']);
		}

		parent::remove();

		$a->updateCached();
	}
}