<?php
$const_list = CONFIGS_PATH."/constants_list.inc";
include_once($const_list);

function saveConstants($constants, $const_list){
	$tmp_list = '<?php ' .
	'$constants = ';
	$tmp_list = $tmp_list.var_export($constants, true).";";
	
	$cdb = fopen($const_list, 'w+');
	fwrite($cdb, $tmp_list);
	fclose($cdb);
	return true;
}

if(isset($_POST['add'])){
		$add = $_POST['add'];
		$constants[$add['constant']] = array('name' => $add['name'],
							  				 'type' => $add['type']);

		saveConstants($constants, $const_list);
 
}elseif(isset($_GET['edit'])){
	
	if(isset($_POST['edit'])){
		
		$edit = $_POST['edit'];
		$constants[$_GET['edit']] = array('name' => $edit['name'],
							  				 'type' => $edit['type']);
		
		
		 if(saveConstants($constants, $const_list)) 
		 	$site->redirect(MODULE_URL."directory/");
	}
	$site->assign('constanta', $constants[$_GET['edit']]);
	$site->render('edit.tpl');
	
}elseif(isset($_GET['del'])){
	$del = $_GET['del'];
	unset($constants[$del]);
	
	 if(saveConstants($constants, $const_list)) 
	 	$site->redirect(MODULE_URL."directory/");
	 
}


$site->addModuleJS("js/autocomplit.js");
$site->addModuleJS("js/jquery-ui-1.8.19.custom.min.js");
$site->addModuleCSS("css/ui-lightness/jquery-ui-1.8.19.custom.css");

$site->assign('const', $constants);
$site->render("directory.tpl");