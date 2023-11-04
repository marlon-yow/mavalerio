<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.4 [2021-dez-03]
* @copyleft GPLv3
*/
if(isset($breadcrumbs)){
    $breadSize = sizeof($breadcrumbs)-1;
    if(defined('LIB_BS')){
        if(file_exists(__DIR__.'/breadCrumbs/_breadCrumbs_bs'.LIB_BS.'.php')){
            require_once (__DIR__.'/breadCrumbs/_breadCrumbs_bs'.LIB_BS.'.php');
        }else{
            echo "Arquivo não encontrado: ".__DIR__.'/breadCrumbs/_breadCrumbs_bs'.LIB_BS.'.php';
            echo __FILE__.":".__LINE__;
            die;
        }
    }
}
