<?php

require_once __DIR__.'/../../configs/main.php';
require_once ENGINE_PATH.'/init.php';

echo "\n\n\n";

/**
* Model Rebuilder
*/
class modelRebuilder
{

	protected $systemTables = array("amnesia","blog_comments","blog_comments_t","blog_favorites","blog_images","blog_posts","blog_posts_ext","blog_posts_visits_map","blog_posts_votes","blog_sources","blog_tags","blog_tags_map","blog_visits","blog_visits_map","blogs","cities","countries","feedbacks","menu_links","pages","pages_comments","pages_comments_t","pages_items","ref_landings","ref_sources","ref_visits","site_settings","social_networks","stat_agents","static_pages","tags","users","users_email_changes","users_external","users_info","users_stats");

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

		$code = "<?php\n\n".$code;

		file_put_contents($this->path.'/'.'basemodel_'.$this->translateSQLNameToPHP($tableInfo['table_name']).'.class.php', $code);
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
			if($fieldData['is_primary']) $pKey = $fieldData['name'];
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
		$code = 'class basemodel_'.$this->translateSQLNameToPHP($tableInfo['table_name']).' extends baseModel';
		$code .= "\n{\n";
		$code .= "\tprotected \$pKey = '{$tableInfo['pKey']}';\n";
		$code .= "\tprotected \$tableName = '{$tableInfo['table_name']}';\n";
		$code .= "\n";
		$code .= "\tprotected \$fields = ".var_export($tableInfo['fields'], 1).";\n";
		$code .= "\n}";


		return $code;
	}

	public function translateSQLNameToPHP($SQLName)
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


	$rebuilder->rebuildWithout(array(
		'beers'
	));



}catch(Exception $e){
	echo "\n\n============== * ERROR * =========================\n".$e->getMessage()."\n========================================\n\n";
}