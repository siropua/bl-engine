<?php

class rMyTPLModule extends rMyModule{
    public function Run(){
	if(file_exists(TEMPLATES_PATH.'/static-tpl-file.tpl')){
	    $this->assign('tpl_file', $this->app->getTemplate());
	    $this->setTemplate('static-tpl-file.tpl');
	}
	$this->app->render();
    }
}