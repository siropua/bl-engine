<?php


require_once(ENGINE_FOLDER.'/classes/rWebApp.class.php');


final class rMyApp extends rWebApp{


	public static function getInstance()
	{
		if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
	}

}