<?php

require_once 'classes/soc/rSocNetworkBase.class.php';

class module_logout_as extends rMyModule{
	
	protected $s = '';

	public function Init()
	{
		$url = $this->app->path(3);
		if(!$url) $this->app->url->redirect(ROOT_URL.'login/');
		if(!$this->s = rSocNetworkBase::getInstance($this->app, $url)) throw new rNotFound;
	}

	public function Run(){
		if(!$this->app->user->authed()){
			$this->app->redirect(ROOT_URL);
		}

		$ext = new rUserExternal($this->app->user);

		$ext->disconnect($this->s->id);

		$this->app->url->redirect(ROOT_URL.'my/');
		

	}


}