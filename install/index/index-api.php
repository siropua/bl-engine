<?php


/** Инклудим конфиги **/
if(!include_once 'configs/web.php') die('Site not installed!');

define('IS_JSON_MODE', true); 


/** Инклудим движок **/
include_once ENGINE_PATH.'/init.php';

$module = null;



function echoJSON($data, $error_msg = '', $error_code = 0, $globalStatus = 200)
{
	if(!headers_sent()) header('Content-Type: application/json');

	$userInfo = array();
	$_APP = rMyApp::getInstance();
	$user = $_APP->user;
	if($user->authed()) $userInfo = array(
		'user_id' => $user->getID(),
		'user_login' => $user->email,
	);



	$r = json_encode(array(
			'meta' => array('code' => $globalStatus),
			'error' => array('message' => $error_msg, 'code' => $error_code),
			'data' => $data,
			'user' => $userInfo,
		));


	echo $r;

	exit;
}


try{

	if(empty($_POST))
	{
		if($postdata = trim(file_get_contents("php://input")))
		{
			if($postdata[0] == '{')
			{
				$_POST = @json_decode($postdata);
			}elseif (strstr($postdata, '=')) {
				parse_str($postdata, $_POST);
			}
		}

	}

	

	$_APP = rMyApp::getInstance();
	$_APP->loadComponents();
	$_APP->user->authed();

	

	$methodName = 'Run';

	
	$_Autoexec = new rMyAutoexec;
	$_Factory = new rMyAPIModulesFactory;

	$module = $_Factory->getModule();


	if(!$module) throw new Exception($_APP->lang->Cant_create_module);
	
	
	$methodName = $module->getMyRunMethod();
	$module->Init();
	echoJSON($module->$methodName());
	

}catch(dbException $e){
	$info = $e->getInfo();
	echoJSON(false, $info['message'], 2001);
	
}catch(rNotFound $e){
	//if(!$module) $module = new rMySite($_APP);

	echoJSON(false, $e->getMessage() ? $e->getMessage() : 'Not found', $e->getCode());

	
}catch(Exception $e){
	echoJSON(false, $e->getMessage(), $e->getCode());
	
}