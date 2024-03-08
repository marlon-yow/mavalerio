<?php

Class XML_Tools {
    /**
     * transformar o objeto XML em ARRAY
     * @param  OBJ $xmlObject [description]
     * @param  array  $out       [description]
     * @return array            [description]
     */

    public function xml2array ( $xmlObject, $out = array () ){
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;
        return $out;
    }

    /**
     * arrumar vetor para normalizar Documento e saber o tipo de documento
     * @param  array $arr [description]
     * @return array $arr [description]
     */

    public function getDocDest($arr){
        if(!$arr) return array();
        if($arr['CPF']){
            $arr['tipo'] = '01';
            $arr['doc'] = $arr['CPF'];
        }else if($arr['CNPJ']){
            $arr['tipo'] = '02';
            $arr['doc'] = $arr['CNPJ'];
        }

        if(strlen($arr['xFant']) > strlen($arr['xNome'])){
            $arr['nome'] = $arr['xFant'];
        }else{
            $arr['nome'] = $arr['xNome'];
        }

        return $arr;
    }

    public function getTipoDoc($arr){
        if(!$arr) return array();
        if($arr['CPF']){
            return '01';
        }else if($arr['CNPJ']){
            return '02';
        }
    }

}
