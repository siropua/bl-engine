<?php

require_once 'classes/rAutoexec.class.php';
class rMyAdminAutoexec extends rAutoexec{

	public function beforeCreate($app, $factory){
		$app->addFWJS('jquery.last.js');
		$app->addFWJS('modernizr-1.6.min.js');
		$app->addFWJS('jquery.rulezdev.js');
		
		$app->initExternalFW('rMyFWS_Bootstrap');
	}	

}