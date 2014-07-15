<?php

/**Ищем в полученной строке переменню из списка**/
function inStr($str, $edit){
	
	foreach($edit as $k => $v){
		
//		if(strrpos($str, "'".$k."'")) return array($k, $v);
//		if(strrpos($str, "\"".$k."\"")) return array($k, $v);
		
		if(preg_match("/^[\s\t]*define\(.{1,4}($k)[\"\']{1,2}/is", $str)) return array($k, $v);
		
	}
	return false;
}

$const_list = CONFIGS_PATH."/constants_list.inc";
include_once($const_list);

$confList = glob(CONFIGS_PATH."/*.php");

if(isset($_POST['cancel'])){
	$site->redirect(MODULE_URL);
}

if(isset($_GET['edit'])){
	$res_conf = array();
	$tmp_conf = array();
	$getConf = $_GET['edit'];
	$confFile = ROOT."/config/".$getConf;
	if(isset($_POST['edit'])){
		
		//print_r($_POST);
		$edit = $_POST['edit'];
		$tmp_file = file($confFile, FILE_IGNORE_NEW_LINES);
		
		/**Читаем массив файла**/
		foreach($tmp_file as $k=>$v){
			/**Проверяем наличие константы из списка в строке**/
			if($val = inStr($v, $edit)){
				
				if(!isset($constants[$val[0]])) continue; //Если вдруг, нет такой константы то пропустим
				
				if($constants[$val[0]]['type'] == 'bool'){ 
					
					if(isset($edit[$val[0]."_bool"])){
						$tmp_file[$k] = "define('".$val[0]."', true);";
					}else{
						$tmp_file[$k] = "define('".$val[0]."', false);";
					}

				}elseif($constants[$val[0]]['type'] == 'string'){
					$val[1] = str_replace("'", "\'", $val[1]);
					$tmp_file[$k] = "define('".$val[0]."', '".$val[1]."');";
				}elseif($constants[$val[0]]['type'] == 'value'){
					$tmp_file[$k] = "define('".$val[0]."', ".$val[1].");";
				}
				
				
			}

			
		}
		$tmp_file = implode("\r\n", $tmp_file);
		
			$confOpen = fopen($confFile, 'w+');
			fwrite($confOpen, $tmp_file);
			fclose($confOpen);
		$site->redirect(MODULE_URL);
	}
	
	$config = file_get_contents($confFile);
	
	/**Выдераем все константы из файла**/
	preg_match_all("/[\n\r]{1,3}[\s\t]*define\((.{5,})\);/i",$config, $allConfigs);
	
	/**Получаем полный массив с константами конфига***/
	foreach($allConfigs[1] as $kk => $vv){
		
		$vv = explode(",", $vv, 2);
		$vv[0] = str_replace(array("'", " ","\""),"",$vv[0]);
		$tmp_conf[] = $vv;
	}

	foreach($tmp_conf as $k =>$v){
		if(isset($constants[$v[0]])){
			if($constants[$v[0]]['type'] == "bool") $v[1] = str_replace(array("'", " ","\""),"",$v[1]);
			if($constants[$v[0]]['type'] == "string"){
				$v[1] = str_replace(array(" "),"",$v[1]);
				$v[1] = trim($v[1]);
				$v[1] = substr($v[1], 1, -1);
				$v[1] = str_replace(array("\'"),"'",$v[1]);
						
			} 
			$res_conf[] = array('constanta'=> $v[0], 'name' => $constants[$v[0]]['name'], 'type'=> $constants[$v[0]]['type'], 'value' => $v[1]);
		}else{
			$dop_const[] = $v;
		}
		
		
	}
//print_r($constants);
//print_r($tmp_conf);
//print_r($res_conf);
	if(isset($dop_const)) $site->assign("dop_const", $dop_const);
 	$site->assign('config', $res_conf);
	$site->render('config_edit.tpl');
}


$site->assign('list', $confList);