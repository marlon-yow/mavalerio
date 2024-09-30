<?php
/*! ---UTF-8---
* @Autor MV https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.7 [2023-set-21]
* @copyleft
*/

if (!class_exists('\BancoDeDadosMysql')) {
class BancoDeDadosMysql{
    //MYSQL
    var $usuario;
    var $senha;
    var $servidor;
    var $database;
    var $porta;
    var $con;
    var $erro;
    public $DBG = false;
    public $auditoria = true;
    private $ECHO_SQL = false;

    private $erroFile = '';
    private $auditFile = '';

    public function  __construct($u,$p,$h,$d,$porta=3306){
        if($this->DBG){ echo "BancoDeDados->__construct: Nova instacia de banco <br>\n";}
        if(isset($_SERVER['HTTP_SQL']) and $_SERVER['HTTP_SQL'] == 1){
            echo "BancoDeDados->__construct: Echo SQL Ativo <br>\n";
            $this->ECHO_SQL = true;
        }

        $this->usuario = $u;
        $this->senha = $p;
        $this->servidor = $h;
        $this->database = $d;
        $this->porta = $porta;

        if(!$this->usuario or !$this->senha or !$this->servidor or !$this->database){
            echo "Não tenho todos os dados para conectar: (Usu:$u,Senha:$p,Host:$h,Db:$d,Porta:$porta)";
            die;
        }

        try{
            if($this->DBG){ echo "BancoDeDados->__construct: Conectar mysqli_connect($this->servidor, $this->usuario,$this->senha) <br>\n";}
            if(!$this->con = new \mysqli($this->servidor, $this->usuario,$this->senha,$this->database,$this->porta)){
                if($this->DBG){ echo "BancoDeDados->__construct: Não Conectado <br>\n";}
                trigger_error("BancoDeDados->__construct: Erro ao conectar ao servidor - " . mysqli_connect_error(), E_USER_WARNING);
            }
        }catch (\Exception $e1){
            trigger_error($e1->getMessage(), E_USER_WARNING);
        }

        /* parte de log */
        $this->testAndCreateFolder(LOG."db/");
        $this->erroFile = LOG."/db/".date('Y-m')."_MY_error.log";
        $this->auditFile = LOG."/db/".date('Y-m')."_MY_audit.log";
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

            $_text = "[".date('d/m/Y H:i:s')."]\n".
                      $param." \n".
                      $this->erro."\n".
                      $_SERVER['SCRIPT_FILENAME']."\n".
                      "-------------";
            file_put_contents($this->erroFile,$_text,FILE_APPEND);

            if($this->DBG){
                echo "Erro banco: <pre>";
                print_r($param);
                echo "</pre> ";
                print_r($this->erro);
                die;
            }
        }

        if($this->ECHO_SQL){
            echo "<br>".$param."<br>";
        }

        if($this->auditoria){
            /* AUDITORIAAAA, REPETE!, AUDITORIAAAAA*/
            /*todos os comandos que realmente mexem no banco INS,DEL,UPD*/
            $arr_audit = array("DELE"=>1,"INSE"=>1,"UPDA"=>1,"SELE"=>0,"BEGI"=>1);
            if( $arr_audit[substr($param,0,4)] ){
                if($this->DBG){ echo "<br>AUDITAR<br>"; }

                $_text = "---[".date('d/m/Y H:i:s')."] ".
                         $_SERVER['REMOTE_ADDR']." ".
                         $_SERVER['SCRIPT_FILENAME']."\n".
                         $param."\n";
                file_put_contents($this->auditFile,$_text,FILE_APPEND);
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
                        $statement->bind_param($prepares[0], ...$prepares[1]);
                        break;
                }
            }else{
                $erro = "Prepares não é um array!";
                return false;
            }

            if (!$statement->execute()) {
                trigger_error('Error executing MySQL query: ' . $statement->error);

                $this->erro = mysqli_error($this->con);

                $_text = "---[".date('d/m/Y H:i:s')."]\n".
                          $param." \n".
                          $this->erro."\n".
                          $statement->error."\n".
                          print_r($prepares,1)."\n".
                          $_SERVER['SCRIPT_FILENAME']."\n".
                          "-------------";
                file_put_contents($this->erroFile,$_text,FILE_APPEND);

                if($this->DBG){
                    echo "Erro banco: <pre>";
                    print_r($param);
                    print_r($prepares);
                    echo "</pre> ";
                    print_r($this->erro);
                    print_r($statement->error);
                    die;
                }
            }

            if($this->ECHO_SQL){
                echo "<br>".$param."<br>";
            }

            if($this->auditoria){
                /* AUDITORIAAAA, REPETE!, AUDITORIAAAAA*/
                /*todos os comandos que realmente mexem no banco INS,DEL,UPD*/
                $arr_audit = array("DELE"=>1,"INSE"=>1,"UPDA"=>1,"SELE"=>0,"BEGI"=>1);
                if( $arr_audit[substr($param,0,4)] ){
                    if($this->DBG){ echo "<br>AUDITAR<br>"; }

                    $_text = "---[".date('d/m/Y H:i:s')."] ".
                             $_SERVER['REMOTE_ADDR']." ".
                             $_SERVER['SCRIPT_FILENAME']."\n".
                             $param."\n".
                             print_r($prepares,1)."\n";
                    file_put_contents($this->auditFile,$_text,FILE_APPEND);
                }
                /**/
            }

            if(homeExplode(' ',$param) == "INSERT"){
                if($this->DBG){ echo "BancoDeDados->query: é Insert, chamar outra funcao <br>\n";}
                $id = $this->con->insert_id;
                if(!$id){
                    if($this->DBG){ echo "NAO TEM RETORNO DO ID INSERIDO, retornar true"; }
                    return true; //não auto numericos
                }
                if($this->DBG){ echo "ID INSERIDO: $id ";}
                return $id;
            }


            /* bind result variables */
            $res = $statement;
        }
        if($this->DBG){ echo "resposta: ".print_r($res,1);}
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

    public function sqldate($dt,$mask=null){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }
        if(is_null($dt)) return 'null';
        /*palavras que podem se enviadas para o banco ao inves de uma data*/
        $safe_words = array(
            array(0,3,'NOW'),
            array(0,4,'date'),
        );

        foreach ($safe_words as $key => $sw) {
            if( strtoupper(substr($dt,$sw[0],$sw[1])) == strtoupper($sw[2]) ){
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

        //se a mascara pede hora tb ou não
        if ($mask) {
            if (sizeof(explode(' ', $mask)) == 1) {
                $_arr = explode(' ', $dt);
                $dt =  $_arr[0];
            }
        }

        if( in_array(strlen($dt),array(10,16,19)) ){ //10, 16 ou 19 digitos YYYY-MM-DD HH:MI:SS
            $data = "'$dt'";
        }else{
            $data = 'null';
        }
        return $data;
    }

    public function validaData($dt){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__."( <br>\n" .implode(" <br>\n",func_get_args()) ."<br>\n ) <br>\n"; }
        if(is_null($dt)) return false;

        $data = $this->normalizaData($dt);
        $_arr = explode(" ", $data);
        $data = $_arr[0];

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
                        try{
                            if(!mkdir($lpath,0777,true)){
                                echo "Não consigo criar a pasta: $lpath ";
                            }
                        }catch(\Exception $e){

                        }
                        chown($lpath,"www-data");
                    }
                }
            }
        }catch (\Exception $e) {
            trigger_error($e->getMessage()." ".$pasta, E_USER_WARNING);
        }
    }

    /*
     * Funções andreliticas
     */


    function mysqli_field_name($result, $field_offset){
        $properties = mysqli_fetch_field_direct($result, $field_offset);
        return is_object($properties) ? $properties->name : null;
    }
    /**
     *
     * @return type
     */
    public function lastInsertedId(){
            return mysqli_insert_id($this->con);
    }
    /**
     * Quebra o singleton, usar com parcimonia;
     * @param type $con
     */
    public function setCon($con){
            $this->con = $con;
    }
    /**
     *
     * @param type $user
     * @param type $senha
     * @param type $dominio
     * @param type $bd
     * @return string
     */
    public function conecta($user, $senha, $dominio, $bd){
      if ($this->con = mysqli_connect($dominio,$user,$senha) ){
        mysqli_select_db($bd,$this->con) or die("Não conectou!");
        mysqli_set_charset( $this->con, 'utf8mb4');
        return 'Não conectou';
      } else{
          return - 1;
      }
    }
    public function criaCombo($selectUsado,$selecionado, $conn, $semSelecione = 0){

            $stmt = mysqli_query($this->con, $selectUsado);
            $nomeCombo=strtoupper(mysqli_field_name($stmt, 0));
            $valueCombo=$this->mysqli_field_name($stmt, 0);
            $descCombo=$this->mysqli_field_name($stmt, 1);

            echo ("<select name='$nomeCombo' id='$nomeCombo' >");
            if ($semSelecione == 0){
                echo ("<option value='' >Selecione</option>\n");
            }
            while($recordSet = mysqli_fetch_array($stmt)){

                $registro = $recordSet[$valueCombo];
                //echo "<br />registro = $registro <br />";
                if(strtoupper($selecionado) == strtoupper($registro))
                {
                        echo ("<option value='".$recordSet[$valueCombo]."' selected>".$recordSet[$descCombo]."</option>");
                }
                else
                {
                        echo ("<option value='".$recordSet[$valueCombo]."'>".$recordSet[$descCombo]."</option>\n"     );
                }
            }
            echo ("</select>");
            mysqli_free_result($stmt);
    }
    public function criaComboJavaScript($javaScript, $selectUsado,$selecionado, $conn, $semSelecione = 0){

            $stmt = mysqli_query($this->con, $selectUsado);

            $nomeCombo=strtoupper($this->mysqli_field_name($stmt, 0));
            $valueCombo=$this->mysqli_field_name($stmt, 0);
            $descCombo=$this->mysqli_field_name($stmt, 1);

            echo ("<select name='$nomeCombo' id='$nomeCombo'  $javaScript >");
            if ($semSelecione == 0){
                echo ("<option value='' >Selecione</option>\n");
            }
            while($recordSet = mysqli_fetch_array($stmt)){

                $registro = $recordSet[$valueCombo];
                //echo "<br />registro = $registro <br />";
                if(strtoupper($selecionado) == strtoupper($registro))
                {
                        echo ("<option value='".$recordSet[$valueCombo]."' selected>".$recordSet[$descCombo]."</option>");
                }
                else
                {
                        echo ("<option value='".$recordSet[$valueCombo]."'>".$recordSet[$descCombo]."</option>\n"     );
                }
            }
            echo ("</select>");
            mysqli_free_result($stmt);
    }
    public function modificarBD($query){
        $stmt = mysqli_query($this->con,$query);
        if(mysqli_error($this->con)){
            $this->mensagem = mysqli_error($this->con);
            $this->erro = 1;
        }
        return $stmt;
    }
    public function paginadoTransposto($param, $inicial, $final){
        if ($this->DBG) {
            echo __FILE__ . ":" . __LINE__ . " <br>\n" . __CLASS__ . "->" . __FUNCTION__ . "( <br>\n" . implode(" <br>\n", func_get_args()) . "<br>\n ) <br>\n";
        }
        $lim = intval($final - $inicial);
        if ($lim < 1) $lim = 30;

        $reparam = "SELECT *  FROM (
                    $param
                ) PAGINATED
                LIMIT $lim OFFSET $inicial";
        return $this->seleciona($reparam);
    }
    public function seleciona($query){
        //echo $query;
        $stmt = mysqli_query($this->con, $query);
        if (!$stmt) {
            $this->erro = mysqli_error($this->con);

            $erroFile = LOG . "/db/" . date('Ym') . "_erro.log";

            $_text = "[" . date('d/m/Y H:i:s') . "]\n" .
            $param . " \n" .
                $this->erro . "\n" .
                $_SERVER['SCRIPT_FILENAME'] . "\n" .
                "-------------";
            file_put_contents($erroFile, $_text, FILE_APPEND);

            if ($this->DBG) {
                echo "Erro banco: <pre>";
                print_r($param);
                echo "</pre> ";
                print_r($this->erro);
                die;
            }
        }else{

            $c = 0;
            for ($i = 0; $i < mysqli_num_fields($stmt); ++$i) {
                $v[$i] = $this->mysqli_field_name($stmt, $i);
            }

            while ($test = mysqli_fetch_array($stmt, MYSQLI_BOTH)){
                for ($i = 0; $i < mysqli_num_fields($stmt); ++$i) {
                    //$name = $v[$i];
                    $row[$v[$i]][$c] = $test[$i];
                }
                $c++;
            }
            // Free the query result
            mysqli_free_result($stmt);
            return $row;
        }
            return null;
    }
    public function insere ($query){
        return $this->modificarBD($query);
    }
    public function altera($query){
        return $this->modificarBD($query);
    }
}
}