<?php

$queryString = $_GET['queryString'];
        
$result = array();

$confList = glob(CONFIGS_PATH."/*.php");

foreach($confList as $k => $v){
	
	$tmp_file = file_get_contents($v);
	
	preg_match_all("/define\((.{0,3}".$queryString.".*?),/is", $tmp_file, $tmp_res);
	$result = array_merge($result, $tmp_res[1]);
//	foreach($tmp_res[1] as $kk=> $vv);
}

function cleanStr($str){
	return $str = str_replace(array(" ", "'", "\""),"",$str);
}
$result = array_map("cleanStr", $result);

if(!empty($result)){
	echo json_encode($result);

}


