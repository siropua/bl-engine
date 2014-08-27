<?php

if(defined('SIMPLE_APPLICATION') && SIMPLE_APPLICATION){
	/**
		Если включен режим простого сайта (без поддержки DB и аутентификации)
		То наследуемся от simpleApplication
	**/
	require_once 'classes/rSimpleApplication.class.php';
	class rApplication extends rSimpleApplication{}
}else
	require_once 'classes/rApplication.class.php';

define('STATIC_JS_URL', ROOT_URL.SITE_FOLDER.'/js/');
define('ENGINE_JS_URL', ROOT_URL.ENGINE_FOLDER.'/js/');
define('SITE_JS_URL', STATIC_URL.'js/');




class rWebApp extends rApplication{

	protected $templateContainer = 'index.tpl';
	public $stdTemplatesFolder = DESIGN_PATH;
	protected $templateFile = 'main.tpl';
	protected $notFoundTemplate = '404.tpl';

	protected $navPath = array();
	
	protected $page_title = '';
	protected $page_title_separator = ' / ';

	protected $headResources = array();

	protected $isForceAJAXHit = false; // там где мы уверены, что точно ajax



	protected function __construct(){
		parent::__construct();

		if(defined('IS_MULTISITE') && IS_MULTISITE)
			$this->stdTemplatesFolder .= '/'.$_SERVER['HTTP_HOST'] ;

		$this->setTemplate($this->templateFile);

		$this->setTitle($this->getSetting('default_title', '', false));
		$this->setKeywords($this->getSetting('default_keywords', '', false));
		$this->setDescription($this->getSetting('default_descr', '', false));
		
	}

	public static function getInstance()
	{
		if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
	}



	/** 
	assigments 
	*/
	/**
	* assignDefaults
	* @return void
	*/
	public function assignDefaults(){


		if($this->user->authed()){
			$this->assign('user', $this->user->getData());
			$this->assign('authed', true);
		}

		$this->assign('langs_files', $this->lang->getLangFiles());		
		$this->assign('ROOT_LANG', ROOT_URL.$this->lang->getCurLang().'/');

		$this->assign('_APP', $this);
		
		$this->assign('_APPMSG_NOTICE', $this->getMessages(APPMSG_NOTICE));
		$this->assign('_APPMSG_ERROR', $this->getMessages(APPMSG_ERROR));
		$this->assign('_APPMSG_OK', $this->getMessages(APPMSG_OK));

		$this->assign('_APP_HEAD_RESOURCES', $this->getHeadResources());

		$this->assign('_BREADCRUMBS', $this->getNavPath());

	}
	
	
	/**
	* assignSession
	* Добавляет переменную в сессию, при следующей инициализации сайта эта переменная будет добавлена в шаблонизатор
	* @param mixed $var
	* @param mixed $value
	* @return void
	*/
	function assignSession($var, $value){
		@session_start();
		$_SESSION['saved_vars'][$var] = $value;
		session_write_close();
	}

	/**
		Работа с сообщениями.
		В веб-приложении сообщения хранятся в сессии, ибо возможен редирект
	**/
	public function addMessage($message, $level = APPMSG_NOTICE){
		@session_start();
		$_SESSION['messages'][$level][] = $message;
	} 


	public function getMessages($level){
		@session_start();
		if(empty($_SESSION['messages'][$level])) return '';
		$m = implode($this->messagesGlue, $_SESSION['messages'][$level]);
		$_SESSION['messages'][$level] = array();
		return $m;
	}

	public function reload($message = '', $level = APPMSG_NOTICE)
	{
		if($message) $this->addMessage($message, $level);
		$this->url->reloadPage();
	}



	/**
	 BREADCRUMBS 
	**************/

	/**
	* Получить Навигационый путь
	* @return mixed
	*/
	function getNavPath(){
		
		return $this->navPath;

		if(!$this->navPath) return '';
		$path = array();
		foreach($this->navPath as $n => $v){
			
			if(!empty($v['pre_title'])){
				$pre = $v['pre_title'];
			}else{
				$pre = '';
			}
			
			if($v['link'])
				$path[] = $pre.'<a href="'.$v['link'].'">'.$v['title'].'</a>';
			else
				$path[] = $pre.$v['title'];
		}
		return implode($this->navSeparator, $path);
	}

	/**
	* Добавить Навигационый путь
	* @param mixed $title
	* @param mixed $link
	* @param mixed $additional
	* @return void
	*/
	function addNavPath($title, $link = SELF_URL, $additional = array()){
		$this->navPath[] = array_merge(array('link' => $link, 'title' => $title), $additional);
	}

	/**
	* Убрать Навигационый путь
	* @param mixed $link
	* @return void
	*/
	function removeNavPath($link){
		unset($this->navPath[$link]);
	}

	/**
	* Установить Навигационый разделитель
	* @param mixed $separator
	* @return void
	*/
	function setNavSeparator($separator){
		$this->navSeparator = $separator;
	}

	/**
		RENDERS
	**/
	public function render($template = false, $container = false){

		if($template) $this->setTemplate($template);
		if(!$container) $container = $this->templateContainer;

		$this->assignDefaults();


		@session_start();
		if(isset($_SESSION['saved_vars']) && is_array($_SESSION['saved_vars'])){
			foreach($_SESSION['saved_vars'] as $n=>$v) $this->assign($n, $v);
			unset($_SESSION['saved_vars']);
		}
		session_write_close();

		if($this->user->authed() && !$this->isAJAXHit())
			$this->user->doHit();
		


		error_reporting(E_ALL ^ E_NOTICE);
		if(($container[0] == '/') || strpos($container, ':'))
		    $this->tpl->display($container);
		else
			$this->tpl->display($this->stdTemplatesFolder.'/'.$container);

		exit;
	}


	/**
	* Fetch page
	* Рендерит шаблон и возвращает его, а не выводит на экран
	* @param string $template Шаблон, который парсить
	* @param string $container Контейнер, который парсить
	* @return mixed
	*/
	function fetch($template = false, $container = false){

		if($template) $this->setTemplate($template);
		if(!$container) $container = $this->templateContainer;
		
		$this->assignDefaults();


		error_reporting(E_ALL ^ E_NOTICE);

		

		if(($container[0] == '/') || strpos($container, ':'))
		    return $this->tpl->fetch($container);
		else
			return $this->tpl->fetch($this->stdTemplatesFolder.'/'.$container);

	}

	/**
		RESOURCES 
	**/


	/**
	* Установить Title
	* @param mixed $title
	* @return void
	*/
	function setTitle($title){
		if(defined('DEFAULT_TITLE_POSTFIX') && DEFAULT_TITLE_POSTFIX){
			$t = $this->getSetting('default_title');
			if($t != $title)
				$title .= ' '.$t;
		}
		$this->page_title = $title;
		$this->assign('page_title', $title); 
	}
	
	public function add2Title($title, $separator = NULL)
	{
	    $this->page_title = $title . ($separator === NULL ? $this->page_title_separator : $separator) . $this->page_title;
	    $this->assign('page_title', $this->page_title);
	}
	
	
	/**
	* Установить Keywords
	* @param mixed $k
	* @return void
	*/
	function setKeywords($k){
		$this->assign('page_kws', $k);
	}
	
	/**
	* Установить Description
	* @param mixed $d
	* @return void
	*/
	function setDescription($d){
		$this->assign('page_descr', $d);
	}

	/**
	*	Добавляет ресурс в шаблон head_data.tpl
	**/
	public function addHeadResource($type, $key, $value = '')
	{
		$uniqKey = $type.'##'.md5($key);
		if(isset($this->headResources[$uniqKey])){
			unset($this->headResources[$uniqKey]);
			$this->internalError('WARNING', 'Resource ##'.$type.'## already added: '.$key.' ('.$value.')');
		}

		$this->headResources[$uniqKey] = array(
			'type' => $type,
			'key' => $key,
			'value' => $value
		);
	}

	public function getHeadResources()
	{
		return $this->headResources;
	}
	
	
	/**
	* Добавить CSS
	* @param mixed $file
	* @param bool $noVer добавлять ли инфу о версии файла
	* @return void
	*/
	function addCSS($file, $noVer = false){
		if(substr($file, 0, 6) == '~SITE/') $file = ROOT_URL.SITE_FOLDER.'/'.substr($file, 6);
		$file = $this->url->uniPath($file, DESIGN);
		$this->addHeadResource('css', $file, $noVer);
	}
	
	/**
	* Добавляет CSS файл только если он существует
	* @param mixed $file
	* @return void
	*/
	public function addCSSIfExists($file){
		if(file_exists(TEMPLATES_PATH.'/'.$file))
			$this->addCSS($file);
	}
	
	/**
	* Добавляет JS файл только если он существует
	* @param mixed $file
	* @return void
	*/
	public function addJSIfExists($file){
		if(file_exists(ROOT.'/js/'.$file))
			$this->addJS($file);
	}
	
	/**
	* getCSSFiles
	* @return mixed
	*/
	function getCSSFiles(){
		//return $this->cssFiles;
		throw new Exception('Method not working', 1);
		
	}
	
	/**
	* Добавить JS файл
	* @param bool $noVer добавлять ли инфу о версии файла
	* @param mixed $file
	* @return void
	*/
	function addJS($file, $noVer = false){
		if(substr($file, 0, 8) == '~DESIGN/') $file = DESIGN.substr($file, 8);
		$file = $this->url->uniPath($file, ROOT_URL.SITE_FOLDER.'/');
		$this->addHeadResource('js', $file, $noVer);
		
	}

	public function addFWJS($file){
		$this->addJS(ROOT_URL.ENGINE_FOLDER.'/fws/'.$file);
	}

	public function addFWCSS($file){
		$this->addCSS(ROOT_URL.ENGINE_FOLDER.'/fws/'.$file);
	}

	/**
	* getJSFiles
	* @return mixed
	*/
	function getJSFiles(){
		throw new Exception('Method not working', 1);
		return $this->jsFiles;
	}


	/**
	* Добавляет MetaLink
	* @param mixed $data
	* @return void
	*/
	public function addMetaLink($data){
		$this->metaLinks[] = $data;
	}

	/**
	* Инициализирует внешний фреймворк
	**/
	public function initExternalFW($fwObject){
		
		if(is_string($fwObject)) $fwObject = new $fwObject;
		if(!is_object($fwObject)) throw new Exception('FW Plugin must be an object or string');
		if(!is_subclass_of($fwObject, 'riExternalFW')) throw new Exception('FW Plugin must be instance of riExternalFW interface');
		

		$fwObject->init($this);

	}

	/**
		Templates
	**/
	/**
	* Устанавливает папку по умолчанию для темплейтов
	* @param mixed $folder папка
	* @return bool true если папка установлена успешно. иначе false
	*/
	function setStdTemplatesFolder($folder){
		if((substr($folder, 0, 1) != '/') && !strpos($folder, ':'))
			$folder = TEMPLATES_PATH . '/' . $folder;
		
		$folder = realpath($folder);
		
		if(!$folder) return false;
		
		$this->stdTemplatesFolder = $folder;
		return true;
	}
	
	/**
	* Устанавливает контейнер (родительский шаблон который будем рендерить)
	* @param mixed $container
	* @return void
	*/
	function setContainer($container)
	{
		$this->templateContainer = $container;
	}
	

	
	/**
	* устанавливает внутрений темплейт
	* @param mixed $template
	* @return void
	*/
	public function setTemplate($template)
	{		
		$this->templateFile = $template; 
		$this->assign('_APPPAGE_TEMPLATE', $this->templateFile); 
	}

	/**
	* Проверяет, есть ли шаблон с заданным именем (расширение .tpl добавляется автоматически при надобности).
	* Если есть - устанавливает его и возвращает TRUE. Иначе просто возвращает FALSE
	* @param mixed $template
	* @return bool
	*/
	function checkTemplate($template)
	{
		if(!preg_match('/\.tpl$/', $template)) $template .= '.tpl';
		if(file_exists(TEMPLATES_PATH.'/'.$template))
		{
			$this->setTemplate($template);
			return true;
		}
		return false;
	}

	/**

		Errors

	*/

	/**
	* notFound (страница не найдена)
	* @param bool $template
	* @return void
	*/
	public function notFound($template = false){
		if(!$template)
			$template = $this->notFoundTemplate;
		header("HTTP/1.0 404 Not Found");
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		
		$this->render($template);
	}

	/**
	* internalError Внутренняя ошибка без показа юзеру
	* @param string $type ТИП: ERROR, WARNING, NOTICE
	* @param string $text Текст ошибки
	* @return void
	* @todo РЕАЛИЗОВАТЬ!
	**/
	public function internalError($type, $text)
	{
		return;
	}


	/**
		Some getters
	**/

	public function getCurLang()
	{
		return $this->lang->getCurLang();
	}


	/**
	* Принудительно ставит флаг, что приложение AJAX
	* @var bool $f
	**/
	public function setForceAJAXHit($f = true)
	{
		$this->isForceAJAXHit = $f;
	}

	/**
	* Возвращает, AJAX-приложение или обычное
	* @return bool
	**/
	public function isAJAXHit()
	{
		return $this->isForceAJAXHit ||
			(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));

	}

	
}