<?php

class module_logout extends rMyModule{
	
	// Run нам не нужен, все можно определить на стадии конструирования
	public function __construct($app){

		$redirect = empty($_SERVER['HTTP_REFERER']) ? ROOT_URL : $_SERVER['HTTP_REFERER'];

		if(!empty($_REQUEST['key']) && $app->user->authed() && (md5($app->user->datereg) == $_REQUEST['key'])){
			$app->user->logout();
		}

		$app->url->redirect($redirect);

	}

	public function Run(){
		
	}

}