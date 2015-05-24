<?php
require_once '../../includes/inc.autoload.php';
$title = 'Admin: Products';
$content = '';
$error_msg = 0;
$ref='ref=product-list';
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
    require_once('../../template.php');
    exit;
}
$list = new ProductCollection();
$content.= "<table>\n";
$content.= "<tr><th>Product Number</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>\n";
foreach ($list as $product){
    $content.= '<tr>';
    $content.= '<td>'.$product->product_num.'</td>';
    $content.= '<td>'.$product->product_name.'</td>';
    $content.= '<td>'.$product->price.'</td>';
    $content.= '<td>'.$product->quantity_on_hand.'</td>';
    $content.= '<td><a href="product-form.php?id='.$product->id.'">edit</a> | <a href="../product.php?id='.$product->id.'">view</a></td>';
    $content.= '</tr>'."\n";
}
$content.= "</table>\n";
$content.= '<p><a href="product-form.php">Add new product</a></p>';

//template
require_once('../template.php');
?>