<?php

require_once 'classes/soc/rSocNetworkBase.class.php';

class module_my extends rMyModule{

	protected $ext;

	public function Init()
	{
		if(!$this->app->user->authed()){
		    $this->app->render('login.tpl');
		}

		$this->ext = new rUserExternal($this->app->user);
		
	}

	public function Run()
	{
		$snt = new rSocNetworkTable($this->app->db);
		

		$allSocials = $snt->getList();
		$mySocials = $this->ext->getMySocials();

		$this->assign('allSocials', $allSocials);
		$this->assign('mySocials', $mySocials);

		$this->app->setTitle('Мой профайл');
		$this->app->setTemplate('my/index.tpl');
	}

	public function RunAJAX_set_ext_userpic()
	{
		if(empty($_POST['id'])) return false;
		return $this->ext->useUserpic($_POST['id']);
		
	}
}