<?php

/**
 * This class helps the developers in managing a lightbox collection.
 * Using this class you can create, delete and change an existing lightbox's properties 
 *
 * @package    MomaSDK
 * @subpackage
 * @license
 * @author     Stefano Lettica <s.lettica@momapix.com>
 *
 **/

namespace MomaSDK;

use Karriere\JsonDecoder\JsonDecoder;

class Lightbox extends MomaRestORM  {
    
    /**
     * 
     * Sets the lightbox description with given string. All changes to lightbox's properties need to be saved calling the save method.
     *
     * @param  description. The lightbox descripton
     * @return lightbox. The lightbox itself
     *
     **/
    public function setDescription($description) : Lightbox
    {
        
        $this->attributes['description']    =   $description;
        $this->attributes['lastpdate_date'] =   date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    /**
     * 
     * Return lightbox's current description
     * 
     * @return description. The lightbox description
     * 
     * */
    public function getDescription() : String
    {
        
        return $this->attributes['description'];
        
    }
    
    /**
     *
     * Sets the lightbox date with given date. All changes to lightbox's properties need to be saved calling the save method.
     *
     * @param  date. The lightbox date
     * @return lightbox. The lightbox itself
     *
     **/
    public function setSubjectDate($date) : Lightbox
    {
        
        // Verificare il formato della data ?
        $this->attributes['subject_date']   =   $date;
        $this->attributes['lastpdate_date'] =   date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    /**
     *
     * Returns the lightbox date.
     *
     * @return date. The lightbox date
     *
     **/
    public function getSubjectDate($date) : Lightbox
    {
        
        return $this->attributes['subject_date'];
        
    }
    
    /**
     *
     * Sets the lightbox category with the given one. All changes to lightbox's properties need to be saved calling the save method.
     *
     * @param  category. The lightbox date
     * @return lightbox. The lightbox itself
     *
     **/
    public function setCategory($category) : Lightbox
    {
        
        // Verificare se la categoria è di 3 lettere maiuscole ?
        $this->attributes['category']       =   $category;
        $this->attributes['lastpdate_date'] =   date('Y-m-d h:i:s');
            
        return $this;
        
    }
    
    /**
     *
     * Returns the lightbox category.
     *
     * @return category. The lightbox category
     *
     **/
    public function getCategory($category) : Lightbox
    {
        
        return $this->attributes['category'];
        
    }
    
    /**
     *
     * Sets the lightbox text with the given one. All changes to lightbox's properties need to be saved calling the save method.
     *
     * @param  text. The lightbox text
     * @return lightbox. The lightbox itself
     *
     **/
    public function setText($text) : Lightbox
    {
        
        $this->attributes['text']           =   $text;
        $this->attributes['lastpdate_date'] =   date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    /**
     *
     * Returns the lightbox text.
     *
     * @return text. The lightbox text
     *
     **/
    public function getText($text) : Lightbox
    {
        
        return $this->attributes['text'];
        
    }
    
    /**
     *
     * Adds given item to the lightbox. Remember to call the save method to make changes persistent.
     *
     * @param  item. The item id
     * @return lightbox. The lightbox itself
     *
     **/
    public function addItem($itemId) : Lightbox
    {
        
        $this->relationships['data']['items']['data'] = array(
            
            "type"  => "item",
            "id"    =>  $itemId
            
        );
        
        $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    /**
     *
     * Removes given item to the lightbox. Remember to call the save method to make changes persistent.
     *
     * @param  item. The item id
     * @return lightbox. The lightbox itself
     *
     **/
    public function removeItem($itemId) : Lightbox
    {
        
//         $this->relationships['data']['items'] = array (
//             "type"  => "item",
//             "id"    =>  $itemId
//         );

        $this->relationships['items'];
        $this->attributes['lastpdate_date'] = date('Y-m-d h:i:s');
        
        return $this;
        
    }
    
    /**
     *
     * Returns the lightbox id. 
     *
     * @return lightbox id. The lightbox id.
     *
     **/
    public function getId() : int
    {
        
        return intval($this->attributes['id']);
        
    }
    
    protected static function fixJSON($json) : String
    {
        
        $array      =   json_decode($json,true);
        
        $lightbox   =   array();
        
        $lightbox['meta']           =   $array['meta'];
        $lightbox['links']          =   $array['links'];
        $lightbox['included']       =   $array['included'];
        $lightbox['attributes']     =   $array['data']['attributes'];
        $lightbox['relationships']  =   $array['data']['relationships'];
        
        return json_encode($lightbox);
        
    }
    
    
    
    
    
    
    
    
    public static function create($endpoint = "/rest/lightbox") : Lightbox
    {
        
        $json        =  self::fixJSON(parent::create($endpoint));
        
        $jsonDecoder =  new JsonDecoder(false,true);
        
        return $jsonDecoder->decode($json, Lightbox::class);
        
    }
    
    public static function retrieve($id,$endpoint = '/rest/lightbox/') : Lightbox
    {
        
        $json        =  self::fixJSON(parent::retrieve($id,$endpoint));
        
        $jsonDecoder =  new JsonDecoder(false,true);
        
        return $jsonDecoder->decode($json, Lightbox::class);
        
    }
    
    /**
     * Saves all changes made to a given lightbox o saves a new one if no properties are provided.
     *
     * @return lightbox The lightbox itself ( with all attributes saved to the db ).
     *
     **/
    public function update($id = null,$endpoint = "/rest/lightbox/") : bool
    {
        
        if ($id == null) $id = $this->attributes['id'];
        
        $jsonLightbox   =   parent::update($id,$endpoint);
        
        return true;
        
    }
    
    /**
     *
     * Deletes a lightbox with a certain id
     *
     * @param id. The id of the lightbox
     * @return true | false. Tells wether the operation was successfull or not.
     *
     **/
    public static function delete($id,$endpoint = "/rest/lightbox/") : bool
    {
        
        parent::delete($id, $endpoint);
        
        return true;
        
    }
    
}