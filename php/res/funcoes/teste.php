<?php
function somenteLetrasEEspacos($str){
	//return preg_replace("[^A-Za-z ]", "", $str);
	return preg_replace("/[^A-Za-z[:space:]áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/","",$str);
}

function somenteLetras($str){
	//return preg_replace("[^A-Za-z ]", "", $str);
	return preg_replace("/[^A-Za-záàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/","",$str);
}


echo somenteLetras('Áugusté Conceição');
