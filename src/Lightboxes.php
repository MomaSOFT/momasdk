<?php

namespace MomaSDK;

class Lightboxes 
{
    
       public static function getAllLightboxes()
       {
           
           $endpoint    =   "/rest/lightbox";
           
           $request     =   new Request(\MomaSDK\MomaPIX::$apiURL.$endpoint);
           
           $headers     =   array (
               
               "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
               "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
               "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
               "Authorization: Bearer ".  Session::$bearerToken
               
           );
           
           $request ->  setRequestHeader($headers);
           
           $request ->  setRequestType("GET");
           
           // Provo a fare una POST senza parametri
           $request ->  execute();
           
           $response = $request->getResponse();
           
           return $response;
           
       }
    
}
