<?php

/** эксцепшены **/
require_once ENGINE_PATH.'/classes/rException.class.php';
/** цепляем, если есть, файл с описанием исключений для сайта **/
if(file_exists(SITE_PATH.'/lib/exceptions.php'))
	include_once(SITE_PATH.'/lib/exceptions.php');

if(file_exists(__DIR__.'/vendor/autoload.php')) require_once __DIR__.'/vendor/autoload.php';

/**
	перехват ошибок выполнения
**/
//require_once('rlib/errorhook/Listener.php');
//require_once('rlib/errorhook/rSiteNotifier.php');
//$errorsHandler = new Debug_ErrorHook_Listener();
//$errorsHandler->addNotifier(new rSiteNotifier(rSiteNotifier::LOG_ALL));

require_once(__DIR__.'/init/errorhook.php');


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

	require_once 'core/baseTableModel.class.php';
	require_once 'core/baseListModel.class.php';
	require_once 'core/baseTreeModel.class.php';
	require_once 'core/router.class.php';
	require_once 'core/dates.class.php';
	



	// автоподключение классов 
	spl_autoload_register(function($class){
		if(substr($class, 0, 3) == 'rMy'){
			$file2include = $class.'.class.php';
			if(file_exists(SITE_PATH.'/lib/'.$file2include))
				include_once SITE_PATH.'/lib/'.$file2include;
			else
				include_once ENGINE_PATH.'/lib/'.$file2include;
		}elseif (substr($class, 0, 10) == 'basemodel_') {
			$file2include = substr($class, 10).'.class.php';
			
			if(file_exists(SITE_PATH.'/models/base/'.$file2include)){
				include_once SITE_PATH.'/models/base/'.$file2include;
				return;
			} //else echo 'FILE NOT FOUND '.SITE_PATH.'/models/base/'.$file2include;
			exit;
		}elseif (substr($class, 0, 6) == 'model_') {
			$file2include = substr($class, 6).'.class.php';
			
			if(file_exists(SITE_PATH.'/models/'.$file2include)){
				include_once SITE_PATH.'/models/'.$file2include;
				return;
			}
		}

		if(strpos($class, '\\')){
			// используем неймспейсы (разложено по каталогам)
			$parts = explode('\\', $class);
			$file2include = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.class.php';

			if(substr(end($parts), 0, 3) == 'rMy'){
				

				if(file_exists(SITE_PATH.'/lib/'.$file2include))
				{
					include_once SITE_PATH.'/lib/'.$file2include;
				}
				elseif(file_exists(ENGINE_PATH.'/lib/'.$file2include))
				{
					include_once ENGINE_PATH.'/lib/'.$file2include;
				}
				
			}else{
				if (file_exists(ENGINE_PATH.'/classes/'.$file2include)) {
					include_once ENGINE_PATH.'/classes/'.$file2include;
				}
			}
			
		}
	});
	


}catch(dbException $e){
	header("Content-Type: text/html; charset=UTF-8");
	$info = $e->getInfo();
	rSiteNotifier::outputError($info['message'], $info, TEMPLATES_PATH.'/errors/fatal.tpl', 'E_DB_ERROR', true);
}


function formatDateTime($date = 0, $params = array())
{
	return ble\Dates::getInstance()->formatDateTime($date, $params);
}

function make_get_string($url, $var, $val = null){
	$path = explode('?', $url);
	if (!empty($path[1])){
		if (preg_match('/'.$var.'(=[^&]*)?/', $path[1])){
			$path[1] = preg_replace('/'.$var.'(=[^&]*)?/', $var.(!empty($val) ? '='.$val : ''), $path[1]);
		}else{
			$path[1] .= '&'.$var.(!empty($val) ? '='.$val : '');
		}
		return $path[0].'?'.$path[1];
	}
	return $url.'?'.$var.'='.$val;
}
