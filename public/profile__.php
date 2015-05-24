<?php
/*
 * Show individual records of various class types -- works for customer and product
 * 
 */
session_start();
require_once '../includes/inc.autoload.php';
require_once '../classes/ProfileInterface.php';


$error_msg = '';
$content = '';
if (ctype_alnum($_GET['type'])){
   $type = $_GET['type'];
}
$id = (int)$_GET['id'];
$valid = 0;
$cached_types = array('product'); //types of objects that are cached.
$is_cached = 0;
$data_array = 0;
if ($type && $id){
    //Check that the type is valid:
    //It must exist and be an instance of DBObject Base *and* implement ProfileInterface
    if (class_exists($type,true)){
        //check if there's a cache for this page
        /*if (in_array($type, $cached_types)){
            $cache = new Cache($id,$type);
            if ($data_array = $cache->getCache()){
               $is_cached = 1; 
               $valid = 1;
            }
        }*/
        if (!$is_cached){
           $obj = new $type($id);
            if (is_subclass_of($obj, 'DBObjectBase')){
                if ($obj instanceof ProfileInterface){
                    $valid = 1;
                }
            } 
        }
        
        
    }   
    if (!$valid){
        $error_msg = 'Not a valid record';
    } else {
        if (!$data_array){ //if the data has not been pulled from the cache
            $data_array = $obj->getProfileData();
            //cache the data
            //$cache = new Cache($id,$type);
            //$cache->setCache($data_array);
        }
        
        $content = '';
        foreach ($data_array as $field=>$value){
            if ($field == 'title'){
                $title = $value;
            } else {
                $content .= "<p>$field: $value</p>";
            }   
        }
        if ($type == 'product'){
            //display 'Add to Cart' button
            $form = new Form();
            $form->addInput('pid','',$id,'hidden');
            $form->addInput('qty','Quantity',1,'text',2); 
            $form->addInput('submit',null,'Add to Cart','submit');
            $form->setAction('cart-add.php');
            $content .= $form->render();
            $content .= '<p><br/><a href="index.php">Back to shopping</a></p>';
        }
        if ($type == 'customer'){
            //display list of addresses
            $addresses = new AddressList($id);
            if (!empty($addresses)){
                $content .= '<h3>Addresses</h3>';
                foreach ($addresses as $a){
                    $content .= '<div class="cust-address">';
                    $content .= $a->getFullAddress();
                    $content .= '</div>';
                }
            }
        }
    }
} else {
    $error_msg = 'Not enough info given to retreive a record.';
}

//display
require_once 'template.php';

?>
