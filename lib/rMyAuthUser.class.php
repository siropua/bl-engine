<?php


class rMyAuthUser{
	
	public static function login(rApplication $app, $loginData){
		if(empty($loginData['login']) || !preg_match(LOGIN_PREG, $loginData['login'])) return 'Login_fail';
		if(empty($loginData['password'])) return 'Pass_fail';

		$loginData['save_me'] = empty($loginData['save_me']) ? 0 : strtotime(date('Y-m-d').' next year');

		$result = $app->user->login($loginData['login'], $loginData['password'], $loginData['save_me']);

		switch($result){
			case LOGIN_OK:
				return true;

			case LOGIN_NO_USER:
				return 'No_user_with_this_login';
			case LOGIN_ERR_LOGIN:
				return 'Login_fail';
			case LOGIN_BLOCKED_USER:
				return 'You_are_blocked';

			case LOGIN_PASS_ERR:
				return 'Password_incorrect';

			default:
				throw new Exception('Login result unknown');
				
		}

	}

}