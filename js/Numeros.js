/*!
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.5 [2021-set-02]
* @copyleft GPLv3
*/

/**
 * Lib que trata os numeros
 */

class Numeros {

    /**
    *    Funcao preenche com zeros a esquerda o numero
    *    @parameter mixed numero
    *    @parameter int casas
    *    @returns mixed
    */
    static pad(number, length) {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    }

    /**
    *    Funcao preenche os zeros depois da virgula
    *    @parameter mixed numero
    *    @parameter int casas
    *    @returns mixed
    */
    static dinheiroPrint(valor,casasDescimais=2){
        if(!valor) return '0,00';
        valor = valor.toString().replace(",", ".");
        valor = valor * 1

        return valor.toFixed(2).toString().replace(".", ",");
    }

    /**
    *    Funcao transforma texto em numero
    *    @parameter mixed numero
    *    @parameter char separador de miliar que deve ser removido do numero
    *    @parameter separador decimal caso não seja ponto deve ser preenchido
    *    @returns float
    */
    static toNumero(vlr,miliarSep=',',decimalSep='.'){
        if(miliarSep == ','){
            vlr = vlr.replace(/\,/g,'');
        }else if(miliarSep == '.'){
            vlr = vlr.replace(/\./g,'');
        }else{
            vlr = vlr.replace(miliarSep,'');
        }

        vlr = vlr.replace(decimalSep,'.');
        vlr = parseFloat( vlr );

        return vlr;
    }
}


