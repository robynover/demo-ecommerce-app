<?php
require_once 'class.DBObjectBase.php';

class Address extends DBObjectBase{ 
    protected $id,$customer_id,$address1,$address2,$city,$state,$zip,$country,$phone;
    protected $_tablename = 'addresses';
    protected $_db_fields = array(
                            'id',
                            'customer_id',
                            'address1',
                            'address2',
                            'city',
                            'state',
                            'zip',
                            'country',
                            'phone'
                          );
    
    public function __construct($id=null) {
        if ((int)$id){
            $this->id = $id;
            //get product details from db
            $data_row = $this->getRowById($this->id);
            //populate properties
            $this->setProperties($data_row);
        }
    }
    
    public function validateStreet($text,$line2=false){
        if (!$line2 && strlen($text)<5){
            return false;
        }
        //allowed characters in street addresses
        $pattern = '#\A[a-zA-Z0-9\s\'-\.\#]+\Z#'; 
        if (preg_match($pattern, trim($text))){
            return true;
        }
        return false;
    }
    public function validateZip($zip){
        
    }
    public function getFullAddress(){
        $html = $this->address1."<br/>";
        if (strlen(trim($this->address2)) > 0){
            $html .= $this->address2."<br/>";
        }
        $html .= "{$this->city}, {$this->state} {$this->zip}";
        return $html;
    }
    public function validateData() {
        if (!$this->validateStreet($this->address1) || !$this->validateStreet($this->address2,true)){
            return false;
        }
        
        return true;
        
        //many more validation checks one could do here
        /*
         * !$this->city ||
           !$this->state ||
            
         */
    }
    
}