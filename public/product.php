<?php
/*
 * Show an individual product
 * 
 */
session_start();
require_once '../includes/inc.autoload.php';
require_once '../classes/ProfileInterface.php';

$title = '';
$error_msg = '';
$content = '';
$img_path = 'images/product_images/';

if((int)$_GET['id']){
    $id = (int)$_GET['id'];
}
$product = new Product($id);
foreach ($product->getProfileData() as $field=>$value){
    if ($field == '__title'){
        $title = $value;
    } else {
        if ($field == '__img' && !empty($value)){
            $content .= "<div id='product_img'><img src='{$img_path}$value' /></div>";
        } else {
           $content .= "<p>$field: $value</p>"; 
        }
        
        
    }   
}
//make the form
$form = new Form();
$form->addInput('pid','',$id,'hidden');
$form->addInput('qty','Quantity',1,'text',2); 
$form->addInput('submit',null,'Add to Cart','submit');
$form->setAction('cart-add.php');
$content .= $form->render();
$content .= '<p><br/><a href="index.php">Back to shopping</a></p>';

//display
require_once 'template.php';

?>
