<?php
/* TODO: FIX DEPRECATED
if(!function_exists('validaCPFCNPJ')){
    function validaCPFCNPJ($str=''){
        if(validaCPF($str)) return true;
        if(validaCNPJ($str)) return true;
        return false;
    }
}

if(!function_exists('validaCPF')){
    function validaCPF($cpf) {
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
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;

            if($DBG) echo "DV $d<br>";

            if ($cpf{$c} != $d) {
                if($DBG) echo "invalido dv<br>";
                return false;
            }
        }
        if($DBG) echo "valido<br>";
        return true;
    }
}

if(!function_exists('validaCNPJ')){
    function validaCNPJ($cnpj){
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        // Valida tamanho
        if (strlen($cnpj) != 14) return false;

        // Verifica se todos os digitos sao iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

        // Valida primeiro di­gito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++){
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)) return false;

        // Valida segundo digito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++){
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }
}
*/
if(!function_exists('validarEmail')){
    function validarEmail($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }else{
            return false;
        }
    }
}