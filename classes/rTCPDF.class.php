<?php

if (!defined('K_TCPDF_EXTERNAL_CONFIG')){
	define('K_TCPDF_EXTERNAL_CONFIG', true);
}
require_once(CONFIGS_PATH.'/tcpdf.php');
require_once('TCPDF/tcpdf.php');

class rTCPDF extends TCPDF
{
}
