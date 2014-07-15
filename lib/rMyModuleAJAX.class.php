<?php

require_once 'classes/rModule.class.php';


abstract class rMyModuleAJAX extends rModule{
	public function Run()
	{
		return $this->RunAJAX();
	}

	abstract public function RunAJAX();
}