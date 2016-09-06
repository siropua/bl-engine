<?php


/**
	Приложение.
	По сути просто содержит набор всех объектов, чтобы удобно передавать в модули
**/

define('APPMSG_NOTICE', 0);
define('APPMSG_OK', 1);
define('APPMSG_ERROR', -1);
define('APPMSG_USER_NOTICE', 10);
define('APPMSG_USER_OK', 100);
define('APPMSG_USER_ERROR', -100);

abstract class rSimpleApplication{

	public $tpl;
	public $url;
	public $lang;
	public $user;

	protected static $instance;

	public $var = array(); // массив с глобальными переменными

	protected $settings = NULL; // массив с кешем настроек

	/** сообщения **/
	protected $messages = array();
	protected $messagesGlue = ', ';


	protected function __construct(){
		$this->initTPL();
		$this->initURL();
		$this->initLang();
		$this->initUser();
	}


	public function loadComponents()
	{
		return  true;
	}


	

	/**
	* Инициализиуем юзера
	**/
	protected function initUser(){
		$this->user = new rMyUser(false);
	}


	protected function initTPL(){

		if(PHP_SAPI == 'cli'){
			$this->tpl = new rMyTPL_CLI;
		}else{
			$this->tpl = new rMyTPL;
		}
	}

	protected function initURL(){
		require_once 'rlib/rURLs.class.php';
		$this->url = new rURLs;
	}

	protected function initLang(){
		require_once('rlib/rLang.class.php');
		$this->lang = new rLang(LANG_PATH, $this->tpl);
		$this->lang->selectLang($this->url->getCurLang());
	}

	/**
		Работа с сообщениями
	**/
	public function addMessage($message, $level = APPMSG_NOTICE){
		$this->messages[$level][] = $message;
	}


	public function getMessages($level){
		if(empty($this->messages[$level])) return '';
		return implode($this->messagesGlue, $this->messages[$level]);
	}

	/**
		Работа с языковой частью
	**/
	/**
	* Добавить язык
	* @param mixed $file
	* @return void
	*/
	public function addLangFile($file){
		$this->lang->addLang($file);
	}
	
	/**
	* getLang
	* @param mixed $str
	* @return mixed
	*/
	public function getLang($str){
		return $this->lang->__get($str);
	}

	/**
		Работа с настройками SETTINGS
	**/
	/**
	* Запуск настроек
	* @param string $folder
	* @return void
	*/
	public function initSettings($table = 'site_settings'){
		if($this->settings == null)
			$this->settings = array();

		if(file_exists(CONFIGS_PATH.'/settings.php')){
			include CONFIGS_PATH.'/settings.php';
			$this->settings = defined('IS_MULTISITE') && IS_MULTISITE ? $_SITE_SETTINGS[HTTP_HOST] : $_SITE_SETTINGS;

		}

		$this->setTitle($this->getSetting('default_title', ''));
		$this->setKeywords($this->getSetting('default_keywords', ''));
		$this->setDescription($this->getSetting('default_descr', ''));		
		
	}
	
	/**
	* Считывание настроек
	* @param mixed $key
	* @param string $default
	* @param bool $autoCreate
	* @return mixed
	*/
	public function getSetting($key, $default = ''){
		if($this->settings === NULL)
			$this->initSettings();

		if(isset($this->settings[$key])) return $this->settings[$key];

		return $default;
	}

	/**
		Всякое вспомогательное
	**/

	/**
	* rApplication::assign() Добавляет в шаблонизатор переменную
	* @param string $var Имя переменной
	* @param mixed $value Значение переменной
	* @return void
	*/
	public function assign($var, $value){
		$this->tpl->assign($var, $value);
	}	

	public function assignDefaults(){
		
	}


	/**
		Работа с путями
	**/
	public function testPath($name, $path = 1)
	{
		return $this->url->path($path) == $name;
	}

	public function path($path)
	{
    	    return $this->url->path($path);
	}


	public function dump($variable, $exit = true, $var_dump = true)
	{
		echo '<pre>';
		if($var_dump)
		{
			var_dump($variable);
		}else
		{
			print_r($variable);
		}
		echo '</pre>';


		if($exit) exit;
	}

	/**
	* Простенький логгер
	*/
	public function log($string, $file = 'all.log')
	{
		if(is_array($string) || is_object($string))
			$string = print_r($string, 1);
		$ip = @$_SERVER['REMOTE_ADDR'];
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$string = trim('['.date('m-d H:i:s').' | '.$ip.'] '.$string)."\n";
		
		

		file_put_contents(VAR_PATH.'/logs/'.$file, $string, FILE_APPEND);
		return $string;
	}

}