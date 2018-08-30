<?php
use PHPUnit\Framework\TestCase;
use MomaSDK\MomaPIX;
use MomaSDK\Session;
use MomaSDK\MomaUTIL;

require 'vendor/autoload.php';

class SessionTest extends TestCase
{

    /**
     *
     * @expectedException   \MomaSDK\Exceptions\InvalidApiKeyException
     *
     * */
    public function testSessionTestInvalidApiKey()
    {
        
        MomaPIX::setApiKey("Invalid Api Key");
        MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
        
        $session = new Session();
        
        // Inserire asserzioni su codici errore
        //$this->assertEquals('',$session->getBearerToken());
        
    }
    
    public function testSessionTestValidApiKey()
    {
        
        MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
        MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
        
        $session = new Session();
        
        $this->assertNotEmpty($session->getBearerToken());
        
    }
    
    public function testSessionLoginWithWrongCredentials()
    {
        
        MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
        MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
        
        $session    =   new Session();
        
        try {
        
            $session    ->  connect("client1", "*****");
            $response   =   $session->getResponse();
            
        } catch (Exception $e) {
            
            MomaUTIL::log($e->getMessage());
            
        }
        
        $this->assertFalse($session::isLoggedIn());
        
    }
    
    public function testSessionLoginWithRightCredentials()
    {
        
        MomaPIX::setApiKey("1n29BMfN7EtaPqTzO6D9RIqryZSSiLsJ");
        MomaPIX::setApiURL("http://sandbox.my.momapix.com/testme");
        
        $session    =   new Session();
        
        $session    ->  connect("client1", "client1");
        
        $response   =   $session->getResponse();
        
        $this->assertTrue($session::isLoggedIn());
        
    }
    
}
    