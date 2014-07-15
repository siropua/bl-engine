<?php

/** Инклудим конфиги **/
if(!include_once 'configs/web.php') die('Site not installed!');

/** Инклудим движок **/
include_once ENGINE_PATH.'/init.php';

$module = null;

try{

	

	$_APP = rMyApp::getInstance();

	// логинимся
	if(!empty($_POST['login2site'])){
		$result = rMyAuthUser::login($_APP, $_POST['login2site']);
		if($result === true){
			$_APP->url->reloadPage();
		}else{
			$_APP->addMessage($_APP->lang->__get($result), APPMSG_ERROR);
		}
	}

	$methodName = 'Run';

	// выбираем чо как
	if(defined('ADMIN_FOLDER') && ADMIN_FOLDER && ($_APP->url->path(1) == ADMIN_FOLDER)){
		// админочка!
		require_once CONFIGS_PATH.'/admin.php';
		$_Autoexec = new rMyAdminAutoexec;
		$_Factory = new rMyAdminModulesFactory($_APP);
		
	}else{
		$_Autoexec = new rMyAutoexec;
		$_Factory = new rMyModulesFactory($_APP, $_EngineModulesOrder);
		
	}

	
	$_Autoexec->beforeCreate($_APP, $_Factory);
	$module = $_Factory->getModule();

	if(!$module) throw new Exception($_APP->lang->Cant_create_module);
	
	
	$_Autoexec->beforeRun($_APP, $module);
	$methodName = $module->getMyRunMethod();
	$module->Init();
	$module->$methodName();
	$_Autoexec->afterRun($_APP, $module);

	$_APP->assign('_MODULE', $module);
	$_APP->render();
	

	

}catch(dbException $e){
	$info = $e->getInfo();
	rSiteNotifier::outputError($info['message'], $info, TEMPLATES_PATH.'/errors/fatal.tpl', 'E_DB_ERROR', true);
	
}catch(rNotFound $e){
	//if(!$module) $module = new rMySite($_APP);

	$_APP->notFound();

	
}catch(SmartyException $e){
	rSiteNotifier::outputError($e->getMessage(), '', TEMPLATES_PATH.'/errors/notice.tpl', 'E_TPL_ERROR');
	//exit;
}catch(Exception $e){
	// if($module)
	// 	$_APP->renderError($e->getMessage());
	// else
		rSiteNotifier::outputError('', $e->getMessage(), TEMPLATES_PATH.'/errors/fatal.tpl', 'E_ENGINE_ERROR', true);
}