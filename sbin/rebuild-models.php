<?php

require_once __DIR__.'/../../configs/main.php';
require_once ENGINE_PATH.'/init.php';

echo "\n\n\n";

/**
* Model Rebuilder
*/
class modelRebuilder
{
	protected $path;

	protected $app;

	function __construct($path)
	{
		if(!is_dir($path)){
			if(!mkdir($path, 0777, true)) throw new Exception('Cant create models dir ('.$path.')');
		}
		if(!is_writable($path)) throw new Exception('Models dir is not writable!');
		
		$this->path = realpath($path);
		if(!$this->path) throw new Exception('realpath for ('.$path.') returns false!', 1);

		$this->app = rMyCLIApp::getInstance();
		
	}

	public function rebuild($tables)
	{
		foreach ($tables as $t) {
			$this->rebuldTable($t);
		}
	}

	public function rebuldTable($table)
	{
		$this->log('Rebuilding '.$table);
		$tableInfo = $this->getTableInfo($table);

		print_r($tableInfo); exit;
	}


	public function getTableInfo($table)
	{
		# code...
	}


	public function log($msg)
	{
		echo is_array($msg) || is_object($msg) ? print_r($msg, 1) : $msg;
		echo "\n";
	}
}

try{

	echo "Starting rebuild...\n";

	$rebuilder = new modelRebuilder(SITE_PATH.'/models/base');

	$rebuilder->rebuild(array(
		'hotel_checkins',
		'infr_objects'
	));

}catch(Exception $e){
	echo "\n\n=======================================\n".$e->getMessage()."\n========================================\n\n";
}