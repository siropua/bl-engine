<?php

/**
 * Making new modules vizard
 *
 * @version $Id: make_new.php,v 1.1 2007/11/26 17:45:57 steel Exp $
 * @copyright 2007
 **/


require_once('rlib/rac/RACStruct.php');
$struct = new RACStruct;


if(@$_POST['new_section'])
{
	$ns = array(
		'name'=>$_POST['new_section'],
		'url'=>$site->rURL->URLize($_POST['new_section_url'], FALSE, TRUE)
	);
	if($_FILES['new_section_icon'])
		$ns['icon'] = $_FILES['new_section_icon']['tmp_name'];

	if($struct->createSection($ns))
	{
		$site->assign("msg", $_LANG->section_created);
	}else{
		$site->assign("error", $_LANG->cant_create_section);
	}

}elseif(@$_POST['new_module'])
{
	$ns = array(
		'name'=>$_POST['new_module'],
		'url'=>$site->rURL->URLize($_POST['new_module_url'], false, true),
		'section'=>$_POST['new_module_section'],
		'create_files'=>@(int)$_POST['create_files']
	);
	if($_FILES['new_module_icon'])
		$ns['icon'] = $_FILES['new_module_icon']['tmp_name'];
	if($struct->createModule($ns))
	{
		$site->assign("msg", $_LANG->module_created);
	}else
	{
		$site->assign("error", $_LANG->cant_create_module);
	}
}

$module_sections = array();
foreach(glob('modules/*', GLOB_ONLYDIR) as $s)
{
	if($s!='modules/CVS')
		$module_sections[] = basename($s);
}

$site->assign("module_sections", $struct->getSections());
$site->assign("template", 'make_new.tpl');
