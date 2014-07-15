<?php

class module_register extends rMyModule{
	public function Run()
	{

		if(!empty($_POST['reg'])){
			if($this->doRegister($_POST['reg'])){
				$this->app->url->redirect(ROOT_URL.'my/');
			}
		}

		require_once 'classes/soc/rSocNetworkBase.class.php';
		$s = new rSocNetworkTable($this->app->db);
		$this->assign('social_networks', $s->getList());

		$this->app->addJS('register.js');
		$this->app->setTemplate('register.tpl');
	}

	/**
	* Регистрирует юзера
	* @var array $d массив с данными
	* @return bool результат регистрации
	*/
	public function doRegister($d)
	{

		$d = array_map('trim', $d);
		

		if($error = $this->hasFieldsErrors($d)){
			$this->app->addMessage($this->app->lang->__get('error_'.$error), APPMSG_ERROR);
			return false;
		}

		if($this->app->user->getByLogin($d['login'])){
			$this->app->addMessage($this->app->lang->error_login_exists, APPMSG_ERROR);
			return false;
		}

		$salt = rMyUser::getRandSalt();
		$this->app->db->query('INSERT INTO users SET ?a', array(
			LOGIN_FIELD => $d['login'],
			'password' => $this->app->user->hashPassword($d['pass'], $salt),
			'nick' => $d['login'],
			'full_name' => $d['login'],
			'ip' => rMyUser::getIntIP(),
			'salt' => $salt,
			'datereg' => time()
		));

		$this->app->user->login($d['login'], $d['pass'], time() + (60*60*24*365));

		return true;
	}

	/**
	* Проверяет, есть ли ошибки в полях
	* @var array $d массив с полями для проверки
	* @return false в случае отсутствия ошибок, либо строку с описанием ошибки
	**/
	public function hasFieldsErrors($d)
	{
		// пустой логин
		if(empty($d['login'])) return 'empty_login';

		// логин не подходит
		if(!preg_match(LOGIN_PREG, $d['login'])) return 'wrong_login';

		// пустой пароль
		if(empty($d['pass']) || empty($d['pass2'])) return 'empty_password';

		// пароли не совпадают
		if($d['pass'] != $d['pass2']) return 'passwords_not_match';

		return false;
	}
}