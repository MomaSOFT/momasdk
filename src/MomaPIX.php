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

class MomaPIX
{
    
    public static $apiKey;
    public static $apiURL;
    public static $ormAutoSave  =   false;
    public static $acceptType   =   "application/vnd.api+json";
    public static $contentType  =   "application/vnd.api+json";
    
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }
    
    public static function getApiKey()
    {
        return self::$apiKey;
    }
    
    public static function setApiURL($url)
    {
        self::$apiURL = $url;
    }
    
    public static function getApiURL()
    {
        return self::$apiURL;
    }
    
    public static function setOrmAutoSave()
    {
        
        self::$ormAutoSave = $ormas;
        
    }
    
}