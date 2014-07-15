<?php


abstract class iModulesFactory{

	protected $app;
	protected $db;
	protected $rURL;

	public function __construct(rApplication $app){
		$this->app = $app;
		if(!empty($app->db))
			$this->db = $app->db;
		$this->rURL = $app->url;
	}

	public abstract function getModule();

}