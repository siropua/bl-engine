<?php

/**

	Bootstrap

**/

require_once 'classes/rExternalFW.class.php';

class rMyFWS_Gallery extends rExternalFW{
	public function init(rWebApp $app)
	{

		$app->addFWJS('multi_uploader/js/vendor/jquery.ui.widget.js');
		$app->addFWJS('multi_uploader/js/load-image.all.min.js');
		$app->addFWJS('multi_uploader/js/canvas-to-blob.min.js');
		//$app->addFWJS('multi_uploader/js/jquery.fileupload-image.js');
		$app->addFWJS('multi_uploader/js/jquery.fileupload.js');
		$app->addFWJS('multi_uploader/js/jquery.fileupload-process.js');
		$app->addFWJS('multi_uploader/js/jquery.iframe-transport.js');
		$app->addFWJS('jquery.sortable.js');
		$app->addFWJS('jquery.ui.touch-punch.js');
		$app->addFWJS('jquery.rGallery.js');

		return true;
	}
}