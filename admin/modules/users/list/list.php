<?php


class module_list extends rMyAdminModule{

	public function Run()
	{
		$q = false;
		if(!empty($_GET['q'])){
			$q = '%'.$_GET['q'].'%';
		}elseif (!empty($_GET['edit'])) {
			$this->edit($_GET['edit']);
		}
		
		$users = $this->app->db->select('SELECT u.*
			FROM users u 
			WHERE 1{ AND u.email LIKE ?}
			ORDER BY u.id DESC
			LIMIT 100',
			$q ? $q : DBSIMPLE_SKIP);

		$this->assign('users', $users);
	}

	public function edit($id)
	{
		$user = new rMyUser($this->app->db, false);

		if(!$user->getByID($id)) throw new rNotFound('User not found');


		if(!empty($_POST['u']) && is_array($_POST['u'])){
			$u = $_POST['u'];
			if(!empty($_POST['u_prem_date']))
				$u['premium_till'] = strtotime($_POST['u_prem_date']);
			else
				$u['premium_till'] = 0;

			$user->setFields($u);
			$this->app->addMessage('Данные сохранены', APPMSG_OK);
			$this->app->url->redirect('?edit='.$user->id);

		}


		$this->app->addFWJS('date_ru_utf8.js');
		$this->app->addFWJS('jquery.pickmeup.min.js');
		$this->app->addFWCSS('pickmeup.min.css');


		require_once 'classes/soc/rSocNetworkBase.class.php';
		$ext = new rUserExternal($user);
		$this->assign('userSocials', $ext->getMySocials());

		$this->assign('editUser', $user->getData());
		$this->assign('isAdmin', $user->can('admin'));

		$this->app->render('editUser.tpl');
	}
}