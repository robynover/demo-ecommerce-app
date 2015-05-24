<?php
session_start();
require_once '../includes/inc.autoload.php';

/*$collection = new ProductCollection();
foreach ($collection as $p){
    echo $p->product_name;
}*/

$customer_id = (int)$_SESSION['cid'];
/*$product = new Product(5);

$cart = new Cart($customer_id);
$cart->add($product, 1);

foreach ($cart as $k=>$v){
    echo $k;
    var_dump($v);
}*/



$cart = new Cart($customer_id,$_SESSION['cart']);
//var_dump($cart);
foreach ($cart as $product){
    echo $product->product_name;
    echo "<br/>";
}

/*
$db = DB::getInstance();

$query = 'SELECT * FROM products WHERE id IN (?,?)';
$stmt = $db->prepare($query);
$stmt->setFetchMode(PDO::FETCH_CLASS,'Product');
$stmt->execute(array(5,8));
$array=  $stmt->fetchAll(); 

$c = new Collection();
$c->setList($array);

foreach ($c as $k=>$v){
    var_dump($v);
}*/