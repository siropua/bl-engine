<?php


require_once(ENGINE_FOLDER.'/classes/rApplication.class.php');


class rMyCLIApp extends rApplication{


 //    protected function __construct()
 //    {
	// $this->initDB();
 //    }

	public static function getInstance()
	{
		if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
	}
}