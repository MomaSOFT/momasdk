<?php

/**
 * Class implementing MomaPIX Sessions.
 * 
 * Instantiating a Session object will contact MomaPIX's REST services in order to get a bearer token 
 * useful to keep track of the session.
 *
 * @package    MomaSDK
 * @author     Stefano Lettica <s.lettica@momapix.com>
 *
 **/

namespace MomaSDK;

class Session 
{
    
    private $request;
    private $response;
    
    public static $isLoggedIn;
    public static $bearerToken;
    
    /**
     * 
     * Uses apiKey set using the MomaPIX class to get a bearer token.
     * 
     * */
    public function __construct()
    {
        
        $this::$isLoggedIn = false;
        
        $this->request =  new Request(MomaPIX::getApiURL(). "/rest/session");
        $this->request -> setRequestType("GET");
        $this->request -> setRequestHeader(
            
            array (
                
                "Apikey:        ". \MomaSDK\MomaPIX::$apiKey,
                "Accept:        ". \MomaSDK\MomaPIX::$acceptType,
                "Content-Type:  ". \MomaSDK\MomaPIX::$contentType,
                
            )
            
        );
        
        $this->request->execute();
        $this->response = json_decode($this->request->getResponse(),true);
        
        if (!is_array($this->response['errors'][0])) {
            
            self::$bearerToken = $this->response['data']['attributes']['TokenId'];
            
        } else {
            
            self::$bearerToken = "";
            switch ($this->response['errors'][0]['code']) {
                
                case 1007:
                    
                    // Check your API Key
                    
                    break;
                    
                case 1004:
                    
                    // Resource not found
                    
                    break;
                    
                default:
                    
                    throw new \Exception($this->response['errors'][0]['detail'],$this->response['errors'][0]['code']);
                
            }
            
        }
        
    }
    
    /**
     * Returns the bearer token received by the server
     * */
    public function getBearerToken()
    {
        
        return self::$bearerToken;
        
    }
    
    /**
     * 
     * Allows to connect to a MomaPIX site using username and password
     * 
     * @param string Username
     * @param string Password
     * 
     * */
    public function connect($usr,$pwd)
    {
        
        $this->request    =   new Request(\MomaSDK\MomaPIX::getApiURL(). "/rest/session/login");
        $this->request    ->  setRequestType("PATCH");
        $this->request    ->  setRequestHeader(
            
            array (
                
                "Apikey:        ". \MomaSDK\MomaPIX::$apiKey,
                "Accept:        ". \MomaSDK\MomaPIX::$acceptType,
                "Content-Type:  ". \MomaSDK\MomaPIX::$contentType,
                "Authorization: Bearer ". self::$bearerToken
                
            )
            
        );
        
        $this->request->setPostFields(
            json_encode(
                array (
                    "data" => array (
                        'id'    =>  self::$bearerToken,
                        'type'  =>  'session',
                        'attributes'    =>  array (
                            'username'  =>  $usr,
                            'password'  =>  $pwd
                        )
                    )
                )
            )
        );
        
        $this->request->execute();
        $this->response = json_decode($this->request->getResponse(),true);
        
        if (!is_array($this->response['errors'][0])) {
            
            self::$isLoggedIn = true;
            
        } else {
            
            switch ($this->response['errors'][0]['code']) {
                
                default:
                    
                    self::$isLoggedIn = false;
                    throw new \Exception($this->response['errors'][0]['detail'],$this->response['errors'][0]['code']);
                
            }
            
        }
        
    }
    
    /**
     * 
     * Checks wether the user is logged in or not.
     * 
     * @return bool true|false The log-in status
     * 
     * */
    public static function isLoggedIn() : bool
    {
        
        return self::$isLoggedIn;
        
    }
    
    public function getRequest() : Request
    {
        
        return $this->request;
        
    }
    
    public function getResponse()
    {
        
        return $this->response;
        
    }
    
}