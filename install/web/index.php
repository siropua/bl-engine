<html>
<head>
<?php include 'head_data.html'; ?>
</head>

<body><h1>Создание структуры</h1>
<?php


try{
	/**
	пробуем подключить конфиг
	*/
	if(!file_exists('../../../configs/main.php')) throw new Exception('Нет конфига! <a href="config.php">Создайте конфиг-файлы</a> и попробуйте снова');
	include('../../../configs/main.php');

		error_reporting(E_ALL);
		ini_set('display_errors', 'on');

	if(SIMPLE_APPLICATION){
		/**
			Простой режим. Просто проверим на возможность записи настроек.
		**/
		if(!is_writable(CONFIGS_PATH.'/settings.php')){
			throw new Exception('Файл с настройками ('.CONFIGS_PATH.'/settings.php) не записывамый!', 1);
			
		}
	}else{

		/**
		коннектимся к базе!
		**/

		function newDBErrorHandler($message, $info){	
		    print_r($message);
		    print_r($info);
		    exit;
		}

		require_once __DIR__.'/../../core/DB.class.php';
		

		require_once "rlib/rDBSimple.php";
		$db = rDBSimple::connect('mypdo://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME);
		$db->setErrorHandler('newDBErrorHandler');
		$db->query('SET NAMES UTF8');



		$tables_db = $db->selectCol('SHOW TABLES');
		if(in_array('users', $tables_db)) 
			throw new Exception('Настройка завершена');
		$tables_files = glob('../db/*.sql');
		foreach($tables_files as $f){
			$f = str_replace('.sql', '', basename($f));
			if(in_array($f, $tables_db)) throw new Exception('Таблица '.$t.' уже существует. Необходимо либо почистить базу, либо заполнять её руками');
		}

	}

	if(!empty($_POST['u'])){
		$u = $_POST['u'];
		$salt = substr(uniqid(''), -10);

		if(file_exists(CUSTOM_LIB_PATH.'/rMyUser.class.php'))
	    	    require_once(CUSTOM_LIB_PATH.'/rMyUser.class.php');
			else
			    require_once(LIB_PATH.'/rMyUser.class.php');

		if(SIMPLE_APPLICATION){
			/** записываем файл с настройками **/

			$pass = rMyUser::hashPassword($u['pass'], $salt);
			include CONFIGS_PATH.'/settings.php';

			$_SITE_SETTINGS['admin_login'] = $u['login'];
			$_SITE_SETTINGS['admin_pass'] = $pass;
			$_SITE_SETTINGS['admin_salt'] = $salt;
			$_SITE_SETTINGS['admin_nick'] = $u['nick'];

			$_SITE_SETTINGS['default_title'] = $u['default_title'];
			$_SITE_SETTINGS['default_kws'] = $u['default_kws'];
			$_SITE_SETTINGS['default_description'] = $u['default_description'];

			file_put_contents(CONFIGS_PATH.'/settings.php', '<?php $_SITE_SETTINGS = '.var_export($_SITE_SETTINGS, 1).';');

			
		}else{

			$user = new rMyUser(false);


			/**
			хуярим таблички!
			**/						
			foreach($tables_files as $f)
			{
			    $db->query(trim(file_get_contents($f)));
			}

			$db->query('INSERT INTO ?# SET ?a, ip = INET_ATON(?)', USERS_TABLE, array(
				LOGIN_FIELD => $u['login'],
				PASS_FIELD => $user->hashPassword($u['pass'], $salt),
				'rights' => 'a:1:{s:9:"allow_all";s:1:"1";}',
				'nick' => $u['nick'],
				'full_name' => $u['nick'],
				'email_confirmed' => 1,
				'datereg' => time(),
				'salt' => $salt
			), $_SERVER['REMOTE_ADDR']);


			$db->query('INSERT INTO site_settings SET ?a', array(
				'id' => 'default_title',
				'name' => 'Заголовок по умолчанию',
				'value' => $u['default_title'],
				'type' => 'string'
			));
			$db->query('INSERT INTO site_settings SET ?a', array(
				'id' => 'default_kws',
				'name' => 'Киворды по умолчанию',
				'value' => $u['default_kws'],
				'type' => 'string'
			));
			$db->query('INSERT INTO site_settings SET ?a', array(
				'id' => 'default_description',
				'name' => 'Описание по умолчанию',
				'value' => $u['default_description'],
				'type' => 'string'
			));
			$db->query('INSERT INTO site_settings SET ?a', array(
				'id' => 'site_head_area',
				'name' => 'Дополнительные теги в HEAD секции',
				'value' => '',
				'type' => 'text'
			));
		}
		echo '<div class="alert alert-success">Всё прошло успешно! Теперь по возможности сделайте svn update в каталоге исталл, чтобы закрыть к нему доступ!</div>';

	}else{
		?>

		<form action="" method="post" class="stdForm form">
			Почта админа: <br/><input type="email" name="u[login]" size="20" class="needed" required="required" /><br/><br/>
			Пароль админа: <br/><input type="password" name="u[pass]" size="20" /><br/><br/>
			Никнейм админа: <br/><input type="text" name="u[nick]" size="20"  class="needed" required="required" /><br/><br/><br/>

			Заголовок сайта по умолчанию: <br/><input type="text" name="u[default_title]" size="40" required="required" /><br/><br/>
			Киворды по умолчанию: <br/><input type="text" name="u[default_kws]" size="40" /><br/><br/>
			Описание по умолчанию: <br/><input type="text" name="u[default_description]" size="40" /><br/><br/>

			
			<button type="submit" class="saveButton okButton btn btn-success">Закончить настройки</button>
		</form>

		<?php

	}



}catch(Exception $e){
	?>
		<div class="error"><?php echo $e->getMessage(); ?></div>
	<?php
}
?>
</body>
</html?