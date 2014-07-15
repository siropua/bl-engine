<?php

/**

	Bootstrap

**/

require_once 'classes/rExternalFW.class.php';

class rMyFWS_Uploader extends rExternalFW{
	public function init(rWebApp $app)
	{

		$app->addfWCSS('multi_uploader/css/jquery.fileupload-ui.css');		

		$app->addfWJs('multi_uploader/js/tmpl.min.js');
		$app->addfWJs('multi_uploader/js/load-image.min.js');
		$app->addfWJs('multi_uploader/js/canvas-to-blob.min.js');
		
		$app->addfWJs('multi_uploader/js/vendor/jquery.ui.widget.js');
		$app->addfWJs('multi_uploader/js/jquery.iframe-transport.js');
		$app->addfWJs('multi_uploader/js/jquery.fileupload.js');
		$app->addfWJs('multi_uploader/js/jquery.fileupload-process.js');
		$app->addfWJs('multi_uploader/js/jquery.fileupload-image.js');
		$app->addfWJs('multi_uploader/js/jquery.fileupload-ui.js');
		return true;
	}
}