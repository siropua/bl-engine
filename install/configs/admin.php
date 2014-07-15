<?php

define("ADMIN_DIR", "admin");
define("ADMIN_URL", ROOT_URL.ADMIN_DIR.'/'); 
define("ADMIN_PATH", ROOT."/".ADMIN_DIR); 

define("ADMIN_MODULES_PATH", ADMIN_PATH."/modules");        // modules' absolute path
define('ADMIN_MODULES_URL', ADMIN_URL.'module/');


define('RAC_LESS_SECURITY', 1);

$_LANGS = array(
	'' => 'рус',
	'_en' => 'анг',
	'_ua' => 'укр',
	'_fr' => 'фр',
	'_de' => 'нем',
	'_es' => 'исп',
	'_it' => 'ит',
);