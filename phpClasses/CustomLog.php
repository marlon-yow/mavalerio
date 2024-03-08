<?php

/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.3 [2022-out-07]
* @copyleft
*/

namespace mavalerio\phpClasses;

if (!class_exists('mavalerio\phpClasses\CustomLog')) {

/*

    === 1- instanciar a classe com ===
    $CustomLog = new CustomLog($logFolder,$logfile,$errorlogfile);

    $logFolder=> pasta onde serão salvos os logs (ele cria a pasta se não existir)<br>
        redirecione por padrão para  $logFolder = "/var/bancoDeDados/$appName/logs/";
    $logfile = date('Y-m-d')."_".NOME DO ARQUIVO COMUM.".log";
    $errorlogfile = date('Y-m-d')."_".NOME DO ARUIVO DE ERRO."_ERROS.log";


    === 2- gravar uma linha no arquivo ===
    $CustomLog->loggar(TEXTO, COLOCA DATA?, PULAR LINHA?, DAR FEDDBAK EM TELA?)

    === 3- limpar cache de erro no fim do processamento de uma linha ===
    $CustomLog->clearError()

    === 4- gravar cache de erro se der erro ===
    $CustomLog->saveErrorLog();

*/

    class CustomLog{
        var $logfile = '';
        var $logFileError ='';
        var $DBG = false;
        public $inp = 0;

        var $caheLog = '';

        public function  __construct($logFolder='',$logfile='', $logFileError=''){
            $this->logfile = $logFolder.$logfile;
            $this->logFileError = $logFolder.$logFileError;

            if($logFolder){
                $this->testAndCreateFolder($logFolder);
            }

            if($this->DBG){
                echo "Iniciada CLasse CustomLog: <br>\nPasta: $logFolder <br>\nlogfile: $logfile <br>\nlogFileError: $logFileError <br>\n";
            }

            $this->loggar('----',0,1,$this->DBG);
        }

        public function  __destruct(){
            $x = $this->endTime($this->inp);
            $this->loggar($x,0,1,$this->DBG);
        }

        function loggar($txt,$putdate=null,$lfcr=true,$echo=false){
            if(!$this->logfile){
                echo "defina o logfile";
                die;
            }

            if(!$this->inp){
                $this->inp = hrtime(1);
            }

            /*if(!$this->logFileError){
                echo "defina o logFileError";
                die;
            }*/

            if($putdate){
                $txt = "[".date('d/m/Y H:i:s')."] ".$txt;
            }

            if($lfcr){
                $txt .= "\n";
            }

            if($echo){ echo $txt; flush(); }

            if($this->logFileError){
                $this->caheLog .= $txt;
            }

            file_put_contents($this->logfile,$txt,FILE_APPEND);
        }

        function clearError(){
            $this->caheLog = '';
        }

        function saveErrorLog(){
            if(!$this->logFileError){
                echo "defina o logFileError";
                die;
            }

            file_put_contents($this->logFileError,$this->caheLog,FILE_APPEND);
            $this->caheLog = '';
        }



        /* funcoes internas */
        private function testAndCreateFolder($pasta){
            try{
                if (!file_exists($pasta) and !is_dir($pasta)) {
                    $arr = explode('/',$pasta);
                    $path = '';
                    foreach($arr as $i => $p){
                        $lpath .= "/".$p;
                        if($this->DBG) echo "$i,$lpath";
                        if(file_exists($lpath)){
                            if($this->DBG) echo " existe, ";
                            if(is_dir($lpath)){
                                if($this->DBG) echo " é pasta <br>";
                            }else{
                                if($this->DBG) echo " <b>não pasta</b> <br>";
                            }
                        }else{
                            if($this->DBG) echo " <b>não existe</b> <br>";
                            mkdir($lpath,0777,true);
                            chown($lpath,"www-data");
                        }
                    }
                }
            }catch (Exception $e) {
                trigger_error($e->getMessage()." ".$pasta, E_USER_WARNING);
            }
        }

        private function endTime($inp){
            $fnp = hrtime(1);
            $tp = $fnp - $inp;
            $tp = intval($tp / 1e+6); //nanoseconds to milliseconds
            return $this->time_elapsed_B($tp);
        }

        private function time_elapsed_B($milisecs){
            if($milisecs < 1000){
                return "$milisecs milisegundos";
            }

            $secs = intval($milisecs / 1000);

            $bit = array(
                ' ano'        => $secs / 31556926 % 12,
                ' semana'     => $secs / 604800 % 52,
                ' dia'        => $secs / 86400 % 7,
                ' hora'       => $secs / 3600 % 24,
                ' minuto'     => $secs / 60 % 60,
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
    }
}