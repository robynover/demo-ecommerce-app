<?php
class Cache{
    //protected $types = array('product'); //??
    
    protected $file_dir = '../cache';
    protected $id;
    protected $type;
    protected $filename;


    public function __construct($id,$type){
        if ((int)$id && ctype_alpha($type)){
            $this->id = $id;
            $this->type = $type;
        }
        
    }
    public function getCachedObj(){
        if ($this->cacheExists()){
            $serialized = file_get_contents($this->filename);
            return unserialize($serialized);
        }
    }
    
    
    public function setCache($obj){
        //double check that we have valid names for the file
        if (!isset($this->id) || !isset($this->type)){ return false;}
        //serialize the array into text
        $content = serialize($obj);
        if (!isset($this->filename)){
            $this->filename = $this->file_dir.'/'.$this->type.'_'.$this->id.'.txt';
        }
        if (file_put_contents($this->filename, $content) !== false){
            
            return true;
        }
        return false;
    }
    
    public function cacheExists(){
        $this->filename = $this->file_dir.'/'.$this->type.'_'.$this->id.'.txt';
        if (file_exists($this->filename)){
            return true;
        }
        return false;
    }
}
