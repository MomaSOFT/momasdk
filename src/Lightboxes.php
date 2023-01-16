<?php
namespace MomaSDK;

class Lightboxes
{
    
    public static function getAllLightboxes($session = null)
    {
        
        $endpoint    =   "/rest/lightbox";
        
        $request     =   new Request(\MomaSDK\MomaPIX::$apiURL.$endpoint);

        if ($session === null) {
            $bearer_token = Session::$bearerToken;
        } else {
            $bearer_token = $session->getBearerToken();
        }
        
        $headers     =   array (
            
            "Apikey:  ".               \MomaSDK\MomaPIX::$apiKey,
            "Accept:  ".               \MomaSDK\MomaPIX::$acceptType,
            "Content-Type:  ".         \MomaSDK\MomaPIX::$contentType,
            "Authorization: Bearer ".  $bearer_token
            
        );
        
        $request ->  setRequestHeader($headers);
        
        $request ->  setRequestType("GET");
        
        // Provo a fare una POST senza parametri
        $request ->  execute();
        
        $response = $request->getResponse();
        
        $lightboxes_response = self::fixJSON($response);

        $lightboxes_data = json_decode($lightboxes_response, true);

        return array_map(
            function ($lightbox_data) use ($session) {
                return new Lightbox($lightbox_data, $session);
            },
            $lightboxes_data
        );
    }
    
    private function fixJSON($json) : string
    {
        if (is_array($json)) {
            $array = $json;
        } else {
            $array = json_decode($json,true);
        }
        
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
