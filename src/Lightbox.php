<?php
namespace MomaSDK;

use Karriere\JsonDecoder\JsonDecoder;

class Lightbox extends MomaRestORM  {
    
    public function __construct()
    {
     
        // ID ???
        $this->endpoint     = "/rest/lightbox";
        // Inizializzo solo variabili, nessuna chiamata a servizi REST
        $this->attributes   =   array (
            
//             "description"   => $desc,
//             "subject_date"  => $date,
//             "type"          => "lightbox"
            
        );
        
    }
    
    public static function create() : Lightbox
    {
        
        self::$endpoint = "/rest/lightbox";
        $jsonLightbox = parent::create();
        
        error_log("\n\nLightbox class: ". Lightbox::class,3,"mylog.log");
        error_log("\n\njson Lightbox: ". $jsonLightbox,3,"mylog.log");
        
        $jd       = new JsonDecoder();
        $lightbox = $jd->decode($jsonLightbox->data, Lightbox::class);
        
        return $lightbox;
        
    }
    
    public static function retrieve($id) :Lightbox
    {
        
        error_log("=== retrieve method ===",3,"mylog.log");
        // USO: Lightbox::getLightbox($id)
        // GET http://sandbox.momapix.com/testme/rest/lightbox/<lightbox identifier>
        self::$endpoint     =   "/rest/lightbox/";
        
        $jsonData           =   parent::retrieve($endpoint);
        $jsonDecoder        =   new JsonDecoder();
        $lightbox           =   $jsonDecoder->decode($jsonData, Lightbox::class);
        
        return $lightbox;
        
    }
    
    public static function delete($id)
    {
        
        $this->endpoint     = "/rest/lightbox/$id";
        
    }
    
    public function setDescription($descr) : Lightbox
    {
        
        $this->attributes['description']    = $descr;
        $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    public function setSubjectDate($date) : Lightbox
    {
        
        $this->attributes['subject_date']   = $descr;
        $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    public function setCategory($category) : Lightbox
    {
        
        if (1 /** 3 uppercase letter code */)
        {
            
            
            
        }
        else
        {
            
            $this->attributes['category']       = $category;
            $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
            
        }
        return $this;
        
    }
    
    public function setText($text) : Lightbox
    {
        
        $this->attributes['text'] = $text;
        $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    public function addItem($itemId)
    {
        
        $this->relationships['data']['items'][] = array(
            
            "type"  => "item",
            "id"    =>  $itemId
            
        );
        
        $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    public function removeItem($itemId)
    {
        
//         $this->relationships['data']['items'] = array (
            
//             "type"  => "item",
//             "id"    =>  $itemId
            
//         );

        $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
}