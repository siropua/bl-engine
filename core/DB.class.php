<?php



namespace ble;

class DB
{

	static protected $db = NULL;

	protected function __construct()
	{
		
		
	}

	public static function getInstance()
	{
		if (self::$db === NULL) {

            require_once "rlib/rDBSimple.php";
			self::$db = \rDBSimple::connect('mypdo://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME);
			self::$db->setErrorHandler('stdDBErrorHandler');
			if(defined('DB_SET_NAMES') && DB_SET_NAMES)
				self::$db->query('SET NAMES '.DB_SET_NAMES);

			if(function_exists('cache_Memcache'))
				self::$db->setCacher('cache_Memcache');

        }
        return self::$db;
	}

}