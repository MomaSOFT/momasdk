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
     * Creates a new empty lightbox with default parameters. The lightbox will belong to logged in user.
     *
     * @return Lightbox The lightbox itself
     *
     * */
    public static function create($endpoint = "/rest/lightbox") : Lightbox
    {
        
        $json   =  self::fixJSON(parent::create($endpoint));
        
        $jd     =  new JsonDecoder(false,true);
        
        return $jd->decode($json, Lightbox::class);
        
    }
    
    /**
     *
     * Retrieves a lightbox with a certain id.
     *
     * @return Lightbox. The lightbox with that specific id
     *
     * */
    public static function retrieve($id,$endpoint = '/rest/lightbox/') : Lightbox
    {
        
        $json   =  self::fixJSON(parent::retrieve($id,$endpoint));
        
        $jd     =  new JsonDecoder(false,true);
        
        return $jd->decode($json, Lightbox::class);
        
    }
    
    /**
     * Saves all changes made to a given lightbox ( made by the use of setters methods ) or saves a new one if no properties are provided.
     *
     * @return Lightbox. The lightbox itself ( with all attributes saved to the db ).
     *
     **/
    public function update($endpoint = "/rest/lightbox") : bool
    {
        
        $jsonLightbox   =   parent::update($endpoint);
        
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
    public static function delete($id,$endpoint = "/rest/lightbox") : bool
    {
        
        parent::delete($id, $endpoint);
        
        return true;
        
    }
    
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
        
        if (!in_array("attributes", $this->changedProperties))  $this->changedProperties[]  =   "attributes";
        
        return $this;
        
    }
    
    /**
     *
     * Return lightbox's current description
     *
     * @return description. The lightbox description
     *
     * */
    public function getDescription() : string
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
        
        if (!in_array("attributes", $this->changedProperties))  $this->changedProperties[]  =   "attributes";
        
        return $this;
        
    }
    
    /**
     *
     * Returns the lightbox date.
     *
     * @return date. The lightbox date
     *
     **/
    public function getSubjectDate() : string
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
        
        if (!in_array("attributes", $this->changedProperties))  $this->changedProperties[]  =   "attributes";
        
        return $this;
        
    }
    
    /**
     *
     * Returns the lightbox category.
     *
     * @return category. The lightbox category
     *
     **/
    public function getCategory() : string
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
        
        if (!in_array("attributes", $this->changedProperties))  $this->changedProperties[]  =   "attributes";
        
        return $this;
        
    }
    
    /**
     *
     * Returns the text linked to current lightbox.
     *
     * @return text. The lightbox text
     *
     **/
    public function getText() : string
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
        
        array_push($this->relationships['items']['data'],array("type"  => "item", "id"    =>  $itemId));
        
        if (!in_array("relationships", $this->changedProperties))  $this->changedProperties[]  =   "relationships";
        
        return $this;
        
    }
    
    /**
     *
     * Adds a set of items to the lightbox. Remember to call the save method to make changes persistent.
     *
     * @param  array . An array containing a list of items to be added.
     * @return lightbox. The lightbox itself
     *
     **/
    public function addItems($itemsToBeAdded) : Lightbox
    {
        
        foreach ( $itemsToBeAdded as $item )
        {
            
            array_push($this->relationships['items']['data'], array("type"  => "item", "id"    =>  $item));
            
        }
        
        if (!in_array("relationships", $this->changedProperties))  $this->changedProperties[]  =   "relationships";
        
        return $this;
        
    }
    
    /**
     *
     * Removes given item from the lightbox. Remember to call the save method to make changes persistent.
     *
     * @param  item. The item id
     * @return lightbox. The lightbox itself
     *
     **/
    public function removeItem($itemId) : Lightbox
    {
        
        $this->relationships['items']['data'] = MomaUTIL::removeElementWithValue($this->relationships['items']['data'], "id", $itemId);
        if (!in_array("relationships", $this->changedProperties))  $this->changedProperties[]  =   "relationships";
        
        return $this;
        
    }
    
    /**
     * 
     * Removes given items set from the lightbox. Remember to call the save method to make changes persistent.
     * 
     * */
    public function removeItems($itemsToBeRemoved) : Lightbox
    {
        
        foreach ( $itemsToBeRemoved as $item )
        {
            
            $this->relationships['items']['data'] = MomaUTIL::removeElementWithValue($this->relationships['items']['data'], "id", $item);
            
        }
        
        if (!in_array("relationships", $this->changedProperties))  $this->changedProperties[]  =   "relationships";
        
        return $this;
        
    }
    
    public function hasItem($itemId) : bool
    {
        
        return MomaUTIL::searchElementWithValue($this->relationships['items']['data'], "id", $itemId);
        
    }
    
    /**
     *
     * Returns an array listing all items in the current lightbox.
     *
     * @return array The array containing all items in the current lightbox
     *
     * */
    public function getItems()
    {
        
        return $this->relationships['items']['data'];
        
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
    
    /**
     * 
     * Empties a lightbox
     * 
     * */
    public function empty() : Lightbox
    {
        
        $this->relationships['items']['data']   =   array();
        
        if (!in_array("relationships", $this->changedProperties))  $this->changedProperties[]  =   "relationships";
        
        return $this;
        
    }
    
    /**
     *
     * Returns wether a lightbox has no items in it or not.
     *
     * */
    public function isEmpty() : bool
    {
        
        return (count($this->relationships['items']['data']) == 0) ? true : false;
        
    }
    
    
    /**
     *
     * Adjust the JSON representation of the entity according to Lightbox class neeeds.
     *
     * @param  String $json.  An entity representation in JSON format.
     * @return String $json.  A fixed entity representation in JSON format.
     *
     * */
    protected static function fixJSON($json) : String
    {
        
        $array      =   json_decode($json,true);
        
        $lightbox   =   array();
        
        $lightbox['meta']               =   $array['meta'];
        $lightbox['links']              =   $array['links'];
        $lightbox['included']           =   $array['included'];
        $lightbox['attributes']         =   $array['data']['attributes'];
        $lightbox['relationships']      =   $array['data']['relationships'];
        $lightbox['changedProperties']  =   array();
        
        return json_encode($lightbox);
        
    }
    
}