<?php
namespace MomaSDK;

class MomaUTIL
{
    
    public static function removeElementWithValue($array, $key, $value){
        
        foreach($array as $subKey => $subArray){
            if($subArray[$key] == $value){
                unset($array[$subKey]);
            }
        }
        return $array;
    }
    
}
