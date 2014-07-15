<?php

require_once('classes/soc/rSocNetworkBase.class.php');


/**
* Vkontakte
*/
class rSoc_instagram extends rSocNetworkBase
{

	protected $loginURL = 'https://api.instagram.com/oauth/authorize/';
	protected $accessTokenURL = 'https://api.instagram.com/oauth/access_token';
	
	public function getAccessToken($code)
	{
	    $params = array(
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'code' => $code,
			'grant_type' => 'authorization_code',
			'redirect_uri' => $this->getRedirectURI(),
	    );

	    $token = $this->vb->post($this->accessTokenURL, $params);
	    
	    return $token;
	}
	
	public function parseToken($token)
	{
	    if(!$token) return false;
	    
	    $token = @json_decode($token);	    
			if($token && !empty($token->access_token)){
			    return array(
					'access_token' => $token->access_token,
					'user_id' => $token->user->id,
					'userpic' => $token->user->profile_picture,
					'login' => $token->user->username,
					'name' => $token->user->full_name
			    );
			}
	    
	    return false;
	    
	}
}