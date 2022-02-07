<?php

$menu = array(
    array('titulo'=>'Cadastros', 'class'=>'btn btn-secondary', 'onclick'=>"showMenu(this,'submenu1')",'permissao'=>array('OPERACAO')),
    array('titulo'=>'Administração', 'class'=>'btn btn-secondary', 'onclick'=>"showMenu(this,'submenu6')",'permissao'=>array('ADMIN')),
);

$submenu = array(
    'submenu1' => array(),

    'submenu6' => array(
        array('titulo'=>'Permissões',"class"=>"dropdown-item", 'href'=>$caminho.'usuario/usuario_listall.php','permissao'=>array('ADMIN')),            
    ),
);
