<?php
require_once '../../includes/inc.autoload.php';
require_once '../../includes/inc.checkLogin.php';
$title = 'Admin: Customers';
$error_msg = '';
$content = '';
$ref='ref=customer-list';
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


$customers = new CustomerCollection();
$content.= '<ol>';
foreach ($customers as $c){
    $content.= '<li><a href="customer.php?id='.$c->id.'">'.$c->getFullName().'</a></li>';
    
}
$content.= '</ol>';

//template
require_once('../template.php');
