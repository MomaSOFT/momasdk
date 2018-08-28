<?php

/**
 * Abstract class to implement CRUD operations using REST endpoints.
 * It allows to handle MomaPIX resources such as Lightboxes, and so on...
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
    
    /**
     *
     * These variables won't never used directly by this class.
     * Their purpose is to provide a fixed entity structure when converting the into object of a specific class.
     *
     * */
    protected   $meta;
    protected   $links;
    protected   $included;
    protected   $attributes;
    protected   $relationships;
    
    private     $requestType    =   "GET";
    
    /**
     *
     * Enable children classes to create their own entity, such as Lightboxes.
     *
     * @param   endpoint. The complete endpoint url to be contacted
     *
     * @return  response. The response from server in JSON format
     *
     **/
    protected static function create($endpoint)
    {
        
        $type       =   preg_replace("/\/rest\//","",$endpoint);
        
        $request    =  new Request(\MomaSDK\MomaPIX::$apiURL.$endpoint);
        
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
                        "type"          => $type,
                        "attributes"    => null,
                        "relationships" => null
                    )
                )
                )
            );
        
        // Provo a fare una POST senza parametri
        $request ->  execute();
        $response = $request->getResponse();
        
        return $response;
        
    }
    
    /**
     *
     * Enable children classes to retrieve a certain entity of their own, such as lightbox with a given id.
     *
     * @param   id.       The entity id
     * @param   endpoint. The complete endpoint url to be contacted
     *
     * @return  response. The response from server in JSON format
     *
     **/
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
        $response =  $request->getResponse();
        
        $decodedResponse  = json_decode($response,true);
        
        if (!isset($decodedResponse['errors'][0])) {
            
            return $response;
            
        } else {
            
            switch ($decodedResponse['errors'][0]['code']) {
                
                case "1002":
                    
                    MomaUTIL::log("*** ResourceNotFoundException ***");
                    throw new \MomaSDK\ResourceNotFoundException();
                    
                    break;
                    
                default:
                    
                    MomaUTIL::log("*** GenericException ***");
                    throw new \Exception();
                    
            }
            
        }
        
        MomaUTIL::log("Retrieving: " . print_r($response,true));
        
        return $response;
        
    }
    
    /**
     *
     * Enable children classes to update a certain entity with all changed properties, such as a lightbox description or content.
     *
     * @param   id.       The entity id
     * @param   endpoint. The endpoint url to be contacted
     *
     * @return  response. The response from server in JSON format
     *
     **/
    public function update($id,$endpoint) {
        
        $type       =   preg_replace("/\/rest\//","",$endpoint);
        
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
        
        MomaUTIL::log("relationships: " . print_r($this->relationships,true));
        
        $request ->  setPostFields(
            json_encode(
                array (
                    "data" => array (
                        
                        "type"          =>  $type,
                        "id"            =>  $id,
                        "attributes"    =>  $this->attributes,
                        "relationships" =>  $this->relationships
                        
                    )
                )
                )
            );
        
        $request ->   execute();
        $response =   $request->getResponse();
        
        return $response;
        
    }
    
    /**
     *
     * Enable children classes to delete an entity of their own identifying it by an id.
     *
     * @param   id.       The entity id
     * @param   endpoint. The endpoint url to be contacted
     *
     * @return  response. The response from server in JSON format
     *
     **/
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
        
        return $response;
        
    }
    
    /**
     * Abstract function whose aim is to make children classes able to adjust returned json according to their specific needs.
     *
     * @param json. A JSON string representing the entity as it comes back from REST services.
     * @return json. A manipulated JSON string that fits children classes specific needs.
     * */
    protected static abstract function fixJSON($json) : String;
    
}