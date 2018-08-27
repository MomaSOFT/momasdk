<?php

namespace MomaSDK;

class ResourceNotFoundException extends \Exception
{
    
    public function errorMessage()
    {
        
        return "Resource can't be found.";
        
    }
    
}