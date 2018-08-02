<?php
namespace MomaSDK;

abstract class MomaRestORM
{
    
    protected   $request;
    protected   static  $endpoint;
    
    public      static  $attributes;
    public      static  $relationships;
    public      static  $included;
    public      static  $links;
    
    private     $requestType        =   "GET";
    
    /**
     * Rende persistenti le modifiche ad un oggetto come un Lightbox
     * */
    public function __construct()
    {
        
        $this->attributes     =   array ();
        $this->relationships  =   array ();
        $this->included       =   array ();
        $this->links          =   array ();
        
    }
    
    public function save()
    {
        
        // Mi appoggio a Request per fare le chiamate REST
        return $this;
        
    }
    
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
        
        $response   =   $request    ->  getResponse();
        
        error_log("\n\nRetrieve response: " . print_r($response,true)."\n\n",3,"mylog.log");
        
    }
    
}