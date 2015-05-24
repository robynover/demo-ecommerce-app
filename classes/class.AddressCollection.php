<?php
class AddressCollection extends Collection{
    protected $classname = 'Address';
    protected $tablename = 'addresses';
    
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
            $this->items = $stmt->fetchAll();
        }
    }
}
