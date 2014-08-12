<?php

class rTPL_CLI{
	
	protected $vars;

	function __construct(){
		
		
		
	}
	
	function display($template,$cacheid = NULL, $compile_id = NULL, $parent = NULL){
		echo "\n=================\n";
		foreach ($this->vars as $key => $value) {
			echo $key.' = "'.print_r($value, 1).'"';
			echo "\n";
		}
		echo "\n=================\n";
	}

	function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false){
		$msg = '';
		foreach ($this->vars as $key => $value) {
			$msg .= $key.' = "'.print_r($value, 1).'"';
			$msg .= "\n";
		}
		return $msg;
	}


	public function assign($key, $value)
	{
		$this->vars[$key] = $value;
	}
}
