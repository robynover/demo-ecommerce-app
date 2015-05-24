<?php
session_start();
require_once '../includes/inc.autoload.php';
$error_msg = '';
$content = '';
$info_msg = '';
$title = 'Login';
$ref = ''; //used to redirect the user after login
$id = 0; //also for redirect
$redirect = 'index.php'; //index is the default page to redirect to
$allowed_refs = array('cart','order-list','customer-list'); //whitelist referrer pages


//check log-in
if (isset($_POST['submit'])){
    //need to check token also
    //TODO: add salt
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $pw=$_POST['password'];
        $email = $_POST['email'];
        $login = new Login();
        if ($user_info = $login->checkCredentials($email, $pw)){
            $cid = $user_info['id'];
            $_SESSION['fname'] = $user_info['fname'];
            $_SESSION['cid'] = $cid; //remember the user in the session
            $_SESSION['cart'] = array(); //start with a fresh cart
            $_SESSION['admin'] = $user_info['is_admin']; //store user's admin status
            //check if there's a referrer for the redirect
            if(isset($_POST['ref'])){ 
                if(in_array($_POST['ref'],$allowed_refs)){
                    $ref = $_POST['ref'];
                    if ($ref == 'cart'){
                        $redirect ='profile.php?type=product';
                    } else {
                        $redirect = $ref.'.php';
                    }
                }
                if (isset($_POST['n'])){
                     $redirect .='&id='.(int)$_POST['n'];
                }
            }
            $header = 'Location:'.$redirect;
            header($header);
            exit;
        } else {
            $content .= 'could not log in';
        }
    }
} else{
    if(isset($_SESSION['cid'])){ //if they are logged in, this is the log-out form
        unset($_SESSION['cid']);
        unset($_SESSION['cart']);
        $info_msg = 'You are now logged out. Thank you for visiting.';
    } 
    //Determine the page to send the user when they successfully log in
    if(isset($_GET['ref'])){ //to prevent undefined index notice 
        if(in_array('cart',$allowed_refs) && (int)$_GET['n']){
            $ref = 'cart';
            $id = (int)$_GET['n'];
        }
    }
    
    //login form
    $form = new Form();
    $form->addInput('email','Email');
    $form->addInput('password','Password',null,'password');
    //send the reference info is there is some.
    if ($ref){
        $form->addInput('ref','',$ref,'hidden');
    }
    if ($id){
        $form->addInput('n','',$id,'hidden');
    } 
    $form->addInput('submit',null,'Log in','submit');
    //need to add a token field
    $content .= $form->render();
}



//display
require_once('template.php');
?>
