<?php

namespace Articles;

/**
* Секция текста
*/
class SectionGallery extends SectionImage
{

	public function uploadPic($file)
	{
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
}