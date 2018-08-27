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

/**
 * 
 * Class that allows to store environment main variables such as API KEY and site URL.
 * 
 * */
class MomaPIX
{
    
    public static $apiKey;
    public static $apiURL;
    public static $ormAutoSave  =   false;
    public static $acceptType   =   "application/vnd.api+json";
    public static $contentType  =   "application/vnd.api+json";
    
    /**
     * 
     * Sets Api Key needed for authentication
     * 
     * @param string The api key needed to connect to a MomaPIX site.
     * 
     * */
    public static function setApiKey($apiKey) : void
    {
        self::$apiKey = $apiKey;
    }
    
    /**
     * 
     * Returns the api key string.
     * 
     * @return string the api key
     * 
     * */
    public static function getApiKey() : string
    {
        return self::$apiKey;
    }
    
    /**
     * 
     * Sets the MomaPIX site url linked to the api key.
     * 
     * @param string the api url
     * 
     * */
    public static function setApiURL($url) : void
    {
        self::$apiURL = $url;
    }
    
    /**
     * 
     * Returns the MomaPIX site URL
     * 
     * @return string the MomaPIX site URL
     * */    
    public static function getApiURL()
    {
        return self::$apiURL;
    }
    
    
    public static function setOrmAutoSave()
    {
        
        self::$ormAutoSave = $ormas;
        
    }
    
}