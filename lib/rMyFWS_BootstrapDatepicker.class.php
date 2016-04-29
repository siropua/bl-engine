<?php

/**

	Bootstrap Datepicker

**/

require_once 'classes/rExternalFW.class.php';

class rMyFWS_BootstrapDatepicker extends rExternalFW{
	public function init(rWebApp $app)
	{
		$app->addFWCSS('bootstrap-datepicker/css/datepicker3.css');
		$app->addFWJS('bootstrap-datepicker/js/bootstrap-datepicker.js');

		return true;
	}
}