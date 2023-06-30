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
    protected   $_changedProperties;
    protected   $_data;
    protected   $_session;
    protected static $_endpoint;
    
    private     $requestType    =   "GET";
    
    /**
     * Constructor
     */
    public function __construct($api_response, $session = null)
    {
        $this->_changedProperties = array();
        $this->_api_response = $api_response;
        $this->_data = json_decode($api_response, true);
        
        if (!empty($session)) {
            $this->_session = $session;
        }
    }
    
    /**
     *
     * Enable children classes to create their own entity, such as Lightboxes.
     *
     * @param   endpoint. The complete endpoint url to be contacted
     *
     * @return  response. The response from server in JSON format
     *
     **/
    public static function create($session = null)
    {
        
        if (!empty($session)) {
            $bearer_token = $session->getBearerToken();
        } else {
            $bearer_token = Session::$bearerToken;
        }
        
        if (empty($bearer_token)) {
            throw new \MomaSDK\Exceptions\ResourceCreationErrorException('empty bearer token');
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
                    )
                )
                )
            );
        
        // Provo a fare una POST senza parametri
        $request ->  execute();
        $response = $request->getResponse();
        
        $decodedResponse  = json_decode($response,true);
        
        if ( preg_match("/lightbox/",static::$_endpoint) ) {
            
            if ( !array_key_exists("included",$decodedResponse  ) ) {
                
                $decodedResponse['included'] = array();
                
            }
            
            if ( !array_key_exists("relationships",$decodedResponse['data']  ) ) {
                
                $decodedResponse['data']['relationships'] = array('items' => array ('data' => array()));
                
            }
            
            $response = json_encode($decodedResponse);
            
        }
        
        if (!isset($decodedResponse['errors'][0])) {
            
            return new static($response, $session);
            
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
        
        if (!empty($session)) {
            $bearer_token = $session->getBearerToken();
        } else {
            $bearer_token = Session::$bearerToken;
        }
        
        if (empty($bearer_token)) {
            throw new \MomaSDK\Exceptions\ResourceNotFoundException('empty bearer token');
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
        
        if ( preg_match("/lightbox/",static::$_endpoint) ) {
            
            if ( !array_key_exists("included",$decodedResponse ) ) {
                
                $decodedResponse['included'] = array();
                
            }
            
            if ( !array_key_exists("relationships",$decodedResponse['data']  ) ) {
                
                $decodedResponse['data']['relationships'] = array( "items" => array( "data" => array() ));
                
            }
            
            $response = json_encode($decodedResponse);
            
        }
        
        if (!isset($decodedResponse['errors'][0])) {
            
            return new static($response, $session);
            
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
    public function update()
    {
        
        if (!empty($this->_session)) {
            $bearer_token = $this->_session->getBearerToken();
        } else {
            $bearer_token = Session::$bearerToken;
        }
        
        $type       =   preg_replace("/\/rest\//","",static::$_endpoint);
        
        if (is_array($this->_changedProperties) and count($this->_changedProperties) == 1 and in_array("relationships",$this->_changedProperties))
        {
            
            /**
             * Aggiorno solo le relationships dell'entità:
             *
             * - Setto POST FIELDS
             * - Setto endpoint specifico
             *
             **/
            $finalEndpoint  =   \MomaSDK\MomaPIX::$apiURL.static::$_endpoint."/".$this->_data['attributes']['id']."/relationships/items";
            
            $postFields     =   $this->_data['relationships']['items'];
            
            
        }
        else if (is_array($this->_changedProperties) and count($this->_changedProperties) > 0)
        {
            
            /**
             * Rimpiazzo l'intera entità:
             *
             * - Setto POST FIELDS
             * - Setto endpoint generico
             *
             **/
            $finalEndpoint  =   \MomaSDK\MomaPIX::$apiURL.static::$_endpoint."/".$this->_data['attributes']['id'];
            
            switch ( static::$_endpoint ) {
                
                case '/rest/lightbox':
                    
                    $postFields     =   array (
                    
                    "data" => array (
                    
                    "type"          =>  $type,
                    "id"            =>  $this->_data['attributes']['id'],
                    "attributes"    =>  array("description" => $this->_data['attributes']['description']),
                    "relationships" =>  $this->_data['relationships']
                    
                    )
                    
                    );
                    
                    break;
                    
                default:
                    
                    $postFields     =   array (
                    
                    "data" => array (
                    
                    "type"          =>  $type,
                    "id"            =>  $this->_data['attributes']['id'],
                    "attributes"    =>  $this->_data['attributes'],
                    "relationships" =>  $this->_data['relationships']
                    
                    )
                    );
                    
            }
            
            
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
    public function delete()
    {
        if (!empty($this->_session)) {
            $bearer_token = $this->_session->getBearerToken();
        } else {
            $bearer_token = Session::$bearerToken;
        }
        
        $id = $this->_data['attributes']['id'];
        
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