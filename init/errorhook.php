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


$exceptionSilentMode = (IS_DEVELOP == 2) && file_exists(DESIGN_PATH.'/errors/fatal.tpl');

if($exceptionSilentMode){
    /**
    * Empty error
    */
    class BLEngineProdException extends Handler
    {
        private $returnFrames = false;
        public function handle()
        {
            
            $exception = $this->getException();

            $response = sprintf("%s: %s in file %s on line %d\n",
                    get_class($exception),
                    $exception->getMessage(),
                    $exception->getFile(),
                    $exception->getLine()
                );

            ini_set('display_error', 'no');

            file_put_contents(VAR_PATH.'/logs/fatal.log', $response, FILE_APPEND);

            $display = file_get_contents(DESIGN_PATH.'/errors/fatal.tpl');

            echo ($display);

            return Handler::QUIT;
        }
        
    }

    $handler = new BLEngineProdException;

}else
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
