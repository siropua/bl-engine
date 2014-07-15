<?php

$site->template('adminForm.tpl');

require_once(COMPILED_PATH.'/admin/rights.php');
$site->assign('rights', $_RIGHTS);


if(!empty($_POST['data'])){
	
	$data = array_map('trim', $_POST['data']);
	$data['can'] = $_POST['can'];
	
	$site->assign('data', $data);
	
	if($db->selectCell('SELECT id FROM ?# WHERE email = ?', USERS_TABLE, $data['email'])){
		$site->assign('error', 'Такой логин уже зарегистрирован в системе');
		$site->render();
	}
	
	if(!preg_match(LOGIN_PREG, $data['email'])){
		$site->assign('error', 'Неправильный логин');
		$site->render();	
	}
	
	$salt = $user->getRandSalt();
	$db->query('INSERT INTO ?# SET ?a', USERS_TABLE, array(
		'email' => $data['email'],
		'password' => $user->hashPassword($data['p1'], $salt),
		'salt' => $salt,
		'full_name' => $data['full_name'],
		//'short_name' => $data['full_name'],
		//'name' => $data['full_name'],
		//'fname' => $data['full_name'],
		'nick' => $data['full_name'],
		'datereg' => time(),
		'rights' => serialize($data['can'])
	));
	
	$site->assignSession('msg', 'Пользователь создан');
	$site->redirect(MODULE_URL);
	
}



$site->render();