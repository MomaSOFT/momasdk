<?php
namespace MomaSDK;

class MomaPIX
{
    
    public static $apiKey;
    public static $apiURL;
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
    
}