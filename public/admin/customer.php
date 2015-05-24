<?php
require_once '../../includes/inc.autoload.php';
$title = 'Admin: Customer';
$error_msg = '';
$content = '';
require_once '../../includes/inc.CheckLogin.php';
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
if ((int)$_GET['id']){
    $id = (int)$_GET['id'];
    $title = 'Admin: Customer #'.$id;
    $customer = new Customer($id);
    
    foreach ($customer->getProfileData() as $field=>$value){
        if ($field == '_title'){
            $title = $value;
        } else {
            $content .= "<p>$field: $value</p>";
        }   
    }
    
    //addresses
    //display list of addresses
    $addresses = new AddressCollection($id);
    if (!empty($addresses)){
        $content .= '<h3>Addresses</h3>';
        foreach ($addresses as $a){
            $content .= '<div class="cust-address">';
            $content .= $a->getFullAddress();
            $content .= '</div>';
        }
    }
    
    
} else {
    $error_msg = 'No order id given.';
}

//template
require_once '../template.php';

