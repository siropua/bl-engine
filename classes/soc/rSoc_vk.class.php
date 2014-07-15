<?php

require_once('classes/soc/rSocNetworkBase.class.php');


/**
* Vkontakte
*/
class rSoc_vk extends rSocNetworkBase
{

	protected $loginURL = 'https://oauth.vk.com/authorize';
	protected $accessTokenURL = 'https://oauth.vk.com/access_token';
	protected $apiURL = 'https://api.vk.com/method/';
	
	public function getRedirectParams()
	{
		$params = parent::getRedirectParams();
		$params['scope'] = 'offline';
		$params['v'] = '5.0';
		$params['display'] = 'page';

		return $params;
	}
	
	public function parseToken($token)
	{
	    if(!$token) return false;
	    $token = parent::parseToken($token);
	    
	    if(!$token || empty($token['access_token'])) return false;
	    
	    $uInfo = $this->requestAPI('users.get', array('access_token' => $token['access_token'], 'fields' => 'photo_max,domain'));
	    if($uInfo && is_object($uInfo) && empty($uInfo->error)){
		$token['userpic'] = $uInfo->response[0]->photo_max;
		$token['login'] = $uInfo->response[0]->domain;
		$token['name'] = $uInfo->response[0]->first_name.' '.$uInfo->response[0]->last_name;	    
	    }else return false;
	    
	    return $token;
	}
}