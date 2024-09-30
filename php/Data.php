<?php
/*! ---UTF-8---
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.1
* @copyleft
*/

/**TODO: documentar metodos
 *
 */

if (!class_exists('\Data')) {
class Data{
    public $DBG = false;
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

    /**
     * diaUtil Testa se é dia útil
     * @param  DateTime  data para testar
     * @return boolean se é ou não dia util
     */
    public function diaUtil(DateTime $date){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }

        $d = $date->format('w');
        if($this->DBG){ echo $date->format('d-m-Y')." $d ".$this->arr_dias[$d]."<br>"; }

        if($d != 0 and $d != 6) return true;

        return false;
    }

    public function isNotFeriado(DateTime $date){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."<xmp>" .print_r(func_get_args(),1)."</xmp><br>\n"; }
        $ano = $date->format('Y');
        $file = RES."feriados/".$ano.".json";
        if(!file_exists($file)) return true;
        $cont = file_get_contents($file);
        $cont = str_replace("\n"," ",$cont);
        $cont = preg_replace('/ +/', ' ', $cont);
        //debug($cont);
        $json = json_decode($cont,1);
        //debug($json);
        if($json[$date->format('dm')]){
            if($this->DBG){ echo "FERIADO!"; }
            //die;
            return false;
        }
        return true;
    }

    /**
     * proximoDiaUtil Testa se é dia útil
     * @param  mixed  $dtEnUs Y-m-d
     * @param  boolean $addOneDay  Adicionar um dia à data
     * @return mixed  DATE-EN-US Y-m-d
     */
    public function proximoDiaUtil($dtEnUs,$addOneDay=false){
        $repete = false;

        $date = new DateTime($dtEnUs);
        if($addOneDay) $date = date_add($date,new DateInterval('P1D'));
        $d = $this->diaUtil($date);
        $f = $this->isNotFeriado($date);

        if(!$d or !$f) $repete = true;
        $loopControl = 0;
        while($repete){
            if($this->DBG){ echo $date->format('d-m-Y')." d:$d f:$f<br>"; }
            $date = date_add($date,new DateInterval('P1D'));
            if($this->DBG){ echo $date->format('d-m-Y'); }

            $d = $this->diaUtil($date);
            $f = $this->isNotFeriado($date);
            if($d and $f) $repete = false;

            $loopControl++;
            if($loopControl > 10) die;
        }
         if($this->DBG){echo $date->format('d-m-Y')." d:$d f:$f<br>"; }

        return $date->format('Y-m-d');
    }

    function dataCNAB2EnUs($dtCANB){
        //le magik gambiarra by mv
        if(substr($dtCANB,4,2) > 80 ){
            $ano = 19; // isso vai dar problema em 2078
        }else{
            $ano = 20;
        }

        $dt = "$ano".substr($dtCANB,4,2).'-'.substr($dtCANB,2,2).'-'.substr($dtCANB,0,2);

        return $dt;
    }

    function dataEnUs2CNAB($dtEnUs){
        $ymd = explode(' ',$dtEnUs); //remover horas
        $x = explode('-',$ymd[0]);
        $dt = $x[2].$x[1].substr($x[0],2,2);
        return $dt;

    }

    function dataCNAB2PtBr($dtCANB){
        //le magik gambiarra by mv
        if(substr($dtCANB,4,2) > 80 ){
            $ano = 19; // isso vai dar problema em 2078
        }else{
            $ano = 20;
        }

        $dt = substr($dtCANB,0,2).'-'.substr($dtCANB,2,2)."-$ano".substr($dtCANB,4,2);

        return $dt;
    }

    function addDmaisUteis($dtEnUs, $dmais){
        for ($daysToAdd = $dmais; $daysToAdd > 0; $daysToAdd--) {
            $dtEnUs = $this->proximoDiaUtil($dtEnUs, true);
        }

        return $dtEnUs;
    }
}
}