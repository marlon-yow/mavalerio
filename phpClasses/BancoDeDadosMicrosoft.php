<?php
/*! ---UTF-8---
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.5 [2022-out-07]
* @copyleft
*/

namespace mavalerio\phpClasses;

if (!class_exists('mavalerio\phpClasses\BancoDeDadosMicrosoft')) {
class BancoDeDadosMicrosoft{
	// Variables to tune the retry logic.
    var $connectionTimeoutSeconds = 30;  // Default of 15 seconds is too short over the Internet, sometimes.
    var $maxCountTriesConnectAndQuery = 3;  // You can adjust the various retry count values.
    var $secondsBetweenRetries = 4;  // Simple retry strategy.
    var $errNo = 0;
	var $arrayOfTransientErrors = array('08001', '08002', '08003', '08004', '08007', '08S01');
	//
    var $usuario;
    var $senha;
    var $servidor;
    var $database;
    var $porta;
    var $con;
    var $erro;
    public $DBG = false;
    public $auditoria = false;

    private $erroFile = '';
    private $auditFile = '';

	public function  __construct($host,$usuario,$senha,$porta,$base){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->__construct: Nova instacia de banco <br>\n";}

        $this->servidor = $host;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->porta = $porta;
        $this->database = $base;

        if($this->porta){
            $this->servidor = $this->servidor.', '.$this->porta;
        }

        $this->tryToConnect();

        /* parte de log */
        $this->testAndCreateFolder(LOG."db/");
        $this->erroFile = LOG."/db/".date('Y-m')."_MS_error.log";
        $this->auditFile = LOG."/db/".date('Y-m')."_MS_audit.log";
    }

    public function tryToConnect(){
        $connectionOptions = array(
			"Database"=>$this->database,
            "Uid"=>$this->usuario,
			"PWD"=>$this->senha,
			"LoginTimeout" => $this->connectionTimeoutSeconds,
            "TrustServerCertificate" => "yes"
		);

        try{
            if($this->DBG){ echo "BancoDeDadosMicrosoft->__construct: Conectar sqlsrv_connect($this->servidor, $this->usuario,$this->senha) <br>\n";}
			for ($cc = 1; $cc <= $this->maxCountTriesConnectAndQuery; $cc++) {
				// [A.2] Connect, which proceeds to issue a query command.
				$this->con = sqlsrv_connect( $this->servidor, $connectionOptions);

				if($this->con){
					break;
				}else{
					// [A.4] Check whether the error code is on the list of allowed transients.
					$isTransientError = false;
					$errorCode = '';

					if (($errors = sqlsrv_errors()) != null) {
						foreach ($errors as $error) {
							$errorCode = $error['code'];
							$isTransientError = in_array($errorCode, $this->arrayOfTransientErrors);
							if ($isTransientError) {
								break;
							}
						}
					}

					if (!$isTransientError) {
						// it is a static persistent error...
						echo("Persistent error suffered with error code = $errorCode. Program will terminate.");
						echo "<br>";
                        debug($errors);
						// [A.5] Either the connection attempt or the query command attempt suffered a persistent error condition.
						// Break the loop, let the hopeless program end.
						exit(0);
					}

					// [A.6] It is a transient error from an attempt to issue a query command.
					// So let this method reloop and try again. However, we recommend that the new query
					// attempt should start at the beginning and establish a new connection.
					if ($cc >= $this->maxCountTriesConnectAndQuery) {
						echo "Transient errors suffered in too many retries - $cc. Program will terminate.";
						echo "<br>";
						exit(0);
					}
					echo("Transient error encountered with error code = $errorCode. Program might retry by itself.");
					echo "<br>";
					echo "$cc attempts so far. Might retry.";
					echo "<br>";
					// A very simple retry strategy, a brief pause before looping.
					sleep(1*$this->secondsBetweenRetries);
				}
				// [A.3] All has gone well, so let the program end.
			}


            if(!$this->con){
                if($this->DBG){ echo "BancoDeDadosMicrosoft->__construct: Não Conectado <br>\n";}

                trigger_error("BancoDeDadosMicrosoft->__construct: Erro ao conectar ao servidor - " . print_r( sqlsrv_errors(), true), E_USER_WARNING);
            }else{
                if($this->DBG){ echo "BancoDeDadosMicrosoft->__construct: Conectado, na base $this->database <br>\n";}
            }
        }catch (Exception $e1){
            trigger_error($e1->getMessage(), E_USER_WARNING);
        }

    }

    public function getCon(){ return $this->con; }
    public function setDatabase($dbName=''){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->setDatabase: $dbName <br>\n";}
        $this->database = $dbName;
    }

    public function query($query,$nonBufer=false){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->query: $param <br>\n";}

        if($nonBufer){
            $options["Scrollable"] = SQLSRV_CURSOR_KEYSET;
        }else{
            $options["Scrollable"] = 'buffered';
        }

        $params = array();
        $sql = sqlsrv_query($this->con, $query, $params, $options);
        if(!$sql){
            $this->erro = sqlsrv_errors();
            //debug($this->erro);

            $_text = "---[".date('d/m/Y H:i:s')."]\n".
                      $param." \n".
                      print_r($this->erro,1)."\n".
                      $_SERVER['SCRIPT_FILENAME']."\n".
                      $this->database."\n".
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

        if($this->auditoria){
            /* AUDITORIAAAA, REPETE!, AUDITORIAAAAA*/
            /*todos os comandos que realmente mexem no banco INS,DEL,UPD*/
            $arr_audit = array("DELE"=>1,"INSE"=>1,"UPDA"=>1,"SELE"=>0,"BEGI"=>1);
            if( $arr_audit[substr($param,0,4)] ){

                $_text = "---[".date('d/m/Y H:i:s')."] ".
                         $_SERVER['REMOTE_ADDR']." ".
                         $_SERVER['SCRIPT_FILENAME']." ".
                         $param."\n";
                file_put_contents($this->auditFile,$_text,FILE_APPEND);
            }
            /**/
        }

        /*if(homeExplode(' ',$param) == "INSERT"){
            if($this->DBG){ echo "BancoDeDadosMicrosoft->query: é Insert, chamar outra funcao <br>\n";}
            return $this->con->insert_id;
        }*/
        return $sql;
    }

    public function normalizaData($data){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->normalizaData: $data <br>\n";}

        if(!$data) return null;
        if($this->DBG) echo "<pre>normalizaData $data</pre>";
        $data = protect($data);
        $arr = explode(' ',$data);

        $data = $arr[0];
        $data = str_replace("/", "-", $data);

        if((strpos($data, "-") != 4)){ //está DD/MM/YYYY
            $data = implode("/", array_reverse(explode("-", $data)));
        }
        //$data = $data.($arr[1] ? ' '.$arr[1] : ' 00:00:00');
        if($this->DBG) echo "<pre>normalizada: $data</pre>";
        return $data;
    }

    public function sqlDate($dt){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->sqlDate: $dt <br>\n";}

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
        if($this->DBG){ echo "BancoDeDadosMicrosoft->validaData: $dt <br>\n";}
        if(is_null($dt)) return false;
        $_arr = explode(" ", $dt);
        $data = $_arr[0];
        $data = $this->normalizaData($data);
        if(is_null($data)) return '';
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
        if($this->DBG){ echo "BancoDeDadosMicrosoft->validaHora: $hr <br>\n";}
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
    /*
    public function sqlnumero($numero, $formatarFloat=false){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->sqlnumero: $numero <br>\n";}

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
        if($this->DBG){ echo "BancoDeDadosMicrosoft->getQuantReg: $param <br>\n";}
        $parampg = "SELECT count(*) AS NUMERO FROM ($param) TC";

        $sql_pg = $this->query($parampg);
        $rw = $this->fetch($sql_pg);
        return intval($rw["NUMERO"]);
    }

    public function paginado($param, $inicial, $final){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->paginado: $param, $inicial, $final <br>\n";}
        $lim = intval($final - $inicial);
        if($lim < 1) $lim = 30;

        $reparam = "SELECT *  FROM (
                        $param
                    ) PAGINATED
                    LIMIT $lim OFFSET $inicial";
        return $this->query($reparam);
    }
    */
    public function fetch($sql){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->fetch: <br>\n";}

        $fetch = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
        //var_dump( sqlsrv_errors() );
        return $fetch;
        //return sqlsrv_fetch_array($sql);
    }

    public function refetch($sql,$i){
        if($this->DBG){ echo "BancoDeDadosMicrosoft->fetch: <br>\n";}

        $fetch = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC, SQLSRV_SCROLL_ABSOLUTE, $i);
        //var_dump( sqlsrv_errors() );
        return $fetch;
        //return sqlsrv_fetch_array($sql);
    }

    public function getResultSize($sql){
        return sqlsrv_num_rows($sql);
    }

    /*public function getNextId($sequence){
        $param_pk = "SELECT $sequence AS PK FROM DUAL";
        $sql = $this->query($param_pk);
        $row = oci_fetch_assoc($sql);
        return $row['PK'];
    }*/

    /**
     * Funcao verifica se o banco de dados enviado por parametro existe na conexão atual
     * @param  string $bancoDeDados nome do banco (acho que é case sensitive)
     * @return int 1 para existente, 0 para não existente
     * @exception morre na execução com erro
     */
    public function testeBancoExiste($bancoDeDados){
        $param = "SELECT (CASE WHEN DB_ID('$bancoDeDados') IS NOT NULL THEN 1 ELSE 0 END) AS EXISTE_DB";
        $sql = $this->query($param);
        if(!$sql){
            echo "ERRO SQL";
            echo $param;
            die;
        }
        $row = $this->fetch($sql);
        return $row['EXISTE_DB'];
    }

    /**
     * troca o banco de dados padrao da conexao
     * @param  string $bancoDeDados nome do banco (acho que é case sensitive)
     * @return bool/resource retorno da funcao sqlsrv_query
     * @exception morre na execução com erro
     */
    public function trocarBancoDeDados($bancoDeDados){
        $param = " USE $bancoDeDados; ";
        $sql = $this->query($param);
        if(!$sql){
            echo "ERRO SQL";
            echo $param;
            die;
        }
        return $sql;
    }

    /**
     * testa se a View Existe
     * @param  string $nomeView nome da View
     * @return int 1 para existente, 0 para não
     */
    public function testeViewExiste($nomeView){
        $param = " select COUNT(*) AS EXISTE_VIEW from sys.views v where name='$nomeView' ";
        $sql = $this->query($param);
        if(!$sql){
            echo "ERRO SQL";
            echo $param;
            die;
        }
        $row = $this->fetch($sql);
        return $row['EXISTE_VIEW'];
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
}
}
