<?php
require_once 'ProfileInterface.php';
/**
 * Represents a product in the DB
 *
 * 
 * @author robyn overstreet <robynover@gmail.com>
 */

class Product extends DBObjectBase implements ProfileInterface{ 
    protected $id,$product_num,$product_name,$price,$quantity_on_hand,$description,$img_src;
    protected $_db_fields = array('id','product_num','product_name','price','quantity_on_hand','description','img_src');
    protected $_tablename = 'products'; 
    
    public function __construct($id=null) {
        if ((int)$id){
            $this->id = $id;
            //get product details from db
            $data_row = $this->getRowById($this->id);
            //populate properties
            $this->setProperties($data_row);
        }
    }
    /**
     * Defines which fields will be included for display in profile.php 
     * Object properties are the titles that should be displayed on the front end
     *
     * @return Object
     */
    public function getProfileData(){
        $data = array(
            '__img'=>$this->img_src,
            'Product Number'=>$this->product_num,
            'Description'=>$this->description,
            'Price'=>'$'.$this->getFormattedPrice(),
            '__title'=>$this->product_name
        );
        return $data;
    }
    /**
     * Returns the price of the product, with the object to format it for display
     * @param bool $format Whether or not the price should be formatted
     * @return string  
     */
    public function getFormattedPrice(){
        return number_format($this->price,2); //show the price with 2 decimal points, even if it's an integer
        
    }
    protected function validateData(){
    }
}