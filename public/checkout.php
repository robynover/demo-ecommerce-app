<?php
require_once '../includes/inc.autoload.php';

$title = 'Complete Your Order';
$error_msg = '';
$info_msg ='';
$content = '';
$valid = 0;
//check log-in
require_once '../includes/inc.checkLogin.php';

$customer_id = (int)$_SESSION['cid'];
if (!$customer_id){
    $error_msg = 'Invalid customer id';
    require_once 'template.php';
    exit;  
}

//create the cart from the session
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
    $cart = new Cart($customer_id,$_SESSION['cart']);
} else {
    $error_msg = 'Your cart is empty.';
}



if (strtolower($_POST['submit']) == 'check out'){ //if the form has come in from the initial cart page
    //validate that the cart in the session and in the db are the same (??)
    if (!isset($_SESSION['cart'])){
        $error_msg = 'Could not locate your cart';
    }
    if ($_POST['token'] == $_SESSION['form_token']){
        $valid = 1;
    } else {
        $error_msg = 'Invalid form submission.';
    } 

    if ($valid){
        //ready to purchase
        //show total (and show items?)
        $total = $cart->getFormattedTotal();
        $content= "<h3>Purchase for <strong>$total</strong></h3>";

        $content.= '<p>Shipping Address: </p>';
        //get address from DB or show address fields
        //get customer addresses
        $addresses = new AddressCollection($customer_id);
        $address_options = array();
        //make empty default option
        $address_options[] = array(0,'Choose an address');
        if (!empty($addresses)){
            foreach($addresses as $a){
                $address_options[] = array($a->id,$a->address1);
            }
        }
        
        
        $form = new Form();
        $form->addSelectList('address_id', 'Ship to',$address_options);
        $form->addText('Or ship to a different address');
        //token
        $token_str = '';
        //make a string from the cart ids converted to hex
        foreach ($_SESSION['cart'] as $id){
            $token_str.= dechex($id);
        }
        $token = md5($customer_id.time().$token_str); //also add a salt? from file or db
        $_SESSION['form_token'] = $token;
        $form->addInput('token','',$token,'hidden');
        
        $form->addInput('address1','Address Line 1');
        $form->addInput('address2','Address Line 2');
        $form->addInput('city','City');
        $form->addInput('state','State');
        $form->addInput('zip','Zip');
        //$form->addInput('country','Country');

        //add payment fields here
        $form->addInput('submit',null,'Place Order','submit');
        $content.= $form->render();


        //choose payment -- paypal API sandbox??? 
    } else { //if not valid
        require_once 'template.php';
        exit;
    }
} elseif (strtolower($_POST['submit']) == 'place order') { //if this page has been submitted to itself, the order finalized
    //check the token
    if ($_POST['token'] == $_SESSION['form_token']){
        $valid = 1;
    }
    if ($valid){
        //is the customer using an existing address or a new address?
        if((int)$_POST['address_id']){
            //use an existing address
            $aid = (int)$_POST['address_id'];
        } elseif ($_POST['address1'] && $_POST['city'] && $_POST['state'] && $_POST['zip']){ //or create a new address
             //-- validate post fields
            //**** use address validation API??? ex: smartystreets.com  .... or google maps geocoding
            $addy = new Address();
            $addy->customer_id = $customer_id;
            if ($addy->validateStreet($_POST['address1'])){
                $addy->address1 = strip_tags($_POST['address1']);
                if ($_POST['address2'] && $addy->validateStreet($_POST['address2'],true)){
                    $addy->address2 = strip_tags($_POST['address2']);
                }
                $addy->city = strip_tags($_POST['city']);
                $addy->state = strip_tags($_POST['state']);
                $addy->zip = strip_tags($_POST['zip']);
                
                try{
                    $addy->save();
                    //$info_msg .= 'Saved new address.';
                    $aid = $addy->id;
                } catch (PDOException $e){
                    $error_msg = 'Could not save address';
                }
            }
        }
        if ($aid){
            //store the order
            $order = new Order();
            //set order properties
            $order->customer_id = $customer_id;
            $order->order_date = time();
            $order->address_id = $aid;
            $order->setProducts($cart);
            try{
               $order->save();
               if (!isset($addy)){$addy = new Address($aid);}
               $info_msg .= '<p>Successfully completed your order.</p>';
               $content .= '<p>We will ship your order to:</p><br/><p>';
               $content .= $addy->getFullAddress().'</p>';
                //remove items from session cart
                unset($_SESSION['cart']);
            } catch (PDOException $e){
                $error_msg = 'Could not save order.';
            }
        }
        
        
    }
}

$content.= '<p class="back"><a href="index.php">Back to shopping</a></p>'; 
require_once 'template.php';