<?php



abstract class rAPIModule{

	protected $app = null;

	public function __construct()
	{
		$this->app = rMyApp::getInstance();
	}

	/**
	* Возвращает, каким методом мы будем стартовать основной код в объекте
	**/
	public function getMyRunMethod()
	{
		$method = $this->app->path(5) or $this->app->path(4);
		$method = preg_replace('~[^a-z0-9_]~i', '_', $method);
		if(method_exists($this, 'Run_'.$method)) return 'Run_'.$method;

		return 'Run';
	}

	abstract public function Run();

	public function Init()
	{
		return;
	}


}