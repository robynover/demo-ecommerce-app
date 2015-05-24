<?php
//this needs to be in an include file!
//if (session_status()== PHP_SESSION_NONE){ //<-- this only works in PHP5.4
/*if (!isset($_SESSION['cid'])){
    session_start();  
}*/
$logged_in = 0;
$is_admin = 0;
$log_text= 'Login';
if (isset($_SESSION['cid'])){
    $log_text = 'Logout';
    $logged_in = 1;
    $firstname = $_SESSION['fname'];
    if (isset($_SESSION['admin']) &&  $_SESSION['admin'] == 1){
        $is_admin = 1;
    }
}
$path_to_public = '/zend/php2/php2newproject/public/';
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title ?></title>
        <link rel="stylesheet" type="text/css" href="<?=$path_to_public ?>css/style.css" />

    </head>
    <body>
        <section id="page">
            <header>
                <h1><a href="<?=$path_to_public ?>">Widget World</a></h1>
                
                <nav>
                    <?php
                    if ($logged_in){
                        echo "Welcome, $firstname";
                    } else {
                        echo '<a href="'.$path_to_public.'register.php">Register</a>';
                    }
                    ?> | <a href="<?=$path_to_public ?>view-cart.php">View Cart</a> | <a href="<?=$path_to_public ?>login.php"><?php echo $log_text ?></a>
                </nav>
            </header>
            <?php
            //show the admin menu if the user is logged in. 
            if ($logged_in && $is_admin){
                echo '<section id="admin_nav"><nav>Admin: <a href="'.$path_to_public.'admin/order-list.php">Orders</a> | 
                    <a href="'.$path_to_public.'admin/product-list.php">Products</a>| 
                    <a href="'.$path_to_public.'admin/customer-list.php">Customers</a></nav></section>';
            }
            ?>
            
            <section id="main">
                <h2><?php echo $title ?></h2>
                
                <?php
                    if ($error_msg){
                        echo "<div class='error_msg'>$error_msg</div>";
                    } 
                    if (isset($info_msg) && strlen($info_msg) > 0){
                        echo "<div class='info_msg'>$info_msg</div>";
                    }
                    echo $content;


                ?>
            </section>
        </section>   
    </body>
</html>