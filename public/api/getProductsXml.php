<?php
/*
 * The xml is created by hand here. Could also be created with the DOMDocument class
 */
require_once '../../includes/inc.autoload.php';
header('Content-type:text/xml');
echo "<?xml version='1.0' encoding='UTF-8'?>";
$list = new ProductList();
echo '<products>';
foreach ($list as $obj){
    echo '<product>';
        echo '<product-num>'.$obj->getProductNum().'</product-num>';
        echo '<product-name>'.$obj->getProductName().'</product-name>';
        echo '<price>'.$obj->getPrice().'</price>';
    echo '</product>';
}
echo '</products>';