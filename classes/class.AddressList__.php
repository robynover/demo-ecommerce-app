<?php
require_once 'class.DB.php';
require_once 'class.Address.php';

class AddressList implements Iterator{
    public $addresses = array(); 
    protected $_db;
    protected $position =0; //for the iterator
    
    
    public function __construct($cust_id){
        if ((int)$cust_id){
            if (!$this->_db){
                $this->_db = DB::getInstance();
            }
            $query = 'SELECT * FROM addresses WHERE customer_id = ?';
            $stmt = $this->_db->prepare($query);
            $stmt->execute(array($cust_id));
            //set fetch mode to return address objects
            $stmt->setFetchMode(PDO::FETCH_CLASS,'Address'); 
            $this->addresses = $stmt->fetchAll();
        }
    }
    
    
    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->addresses[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->addresses[$this->position]);
    }
}