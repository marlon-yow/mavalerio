<?php

$m_arr['CPF'] = '###.###.###-##';
$m_arr['CNPJ'] = '##.###.###/####-##';
$m_arr['CEP'] = '##.###-###';
$m_arr['RG'] = '##.###.###-#';

function mask($val, $mask){
	global $m_arr;

	$_mask = $mask;
	$mask = $m_arr[$mask];

	if(!$_mask) return "NO MASK DESC mask($val, $mask)";
	if(!$mask) return "NO MASK AVALIABLE mask($val, $mask)";
    if(!$val) return str_replace("#"," ",$mask);

	//echo "mask($val,$_mask=> ".$mask.")";

	$maskared = '';
	$k = 0;
	for($i = 0; $i<=strlen($mask)-1; $i++){
		if($mask[$i] == '#'){
			if(isset($val[$k]))
				$maskared .= $val[$k++];
		}else{
			if(isset($mask[$i]))
				$maskared .= $mask[$i];
		}
	}
	if ($maskared) return $maskared;
	return $val;
}

function dinheiroPrint($valor,$casasDescimais=2){
    if(!$valor) return '0,00';
	$valor = str_replace(",", ".", $valor);

	$margemCorte = pow(10,$casasDescimais);
	$valor_u = $valor * $margemCorte;
	$temp_arr = explode(".", $valor_u);
	$valor = $temp_arr[0] / $margemCorte;

	return number_format(str_replace(',','.',$valor), $casasDescimais, "," , ".");
}

function dinheiroSemMascara($valor){
	$valor = dinheiroPrint($valor);
	$valor = str_replace(",", "", $valor);
	$valor = str_replace(".", "", $valor);
	return $valor;
}

function somenteNumeros($value){
   return preg_replace( '/[^0-9]/', '', $value);
}

function somenteNumerosPontosEVirgulas($value){
    return preg_replace('/[^0-9],./', '', $value);
}

function transformaDiaHoraParaBD($value){
    $newDate = preg_replace('/T/', ' ', $value);
    return substr_replace($newDate, ':00', 16, 0);
}

function transformaDiaHoraParaInput($value){
    $newDate = preg_replace('/ /', 'T', $value);
    return substr_replace($newDate, '', 16, 0);
}
