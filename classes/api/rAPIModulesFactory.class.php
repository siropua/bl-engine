<?php


/**
* API module factory
*/
class rAPIModulesFactory
{
	protected $app;

	public function __construct()
	{
		$this->app = rMyApp::getInstance();
	}

	/**
	 * Возвращает модуль API
	 * @return rMyAPIModule|bool модуль API или false, если он не найден
	 */
	public function getModule()
	{
		
	}

	/**
	 * Возвращает имя файла модуля, который можно использовать в качестве исполняемого модуля
	 * @return string|bool Строка с полным путём к файлу или false, если такой файл не найден
	 */
	public function getModuleFile()
	{
		if($file = $this->getModuleFileAtDir(SITE_PATH)) return $file;
		return $this->getModuleFileAtDir(ENGINE_PATH);
	}

	protected function getModuleFileAtDir($dir)
	{
		if(!$dir = realpath($dir)) return false;
		if(!$this->app->url->path(3)) throw new Exception('API method not specified');
		
			
		$dir .= '/'.$this->getAPIVersion();
		if(!is_dir($dir)) return false;

		if(is_dir($dir.'/'.$this->app->url->path(3)))
		{
			// /version/dir/file or /version/dir/file/method/
			$filename = $this->app->url->path(4) or 'index';
			if(file_exists($dir.'/'.$this->app->url->path(3).'/'.$filename.'.php')) 
				return $dir.'/'.$this->app->url->path(3).'/'.$filename.'.php';
		}else
		{
			// /version/file/ or /version/file/method/
			if(file_exists($dir.'/'.$this->app->url->path(3).'.php'))
				return $dir.'/'.$this->app->url->path(3).'.php';
		}


		if($this->app->url->path(5))
		{

		}
	}



	/**
	 * Возвращает корректную версию запрошенного API, очищая все небезопасные символы.
	 * Проверка на формат строки не производится, так что может вернуть и v1, и v1.2 и version-dva
	 * @return string Версия API
	 */
	public function getAPIVersion()
	{
		if(!$v = preg_replace('~[^a-z0-9._-]~i', '', $this->app->url->path(2)))
		{
			throw new Exception('API version not specified!');
		}

		return $v;

	}
}