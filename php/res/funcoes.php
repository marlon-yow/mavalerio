<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-may-26]
* @copyleft GPLv3
*/

/*funca de debug*/
include(__DIR__.'/debug.php');

/**TEXTO**/
include(__DIR__.'/funcoes/_funcoes_texto.php');

/**NUMEROS**/
include(__DIR__.'/funcoes/_funcoes_numeros.php');

/*Criptografia*/
include(__DIR__.'/funcoes/_funcoes_cripto.php');

/** ARRAY **/
include(__DIR__.'/funcoes/_funcoes_array.php');

/**DATA**/
include(__DIR__.'/funcoes/_funcoes_data.php');

/**DOCUMENTO**/
include(__DIR__.'/funcoes/_funcoes_documento.php');

/**LINK**/

function getLinkGet($arrRemove){
	global $_GET;
	$link = '';
	$arr_get = $_GET;

	if($arrRemove){
		if(is_array($arrRemove)){
			foreach ($arrRemove as $value) {
		        unset($arr_get[$value]);
			}
		}else{
			unset($arr_get[$arrRemove]);
		}
	}

    if(is_array($arr_get)){
        foreach ($arr_get as $key => $value) {
            $link .= "&$key=$value";
        }
    }
    return $link;
}

/*Permissao*/
function noPowerHere(){
    echo "You have no power here";
    die;
}

function testarPermissao($perms=''){
    global $_SESSION, $appname;
    $DBG = false;

    if($DBG) echo 'testar acesso. ';

    $permitido = false;
    if(is_array($perms)){
        if($DBG) echo 'vetor ';
        $txtPerm = implode(' ou ',$perms);
        foreach ($perms as $key) {
            if($DBG) echo "testar $key ";
            if($_SESSION[$appname]['PERM'][$key]){
                if($DBG) echo 'liberado ';
                $permitido = true;
            }
        }
    }else if($perms){
        if($DBG) echo 'texto ';
        $txtPerm = $perms;
        if($DBG) echo "testar $perms ";
        if($_SESSION[$appname]['PERM'][$perms]){
            if($DBG) echo 'liberado ';
            $permitido = true;
        }
    }
    return $permitido;
}

function requerirPermissao($perms=''){
    global $_SESSION, $appname, $caminho;
    $DBG = false;

    if($DBG) echo 'testar acesso. ';

    $permitido =  testarPermissao($perms);

    if(!$permitido){
        if($DBG) echo 'bloqueado ';
        $_SESSION[$appname]['mensagem'] = array("<h4>Sem acesso!</h4> Permissão requerida do tipo: $txtPerm",'VERMELHO','FICAR');
        if(!headers_sent()){
            header("Location: $caminho");
        }else{
            echo $_SESSION[$appname]['mensagem'][0];
        }
        exit();
    }
}