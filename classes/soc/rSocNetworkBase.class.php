<?php


class rSocException extends Exception{};

require_once('rlib/vibrowser.inc.php');



/**
* Базовый класс для осуществления работы с соц-сетью
*/
class rSocNetworkBase
{
	protected $app;
	protected $data;
	protected $vb;

	protected $loginURL = '';
	protected $accessTokenURL = '';
	protected $apiURL = '';
	protected $apiReqMethod = 'get';
	protected $apiDecode = 'json';
	

	protected $response_type = 'code';
	
	
	function __construct(rApplication $app, $data)
	{
		$this->app = $app;
		$this->data = $data;
		$this->vb = new ViBrowser;
		
		
	}

	static public function getInstance(rApplication $app, $id_or_URL)
	{
		$field = 'url';
		if(is_numeric($id_or_URL)) $field = 'id';
		$data = $app->db->selectRow('SELECT * FROM social_networks WHERE ?# = ?', $field, $id_or_URL);
		if(!$data) return false;

		$url = $data['url'];

		$className = 'rSoc_'.$url;
		require_once 'classes/soc/'.$className.'.class.php';
		return new $className($app, $data);
	}

	public function __get($key)
	{
		if(!isset($this->data[$key])) return NULL;
		return $this->data[$key];
	}

	public function redirect2login($params)
	{
		$url = $this->loginURL.'?'.http_build_query($params, '', '&');
		$this->app->url->redirect($url);
	}

	public function getRedirectParams()
	{
		return array(
			'client_id' => $this->client_id,
			'redirect_uri' => $this->getRedirectURI(),
			'response_type' => $this->response_type,
		);
	}
	
	public function getAccessToken($code)
	{
	    $params = array(
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'code' => $code,
			'redirect_uri' => $this->getRedirectURI(),
	    );

	    $token = $this->vb->get($this->accessTokenURL .'?'.http_build_query($params, '', '&'));
	    
	    return $token;
	    return $this->parseToken($token);	    
	}

	public function parseToken($token)
	{
		if(!$token){
			return false;
		}

		$token = @json_decode($token);
		
//		print_r($token);
		if(!$token) return false;
		if(!empty($token->access_token)){
		    return array(
				'access_token' => $token->access_token,
				'user_id' => trim(@$token->user_id)
		    );
		}

	    return false;
	}
	

	protected function getRedirectURI()
	{
		return SERVER_URL.'login/as/'.$this->url.'/done/';
	}
	
	public function requestAPI($method, $params, $reqMethod = NULL)
	{
	    if($reqMethod == NULL)
		$reqMethod = $this->apiReqMethod;
		
	    if($reqMethod == 'get'){
		$result = $this->vb->get($this->apiURL.$method.'?'.http_build_query($params, '', '&'));
	    }elseif($reqMethod == 'post'){
		$result = $this->vb->post($this->apiURL.$method, $params);
	    }else{
		throw new rSocException('Unknown request method '.$reqMethod);
	    }
	    
	    if($result && ($this->apiDecode == 'json')){
		$result = json_decode($result);
	    }
	    
	    return $result;
	}

}


/**
* External networks table
*/
class rSocNetworkTable extends rTableClass
{
	
	function __construct($db, $name = null)
	{
		parent::__construct($db, 'social_networks');
	}

}



/**
* Users work
*/
class rUserExternal
{

	protected $u;
	
	function __construct(rUser $user)
	{
		$this->u = $user;
	}

	public function getMySocials()
	{
		$list = $this->u->_db->select('SELECT e.network_id AS ARRAY_KEY, e.*, 
				n.url as network_url, n.name as network_name 
			FROM users_external e 
			LEFT JOIN social_networks n ON n.id = e.network_id

			WHERE e.user_id = ?d', $this->u->getID());

		return $list;
	}

	public function getExtData($network_id)
	{
		return $this->u->_db->selectRow('SELECT * FROM users_external WHERE network_id = ?d AND user_id = ?', 
			$network_id, $this->u->getID());
	}

	public function isUserExists($network_id, $client_id)
	{
		return $this->u->_db->selectCell('SELECT user_id FROM users_external WHERE network_id = ?d AND client_id = ?', 
			$network_id, $client_id);
	}

	public function markLogin($network_id, $client_id)
	{
		$this->u->_db->query('UPDATE users_external SET last_login = ?d WHERE network_id = ?d AND client_id = ?', 
			time(), $network_id, $client_id);
	}

	public function connect($network_id, $token)
	{
		return $this->u->_db->query('INSERT INTO users_external SET ?a 
				ON DUPLICATE KEY UPDATE 
				    client_id = VALUES(client_id), 
				    client_secret = VALUES(client_secret), 
				    last_update = VALUES(last_update), 
				    last_login = VALUES(last_login),
				    name = VALUES(name),
				    login = VALUES(login),
				    userpic = VALUES(userpic)
				    ',
					array(
					    'user_id' => $this->u->getID(),
					    'network_id' => $network_id,
					    'client_id' => $token['user_id'],
					    'client_secret' => $token['access_token'],
					    'last_update' => time(),
					    'last_login' => time(),
					    'userpic' => $token['userpic'],
					    'login' => $token['login'],
					    'name' => $token['name'],
					)
			    );
	}


	/**
	* Отключает соц-сеть от юзера
	* @var int $network_id ID соц-сети в приложении
	**/
	public function disconnect($network_id)
	{
		return $this->u->_db->query('DELETE FROM users_external 
			WHERE user_id = ?d AND network_id = ?d',
				$this->u->getID(),
				$network_id
		);
	}

	public function useUserpic($network_id)
	{
		$upic = $this->u->_db->selectCell('SELECT userpic FROM users_external 
				WHERE network_id = ?d AND user_id = ?d', 
			$network_id, $this->u->getID()
		);

		if(!$upic) return false;

		$vb = new ViBrowser;

		$localFile = TMP_PATH.'/'.basename($upic);
		$vb->getURLToFile($upic, $localFile);
		if(file_exists($localFile)){
			$this->u->uploadUserpic($localFile);
		}
		@unlink($localFile);

		$this->u->reloadData();
		$this->u->_fetchUserData();


		return $this->u->userpics;
	}
}