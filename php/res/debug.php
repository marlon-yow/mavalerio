<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2021-jun-11]
* @copyleft GPLv3
*/
function debug($var, $die= true, $trace = true, $type = true){
    echo "<pre>";
    print_r($var);
    echo "\n\n";
    //echo __FILE__.":".__LINE__."\n\n";
    if($type){ echo "TIPO: ".gettype($var)."\n"; }
    if($trace){ echo "TRACE:\n"; debug_print_backtrace (); }
    if($die) die;
    echo "</pre>";
}


function myErrorHandler($errno, $errstr, $errfile, $errline){
    if(substr($errstr,0,18) == "Undefined variable") return true;
    if(substr($errstr,0,19) == "Undefined array key") return true;
    if(substr($errstr,0,51) == "Trying to access array offset on value of type null") return true;
    if(substr($errstr,0,23) == "gzinflate(): data error") return true;
    if(substr($errstr,0,40) == 'Use of "self" in callables is deprecated') return true;
    if(substr($errstr,0,39) == 'Creation of dynamic property GuzzleHttp') return true;


    // echo "Erro Numero: ". $errno; return false;
    // echo "Erro Numero: ". $errstr; return false;

    return false;
}


$old_error_handler = set_error_handler("myErrorHandler",E_ALL);
// $old_error_handler = set_error_handler("myErrorHandler",E_DEPRECATED);
