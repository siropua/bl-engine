<?php


/**
* Login to site
*/
class api_users_login extends rMyAPIModule
{
	
	function Run()
	{
		if(empty($_POST['login'])) throw new Exception('No login specified', 1001);
		if(empty($_POST['password'])) throw new Exception('No password specified', 1002);

		$loginStatus = $this->app->user->login($_POST['login'], $_POST['password'], time() + 365*24*60*60);

		if($loginStatus == LOGIN_OK) return array(
			'access_token' => $this->app->user->getCurToken(),
			'status' => 'ok',
		);

			

		$msg = '';
		switch ($loginStatus) {
			case LOGIN_ERR_LOGIN:
				$msg = 'Login incorrect!';
				break;
			case LOGIN_NO_USER:
			case LOGIN_PASS_ERR:
				$msg = 'Password incorrect!';
				break;
			
			default:
				$msg = 'Unknown login error';
				break;
		}

		throw new Exception($msg, 1010+$loginStatus);

	}
}