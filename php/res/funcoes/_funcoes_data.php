<?php

function dataVenceu($data){
	if($data){
		$data = substr($data,6,4).substr($data,3,2).substr($data,0,2);
		if(date('Ymd') <= $data){
			return false;
		}
	}
	return true;
}

/* dia da semana em texto */
function diaDaSemana($d){
	$arr_d = explode("/", $d);
	$d = date('w',mktime(0,0,0,$arr_d[1],$arr_d[0],$arr_d[2]));

	$arr_dias= array(
		0 => 'Domingo',
		1 => 'Segunda-feira',
		2 => 'Terça-feira',
		3 => 'Quarta-feira',
		4 => 'Quinta-feira',
		5 => 'Sexta-feira',
		6 => 'Sábado',
    );
	return $arr_dias[$d];
}

/*$data1 menos a $data2*/
function diferencaDias($data1,$data2){
	$dd1 = substr($data1,0,2);
	$mm1 = substr($data1,3,2);

	$yy1 = substr($data1,6,4);
	$dd2 = substr($data2,0,2);

	$mm2 = substr($data2,3,2);
	$yy2 = substr($data2,6,4);

	$date1 = mktime(0,0,0,$mm1,$dd1,$yy1);
	$date2 = mktime(0,0,0,$mm2,$dd2,$yy2);

	$diff = $date1-$date2;
	$fullDays = floor($diff/(60*60*24));
	return $fullDays;
}

/*Hora1 menos a hora2*/
function difdehora($hora1, $hora2){

	$hh1 = substr($hora1,0,2);
	$hm1 = substr($hora1,3,2);

	$hh2 = substr($hora2,0,2);
	$hm2 = substr($hora2,3,2);

	$hora1 = $hh1 * 60 + $hm1;
	$hora2 = $hh2 * 60 + $hm2;

	$diff = $hora1 - $hora2;

	if($diff > 1000){ $diff = $diff - (24 * 60);  }
	if($diff < -1000){ $diff = $diff + (24 * 60);  }

	return $diff;
}

/*diferenca entre um time() iniciado no comeco do processamento e o agora, human read*/
function endTime($inp){
    $fnp = time();
    $tp = $fnp - $inp;
    return time_elapsed_B($tp);
}

function getNumMes($mes){ return getMes($mes);}
function getMes($mes){
    $mes = strtolower($mes);
	switch($mes) {
		case"01": $numMes = "Janeiro";	break;
		case"02": $numMes = "Fevereiro";break;
		case"03": $numMes = "Março";	break;
		case"04": $numMes = "Abril";	break;
		case"05": $numMes = "Maio";	break;
		case"06": $numMes = "Junho";	break;
		case"07": $numMes = "Julho";	break;
		case"08": $numMes = "Agosto";	break;
		case"09": $numMes = "Setembro";	break;
		case"10": $numMes = "Outubro";	break;
		case"11": $numMes = "Novembro";	break;
		case"12": $numMes = "Dezembro";	break;

		case"janeiro":   $numMes = "01";break;
		case"fevereiro": $numMes = "02";break;
		case"março":
		case"marco":     $numMes = "03";break;
		case"abril":     $numMes = "04";break;
		case"maio":      $numMes = "05";break;
		case"junho":     $numMes = "06";break;
		case"julho":     $numMes = "07";break;
		case"agosto":    $numMes = "08";break;
		case"setembro":  $numMes = "09";break;
		case"outubro":   $numMes = "10";break;
		case"novembro":  $numMes = "11";break;
		case"dezembro":  $numMes = "12";break;
	}
	return 	$numMes;
}

function getTempo($dt,$inverter=false){
    global $FUN;
    $dt = $FUN->normalizaData($dt);

    $arr = explode(" ", $dt);
    $hoje = date('Y-m-d');

    if ($hoje == $arr[0]) { // eh hoje mostra em horas/minutos
        $arr2 = explode(":", $arr[1]);

        $hora1 = date('H') * 60 + date('i');
        $hora2 = $arr2[0] * 60 + $arr2[1];
        if($inverter){ $_a = $hora1; $hora1 = $hora2; $hora2 = $_a; }

        $diff = $hora1 - $hora2;
        if ($diff < 60) {
            $texto_distancia = " minuto";
        } else {
            $diff = intval($diff / 60);
            $texto_distancia = " hora";
        }
    } else {
        $dia2 = explode("-", $arr[0]);

        $date1 = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $date2 = mktime(0, 0, 0, $dia2[1], $dia2[2], $dia2[0]);
        if($inverter){ $_a = $date1; $date1 = $date2; $date2 = $_a; }

        $difef = $date1 - $date2;
        $diff = round($difef / (60 * 60 * 24));

        if ($diff < 31) {
            $texto_distancia = " dia";
        } else {
            $diff = round($diff / 30);
            $texto_distancia = " mês";
        }
    }
    if ($diff != 1) {
        if ($texto_distancia == ' mês') {
            $texto_distancia = " meses";
        } else {
            $texto_distancia .= "s";
        }
    }
    return $diff . $texto_distancia;
}

function time_elapsed_B($secs){
    $bit = array(
        ' ano'        => $secs / 31556926 % 12,
        ' semana'        => $secs / 604800 % 52,
        ' dia'        => $secs / 86400 % 7,
        ' hora'        => $secs / 3600 % 24,
        ' minuto'    => $secs / 60 % 60,
        ' segundo'    => $secs % 60
        );

    $ret = array();
    foreach($bit as $k => $v){
        if($v > 1)$ret[] = $v . $k . 's';
        if($v == 1)$ret[] = $v . $k;
    }

    $str = join(', ', $ret);
    $str = preg_replace('/(,(?!.*,))/', ' e', $str);

    //debug($str);
    //array_splice($ret, count($ret)-1, 0, 'e');
    //$ret[] = 'atrs.';

    if($str) return $str;
    return "0 segundos";
}
