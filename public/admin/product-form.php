<?php
require_once '../../includes/inc.autoload.php';
$error_msg = '';
$title = 'Admin: Product Form';
$content = '';
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
if (isset($_POST['submit'])){
    //is this an edit or a new record?
    if (isset($_POST['id'])){
        $id = (int)$_POST['id'];
        $product = new Product($id);
    } else {
        //add a new product
        $product = new Product();
    }
    
    $product->product_name = strip_tags($_POST['product_name']); 
    if (ctype_alnum($_POST['product_num'])){
        $product->product_num = $_POST['product_num'];
    }
    if (is_numeric($_POST['price'])){
        $product->price = $_POST['price'];
    }
    if (is_numeric($_POST['quantity'])){
        $product->quantity_on_hand = $_POST['quantity'];
    }
    if (isset($_POST['desc'])){
        $product->description = strip_tags($_POST['desc']);
    }
    $title = "Product";
    try{
        $product->save();
        $content = "Saved Product: ".$product->product_name;
        $content .= " | <a href='../product.php?id={$product->id}'>view</a> ";
        $content .= '<p><a href="product-form.php">Add another product</a></p>';
    }catch(PDOException $e){
        $error_msg = "Could not save product."; 
    }
    
    
    //display result of add
} else {
    //initialize vars
    $name = ''; 
    $num = '';
    $price = '';
    $quantity = '';
    $desc = '';
    $action = 'Add';
    $is_edit = 0;
    $button = 'Add New Product';
    
    //is this an edit?
    if (isset($_GET['id']) && (int)$_GET['id'] > 0){
        $is_edit = 1;
        $id = (int)$_GET['id'];
        $product = new Product($id);
        $name = $product->product_name;
        $num = $product->product_num;
        $price = $product->price;
        $quantity = $product->quantity_on_hand;
        $desc = $product->description;
        $action = 'Edit';
        $button = 'Edit';
    }
    
    
    $title = $action.' Product';
    $form = new Form();
    $form->addInput('product_name','Product Name',$name);
    $form->addInput('product_num', 'Product Number',$num);
    $form->addInput('price','Price',$price);
    $form->addInput('quantity','Quantity on Hand',$quantity);
    $form->addTextArea('desc','Description',$desc);
    if ($is_edit){
        $form->addInput('id','',$id,'hidden');
    }
    $form->addInput('submit',null,$button,'submit');
    $content = $form->render();
    
}
$content .= '<p class="back"><a href="product-list.php">Back to product list</a></p>';
//---Display---//
require_once('../template.php');
?>
