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


	public function RunAJAX_image()
	{
		if(empty($_POST['id'])) return false;
		if(empty($_FILES['secpic']['tmp_name'])) return false;
		if(!is_uploaded_file($_FILES['secpic']['tmp_name'])) return false;

		$section = Articles\SectionImage::get($_POST['id']);

		$section->uploadPic($_FILES['secpic']['tmp_name']);

		$return = [
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

		$section->remove();

		return 'OK';
	}
}