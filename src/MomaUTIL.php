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
    
    public static function log($message)
    {
        
        error_log("\n----------\n". $message . "\n----------\n",3,"momasdk.log");
        
    }
    
    public static function removeElementWithValue($array, $key, $value){
        
        foreach($array as $subKey => $subArray){
            if($subArray[$key] == $value){
                unset($array[$subKey]);
            }
        }
        return $array;
    }
    
}
