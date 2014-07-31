<?php

/** эксцепшены **/
require_once ENGINE_PATH.'/classes/rException.class.php';
/** цепляем, если есть, файл с описанием исключений для сайта **/
if(file_exists(SITE_PATH.'/lib/exceptions.php'))
	include_once(SITE_PATH.'/lib/exceptions.php');



/**
	перехват ошибок выполнения
**/
require_once('rlib/errorhook/Listener.php');
require_once('rlib/errorhook/rSiteNotifier.php');
$errorsHandler = new Debug_ErrorHook_Listener();
$errorsHandler->addNotifier(new rSiteNotifier(rSiteNotifier::LOG_ALL));

/**
	пагнали подключать все необходимое
**/
try{

	if(defined('MEMCACHE_PORT') && class_exists('Memcache')){
	        $_MEMCACHE = new Memcache;
	        $_MEMCACHE->connect('localhost', MEMCACHE_PORT);

	        function cache_Memcache($key, $value){
	                global $_MEMCACHE;
	                if($value !== null){
	                        $_MEMCACHE->set($key, $value);
	                }
	                if($value === null){
	                        return $_MEMCACHE->get($key);
	                }
	        }
	}



	// автоподключение классов 
	spl_autoload_register(function($class){
		if(substr($class, 0, 3) == 'rMy'){
			$file2include = $class.'.class.php';
			if(file_exists(SITE_PATH.'/lib/'.$file2include))
				include_once SITE_PATH.'/lib/'.$file2include;
			else
				include_once ENGINE_PATH.'/lib/'.$file2include;
		}

		if(strpos($class, '\\')){
			// используем неймспейсы (разложено по каталогам)
			$parts = explode('\\', $class);
			if(substr(end($parts), 0, 3) == 'rMy'){
				$file2include = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.class.php';
				if(file_exists(SITE_PATH.'/lib/'.$file2include))
					include_once SITE_PATH.'/lib/'.$file2include;
				else
					include_once ENGINE_PATH.'/lib/'.$file2include;
			}
			
		}
	});
	

}catch(dbException $e){
	header("Content-Type: text/html; charset=UTF-8");
	$info = $e->getInfo();
	rSiteNotifier::outputError($info['message'], $info, TEMPLATES_PATH.'/errors/fatal.tpl', 'E_DB_ERROR', true);
}





$days_names=array(-2=>'позавчера', -1=>"вчера", 0=>"сегодня", 1=>"завтра", 2=>"послезавтра");

$monthNames=array(1=>"января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");

$shortMonthNames=array(1=>"янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек");

$weekNames=array("Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");

$shortWeekNames=array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс");


function formatDateTime($date = 0, $params = array())
{
	global $days_names, $monthNames, $shortMonthNames, $weekNames, $shortWeekNames;
	
	$date=(int)$date;
	if(!$date)$date=time();
	$date_str = date("j n Y", $date);
	list($dD, $dM, $dY)=explode(" ", $date_str);
	list($curD, $curM, $curY) = explode(" ", date("j n Y"));

	$time_str = date("H:i".(@$params['show_seconds'] ? ":s":""), $date);
	
	$days = (mktime(0, 0, 0, $dM, $dD, $dY) - mktime(0, 0, 0, $curM, $curD, $curY)) / (60*60*24) ;
	if(abs($days)<3)
	{
		return $days_names[$days].", $time_str";
	}

	$ret="";
	
	if(@!$params['hide_dayname'])
		$ret = $shortWeekNames[date('w', $date)].", ";
	$ret .= $dD.' '.$monthNames[$dM];
	
	if($dY != $curY)
		$ret .= ' '.$dY;
	
	$ret .= ', '.$time_str;

	return trim($ret);

}
