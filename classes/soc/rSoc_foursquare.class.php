<?php

require_once('classes/soc/rSocNetworkBase.class.php');


/**
* Foursquare authorization
*/
class rSoc_foursquare extends rSocNetworkBase
{

	protected $loginURL = 'https://foursquare.com/oauth2/authenticate';
	protected $accessTokenURL = 'https://foursquare.com/oauth2/access_token';
	protected $apiURL = 'https://api.foursquare.com/v2/';


        public function getAccessToken($code)
        {
            $params = array(
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'code' => $code,
                'redirect_uri' => $this->getRedirectURI(),
                'grant_type' => 'authorization_code'
            );

            $token = $this->vb->get($this->accessTokenURL .'?'.http_build_query($params, '', '&'));
            
	    return $token;	    
	    
	}
	
	public function parseToken($token)
	{
	    if(!$token) return false;
	    $token = parent::parseToken($token);
            $uInfo = $this->requestAPI('users/self', array('oauth_token' => $token['access_token'], 'v' => 20131206));
            if(empty($uInfo->response->user)) return false;
            $uInfo = $uInfo->response->user;
            
            $token['name'] = $uInfo->firstName.' '.$uInfo->lastName;
            $token['user_id'] = $uInfo->id;
            $token['userpic'] = is_object($uInfo->photo) ? $uInfo->photo->prefix.'300x300'.$uInfo->photo->suffix 
        	: $uInfo->photo;
    	    $token['login'] = $uInfo->id;
    	    
            
	    return $token;        


	}

}