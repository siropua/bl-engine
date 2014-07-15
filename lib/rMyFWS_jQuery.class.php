<?php

/**

	Bootstrap

**/

require_once 'classes/rExternalFW.class.php';

class rMyFWS_jQuery extends rExternalFW{
	public function init(rWebApp $app)
	{
		$app->addFWJS('jquery.last.js');
		$app->addFWJS('jquery.rulezdev.js');
		return true;
	}
}