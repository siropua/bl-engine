<?php

/**
* Логинит под любым юзером! Опасная штука!
*/
class module_authas extends rMyModule
{
	
	function Run()
	{
		if(!$token = $this->app->path(2)) $this->app->url->redirect(ROOT_URL);

		if(!$token = model_usersAuthAs::get($token)) $this->app->url->redirect(ROOT_URL);

		$user = new rMyUser($this->app->db, '', false);
		if($user->getByID($token->login_as))
			$user->doLogin();

		$this->app->db->query('DELETE FROM users_auth_as WHERE token = ? OR date_add < ?d', $token->token, time() - 3600);


		$this->app->url->redirect(ROOT_URL);

	}
}