<?php
/*! ---UTF-8---
* @version 0.0.0.7 [2023-maio-23]
* @copyleft
*/

namespace mavalerio\phpClasses;

use Exception;

if (!class_exists('mavalerio\phpClasses\FUN')) {
    class FUN {
        public $DBG = false;
        public $mensagem = array();

        public function  __construct() {
            if (isset($_SERVER['HTTP_FUN']) and $_SERVER['HTTP_FUN'] == 1) {
                echo "FUN->__construct: FUN debug Ativo <br>\n";
                $this->DBG = true;
            }
        }

        private function echoDebug($f, $l, $c, $f2, $a) {
            echo $f . ":" . $l . " <br>\n" .
                $c . "->" . $f2 . ($a ? "<pre>" . print_r($a, 1) . "</pre>" : '')
                . "<br>\n";
        }

        public function setDBG($d) {
            $this->DBG = $d;
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
        }

        public function setMensagem($value = '') {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $this->mensagem[] = $value;
        }

        /**
         * endExplode:
         * Retorna o último elemento de um array resultante da explosão de uma string usando um delimitador.
         * @param string $arg O delimitador usado para explodir a string (opcional, padrão é um espaço).
         * @param string $var A string que será explodida (opcional, padrão é uma string vazia).
         * @return string O último elemento do array resultante da explosão da string.
         */
        public function endExplode($arg, $var) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $arg = ($arg ?: ' ');
            $var = ($var ?: '');
            $_E = explode($arg, $var);
            return end($_E);
        }

        /**
         * firstword:
         * @param string $nomew A string da qual a primeira palavra será extraída.
         * @param string $arg O delimitador usado para separar as palavras na string (opcional, padrão é um espaço).
         * @return string A primeira palavra da string
         */
        public function firstword($nomew, $arg = ' ') {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $nomew = ($nomew ?: '');
            $arr = explode($arg, $nomew);
            return $arr[0];
        }

        /**
         * homeExplode
         * Retorna o último elemento de um array resultante da explosão de uma string usando um delimitador.
         * @param string $arg O delimitador usado para explodir a string (opcional, padrão é um espaço).
         * @param string $var A string que será explodida (opcional, padrão é uma string vazia).
         * @return string O último elemento do array resultante da explosão da string.
         */
        public function homeExplode($arg, $var) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $arg = ($arg ?: ' ');
            $var = ($var ?: '');
            $_E = explode($arg, $var);
            return $_E !== '' ? reset($_E) : '';
        }

        /**
         * pad
         * Preenche uma string com um caractere especificado para alcançar um comprimento desejado.
         * @param string|int $var A string ou valor numérico que será preenchido.
         * @param int $numChar O comprimento final da string após o preenchimento.
         * @param string $pad O caractere usado para preenchimento (opcional, padrão é '0').
         * @param int $pad_type A constante que define onde o preenchimento será aplicado (opcional, padrão é STR_PAD_LEFT). Valores possíveis: STR_PAD_BOTH, STR_PAD_LEFT, STR_PAD_RIGHT.
         * @return string A string resultante após o preenchimento.
         */
        public function pad($var, $numChar, $pad = '0', $pad_type = STR_PAD_LEFT) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            //STR_PAD_BOTH STR_PAD_LEFT STR_PAD_RIGHT
            return str_pad(($var ?: ''), $numChar, $pad, $pad_type);
        }

        /**
         * rpad
         * Preenche uma string à direita com espaços ou com um caractere especificado para alcançar um comprimento desejado.
         * @param string $texto A string que será preenchida à direita.
         * @param int $qtdChar O comprimento final da string após o preenchimento.
         * @param bool $space Indica se deve ser usado espaço como caractere de preenchimento (opcional, padrão é false).
         * @return string A string resultante após o preenchimento à direita.
         */
        public function rpad($texto, $qtdChar, $space = false) {
            $texto = substr($texto, 0, $qtdChar);
            if ($space) return "     " . str_pad($texto, $qtdChar, " ", STR_PAD_RIGHT);
            return str_pad($texto, $qtdChar, " ", STR_PAD_RIGHT);
        }

        /**
         * lpad
         * Preenche uma string à esquerda com espaços ou com um caractere especificado para alcançar um comprimento desejado.
         * @param string $texto A string que será preenchida à esquerda.
         * @param int $qtdChar O comprimento final da string após o preenchimento.
         * @param bool $space Indica se deve ser usado espaço como caractere de preenchimento (opcional, padrão é false).
         * @return string A string resultante após o preenchimento à esquerda.
         */
        public function lpad($texto, $qtdChar, $space = false) {
            $texto = substr($texto, 0, $qtdChar);
            if ($space) return str_pad($texto, $qtdChar, " ", STR_PAD_LEFT) . "     ";
            return str_pad($texto, $qtdChar, " ", STR_PAD_LEFT);
        }

        /**
         * repeat
         * Repete uma string para alcançar um comprimento desejado.
         * @param string $str A string que vai ser repetida.
         * @param int $qtdChar O comprimento final desejado da string.
         * @return string A string resultante após repetição para alcançar o comprimento especificado.
         */
        public function repeat($str, $qtdChar) {
            $txt = "";
            $size = strlen($str);
            if ($size > $qtdChar) {
                $txt = substr($str, 0, $qtdChar);
            } else {
                $it = floor($qtdChar / $size); //quantas vezes cabe
                for ($i = 1; $i <= $it; $i++) {
                    $txt .= $str;
                }
                //se não terminou de preencher, pega um pedaço para finalizar
                $dif = strlen($txt);
                if ($dif != $qtdChar) {
                    $txt .= substr($str, 0, ($qtdChar - $dif));
                }
            }
            return $txt;
        }

        /**
         * protect
         * Verifica o tipo da variável e toma uma ação após a verificação.
         * @param mixed $var A variável a ser verificada/protegida.
         * @return mixed A variável protegida após remoção de caracteres suspeitos ou clonagem.
         */
        public function protect($var) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());

            $sec_arr = array("'" => '', '"' => "");
            $varType = gettype($var);

            if (in_array($varType, array('string', 'integer', 'double', 'float', 'NULL'))) {
                if (is_null($var)) return '';
                return strtr($var, $sec_arr);
            } else if ($varType == 'boolean') {
                return $var;
            } else if (is_array($var)) {
                $tmp = array();
                foreach ($var as $k => $v) {
                    $tmp[$k] = $this->protect($v);
                }
                return $tmp;
            } else if ($varType == 'object') {
                $tmp = clone $var;
                foreach ($var as $k => $v) {
                    $tmp->{$k} = $this->protect($v);
                }
                return $tmp;
            } else if ($varType == 'resource') {
                return $var;
            } else {
                echo "FUN->protect: $varType Não sei lidar com isso";
                die;
            }
        }


        /**
         * removeEspacosDuplicadosEntrePalavras
         * Remove espaços duplicados entre palavras em uma string.
         * @param string $var A string da qual os espaços duplicados entre palavras serão removidos.
         * @return string A string resultante após a remoção dos espaços duplicados entre palavras.
         */
        public function removeEspacosDuplicadosEntrePalavras($var) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            return preg_replace('/ +/', ' ', ($var?: ""));
        }


        /**
         * somenteLetras
         * Remove todos os caracteres exceto letras (incluindo acentuadas) de uma string.
         * @param string $str A string da qual os caracteres não-alfabéticos serão removidos.
         * @param string $replaceWith (opcional) A string que substituirá os caracteres removidos (padrão é uma string vazia).
         * @return string A string resultante contendo apenas letras (incluindo acentuadas) ou caracteres substituídos.
         */
        public function somenteLetras($str, $replaceWith = "") {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            //return preg_replace("[^A-Za-z ]", "", $str);
            return preg_replace("/[^A-Za-záàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/", $replaceWith, ($str?: ""));
        }


        /**
         * somenteLetrasEEspacos
         * Remove todos os caracteres exceto letras (incluindo acentuadas) e espaços de uma string.
         * @param string $str A string da qual os caracteres não-alfabéticos e não-espaços serão removidos.
         * @param string $replaceWith (opcional) A string que substituirá os caracteres removidos (padrão é uma string vazia).
         * @return string A string resultante contendo apenas letras (incluindo acentuadas) e espaços ou caracteres substituídos.
         */
        public function somenteLetrasEEspacos($str, $replaceWith = "") {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            //return preg_replace("[^A-Za-z ]", "", $str);
            return preg_replace("/[^A-Za-z[:space:]áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/", $replaceWith, ($str?: ""));
        }


        /**
         * somenteLetrasENumeros
         * Remove todos os caracteres exceto letras (incluindo acentuadas) e números de uma string.
         * @param string $str A string da qual os caracteres não-alfanuméricos serão removidos.
         * @param string $replaceWith (opcional) A string que substituirá os caracteres removidos (padrão é uma string vazia).
         * @return string A string resultante contendo apenas letras (incluindo acentuadas) e números ou caracteres substituídos.
         */
        public function somenteLetrasENumeros($str, $replaceWith = "") {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if ($this->DBG) echo "FUN->somenteLetrasENumeros($str) <br>\n";
            if (is_null($str)) return '';
            return preg_replace('/[^a-zA-Z0-9áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ]/', $replaceWith, ($str?: ""));
        }


        /**
         * somenteLetrasNumerosEspacosEChars
         * Remove todos os caracteres exceto letras (incluindo acentuadas), números, espaços e caracteres especiais (',', '-', '.', ',' e outros) de uma string.
         * @param string $str A string da qual os caracteres não permitidos serão removidos.
         * @param string $replaceWith (opcional) A string que substituirá os caracteres removidos (padrão é uma string vazia).
         * @return string A string resultante contendo apenas letras (incluindo acentuadas), números, espaços e caracteres especiais
         *               ou caracteres substituídos.
         */
        public function somenteLetrasNumerosEspacosEChars($str, $replaceWith = "") {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if ($this->DBG) echo "FUN->somenteLetrasNumerosEspacosEChars($str) <br>\n";
            if (is_null($str)) return '';
            $str = preg_replace('/[\x00-\x1F\x80-\xFF]/', $replaceWith, ($str?: ""));
            $str = str_replace('\\', $replaceWith, $str);
            return preg_replace("/[^A-Za-z0-9[:space:]áàãâÁÀÃÂéèêÉÈÊíÍóõÓÕúÚçÇ,.-]/", $replaceWith, ($str?: ""));
        }


        /**
         * tirarAcentos
         * Remove os acentos e caracteres especiais de uma string.
         * @param string $string A string da qual os acentos e caracteres especiais serão removidos.
         * @return string A string resultante após a remoção dos acentos e caracteres especiais.
         */
        public function tirarAcentos($string) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if ($this->DBG) echo "FUN->tirarAcentos($string) <br>\n";
            if (is_null($string)) return '';
            return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(Ç)/", "/(ç)/"), explode(" ", "a A e E i I o O u U n N C c"), ($string?: ""));
        }


        /**
         * limpezaStringUtf8
         * Remove caracteres especiais, de controle e não-imprimíveis de uma string codificada em UTF-8.
         * @param string $str A string da qual os caracteres não permitidos serão removidos.
         * @param string $replaceWith (opcional) A string que substituirá os caracteres removidos (padrão é uma string vazia).
         * @return string A string resultante após a remoção dos caracteres especiais, de controle e não-imprimíveis.
         */
        public function limpezaStringUtf8($str, $replaceWith = '') {
            if (is_null($str)) return '';
            $str = preg_replace('/[\x00-\x1F\x80-\xFF]/', $replaceWith, ($str?: ""));
            $str = str_replace('\\', $replaceWith, $str);
            $str = preg_replace('/[[:cntrl:]]/', '', ($str?: ""));
            return $str;
        }


        /**
         * formatarNome
         * Formata um nome removendo espaços duplicados, acentos e caracteres não permitidos, tornando-o maiúsculo.
         * @param string $nome O nome a ser formatado.
         * @return string O nome formatado após a remoção de espaços duplicados, acentos e caracteres não permitidos, em maiúsculas.
         */
        public function formatarNome($nome) {

            $nome = strtoupper($nome);
            $nome = $this->removeEspacosDuplicadosEntrePalavras($nome);
            $nome = trim($nome);
            $nome = $this->tirarAcentos($nome);
            $nome = $this->somenteLetrasEEspacos($nome);

            return $nome;
        }


        /**
         * isJson
         * Verifica se a string é JSON
         * @param  string  $string texto a decodificar
         * @return boolean true para JSON
         */
        public function isJson($string) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $alphapha = json_decode($string);
            if ($this->DBG) echo "<pre>OBJ: " . print_r($alphapha, 1) . "</pre>";
            $beterraba = json_last_error();
            if ($beterraba != JSON_ERROR_NONE) {
                if ($this->DBG) echo "<pre>ERRO: " . print_r($beterraba, 1) . "</pre>";
                $this->setMensagem('isJson ->' . $this->decodeJsonError($beterraba));
            }
            return json_last_error() === JSON_ERROR_NONE;
        }


        /**
         * decodeJsonError
         * Converte um código de erro JSON em uma mensagem descritiva.
         * @param int $jsonLastError O código de erro JSON a ser convertido.
         * @return string A mensagem descritiva correspondente ao código de erro JSON.
         */
        public function decodeJsonError($jsonLastError) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            switch ($jsonLastError) {
                case JSON_ERROR_NONE:
                    return 'No error has occurred';
                    break;
                case JSON_ERROR_DEPTH:
                    return 'The maximum stack depth has been exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    return 'Invalid or malformed JSON';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    return 'Control character error, possibly incorrectly encoded';
                    break;
                case JSON_ERROR_SYNTAX:
                    return 'Syntax error';
                    break;
                case JSON_ERROR_UTF8:
                    return 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                case JSON_ERROR_RECURSION:
                    return 'One or more recursive references in the value to be encoded';
                    break;
                case JSON_ERROR_INF_OR_NAN:
                    return 'One or more NAN or INF values in the value to be encoded';
                    break;
                case JSON_ERROR_UNSUPPORTED_TYPE:
                    return 'A value of a type that cannot be encoded was given';
                    break;
                case JSON_ERROR_INVALID_PROPERTY_NAME:
                    return 'A property name that cannot be encoded was given';
                    break;
                case JSON_ERROR_UTF16:
                    return 'Malformed UTF-16 characters, possibly incorrectly encoded';
                    break;
            }
            /*
            echo 'JSON_ERROR_NONE: '.JSON_ERROR_NONE;
            echo 'JSON_ERROR_DEPTH: '.JSON_ERROR_DEPTH;
            echo 'JSON_ERROR_STATE_MISMATCH: '.JSON_ERROR_STATE_MISMATCH;
            echo 'JSON_ERROR_CTRL_CHAR: '.JSON_ERROR_CTRL_CHAR;
            echo 'JSON_ERROR_SYNTAX: '.JSON_ERROR_SYNTAX;
            echo 'JSON_ERROR_UTF8: '.JSON_ERROR_UTF8;
            echo 'JSON_ERROR_RECURSION: '.JSON_ERROR_RECURSION;
            echo 'JSON_ERROR_INF_OR_NAN: '.JSON_ERROR_INF_OR_NAN;
            echo 'JSON_ERROR_UNSUPPORTED_TYPE: '.JSON_ERROR_UNSUPPORTED_TYPE;
            echo 'JSON_ERROR_INVALID_PROPERTY_NAME: '.JSON_ERROR_INVALID_PROPERTY_NAME;
            echo 'JSON_ERROR_UTF16: '.JSON_ERROR_UTF16;
            */
        }

        /**
         * NUMEROS
         **/
        private $masks = array(
            'CPF' => '###.###.###-##',
            'CNPJ' => '##.###.###/####-##',
            'CEP' => '##.###-###',
            'RG' => '##.###.###-#',
        );


        /**
         * mask
         * Aplica uma máscara a um valor, substituindo caracteres de acordo com a máscara especificada.
         * @param string $val O valor a ser mascarado.
         * @param string $mask A máscara a ser aplicada ao valor.
         * @return string O valor mascarado após a aplicação da máscara.
         */
        public function mask($val, $mask) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());

            $_mask = $mask;
            $mask = $this->masks[$mask];

            if (!$_mask) return "NO MASK DESC mask($val, $mask)";
            if (!$mask) return "NO MASK AVALIABLE mask($val, $mask)";
            if (!$val) return str_replace("#", " ", $mask);

            //echo "mask($val,$_mask=> ".$mask.")";

            $maskared = '';
            $k = 0;
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k]))
                        $maskared .= $val[$k++];
                } else {
                    if (isset($mask[$i]))
                        $maskared .= $mask[$i];
                }
            }
            if ($maskared) return $maskared;
            return $val;
        }


        /**
         * dinheiroPrint
         * Formata um valor monetário exibindo-o com a quantidade de casas decimais especificada.
         * @param float $valor O valor monetário a ser formatado.
         * @param int $casasDecimais (opcional) O número de casas decimais a ser exibido (padrão é 2).
         * @return string O valor monetário formatado com a quantidade de casas decimais especificada.
         */
        public function dinheiroPrint($valor, $casasDescimais = 2) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (!$valor) return '0,00';
            $valor = str_replace(",", ".", $valor);

            $margemCorte = pow(10, $casasDescimais);
            $valor_u = $valor * $margemCorte;
            $temp_arr = explode(".", $valor_u);
            $valor = $temp_arr[0] / $margemCorte;

            return number_format(str_replace(',', '.', $valor), $casasDescimais, ",", ".");
        }


        /**
         * dinheiroSemMascara
         * Remove a formatação de máscara e retorna o valor monetário como uma sequência de dígitos.
         * @param float $valor O valor monetário a ser desmascarado.
         * @return string O valor monetário sem a formatação de máscara, como uma sequência de dígitos.
         */
        public function dinheiroSemMascara($valor) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $valor = dinheiroPrint($valor);
            $valor = str_replace(",", "", $valor);
            $valor = str_replace(".", "", $valor);
            return $valor;
        }


        /**
         * somenteNumeros
         * Remove todos os caracteres não numéricos de uma string.
         * @param string|null $value A string da qual os caracteres não numéricos serão removidos.
         * @return string A string resultante contendo apenas os caracteres numéricos ou uma string vazia se for nula.
         */
        public function somenteNumeros($value) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $val2 = $value !== null ? preg_replace('/[^0-9]/', '', ($value?: "")) : '';
            if ($this->DBG) {
                echo "Returning $val2 <br>\n";
            }
            return $val2;
        }


        /**
         * somenteNumerosPontosEVirgulas
         * Remove todos os caracteres não numéricos, pontos e vírgulas de uma string.
         * @param string $value A string da qual os caracteres não numéricos, pontos e vírgulas serão removidos.
         * @return string A string resultante contendo apenas os caracteres numéricos, pontos e vírgulas.
         */
        public function somenteNumerosPontosEVirgulas($value) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            return preg_replace('/[^0-9],./', '', ($value ?: ""));
        }


        /** Funcao traz o valor de dinheiro mascarado para float.
         * @param mixed dinheiro com mascara
         * @param int opcional -> quantidade de casas depois do ponto decimal
         * @return float
         * https://stackoverflow.com/questions/5139793/unformat-money-when-parsing-in-php */
        public function getAmount($money, $casas = 2) {
            if (!$money) return (float) 0;

            $cleanString = preg_replace('/([^0-9\.,])/i', '', ($money?: ""));
            $onlyNumbersString = preg_replace('/([^0-9])/i', '', ($money?: ""));
            $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

            $removedThousandSeparator = preg_replace('/([.])/', '', $cleanString, ($separatorsCountToBeErased?: -1));

            $convertToFloat = (float) str_replace(',', '.', $removedThousandSeparator);

            return (float) bcadd("$convertToFloat", '0', $casas);
        }

        /**
         * secureDecimal
         *  Funcao recebe um valor, valida como decimal e converte para float.
         * @param mixed valor numerico decimal
         * @param int opcional numero de casas decimais desejadas
         * @return float */
        public function secureDecimal($valor, $casas = 2) {
            if (!$valor) return (float) 0;

            if(is_string($valor) && strpos($valor, ',')){
                return $this->getAmount($valor, $casas);

            }
            return (float) bcadd($valor, 0, $casas);
        }

        /**
         *hashe
         * Gera um hash criptográfico de um valor.
         * @param string $val O valor a ser usado para gerar o hash.
         * @return string O hash criptográfico gerado.
         */
        public function hashe($val) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $hs = sha1(SALT . $val);
            $hs2 = strrev($hs);
            $hs3 = substr($hs2, 3, 5);
            $hs4 = strtoupper($hs3);
            return $hs4;
        }

        /**
         * firstitem
         * Retorna o primeiro elemento de um array.
         * @param array $arr O array do qual deseja-se obter o primeiro elemento.
         * @return mixed|null Retorna o primeiro elemento do array ou null se o array estiver vazio.
         */
        public function firstitem($arr) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            return reset($arr);
        }


        /**
         * utf8Encode
         * Converte uma variável ou array para a codificação UTF-8.
         * @param mixed $var A variável ou array a ser convertido para UTF-8.
         * @return mixed A variável ou array convertido para a codificação UTF-8.
         */
        public function utf8Encode($var) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (is_array($var)) {
                $_arr = array();
                foreach ($var as $key => $value) {
                    $_arr[$this->utf8Encode($key)] = $this->utf8Encode($value);
                }
                return $_arr;
            } else {
                //return utf8_encode($var);
                $target = 'UTF-8';
                if ($translit) $target .= '//TRANSLIT';
                return iconv('ISO-8859-1', $target, $var);
            }
        }


        /**
         * utf8Decode
         * Converte uma variável ou array da codificação UTF-8 para ISO-8859-1.
         * @param mixed $var A variável ou array a ser convertido de UTF-8 para ISO-8859-1.
         * @return mixed A variável ou array convertido de UTF-8 para ISO-8859-1.
         */
        public function utf8Decode($var) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (is_array($var)) {
                $_arr = array();
                foreach ($var as $key => $value) {
                    $_arr[$this->utf8Decode($key)] = $this->utf8Decode($value);
                }
                return $_arr;
            } else {
                //return utf8_decode($var);
                $target = 'ISO-8859-1';
                if ($translit) $target .= '//TRANSLIT';
                return iconv('UTF-8', $target, $var);
            }
        }

        /**DATA**/
        public $arr_dias = array(
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

        public function validaHora($hr) {
            return $this->validarHora($hr);
        }


        /**
         * validarHora
         * Valida uma string no formato de hora (HH:mm:ss) para garantir que representa uma hora válida.
         * @param string $hr A string contendo a hora a ser validada (no formato HH:mm:ss).
         * @return bool Retorna true se a hora for válida, caso contrário, retorna false.
         */
        public function validarHora($hr) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (!$hr) return false;
            $_arr = explode(" ", $hr);
            $hr = (isset($_arr[1]) ? $_arr[1] : $_arr[0]);
            $arr_hr = explode(":", $hr);
            $h = $arr_hr[0];
            $m = $arr_hr[1];
            $s = $arr_hr[2];
            if (($h < 0) or ($h > 23)) {
                if ($this->DBG) {
                    echo "hora $h não passou\n";
                }
                return false;
            }
            if (($m < 0) or ($m > 59)) {
                if ($this->DBG) {
                    echo "minuto $m não passou\n";
                }
                return false;
            }
            if (($s < 0) or ($s > 59)) {
                if ($this->DBG) {
                    echo "segundo $s não passou\n";
                }
                return false;
            }
            return true;
        }


        /**
         * normalizaHora
         * Normaliza uma string no formato de hora (HH:mm:ss) para garantir que representa uma hora válida.
         * @param string $str A string contendo a hora a ser normalizada (no formato HH:mm:ss).
         * @return string A string da hora normalizada, ou '00:00:00' se a hora não for válida.
         */
        public function normalizaHora($str) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $failSafe = '00:00:00';

            if (!$str) return $failSafe;

            $hora = preg_replace('/[^0-9]/', " ", ($str?: ""));
            $arr = explode(' ', $hora);
            $h = $arr[0];
            $m = $arr[1];
            $s = $arr[2];

            if (($h < 0) or ($h > 23)) {
                $h = 0;
            }
            if (($m < 0) or ($m > 59)) {
                $m = 0;
            }
            if (($s < 0) or ($s > 59)) {
                $s = 0;
            }

            $horaFinal = $this->pad($h, 2) . ":" . $this->pad($m, 2) . ":" . $this->pad($s, 2);
            if ($this->validarHora($horaFinal)) {
                return $horaFinal;
            }

            return $failSafe;
        }

        public function validaData($dt) {
            return $this->validarData($dt);
        }


        /**
         * validarData
         * Valida uma string no formato de data (dd/mm/aaaa) para garantir que representa uma data válida.
         * @param string $dt A string contendo a data a ser validada (no formato dd/mm/aaaa).
         * @return bool Retorna true se a data for válida, caso contrário, retorna false.
         */
        public function validarData($dt) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());

            $_arr = explode(" ", $dt);
            if ($this->DBG) {
                echo "FUN->validarData: explode: " . print_r($_arr, 1) . " <br>\n";
            }
            $data = $_arr[0];
            if ($this->DBG) {
                echo "FUN->validarData: data: $data  <br>\n";
            }

            if (!strpos($dt, "/")) {
                if ($this->DBG) {
                    echo "FUN->validarData:  nao tem barra  <br>\n";
                }
                if (!strpos($dt, "-")) {
                    if ($this->DBG) {
                        echo "FUN->validarData:  nao tem traço  <br>\n";
                    }
                    return  false; //procura a barra
                } else {
                    if ($this->DBG) {
                        echo "FUN->validarData:  trocar traço por barra  <br>\n";
                    }
                    $data = implode('/', array_reverse(explode('-', $data)));
                }
            }


            if ($this->DBG) {
                echo "FUN->validarData: data: $data  <br>\n";
            }
            $_arr2 = explode("/", $data);
            if ($this->DBG) {
                echo "FUN->validarData: explode: " . print_r($_arr2, 1) . "  <br>\n";
            }

            $dd = intval($_arr2[0]);
            $mm = intval($_arr2[1]);
            $aaaa = $_arr2[2];

            if (($dd < 1 or $dd > 31)) {
                if ($this->DBG) {
                    echo "dia entre 1 e 31  <br>\n";
                }
                return false;
            }
            if (($mm < 1 or $mm > 12)) {
                if ($this->DBG) {
                    echo "mes entre 1 e 12  <br>\n";
                }
                return false;
            }
            if (($aaaa < 1900 or $aaaa > 2100)) {
                if ($this->DBG) {
                    echo "ano entre 1899 e 2100  <br>\n";
                }
                return false;
            }
            if ($dd > 29 and $mm == 2) { //fev
                if ($this->DBG) {
                    echo "fev +29 dias  <br>\n";
                }
                return false;
            }
            if ($dd == 31 and in_array($mm, array(4, 6, 9, 11))) { //dia 31
                if ($this->DBG) {
                    echo "mes nao tem 31  <br>\n";
                }
                return false;
            }
            $arr_bisextos = array(2000, 2004, 2008, 2012, 2016, 2020, 2024, 2028, 2032, 2036, 2040, 2044, 2048, 2052);
            if ($dd == 29 and $mm == 2 and !in_array($aaaa, $arr_bisextos)) { //bisextos
                if ($this->DBG) {
                    echo "29 nao bisexto  <br>\n";
                }
                return false;
            }

            return true;
        }

        /**
         * normalizaData
         * Normaliza uma string em formato de data.
         * @param string|null $str A string que representa a data.
         * @return string|null Retorna a data normalizada no formato 'YYYY-MM-DD HH:MM:SS' ou null em caso de falha.
         * @throws \Exception Se a data for inválida.
         */
        public function normalizaData($str) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());

            if (is_null($str) or strlen($str) < 9) {
                if ($this->DBG) echo "FUN->normalizaData: < 9 char; return null <br>\n";
                return null;
            }
            //separar data e hora
            if (strlen($str) > 10) {
                if ($this->DBG) echo "FUN->normalizaData: +10 char <br>\n";
                $data = substr($str, 0, 10);
                $hora = substr($str, 11, 8);

                return $this->normalizaData($data) . " " . $this->normalizaHora($hora);
            } else {
                $failSafe = date('Y-m-d');

                $dt = preg_replace('/[^0-9]/', " ", ($str?: ""));
                $arr = explode(' ', $dt);

                //procurar o ano
                if (strlen($arr[0]) == 4) {
                    if ($this->DBG) echo "FUN->normalizaData: ano no 1º elemento<br>\n";
                    $y = $arr[0];

                    //normalmente está yyyy-mm-dd
                    if ($arr[1] > 12) { //era pra ser o mes aqui
                        if ($this->DBG) echo "FUN->normalizaData: 2º elemento maior que 12 <br>\n";
                        if ($arr[2] > 12) { //tem erro aqui
                            if ($this->DBG) echo "FUN->normalizaData: 3º elemento maior que 12 ERRO <br>\n";
                            throw new \Exception("normalizaData: ERRO Data inválida: $str ");
                            return $failSafe;
                        } else {
                            $m = $arr[2];
                            $d = $arr[1];
                        }
                    } else { //ok é o mes mesmo
                        $m = $arr[1];
                        $d = $arr[2];
                    }
                } else if (strlen($arr[1]) == 4) { //mm-yyyy-dd
                    if ($this->DBG) echo "FUN->normalizaData: ano no 2º elemento<br>\n";
                    $y = $arr[1];

                    if ($arr[0] > 12) { //era pra ser o mes aqui
                        if ($this->DBG) echo "FUN->normalizaData: 1º elemento maior que 12 <br>\n";
                        if ($arr[2] > 12) { //tem erro aqui
                            if ($this->DBG) echo "FUN->normalizaData: 3º elemento maior que 12 ERRO <br>\n";
                            throw new \Exception("normalizaData: ERRO Data inválida: $str ");
                            return $failSafe;
                        } else {
                            $m = $arr[2];
                            $d = $arr[0];
                        }
                    } else { //ok é o mes mesmo
                        $m = $arr[0];
                        $d = $arr[2];
                    }
                } else if (strlen($arr[2]) == 4) { //dd/mm/yyyy
                    if ($this->DBG) echo "FUN->normalizaData: ano no 3º elemento<br>\n";
                    $y = $arr[2];

                    if ($arr[1] > 12) { //era pra ser o mes aqui
                        if ($this->DBG) echo "FUN->normalizaData: 2º elemento maior que 12 <br>\n";
                        if ($arr[0] > 12) { //tem erro aqui
                            if ($this->DBG) echo "FUN->normalizaData: 1º elemento maior que 12 ERRO <br>\n";
                            throw new \Exception("normalizaData: ERRO Data inválida: $str ");
                            return $failSafe;
                        } else {
                            $m = $arr[0];
                            $d = $arr[1];
                        }
                    } else { //ok é o mes mesmo
                        $m = $arr[1];
                        $d = $arr[0];
                    }
                } else {
                    if ($this->DBG) echo "FUN->normalizaData: ano nao encontrado <br>\n";
                    throw new \Exception("normalizaData: ERRO Data inválida: $str ");
                    return $failSafe;
                }

                $dataFinal = $y . "-" . $this->pad($m, 2) . "-" . $this->pad($d, 2);
                if ($this->validarData($dataFinal)) {
                    return $dataFinal;
                }

                return $failSafe;
            }
        }


        /**
         * dataPadraoBR
         * Converte uma string de data e hora no formato ISO-8601 para o formato de data e hora padrão brasileiro (dd/mm/aaaa HH:mm:ss).
         * @param string $str A string contendo a data e hora a ser convertida (no formato ISO-8601).
         * @return string|null A string da data e hora convertida para o formato padrão brasileiro, ou null se a entrada for inválida.
         */
        public function dataPadraoBR($str) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $str = $this->normalizaData($str);
            if (!$str) return null;

            if (strlen($str) > 10) {
                $hora = substr($str, 11, 8);
            }
            $data = substr($str, 0, 10);

            $arr = explode('-', $data);
            $arr = array_reverse($arr);
            return implode('/', $arr) . ($hora ? " " . $hora : '');
        }


        /**
         * dataVenceu
         * Verifica se uma data especificada já venceu (é anterior ou igual à data atual).
         * @param string $data A data a ser verificada (no formato ISO-8601).
         * @return bool Retorna true se a data já venceu, caso contrário, retorna false.
         */
        public function dataVenceu($data) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (!$data) return true;
            $data = $this->normalizaData($data);
            $intData = strtr($data, array('-' => ''));
            if (date('Ymd') <= $intData) {
                return false;
            }
            return true;
        }


        /**
         * dataStrMktime
         * Converte uma string de data e hora no formato ISO-8601 em um timestamp Unix utilizando a função mktime.
         * @param string $str A string contendo a data e hora a ser convertida (no formato ISO-8601).
         * @return int|null O timestamp Unix resultante da conversão da data e hora, ou null se a entrada for inválida.
         */
        private function dataStrMktime($str) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());

            //mktime ($hour,$minute,$second,$month,$day,$year)
            //$failSafe = mktime (date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"));
            $failSafe = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
            if (!$str) {
                if ($this->DBG) echo "FUN->dataStrMktime: sem parametro retornando failSafe: $failSafe<br>\n";
                return $failSafe;
            }

            $str = $this->normalizaData($str);
            if (strlen($str) > 10) {
                if ($this->DBG) echo "FUN->dataStrMktime: horas encontradas <br>\n";
                $data = substr($str, 0, 10);
                $hora = substr($str, 11, 8);

                $h = substr($hora, 0, 2);
                $m = substr($hora, 3, 2);
                $s = substr($hora, 6, 2);
            } else {
                if ($this->DBG) echo "FUN->dataStrMktime: horas não encontradas, zerar <br>\n";
                $data = substr($str, 0, 10);

                $h = '00';
                $m = '00';
                $s = '00';
            }

            $arr_d = explode("-", $data);
            if ($arr_d[1] and $arr_d[2] and $arr_d[0])
                return mktime($h, $m, $s, $arr_d[1], $arr_d[2], $arr_d[0]);
            return null;
        }

        /**
         * diaDaSemana
         * Retorna o dia da semana para uma data específica.
         * @param string|null $data A data para a qual o dia da semana deve ser determinado.
         * @return string|null O dia da semana correspondente à data ou null em caso de falha.
         */
        public function diaDaSemana($data) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (!$data) return null;
            $data = $this->normalizaData($data);
            $data = $this->homeExplode(' ', $data);
            $arr_d = explode("-", $data);
            $d = date('w', mktime(0, 0, 0, $arr_d[1], $arr_d[2], $arr_d[0]));
            return $this->arr_dias[$d];
        }

        /**
         * diaDaSemanaNumero
         * Retorna o número do dia da semana para uma data específica.
         * @param string|null $data A data para a qual o número do dia da semana deve ser determinado.
         * @return int|null O número do dia da semana correspondente à data (0 para Domingo, 1 para Segunda, etc.) ou null em caso de falha.
         */
        public function diaDaSemanaNumero($data) {
            if ($this->DBG) {
                echo __FILE__ . ":" . __LINE__ . " <br>\n" . __CLASS__ . "->" . __FUNCTION__ . "<xmp>" . print_r(func_get_args(), 1) . "</xmp><br>\n";
            }
            if (!$data) return null;
            $data = $this->normalizaData($data);
            $data = $this->homeExplode(' ', $data);
            $arr_d = explode("-", $data);
            $d = date('w', mktime(0, 0, 0, $arr_d[1], $arr_d[2], $arr_d[0]));
            return $d;
        }

        /**
         * diferencaDias
         * Calcula a diferença em dias entre duas datas.
         * @param string $data1 A primeira data.
         * @param string|null $data2 A segunda data (pode ser nula, caso em que a data atual será usada).
         * @return int A diferença em dias entre as duas datas.
         */
        public function diferencaDias($data1, $data2 = null) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());

            $date1 = $this->dataStrMktime($data1);
            $date2 = $this->dataStrMktime($data2);
            if ($this->DBG) echo "FUN->diferencaDias $date1 - $date2  <br>\n";

            $diff = $date1 - $date2;
            if ($this->DBG) echo "FUN->diferencaDias $diff <br>\n";

            $fullDays = floor($diff / (60 * 60 * 24));
            if ($this->DBG) echo "FUN->diferencaDias $fullDays <br>\n";

            return $fullDays;
        }

        /**
         * diferencaHoras
         * Calcula a diferença em minutos entre duas horas.
         * @param string $hora1 A primeira hora no formato HH:MM.
         * @param string|false $hora2 A segunda hora no formato HH:MM (pode ser falso, caso em que a hora atual será usada).
         * @return int A diferença em minutos entre as duas horas.
         */
        public function diferencaHoras($hora1, $hora2 = false) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $hh1 = substr($hora1, 0, 2);
            $hm1 = substr($hora1, 3, 2);

            $hh2 = substr($hora2, 0, 2);
            $hm2 = substr($hora2, 3, 2);

            $hora1 = $hh1 * 60 + $hm1;
            $hora2 = $hh2 * 60 + $hm2;

            $diff = $hora1 - $hora2;

            if ($diff > 1000) {
                $diff = $diff - (24 * 60);
            }
            if ($diff < -1000) {
                $diff = $diff + (24 * 60);
            }

            return $diff;
        }

        /**
         * endTime
         * Calcula o tempo decorrido desde um timestamp até o momento atual e retorna em formato legível.
         * @param int $inp O timestamp de início.
         * @return string O tempo decorrido no formato legível.
         */
        public function endTime($inp) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $fnp = time();
            $tp = $fnp - $inp;
            return $this->time_distance($tp);
        }


        /**
         * getMes
         * Obtém o nome completo ou o número do mês com base no valor fornecido.
         * @param int|string $mes O número do mês (1 a 12) ou o nome do mês (em inglês ou português) a ser obtido.
         * @return int|string|null O nome completo do mês se um número foi fornecido, ou o número do mês se um nome foi fornecido. Retorna null se o valor fornecido não corresponde a um mês válido.
         */
        public function getMes($mes) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (!$mes) return null;

            if (is_numeric($mes)) {
                $mes = $this->pad($mes, 2);

                foreach ($this->arr_meses as $num => $nome) {
                    if ($mes == $num)
                        return $nome;
                }
            } else {
                $mes = strtolower($mes);
                foreach ($this->arr_meses as $num => $nome) {
                    if ($mes == strtolower($nome))
                        return $num;
                }
            }
            return null;
        }


        /**
         * time_distance
         * Converte um intervalo de tempo em segundos em uma string legível que representa a diferença de tempo.
         * @param int $secs O intervalo de tempo em segundos.
         * @return string A representação legível da diferença de tempo, por exemplo: "2 anos, 3 semanas e 4 dias".
         */
        public function time_distance($secs) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $bit = array(
                ' ano'        => intval($secs / 31556926) % 12,
                ' semana'     => intval($secs / 604800) % 52,
                ' dia'        => intval($secs / 86400) % 7,
                ' hora'       => intval($secs / 3600) % 24,
                ' minuto'     => intval($secs / 60) % 60,
                ' segundo'    => $secs % 60
            );

            $ret = array();
            foreach ($bit as $k => $v) {
                if ($v > 1) $ret[] = $v . $k . 's';
                if ($v == 1) $ret[] = $v . $k;
            }

            $str = join(', ', $ret);
            $str = preg_replace('/(,(?!.*,))/', ' e', ($str?: ""));

            //debug($str);

            if ($str) return $str;
            return "0 segundos";
        }


        /**
         * getTempoDistancia
         * Calcula a diferença de tempo entre duas datas e retorna uma string legível que representa a distância de tempo entre elas.
         * @param string $dt1 A primeira data (no formato ISO-8601) para calcular a diferença de tempo.
         * @param string|false $dt2 A segunda data (no formato ISO-8601) para calcular a diferença de tempo. Se não fornecida, a data atual é usada.
         * @return string A representação legível da diferença de tempo entre as duas datas, por exemplo: "2 anos, 3 semanas e 4 dias".
         */
        public function getTempoDistancia($dt1, $dt2 = false) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $unix1 = $this->dataStrMktime($dt1);
            $unix2 = $this->dataStrMktime($dt2);

            if ($unix1 > $unix2) {
                $dif = $unix1 - $unix2;
            } else {
                $dif = $unix2 - $unix1;
            }
            return $this->time_distance($dif);
        }

        /**DOCUMENTO**/

        /**
         * fixPlaca
         * Formata uma placa de carro, incluindo o padrão Mercosul.
         * @param string $placa A placa de carro a ser formatada.
         * @return string|bool A placa formatada no padrão Mercosul, ou false se a formatação não for bem-sucedida.
         */
        function fixPlaca($placa) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if ($this->DBG) echo "FUN->fixPlaca($placa) <br>\n";
            $placa = $this->tirarAcentos($placa);
            $placa = strtoupper($this->somenteLetrasENumeros($placa));
            if ($this->DBG) echo "FUN->fixPlaca: $placa <br>\n";
            $p1 =  substr($placa, 0, 3);
            $p1 = preg_replace("/[^A-Z]/", "", ($p1?: ""));

            $p2 = substr($placa, 3, 1);
            $p2 = $this->somenteNumeros($p2);

            $p3 = substr($placa, 4, 1);
            $p3 = $this->somenteLetrasENumeros($p3);

            $p4 = substr($placa, 5, 2);
            $p4 = $this->somenteNumeros($p4);

            $placa = $p1 . "-" . $p2 . $p3 . $p4;
            if (strlen($placa) != 8) return false;
            return $placa;
        }

        /**
         * protectMail
         * Limpa caracteres inválidos de um endereço de email.
         * @param string $email O endereço de email a ser limpo.
         * @return string O endereço de email com caracteres inválidos removidos e limitado a 255 caracteres.
         */
        public function protectMail($email) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            $email = $this->tirarAcentos($email);
            $email = preg_replace("/[^A-Za-z0-9@.#+-_]/", "", ($email?: ""));
            $email = substr($email, 0, 255);

            return $email;
        }


        /**
         * embaralhaEmail
         * Embaralha partes do endereço de e-mail substituindo caracteres por '*' para dificultar a leitura automatizada.
         * @param string $email O endereço de e-mail a ser embaralhado.
         * @return string O endereço de e-mail com partes embaralhadas.
         */
        public function embaralhaEmail($email) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (!$email) return '';

            $a = 0;
            $fator = 3;
            $max = strlen($email);
            for ($i = 0; $i <= $max; $i++) {
                $a++;
                if (preg_match("/[A-Za-z0-9]/", substr($email, $i, 1))) {
                    if ($a == $fator) {
                        $email = substr_replace($email, '*', $i, 1);
                        $a = 0;
                        if ($fator == 3) {
                            $fator = 2;
                        } else {
                            $fator = 3;
                        }
                    }
                } else {
                    $a--;
                }
            }

            return $email;
        }

        /**
         * protectNome
         * Protege o nome ocultando parte dele, substituindo por asteriscos.
         * @param string $nome O nome a ser protegido.
         * @return string O nome protegido, onde parte do nome é substituída por asteriscos.
         */
        function protectNome($nome) {
            return $this->embaralhaNome($nome);
        }
        /**
         * embaralhaNome
         * Embaralha o nome ocultando parte dele, substituindo por asteriscos.
         * @param string $nome O nome a ser embaralhado.
         * @return string O nome embaralhado, onde parte do nome é substituída por asteriscos.
         */
        function embaralhaNome($nome) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (!$nome) return '';
            $arr = explode(' ', $nome);

            $firstName = array_shift($arr);
            $midleNames = '';
            $lastName = '';

            $firstName = substr($firstName, 0, 2) . pad('', strlen($firstName) - 2, '*');

            if (sizeof($arr)) {
                $lastName = array_pop($arr);
                $lastName = substr($lastName, 0, 2) . pad('', strlen($lastName) - 2, '*');
            }

            if (sizeof($arr)) {
                $arr2 = array();
                foreach ($arr as $key => $value) {
                    $arr2[] = substr($value, 0, 1) . pad('', strlen($value) - 1, '*');
                }
                $midleNames = implode(' ', $arr2);
            }

            return $firstName . ($midleNames ? ' ' . $midleNames : '') . ($lastName ? ' ' . $lastName : '');
        }

        /************/
        /*VALIDACOES*/
        /************/

        /**
         * validaEmail
         * Valida um endereço de email.
         * @param string $value O endereço de email a ser validado.
         * @return bool Retorna true se o endereço de email for válido, senão retorna false.
         */
        function validaEmail($value) {
            return $this->validarEmail($value);
        }
        function validarEmail($value) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        }

        /**
         * validaNome
         * Valida um nome.
         * @param string $nome O nome a ser validado.
         * @return bool Retorna true se o nome for válido (contém pelo menos duas partes), senão retorna false.
         */
        function validaNome($nome) {
            return $this->validarNome($nome);
        }
        function validarNome($nome) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if (!$nome) return false;
            if (sizeof(explode(' ', $nome)) < 2) return false;
            return true;
        }

        /**
         * validaCPFCNPJ
         * Valida um CPF ou CNPJ.
         * @param string $str O CPF ou CNPJ a ser validado.
         * @return bool Retorna true se o CPF ou CNPJ for válido, senão retorna false.
         */
        function validaCPFCNPJ($str = '') {
            return $this->validarCPFCNPJ($str);
        }
        function validarCPFCNPJ($str = '') {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if ($this->validarCPF($str)) return true;
            if ($this->validarCNPJ($str)) return true;
            return false;
        }

        /**
         * validaCPF
         *  Faz o callback da função validarCPF
         */
        function validaCPF($cpf) {
            return $this->validarCPF($cpf);
        }
        /**
         * validarCPF
         * @param  [mixed] $cpf CPF com ou sem mascara
         * @return [bool] true para válido
         */
        function validarCPF($cpf) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            if ($cpf) {
                if ($DBG) echo $cpf . "<br>";
                $cpf = preg_replace('/[^0-9]/is', '', ($cpf?: ""));
                if ($DBG) echo $cpf . "<br>";

                if (strlen($cpf) != 11) {
                    if ($DBG) echo "nao tem 11 char<br>";
                    return false;
                }

                if (preg_match('/(\d)\1{10}/', $cpf)) {
                    if ($DBG) echo "nao tem numero<br>";
                    return false;
                }

                for ($t = 9; $t < 11; $t++) {
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $cpf[$c] * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;

                    if ($DBG) echo "DV $d<br>";

                    if ($cpf[$c] != $d) {
                        if ($DBG) echo "invalido dv<br>";
                        return false;
                    }
                }
                if ($DBG) echo "valido<br>";
                return true;
            } else {
                return false;
            }
        }


        function validaCNPJ($cnpj) {
            return $this->validarCNPJ($cnpj);
        }
        /**
         * validarCNPJ
         * @param  [mixed] $cnpj CNPJ com ou sem mascara
         * @return [bool]  true para valido
         */
        function validarCNPJ($cnpj) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());

            $cnpj = preg_replace('/[^0-9]/', '', ($cnpj?: ""));

            if ($this->DBG) {
                echo "somente numeros : $cnpj <br>\n";
            }

            // Valida tamanho
            if (strlen($cnpj) != 14) {
                if ($this->DBG) {
                    echo "não tem 14 dig <br>\n";
                }
                return false;
            } else {
                if ($this->DBG) {
                    echo "tem 14 dig <br>\n";
                }
            }

            // Verifica se todos os digitos sao iguais
            if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

            // Valida primeiro di­gito verificador
            for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
                $soma += $cnpj[$i] * $j;
                $j = ($j == 2) ? 9 : $j - 1;
            }

            $resto = $soma % 11;

            if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;

            // Valida segundo digito verificador
            for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
                $soma += $cnpj[$i] * $j;
                $j = ($j == 2) ? 9 : $j - 1;
            }

            $resto = $soma % 11;
            return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
        }

        /**
         * testa se a string é cpf ou cnpj
         * @param  STRING $str CPF CNPJ ou
         * @return STRING CPF/CNPJ/ERR
         */
        public function testarCpfCnpj($str) {
            if ($this->validarCNPJ($str)) return 'CNPJ';
            if ($this->validarCPF($str)) return 'CPF';
            return 'ERR';
        }


        /**LINK**/
        /**
         * getLinkGet
         * Gera uma string de consulta GET para montar um link.
         * @param array|string $arrRemove Parâmetros que devem ser removidos da string de consulta.
         * @return string Retorna a string de consulta GET para montar um link.
         */
        function getLinkGet($arrRemove) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            global $_GET;
            $link = '';
            $arr_get = $_GET;

            if ($arrRemove) {
                if (is_array($arrRemove)) {
                    foreach ($arrRemove as $value) {
                        unset($arr_get[$value]);
                    }
                } else {
                    unset($arr_get[$arrRemove]);
                }
            }

            if (is_array($arr_get)) {
                foreach ($arr_get as $key => $value) {
                    $link .= "&$key=$value";
                }
            }
            return $link;
        }

        /**
         * requerirPermissao
         * Verifica e redireciona se o usuário não tiver as permissões requeridas.
         * @param array|string $perms As permissões requeridas. Pode ser uma string ou um array de strings.
         * @param bool $DBG Se verdadeiro, mostrará mensagens de depuração.
         * @return void
         */
        function requerirPermissao($perms = '', $DBG = false) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            global $_SESSION, $appname, $caminho;

            $permitido = $this->testarPermissao($perms, $DBG);

            $txtPerm = (is_array($perms) ? implode(' ou ', $perms) : $perms);

            if (!$permitido) {
                if ($DBG) echo 'bloqueado ';
                $_SESSION[$appname]['mensagem'] = array("<h4>Sem acesso!</h4> Permissão requerida do tipo: $txtPerm", 'VERMELHO', 'FICAR');
                if (!headers_sent()) {
                    header("Location: $caminho");
                } else {
                    echo $_SESSION[$appname]['mensagem'][0];
                }
                exit();
            }
        }

        /**
         * testarPermissao
         * Testa se o usuário tem as permissões requeridas.
         * @param array|string $perms As permissões requeridas. Pode ser uma string ou um array de strings.
         * @param bool $DBG Se verdadeiro, mostrará mensagens de depuração.
         * @return bool Retorna verdadeiro se o usuário tiver as permissões requeridas, senão retorna falso.
         */
        function testarPermissao($perms = '', $DBG = false) {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            global $_SESSION, $appname, $caminho;

            if ($DBG) echo 'testar acesso. ';

            $permitido = false;
            if (is_array($perms)) {
                if ($DBG) echo 'vetor ';
                $txtPerm = implode(' ou ', $perms);
                foreach ($perms as $key) {
                    if ($DBG) echo "testar $key ";
                    if ($_SESSION[$appname]['PERM'][$key]) {
                        if ($DBG) echo 'liberado ';
                        $permitido = true;
                    }
                }
            } else if ($perms) {
                if ($DBG) echo 'texto ';
                $txtPerm = $perms;
                if ($DBG) echo "testar $perms ";
                if ($_SESSION[$appname]['PERM'][$perms]) {
                    if ($DBG) echo 'liberado ';
                    $permitido = true;
                }
            }
            return $permitido;
        }

        /**
         * requerirPermissao_old
         * Verifica se o usuário tem as permissões requeridas e redireciona em caso negativo.
         * @param array|string $perms As permissões requeridas. Pode ser uma string ou um array de strings.
         * @return void
         */
        function requerirPermissao_old($perms = '') {
            if ($this->DBG) $this->echoDebug(__FILE__, __LINE__, __CLASS__, __FUNCTION__, func_num_args());
            global $db, $_SESSION, $appname, $caminho;
            $DBG = false;

            if ($DBG) echo 'testar acesso. ';

            $permitido = false;
            if (is_array($perms)) {
                if ($DBG) echo 'vetor ';
                $txtPerm = implode(' ou ', $perms);
                foreach ($perms as $key) {
                    if ($DBG) echo "testar $key ";
                    if ($_SESSION[$appname]['PERM'][$key]) {
                        if ($DBG) echo 'liberado ';
                        $permitido = true;
                    }
                }
            } else if ($perms) {
                if ($DBG) echo 'texto ';
                $txtPerm = $perms;
                if ($DBG) echo "testar $perms ";
                if ($_SESSION[$appname]['PERM'][$perms]) {
                    if ($DBG) echo 'liberado ';
                    $permitido = true;
                }
            }

            if (!$permitido) {
                if ($DBG) echo 'bloqueado ';
                $_SESSION[$appname]['mensagem'] = array("<h4>Sem acesso!</h4> Permissão requerida do tipo: $txtPerm", 'VERMELHO', 'FICAR');
                if (!headers_sent()) {
                    header("Location: $caminho");
                } else {
                    echo $_SESSION[$appname]['mensagem'][0];
                }
                exit();
            }
        }

        /*PASTAS E ARQUIVOS*/

        /**
         * testAndCreateFolder
         * Verifica se uma pasta existe e a cria, se necessário.
         * @param string $pasta O caminho completo da pasta a ser verificada e possivelmente criada.
         * @return void
         */
        public function testAndCreateFolder($pasta) {
            try {
                if (!file_exists($pasta) and !is_dir($pasta)) {
                    $arr = explode('/', $pasta);
                    $path = '';
                    foreach ($arr as $i => $p) {
                        $lpath .= "/" . $p;
                        if ($this->DBG) echo "$i,$lpath";
                        if (file_exists($lpath)) {
                            if ($this->DBG) echo " existe, ";
                            if (is_dir($lpath)) {
                                if ($this->DBG) echo " é pasta <br>";
                            } else {
                                if ($this->DBG) echo " <b>não pasta</b> <br>";
                            }
                        } else {
                            if ($this->DBG) echo " <b>não existe</b> <br>";
                            try {
                                if (!mkdir($lpath, 0777, true)) {
                                    echo "Não consigo criar a pasta: $lpath ";
                                }
                            } catch (Exception $e) {
                            }
                            chown($lpath, "www-data");
                        }
                    }
                }
            } catch (Exception $e) {
                trigger_error($e->getMessage() . " " . $pasta, E_USER_WARNING);
            }
        }


        /**
         * delTree
         * Exclui uma árvore de diretórios e arquivos.
         * @param string $dir O caminho do diretório a ser excluído.
         * @return bool Retorna true se a exclusão for bem-sucedida, caso contrário, retorna false.
         */
        public function delTree($dir) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
            }
            return rmdir($dir);
        }

        /**
         * verificaArrayOuJson
         * função testa se a string já está encodada em json e se não estiver encoda
         * @param mixed
         * @return mixed
         */
        public function verificaArrayOuJson($data, $protect = false) {
            return $this->encodaJson($data, $protect);
        }
        public function encodaJson($data, $protect = false) {
            if (is_null($data) or !$data) {
                return '';
            }

            if (is_string($data) && (str_starts_with($data, "[") || str_starts_with($data, "{"))) {
                return $protect ? $this->protect($data) : $data;
            } else {
                return $protect ? json_encode($this->protect($data)) : json_encode($data);
            }
        }

        //Recebe array e retorna média dos valores;
        function calcularMediaArray($array) {
            $valorTotalArray = array_sum($array);

            $quantidadeItensArray = count($array);

            $media = $valorTotalArray / $quantidadeItensArray;

            return $media;
        }
    }
}
