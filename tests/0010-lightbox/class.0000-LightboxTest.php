<?php

use MomaSDK\Lightbox;
use MomaSDK\MomaPIX;
use MomaSDK\Session;
use MomaSDK\MomaUTIL;

use PHPUnit\Framework\TestCase;
use MomaSDK\Exceptions\ResourceNotFoundException;
use MomaSDK\Lightboxes;

require 'vendor/autoload.php';

class LightboxTest extends TestCase
{
   // Useful to store lightbox id in order to delete previously created ones and leaving the testme installation as it was at first setup
   protected static $_id;
   protected static $_id2;
    
   public function testCreateNewLightbox()
   {
        MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
        MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
        
        $session    =   new Session();
        $session    ->  connect("client1", "client1");
        
        $lightbox   =   Lightbox::create();
        
        self::$_id  =   $lightbox->getId();
        
        $this->assertInternalType("int", $lightbox->getId());

        $lightbox_using_session = Lightbox::create($session);
        self::$_id2 =   $lightbox_using_session->getId();
        $this->assertInternalType("int", $lightbox_using_session->getId());
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
       
       $lightbox_using_session = Lightbox::retrieve(self::$_id2, $session);
       $this->assertEquals(self::$_id2, $lightbox_using_session->getId());
   }
   
   /**
     * @expectedException   \MomaSDK\Exceptions\ResourceNotFoundException
     * 
     */
   public function testRetrieveNonExistingLightbox()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve("1000");
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

       $lightbox_using_session   =   Lightbox::retrieve(self::$_id2, $session);
       $lightbox_using_session   ->  setDescription("Nuova descrizione 2");
       $lightbox_using_session   ->  update();
       
       $this->assertEquals($lightbox_using_session->getDescription(),"Nuova descrizione 2");
   }
   
   public function testSetLightboxSubjectDate()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  setSubjectDate("2020-02-02");
       $lightbox   ->  update();
       
       $this->assertEquals($lightbox->getSubjectDate(),"2020-02-02");
       
   }
   
   public function testSetLightboxCategory()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  setCategory("CRO");
       $lightbox   ->  update();
       
       $this->assertEquals($lightbox->getCategory(),"CRO");
       
   }
   
   public function testSetLightboxText()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  setText("This is a test text");
       $lightbox   ->  update();
       
       $this->assertEquals($lightbox->getText(),"This is a test text");
       
   }
   
   public function testLightboxAddItem()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  addItem(2241028);
       $lightbox   ->  update();
       
       $this->assertTrue($lightbox->hasItem(2241028));
       
   }
   
   public function testLightboxRemoveItem()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  removeItem(2241028);
       $lightbox   ->  update();
       
       $this->assertFalse($lightbox->hasItem(2241028));
       
   }
   
   public function testLightboxAddMultipleItems()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  addItems(array(2192360,2241028,2239674,2239642,2239566,2239457));
       $lightbox   ->  update();
       
       $this->assertTrue($lightbox->hasItem(2239674));
       
   }
   
   public function testLightboxRemoveMultipleItems()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  removeItems(array(2192360,2241028,2239674,2239642,2239566,2239457));
       $lightbox   ->  update();
       
       $this->assertFalse($lightbox->hasItem(2239674));
       
   }
   
   public function testEmptyLightbox()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox   ->  addItems(array(2192360,2241028,2239674,2239642,2239566,2239457));
       $lightbox   ->  empty();
       $lightbox   ->  update();
       
       $this->assertTrue($lightbox->isEmpty());
       
   }
   
   /**
    * 
    * @expectedException   \MomaSDK\Exceptions\ResourceNotFoundException
    * 
    * */
   public function testDeleteLightbox()
   {
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       $lightbox   =   Lightbox::retrieve(self::$_id);
       $lightbox->delete(self::$_id);

       $lightbox   =   Lightbox::retrieve(self::$_id);
   }

   /**
    * 
    * @expectedException   \MomaSDK\Exceptions\ResourceNotFoundException
    * 
    * */
   public function testDeleteLightboxUsingSession()
   {
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       $lightbox_using_session = Lightbox::retrieve(self::$_id2, $session);
       $lightbox_using_session->delete();

       $lightbox_using_session   =   Lightbox::retrieve(self::$_id2);
   }
   
   public function testGetAllLightboxes()
   {
       
       /** Setting environment variables */
       MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
       MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
       
       /** Logging in as a test client */
       $session    =   new Session();
       $session    ->  connect("client1", "client1");
       
       /** Creating a new lightbox*/
       $lightboxes = Lightboxes::getAllLightboxes();
       $this->assertEquals(count($lightboxes), 11);
       
       $lightboxes_using_session = Lightboxes::getAllLightboxes($session);
       $this->assertEquals(count($lightboxes_using_session), 11);
   }
   
}