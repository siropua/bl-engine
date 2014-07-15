<?php



abstract class rModule{

	protected $app = null;
	protected $user = null;

	public function __construct(rApplication $app)
	{
		$this->app = $app;
		$this->user = $app->user;
	}

	public function assign($key, $value)
	{
		$this->app->assign($key, $value);
	}
	
	public function setTemplate($tpl)
	{
	    $this->app->setTemplate($tpl);
	}

	public function testPath($name, $path = 1)
	{
		return $this->app->testPath($name, $path);
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


}