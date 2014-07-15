<?php

if(!defined('RMYCACHER_TIMEOUT')) 
	define('RMYCACHER_TIMEOUT', 60*60*24); // 1 day by default


class rMyCacher{



	static public function get($key, $timeout = RMYCACHER_TIMEOUT)
	{
		$file = self::getFileName($key);
		return self::getFileData($file, $timeout);
	}

	static protected function getFileData($file, $timeout = RMYCACHER_TIMEOUT)
	{
		if(!file_exists($file)) return NULL;

		if($timeout)
			if(time() - filemtime($file) > $timeout) return false;

		return @unserialize(file_get_contents($file));
	}

	static public function set($key, $data)
	{
		$file = self::getFileName($key);
		return self::setFileData($file, $data);
	}

	static protected function setFileData($file, $data)
	{
		if(!is_dir(dirname($file))){
			if(!@mkdir(dirname($file), 0777, true)) return false;
		}

		file_put_contents($file, serialize($data));

		return true;
	}

	static public function getFileName($key)
	{
		$hash = md5($key).strlen($key);
		return VAR_PATH.'/cache/rMyCacher/'.$hash[0].'/'.$hash[1].'/'.$hash[2].'/'.$hash[3].'/'.$hash.'.txt';
	}


}