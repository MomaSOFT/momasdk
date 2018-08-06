<?php

/**
 * Abstract class to implement CRUD operations using endpoints.
 *
 * @package    MomaSDK
 * @subpackage 
 * @license    
 * @author     Stefano Lettica <s.lettica@momapix.com>
 * 
 **/

namespace MomaSDK;

abstract class MomaRestORM
{
    
    // Object properties
    protected   $links;
    protected   $included;
    protected   $attributes;
    protected   $relationships;
    
    // Accessory variables
    protected   $request;
    protected   $response;
    protected   $endpoint;
    
    private     $requestType =  "GET";
    
    public function __construct() {
        
    }

    /**
     * 
     * Makes all changes to the entity persistent to the database.
     * 
     * */
    public function save()
    {
        
        // Mi appoggio a Request per fare le chiamate REST
        $this->request = new Request(\MomaSDK\MomaPIX::$apiURL.$this->endpoint);
        
        $this->request  ->  setRequestHeader(
            array (
                "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
                "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
                "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
                "Authorization: Bearer ".  Session::$bearerToken
            )
        );
        
        $this->request ->  setRequestType("POST");
        
        $this->request ->  setPostFields(
            json_encode(
                array (
                    "data" => array (
                        "type"          => "lightbox",
                        "attributes"    => $this->attributes,
                        "relationships" => $this->relationships
                    )
                )
            )
        );
        
        $this->request ->   execute();
        $this->response =   $this->request->getResponse();
        
        return $this->response;
        
    }
    
    /*
    public static function create()
    {
        error_log("\n\nMomaRestORM::Create: endpoint: " . self::$endpoint."\n\n",3,"mylog.log");
        
        $request =  new Request(\MomaSDK\MomaPIX::$apiURL.self::$endpoint);
        
        $headers    =   array (
            
            "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
            "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
            "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
            "Authorization: Bearer ".  Session::$bearerToken
            
        );
        
        $request ->  setRequestHeader($headers);
        
        $request ->  setRequestType("POST");
        
        $request ->  setPostFields(
            json_encode(
                array (
                    "data" => array (
                        "type"          => "lightbox",
                        "attributes"    => self::$attributes,
                        "relationships" => self::$relationships
                    )
                )
            )
        );
        
        // Provo a fare una POST senza parametri
        $request ->  execute();
        $response = $request->getResponse();
        
        error_log("\n\nCreate method response: " . print_r($response,true)."\n\n",3,"mylog.log");
        
        return $response;
        
        
    }
    
    public static function retrieve($endpoint)
    {
        
        self::$endpoint = \MomaSDK\MomaPIX::$apiURL.$endpoint;
        
        error_log("\n\nEndpoint: " . self::$endpoint."\n\n",3,"mylog.log");

        $headers    =   array (
            
            "Apikey:        ".\MomaSDK\MomaPIX::$apiKey,
            "Accept:        ".\MomaSDK\MomaPIX::$acceptType,
            "Content-Type:  ".\MomaSDK\MomaPIX::$contentType
            
        );
        
        $request    =   new Request(self::$endpoint);
        
        $request    ->  setRequestType("GET");
        $request    ->  setRequestHeader($headers);
        $request    ->  execute();
        
        $response   =   $request    ->  getsResponse();
        
        error_log("\n\nRetrieve response: " . print_r($response,true)."\n\n",3,"mylog.log");
        
    }
    
    public static function delete($endpoint)
    {
        
        
        
    }
    */
    
    protected static abstract function fixJSON($json) : String;
    
}