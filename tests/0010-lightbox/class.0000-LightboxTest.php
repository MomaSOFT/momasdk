<?php

use MomaSDK\Lightbox;
use MomaSDK\MomaPIX;
use MomaSDK\Session;
use MomaSDK\MomaUTIL;

use PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';

class LightboxTest extends TestCase
{
   
    // Useful to store lightbox id in order to delete previously created ones and leaving the testme installation as it was at first setup
   public static $_id;
    
   public function testCreateNewLightbox()
   {
        
        MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
        MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
        
        $session    =   new Session();
        $session    ->  connect("client1", "client1");
        
        $lightbox   =   Lightbox::create();
        
        self::$_id  =   $lightbox->getId();
        
        $this->assertInternalType("int", $lightbox->getId());
        
   }
   
   public function testRetrieveLightbox()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       
       $this->assertEquals(self::$_id, $lightbox->getId());
       
   }
   
   public function testSetLightboxDescription()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  setDescription("Nuova descrizione");
       $lightbox   ->  update();
       
       $this->assertEquals($lightbox->getDescription(),"Nuova descrizione");
       
   }
    
   public function testDeleteLightbox()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       Lightbox::delete(self::$_id);
       
   }
   
}