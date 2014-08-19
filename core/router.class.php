<?php

/**
	Чтобы Роутер подхватился движком необходимо создать файл 
	settings/router.php

	В нём пронаследовать этот роутер:

	class rMyRouter extends ble\Router{
		function loadRules(){
	
		}
	}

	Далее, в методе loadRules() Роутер может изменять правила роутинга движка.
	А также вводить правила для каких-то путей сайта.
	Например закрывать для юзеров (или группы юзеров какой-то раздел):
		$this->addRule('^/superuser/', array(
			self::restrict_method => 'superuser'
		));
	Будет означать, что при обращении к site.com/superuser/ и любой страницы глубже 
	будет вызываться предварительно $router->superuser(), который должен вернуть:
		true - тогда работа приложения продолжится как обычно
		false - будет вызвано исключение ble\ForbiddenException
		либо можно кинуть внутри другое исключение

	Или редирект:
		$this->addRule('^/superpage/', array(
			self::use_controller => 'controller/superpage'
		));
	Тогда для страницы site.com/superpage/ будет использован контроллен из папки
		SITE_PATH/controller/superpage.php и заюзан класс controller\superpage 
		(но он должен быть наследован всё равно от rMyModule и иметь метод Run())


	Часть пути языка /ru/ /en/ итд не учитывается.
	Работает и для AJAX-запросов.



	
**/

namespace ble;

/**
* Абстрактный класс роутера
*/
abstract class Router
{
	
	const restrict_method = '{restrict_method}';
	const use_controller = '{use_controller}';
	const rules_item = '{rules}';

	protected $rules = array();

	public function __construct(\rApplication $app)
	{
		$this->app = $app;
		$this->loadRules();
	}

	abstract protected function loadRules();


	public function addRule($urlTemplate, $rules)
	{
		$urlTemplate = strtolower(trim($urlTemplate, '/ *'));
		$urlTemplate .= '/*';

		$urlParts = array_reverse(explode('/', $urlTemplate));
		
		
		$urlPath = array(self::rules_item => $rules);
		foreach ($urlParts as $p) 
		{
			$urlPath = array($p => $urlPath);
		}

		$this->rules = array_merge_recursive($this->rules, $urlPath);

		return $this;
	}

	public function getRules()
	{
		return $this->rules;
	}

	public function removeRule($urlTemplate)
	{
		if(isset($this->rules[$urlTemplate])){
			unset($this->rules[$urlTemplate]);
		}
		return $this;
	}

	public function checkRules()
	{
		$rule = $this->getPathRule();

		if($rule){
			return $this->execRule($rule);
		}

		return true;

	}


	/**
	* Проверяет, подходит ли текущий URL приложения под шаблон
	* @param string $url preg-шаблон для УРЛа
	* @return bool Подходит правило, или нет
	**/
	public function getPathRule()
	{
		$paths = $this->app->url->pathCount();
		$curRulePath = $this->rules;

		if(!$paths && isset($curRulePath[''])) return $curRulePath['']['*'][self::rules_item];
		for($i = 1; $i <= $paths; $i++)
		{
			$r = $this->app->url->path($i);
			if(isset($curRulePath[$r])){
				$curRulePath = $curRulePath[$r];
			}elseif (isset($curRulePath['*'])) {
				$curRulePath = $curRulePath['*'];
			}else{
				break;
			}
		}

		if(isset($curRulePath[self::rules_item])){
			$curRulePath = $curRulePath[self::rules_item];
		}elseif(isset($curRulePath['*'][self::rules_item])){
			$curRulePath = $curRulePath['*'][self::rules_item];
		}else{
			$curRulePath = false;
		}

		return $curRulePath;
	}

	/**
	* Выполняет правило и возвращает результат
	*
	**/
	public function execRule($rule)
	{
		if(isset($rule[self::restrict_method])){
			$method = $rule[self::restrict_method];
			$result = $this->$method();
			if(!$result) return false;
		}

		if(isset($rule[self::use_controller])){
			return new $rule[self::use_controller];
		}

		return true;
	}
}