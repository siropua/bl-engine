<?php

switch($site->getTab())
{
	case "makenew":
		require_once('make_new.php');
	break;
	default:
		require_once('recompile.php');
}


	

	
	

?>