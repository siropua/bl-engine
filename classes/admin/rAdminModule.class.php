<?php




// require_once 'rlib/rsite/rSite.class.php';

require_once 'classes/rModule.class.php';



abstract class rAdminModule extends rModule{

	protected $me = array();

	public $db;

	
	protected $titlePathSeparator = ' / ';

	function __construct($app, $info){
		
		parent::__construct($app);

		$this->db = $app->db;
		$this->me = $info;
	}

	/******** SELECTORS *****************************/
	public function selectSection($section){
		$section = $this->app->url->URLize($section);

		if(empty($this->menu[$section])){
			$this->renderError($this->lang->Section_not_found);
		}

		$this->curSection = $section;

		$this->assign('_SECTION_INFO', $this->menu[$this->curSection]);
		$this->assign('_SECTION', $this->curSection);

		$this->assign('_SECTION_URL', ADMIN_URL.'module/'.$section.'/');

		$this->addNavPath($this->menu[$this->curSection]['name'], '');

		return true;
	}

	public function selectModule($section, $module = ''){


		if(!$module){
			$module = $section;
			$section = $this->curSection;
		}else $this->selectSection($section);

		$module = $this->rURL->URLize($module);

		if(empty($this->menu[$section]['modules'][$module])){
			$this->renderError($this->lang->Module_not_found, $this->lang->No_module_in_this_section);
		}

		$this->moduleInfo =& $this->menu[$section]['modules'][$module];

		$this->assign("_MODULE_INFO", $this->moduleInfo);

		$this->addNavPath($this->moduleInfo['name'], ADMIN_URL.'module/'.$this->curSection.'/'.$module.'/');
		
		$this->setTitle($this->moduleInfo['name'].
			$this->titlePathSeparator.
			$this->menu[$section]['name']);

		$this->assign('_MODULE_URL', ADMIN_URL.'module/'.$this->curSection.'/'.$module.'/');
		$this->assign('_MODULE_BASE', ADMIN_URL.'modules/'.$this->curSection.'/'.$module.'/');
		$this->assign('_MODULE_ROOT', ADMIN_URL.'module/'.$this->curSection.'/'.$module.'/');


		if(!empty($this->moduleInfo['tabs'])){
			$this->assign('_MODULE_TABS', $this->moduleInfo['tabs']);
			$k = array_keys($this->moduleInfo['tabs']);
			$this->selectTab($k[0]);
		}



		$this->curModule = $module;

		$module_tpl = false;
		if(@$this->moduleInfo['output'] != "only_php")
			if(@$this->moduleInfo['tpl'])
			{
				$module_tpl = $this->moduleInfo['tpl'];
			}else
			{
				if(file_exists($this->getModulePath()."/index.tpl"))
				{
					$module_tpl = "index.tpl";
				}else
				{
					$module_tpl = $module.".tpl";
				}
			}

		if($module_tpl){
			$this->assign('template', $module_tpl);
			$this->curTemplate = $module_tpl;
		}

		if(file_exists($addLang = $this->getModulePath().'/lang.'.DEF_LANG.'.ini'))
			$this->lang->addModuleLang($addLang);

		if(@$this->moduleInfo['js'])
			$this->addModuleJS($this->moduleInfo['js']);

		if(@$this->moduleInfo['style'])
			$this->addModuleCSS($this->moduleInfo['style']);

		$this->assign('_MODULE', $this->curModule);
		$this->assign('_MODULE_AJAX', ADMIN_URL."ajax/$section/$module/");


		$this->tpl->template_dir = $this->getModulePath() .'/';

		if(!is_dir(COMPILED_PATH."/admin/m")) @mkdir(COMPILED_PATH."/admin/m", 0777, true);
		if(!is_dir(COMPILED_PATH."/admin/m/$section/$module")){
			@mkdir(COMPILED_PATH."/admin/m/$section", 0777, true);
			@mkdir(COMPILED_PATH."/admin/m/$section/$module", 0777, true);
		}

		$this->tpl->compile_dir = COMPILED_PATH."/admin/m/$section/$module/";

		return true;
	}

	function selectTab($tab){

		if(empty($this->moduleInfo['tabs'])){
			$this->curTab = NULL;
			return NULL;
		}

		if(empty($this->moduleInfo['tabs'][$tab])){
			$k = array_keys($this->moduleInfo['tabs']);
			$this->curTab = $k[0];
		}else{
			$this->curTab = $tab;
		}
		
		$this->setTitle(
			$this->moduleInfo['tabs'][$tab]['name'].
			$this->titlePathSeparator.
			$this->moduleInfo['name'].
			$this->titlePathSeparator.
			$this->menu[$this->curSection]['name']
		);
		
		$this->assign('_MODULE_URL', ADMIN_URL.'module/'.$this->curSection.'/'.$this->curModule.'/'.$this->curTab.'/');
		$this->assign('_TAB', $this->curTab);

		return $this->curTab;
	}

	function getTab(){
		return empty($this->me['tab']) ? false : $this->me['tab'];
	}

	/**
	* Возвращает, каким методом мы будем стартовать основной код в объекте
	**/
	public function getMyRunMethod()
	{
		if(empty($this->me['tab'])) return 'Run';
		$m = 'Run_'.$this->me['tab'];
		if(method_exists($this, $m)) return $m;

		return 'Run';
	}

	
	
	// interface
	protected function updateMenu(&$menu){
		return;
	}

	/**
	 * Возвращает адрес файла, который необходимо проинклудить для работы модуля
	 *
	 * @return
	 **/
	function need2Require(){
		if(@$this->menu[$this->curSection]['modules'][$this->curModule]['output']=="only_tpl")
			return false;

		// finding script index
		$module_index = $this->getModulePath();
		if(!$module_index) return false;
		$module_index .= "/";

		if(@$this->moduleInfo['php'])
		{
			$module_index .= $this->moduleInfo['php'];
		}else
		{
			if($this->curTab && @$this->moduleInfo['autotabs'])
				if(file_exists($module_index.$this->curTab.'.php'))
					return $module_index.$this->curTab.'.php';

			if(file_exists($module_index."index.php"))
			{
				$module_index .= "index.php";
			}else $module_index .= $this->curModule.".php";
		}

		return $module_index;

	}
	
	function getPreIncludes(){
		$inc = array();
		$p = MODULES_PATH."/".$this->curSection;

		if(file_exists("$p/config.pre.php")) $inc[] = "$p/config.pre.php";
		if(file_exists("$p/init.php")) $inc[] = "$p/init.php";
		if(file_exists("$p/config.php")) $inc[] = "$p/config.php";
		
		$p .= "/".$this->curModule;

		if(file_exists("$p/config.pre.php")) $inc[] = "$p/config.pre.php";
		if(file_exists("$p/init.php")) $inc[] = "$p/init.php";
		if(file_exists("$p/config.php")) $inc[] = "$p/config.php";
		
		return $inc;
		
	}

	/************************ Manage resources *******/
	/******* CSS *******/

	function addModuleCSS($file){
		$this->cssFiles[] = MODULES_URL . $this->curSection.'/'.$this->curModule.'/'.$file;

	}


	/****** JAVASCRIPT ******/

	function addJS($file){
		$this->app->addJS($this->me['res_url'].$file);
	}
	
	function addAdminJS($file){
		$this->jsFiles[] = ADMIN_JS_URL . $file;
	}

	function addCSS($file){
		$this->app->addCSS($this->me['res_url'].$file);
	}

	function getModulePath(){
		if(!$this->curModule) return false;
		return MODULES_PATH."/".$this->curSection."/".$this->curModule;
	}



	/**
		MENU WORKING
	**/

	public function parseModules($forceReload = false){
		$menu = new RACMenuWorker;
		return $menu->parseModules($forceReload);
	}

	/**
	* Редиректит на главную страницу модуля, можно опционально задать собщение
	**/
	public function go2index($msg = '', $msgType = APPMSG_NOTICE)
	{
		if($msg)
			$this->app->addMessage($msg, $msgType);
		$this->app->url->redirect($this->me['url']);
	}



}

class FormNotValid extends Exception{}
	
class EngineError extends Exception{}


/** класс для тикера **/
abstract class RACTicker{
	
	/** интервал обновления **/
	public $interval = 10;
	protected $site = null;

	public function __construct(rSite $site){
		$this->site = $site;
	}

	/** получаем данные тикера **/
	abstract public function getData();

}
