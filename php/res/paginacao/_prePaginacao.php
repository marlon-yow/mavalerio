<?php
/*!
* @Autor MV https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2022-jan-04]
* @copyleft GPLv3
*/

/* Paginao (parte1)*/
$pg = (isset($_GET['pg']) ? $_GET['pg'] : 1 );
if(!isset($numreg)) $numreg = 30;
$final = $pg * $numreg;
$inicial = ($pg-1) * $numreg;
$paginado = array('pg'=>$pg,'registrosPorPagina' => $numreg);