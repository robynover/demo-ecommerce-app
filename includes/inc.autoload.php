<?php
/*** autoload clases ***/
function classLoader($class){
    $file = __DIR__.'/../classes/class.'.$class.'.php';
    //echo $file;
    if (!file_exists($file)){
        return false;
    }
    require_once($file);
}

/*** register the loader ***/
spl_autoload_register('classLoader');
?>