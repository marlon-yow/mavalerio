<?php
/*! ---UTF-8---
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.3 [2022-fev-4]
* @copyleft GPLv3
*/

class BancoDeDadosMysql{
    //MYSQL
    var $usuario;
    var $senha;
    var $servidor;
    var $database;
    var $porta = 3306;
    var $con;
    var $erro;
    public $DBG = false;
    public $auditoria = true;

    private static $instance;

	public function  __construct($u,$p,$h,$d){
        if($this->DBG){ echo "BancoDeDados->__construct: Nova instacia de banco <br>\n";}
		$this->usuario = $u;
		$this->senha = $p;
		$this->servidor = $h;
		$this->database = $d;
        if(!$this->usuario or !$this->senha or !$this->servidor or !$this->database){
            echo "Não tenho todos os dados para conectar: ($u,$p,$h,$d)";
            die;
        }

        try{
            if($this->DBG){ echo "BancoDeDados->__construct: Conectar mysqli_connect($this->servidor, $this->usuario,$this->senha) <br>\n";}
            if(!$this->con = new  mysqli($this->servidor, $this->usuario,$this->senha,$this->database)){
                if($this->DBG){ echo "BancoDeDados->__construct: Não Conectado <br>\n";}
                trigger_error("BancoDeDados->__construct: Erro ao conectar ao servidor - " . mysqli_connect_error(), E_USER_WARNING);
            }
        }catch (Exception $e1){
            trigger_error($e1->getMessage(), E_USER_WARNING);
        }
	}

    /*public static function singleton(){
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    /*public function  __destruct(){
        try{
            if(!ocilogoff($this->con)){
                    $e = oci_error();
                    trigger_error("Erro ao desconectar do servidor usando mysqli - " . $e['message'], E_USER_WARNING);
            }
        }catch (Exception $e1){
            trigger_error($e1->getMessage(), E_USER_WARNING);
        }
    }*/

    public function getCon(){ return $this->con; }

    public function query($param,$prepares=array()){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }

        if(sizeof($prepares)) return $this->queryPrepared($param,$prepares);

        $sql = mysqli_query($this->con, $param);
        if(!$sql){
            $this->erro = mysqli_error($this->con);

            $erroFile = LOG."/db/".date('Ym')."_erro.log";

            $_text = "[".date('d/m/Y H:i:s')."]\n".
                      $param." \n".
                      $this->erro."\n".
                      $_SERVER['SCRIPT_FILENAME']."\n".
                      "-------------";
            file_put_contents($erroFile,$_text,FILE_APPEND);

            if($this->DBG){
                echo "Erro banco: <pre>";
                print_r($param);
                echo "</pre> ";
                print_r($this->erro);
                die;
            }
        }

        if($this->auditoria){
            /* AUDITORIAAAA, REPETE!, AUDITORIAAAAA*/
            /*todos os comandos que realmente mexem no banco INS,DEL,UPD*/
            $arr_audit = array("DELE"=>1,"INSE"=>1,"UPDA"=>1,"SELE"=>0,"BEGI"=>1);
            if( $arr_audit[substr($param,0,4)] ){
                $auditFile = LOG."/db/".date('Ym')."_audit.log";

                $_text = "[".date('d/m/Y H:i:s')."] ".
                         $_SERVER['REMOTE_ADDR']." ".
                         $_SERVER['SCRIPT_FILENAME']." ".
                         $param."\n";
                file_put_contents($auditFile,$_text,FILE_APPEND);
            }
            /**/
        }

        if(homeExplode(' ',$param) == "INSERT"){
            if($this->DBG){ echo "BancoDeDados->query: é Insert, chamar outra funcao <br>\n";}
            $id = $this->con->insert_id;
            if(!$id) return true; //não auto numericos
            return $id;
        }
        return $sql;
    }

    public function queryPrepared($param, $prepares = array() ){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }
        $res = null;
        $statement = $this->con->stmt_init();
        if ($statement->prepare($param)) {

            if(is_array($prepares)){
                //sinto uma vergonha imensa disso, mas não consigo resolver de outra forma
                switch (strlen($prepares[0])) {
                    case '1':
                        $statement->bind_param($prepares[0], $prepares[1]);
                        break;
                    default:
                        echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__;
                        echo "quantidade de param: ".strlen($prepares[0])." Não suportada. Edite o case";
                        break;
                }
            }

            if (!$statement->execute()) {
                trigger_error('Error executing MySQL query: ' . $statement->error);
            }

            /* bind result variables */
            $res = $statement;
        }
        //$res = $prepared->execute();
        return $res;
    }

    public function normalizaData($data){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }

        if(!$data) return null;
        if($this->DBG) echo "<pre>normalizaData $data</pre>";
        $data = protect($data);
        $arr = explode(' ',$data);

        $data = $arr[0];
        $data = str_replace("/", "-", $data);

        if((strpos($data, "-") != 4)){ //está DD/MM/YYYY
            $data = implode("-", array_reverse(explode("-", $data)));
        }
        $data = $data.($arr[1] ? ' '.$arr[1] : ' 00:00:00');
        if($this->DBG) echo "<pre>normalizada: $data</pre>";
        return $data;
    }

    public function sqlDate($dt){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }

        /*palavras que podem se enviadas para o banco ao inves de uma data*/
        $safe_words = array(
            array(0,3,'NOW')
        );

        foreach ($safe_words as $key => $sw) {
            if( substr($dt,$sw[0],$sw[1]) == $sw[2] ){
                return $dt;
            }
        }
        /**/

        $dt = $this->normalizaData($dt);

        if(!$this->validaData($dt)){
            if($this->DBG) echo "$dt Invalida ";
            return 'null';
        }
        $dt = trim($dt);

        if( in_array(strlen($dt),array(10,16,19)) ){ //10, 16 ou 19 digitos YYYY-MM-DD HH:MI:SS
            $data = "'$dt'";
        }else{
            $data = 'null';
        }
        return $data;
    }

    public function validaData($dt){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }

        $_arr = explode(" ", $dt);
        $data = $_arr[0];
        $data = $this->normalizaData($data);
        $_arr2 = explode("-", $data);
        if(!strpos($data, "-")) return  false; //procura hifen

        $aaaa = intval($_arr2[0]);
        $mm = intval($_arr2[1]);
        $dd = $_arr2[2];

        if(($dd < 1 or $dd > 31)) return false;
        if(($mm < 1 or $mm > 12)) return false;
        if(($aaaa < 1899 or $aaaa > 2100)) return false;
        if($dd > 29 and $mm == 2) return false; //fev
        if($dd == 31 and in_array($mm, array(4,6,9,11))) return false; //dia 31
        $arr_bisextos = array(2000, 2004, 2008, 2012, 2016, 2020, 2024, 2028, 2032, 2036, 2040, 2044, 2048, 2052);
        if($dd == 29 and $mm == 2 and !in_array($aaaa, $arr_bisextos)) return false; //bisextos

        $hora = $_arr[1];
        if($hora) return $this->validaHora($hora);

        return true;
    }

    public function validaHora($hr){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }
        if(!$hr) return false;

        if( strlen($hr) == 19 ){ // 19 digitos YYYY-MM-DD HH:MI:SS
            $_arr = explode(" ", $hr);
            $hr = $arr[1];
        }

        if(strlen($hr) != 8 and strlen($hr) != 5 ){
            return false;
        }

        $arr_hr = explode(":",$hr);
        $h = $arr_hr[0];
        $m = $arr_hr[1];
        $s = $arr_hr[2];

        if(($h < 0) or ($h > 23)) return false;
        if(($m < 0) or ($m > 59)) return false;
        if($s) if(($s < 0) or ($s > 59)) return false;

        return true;
    }

    public function sqlnumero($numero, $formatarFloat=false){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }

        if(!$numero) return 0;
        if($this->DBG) echo "<pre>Convertendo: $numero\n";

        $numero_post = preg_replace( '/[^0-9]/', '', $numero );
        if($this->DBG) echo "\nremover nao numeros: $numero_post\n";
        if(!$numero_post){
            return 0;
        }

        if($numero_post == $numero){
            if($this->DBG) echo "\nnao existe separador, retornar: $numero_post\n";
            return $numero_post;
        }

        $arr_separadores = array();
        $separador='';
        $separadorIdx = 0;
        $arrNumeros = array();
        if($this->DBG) echo "\nvarrer verificando\n";
        for( $i=0;$i<strlen($numero);$i++){
            $item = substr($numero,$i,1);
            if($this->DBG) echo "\nitem: $item\n";
            if(!is_numeric($item)){
                if($this->DBG) echo "\nnao numerico\n";
                $arr_separadores[$item]++;
                $separador = $item;
                $separadorIdx = $i;
            }else{
                $arrNumeros[$i] = $item;
                if($this->DBG) echo "\nnumerico\n";
            }
        }

        if($arr_separadores[$separador]==1){ //se for separador de milhar vai dar erro
            $arrNumeros[$separadorIdx] = '.';
            if($this->DBG) echo "\nadd ponto na casa $separadorIdx\n";
        }

        ksort($arrNumeros);
        //debug($arrNumeros);

        $numFinal = implode('',$arrNumeros);

        if($this->DBG) echo "\nfinal $numFinal\n";

        return $numFinal;
        //iterar o string item por item
        //contar os caracteres não numericos
        //marcar o ultimo caracter
        //vai ser ele, pegar a posicao dele,
        //remover os outros caracteres nao numericos
        //inserir um ponto no lugar dele
        //se nao tiver caracter nao numerico, devolver como inteiro msm

        //usar is_numeric('.')




        if($formatarFloat){
            //verificar quais as posicoes de ponto e de virgua e pegar o ultimo
            $ponto = strpos($numero,',');
            $virgula = strpos($numero,'.');
            if($ponto > $virgula){
                $decSepPos = $ponto;
                $decSep = 'ponto';
            }else if($ponto < $virgula){
                $decSepPos = $virgula;
                $decSep = 'virgula';
            }else{
                $decSepPos = -1;
                $decSep = 'nenhum';
            }
            if($this->DBG) echo "Posicao Ponto: $ponto \nPosicao Virgula: $virgula \nSeparador Escolhido: $decSep Posicao dele: $decSepPos ";
            //quantos caracteres tem depois do separador
            $after = substr($numero,$decSepPos);
            if($this->DBG) echo "\napos o separador: $after ";
            $sizeAfter = strlen($after);
            if($this->DBG) echo "\n $after  tem $sizeAfter caracteres";
            if($sizeAfter == 2){
                if($this->DBG) echo "\nAdd um zero no numero";
                $numero = $numero."0";
                $ehFloat = true;
            }else if($sizeAfter == 3){
                if($this->DBG) echo "\nnumero ja possui 2 casas";
                $ehFloat = true;
            }else{
                if($this->DBG) echo "\nnumero nao possui decimal";
                $ehFloat = false;
            }
        }

        $numero = preg_replace( '/[^0-9]/', '', $numero );

        if($ehFloat and $formatarFloat){
            if($this->DBG) echo "\nnumero eh float, cortar 2 casas e separar com ponto";
            $inteiros = substr($numero, 0, strlen($numero)-2);
            $centavos = substr($numero, strlen($numero)-2, 2);
            $numero = $inteiros.".".$centavos;
        }
        if($this->DBG) echo "\nnumero final: $numero</pre>";
        return $numero;
    }

    public function getQuantReg($param){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }
        $parampg = "SELECT count(*) AS NUMERO FROM ($param) TC";

        $sql_pg = $this->query($parampg);
        $rw = $this->fetch($sql_pg);
        return intval($rw["NUMERO"]);
    }

    public function paginado($param, $inicial, $final){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }
        $lim = intval($final - $inicial);
        if($lim < 1) $lim = 30;

        $reparam = "SELECT *  FROM (
                        $param
                    ) PAGINATED
                    LIMIT $lim OFFSET $inicial";
        return $this->query($reparam);
    }

    public function fetch($sql){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n<xmp>" .print_r(func_get_args()) ."</xmp>\n ) <br>\n"; }
        if(gettype($sql) != 'object'){
            if($this->DBG){ echo "BancoDeDados->fetch: <br>\n";}
            echo "<hr>Tentado processar dados com tipo errado: ".gettype($sql)." verificar consulta";
            die;
        }
        return $rw = $sql->fetch_assoc();
    }

    /*public function getNextId($sequence){
        $param_pk = "SELECT $sequence AS PK FROM DUAL";
        $sql = $this->query($param_pk);
        $row = oci_fetch_assoc($sql);
        return $row['PK'];
    }*/
}
