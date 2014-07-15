<?php

$file = ROOT.'/sitemap.xml';

if(!is_writable($file)){
	$site->assign('error', 'Файл sitemap.xml не может быть изменен. Для возможности изменения смените на него права <br>chmod 666 sitemap.xml');
}else{
	$site->assign('writable', true);
}

if(!empty($_POST['content'])){
	file_put_contents($file, $_POST['content']);
	$site->assignSession('msg', 'Файл сохранён!');
	$site->redirect(SELF_URL);
}

if(file_exists($file))
	$site->assign('content', file_get_contents($file));

$site->template('sitemap.tpl');