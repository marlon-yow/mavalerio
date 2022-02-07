<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2021-jun-11]
* @copyleft GPLv3
*/
function debug($var, $die= true){
    echo "<pre>";
    print_r($var);
    echo "\n\n";
    //echo __FILE__.":".__LINE__."\n\n";
    debug_print_backtrace ();
    if($die) die;
}


function myErrorHandler($errno, $errstr, $errfile, $errline){
    //echo "Erro Numero: ". $errno; return false;
    if(substr($errstr,0,18) == "Undefined variable") return true;
    if(substr($errstr,0,19) == "Undefined array key") return true;
    if(substr($errstr,0,51) == "Trying to access array offset on value of type null") return true;

    return false;
}

$old_error_handler = set_error_handler("myErrorHandler",E_WARNING);