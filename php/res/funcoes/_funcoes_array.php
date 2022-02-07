<?php 

function firstitem($arr){
	return reset($arr);
}

function utf8Encode($var){	
	if(is_array($var)){
		return array_map('utf8Encode',$var);
	}else if(gettype($var) == 'string'){
		return utf8_encode($var);
	}else{
		return $var;
	}
}

function utf8Decode($var){	
	if(is_array($var)){
		return array_map('utf8Decode',$var);
	}else if(gettype($var) == 'string'){
		return utf8_decode($var);		
	}else{
		return $var;
	}
}
