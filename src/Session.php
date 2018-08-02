<?php
namespace MomaSDK;

class Session 
{
    
    private $request;
    private $response;
    
    public static $isLoggedIn;
    public static $bearerToken;
    
    /**
     * 
     * Uses apiKey to get a bearer token
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
            
            error_log("Session response " . print_r($this->response,true),3,"mylog.log");
            
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
    
    public function getBearerToken()
    {
        
        return self::$bearerToken;
        
    }
    
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
                
                case 2002:
                    
                    // Authentication failure. Username or password wrong.
                    
                    break;
                    
                default:
                    
                    throw new \Exception($this->response['errors'][0]['detail'],$this->response['errors'][0]['code']);
                
            }
            
        }
        
    }
    
    public static function isLoggedIn()
    {
        
        return self::$isLoggedIn;
        
    }
    
    public function getRequest()
    {
        
        return $this->request;
        
    }
    
    public function getResponse()
    {
        
        return $this->response;
        
    }
    
    
}