<?php
session_start();
require_once '../includes/inc.autoload.php';
$title = 'Welcome to our shop';
$error_msg = '';
$content = '<h3>Newest products</h3>';

$limit = 20;
$list = new ProductCollection($limit);
$content .= '<div class="product-box-container">'."\n";
foreach ($list as $product){
    $content.= '<div class="product-box">';
    $content.= '<img src="images/product_images/generic_product.png" />';
    $content.= '<p><a href="product.php?id='.$product->id.'">'.$product->product_name.'</a></p>';
    $content.= '<p>$'.$product->getFormattedPrice().'</p>';
    $content.= '</div>'."\n";
}
$content .= '</div>';


//display
require_once 'template.php';