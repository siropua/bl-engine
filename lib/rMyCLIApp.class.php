<?php


require_once(ENGINE_FOLDER.'/classes/rApplication.class.php');


class rMyCLIApp extends rApplication{


    public function __construct()
    {
	$this->initDB();
    }


}