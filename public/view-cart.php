<?php
ini_set('display_errors',1);
require_once '../includes/inc.autoload.php';
$title = 'Your Cart';
$error_msg = '';
$content = '';
$fetch = 0;


//check for info messages
if (isset($_GET['add']) && $_GET['add']==1){ //checking isset() here to prevent "Undefined index" notice
    $info_msg = 'Added product to cart.';
}
if (isset($_GET['remove']) && $_GET['remove']==1){
    $info_msg = 'Removed product from cart.';
}


require_once '../includes/inc.checkLogin.php';
$customer_id = (int)$_SESSION['cid'];

//if there's a cart in the session, use that.
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
    $cart = new Cart($customer_id,$_SESSION['cart']);
    $fetch = 1;
    
} else {
    //if there's no cart in the session, find cart stored in DB, based on customer id
    //$cart = new Cart($customer_id);
    //save the current cart to the database
    $content .= '<p>No items in your cart yet.</p>';
    $content .= '<p>Want to <a href="index.php">shop</a>?</p>';
}

//if there are items in the cart, we can fetch them now.
if($fetch){
    //set up some content for the token:
    $token_str = '';
    
    //list items in cart
    foreach($cart as $product){
        $content.='<div class="cart-item">';
        $content.= $product->product_name;
        $content.= ' $'.$product->price;
        $pid = $product->id;
        $quantity = $cart->getProductQuantity($product->id);
        $content.= ' x '.$quantity.'= $'.$product->price * $quantity;;
        $content.= " | <a href='profile.php?type=product&id=$pid'>view</a> | ";
        $content.= "<a href='cart-remove.php?pid=$pid'>remove</a>";

        $content.='</div>';
        //add to token string
        $token_str .= $product->price;
    }
    
        //show total price
        $content.= '<p class="total">TOTAL: ';
        $content.= $cart->getFormattedTotal();

        $form = new Form();
        $form->setAction('checkout.php');
        $token = md5(time().$token_str.$customer_id); //also add a salt? from file or db
        $_SESSION['form_token'] = $token;
        $form->addInput('token','',$token,'hidden');
        $form->addInput('submit',null,'Check out','submit');
        $content.= $form->render();
        $content.= '<p class="back"><a href="index.php">Back to shopping</a></p>';
    //}
}



require_once 'template.php';





