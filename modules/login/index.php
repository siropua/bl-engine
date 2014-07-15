<?php

class module_login extends rMyModule{
	
	public function __construct($app){
		if($app->user->authed()) $app->url->redirect(ROOT_URL);
		parent::__construct($app);
	}

	public function Run(){

		$this->app->setTemplate('login.tpl');

		require_once 'classes/soc/rSocNetworkBase.class.php';
		$s = new rSocNetworkTable($this->app->db);
		$this->assign('social_networks', $s->getList());
	}

}