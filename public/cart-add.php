<?php
/*
 * add item/s to cart
 */

$title = 'Cart';
$error_msg = '';
$content = '';
$user_id_present = 0;
if (isset($_POST['submit'])){
        //check login
        require_once '../includes/inc.checkLogin.php';
        //if not logged in, the included file will exit the script
        $user_id_present = 1;
    
    if ($user_id_present){
        //get product id & make sure there's a customer id in the session
        if ((int)$_POST['pid'] && (int)$_SESSION['cid']){
            $qty = 1;
            $pid = (int)$_POST['pid'];
            if ((int)$_POST['qty']){
                $qty = (int)$_POST['qty'];
            }
            //do they have a cart?
            if (!isset($_SESSION['cart'])){
            //create a cart in the session
            $_SESSION['cart'] = array();   
            }

            //store the product & quantity in the session cart
            if (isset($_SESSION['cart'][$pid])){
                $_SESSION['cart'][$pid] += $qty;
            } else {
                $_SESSION['cart'][$pid] = $qty;  
            }

            header('Location:view-cart.php?add=1');
            exit;
        } 
    }
    
    
}
require_once 'template.php';
