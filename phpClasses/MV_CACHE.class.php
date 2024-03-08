<?php
/*! ---UTF-8---
* @version 0.0.0.6 [2023-maio-23]
* @copyleft
*/
namespace mavalerio\phpClasses;

if (!class_exists('mavalerio\phpClasses\MV_CACHE')) {
    class MV_CACHE{
        public $nameItem;
        public $timeTest;
        public $cache_file;
        private $DBG;

        public function __construct($nameItem, $timeTest) {
            $this->nameItem = $nameItem;
            $this->timeTest = $timeTest;
            $this->cache_file = TMP . 'cache/listaDe' . $nameItem . '_cache.json';
        }

        public function getCache(){
            $cache = array();
            /* Verificar se: * existe * tem conteudo * eh de hoje */
            if (file_exists($this->cache_file)) {
                if ($this->DBG) echo "arquivo existe; ";
                if (filesize($this->cache_file) > 1) {
                    if ($this->DBG) echo "arquivo com tamanho; ";
                    if (date($this->timeTest, filemtime($this->cache_file)) == date($this->timeTest) ){
                        if ($this->DBG) echo "arquivo com data valida; ";
                        $cache['listaDe'.$this->nameItem.'_UTF8'] = json_decode(file_get_contents($this->cache_file),1);
                        if($this->DBG) echo "decodificar; ";
                        $cache['listaDe' . $this->nameItem] = utf8Decode($cache['listaDe' . $this->nameItem . '_UTF8']);
                    } else {
                        if ($this->DBG) echo "arquivo sem data valida; ";
                    }
                } else {
                    if ($this->DBG) echo "arquivo sem tamanho; ";
                }
            } else {
                if ($this->DBG) echo "arquivo nao existe; ";
            }
            return $cache;
        }

        public function getCache2() {
            $cache = array();
            /* Verificar se: * existe * tem conteudo * eh de hoje */
            if (file_exists($this->cache_file)) {
                if ($this->DBG)
                    echo "arquivo existe; ";
                if (filesize($this->cache_file) > 1) {
                    if ($this->DBG)
                        echo "arquivo com tamanho; ";
                    if (date($this->timeTest, filemtime($this->cache_file)) < date($this->timeTest)) {
                        if ($this->DBG)
                            echo "arquivo com data valida; ";
                        $cache['listaDe' . $this->nameItem . '_UTF8'] = json_decode(file_get_contents($this->cache_file), 1);
                        if ($this->DBG)
                            echo "decodificar; ";
                        $cache['listaDe' . $this->nameItem] = utf8Decode($cache['listaDe' . $this->nameItem . '_UTF8']);
                    } else {
                        if ($this->DBG)
                            echo "arquivo sem data valida; ";
                    }
                } else {
                    if ($this->DBG)
                        echo "arquivo sem tamanho; ";
                }
            } else {
                if ($this->DBG)
                    echo "arquivo nao existe; ";
            }
            return $cache;
        }

        public function setCache($arr){
            if ($this->DBG) echo "salvar cache; ";
            $this->testAndCreateFolder($this->cache_file);
            $json_encoded_data = json_encode($arr);
            file_put_contents($this->cache_file, $json_encoded_data);
        }

        private function testAndCreateFolder($path){
            if (!file_exists($path)) {
                $_arr = explode('/', $path);
                $pasta = "";
                if ($_arr) foreach ($_arr as $key => $p){
                    if ($p) {
                        if (endExplode('.', $p) != 'json') {
                            $pasta .= "/$p";
                            if (!file_exists($pasta) and !is_dir($pasta)) {
                                mkdir($pasta);
                                chmod($pasta, 0777);
                                //chown($pasta, "apache");
                            }
                        } else {
                            if ($key < sizeof($_arr) - 1) {
                                echo sizeof($_arr) . "> $key<hr> Pasta com PONTO n�o Suportada: $path";
                                die;
                            }
                            if (!file_exists($path)) {
                                touch($path);
                                chmod($path, 0666);
                                //chown($path, "apache");
                            }
                        }
                    }
                }
            } else {
                if (!file_exists($path)) {
                    touch($path);
                    chmod($path, 0666);
                    //chown($path, "apache");
                }
            }
        }

        public function setDebugMode($value = ''){
            if ($value) echo "---DEBUG MODE ATIVADO--- <br>\n";
            $this->DBG = $value;
        }
    }
}