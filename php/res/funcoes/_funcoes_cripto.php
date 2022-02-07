<?php 

/**
*   Funçao gera um hash curto para autorizaзгo
*
*/
function hashe($val){
    $hs = sha1(SALT.$val);
    $hs2 = strrev($hs);
    $hs3 = substr($hs2,3,5);
    return $hs3;
}
