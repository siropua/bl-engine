<?php

/**

	Bootstrap 3.0 with Font Awesome

**/

require_once 'classes/rExternalFW.class.php';

class rMyFWS_Bootstrap extends rExternalFW{
	public function init(rWebApp $app)
	{
		$app->addFWCSS('bootstrap/css/bootstrap.min.css');
//		$app->addFWCSS('bootstrap/css/bootstrap-theme.min.css');
		$app->addFWCSS('font-awesome/css/font-awesome.min.css');
		$app->addFWJS('bootstrap/js/bootstrap.min.js');

		return true;
	}
}