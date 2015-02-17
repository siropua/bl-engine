<?php


/**
* 
*/
class module_admins extends rMyAdminModule
{
	
	function Run()
	{

		if(!empty($_GET['edit'])){
			$this->editAdmin($_GET['edit']);
		}

		if(!empty($_POST['newpass']))try{
			$npd = $_POST['newpass'];
			if(empty($npd['pass'])){
				throw new FormNotValid('Вы неправильно указали пароль!');
			}
			if(empty($npd['user_id'])){
				throw new EngineError;
			}
			$salt = $this->app->user->getRandSalt();
			$this->app->db->query('UPDATE ?# SET ?# = ?, salt = ? WHERE ?# = ?d', 
				USERS_TABLE, PASS_FIELD, $user->hashPassword($npd['pass'], $salt), $salt, UID_FIELD, $npd['user_id']);
			$this->app->addMessage('Пароль изменен', APPMSG_OK);
			$this->app->redirect($this->me['url']);
			
		}catch(FormNotValid $e){
			$this->app->assign('error', $e->getMessage());
		}


		$list = $this->app->db->select('SELECT * FROM ?# WHERE rights != ""', USERS_TABLE);
		$this->app->assign('list', $list);
	}

	public function editAdmin($editID)
	{
				
		$this->app->addNavPath('Редактирование карточки администратора');


		$editUser = new rMyUser($this->app->db, false);
		if(!$data = $editUser->getByID($editID))
			$this->app->renderError('Такого администратора не существует');

		$data = $editUser->getData();

		$this->assign('admin_data', $data);

		$rmv = new RACMenuWorker;
		$modules = $rmv->parseModules();




		$this->app->assign('rights', $modules);

		$this->app->assign('editMode', true);
		$this->app->setTemplate('adminForm.tpl');


		if(!empty($_POST['data'])){
			
			$data = array_map('trim', $_POST['data']);
			$data['can'] = $_POST['can'];
			
			$this->app->assign('data', $data);
			
			if(isset($data['login'])){
				$reggedID = $this->app->db->selectCell('SELECT id FROM ?# WHERE login = ?', USERS_TABLE, $data['login']);
				if($reggedID && ($reggedID != $editID)){
					$this->app->assign('error', 'Такой логин уже зарегистрирован в системе');
					$this->app->render();
				}
				
				if(!preg_match(LOGIN_PREG, $data['login'])){
					$this->app->assign('error', 'Неправильный логин');
					$this->app->render();	
				}
			}
			$salt = $editUser->getRandSalt();
			$this->app->db->query('UPDATE ?# SET ?a WHERE id = ?d', USERS_TABLE, array(
				//'login' => $data['login'],
				'full_name' => $data['full_name'],
				'nick' => $data['full_name'],
				//'datereg' => time(),
				'rights' => serialize($data['can'])
			), $editID);
			
			$this->app->addMessage('Права сохранены', APPMSG_OK);
			$this->app->redirect($this->me['url']);
		}



		$this->app->render();


	}
}

