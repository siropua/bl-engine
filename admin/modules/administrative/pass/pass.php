<?php

class module_pass extends rMyAdminModule{
	public function Run()
	{
		if(!empty($_POST['password'])){
			if($_POST['password'] != $_POST['password2']){
				$this->app->addMessage('Пароли не совпадают!', APPMSG_ERROR);
			}else{
				$this->app->user->changePassword($_POST['password']);
				$this->app->addMessage('Пароль изменен!', APPMSG_OK);
				$this->app->url->redirect(ADMIN_URL);
			}
		}
		# code...
	}


}