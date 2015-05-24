<?php
abstract class Collection implements Iterator,Countable{
    protected $classname = 'StdClass'; //the class of object this collection holds -- defaults to generic class
    protected $tablename;
    protected $items = array(); 
    protected $position = 0; //for the iterator
    protected $_db;
    protected $limit = 25;
    
    public function __construct($limit=null,$items = null) {
        if ((int)$limit){
            $this->limit = (int)$limit;
        }
        //if an array is given, use it to set the collection array
        //if not, get info from DB
        if (!$items){
            if (isset($this->tablename)){
                $this->fetchFromDB(); 
            }
        } else {
            if (is_array($items)){
               $this->items = $items; 
            }
        }
    }  
    
    public function setLimit($limit){
        if (is_int($limit)){
            $this->limit = $limit;
        }
    }
    
    protected function fetchFromDB(){
        if (!$this->_db){
             $this->_db = DB::getInstance();
        }
        $query = sprintf('SELECT * FROM %s',$this->tablename);
        if ($this->limit){
            $query .= sprintf(' LIMIT %d',$this->limit);
        }
        
        //prepare
        $stmt = $this->_db->prepare($query);
        if (!$stmt->execute()){
            throw new PDOException('Could not fetch order');
        }
        //set fetch mode to return objects of the class
        $stmt->setFetchMode(PDO::FETCH_CLASS,$this->classname); 
        $this->items = $stmt->fetchAll();    
    }
    
    
    //add an item to the collection
    public function add($item){
        if ($item instanceof $this->classname){
            $this->items[] = $item;
        }
    }
    /**
     * Allows setting the items array manually, instead of getting results from the DB
     * @param array $obj_array Array of objects
     */
    public function setList(Array $obj_array){
        if (is_array($obj_array) && !empty($obj_array)){
            $this->items = $obj_array;
        }
    }
    /**
     * Remove an item from the collection
     * @param Object $item 
     */
    public function remove($item){
        $index = array_search($item, $this->items);
        if ($index !== false){
            unset($this->items[$index]);
        }   
    }
    
    
    
     //These 5 functions are required by the Iterator interface:  
    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->items[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->items[$this->position]);
    }
    
    //The count function is required by the Countable interface
    public function count() {
        return count($this->items);
    }
    
}