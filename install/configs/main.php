<?php

/**
* iartStore configuration file
*
* @version $Id: config.main.dist,v 1.1 2007/11/16 19:40:11 steel Exp $
* @copyright 2007
* @package iartStore
* @author steel_ice
*/

define('IS_DEVELOP', 2);
define('STATIC_VERSION', '2');

/**
* ошибки показываем только на девелоперском сервере
**/
//error_reporting(IS_DEVELOP ? E_ALL : 0);
error_reporting(E_ALL);
ini_set('display_errors', 'off');

/**
* **** Section with absolute paths constants
*/

/**
* * absosulte path to site root
*/
define('ROOT', realpath(dirname(__FILE__) . '/..'));

/*
* путь к сайту
**/
define('ROOT_URL', '/');




define('ENGINE_FOLDER', '{%%ENGINE_FOLDER%%}');
define('SITE_FOLDER', '{%%SITE_FOLDER%%}');
define('ADMIN_FOLDER', '{%%ADMIN_FOLDER%%}');


/**
* путь к скриптам сайта
*/
define('SITE_PATH', ROOT.'/'.SITE_FOLDER);

/** путь к движку **/
define('ENGINE_PATH', ROOT.'/'.ENGINE_FOLDER);

define('VAR_PATH', ENGINE_PATH.'/var');


/**
* * absolute path to directory, contained configuration file
*/
define('CONFIGS_PATH', realpath(dirname(__FILE__)));

/** библиотеки **/
define('LIB_PATH', ENGINE_PATH.'/lib');
define('CUSTOM_LIB_PATH', SITE_PATH.'/lib');

define('TMP_PATH', ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') :(
	is_writable(VAR_PATH.'/tmp') ? VAR_PATH.'/tmp' : '/tmp'
	));

define('ENGINE_MODULES_PATH', ENGINE_PATH.'/modules');
define('MODULES_PATH', SITE_PATH.'/modules');
// define('AJAX_PATH', SITE_PATH.'/sections-ajax');

/** для изменений **/
define('CUSTOM_SECTIONS', ROOT.'/sections');
define('CUSTOM_AJAX', ROOT.'/sections-ajax');

/**
* * absolute path to design templates
*/
define('TEMPLATES_PATH', SITE_PATH . '/design');
define('DESIGN_PATH', SITE_PATH . '/design');

/**
* * path to php files with compiled templates
*/
define('COMPILED_PATH', VAR_PATH . '/templates_c');

/**
* * path to pages cache
*/
define('CACHE_PATH', VAR_PATH . '/cache');

/**
* * path to folder, contained languages
*/
define('LANG_PATH', SITE_PATH . '/lang');

/** путь к статическому содержимому **/
define('STATIC_PATH', TEMPLATES_PATH);

/** урл к статическому содержимому **/
define('STATIC_URL', ROOT_URL.SITE_FOLDER.'/design/');


define('USERS_URL', ROOT_URL.'users-data/');

/**
* * absolute path to directory contained all user files
*     i.e. userpics and other
*/
define('USERS_PATH',  ROOT.'/users-data');



/**
* Prefix for auth cookies. 
*/
define('COOKIE_PREFIX', 'r_');


/**
* section with web paths
*/

/**
* * web path to templates
*/
define('TEMPLATES_URL', ROOT_URL .SITE_FOLDER. '/design/');
define('DESIGN', TEMPLATES_URL);

/**
* * web path to images
*/
define('IMAGES_URL', TEMPLATES_URL . 'img/');

/**
*/

/**
* * default language
*/
define('DEF_LANG', 'ru');


define('AUTOLANG_FILE', 'main.txt');
define('SIMPLE_APPLICATION', {%%SIMPLE_APPLICATION%%});


if(!SIMPLE_APPLICATION)
	require_once(CONFIGS_PATH.'/db.php');



{%%IMAGICK_EXISTS%%} define('IMAGEMAGICK_PATH', '{%%IMAGEMAGICK_PATH%%}');

define('_SAPE_USER', '');


define('BLOG_TEMPLATE_POSTCELL', 'blog/my_postCell.tpl');

define('USER_REGISTER_TYPE', '{%%USER_REGISTER_TYPE%%}');

define('FAVICON_FILE_TYPE', '{%%FAVICON_FILE_TYPE%%}');

define('STATIC_TPL_FOLDER', '{%%STATIC_TPL_FOLDER%%}');
define('SIMPLE_BLOG_MODE', '{%%SIMPLE_BLOG_MODE%%}');


set_include_path(
		'.'.PATH_SEPARATOR.
		ROOT.PATH_SEPARATOR.
		SITE_PATH.PATH_SEPARATOR.
		ENGINE_PATH.PATH_SEPARATOR.
		'{%%RLIB_PATH%%}'.PATH_SEPARATOR.
		get_include_path()
);


define('IS_MULTISITE', false);


/**
	site - external module in site directory
	engine - internal module in engine directory
	blog - try to search blog item
	page - try to open static page
	tpl - just render tpl-file
**/

$_EngineModulesOrder = array(
	'site', 'engine', 'tpl', 'blog', 'page'
);