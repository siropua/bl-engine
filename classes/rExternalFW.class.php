<?php


/**

	Хелпер для внешних фреймворков.
	По сути класс состоит всего из одного метода init($app) который тупо делает addJS и addCSS :)
**/


interface riExternalFW{
	public function init(rWebApp $app);
}

abstract class rExternalFW implements riExternalFW{

}