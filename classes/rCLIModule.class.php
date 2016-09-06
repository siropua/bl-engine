<?php



abstract class rCLIModule{

	protected $app = null;

	public function __construct(rApplication $app)
	{
		$this->app = $app;
	}



	/**
	* Возвращает, каким методом мы будем стартовать основной код в объекте
	**/
	public function getMyRunMethod()
	{
		return 'Run';
	}

	abstract public function Run();

	public function Init()
	{
		return;
	}
	
	public function log($txt, $file = 'all.log')
	{
		if(!is_string($txt)) $txt = print_r($txt, true);
	    $str = $this->app->log($txt, $file);
	    echo $str."\n";
	}


}