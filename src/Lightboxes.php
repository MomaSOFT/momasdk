<?php

namespace MomaSDK;

use Karriere\JsonDecoder\JsonDecoder;

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
        
        $jd  =   new JsonDecoder(false,true);
        
        $lightboxes  =   self::fixJSON($response);
        
        return $jd->decodeMultiple($lightboxes, Lightbox::class);
        
    }
    
    private function fixJSON($json) : string
    {
        
        $array        =  json_decode($json,true);
        
        $index        =  0;
        $lightboxes   =  array();
        
        foreach ($array['data'] as $lbox) {
            
            $lightboxes[$index] =    array (
                
                "meta"           =>  array(),
                "links"          =>  $lbox['links'],
                "attributes"     =>  $lbox['attributes'],
                "relationships"  =>  $lbox['relationships']
                
            );
            
            $index++;
            
        }
        
        return json_encode($lightboxes);
        
    }
    
}
