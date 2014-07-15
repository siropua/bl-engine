<?php

require_once 'classes/soc/rSocNetworkBase.class.php';

class module_login_as extends rMyModule{
	
	protected $s = '';

	public function Init()
	{
		$url = $this->app->path(3);
		if(!$url) $this->app->url->redirect(ROOT_URL.'login/');
		if(!$this->s = rSocNetworkBase::getInstance($this->app, $url)) throw new rNotFound;
	}

	public function Run(){
		
		if($this->app->path(4) == 'done'){
			return $this->loginDone();
		}

		$params = $this->s->getRedirectParams();

		$this->s->redirect2login($params);

	}


	public function Run_vk()
	{
		echo 'vk'; exit;
	}
	
	public function loginDone()
	{
	    if(!empty($_GET['code'])){
			$token = $this->s->parseToken($this->s->getAccessToken($_GET['code']));
			
			
			if(!$token){
			    $this->app->addMessage('Не удалось залогиниться!', APPMSG_ERROR);
			    $this->app->url->redirect(ROOT_URL.'login/');
			}
			$ext = new rUserExternal($this->app->user);
			if($this->app->user->authed()){
			    /* just saving user to external table */
			    
			    $ext->connect($this->s->id, $token);

			    $this->app->addMessage('Соц-сеть подключена!', APPMSG_OK);
			    $this->app->url->redirect(ROOT_URL.'my/');

			}elseif($user_id = $ext->isUserExists($this->s->id, $token['user_id'])){
				/** Юзер уже есть, аутентифицируем **/
				$this->app->user->getByID($user_id);
				$this->app->user->doLogin();

				$ext->markLogin($this->s->id, $token['user_id']);

				$this->app->url->redirect(ROOT_URL.'my/');
			}else{
			    /**	Регистрируем юзера как есть, нового и чистенького.  **/
			    $this->registerAsSocial($token);
			}
			
	    }
	}

	public function registerAsSocial($token)
	{
		$salt = rMyUser::getRandSalt();
		$login = LOGIN_FIELD == 'email' ? 
			$token['login'].'@'.$this->s->domain :
			$this->s->url.'-'.$token['login'];
		$pass = uniqid($this->s->url);

		$uid = $this->app->db->query('INSERT INTO users SET ?a', array(
			LOGIN_FIELD => $login,
			'password' => $this->app->user->hashPassword($pass, $salt),
			'nick' => $token['name'],
			'full_name' => $token['name'],
			'ip' => rMyUser::getIntIP(),
			'salt' => $salt,
			'datereg' => time(),
			'acc_type_id' => $this->s->id,
		));

		$this->app->user->login($login, $pass, time() + (60*60*24*365));

		$ext = new rUserExternal($this->app->user);
		$ext->connect($this->s->id, $token);

		$this->app->url->redirect(ROOT_URL.'my/');
	}

}