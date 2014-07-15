<?php

define('CONFIGS_PATH', realpath('../../../configs'));

if(file_exists(CONFIGS_PATH.'/main.php')){
	header('Location: index.php');
	exit;
}

if(!empty($_POST['CONFIG'])){
	$c = $_POST['CONFIG'];
	$c['ENGINE_FOLDER'] = basename(realpath('../../'));
	if(empty($c['SIMPLE_APPLICATION'])) $c['SIMPLE_APPLICATION'] = 'false';

	/** Путь к рлибу должен быть абсолютным **/
	if(isset($c['RLIB_PATH'])){
		$c['RLIB_PATH'] = trim($c['RLIB_PATH']);
		if(substr($c['RLIB_PATH'], 0, 1) != '/'){
			$c['RLIB_PATH'] = realpath('../../../'.$c['RLIB_PATH']);
		}
	}

	$files = glob('../configs/*.php');
	if(!empty($_POST['imagick_off'])) $c['IMAGICK_EXISTS'] = '// ';
	foreach($files as $f){
		$cont = file_get_contents($f);
		foreach($c as $k=>$v) $cont = str_replace('{%%'.$k.'%%}', $v, $cont);
		$cont = preg_replace('~{%%[a-z0-9_-]+%%}~i', '', $cont);
		file_put_contents(CONFIGS_PATH.'/'.basename($f), $cont);
		@chmod(CONFIGS_PATH.'/'.basename($f), 0666);
	}
	@chmod(CONFIGS_PATH, 0755); // возвращаем права доступа
	header('Location: index.php');
	exit;
}

?><html>
<head>
<?php include 'head_data.html'; ?>
<script type="text/javascript">
	$(function(){
		$('#simpleCB').on('change', function (e) {
			
			var is_simple = $(this).is(':checked');
			
			if(is_simple){
				$('.notsimple').attr('disabled', 'disabled');
			}else{
				$('.notsimple').removeAttr('disabled');
			}

		});

	});

</script>
</head>

<body>
<h1>Настройка конфигов</h1>
<?php

try{
	/**
	Проверяем, всё ли на месте и можно ли стартовать
	*/
	if(file_exists(CONFIGS_PATH.'/main.php')) 
		throw new Exception('Конфиги установлены. <a href="new.php">Остальная настройка</a>');

	if(!is_writable(CONFIGS_PATH)) 
		throw new Exception('Папка с конфигами ('.CONFIGS_PATH.') недоступна для записи, сделайте <pre>chmod 777 config</pre> (мы потом уберем сами) или загрузите конфиги самостоятельно');
	

	?>
	<form action="" method="post" class="stdForm form">
		Хост базы даных: <br/><input type="text" name="CONFIG[DB_HOST]" size="20" class="needed notsimple" required="required" value="localhost" /><br/>
		Логин базы даных: <br/><input type="text" name="CONFIG[DB_USER]" size="20" class="needed notsimple" required="required" /><br/>
		Пароль базы даных: <br/><input type="password" name="CONFIG[DB_PASS]" size="20" class="notsimple"/><br/>
		Имя базы даных: <br/><input type="text" name="CONFIG[DB_NAME]" size="20"  class="needed notsimple" required="required" /><br/>
		Кодировка базы даных: <br/><input type="text" name="CONFIG[DB_SET_NAMES]" size="20"  class="needed notsimple" required="required" value="UTF8" /><br/><br/>
		
		<p><label class="checkbox">
        <input type="checkbox" name="CONFIG[SIMPLE_APPLICATION]" value="true" id="simpleCB"> Простой режим сайта
      </label>
      	<div class="muted">Простой режим - отключает базу данных и аутентификацию на сайте.</div>
      </p><br>
		
		Папка с сайтом: <br/><input type="text" name="CONFIG[SITE_FOLDER]" size="20" value="site"  class="needed" required="required" /><br/>
		Папка со статическими TPL: <br/><input type="text" name="CONFIG[STATIC_TPL_FOLDER]" size="20" value="static"  class="needed" required="required" /><br/>
		Путь к админке: <br/><input type="text" name="CONFIG[ADMIN_FOLDER]" size="20" value="admin"  class="needed" required="required" /><br/><br/>


		Тип аутентификации: <br/><select name="CONFIG[USER_REGISTER_TYPE]" class="notsimple">
			<option value="email">E-Mail</option>
			<option value="login">Логин</option>
		</select>
		<br/>

		Тип favicon: <br/><select name="CONFIG[FAVICON_FILE_TYPE]">
			<option value="">нет иконки</option>
			<option value="ico">ICO</option>
			<option value="png">PNG</option>
		</select>
		<br/><br/>

		Путь к ImageMagick: <br/><input type="text" name="CONFIG[IMAGICK_PATH]" size="40" value="/usr/local/bin/" id="fim" /><br/>
		<label><input type="checkbox" name="imagick_off" value="1" onclick="$('#fim').attr('disabled', this.checked ? 'disabled' : ''); "/> Не использовать ImageMagick, использовать GD (лоховской режим)</label>
		<br/><br/>
		
		Путь к rLib: <br/><input type="text" name="CONFIG[RLIB_PATH]" size="40" value="../.." /><br>
		Если путь начинается с <b>/</b> &mdash; будет использоваться абсолютный путь. Иначе &mdash; относительный от <?php echo realpath('../../..').'/'; ?>


		<br/>
		<br/>

		<label><input type="checkbox" name="CONFIG[SIMPLE_BLOG_MODE]" value="1" checked/> Простой режим блога (один блог, адреса типа site.com/post-path.html а не site.com/blog/post-path.tpl)</label>
		<br/><br/>


		<button type="submit" class="saveButton okButton btn btn-success">Создать конфиги</button>
	</form>

	<?php


}catch(Exception $e){
	?>
		<div class="alert alert-error"><?php echo $e->getMessage(); ?></div>
	<?php
}
?>
</body>
</html?