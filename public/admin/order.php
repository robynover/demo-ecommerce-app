<?php
require_once '../../includes/inc.autoload.php';
$title = 'Admin: Order';
$error_msg = '';
$content = '';
require_once '../../includes/inc.CheckLogin.php';
$admin = 0;
if(isset($_SESSION['cid'])){
    $user = new User($_SESSION['cid']);
    if ($user->isAdmin()){
        $admin = 1;
    }
}
if (!$admin){
    $error_msg = 'Admin restricted area';
    //Show the template and end the script
    require_once('../template.php');
    exit;
}
if ((int)$_GET['id']){
    $id = (int)$_GET['id'];
    $title = 'Admin: Order #'.$id;
    $order = new Order($id);
    /*
     * show:
     *  date
     *  customer
     *  products
     *  address
     */
    $customer = new Customer($order->customer_id);
    $address = new Address($order->address_id);
    
    
    $content .= '<p>Order date: '.$order->getFormattedDate().'</p>';
    $content .= '<p>Customer: <a href="../profile.php?type=customer&id='.$customer->id.'">'.$customer->getFullName().'</a></p>';
    $content .= '<p>Address:</p>'.$address->getFullAddress();
    $content .= '<h3>Products</h3>';
    $content .= '<ul>';
    foreach ($order->getProducts() as $product){
        $content .= '<li>';
        $content .= $product->product_name;
        $content .= ' <a href="../profile.php?type=product&id='.$product->id.'">#'. $product->product_num.'</a>';
        $content .= '</li>';
    }
    $content .= '</ul>';    
    
} else {
    $error_msg = 'No order id given.';
}

//template
require_once '../template.php';
