<?php
namespace MomaSDK;

use Karriere\JsonDecoder\JsonDecoder;

class Lightbox extends MomaRestORM  {
    
    public function __construct()
    {
     
        $this->endpoint     = "/rest/lightbox";
        
        parent::__construct();
        
    }
    
    public function save()
    {
        // Actual REST call
        $jsonLightbox   =   parent::save();
        // Fixing returned JSON according Lightbox's needs
        $fixedJson      =   $this->fixJSON($jsonLightbox);
        $decoded        =   json_decode($fixedJson,true);
        // Updating object's properties
        $this->links            =   $decoded['links'];
        $this->included         =   $decoded['included'];
        $this->attributes       =   $decoded['attributes'];
        $this->relationships    =   $decoded['relationships'];
    
        return $this;
        
    }
    
    public static function delete($id)
    {
        
        $this->endpoint     = "/rest/lightbox/$id";
        
    }
    
    public function setDescription($descr) : Lightbox
    {
        
        $this->attributes['description']    =   $descr;
        $this->attributes['lastpdate_date'] =   date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    public function setSubjectDate($date) : Lightbox
    {
        
        // Verificare il formato della data ?
        $this->attributes['subject_date']   =   $date;
        $this->attributes['lastpdate_date'] =   date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    public function setCategory($category) : Lightbox
    {
        
        // Verificare se la categoria è di 3 lettere maiuscole ?
        $this->attributes['category']       =   $category;
        $this->attributes['lastpdate_date'] =   date('Y-m-d h:i:s');
            
        return $this;
        
    }
    
    public function setText($text) : Lightbox
    {
        
        $this->attributes['text']           =   $text;
        $this->attributes['lastpdate_date'] =   date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    public function addItem($itemId)
    {
        
        $this->relationships['data']['items']['data'] = array(
            
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

        $this->relationships['items'];
        $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    public function getId() : int
    {
        
        return (int) $this->attributes['id'];
        
    }
    
    public static function fixJSON($json) : String
    {
        
        $array      =   json_decode($json,true);
        
        error_log("\n\nJson Lightbox: ".print_r($array,true)."\n\n",3,"mylog.log");
        
        $lightbox   =   array();
        
        $lightbox['meta']           =   $array['meta'];
        $lightbox['links']          =   $array['links'];
        $lightbox['included']       =   $array['included'];
        $lightbox['attributes']     =   $array['data']['attributes'];
        $lightbox['relationships']  =   $array['data']['relationships'];
        
        error_log("\n\nJson Lightbox after: ".print_r($lightbox,true)."\n\n",3,"mylog.log");
        
        return json_encode($lightbox);
        
    }
    
}