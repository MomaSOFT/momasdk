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
    protected   $changedProperties;
    protected static $_endpoint;

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
    protected static function create($session = null)
    {
        if ($session === null) {
            $bearer_token = Session::$bearerToken;
        } else {
            $bearer_token = $session->getBearerToken();
        }

        $type       =   preg_replace("/\/rest\//", "", static::$_endpoint);
        
        $request    =  new Request(\MomaSDK\MomaPIX::$apiURL.static::$_endpoint);
        
        $headers    =   array (
            
            "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
            "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
            "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
            "Authorization: Bearer ".  $bearer_token
            
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
        
        $decodedResponse  = json_decode($response,true);
        
        if (!isset($decodedResponse['errors'][0])) {
            
            return $response;
            
        } else {
            
            switch ($decodedResponse['errors'][0]['code']) {
                
                case 1012:
                    
                    throw new \MomaSDK\Exceptions\ResourceCreationErrorException();
                    
                    break;
                default:
                    
                    throw new \Exception();
                
            }
            
        }
        
    }
    
    /**
     *
     * Enable children classes to retrieve a certain entity of their own, such as lightbox with a given id.
     *
     * @param   id.       The entity id
     * @return  response. The response from server in JSON format
     *
     **/
    public static function retrieve($id, $session = null)
    {
        if ($session === null) {
            $bearer_token = Session::$bearerToken;
        } else {
            $bearer_token = $session->getBearerToken();
        }

        $request =  new Request(\MomaSDK\MomaPIX::$apiURL.static::$_endpoint.'/'.$id);
        
        $headers    =   array (
            
            "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
            "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
            "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
            "Authorization: Bearer ".  $bearer_token
            
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
                
                case 1002:
                    
                    throw new \MomaSDK\Exceptions\ResourceNotFoundException();
                    
                    break;
                    
                default:
                    throw new \Exception($decodedResponse['errors'][0]['title']);
                    
            }
            
        }
        
        return $response;
        
    }
    
    /**
     *
     * Enable children classes to update a certain entity with all changed properties, such as a lightbox description or content.
     *
     * @param   id.       The entity id
     * @return  response. The response from server in JSON format
     *
     **/
    public function update($session = null)
    {
        if ($session === null) {
            $bearer_token = Session::$bearerToken;
        } else {
            $bearer_token = $session->getBearerToken();
        }
        
        $type       =   preg_replace("/\/rest\//","",static::$_endpoint);
        
        if (is_array($this->changedProperties) and count($this->changedProperties) == 1 and in_array("relationships",$this->changedProperties))
        {
            
            /**
             * Aggiorno solo le relationships dell'entità:
             * 
             * - Setto POST FIELDS
             * - Setto endpoint specifico
             * 
             **/
            $finalEndpoint  =   \MomaSDK\MomaPIX::$apiURL.static::$_endpoint."/".$this->attributes['id']."/relationships/items";
            
            $postFields     =   $this->relationships['items'];
            
            
        } 
        else if (is_array($this->changedProperties) and count($this->changedProperties) > 0)
        {
            
            /**
             * Rimpiazzo l'intera entità:
             *
             * - Setto POST FIELDS
             * - Setto endpoint generico
             *
             **/
            $finalEndpoint  =   \MomaSDK\MomaPIX::$apiURL.static::$_endpoint."/".$this->attributes['id'];
            
            $postFields     =   array (
                
                "data" => array (
                    
                    "type"          =>  $type,
                    "id"            =>  $this->attributes['id'],
                    "attributes"    =>  $this->attributes,
                    "relationships" =>  $this->relationships
                    
                )
            );
            
        }
        
        $request = new Request($finalEndpoint);
        
        $request  ->  setRequestHeader(
            array (
                "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
                "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
                "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
                "Authorization: Bearer ".  $bearer_token
            )
        );
        
        $request ->  setRequestType("PATCH");
        $request ->  setPostFields(json_encode($postFields));
        $request ->  execute();
        
        $response =   $request->getResponse();
        
        return $response;
        
    }
    
    /**
     *
     * Enable children classes to delete an entity of their own identifying it by an id.
     *
     * @param   id.       The entity id
     * @return  response. The response from server in JSON format
     *
     **/
    public static function delete($id, $session = null)
    {
        if ($session === null) {
            $bearer_token = Session::$bearerToken;
        } else {
            $bearer_token = $session->getBearerToken();
        }

        $request = new Request(\MomaSDK\MomaPIX::$apiURL.static::$_endpoint."/".$id);
        
        $request  ->  setRequestHeader(
            array (
                "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
                "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
                "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
                "Authorization: Bearer ".  $bearer_token
            )
        );
        
        $request ->   setRequestType("DELETE");
        $request ->   execute();
        
        $response =   $request->getResponse();
        
        return $response;
        
    }
    
    public function empty()
    {
        
        
        
    }
    
    /**
     * Abstract function whose aim is to make children classes able to adjust returned json according to their specific needs.
     *
     * @param json. A JSON string representing the entity as it comes back from REST services.
     * @return json. A manipulated JSON string that fits children classes specific needs.
     * */
    protected static abstract function fixJSON($json) : String;
    
}