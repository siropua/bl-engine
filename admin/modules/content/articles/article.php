<?php

class module_articles_article extends rMyAdminModule{

	public function Run()
	{
		# code...
	}

	/**
	* Сохраняем пост
	**/
	public function Run_save()
	{
		if(empty($_POST['id'])) return false;
		if(!$a = Articles\Article::get($_POST['id'])) return false;

		$postData = $_POST['post'];
		$postData['last_update'] = time();

		foreach ($_POST['sections'] as $id => $sec_data) {
			$a->setSectionData($id, $sec_data);
		}

		$postData['text'] = $a->renderSectionsAsText();

		$a->setFields($postData, true);


		return [
			'ok' => 1,
			'status' => $a->status,
			'id' => $a->id,
			'last_update' => $a->last_update
		];
	}

	public function Run_delete()
	{

		if(empty($_POST['id'])) throw new JSONException('Specify ID!');

		if(!$a = Articles\Article::get($_POST['id'])) throw new rNotFoundException("");
		/**
		 * @todo удаление картинок и прочих ресурсов
		 */
		$a->remove();

		return 'OK';
		
	}
	

}