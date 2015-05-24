<?php
require_once '../../includes/inc.autoload.php';
$title = 'Admin: Orders';
$error_msg = '';
$content = '';
$ref='ref=order-list';
require_once '../../includes/inc.checkLogin.php';
//admin-only area
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

$orders = new OrderCollection();

$content.= "<table border='1'>\n";
$content.= "<tr><th>Order Number</th><th>Date</th><th>Customer</th></tr>\n";
foreach ($orders as $order){
    $content.= '<tr>';
    $content.= '<td><a href="order.php?id='.$order->id.'">'.$order->id.'</a></td>';
    $content.= '<td>'.$order->getFormattedDate().'</td>';
    $customer = new Customer($order->customer_id);
    $content.= '<td><a href="customer.php?id='.$customer->id.'">'.$customer->getFullName().'</a></td>';
    $content.= '</tr>'."\n";
}
$content.= "</table>\n";

//template
require_once('../template.php');
