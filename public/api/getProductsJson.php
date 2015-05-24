<?php
require_once '../../includes/inc.autoload.php';
$list = new ProductList();
$json_list = array();



foreach ($list as $product){
    /*
    * Usually this would be more straight-forward, 
    * but because Product objects have protected properties, 
    * we have to create new objects
    *  ... maybe ProductList should use generic objects 
    *     instead of objects of the Product class??
    */
    $json_obj = new stdClass();
    $json_obj->id = $product->getId();
    $json_obj->product_num = $product->getProductNum();
    $json_obj->product_name = $product->getProductName();
    $json_obj->price = $product->getPrice();
    //echo json_encode($json_obj);
    $json_list[] = $json_obj;
}
echo json_encode($json_list);