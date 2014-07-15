<?php

$site->addNavPath('Редактирование карточки администратора');


$editUser = new myUser(false);
if(!$data = $editUser->getByID($editID))
	$site->renderError('Такого администратора не существует');

$data = $editUser->getData();

$site->assign('data', $data);
$site->assign('rights', $_RIGHTS);

$site->assign('editMode', true);
$site->template('adminForm.tpl');


if(!empty($_POST['data'])){
	
	$data = array_map('trim', $_POST['data']);
	$data['can'] = $_POST['can'];
	
	$site->assign('data', $data);
	
	if(isset($data['login'])){
		$reggedID = $db->selectCell('SELECT id FROM ?# WHERE login = ?', USERS_TABLE, $data['login']);
		if($reggedID && ($reggedID != $editID)){
			$site->assign('error', 'Такой логин уже зарегистрирован в системе');
			$site->render();
		}
		
		if(!preg_match(LOGIN_PREG, $data['login'])){
			$site->assign('error', 'Неправильный логин');
			$site->render();	
		}
	}
	$salt = $editUser->getRandSalt();
	$db->query('UPDATE ?# SET ?a WHERE id = ?d', USERS_TABLE, array(
		//'login' => $data['login'],
		'full_name' => $data['full_name'],
		'nick' => $data['full_name'],
		//'datereg' => time(),
		'rights' => serialize($data['can'])
	), $editID);
	
	$site->assignSession('msg', 'Права сохранены');
	$site->redirect(MODULE_URL);
}



$site->render();

