<?php

function firstitem($arr){
	return reset($arr);
}

function utf8Encode($var, $translit = true){
	if(is_array($var)){
		return array_map('utf8Encode',$var);
	}else if(gettype($var) == 'string'){
		//return utf8_encode($var);
		$target = 'UTF-8';
		if ($translit) $target .= '//TRANSLIT';
		return iconv('ISO-8859-1', $target, $var);
	}else{
		return $var;
	}
}

function utf8Decode($var, $translit = true){
	if(is_array($var)){
		return array_map('utf8Decode',$var);
	}else if(gettype($var) == 'string'){
		//return utf8_decode($var);
		$target = 'ISO-8859-1';
		if ($translit) $target .= '//TRANSLIT';
		return iconv('UTF-8', $target, $var);
	}else{
		return $var;
	}
}
