<?php 


/**
* Работа с секциями
*/
class module_articles_section extends rMyAdminModule
{
	
	function RunAJAX()
	{
		return 'runAJAX';
	}

	public function RunAJAX_new()
	{
		if(empty($_POST['article_id'])) return false;
		if(empty($_POST['type'])) return false;

		$method = 'addType_'.$_POST['type'];
		if(method_exists($this, $method))
		{
			return $this->$method();
		}

		return false;
		
	}

	public function addType_text()
	{
		if(!$article = rMyArticle::get($_POST['article_id']))
			throw new rNotFoundException('Article not exists');
			
		$section = Articles\SectionText::createSection($article);

		return $section->getData();
	}

	public function addType_image()
	{
		if(!$article = rMyArticle::get($_POST['article_id']))
			throw new rNotFoundException('Article not exists');
			
		$section = Articles\SectionImage::createSection($article);

		return $section->getData();
	}

	public function addType_gallery()
	{
		if(!$article = rMyArticle::get($_POST['article_id']))
			throw new rNotFoundException('Article not exists');
			
		$section = Articles\SectionGallery::createSection($article);

		return $section->getData();
	}


	public function RunAJAX_image()
	{
		if(empty($_POST['id'])) return false;
		if(empty($_FILES['secpic']['tmp_name'])) return false;
		if(!is_uploaded_file($_FILES['secpic']['tmp_name'])) return false;

		$section = Articles\SectionImage::get($_POST['id']);

		$section->uploadPic($_FILES['secpic']['tmp_name']);

		$return = [
			'type'	=> 'image',
			'id' 	=> $section->id,
			'file' 	=> $section->string_data,
			'url' 	=> $section->getArticle()->getURL()
		];

		return $return;
	}

	public function RunAJAX_delete()
	{
		if(empty($_POST['id'])) return false;
		if(!$section = model_articlesSections::get($_POST['id'])) return false;

		if($section->type == 'gallery')
		{
			$gallerySection = Articles\SectionGallery::hydrate($section->getData());

			$gallerySection->remove();
		}elseif($section->type == 'image'){
			$gallerySection = Articles\SectionImage::hydrate($section->getData());

			$gallerySection->remove();

		}else{
			$section->remove();
		}

		return 'OK';
	}

	public function RunAJAX_gallery()
	{
		if(empty($_POST['id'])) {
			return false;
		}
		if(empty($_FILES['gallery']['tmp_name'])) return false;
		if(!is_uploaded_file($_FILES['gallery']['tmp_name'])) return false;

		$section = Articles\SectionGallery::get($_POST['id']);

		$section->uploadPic($_FILES['gallery']['tmp_name']);

		return [
			'type' => 'gallery',
			'id' 	=> $section->id,
			'files' => $section->getImages(),
			'url' 	=> $section->getArticle()->getURL()
		];
		return $section->getData();
	}

}