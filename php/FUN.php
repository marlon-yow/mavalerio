<?php
/*! ---UTF-8---
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.4 [2021-dez-14]
* @copyleft GPLv3
*/
if (!class_exists('FUN')) {
Class FUN{
    private $DBG = false;
    public function setDBG($d){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $this->DBG = $d;
        if($this->DBG) echo "FUN->setDBG($d) <br>\n";
    }

    /**TEXTO**/
    public function endExplode($arg,$var){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
    	$_E = explode($arg,$var);
        return end($_E);
    }

    public function firstword($nomew,$arg=' '){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
    	$arr = explode($arg,$nomew);
    	return $arr[0];
    }

    public function homeExplode($arg,$var){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $_E = explode($arg,$var);
        return reset($_E);
    }

    public function pad($var, $numChar, $pad='0',$pad_type=STR_PAD_LEFT){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
    	//STR_PAD_BOTH STR_PAD_LEFT STR_PAD_RIGHT
    	return str_pad($var,$numChar,$pad,$pad_type);
    }

    public function protect($var){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }

    	$sec_arr = array("'"=>'','"'=>"");

    	if(is_array($var)){
            $tmp = array();
            foreach($var as $k => $v){
                $tmp[$k] = $this->protect($v);
            }
            return $tmp;
    	}else{
    		return strtr($var,$sec_arr);
    	}
    }

    public function removeEspacosDuplicadosEntrePalavras($var){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
    	return preg_replace('/ +/', ' ', $var);
    }

    public function somenteLetras($str,$replaceWith=""){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
    	//return preg_replace("[^A-Za-z ]", "", $str);
    	return preg_replace("/[^A-Za-záàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/",$replaceWith,$str);
    }

    public function somenteLetrasEEspacos($str,$replaceWith=""){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
    	//return preg_replace("[^A-Za-z ]", "", $str);
    	return preg_replace("/[^A-Za-z[:space:]áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/",$replaceWith,$str);
    }


    public function somenteLetrasENumeros($str,$replaceWith=""){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if($this->DBG) echo "FUN->somenteLetrasENumeros($str) <br>\n";
        return preg_replace('/[^a-zA-Z0-9áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/', $replaceWith, $str);
    }

    public function somenteLetrasNumerosEspacosEChars($str,$replaceWith=""){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if($this->DBG) echo "FUN->somenteLetrasNumerosEspacosEChars($str) <br>\n";
        return preg_replace("/[^A-Za-z0-9[:space:]áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ,.-]/",$replaceWith,$str);
    }

    public function tirarAcentos($string){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if($this->DBG) echo "FUN->tirarAcentos($string) <br>\n";
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(Ç)/","/(ç)/"),explode(" ","a A e E i I o O u U n N C c"),$string);
    }

    public function formatarNome($nome){

        $nome = strtoupper($nome);
        $nome = $this->removeEspacosDuplicadosEntrePalavras($nome);
        $nome = trim($nome);
        $nome = $this->tirarAcentos($nome);
        $nome = $this->somenteLetrasEEspacos($nome);

        return $nome;
    }

    /**NUMEROS**/
    private $masks = array(
        'CPF' => '###.###.###-##',
        'CNPJ' => '##.###.###/####-##',
        'CEP' => '##.###-###',
        'RG' => '##.###.###-#',
    );

    public function mask($val, $mask){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }

        $_mask = $mask;
        $mask = $this->masks[$mask];

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

    public function dinheiroPrint($valor,$casasDescimais=2){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(!$valor) return '0,00';
        $valor = str_replace(",", ".", $valor);

        $margemCorte = pow(10,$casasDescimais);
        $valor_u = $valor * $margemCorte;
        $temp_arr = explode(".", $valor_u);
        $valor = $temp_arr[0] / $margemCorte;

        return number_format(str_replace(',','.',$valor), $casasDescimais, "," , ".");
    }

    public function dinheiroSemMascara($valor){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $valor = dinheiroPrint($valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace(".", "", $valor);
        return $valor;
    }

    public function somenteNumeros($value){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $val2 = preg_replace( '/[^0-9]/', '', $value);
        if($this->DBG){ echo "Returning $val2 <br>\n";}
        return $val2;
    }

    public function somenteNumerosPontosEVirgulas($value){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        return preg_replace( '/[^0-9],./', '', $value);
    }

    /*Criptografia*/
    public function hashe($val){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $hs = sha1(SALT.$val);
        $hs2 = strrev($hs);
        $hs3 = substr($hs2,3,5);
        $hs4 = strtoupper($hs3);
        return $hs4;
    }

    /** ARRAY **/
    public function firstitem($arr){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        return reset($arr);
    }

    public function utf8Encode($var){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(is_array($var)){
            $_arr = array();
            foreach ($var as $key => $value) {
                $_arr[$this->utf8Encode($key)] = $this->utf8Encode($value);
            }
            return $_arr;
        }else{
            return utf8_encode($var);
        }
    }

    public function utf8Decode($var){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(is_array($var)){
            $_arr = array();
            foreach ($var as $key => $value) {
                $_arr[$this->utf8Decode($key)] = $this->utf8Decode($value);
            }
            return $_arr;
        }else{
            return utf8_decode($var);
        }
    }

    /**DATA**/
    public $arr_dias= array(
		0 => 'Domingo',
		1 => 'Segunda-feira',
		2 => 'Terça-feira',
		3 => 'Quarta-feira',
		4 => 'Quinta-feira',
		5 => 'Sexta-feira',
		6 => 'Sábado',
    );

    public $arr_meses = array(
        "01" => "Janeiro",
		"02" => "Fevereiro",
		"03" => "Março",
		"04" => "Abril",
		"05" => "Maio",
		"06" => "Junho",
		"07" => "Julho",
		"08" => "Agosto",
		"09" => "Setembro",
		"10" => "Outubro",
		"11" => "Novembro",
		"12" => "Dezembro",
    );

    public function validaHora($hr){ return $this->validarHora($hr); }
    public function validarHora($hr){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(!$hr) return false;
        $_arr = explode(" ", $hr);
        $hr = ($_arr[1] ? $_arr[1] : $_arr[0] );
        $arr_hr = explode(":",$hr);
        $h = $arr_hr[0];
        $m = $arr_hr[1];
        $s = $arr_hr[2];
        if(($h<0) or ($h>23)){
            if($this->DBG){ echo "hora $h não passou\n";}
            return false;
        }
        if(($m<0) or ($m>59)){
            if($this->DBG){ echo "minuto $m não passou\n";}
            return false;
        }
        if(($s<0) or ($s>59)){
            if($this->DBG){ echo "segundo $s não passou\n";}
            return false;
        }
        return true;
    }

    public function normalizaHora($str){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $failSafe = '00:00:00';

        if(!$str) return $failSafe;

        $hora = preg_replace('/[^0-9]/', " ", $str);
        $arr = explode(' ',$hora);
        $h = $arr[0];
        $m = $arr[1];
        $s = $arr[2];

        if(($h<0) or ($h>23)){
            $h = 0;
        }
        if(($m<0) or ($m>59)){
            $m = 0;
        }
        if(($s<0) or ($s>59)){
            $s = 0;
        }

        $horaFinal = $this->pad($h,2).":".$this->pad($m,2).":".$this->pad($s,2);
        if($this->validarHora($horaFinal)){
            return $horaFinal;
        }

        return $failSafe;
    }

    public function validaData($dt){ return $this->validarData($dt);}
    public function validarData($dt){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }

        $_arr = explode(" ", $dt);
        if($this->DBG){ echo "FUN->validarData: explode: ".print_r($_arr,1)." <br>\n"; }
        $data = $_arr[0];
        if($this->DBG){ echo "FUN->validarData: data: $data  <br>\n";}

        if(!strpos($dt, "/")){
            if($this->DBG){ echo "FUN->validarData:  nao tem barra  <br>\n";}
            if(!strpos($dt, "-")){
                if($this->DBG){ echo "FUN->validarData:  nao tem traço  <br>\n";}
                return  false; //procura a barra
            }else{
                if($this->DBG){ echo "FUN->validarData:  trocar traço por barra  <br>\n";}
                $data = implode('/',array_reverse(explode('-',$data)));
            }
        }


        if($this->DBG){ echo "FUN->validarData: data: $data  <br>\n";}
        $_arr2 = explode("/", $data);
        if($this->DBG){ echo "FUN->validarData: explode: ".print_r($_arr2,1)."  <br>\n"; }

        $dd = intval($_arr2[0]);
        $mm = intval($_arr2[1]);
        $aaaa = $_arr2[2];

        if(($dd < 1 or $dd > 31)){
            if($this->DBG){ echo "dia entre 1 e 31  <br>\n";}
            return false;
        }
        if(($mm < 1 or $mm > 12)){
            if($this->DBG){ echo "mes entre 1 e 12  <br>\n";}
            return false;
        }
        if(($aaaa < 1900 or $aaaa > 2100)){
            if($this->DBG){ echo "ano entre 1899 e 2100  <br>\n";}
            return false;
        }
        if($dd > 29 and $mm == 2){ //fev
            if($this->DBG){ echo "fev +29 dias  <br>\n";}
            return false;
        }
        if($dd == 31 and in_array($mm, array(4,6,9,11))){ //dia 31
            if($this->DBG){ echo "mes nao tem 31  <br>\n";}
            return false;
        }
        $arr_bisextos = array(2000, 2004, 2008, 2012, 2016, 2020, 2024, 2028, 2032, 2036, 2040, 2044, 2048, 2052);
        if($dd == 29 and $mm == 2 and !in_array($aaaa, $arr_bisextos)){ //bisextos
            if($this->DBG){ echo "29 nao bisexto  <br>\n";}
            return false;
        }

        return true;
    }

    public function normalizaData($str){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }

        if(strlen($str) < 9 ){
            if($this->DBG) echo "FUN->normalizaData: < 9 char; return null <br>\n";
            return null;
        }
        //separar data e hora
        if(strlen($str) > 10){
            if($this->DBG) echo "FUN->normalizaData: +10 char <br>\n";
            $data = substr($str,0,10);
            $hora = substr($str,11,8);

            return $this->normalizaData($data)." ".$this->normalizaHora($hora);
        }else{
            $failSafe = date('Y-m-d');

            $dt = preg_replace('/[^0-9]/', " ", $str);
            $arr = explode(' ',$dt);

            //procurar o ano
            if(strlen($arr[0]) == 4){
                if($this->DBG) echo "FUN->normalizaData: ano no 1º elemento<br>\n";
                $y = $arr[0];

                //normalmente está yyyy-mm-dd
                if($arr[1] > 12){ //era pra ser o mes aqui
                    if($this->DBG) echo "FUN->normalizaData: 2º elemento maior que 12 <br>\n";
                    if($arr[2] > 12){ //tem erro aqui
                        if($this->DBG) echo "FUN->normalizaData: 3º elemento maior que 12 ERRO <br>\n";
                        throw new Exception("normalizaData: ERRO Data inválida: $str ");
                        return $failSafe;
                    }else{
                        $m = $arr[2];
                        $d = $arr[1];
                    }
                }else{ //ok é o mes mesmo
                    $m = $arr[1];
                    $d = $arr[2];
                }
            }else if(strlen($arr[1]) == 4){ //mm-yyyy-dd
                if($this->DBG) echo "FUN->normalizaData: ano no 2º elemento<br>\n";
                $y = $arr[1];

                if($arr[0] > 12){ //era pra ser o mes aqui
                    if($this->DBG) echo "FUN->normalizaData: 1º elemento maior que 12 <br>\n";
                    if($arr[2] > 12){ //tem erro aqui
                        if($this->DBG) echo "FUN->normalizaData: 3º elemento maior que 12 ERRO <br>\n";
                        throw new Exception("normalizaData: ERRO Data inválida: $str ");
                        return $failSafe;
                    }else{
                        $m = $arr[2];
                        $d = $arr[0];
                    }
                }else{ //ok é o mes mesmo
                    $m = $arr[0];
                    $d = $arr[2];
                }
            }else if(strlen($arr[2]) == 4){ //dd/mm/yyyy
                if($this->DBG) echo "FUN->normalizaData: ano no 3º elemento<br>\n";
                $y = $arr[2];

                if($arr[1] > 12){ //era pra ser o mes aqui
                    if($this->DBG) echo "FUN->normalizaData: 2º elemento maior que 12 <br>\n";
                    if($arr[0] > 12){ //tem erro aqui
                        if($this->DBG) echo "FUN->normalizaData: 1º elemento maior que 12 ERRO <br>\n";
                        throw new Exception("normalizaData: ERRO Data inválida: $str ");
                        return $failSafe;
                    }else{
                        $m = $arr[0];
                        $d = $arr[1];
                    }
                }else{ //ok é o mes mesmo
                    $m = $arr[1];
                    $d = $arr[0];
                }
            }else{
                if($this->DBG) echo "FUN->normalizaData: ano nao encontrado <br>\n";
                throw new Exception("normalizaData: ERRO Data inválida: $str ");
                return $failSafe;
            }

            $dataFinal = $y."-".$this->pad($m,2)."-".$this->pad($d,2);
            if($this->validarData($dataFinal)){
                return $dataFinal;
            }

            return $failSafe;
        }
    }

    public function dataPadraoBR($str){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $str = $this->normalizaData($str);
        if(!$str) return null;

        if(strlen($str) > 10){
            $hora = substr($str,11,8);
        }
        $data = substr($str,0,10);

        $arr = explode('-',$data);
        $arr = array_reverse($arr);
        return implode('/',$arr).($hora ? " ".$hora:'');
    }

    public function dataVenceu($data){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(!$data) return true;
        $data = $this->normalizaData($data);
        $intData = strtr($data,array('-'=>''));
        if(date('Ymd') <= $intData){
            return false;
        }
        return true;
    }

    private function dataStrMktime($str){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }

        //mktime ($hour,$minute,$second,$month,$day,$year)
        //$failSafe = mktime (date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"));
        $failSafe = mktime (0,0,0,date("n"),date("j"),date("Y"));
        if(!$str){
            if($this->DBG) echo "FUN->dataStrMktime: sem parametro retornando failSafe: $failSafe<br>\n";
            return $failSafe;
        }

        $str = $this->normalizaData($str);
        if(strlen($str) > 10){
            if($this->DBG) echo "FUN->dataStrMktime: horas encontradas <br>\n";
            $data = substr($str,0,10);
            $hora = substr($str,11,8);

            $h = substr($hora,0,2);
            $m = substr($hora,3,2);
            $s = substr($hora,6,2);
        }else{
            if($this->DBG) echo "FUN->dataStrMktime: horas não encontradas, zerar <br>\n";
            $data = substr($str,0,10);

            $h = '00';
            $m = '00';
            $s = '00';
        }

        $arr_d = explode("-", $data);
        if($arr_d[1] and $arr_d[2] and $arr_d[0])
            return mktime($h,$m,$s,$arr_d[1],$arr_d[2],$arr_d[0]);
        return null;
    }

    /* dia da semana em texto */
    public function diaDaSemana($data){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(!$data) return null;
        $data = $this->normalizaData($data);
        $arr_d = explode("-", $data);
        $d = date('w',mktime(0,0,0,$arr_d[1],$arr_d[2],$arr_d[0]));
        return $this->arr_dias[$d];
    }

    /*$data1 menos a $data2*/
    public function diferencaDias($data1,$data2=null){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }

        $date1 = $this->dataStrMktime($data1);
        $date2 = $this->dataStrMktime($data2);
        if($this->DBG) echo "FUN->diferencaDias $date1 - $date2  <br>\n";

        $diff = $date1-$date2;
        if($this->DBG) echo "FUN->diferencaDias $diff <br>\n";

        $fullDays = floor($diff/(60*60*24));
        if($this->DBG) echo "FUN->diferencaDias $fullDays <br>\n";

        return $fullDays;
    }

    /*Hora1 menos a hora2*/
    /*
    segundo param é opcional, se não vier é distancia pra hj
    negativo para dias passados, positivo para futuros
    */
    public function diferencaHoras($hora1, $hora2=false){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
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
    public function endTime($inp){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $fnp = time();
        $tp = $fnp - $inp;
        return $this->time_distance($tp);
    }

    public function getMes($mes){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(!$mes) return null;

        if(is_numeric($mes)){
            $mes = $this->pad($mes,2);

            foreach($this->arr_meses as $num => $nome){
                if($mes == $num)
                    return $nome;
            }
        }else{
            $mes = strtolower($mes);
            foreach($this->arr_meses as $num => $nome){
                if($mes == strtolower($nome))
                    return $num;
            }
        }
        return null;
    }

    public function time_distance($secs){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
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

        if($str) return $str;
        return "0 segundos";
    }

    public function getTempoDistancia($dt1,$dt2=false){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $unix1 = $this->dataStrMktime($dt1);
        $unix2 = $this->dataStrMktime($dt2);

        if($unix1 > $unix2){
            $dif = $unix1 - $unix2;
        }else{
            $dif = $unix2 - $unix1;
        }
        return $this->time_distance($dif);
    }

    /**DOCUMENTO**/

    /* Placa de Carro, já com padrão mercosul */
    function fixPlaca($placa){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if($this->DBG) echo "FUN->fixPlaca($placa) <br>\n";
        $placa = $this->tirarAcentos($placa);
        $placa = strtoupper($this->somenteLetrasENumeros($placa));
        if($this->DBG) echo "FUN->fixPlaca: $placa <br>\n";
        $p1 =  substr($placa, 0, 3);
        $p1 = preg_replace("/[^A-Z]/","",$p1);

        $p2 = substr($placa,3,1);
        $p2 = $this->somenteNumeros($p2);

        $p3 = substr($placa, 4, 1);
        $p3 = $this->somenteLetrasENumeros($p3);

        $p4 = substr($placa,5,2);
        $p4 = $this->somenteNumeros($p4);

        $placa = $p1."-".$p2.$p3.$p4;
        if(strlen($placa) != 8) return false;
        return $placa;
    }
    /*Limpar caracteres invalidos do email*/
    public function protectMail($email){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $email = $this->tirarAcentos($email);
    	$email = preg_replace("/[^A-Za-z0-9@.#+-_]/","",$email);
        $email = substr($email,0,255);

        return $email;
    }

    public function embaralhaEmail($email){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(!$email) return '';

        $a = 0;
        $fator = 3;
        $max = strlen($email);
        for($i=0;$i<=$max;$i++){
            $a++;
            if( preg_match("/[A-Za-z0-9]/",substr($email,$i,1)) ){
                if($a == $fator){
                    $email = substr_replace($email, '*',$i,1);
                    $a = 0;
                    if($fator == 3){
                        $fator = 2;
                    }else{
                        $fator = 3;
                    }
                }
            }else{
                $a--;
            }
        }

        return $email;
    }

    function protectNome($nome){ return $this->embaralhaNome($nome);}
    function embaralhaNome($nome){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(!$nome) return '';
        $arr = explode(' ',$nome);

        $firstName = array_shift($arr);
        $midleNames = '';
        $lastName = '';

        $firstName = substr($firstName,0,2).pad('',strlen($firstName)-2,'*');

        if(sizeof($arr)){
            $lastName = array_pop($arr);
            $lastName = substr($lastName,0,2).pad('',strlen($lastName)-2,'*');
        }

        if(sizeof($arr)){
            $arr2 = array();
            foreach ($arr as $key => $value) {
                $arr2[] = substr($value,0,1).pad('',strlen($value)-1,'*');
            }
            $midleNames = implode(' ',$arr2);
        }

        return $firstName.($midleNames ? ' '.$midleNames : '').($lastName ? ' '.$lastName : '');
    }

    /************/
    /*VALIDACOES*/
    /************/

    function validaEmail($value){ return $this->validarEmail($value); }
    function validarEmail($value){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    function validaNome($nome){ return $this->validarNome($nome); }
    function validarNome($nome){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if(!$nome) return false;
        if(sizeof(explode(' ',$nome)) < 2) return false;
        return true;
    }

    function validaCPFCNPJ($str=''){ return $this->validarCPFCNPJ($str);}
    function validarCPFCNPJ($str=''){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        if($this->validarCPF($str)) return true;
        if($this->validarCNPJ($str)) return true;
        return false;
    }

    /**
     * validarCPF
     * @param  [mixed] $cpf CPF com ou sem mascara
     * @return [bool] true para válido
     */
    function validaCPF($cpf){ return $this->validarCPF($cpf); }
    function validarCPF($cpf) {
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $DBG = false;
        if($DBG) echo $cpf."<br>";
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
        if($DBG) echo $cpf."<br>";

        if (strlen($cpf) != 11) {
            if($DBG) echo "nao tem 11 char<br>";
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            if($DBG) echo "nao tem numero<br>";
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;

            if($DBG) echo "DV $d<br>";

            if ($cpf[$c] != $d) {
                if($DBG) echo "invalido dv<br>";
                return false;
            }
        }
        if($DBG) echo "valido<br>";
        return true;
    }

    /**
     * validarCNPJ
     * @param  [mixed] $cnpj CNPJ com ou sem mascara
     * @return [bool]  true para valido
     */
    function validaCNPJ($cnpj){ return $this->validarCNPJ($cnpj); }
    function validarCNPJ($cnpj){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }

        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        if($this->DBG){ echo "somente numeros : $cnpj <br>\n"; }

        // Valida tamanho
        if (strlen($cnpj) != 14){
            if($this->DBG){ echo "não tem 14 dig <br>\n"; }
            return false;
        }else{
            if($this->DBG){ echo "tem 14 dig <br>\n"; }
        }

        // Verifica se todos os digitos sao iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

        // Valida primeiro di­gito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++){
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;

        // Valida segundo digito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++){
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    /**LINK**/

    function getLinkGet($arrRemove){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
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

    /*Permissão*/
    function requerirPermissao($perms='',$DBG = false){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        global $_SESSION,$appname,$caminho;

        $permitido = $this->testarPermissao($perms,$DBG);

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

    function testarPermissao($perms='',$DBG=false){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        global $_SESSION,$appname,$caminho;

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

    function requerirPermissao_old($perms=''){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        global $db,$_SESSION,$appname,$caminho;
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
}
}
