<?php

namespace Whoops\Init;

use Exception as BaseException;



use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\Handler;
use Whoops\Run;
use Whoops\Exception\Formatter;



class Exception extends BaseException
{
}

/**
* 	blEngine JSON exception
*/
class BLEngineJSONException extends JsonResponseHandler
{
	private $returnFrames = false;
	public function handle()
	{
		 $response = array(
            'error' => Formatter::formatExceptionAsDataArray(
                $this->getInspector(),
                $this->addTraceToOutput()
            ),
            'data' => null,
            'meta' => array('code' => 503),
        );
        if (\Whoops\Util\Misc::canSendHeaders()) {
            header('Content-Type: application/json');
            header("HTTP/1.1 200 OK");
        }
        echo json_encode($response, defined('JSON_PARTIAL_OUTPUT_ON_ERROR') ? JSON_PARTIAL_OUTPUT_ON_ERROR : 0);
        exit;
	}
}

$run     = new Run();


if(defined('IS_JSON_MODE') && IS_JSON_MODE)
{
	$handler = new BLEngineJSONException();
	
}else
{
	$handler = new PrettyPageHandler();
	$handler->setEditor("sublime");

}



$run->pushHandler($handler);
$run->register();
