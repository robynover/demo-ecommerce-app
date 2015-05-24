<?php
ini_set('display_errors',1);
/*
 * add item/s to cart
 */

$title = 'Cart';
$error_msg = '';
$content = '';
if (isset($_GET['pid'])){
    $ref = 'ref=cart&n='.(int)$_GET['pid'];
    require_once '../includes/inc.checkLogin.php';
    //get product id & make sure there's a customer id in the session
    if ((int)$_GET['pid'] && (int)$_SESSION['cid']){
       
        //remove product from the session cart
        $pid = (int)$_GET['pid'];
       
        
        if (array_key_exists($pid,$_SESSION['cart'])!== false){
            unset($_SESSION['cart'][$pid]);            
            //Re-index array so that the cart will be properly formatted for an SQL query later
            //$_SESSION['cart'] = array_values($_SESSION['cart']);
            //$content = 'Removed from cart';
            header('Location:view-cart.php?remove=1');
            exit;
        }
        
    } else {
        $error_msg .= 'Missing required info';
    }
    
} else {
    $error_msg = 'Missing product id';
}
require_once 'template.php';
