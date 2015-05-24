<?php
class Order extends DBObjectBase{
    protected $id;
    protected $customer_id;
    protected $order_date;
    protected $address_id;
    
    protected $total_price;
    protected $item_count;
    protected $products; //ProductQuanityCollection
    protected $customer; //Customer object
    
    protected $_tablename = 'orders';
    protected $_db_fields = array('id','customer_id','order_date','address_id');
    
    public function __construct($id = null) {
        if ((int)$id){
            $this->id = $id;
            //get product details from db
            $data_row = $this->getRowById($this->id);
            //populate properties
            $this->setProperties($data_row);
            
        }   
    }
    
    public function getProducts(){
        //only load from the DB if products is not already populated
        if (!isset($this->products)){
           $this->loadProducts(); 
        }
        return $this->products;
    }
    
    public function setProducts(ProductQuantityCollection $cart){
        $this->products = $cart;
        
    }
    
    
    public function getCustomer(){
        if (!isset($this->customer)){
           $this->loadCustomer(); 
        }
        return $this->customer;
    }
    
    protected function loadProducts(){
        $this->products = new ProductQuantityCollection($this->id); 
    }
    
    protected function loadCustomer(){
        if ($this->customer_id > 0){
            $this->customer = new Customer($this->customer);
            return true;
        } else {
            return false;
        }
    }
    
    public function save(){
        try{
            //use parent save function to store into the orders table, then save to order_product table
            if (parent::save()){
                if (!$this->_db){
                    $this->_db = DB::getInstance();
                }
                //First delete the current order_product records 
                // .. . it's the only way to account for a product being removed from the order
                $delete_query = "DELETE FROM order_product WHERE order_id = ?";
                $stmt = $this->_db->prepare($delete_query);
                if ($stmt->execute(array($this->id))){
                    $params = array();
                    $values = '';
                    $total_products = count($this->products);
                    $count = 0;
                    foreach($this->products as $product){
                        $count++;
                        //build the query
                        $values .= '(?,?,?)'; //set up the prepared statement placeholders
                        if ($count !== $total_products){
                            $values.=',';
                        }
                        $params[] = $this->id; //order id
                        $params[] = $product->id;//product id
                        $params[] = $this->products->getProductQuantity($product->id);//quanity

                    } 
                    $query = 'INSERT INTO order_product (order_id,product_id,quantity) VALUES '.$values;
                    $stmt = $this->_db->prepare($query);
                    if ($stmt->execute($params)){
                        return true;
                    } else {
                        throw new PDOException('could not complete order');
                    }
                    
                }
                   
            }  else {
                echo 'parent save failed';
                return false;
            }  
        } catch(PDOException $e){
            //$this->error_msg = $e->getMessage(); //for debugging
            $this->error_msg = 'There was a database problem.';
            return false;
        }
    }
    
    
    protected function validateData(){
        if (!is_numeric($this->customer_id) || !is_numeric($this->address_id)){
            return false;
        }
        return true;
    }
    
    public function getFormattedDate(){
        return date('m-d-Y',$this->order_date);
    }
}