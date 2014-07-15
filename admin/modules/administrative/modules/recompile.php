<?php

if(@$_POST['sure'])
{
	unlink(COMPILED_PATH.'/admin/menu.php');
	$site->redirect(SELF_URL);
	
}
