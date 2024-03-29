<?php
/*! ---UTF-8---
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-nov-12]
* @copyleft GPLv3
*/

class BlackListDoc{

    var $lista = array();
    var $cache_file = LOG.'/BLACK_LIST_DOC.json';

    private static $instance;

    public function  __construct($folder=null){
        if($folder) $this->cache_file = $folder.'/BLACK_LIST_DOC.json';
    }
    //public function  __destruct(){}

    /*public static function singleton(){
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }*/

    public function testarBloqueado($doc,$max=5){
        $this->carregarLista();
        if(!$this->lista[$doc] or sizeof($this->lista[$doc]) < $max){
            return false;
        }
        return true;
    }

    public function add($doc){
        $this->carregarLista();
        $this->lista[$doc][] = date('d/m/Y H:i:s');
        $this->salvarLista();
        return sizeof($this->lista[$doc]);
    }

    public function rem($doc){
        $this->carregarLista();
        unset($this->lista[$doc]);
        $this->salvarLista();
    }

    public function getLista(){
        $this->carregarLista();
        return $this->lista;
    }

    private function salvarLista(){
        file_put_contents($this->cache_file, json_encode($this->lista));
    }

    private function carregarLista(){
        try{
            /* Verificar se: * existe * tem conteudo * é de hoje */
        	if(file_exists($this->cache_file)){
        		if(filesize($this->cache_file) > 1){
        			if( date ("Ymd", filemtime($this->cache_file)) == date('Ymd') ){
        				$contend = file_get_contents($this->cache_file);
        				$done = true;
        			}
        		}
        	}

            if($done){
                $this->lista = json_decode($contend,1);
            }else{
                $this->lista = array();
            }

        }catch (Exception $e1){
            $this->lista = array();
        }
    }
}