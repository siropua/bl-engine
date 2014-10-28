<?php

class module_phpinfo extends rMyAdminModule{

	public function Run()
	{
		ob_start();
		phpinfo();
		preg_match('/<body>(.*?)<\/body>/s', ob_get_clean(), $phpinfo);
		$this->app->assign('phpinfo', $phpinfo[1]);
	}

}