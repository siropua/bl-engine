<?php

require_once(COMPILED_PATH.'/admin/rights.php');

if(!empty($_POST['newpass']))try{
	$npd = $_POST['newpass'];
	if(empty($npd['pass'])){
		throw new FormNotValid('Вы неправильно указали пароль!');
	}
	if(empty($npd['user_id'])){
		throw new EngineError;
	}
	$salt = $user->getRandSalt();
	$db->query('UPDATE ?# SET ?# = ?, salt = ? WHERE ?# = ?d', 
		USERS_TABLE, PASS_FIELD, $user->hashPassword($npd['pass'], $salt), $salt, UID_FIELD, $npd['user_id']);
	$site->assignSession('msg', 'Пароль изменен');
	$site->redirect(MODULE_URL);
	
}catch(FormNotValid $e){
	$site->assign('error', $e->getMessage());
}


if(isset($_GET['edit']) && ($editID = (int)$_GET['edit'])){
	require_once('editAdmin.php');
}


$list = $db->select('SELECT * FROM ?# WHERE rights != ""', USERS_TABLE);
$site->assign('list', $list);