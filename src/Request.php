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

use Exception;

class Request
{
    
    private $url;
    
    private $requestType    =   "GET";
    private $postFields;
    
    private $connectTimeout =   10;
    private $timeout        =   15;
    
    // Variables used for cookie support.
    private $cookiePath;
    private $cookiesEnabled =   false;
    
    // Enable or disable SSL/TLS.
    private $ssl            =   false;
    
    private $responseBody;
    private $responseHeader;

    private $httpCode;

    private $error;
    
    private $requestHeader;
    
    public function __construct($url)
    {
     
        error_log("URL: $url",3,"mylog.log");
        
        if (!isset($url)) {
            throw new Exception("Error: Address not provided.");
        }
        $this->url = $url;
        
    }
    
    public function execute() 
    {
        
        error_log("\nRequest url: ".$this->url,3,"mylog.log");
        // Set up cURL options.
        $handler = curl_init();
        
        if ($this->cookiesEnabled)
        {
            curl_setopt($handler, CURLOPT_COOKIEJAR,    $this->cookiePath);
            curl_setopt($handler, CURLOPT_COOKIEFILE,   $this->cookiePath);
        }

        // Send a custom request if set (instead of standard GET).
        if (isset($this->requestType)) 
        {
            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $this->requestType);
            // If POST fields are given, and this is a POST request, add fields.
            if (($this->requestType == 'POST' or $this->requestType == 'PATCH') && isset($this->postFields) ) {
                curl_setopt($handler, CURLOPT_POSTFIELDS, $this->postFields);
            }
        }
        
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,   true);
        curl_setopt($handler, CURLOPT_URL,              $this->url);
        
        curl_setopt($handler, CURLOPT_CONNECTTIMEOUT,   $this->connectTimeout);
        curl_setopt($handler, CURLOPT_TIMEOUT,          $this->timeout);
        
        // Follow redirects (maximum of 5).
        curl_setopt($handler, CURLOPT_FOLLOWLOCATION,true);
        
        // Set a custom UA string so people can identify our requests.
        curl_setopt($handler, CURLOPT_USERAGENT,$this->userAgent);
        
        // Output the header in the response.
        curl_setopt($handler,   CURLOPT_HEADER, true);
        if (isset($this->requestHeader))    curl_setopt($handler,    CURLOPT_HTTPHEADER,        $this->requestHeader);
        
        $response               = curl_exec     ($handler);
        $error                  = curl_error    ($handler);
        $http_code              = curl_getinfo  ($handler, CURLINFO_HTTP_CODE);
        $header_size            = curl_getinfo  ($handler, CURLINFO_HEADER_SIZE);
        $time                   = curl_getinfo  ($handler, CURLINFO_TOTAL_TIME);
        
        curl_close($handler);
        
        $this->responseHeader   =   substr($response, 0, $header_size);
        $this->responseBody     =   substr($response, $header_size);
        $this->error            =   $error;
        $this->httpCode         =   $http_code;
        
    }
    
    public function setURL($url)
    {
        $this->url = $url;
        
    }
    
    public function setRequestType($type)
    {
        
        $this->requestType = $type;
        
    }
    
    public function setRequestHeader($headers)
    {
        
        $this->requestHeader = $headers;
        
    }
    
    public function getRequestType() :string
    {
        
        return $this->requestType;
        
    }
        
    public function enableCookies($cookie_path) :void
    {
        
        $this->cookiesEnabled  =   true;
        $this->cookiePath      =   $cookie_path;
        
    }
    
    public function disableCookies()
    {
    
        $this->cookiesEnabled  =   false;
        $this->cookiePath      =   '';
        
    }
    
    public function enableSSL()
    {
        $this->ssl = true;
    }
    
    public function disableSSL()
    {
        
        $this->ssl = false;
        
    }
    
    public function setTimeout($timeout = 15)
    {
        
        $this->timeout = $timeout;
        
    }

    public function getTimeout()
    {
        
        return $this->timeout;
        
    }
    
    public function setConnectTimeout($connectTimeout = 10)
    {
        
        $this->connectTimeout = $connectTimeout;
        
    }
    
    public function getConnectTimeout()
    {
        
        return $this->connectTimeout;
        
    }
    
    public function setPostFields($fields = array())
    {
        
        $this->postFields = $fields;
        
    }
    
    public function getResponse()
    {
        
        return $this->responseBody;
        
    }
    
    public function getHeader()
    {
        
        return $this->responseHeader;
        
    }
    
    public function getHttpCode()
    {
        
        return $this->httpCode;
        
    }
    
    public function getError()
    {
        
        return $this->$error;
        
    }
    
}