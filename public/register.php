<?php
require_once '../includes/inc.autoload.php';
$title = 'Register';
$error_msg = '';
$content = '';

if (isset($_POST['submit'])){
    if ($_POST['password'] == $_POST['password_confirm']){
        $customer = new Customer();
        $success = $customer->newAccount(
            strip_tags($_POST['email']), 
            strip_tags($_POST['first_name']), 
            strip_tags($_POST['last_name']), 
            strip_tags($_POST['password'])
            );
        if ($success){
            $content.='New account created. <a href="login.php">Login</a>';
            //TODO: email confirmation
            
        } else {
            if ($customer->error_msg){
                $error_msg = strip_tags($customer->error_msg);
            } else {
                $error_msg = 'Error creating new account.';
            }
            
        }
    } else {
        $error_msg = 'Passwords do not match.';
    }   
    
} else {
    $form = new Form();
    $form->addInput('first_name','First Name');
    $form->addInput('last_name','Last Name');
    $form->addInput('email','Email');
    $form->addInput('password','Password',null,'password');
    $form->addInput('password_confirm','Confirm Password',null,'password');
    $form->addInput('submit',null,'Register','submit');
    $content.= $form->render();
}


//template
require_once 'template.php';