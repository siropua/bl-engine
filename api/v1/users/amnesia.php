<?php

/**
* Login to site
*/
class api_users_amnesia extends rMyAPIModule
{
	
	public function Run()
	{
		if(!empty($_POST['login'])) return $this->sendCode($_POST['login']);

		if(!empty($_POST['new_pass'])) return $this->resetPass($_POST);
	}

	public function sendCode($login)
	{
		$user = new rUser;
		if(!$user->getByLogin($login)) throw new rNotFoundException('User not found', 404);

		if($amnesia = model_amnesia::get($user->getID())){
			$code = $amnesia->code;
		}else
			$code = sha1(uniqid());

		if(!model_amnesia::create(array('id' => $user->getID(), 
				'timeout' => time()+(60*60*24), 
				'code' => $code
			), false, true))
		{
			throw new Exception("Can't create code");
		}

		require_once 'classes/rEmailer.class.php';
		rEmailer::sendEmail($user->email, 'Востановление пароля', 'Привет. Перейди по ссылке http://alfa-lead.ru/login/amnesia/'.$code);

		return $code;
		
	}

	public function resetPass($data)
	{
		
	}
}