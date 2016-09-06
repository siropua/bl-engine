#!/usr/bin/php
<?php

set_time_limit(0);

$_SERVER['HTTP_HOST'] = $_SERVER['REMOTE_ADDR'] = 'cli';

require_once(dirname(__FILE__).'/../configs/main.php');
include_once ENGINE_PATH.'/init.php';

$m = getopt('f:m:');
if(empty($m['f'])) exit("\nNo file specified\n");

$file = $m['f'].'.php';

ini_set('display_errors', 'on');
error_reporting(E_ALL);

try{
	if(file_exists(SITE_PATH."/cron/$file")){
	    include_once SITE_PATH."/cron/$file";
	}elseif(file_exists(ENGINE_PATH."/cron/$file")){
	    include_once ENGINE_PATH."/cron/$file";
	}else{
	    exit("\nFile '$file' not found!\n");
	}
}catch(Exception $e){
	exit("Error: ".$e->getMessage());
}



$className = 'cron_'.preg_replace('~[^a-z0-9]~i', '_', $m['f']);

if(!class_exists($className)){
    exit("\nClass '$className' not found at '$file'\n");
}


$method = 'Run';
if(!empty($m['m'])) $method = 'Run_'.$m['m'];

if(!method_exists($className, $method)){
    exit("\nMethod '$method' not found in '$className' at '$file'\n");
}

try{
	$class = new $className(rMyCLIApp::getInstance());
	$class->$method();
}catch(Exception $e){
	exit("Error: ".$e->getMessage()."\n\n");
}

echo "\n";
