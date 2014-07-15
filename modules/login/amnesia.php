<?php

class module_login_amnesia extends rMyModule{
	

	public function Run()
	{
		if($this->app->user->authed()){
			$this->app->redirect(ROOT_URL.'my/');
		}

		if($code = $this->app->path(3)){
			
			$this->checkCode($code);
			return;

		}elseif (!empty($_POST['email'])) {
			$u = new rMyUser($this->app->db, false);
			if($u->getByLogin($_POST['email'])){
				if($this->sendCode($u)){
					$this->app->render('login/amnesia-sent.tpl');
				}else{
					$this->app->addMessage('Системе не удалось отправить письмо :( Обратитесь к администрации', APPMSG_ERROR);
				}
			}else{
				$this->assign('email', htmlspecialchars($_POST['email']));
				$this->app->addMessage('Пользователя с такими данными нет в системе', APPMSG_ERROR);
			}
		}

		$this->app->setTemplate('login/amnesia.tpl');

	}

	public function sendCode($u)
	{

		$code = md5(uniqid(''));

		$this->app->db->query('DELETE FROM amnesia WHERE id = ?d', $u->id);
		$this->app->db->query('INSERT INTO amnesia SET ?a', array(
			'id' => $u->id, 'code' => $code, 'timeout' => time() + (60*60*24)
		));
		
		$this->assign('code', $code);
		$this->assign('send2user', $u->getData());

		$text = $this->app->tpl->fetch('emails/amnesia.tpl');

		require_once 'classes/rEmailer.class.php';
		return rEmailer::sendEmail(
			$u->email,
			'Восстановление пароля для'.$_SERVER['HTTP_HOST'],
			$text
		);

	}


	public function checkCode($code)
	{
		$u = $this->app->db->selectRow('SELECT * FROM amnesia WHERE `code` = ? AND timeout >= ?d', $code, time());
		if(!$u || !$this->app->user->getByID($u['id'])){
			$this->app->addMessage('Ссылка по восстановлению является недействительной', APPMSG_ERROR);
			$this->app->url->redirect(ROOT_URL.'amnesia/');
		}

		if(!empty($_POST['p']['p1'])){			
			$this->app->user->changePassword($_POST['p']['p1']);
			$this->app->user->login($this->app->user->email, $_POST['p']['p1']);
			$this->app->db->query('DELETE FROM amnesia WHERE id = ?d', $this->app->user->id);
			$this->app->addMessage('Пароль изменён!', APPMSG_OK);
			$this->app->url->redirect(ROOT_URL.'my/');
		}

		$this->assign('amnesiaUser', $this->app->user->getData());

		$this->setTemplate('login/amnesia-newpass.tpl');
	}

}