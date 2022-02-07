<?php

/**TEXTO**/
function endExplode($arg,$var){
	$_E = explode($arg,$var);
    return end($_E);
}

function firstword($nomew,$arg=' '){
	$arr = explode($arg,$nomew);
	return $arr[0];
}

/* Placa de Carro, já com padrão mercosul */
function fixPlaca($placa){
    $placa = strtoupper(somenteLetrasENumeros($placa));
    $p1 =  substr($placa, 0, 3);
    $p1 = preg_replace("/[^A-Z]/","",$p1);

    $p2 = substr($placa,3,1);
    $p2 = somenteNumeros($p2);

    $p3 = substr($placa, 4, 1);
    $p3 = somenteLetrasENumeros($p3);

    $p4 = substr($placa,5,2);
    $p4 = somenteNumeros($p4);

    $placa = $p1."-".$p2.$p3.$p4;
    if(strlen($placa) != 8) return false;
    return $placa;
}

function homeExplode($arg,$var){
	$_E = explode($arg,$var);
    return reset($_E);
}

function pad($var, $numChar, $pad='0',$pad_type=STR_PAD_LEFT){
	//STR_PAD_BOTH STR_PAD_LEFT STR_PAD_RIGHT
	return str_pad($var,$numChar,$pad,$pad_type);
}

function protect($var){
	$sec_arr = array("'"=>'','"'=>"");
	
	if(is_array($var)){
		return array_map('protect',$var);
	}else{
		return strtr($var,$sec_arr);
	}
}

function protectMail($email){
	$email = preg_replace("/[^A-Za-z0-9@.#+-_]/","",$email);
    $email = substr($email,0,255);
    
    return $email;
}

function removeEspacosDuplicadosEntrePalavras($var){
	return preg_replace('/ +/', ' ', $var);
}

function somenteLetras($str){
	//return preg_replace("[^A-Za-z ]", "", $str);
	return preg_replace("/[^A-Za-záàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/","",$str);
}

function somenteLetrasEEspacos($str){
	//return preg_replace("[^A-Za-z ]", "", $str);
	return preg_replace("/[^A-Za-z[:space:]áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/","",$str);
}

function somenteLetrasENumeros($str){
    return preg_replace('/[^a-zA-Z0-9áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/', "", $str);
}

function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}



/* OBSOLETAS */
function aspasEntrada($var){
    trigger_error('Funcao aspasEntrada obsoleta, trocar por protect', E_USER_WARNING);
	return strtr($var, array("'"=>"&#39;", '"'=>"&#34;"));
}
function aspasSaida($var){
    trigger_error('Funcao aspasSaida obsoleta, trocar por protect', E_USER_WARNING);
	return strtr($var, array("&#39;"=>"'", "&#34;"=>'"'));
}
function disconv($val){
    trigger_error('Funcao disconv obsoleta, trocar por utf8_decode', E_USER_WARNING);
    return iconv('utf-8', 'iso-8859-1', $val);
}
