<?php

/**


	Файл содержит описание базовых исключений для сайта.
	Перемещен из rLib т.к. нужен только исключительно для сайтов. 
	Сталбыть пусть будет в папке с движком сайта.

**/


/** Ошибка в форме **/
class rInvalidFormData extends Exception{
	
}

/** 404-я ошибка **/
class rNotFound extends Exception{}
class rNotFoundException extends Exception{}


/** доступ запрещён **/
class rAccessDenied extends rNotFoundException{
	
}

class rUnauthorized extends Exception{}

/**
* Wrong Data
*/
class DataException extends Exception
{
	protected $globalMessage;
	protected $fieldInfo;
	function __construct($globalMessage, $code = 0, $prev = null, $fieldInfo = false)
	{
		parent::__construct($globalMessage, $code, $prev);
		$this->fieldInfo = $fieldInfo ? $fieldInfo : array();
	}

	public function getFieldInfo()
	{
		return $this->fieldInfo;
	}
}