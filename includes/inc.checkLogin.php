<?php
session_start();
//user must be logged in. 
if(!isset($_SESSION['cid'])){
        //Add a referrer on the link and make login.php send them back to this page.
        if (!isset($ref)){$ref = '';}
        $error_msg = 'You must be logged in to view this page. You can <a href="login.php'."?$ref".'">login</a> now if you like.';
        
        //Show the template and end the script
        require_once('template.php');
        exit;
} 


?>