<?php

echo "\nConfiguring...\n";
error_reporting(E_ALL);
ini_set("display_errors", "on");
require_once __DIR__.'/../../configs/main.php';
require_once ENGINE_PATH.'/init.php';

echo "\n\n\n";



/**
* Model Rebuilder
*/
class modelRebuilder
{

	protected $systemTables = array();

	protected $path;

	protected $app;

	function __construct($path)
	{
		if(!is_dir($path)){
			if(!mkdir($path, 0777, true)) throw new Exception('Cant create models dir ('.$path.')');
		}
		if(!is_writable($path)) throw new Exception('Models dir is not writable!');
		
		$this->path = realpath($path);
		if(!$this->path) throw new Exception('realpath for ('.$path.') returns false!', 1);

		$this->app = rMyCLIApp::getInstance();
		
	}

	public function rebuildWithout($wo, $woSystem = true)
	{
		if(!$wo) $wo = array();

		if($woSystem) 
			$woTables = array_merge($this->systemTables, $wo);

		$allTables = $this->app->db->selectCol('SHOW TABLES');

		$onlyTables = array_diff($allTables, $woTables);

		$this->rebuild($onlyTables);
	}

	public function rebuild($tables)
	{
		foreach ($tables as $t) {
//			$a = array();
//			if (preg_math('/(.*)_str$/', $t, $a) && in_array($a[1], $tables)){
//				continue;
//			}
			$this->rebuldTable($t);
		}
	}

	public function rebuldTable($table)
	{
		$this->log('Rebuilding '.$table);
		$tableInfo = $this->getTableInfo($table);
		if(!$tableInfo) {
			$this->log('Cant get table info for '.$table.'!');
			return false;
		}

		$code = $this->getClassCode($tableInfo);
		if(!$code) {
			$this->log('Cant get code for '.$tableInfo['table_name'].'!');
			return false;
		}

		$code = "<?php\n\nuse ble\baseTableModel;\n\n".$code;

		$filename = $this->translateSQLNameToPHP($tableInfo['table_name']);
		file_put_contents($this->path.'/'.$filename.'.class.php', $code);
		$workingModelFile = $this->path.'/../'.$filename.'.class.php';
		if(!file_exists($workingModelFile)){
			file_put_contents($workingModelFile, "<?php\n\n".$this->getWorkingClassCode($tableInfo));
		}
		$workingModelListFile = $this->path.'/../'.$filename.'List.class.php';
		if(!file_exists($workingModelListFile)){
			file_put_contents($workingModelListFile, "<?php\n\n".$this->getWorkingListClassCode($tableInfo));
		}
	}

	public function getTableInfo($table)
	{
		$fields = @$this->app->db->select('EXPLAIN ?#', $table);
		if(!$fields){
			$this->log('Table "'.$table.'" not exists!');
			return false;
		}

		$pKey = '';
		$tableFields = array();

		foreach ($fields as $f) {
			$fieldData = $this->getFieldInfo($f);
			if($fieldData['is_primary'] && empty($pKey)) $pKey = $fieldData['name'];
			$tableFields[$fieldData['name']] = $fieldData;
		}

		return array(
			'fields' => $tableFields,
			'pKey' => $pKey,
			'table_name' => $table
		);
	}

	public function getFieldInfo($field)
	{
		$info = array(
			'name' => $field['Field'],
			'type' => $this->getFieldType($field['Type']),
			'is_null' => $field['Null'] == 'YES',
			'is_primary' => $field['Key'] == 'PRI',
			'is_index' => $field['Key'] != '',
			'is_unsigned' => strpos($field['Type'], 'unsigned') !== false,
			'default' => $field['Default'],
		);

		return $info;
	}

	public function getFieldType($sqlType)
	{
		if(preg_match('~^([a-z]+)~', $sqlType, $m)){
			return $m[1];
		}else return 'unknown';
	}


	public function getClassCode($tableInfo)
	{
		$code = 'class basemodel_'.$this->translateSQLNameToPHP($tableInfo['table_name']).' extends baseTableModel';
		$code .= "\n{\n";
		$code .= "\tstatic protected \$pKey = '{$tableInfo['pKey']}';\n";
		$code .= "\tstatic protected \$tableName = '{$tableInfo['table_name']}';\n";
		$code .= "\n";
		$code .= "\tstatic protected \$fields = ".var_export($tableInfo['fields'], 1).";\n";
		$code .= "\n}";

		return $code;
	}

	public function getWorkingClassCode($tableInfo)
	{
		$code = 'class model_'.$this->translateSQLNameToPHP($tableInfo['table_name']).' extends basemodel_'.$this->translateSQLNameToPHP($tableInfo['table_name']);
		$code .= "\n{\n}\n";

		return $code;
	}

	public function getWorkingListClassCode($tableInfo)
	{
		$code = "class model_".$this->translateSQLNameToPHP($tableInfo['table_name'])."List extends ble\\baseListmodel\n{\n";
		$code .= "\tpublic function __construct(){\n\t\tparent::__construct('model_".$this->translateSQLNameToPHP($tableInfo['table_name'])."');\n\t}\n";
		$code .= "}\n";
		return $code;
	}

	static public function translateSQLNameToPHP($SQLName)
	{
		$PHPName = preg_replace_callback('~_([a-z])~i', function($m){
			return strtoupper($m[1]);
		}, $SQLName);
		return $PHPName;
	}


	public function log($msg)
	{
		echo is_array($msg) || is_object($msg) ? print_r($msg, 1) : $msg;
		echo "\n";
	}
}

try{

	echo "Starting rebuild...\n";

	$rebuilder = new modelRebuilder(SITE_PATH.'/models/base');


	$rebuilder->rebuildWithout([]);



}catch(Exception $e){
	echo "\n\n============== * ERROR * =========================\n".$e->getMessage()."\n========================================\n\n";
}