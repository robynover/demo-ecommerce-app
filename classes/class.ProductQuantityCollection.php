<?php
class ProductQuantityCollection extends Collection{
    protected $classname = 'Product';
    /**
     *
     * @var array Tracks the quantity of each product if needed (for cart and orders)
     */
    protected $quantity_product_map = array();
    
    public function __construct($order_id=null,$session_cart=null){
        //var_dump($session_cart);
        
        if ($order_id > 0){
            $rows = $this->fetchOrderProducts($order_id);
            
            foreach ($rows as $obj){
                //the objects fetched will technically be Product objects, but will also contain quantity info
                $qty = $obj->quantity;
                $this->add($obj,$qty);
            }
        } else if (is_array($session_cart)){
           
           //the index numbers of the session array are the product ids
            $product_ids = array_keys($session_cart); 
            $products_array = $this->fetchByIdArray($product_ids);
            //add products objects with quanitites
            foreach ($products_array as $p){
                $qty = $session_cart[$p->id];
                $this->add($p,$qty);
                
            } 
        } 
    }
    
    protected function fetchOrderProducts($oid){
        if (!$this->_db){
            $this->_db = DB::getInstance();
        }
        $query = 'SELECT * FROM products LEFT JOIN order_product ON products.id = product_id WHERE order_id=?';
        $stmt = $this->_db->prepare($query);
        $stmt->execute(array($oid));
        $stmt->setFetchMode(PDO::FETCH_CLASS,'Product'); 
        //the objects fetched will technically be Product objects, but will also contain quantity info
        return $stmt->fetchAll();
        
    }
    
    protected function fetchByIdArray($product_ids){
        if (is_array($product_ids)){
            if (!$this->_db){
             $this->_db = DB::getInstance();
            }
            $params = substr(str_repeat("?,", count($product_ids)),0,-1);
            $query = sprintf('SELECT * FROM products WHERE id IN (%s)',$params);
            $stmt = $this->_db->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
            $stmt->execute($product_ids);
            return  $stmt->fetchAll(); 
        }
        return false;   
    }
    
    public function add($product,$qty){
        //if it's already in the collection, don't add it again, just update quantity
        if (!array_key_exists($product->id, $this->quantity_product_map)){
            parent::add($product);
        } 
        if (isset($this->quantity_product_map[$product->id])){
            $qty = $this->quantity_product_map[$product->id] + $qty; 
        }
        $this->quantity_product_map[$product->id] = $qty; 
    }
    
    public function remove($product) {
        parent::remove($product);
        //check if the item is in the quantity map, and remove if so
        if (array_key_exists($product->id, $this->quantity_product_map)){
            unset($this->quantity_product_map[$product->id]);
        }
        
    }
    public function getProductQuantity($pid){
         if (array_key_exists($pid, $this->quantity_product_map)){
            return $this->quantity_product_map[$pid];
         }
         return 0;
     }
    
    
}