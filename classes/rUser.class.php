<?php

/**
 *	Users Class
 *
 * @version $Id: rUser.class.php,v 1.12 2008/05/21 09:44:33 steel Exp $
 * @copyright 2007
 */



if(!defined('REG_ERR_ALLOK')) define('REG_ERR_ALLOK', 1);
if(!defined('REG_ERR_NOLOGIN')) define('REG_ERR_NOLOGIN', -1);
if(!defined('REG_ERR_NOPASS')) define('REG_ERR_NOPASS', -2);
if(!defined('REG_ERR_EMAIL_EXISTS')) define('REG_ERR_EMAIL_EXISTS', -3);
if(!defined('REG_ERR_SQL_ERR')) define('REG_ERR_SQL_ERR', -4);
if(!defined('REG_ERR_LOGIN_EXISTS')) define('REG_ERR_LOGIN_EXISTS', -5);
if(!defined("DENY_ALL_RIGHT")) define("DENY_ALL_RIGHT", "deny_all");
if(!defined("ACCEPT_ALL_RIGHT")) define("ACCEPT_ALL_RIGHT", "allow_all");
if(!defined("PASSWORD_HASH_METHOD")) define('PASSWORD_HASH_METHOD', 'md5sha1');
if(!defined("USER_FORCE_SALT_HASH")) define('USER_FORCE_SALT_HASH', true);

if(!defined('EMAIL_FIELD')) define('EMAIL_FIELD', 'email');
if(!defined('LOGIN_FIELD')){
  if(defined('USER_REGISTER_TYPE') && (USER_REGISTER_TYPE == 'login')){
    define('LOGIN_FIELD', 'login');
  }else{
    define('LOGIN_FIELD', 'email');
  }
} 

if(!defined('EMAIL_PREG')) define('EMAIL_PREG', '/^[a-zа-я0-9_.\-]+@[a-z0-9а-я_.\-]+\.[a-zа-я0-9]{2,10}$/i');

if(!defined('LOGIN_PREG')){
  if(defined('USER_REGISTER_TYPE') && (USER_REGISTER_TYPE == 'login')){
    define('LOGIN_PREG', '/^[a-z0-9-]{2,25}$/i');
  }else{
    define('LOGIN_PREG', EMAIL_PREG);
  }
} 


if(!defined('COOKIE_PREFIX')) define('COOKIE_PREFIX', 'rsite_');

if(!defined('USERS_TABLE')) define('USERS_TABLE', 'users');
if(!defined('UID_FIELD')) define('UID_FIELD', 'id');
if(!defined('LOGIN_FIELD')) define('LOGIN_FIELD', 'login');
if(!defined('PASS_FIELD')) define('PASS_FIELD', 'password');
if(!defined('PASSWORD_HASH_METHOD')) define('PASSWORD_HASH_METHOD', 'md5');
if(!defined('LOGIN_PREG')) define('LOGIN_PREG', '/^[a-z][a-z0-9\-_]+$/i');
if(!defined('DELETED_FLAG')) define('DELETED_FLAG', 'deleted');
if(!defined('USER_BLOCKED_FLAG')) define('USER_BLOCKED_FLAG', 'is_blocked');

/**
* login results
*/


if(!defined('LOGIN_OK')) define('LOGIN_OK', 1);
if(!defined('LOGIN_ERR_LOGIN')) define('LOGIN_ERR_LOGIN', 0);
if(!defined('LOGIN_NO_USER')) define('LOGIN_NO_USER', -1);
if(!defined('LOGIN_PASS_ERR')) define('LOGIN_PASS_ERR', -2);
if(!defined('LOGIN_BLOCKED_USER')) define('LOGIN_BLOCKED_USER', -3);


if(!defined('USERS_PATH')) define('USERS_PATH', ROOT . '/users_data');
if(!defined('USERS_URL')) define('USERS_URL', ROOT_URL . 'users_data/');

@define("RIGHTS_DELIM", "|");
@define("DENY_ALL_RIGHT", "deny_all");
@define("ACCEPT_ALL_RIGHT", "allow_all");



/**
 *
 *
 **/
class rUser{

	protected $db = null;
	protected $_cookie_prefix = '';
	protected $_authed = false;
	protected $_auth_checked = false;
	protected $_ID = 0;
	protected $data = array();
	protected $_cookie_domain = '';
	protected $_cookie_path = '/';
	protected $_can = array();
	protected $_lastAuthError = '';
	protected $currentToken;

	protected $deviceID = '';

	protected $tablePrefix = '';

	protected $_selectString = 'SELECT u.* FROM users AS u ';
	
	protected $userpics = array(
		'original' => array('prefix' => '', 'w' => 500, 'h' => 500, 'assign_as_next' => 1),
		'' => array('prefix' => '100-', 'w' => 100, 'h' => 100, 'assign_as_next' => 1),
		'_50' => array('prefix' => '50-', 'w' => 1280, 'h' => 1024, 'assign_as_next' => true, 'method' => 'crop'),
		'_24' => array('prefix' => '24-', 'w' => 300, 'h' => 300, 'assign_as_next' => true, 'method' => 'crop'),
	
	);
	
	protected $userpicFolder = 'img';

	/**
	* Constructor
	* @return rUser
	*/
	public function __construct()
	{
		$this->db = ble\DB::getInstance();
		
		$this->_resetState();
		
		if(defined('SITE_DOMAIN')){
			$this->_cookie_domain = SITE_DOMAIN;
		}

		$this->_selectString = 'SELECT u.*, '.LOGIN_FIELD.' as login FROM '.$this->getTableName('users').' AS u ';

		$this->deviceID = md5('empty');
	}

	public function getTableName($table)
	{
		return '`'.$this->tablePrefix.$table.'`';
	}

	public function getCurToken()
	{
		if($this->currentToken) return $this->currentToken;
		
		@$hash = trim($_SESSION[$this->_cookie_prefix.'access_token']);

		if(!$hash)
		{
			@$hash = trim($_COOKIE[$this->_cookie_prefix.'access_token']);
		}

		if(!$hash && !empty($_GET['access_token'])) $hash = trim($_GET['access_token']);

		return $hash;
	}

	/**
	* Auth user
	* @return bool
	*/
	public function auth()
	{

		$hash = $this->getCurToken();

		if(!$hash || !$this->authByToken($hash))
		{
			$_authed = false;
			$this->_auth_checked = true;
			$this->_lastAuthError = 'No token ('.$hash.') found';
			return false;
		}

		
		  /** user deleted */
		if(!empty($this->data[DELETED_FLAG])){
				$this->_lastAuthError = 'User '.$this->_ID.' deleted';
				$_authed = false;
				$this->_auth_checked = true;
				return false;
		}
		
		/** user blocked */
		if(!empty($this->data[USER_BLOCKED_FLAG])){
				$this->_lastAuthError = 'User '.$this->_ID.' is blocked';
				$_authed = false;
				$this->_auth_checked = true;
				return false;
		}

		$_SESSION[$this->_cookie_prefix.'access_token'] = $hash;

		$this->_auth_checked = true;
		$this->_authed = true;
		$this->_can = unserialize($this->data['rights']);
		return true;

	}

	/**
	* Login user
	* @param string $login
	* @param string $password
	* @param integer $save_time
	* @return int see LOGIN_* defines to determine login result
	*/
	public function login($login, $password, $save_time = 0)
	{
		
		$save_time = (int)$save_time;
		$this->_resetState();
		if(!preg_match(LOGIN_PREG, $login) || !$password)
		{
			return LOGIN_ERR_LOGIN;
		}
		if(!$this->getByLogin($login))
			return LOGIN_NO_USER;

		$hashedPass = $this->hashPassword($password, $this->salt);		

		
		if($hashedPass != $this->data[PASS_FIELD])
			return LOGIN_PASS_ERR;

		if(DELETED_FLAG && !empty($this->data[DELETED_FLAG]))
			return LOGIN_NO_USER;
		
		if(USER_BLOCKED_FLAG && !empty($this->data[USER_BLOCKED_FLAG]))
			return LOGIN_BLOCKED_USER;

		$this->_ID = (int)$this->data['id'];

		$this->doLogin($save_time);

		return LOGIN_OK;
	}

	/**
	 * Возвращает строчку авторизации по токену.
	 * При этом учитывается device_id
	 * @param  string $access_token Токен, по которому искать пользователя
	 * @return array               Инфа по токену
	 */
	public function getTokenInfo($access_token)
	{
		return $this->db->selectRow('SELECT * FROM '.$this->getTableName('users_devices').' WHERE access_token = ? AND device_id = ?', $access_token, $this->getDeviceID());
	}

	public function authByToken($access_token)
	{
		$token = $this->getTokenInfo($access_token);
		if(!$token) return false;

		$this->getByID($token['user_id']);

		$this->currentToken = $access_token;
		

		$this->_authed = true;
		$this->_auth_checked = true;
		$this->_can = unserialize($this->data['rights']);

		return true;
	}

	public function doLogin($save_time = 0)
	{
		$currentToken = $this->getCurToken();
		$genNewToken = true;

		if($currentToken)
		{
			$tokenInfo = $this->getTokenInfo($currentToken);
			if($tokenInfo && ($tokenInfo['user_id'] == $this->id)) $genNewToken = false;
		}

		if($genNewToken)
		{
			$currentToken = $this->generateAccessToken();
			$this->db->query('INSERT INTO '.$this->getTableName('users_devices').' SET ?a', array(
				'user_id' => $this->id,
				'access_token' => $currentToken,
				'device_id' => $this->getDeviceID(),
				'last_update' => time(),
			));
		}

		
		$_COOKIE[$this->_cookie_prefix.'access_token'] =
			$_SESSION[$this->_cookie_prefix.'access_token'] = $currentToken;

		
			setcookie($this->_cookie_prefix.'access_token', $currentToken, $save_time, $this->_cookie_path);

		$this->db->query('UPDATE ?# SET ip = ?, last_login = ? WHERE id = ?',
			USERS_TABLE, $this->getIntIP(), time(), $this->_ID);

		$this->currentToken = $currentToken;

		$this->auth();

		return $currentToken;
	}

	/**
	 * Возвращает хеш устройства, на котором сидит юзер
	 * @return string md5-строка
	 */
	public function getDeviceID()
	{
		/**
		* @todo сделать определение юзерского девайса. для мобилок это одно, для браузеров другое
		 */
		return $this->deviceID;
	}

	public function setDeviceID($deviceID)
	{
		$this->deviceID = $deviceID;
	}

	/**
	* Хеширует пароль по заданому алгоритму
	* @param mixed $password
	* @param sting $salt
	* @return mixed
	*/
	public function hashPassword($password, $salt = '')
	{
		if(!$salt && defined('USER_FORCE_SALT_HASH') && USER_FORCE_SALT_HASH)
			$salt = $this->salt;
		switch(PASSWORD_HASH_METHOD){
			case 'md5x2':
				return md5(md5($password).$salt);
			case 'md5sha1':
				return sha1(md5($password).$salt);
			break;
			case 'sha1salt':
				return sha1($salt.$password);
			
		}
		return md5($password);
	}

	/**
		Генерирует случайный токен для авторизации
	**/
	static public function generateAccessToken()
	{
		return sha1(uniqid(rand(1, 100000), true));
	}
	
	/**
	* Устанавливает пароль
	* @param mixed $password
	* @param bool $forceLogin
	* @return void
	*/
	public function setPassword($password, $forceLogin = true){
		$p = $this->hashPassword($password);
		$l = $this->authed();
		$this->setFields(array(PASS_FIELD => $p));
		$this->db->query('DELETE FROM '.$this->getTableName('users_devices').' WHERE user_id = ?d', $this->id);
		if($l && $forceLogin) $this->login($this->data[LOGIN_FIELD], $password);
	}

	/**
	* Clear session and cookie
	* @return void
	*/
	public function logout()
	{
		
		$_COOKIE[$this->_cookie_prefix.'access_token'] =
			$_SESSION[$this->_cookie_prefix.'access_token'] = '';


		
		setcookie($this->_cookie_prefix.'access_token', '', 0, $this->_cookie_path);

		$this->db->query('DELETE FROM '.$this->getTableName('users_devices').' WHERE user_id = ?d AND access_token = ?', $this->id, $this->getCurToken());

		$this->_resetState();

	}

	/**
	* rUser::authed()
	* @param bool $force_reauth if true, user will be reauthed
	* @return bool authed or not =)
	*/
	public function authed($force_reauth = false)
	{
		
		if(!$this->_auth_checked || $force_reauth)
				$this->auth();
		return $this->_authed;
	}

	/** in php5 we can use $user->field_name for read any user fields */
	/**
	* rUser::__get()
	* @param mixed $field
	* @return mixed
	*/
	public function __get($field)
	{
		/* if(!$this->authed())
			return null; */
		return @$this->data[$field];
	}

	/**
	* can()
	* @param mixed $action
	* @return mixed
	*/
	public function can($action)
	{
		//if(!$this->authed())
		//	return false;
		if(empty($this->data['can'])) return false;
		return (@$this->data['can'][$action] || @$this->data['can'][ACCEPT_ALL_RIGHT]) && (!@$this->data['can'][DENY_ALL_RIGHT]);
	}

	/**
	* cagetIDn()
	* @return mixed
	*/
	public function getID()
	{
		return $this->_ID;
	}

	/**
	* rUser::getData()
	* @param mixed $key
	* @return mixed
	*/
	public function getData($key = null)
	{
		return  $key == null ? $this->data : $this->data[$key];
	}

	/**
	* rUser::changePassword()
	* @param mixed $new_pass
	* @return bool
	*/
	public function changePassword($new_pass){
		$this->db->query('UPDATE ?# SET password = ?, access_token = ? WHERE id = ?d', 
			USERS_TABLE, $this->hashPassword($new_pass), $this->generateAccessToken(), $this->_ID
		);
		if($this->_authed) $this->login($this->data[LOGIN_FIELD], $new_pass);
		return true;
	}

	/**
	* rUser::checkPassword()
	* @param sring $password
	* @return bool
	*/
	public function checkPassword($password){
		return $this->hashPassword($password, $this->salt) == $this->data[PASS_FIELD];
	}




	/**
	* rUser::fetchUserData()
	* Fetching user data from database to _data array
	* @return bool true if all ok, or false on any error.
	*/
	protected function fetchUserData()
	{
		
		
		if(!$this->data)
		{
			$this->_resetState();
			return false;
		}

		@$this->data['can'] = unserialize($this->data['rights']);
		$this->_ID = @(int)$this->data['id'];

		if(!$this->_ID) return false;
		
		
		
		if(!empty($this->data['userpic'])){
			foreach($this->userpics as $key => $u){
				$this->data['userpics'][$key] = $this->getURL($this->userpicFolder).$u['prefix'].$this->data['userpic'];
			}
		}

		return true;
	}

	/**
	* Reloads user data from database
	* @return void
	*/
	public function reloadData()
	{
		if(!$this->_ID) return false;
		$this->data = $this->db->selectRow($this->_selectString.' WHERE ?# = ?d',
			UID_FIELD, $this->_ID);
		$this->fetchUserData();
	}

	/**
	* Возвращает IP пользователя
	* @return string IP of current user
	*/
    static public function getIP()
    {
        $ip = empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? @$_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR'];
        $ip = explode(',',$ip);
        return trim($ip[0]);
    }

    /**
    * Возвращает IP пользователя в unsigned int формате. Подходит для MySQL функции INET_NTOA
    * @return int IP пользователя
    **/
    static public function getIntIP()
    {
    	return sprintf("%u", ip2long(rUser::getIP()));
    }


	    

	/**
	* Select user by their login, and fill _data array if user found
	* @param mixed $login
	* @return mixed
	*/
	public function getByLogin($login)
	{
		$this->data = $this->db->selectRow($this->_selectString.' WHERE ?# = ?',
			LOGIN_FIELD, $login);


		return $this->fetchUserData();
	}

	/**
	* getByID()
	* @param mixed $id
	* @return mixed
	*/
	public function getByID($id)
	{
		$this->data = $this->db->selectRow($this->_selectString.' WHERE u.?# = ?d',
			UID_FIELD, $id);

		return $this->fetchUserData();
	}

	/**
	* getField()
	* @param mixed $field
	* @return mixed
	*/
	public function getField($field)
	{
		return @$this->data[$field];
	}

	/**
	* setFields()
	* @param mixed $array
	* @return void
	*/
	public function setFields($array)
	{
		$res = $this->db->query('UPDATE '.$this->getTableName('users').' SET ?a WHERE id = ?', $array, $this->_ID);
		$this->data = $array + $this->data;

		return $this;
	}

	/**
	* setField()
	* @param mixed $name
	* @param mixed $value
	* @return rUser
	*/
	public function setField($name, $value)
	{
		return $this->setFields(array($name => $value));
	}

	/**
	* doHit()
	* @return void
	*/
	public function doHit(){
		if(!$this->authed()) return false; // только для залогиненных!

		$this->db->query('UPDATE ?# SET hits = hits + 1, lastpage = ?, ip = ?, last_online = ?d WHERE id = ?d',
			USERS_TABLE, @$_SERVER['REQUEST_URI'], $this->getIntIP(), time(), $this->_ID);
	}

	/**
	* getInfo()
	* @return mixed
	*/
	public function getInfo(){
		if(!$this->_ID) return array();
		$info = $this->db->selectRow('SELECT * FROM '.$this->getTableName('users_info').' WHERE id = ?d', $this->_ID);
		
		if(!$info){
			$this->db->query('INSERT INTO '.$this->getTableName('users_info').' SET id = ?d', $this->_ID);
			$info = $this->db->selectRow('SELECT * FROM '.$this->getTableName('users_info').' WHERE id = ?d', $this->_ID);
		}

		list($info['byear'], $info['bmonth'], $info['bday']) = explode('-', @$info['birthday']);


		return $info;		
	}
	
	/**
	* setInfo()
	* @param mixed $a
	* @return void
	*/
	public function setInfo($a){
		if(!$this->_ID) return false;

		if(!empty($a['byear']) || !empty($a['bmonth']) || !empty($a['byear'])){
			$a['birthday'] = $a['byear'].'-'.$a['bmonth'].'-'.$a['byear'];
			unset($a['byear'], $a['bmonth'], $a['bday']);
		}

		$this->db->query('UPDATE '.$this->getTableName('users_info').' SET ?a WHERE id = ?d', $a, $this->_ID);
	}	
	
	/**
	* Reset all object vars
	* @return void
	*/
	public function _resetState()
	{
		$this->data = array();
		$this->_authed = false;
		$this->_auth_checked = false;
		$this->_ID = 0;
	}

	/**
	* getPath()
	* @param string $sub_dir
	* @return mixed
	*/
	public function getPath($sub_dir = '')
	{
		/*if(!$this->_authed)
			return false;*/
		$dir = USERS_PATH.'/'.$this->_getUserDir() . '/' . $sub_dir;
		if(!is_dir($dir))
		{
			// try create user dir
			if(!is_writable(USERS_PATH))
			{
				mkdir(USERS_PATH, 0777, true);
				chmod(USERS_PATH, 0777);
				if(!is_writable(USERS_PATH))
					return false;
			}
			$this->prepareDir($dir);
		}
		return realpath($dir);

	}

	/**
	* getURL()
	* @param string $sub_dir
	* @return mixed
	*/
	public function getURL($sub_dir = '')
	{
		return rtrim(USERS_URL . $this->_getUserDir() . '/' . $sub_dir, '/ ?').'/';
	}

	/**
	* По сути просто метод вложенности 
	* @return mixed
	*/
	public function _getUserDir()
	{
		$dir = 'u/'. ceil($this->getID() / 10000000).'/';
		$dir .= ceil($this->getID() / 10000);
		return $dir;
	}

	/**
	* getCookiePrefix()
	* @return mixed
	*/
	public function getCookiePrefix(){
		return $this->_cookie_prefix;
	}
	
	/**
	* Добавляет внутреннего рейтинга (за комменты и прочие мелкие шняги)
	* @param int $rating количество баллов
	* @param bool $firstVote добавлять ли количество голосов
	* @return void
	*/
	public function addIntRating($rating, $firstVote){
		$this->db->query('UPDATE ?# SET int_rating = int_rating + ?d{, int_rating_count = int_rating_count + ?d} WHERE id = ?d', 
		USERS_TABLE, $rating, $firstVote ? 1 : DBSIMPLE_SKIP, $this->_ID);
	}
	
	
	/**
	* Добавляет внутреннего рейтинга (за комменты и прочие мелкие шняги)
	* @param int $rating количество баллов
	* @param bool $firstVote добавлять ли количество голосов
	* @return void
	*/
	public function addRating($rating, $firstVote){
		$this->db->query('UPDATE ?# SET rating = rating + ?d{, rating_count = rating_count + ?d} WHERE id = ?d', 
			USERS_TABLE, $rating, $firstVote ? 1 : DBSIMPLE_SKIP, $this->_ID);
	}
	
	/**
	* Добавляет SkillPoints
	* @param mixed $sP
	* @return bool
	*/
	public function addSkillPoints($sP){
		
		return false;
	}
	
	/**
	* Возвращает временный ID юзера, стараясь его для юзера запомнить навсегда. 
	* Используется для всяких голосований анонимных и магазинов - чтобы можно было хранить 
	* корзинку юзера привязав её к простому ID
	*
	* TODO: прикрутить evercookie для вообще навсегда-навсегда запоминания :)
	*/
	public function getMyTempID(){

		if(!empty($this->tempID)) return $this->tempID;

		if(empty($_COOKIE[$this->_cookie_prefix.'temp_id']) || !($tempID = (int)$_COOKIE[$this->_cookie_prefix.'temp_id'])){
			return $this->createTempID();
		}

		if(empty($_COOKIE[$this->_cookie_prefix.'temp_key']) || !($tempKey = trim($_COOKIE[$this->_cookie_prefix.'temp_key']))){
			return $this->createTempID();
		}

		$user = $this->db->selectRow('SELECT * FROM '.$this->getTableName('users_temp').' WHERE id = ?d', $tempID);

		if(!$user || ($user['pass_key'] != $tempKey)) 
			return $this->createTempID();

		return $user['id'];

	}

	/**
	* Создает временного юзера
	* return mixed
	*/
	protected function createTempID(){
		$key = uniqid('', true);
		$id = $this->db->query('INSERT INTO '.$this->getTableName('users_temp').' SET ?a', array(
			'pass_key' => $key,
			'dateadd' => time()
		));

		setcookie($this->_cookie_prefix.'temp_id', $id, time() + (60*60*24*300), $this->_cookie_path);
		setcookie($this->_cookie_prefix.'temp_key', $key, time() + (60*60*24*300), $this->_cookie_path);


		$this->tempID = $id;

		return $id;

	}	
	
	/**
	* uploadUserpic
	* @param mixed $pic
	* @return void
	*/
	public function uploadUserpic($pic){
		
		require_once('rlib/Imager.php');
		$imager = new Imager;
		
		if(!$pic || !file_exists($pic)) return false;
		
		if(!$imager->setImage($pic)){
			return false;
		}
		
		$dir = $this->getPath($this->userpicFolder);
		
		if(!$imager->prepareDir($dir)){
			return false;
		}
		
		$url = uniqid('');
		
		
		if(!$base_name = $imager->packetResize($dir, $this->userpics, $url)){
			return false;
		}
		
		@unlink($pic);
		
		if(!empty($this->data['userpic'])){
			// удаляем предыдущий
			// а пока не удаляем, малоличо
		}
		
		$this->setField('userpic', $base_name);

		
	}

	/**
	* Генерирует случайную соль.
	* @return string строка со случайной солью
	*/
	static public function getRandSalt(){
		return substr(uniqid('').rand(1,100), -10);
	}
	
	/**
	* prepareDir
	* @param mixed $dir
	* @return bool
	*/
	public function prepareDir($dir)
	{
		$dir = rtrim($dir, "/\\");
		if (!is_dir($dir)) {
			if (!$this->prepareDir(dirname($dir)))
				return false;
			if (@!mkdir($dir, 0777, true))
				return false;
			chmod($dir, 0777);
		}
		return true;
	}


	public function getEmailCode()
	{
		return md5($this->email.$this->id.$this->salt.$this->datereg);
	}
 
}

/************* </rUser> ***************************/
