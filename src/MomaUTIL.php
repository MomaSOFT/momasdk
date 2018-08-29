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

class MomaUTIL
{
    
    /**
     * 
     * Logs messages 
     * 
     * @param String $message
     * 
     **/    
    public static function log($message) : void
    {
        
        error_log("\n----------\n". $message . "\n----------\n",3,"momasdk.log");
        
    }
    
    public static function removeElementWithValue($array, $key, $value) {
        
        foreach($array as $subKey => $subArray){
            if($subArray[$key] == $value){
                unset($array[$subKey]);
            }
        }
        return $array;
    }
    
    public static function searchElementWithValue($array, $key, $value) {
        
        foreach ($array as $subKey => $subArray) {
            
            if($subArray[$key] == $value){
                
                return true;
            }
        }
        return false;
        
    }
    
}
