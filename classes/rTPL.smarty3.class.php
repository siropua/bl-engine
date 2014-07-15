<?php

require_once('rlib/smarty3/Smarty.class.php');
class rTPL extends Smarty{
	public $timer;

	function __construct(){
		parent::__construct();
		$this->caching 	        = false;
		$this->template_dir     = TEMPLATES_PATH.'/';
		$_SERVER['HTTP_HOST'] = preg_replace('~^www\.~', '', $_SERVER['HTTP_HOST']);
		$this->compile_dir	    = COMPILED_PATH.'/';	
		$this->config_dir       = LANG_PATH.'/'.DEF_LANG;			
		$this->cache_dir        = CACHE_PATH;		
		$this->config_booleanize= false;
		
		// $this->debugging = true;

		$this->cache_modified_check = true;

		$this->startTiming();
		
		//$this->lang = DEF_LANG;
		
		
	}
	
/*	function display($template,$cacheid = NULL, $compile_id = NULL, $parent = NULL){
		error_reporting(E_NONE);
		ini_set('display_errors', 'no');
		$this->assignDefaults();
		try{
			parent::display($template,$cacheid);
		}catch(SmartyCompilerException $e){
			die($e->getMessage());
		}
	}
*/
	function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false){
		error_reporting(0);
		ini_set('display_errors', 'no');

		$this->assignDefaults();

		try{
			return parent::fetch($template, $cache_id, $compile_id, $parent, $display);
		}catch(SmartyCompilerException $e){
			die($e->getMessage());
		}

	}


	function preFetch($template,$cacheid = NULL){
		$this->assignDefaults();
		return parent::fetch($template,$cacheid);
	}

	function assignDefaults(){
		$this->assign("CACHING", $this->caching);
		$this->assign("DESIGN", DESIGN);
		$this->assign("IMG", IMAGES_URL);
		$this->assign("SITE_IMG", DESIGN.$_SERVER['HTTP_HOST'].'/images/');
		$this->assign("SERVER_ABSOLUTE", SERVER_URL);
		$this->assign("SERVER_ROOT", ROOT_URL);
		$this->assign("ROOT", ROOT_URL);
		$this->assign("CODETIME", $this->stopTiming());
		$this->assign("SELF", SELF_URL);
		$this->assign("USERS_URL", USERS_URL);
		$this->assign("STATIC", STATIC_URL);
	}


	function startTiming(){
		$microtime = microtime();
		$microsecs = substr($microtime, 2, 8);
		$secs = substr($microtime, 11);
		$this->timer = "$secs.$microsecs";
	}


	function stopTiming(){
		$microtime = microtime();
		$microsecs = substr($microtime, 2, 8);
		$secs = substr($microtime, 11);
		$endTime = "$secs.$microsecs";
		return round(($endTime - $this->timer),4);
	}
}
