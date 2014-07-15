<?php

require_once dirname(__FILE__).'/main.php';


if(@$_SERVER["HTTPS"] == "on") define("URL_SCHEME", "https");
else define("URL_SCHEME", "http");


define("SERVER_URL", URL_SCHEME."://".$_SERVER['SERVER_NAME'].ROOT_URL);

define("SELF_URL", $_SERVER['REQUEST_URI']);
define('SELF_URL_FULL', URL_SCHEME."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
