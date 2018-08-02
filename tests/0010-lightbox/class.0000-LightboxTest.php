<?php

use MomaSDK\Lightbox;
use MomaSDK\MomaPIX;
use MomaSDK\Session;

use PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';

class LightboxTest extends TestCase
{
    
    public function testCreateNewLightbox()
    {
        
        MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
        MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
        
        $session    =   new Session();
        
        $session    ->  connect("client1", "client1");
        
        error_log("\n\n\n\Creating new lightbox: " . print_r($lightbox,true)."\n\n\n",3,"mylog.log");
        
        $lightbox   =   Lightbox::create();
        
        error_log("\nFound lightbox: " . print_r($lightbox,true),3,"mylog.log");
        
        $this->assertEquals(true, Session::isLoggedIn());
        
    }
    
}