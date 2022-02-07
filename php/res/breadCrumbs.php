<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.4 [2021-dez-03]
* @copyleft GPLv3
*/
if(isset($breadcrumbs)){
    $breadSize = sizeof($breadcrumbs)-1;
    if(defined('LIB_BS') and LIB_BS == 4){ require_once (__DIR__.'/breadCrumbs/_breadCrumbs_bs4.php'); }
    if(defined('LIB_BS') and LIB_BS == 3){ require_once (__DIR__.'/breadCrumbs/_breadCrumbs_bs3.php'); }
    if(defined('LIB_BS') and LIB_BS == 2){ require_once (__DIR__.'/breadCrumbs/_breadCrumbs_bs2.php'); }
}
