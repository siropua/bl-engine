<?php

require_once 'classes/admin/RACStruct.php';
require_once 'classes/iModulesFactory.class.php';

if(!defined('ADMIN_JS_URL'))
	define('ADMIN_JS_URL', ADMIN_URL.'js/');

if(!defined('ADMIN_DESIGN'))
	define('ADMIN_DESIGN', ADMIN_URL.'templates/');

if(!defined('ADMIN_DESIGN_THEME'))
	define('ADMIN_DESIGN_THEME', 'gray');

if(!defined('ADMIN_DESIGN_URL'))
	define('ADMIN_DESIGN_URL', ROOT_URL.ENGINE_FOLDER.'/admin/design/'.ADMIN_DESIGN_THEME.'/');

if(!defined('ADMIN_DESIGN_PATH'))
	define('ADMIN_DESIGN_PATH', ENGINE_PATH.'/admin/design/'.ADMIN_DESIGN_THEME);


class rAdminModulesFactory extends iModulesFactory{



	public function __construct(rApplication $app){
		parent::__construct($app);
		$app->tpl->template_dir = ENGINE_PATH.'/admin/';
		$this->menu = $this->getUserMenu();

		// $app->dump($this->menu); exit;

		$this->app->assign('_ADMIN_MENU', $this->menu);

		
		$this->app->assign('ADMIN_URL', ADMIN_URL);
		$this->app->assign('ADMIN_DESIGN_URL', ADMIN_DESIGN_URL);
		$this->app->assign('ADMIN_DESIGN', ADMIN_DESIGN);
		$this->app->assign('ADMIN_IMG', ADMIN_DESIGN.'img/');
		$this->app->setStdTemplatesFolder(ENGINE_PATH.'/admin/');
		$this->app->assign('ADMIN_JS', ADMIN_JS_URL);

		$this->app->setContainer(ADMIN_DESIGN_PATH.'/tpl/index.tpl');
	}


	/**
	* @TODO: ПЕРЕПИСАТЬ! Менюхой не должна заведовать фактори!
	* Returns user menu depending on user rights
	*/
	function getUserMenu(){
		if(!$this->app->user->authed()) return array();
		if(!$this->app->user->can('admin')) return array();
		$menu = new RACMenuWorker;
		return $menu->getUserMenu($this->app->user);
	}

	public function getModule(){

		if(!$this->app->user->authed()){
			return $this->getLoginModule();
		}

		if(!$this->app->user->can('admin')){
			throw new rNotFound();
		}

		$this->app->url->redirect2RightURL('/');


		/** ежели у нас индекс - открываем индекс **/
		if(!$this->rURL->path(2)){
			return $this->getIndexModule();
		}

		if(!$this->app->url->path(4)) throw new rNotFound();

		

		if($this->rURL->path(2) == 'module'){
			$s = $this->app->url->path(3); // section
			$m = $this->app->url->path(4); // module
			$t = $this->app->url->path(5); // tab

			if(!$this->app->user->can('admin/'.$s.'/'.$m)) 
				throw new rNotFound();

			if(@!$moduleInfo = $this->menu[$s]['modules'][$m]){
				
				throw new rNotFound();
			}
				

			$modulePath = SITE_PATH.'/'.ADMIN_FOLDER.'/'.$s.'/'.$m;
			
			if(!is_dir($modulePath)){
				$modulePath = ENGINE_PATH.'/admin/modules/'.$s.'/'.$m;
				if(!is_dir($modulePath)) throw new rNotFound();
				$moduleInfo['res_url'] = ROOT_URL.ENGINE_FOLDER.'/admin/modules/'.$s.'/'.$m.'/';
			}else{
				$moduleInfo['res_url'] = ROOT_URL.SITE_FOLDER.'/'.ADMIN_FOLDER.'/'.$s.'/'.$m.'/';
			}



			$moduleInfo['ajax_url'] = ROOT_URL.'ajax/'.ADMIN_FOLDER.'/'.$s.'/'.$m.'/';
			$moduleInfo['json_url'] = ROOT_URL.'json/'.ADMIN_FOLDER.'/'.$s.'/'.$m.'/';

			define('MODULE_PATH', $modulePath);


			$moduleInfo['path'] = $modulePath;
			

			
			
			if(!empty($moduleInfo['tabs'])){
				$key = $t ? $t : key($moduleInfo['tabs']);

				$moduleInfo['tabs'][$key]['active'] = 1;
			}


			$this->app->tpl->template_dir = $modulePath;


			
			$this->app->setTemplate($m.'.tpl');

			$this->app->addNavPath($this->menu[$s]['name'], '');
			$this->app->addNavPath($this->menu[$s]['modules'][$m]['name'], $moduleInfo['url']);
			

			$moduleFilename = $t ? $t.'.php' : $m.'.php';
			$moduleClassname = $t && ($t != $m) ? 'module_'.$m.'_'.$t : 'module_'.$m;
			if(!file_exists($modulePath.'/'.$moduleFilename)){
				$moduleFilename = $m.'.php';
				if(!file_exists($modulePath.'/'.$moduleFilename)){
					throw new Exception('Cant find module file');
				}
				$moduleClassname = 'module_'.$m;
			}

			require_once $modulePath.'/'.$moduleFilename;

			if(!class_exists($moduleClassname))
				throw new Exception('Cant find module class');

			if($t && method_exists($moduleClassname, 'Run_'.$t))
				$moduleInfo['moduleMethod'] = 'Run_'.$t;

			$moduleInfo['tab'] = $t;
				

			$this->app->assign('_M', $moduleInfo);

			$module =  new $moduleClassname($this->app, $moduleInfo);

			if(file_exists(MODULE_PATH.'/'.$m.'.js')) $module->addJS($m.'.js');
			if(file_exists(MODULE_PATH.'/'.$m.'.css')) $module->addCSS($m.'.css');
			

			return $module;

		}



		throw new rNotFound();
		
	}

	/**
		Возвращаем аяксовый модуль 
		* для удобства /ajax/admin/section/module/file/method/
		*                1    2      3       4     5     6
	**/
	public function getAJAXModule()
	{
	
		if(!$this->app->user->can('admin')){
			//echo $this->app->tpl->template_dir; exit;
			throw new rNotFound();
		}


		if(!$this->app->url->path(5)) throw new rNotFound();

		$s = $this->app->url->path(3); // section
		$m = $this->app->url->path(4); // module
		$f = $this->app->url->path(5);
		if(!$f) $f = $m;

		if(!$this->app->user->can('admin/'.$s.'/'.$m)) 
				throw new rNotFound();

		if(@!$moduleInfo = $this->menu[$s]['modules'][$m])
			throw new rNotFound();

		$modulePath = $this->getModulePath($s, $m);
		$this->app->tpl->template_dir = $modulePath;
		$this->app->setStdTemplatesFolder($modulePath);
	    


		if(!file_exists($modulePath.'/'.$f.'.php')){
			if(file_exists($modulePath.'/'.$m.'.php')){
				$f = $m;
			}else throw new rNotFound();
		} 
		require_once $modulePath.'/'.$f.'.php';
		
		$moduleClassname = 'module_'.$m.($f == $m ? '' : '_'.$f);

		if(!class_exists($moduleClassname))
				throw new Exception('Cant find module class');
				
		$this->app->assign('_M', $moduleInfo);

		$module =  new $moduleClassname($this->app, $moduleInfo);
		
		return $module;
	}


	static public function getModulePath($s, $m)
	{
		$modulePath = SITE_PATH.'/'.ADMIN_FOLDER.'/'.$s.'/'.$m;
		if(!is_dir($modulePath)){
			$modulePath = ENGINE_PATH.'/admin/modules/'.$s.'/'.$m;
			if(!is_dir($modulePath)) throw new rNotFound();
			
		}

		return $modulePath;
	}



	/**
		берем главную админки 
	**/
	public function getIndexModule(){
		if(file_exists(SITE_PATH.'/'.ADMIN_FOLDER.'/index.php')){
			require_once SITE_PATH.'/'.ADMIN_FOLDER.'/index.php';
		}else{
			require_once ENGINE_PATH.'/admin/index.php';
		}

		return new admin_module_index($this->app, false);
	}

	public function getLoginModule()
	{
		require_once ENGINE_PATH.'/modules/login/index.php';
		return new module_login($this->app);
	}

}