<?php

namespace ble;

require_once 'core/rURL.class.php';

/**
* Обработка УРЛа
*/
abstract class rURLHandler extends \rMyModule
{
	protected
		$url = NULL;

	public function __construct(rURL $url)
	{
		parent::__construct(\rMyApp::getInstance());
		$this->url = $url;
	}
}