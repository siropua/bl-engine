<?php

class module_engine_404 extends rMyModule{
	public function Run()
	{
		$this->app->notFound();
		return false;
	}
}