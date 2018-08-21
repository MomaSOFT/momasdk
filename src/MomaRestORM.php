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
    protected   $meta;
    protected   $links;
    protected   $included;
    protected   $attributes;
    protected   $relationships;
    
    private     $requestType    =   "GET";
    
    public static function create($endpoint)
    {
        
        $request =  new Request(\MomaSDK\MomaPIX::$apiURL.$endpoint);
        
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
                        "attributes"    => null,
                        "relationships" => null
                    )
                )
            )
        );
        
        // Provo a fare una POST senza parametri
        $request ->  execute();
        $response = $request->getResponse();
        
        if (!is_array($response['errors'][0])) {
            
            return $response;
            
        } else {
        
            switch ($response['errors'][0]['code']) {
                
                default:
                    
                    MomaUTIL::log(print_r($response['errors']));
                
            }
            
        }
        
    }
    
    public static function retrieve($id,$endpoint)
    {
        
        $request =  new Request(\MomaSDK\MomaPIX::$apiURL.$endpoint.$id);
        
        $headers    =   array (
            
            "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
            "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
            "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
            "Authorization: Bearer ".  Session::$bearerToken
            
        );
        
        $request ->  setRequestHeader($headers);
        
        $request ->  setRequestType("GET");
        
        $request ->  execute();
        $response = $request->getResponse();
        
        if (!is_array($response['errors'][0])) {
            
            return $response;
            
        } else {
            
            switch ($response['errors'][0]['code']) {
                
                default:
                    
                    MomaUTIL::log(print_r($response['errors']));
                    
            }
            
        }
        
    }
    
    public function update($id,$endpoint) {
        
        $request = new Request(\MomaSDK\MomaPIX::$apiURL.$endpoint.$id);
        
        $request  ->  setRequestHeader(
            array (
                "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
                "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
                "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
                "Authorization: Bearer ".  Session::$bearerToken
            )
        );

        $request ->  setRequestType("PATCH");

        $request ->  setPostFields(
                json_encode(
                        array (
                                "data" => array (
                                    "type"          => "lightbox",
                                    "id"            =>  $id,
                                    "attributes"    =>  $this->attributes,
                                )
                    )
                )
        );

        $request ->   execute();
        $response =   $request->getResponse();
        
        if (!is_array($response['errors'][0])) {
            
            return $response;
            
        } else {
            
            switch ($response['errors'][0]['code']) {
                
                default:
                    
                    MomaUTIL::log(print_r($response['errors']));
                    
            }
            
        }
        
    }
    
    public static function delete($id,$endpoint)
    {
        
        MomaUTIL::log("Endpoint: " . \MomaSDK\MomaPIX::$apiURL.$endpoint.$id);
        
        $request = new Request(\MomaSDK\MomaPIX::$apiURL.$endpoint.$id);
        
        $request  ->  setRequestHeader(
            array (
                "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
                "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
                "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
                "Authorization: Bearer ".  Session::$bearerToken
            )
        );
        
        $request ->   setRequestType("DELETE");
        $request ->   execute();
        $response =   $request->getResponse();
        
        if (!is_array($response['errors'][0])) {
            
            return $response;
            
        } else {
            
            switch ($response['errors'][0]['code']) {
                
                default:
                    
                    MomaUTIL::log(print_r($response['errors']));
                    
            }
            
        }
        
    }
    
    protected static abstract function fixJSON($json) : String;
    
}