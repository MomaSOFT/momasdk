<?php
namespace MomaSDK\Transformers;

use MomaSDK\Lightbox;
use Karriere\JsonDecoder\ClassBindings;
use Karriere\JsonDecoder\Transformer;
use Karriere\JsonDecoder\Bindings\FieldBinding;

class LightboxTransformer implements Transformer 
{
    
    public function transforms()
    {
        
        return Lightbox::class;
        
    }

    public function register(ClassBindings $classBindings)
    {
        
        
    }

}