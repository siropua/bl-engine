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
class rNotFound extends Exception{
	
}

/** доступ запрещён **/
class rAccessDenied extends rNotFound{
	
}