<?php

class Cart extends ProductQuantityCollection{
    public $customer_id;
    public function __construct($customer_id,$session_cart = null){
        
        if ($customer_id > 0){
            $this->customer_id = $customer_id;
        }
        if (is_array($session_cart)){
            
            parent::__construct(null,$session_cart);
        }
       
    }
    /**
     * Loop through the items and get the total price
     * @return float 
     */
    public function calcTotal(){
         $total = 0;
         foreach($this->items as $product){     
             $total += $product->price * $this->getProductQuantity($product->id);
         }
         return $total;
     }
     public function getFormattedTotal(){
         $total = $this->calcTotal();
         $currency = '$';
         $total = number_format($total,2);
         return $currency.$total;
     }
     
     
     
}
