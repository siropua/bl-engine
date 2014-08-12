<?php


/**
	Приложение.
	По сути просто содержит набор всех объектов, чтобы удобно передавать в модули.
	Наследовано от rSimpleApplication, отличается тем, что умеет работать с базой данных
**/

require_once 'classes/rSimpleApplication.class.php';
require_once 'rlib/settings.class.php';
require_once 'classes/rTableObj.class.php';
require_once 'core/DB.class.php';

abstract class rApplication extends rSimpleApplication{

	public $db;

	

	protected function __construct(){
		$this->initDB();
		parent::__construct();
		$this->lang->setDB($this->db);
		$this->lang->loadIDs();
	}


	protected function __clone()
	{
		// клонирование запрещено
	}

	protected function initDB(){
		$this->db = blEngine\DB::getInstance();

	}

	protected function initUser(){
		$this->user = new rMyUser($this->db);
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
			$this->settings = new siteSettings($table, $this->db);

		$this->settings->loadAll();

		$this->setTitle($this->getSetting('default_title', '', false));
		$this->setKeywords($this->getSetting('default_keywords', '', false));
		$this->setDescription($this->getSetting('default_descr', '', false));		
		
	}
	
	/**
	* Считывание настроек
	* @param mixed $key
	* @param string $default
	* @param bool $autoCreate
	* @return mixed
	*/
	public function getSetting($key, $default = '', $autoCreate = false){
		if($this->settings === NULL)
			$this->initSettings();
		return $this->settings->getValue($key, $default, $autoCreate);
	}

	/**
		Всякое вспомогательное
	**/



}
