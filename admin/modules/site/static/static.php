<?php

/**
* 
*/
class module_static extends rMyAdminModule
{
	protected $files = array();
	protected $path = '';


	public function Init()
	{
		$this->path = DESIGN_PATH.'/'.STATIC_TPL_FOLDER;
		$this->files = glob($this->path.'/*.tpl');
		if($this->files)$this->files = array_map('basename', $this->files);

	}

	public function Run()
	{

		

		if(!empty($_GET['edit'])){
			$this->editPage($_GET['edit']);
		}elseif (!empty($_POST['newURL'])) {
			$url = $this->app->url->URLize($_POST['newURL']);
			file_put_contents($this->path.'/'.$url.'.tpl', '');
			$this->app->url->redirect('?edit='.$url.'.tpl');
		}

		$this->assign('files', $this->files);
	}

	public function editPage($page)
	{
		if(!in_array($page, $this->files)) throw new rNotFound("No tpl file", 1);

		if(!empty($_POST['content'])){
			$fn = $this->path.'/'.$page;
			$content = $_POST['content'];
			if(is_writable($fn)){
				file_put_contents($fn, $content);
				$this->app->addMessage('Текст сохранён!', APPMSG_OK);
				$this->app->url->redirect($this->me['url']);
			}else{
				$this->app->addMessage('Файл не может быть записан!', APPMSG_ERROR);
			}

		}else{
			$content = file_get_contents($this->path.'/'.$page);
		}

		$this->app->addFWJS('tinymce/tinymce.min.js');
		$this->app->addFWJS('tinymce/jquery.tinymce.min.js');
		
		$this->assign('page', $page);
		$this->assign('content', htmlspecialchars($content));

		$this->setTemplate('edit.tpl');
		
	}


	public function RunAJAX_delete()
	{

		if(	empty($_POST['page'])
			|| !($page = trim($_POST['page'])) 
			|| !in_array($page, $this->files)
		) throw new Exception("No tpl file", 1);

		if(unlink($this->path.'/'.$page))
			$this->app->addMessage('Страница удалена', APPMSG_OK);


		echo 'OK';

	}


}